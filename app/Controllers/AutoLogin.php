<?php namespace App\Controllers;

use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\PermissionModel;

/**
 * Auto Login Controller - DEVELOPMENT ONLY
 * Automatically logs in as Frederick Ng
 * 
 * Access: http://localhost:8888/cbi/auto-login
 */
class AutoLogin extends BaseController
{
    public function index()
    {
        // Check if we're in development mode
        if (ENVIRONMENT !== 'development') {
            die('This feature is only available in development mode.');
        }
        
        $session = \Config\Services::session();
        $modelMembers = new MembersModel();
        $modelProfiles = new ProfilesModel();
        $modelPermission = new PermissionModel();
        
        // Check for logout action
        $action = $this->request->getGet('action');
        if ($action === 'logout') {
            $session->destroy();
            echo $this->successPage('Logged Out', 'You have been logged out successfully.', base_url('auto-login'));
            return;
        }
        
        try {
            // Find Frederick Ng by email
            $db = db_connect();
            $builder = $db->table('baptism');
            $builder->select('id as bid');
            $builder->where('email', 'cd@crosspointchurchsv.org');
            $baptismRecord = $builder->get()->getRowArray();
            
            if (!$baptismRecord) {
                echo $this->errorPage('User Not Found', 'Could not find user with email: cd@crosspointchurchsv.org<br>Please check the database.');
                return;
            }
            
            $bid = $baptismRecord['bid'];
            
            // Get full member data
            $member = $modelMembers->db_m_member($bid);
            
            if (!$member) {
                echo $this->errorPage('Member Not Found', 'Frederick Ng found in baptism table but not in members table.<br>BID: ' . $bid);
                return;
            }
            
            // Get permissions
            $permissionSet = $modelPermission->getUserPermissionNames($bid);
            
            // Set session data (same as OAuth does)
            $newdata = [
                'mloggedin'  => $member['bid'],
                'mloggedinName' => ucwords($member['name']),
                'email' => $member['email'],
                'capabilities' => $permissionSet,
                'dsfPicture' => '',
                'xadmin' => $member['admin']
            ];
            
            $session->set($newdata);
            
            // Update last activity
            $modelMembers->lastactivityUpdate($member['bid']);
            
            // Show success page
            echo $this->successPage(
                'Auto-Login Successful!',
                'You are now logged in as:<br><br>' .
                '<strong>Name:</strong> ' . esc($member['name']) . '<br>' .
                '<strong>Email:</strong> ' . esc($member['email']) . '<br>' .
                '<strong>BID:</strong> ' . esc($member['bid']) . '<br>' .
                '<strong>Admin Level:</strong> ' . esc($member['admin']),
                base_url('xAdmin')
            );
            
        } catch (\Exception $e) {
            echo $this->errorPage('Login Failed', 'Error: ' . $e->getMessage());
        }
    }
    
    private function successPage($title, $message, $redirectUrl = null)
    {
        $autoRedirect = $redirectUrl ? '<meta http-equiv="refresh" content="2;url=' . $redirectUrl . '">' : '';
        
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . esc($title) . '</title>
            ' . $autoRedirect . '
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
                    max-width: 600px;
                    margin: 50px auto;
                    padding: 20px;
                    background: #f5f5f5;
                }
                .container {
                    background: white;
                    padding: 40px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .success-icon {
                    font-size: 64px;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #28a745;
                    margin-bottom: 20px;
                }
                .message {
                    color: #333;
                    line-height: 1.8;
                    margin-bottom: 30px;
                }
                .links {
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #eee;
                }
                a {
                    display: inline-block;
                    margin: 5px 10px;
                    padding: 10px 20px;
                    background: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                }
                a:hover {
                    background: #0056b3;
                }
                .logout {
                    background: #dc3545;
                }
                .logout:hover {
                    background: #c82333;
                }
                .countdown {
                    color: #666;
                    font-size: 14px;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="success-icon">✅</div>
                <h1>' . esc($title) . '</h1>
                <div class="message">' . $message . '</div>
                ' . ($redirectUrl ? '<div class="countdown">Redirecting to dashboard in 2 seconds...</div>' : '') . '
                <div class="links">
                    <a href="' . base_url() . '">Home</a>
                    <a href="' . base_url('xAdmin') . '">Admin Dashboard</a>
                    <a href="' . base_url('member') . '">Member Area</a>
                    <a href="' . base_url('pto') . '">PTO</a>
                    <br><br>
                    <a href="' . base_url('auto-login?action=logout') . '" class="logout">Logout</a>
                </div>
            </div>
        </body>
        </html>';
    }
    
    private function errorPage($title, $message)
    {
        return '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . esc($title) . '</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
                    max-width: 600px;
                    margin: 50px auto;
                    padding: 20px;
                    background: #f5f5f5;
                }
                .container {
                    background: white;
                    padding: 40px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .error-icon {
                    font-size: 64px;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #dc3545;
                    margin-bottom: 20px;
                }
                .message {
                    color: #333;
                    line-height: 1.8;
                    margin-bottom: 30px;
                }
                a {
                    display: inline-block;
                    margin: 5px 10px;
                    padding: 10px 20px;
                    background: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                }
                a:hover {
                    background: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="error-icon">❌</div>
                <h1>' . esc($title) . '</h1>
                <div class="message">' . $message . '</div>
                <a href="' . base_url('DevLogin/debug') . '">Debug Info</a>
                <a href="' . base_url('dev-login') . '">Try DevLogin</a>
            </div>
        </body>
        </html>';
    }
}

