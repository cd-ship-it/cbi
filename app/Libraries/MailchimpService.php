<?php

namespace App\Libraries;

use Config\WebConfig;

class MailchimpService
{
    private $apiKey;
    private $server;
    private $baseUrl;
    private $listId;
    private $segmentId;
    
    public function __construct()
    {
        $webConfig = new WebConfig();
        $this->apiKey = getenv('MAILCHIMP_API_KEY') ?: '';
        $this->server = getenv('MAILCHIMP_SERVER') ?: 'us11';
        $this->baseUrl = "https://{$this->server}.api.mailchimp.com/3.0";
        
        // Get audience and segment IDs (gracefully handle if not found)
        try {
            $this->listId = $this->getAudienceId('XP Members');
            if ($this->listId) {
                $this->segmentId = $this->getSegmentId($this->listId, 'Members');
            }
        } catch (\Exception $e) {
            // Log error but don't fail - allow operations to continue
            log_message('error', 'MailchimpService: Failed to get list/segment IDs - ' . $e->getMessage());
            $this->listId = null;
            $this->segmentId = null;
        }
    }
    
    private function getAudienceId($audienceName)
    {
        $url = "{$this->baseUrl}/lists";
        $result = $this->makeRequest('GET', $url, ['count' => 1000]);
        
        if ($result && $result['success'] && isset($result['data']['lists'])) {
            foreach ($result['data']['lists'] as $list) {
                if ($list['name'] === $audienceName) {
                    return $list['id'];
                }
            }
        }
        return null;
    }
    
    private function getSegmentId($listId, $segmentName)
    {
        $url = "{$this->baseUrl}/lists/{$listId}/segments";
        
        // Try static segments first
        $result = $this->makeRequest('GET', $url, ['count' => 1000, 'type' => 'static']);
        if ($result && $result['success'] && isset($result['data']['segments'])) {
            foreach ($result['data']['segments'] as $segment) {
                if ($segment['name'] === $segmentName) {
                    return $segment['id'];
                }
            }
        }
        
        // Try saved segments
        $result = $this->makeRequest('GET', $url, ['count' => 1000, 'type' => 'saved']);
        if ($result && $result['success'] && isset($result['data']['segments'])) {
            foreach ($result['data']['segments'] as $segment) {
                if ($segment['name'] === $segmentName) {
                    return $segment['id'];
                }
            }
        }
        
        return null;
    }
    
    private function makeRequest($method, $url, $data = [], $headers = [])
    {
        $ch = curl_init();
        
        $defaultHeaders = [
            'Authorization: apikey ' . $this->apiKey,
            'Content-Type: application/json'
        ];
        
        $headers = array_merge($defaultHeaders, $headers);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
            curl_setopt($ch, CURLOPT_URL, $url);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return ['success' => true, 'data' => json_decode($response, true)];
        }
        
        // Capture error details
        $errorDetails = [
            'success' => false,
            'http_code' => $httpCode,
            'curl_error' => $curlError ?: null,
            'response' => null
        ];
        
        if ($response) {
            $errorResponse = json_decode($response, true);
            $errorDetails['response'] = $errorResponse;
            
            // Extract MailChimp error message if available
            if (isset($errorResponse['title'])) {
                $errorDetails['message'] = $errorResponse['title'];
            }
            if (isset($errorResponse['detail'])) {
                $errorDetails['message'] = ($errorDetails['message'] ?? '') . ': ' . $errorResponse['detail'];
            }
            if (isset($errorResponse['errors']) && is_array($errorResponse['errors'])) {
                $errorMessages = [];
                foreach ($errorResponse['errors'] as $error) {
                    if (isset($error['field']) && isset($error['message'])) {
                        $errorMessages[] = $error['field'] . ': ' . $error['message'];
                    } elseif (isset($error['message'])) {
                        $errorMessages[] = $error['message'];
                    }
                }
                if (!empty($errorMessages)) {
                    $errorDetails['message'] = ($errorDetails['message'] ?? '') . ' (' . implode(', ', $errorMessages) . ')';
                }
            }
        }
        
        if (!isset($errorDetails['message'])) {
            $errorDetails['message'] = 'HTTP ' . $httpCode . ($curlError ? ' - ' . $curlError : '');
        }
        
        return $errorDetails;
    }
    
    public function getMemberByEmail($email)
    {
        if (!$this->listId || !$email) {
            return null;
        }
        
        $emailHash = md5(strtolower($email));
        $url = "{$this->baseUrl}/lists/{$this->listId}/members/{$emailHash}";
        
        $result = $this->makeRequest('GET', $url);
        if ($result && $result['success']) {
            return $result['data'];
        }
        return null;
    }
    
    public function createOrUpdateMember($email, $fName, $lName, $status = 'subscribed')
    {
        if (!$this->listId || !$email) {
            return ['success' => false, 'message' => 'Missing list ID or email. List ID: ' . ($this->listId ?: 'null') . ', Email: ' . ($email ?: 'null')];
        }
        
        $emailHash = md5(strtolower($email));
        $url = "{$this->baseUrl}/lists/{$this->listId}/members/{$emailHash}";
        
        $data = [
            'email_address' => strtolower($email),
            'status' => $status,
            'merge_fields' => [
                'FNAME' => $fName,
                'LNAME' => $lName
            ]
        ];
        
        $result = $this->makeRequest('PUT', $url, $data);
        
        if ($result && $result['success']) {
            // Add to segment if status is subscribed and segment exists
            if ($status === 'subscribed' && $this->segmentId) {
                $this->addToSegment($email);
            }
            
            // Add "Member" tag when creating/updating to subscribed
            if ($status === 'subscribed') {
                $this->addTagToMember($email, 'Member');
            }
            
            return ['success' => true, 'data' => $result['data']];
        }
        
        // Return detailed error message
        $errorMsg = 'Failed to create/update member';
        if ($result && isset($result['message'])) {
            $errorMsg .= ': ' . $result['message'];
        }
        if ($result && isset($result['http_code'])) {
            $errorMsg .= ' (HTTP ' . $result['http_code'] . ')';
        }
        if ($result && isset($result['response'])) {
            $errorMsg .= ' [Response: ' . json_encode($result['response']) . ']';
        }
        
        return ['success' => false, 'message' => $errorMsg, 'debug' => $result];
    }
    
    public function updateMemberStatus($email, $status)
    {
        if (!$this->listId || !$email) {
            return ['success' => false, 'message' => 'Missing list ID or email. List ID: ' . ($this->listId ?: 'null') . ', Email: ' . ($email ?: 'null')];
        }
        
        $member = $this->getMemberByEmail($email);
        if (!$member) {
            return ['success' => false, 'message' => 'Member not found in Mailchimp (Email: ' . $email . ', List ID: ' . $this->listId . ')'];
        }
        
        $emailHash = md5(strtolower($email));
        $url = "{$this->baseUrl}/lists/{$this->listId}/members/{$emailHash}";
        
        $data = [
            'status' => $status
        ];
        
        $result = $this->makeRequest('PATCH', $url, $data);
        
        if ($result && $result['success']) {
            // Remove from segment if unsubscribed
            if ($status === 'unsubscribed' && $this->segmentId) {
                $this->removeFromSegment($email);
            }
            
            return ['success' => true, 'data' => $result['data']];
        }
        
        // Return detailed error message
        $errorMsg = 'Failed to update member status';
        if ($result && isset($result['message'])) {
            $errorMsg .= ': ' . $result['message'];
        }
        if ($result && isset($result['http_code'])) {
            $errorMsg .= ' (HTTP ' . $result['http_code'] . ')';
        }
        if ($result && isset($result['response'])) {
            $errorMsg .= ' [Response: ' . json_encode($result['response']) . ']';
        }
        
        return ['success' => false, 'message' => $errorMsg, 'debug' => $result];
    }
    
    public function updateMemberEmail($oldEmail, $newEmail, $fName, $lName)
    {
        if (!$this->listId || !$oldEmail || !$newEmail) {
            return ['success' => false, 'message' => 'Missing required parameters. List ID: ' . ($this->listId ?: 'null') . ', Old Email: ' . ($oldEmail ?: 'null') . ', New Email: ' . ($newEmail ?: 'null')];
        }
        
        $member = $this->getMemberByEmail($oldEmail);
        
        if ($member) {
            // Update existing member
            $emailHash = md5(strtolower($oldEmail));
            $url = "{$this->baseUrl}/lists/{$this->listId}/members/{$emailHash}";
            
            $data = [
                'email_address' => strtolower($newEmail),
                'merge_fields' => [
                    'FNAME' => $fName,
                    'LNAME' => $lName
                ]
            ];
            
            $result = $this->makeRequest('PATCH', $url, $data);
            
            if ($result && $result['success']) {
                return ['success' => true, 'data' => $result['data']];
            }
            
            // Return detailed error message
            $errorMsg = 'Failed to update email';
            if ($result && isset($result['message'])) {
                $errorMsg .= ': ' . $result['message'];
            }
            if ($result && isset($result['http_code'])) {
                $errorMsg .= ' (HTTP ' . $result['http_code'] . ')';
            }
            if ($result && isset($result['response'])) {
                $errorMsg .= ' [Response: ' . json_encode($result['response']) . ']';
            }
            
            return ['success' => false, 'message' => $errorMsg, 'debug' => $result];
        } else {
            // Create new member
            return $this->createOrUpdateMember($newEmail, $fName, $lName, 'subscribed');
        }
    }
    
    private function addToSegment($email)
    {
        if (!$this->listId || !$this->segmentId) {
            return false;
        }
        
        // Check if member is already in segment
        $segmentMembers = $this->getSegmentMembers();
        $emailLower = strtolower($email);
        foreach ($segmentMembers as $member) {
            if (strtolower($member['email_address']) === $emailLower) {
                return true; // Already in segment
            }
        }
        
        $url = "{$this->baseUrl}/lists/{$this->listId}/segments/{$this->segmentId}/members";
        
        $data = [
            'email_address' => strtolower($email)
        ];
        
        $result = $this->makeRequest('POST', $url, $data);
        return $result && $result['success'];
    }
    
    private function getSegmentMembers()
    {
        if (!$this->listId || !$this->segmentId) {
            return [];
        }
        
        $url = "{$this->baseUrl}/lists/{$this->listId}/segments/{$this->segmentId}/members";
        $members = [];
        $offset = 0;
        $count = 1000;
        
        while (true) {
            $result = $this->makeRequest('GET', $url, ['count' => $count, 'offset' => $offset]);
            if (!$result || !$result['success'] || !isset($result['data']['members'])) {
                break;
            }
            
            $segmentMembers = $result['data']['members'];
            if (empty($segmentMembers)) {
                break;
            }
            
            $members = array_merge($members, $segmentMembers);
            
            $totalItems = $result['data']['total_items'] ?? 0;
            if (count($members) >= $totalItems) {
                break;
            }
            
            $offset += $count;
        }
        
        return $members;
    }
    
    private function removeFromSegment($email)
    {
        if (!$this->listId || !$this->segmentId) {
            return false;
        }
        
        $emailHash = md5(strtolower($email));
        $url = "{$this->baseUrl}/lists/{$this->listId}/segments/{$this->segmentId}/members/{$emailHash}";
        
        $result = $this->makeRequest('DELETE', $url);
        return $result && $result['success'];
    }
    
    private function addTagToMember($email, $tagName)
    {
        if (!$this->listId || !$email || !$tagName) {
            return false;
        }
        
        $emailHash = md5(strtolower($email));
        $url = "{$this->baseUrl}/lists/{$this->listId}/members/{$emailHash}/tags";
        
        $data = [
            'tags' => [
                [
                    'name' => $tagName,
                    'status' => 'active'
                ]
            ]
        ];
        
        $result = $this->makeRequest('POST', $url, $data);
        return $result && $result['success'];
    }
}

