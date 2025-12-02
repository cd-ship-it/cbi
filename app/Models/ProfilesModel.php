<?php

    namespace App\Models;

    class ProfilesModel extends \CodeIgniter\Model
    {
            protected $table = 'baptism';
            protected $allowedFields = ['fName','mName','lName','gender','marital','birthDate','homeAddress','city','zCode','hPhone','mPhone','email','occupation','nocb','nopc','baptizedDate','membershipDate','site','zPastor','family','inactive','picture','bornagain', 'attendingagroup', 'lifegroup', 'baptizedprevious', 'byImmersion','cName','testimony','certificate','created_by','onMailchimp'];

			
function member_change_log($data){
	
		$db = db_connect();		
		$builder = $db->table('member_change_log');	
		
		$builder->insert($data);
	
		
}

function userUpdate($bid,$data,$by){
	
	$userDataBefore = $this->getBaptistbyId($bid);	
		
	if($this->update($bid, $data)){
		
		$diff = $this->generateUpdateLogs($data,$userDataBefore);
		
		if($diff){
			
			$mlogs['bid'] = $bid;
			$mlogs['by'] = $by;
			$mlogs['log'] = implode('<br />',$diff);
			$this->member_change_log($mlogs);
			
		}
		
		return true;
	}else{
		return false;
	}
	
}

			
function generateUpdateLogs($new_data,$old_data){
	
	$updateLogs = [];
	$result = array_diff_assoc($new_data, $old_data);
	
	if(!$result){
		return false;
	}
	
	
	$field = [
		'fName' => 'First Name' ,
		'mName' => 'Middle Name',
		'lName' => 'Last Name',
		'gender' => 'Gender',
		'marital' => 'Marital Status',
		'birthDate' => 'Birth Date',
		'homeAddress' => 'Home Address',
		'city' => 'City',
		'zCode' => 'Zip code',
		'hPhone' => 'Home Phone',
		'mPhone' => 'Mobile Phone',
		'email' => 'Email',
		'occupation' => 'Occupation',
		'nocb' => 'Name of the church you were baptized',
		'nopc' => 'Name of your previous church',
		'baptizedDate' => 'Baptized Date',
		'membershipDate' => 'Membership Date',
		'site' => 'Site',
		'family' => 'Family member',
		'inactive' => 'Status',
		'cName' => 'Chinese Name',
		'testimony' => 'Testimony',
		'certificate' => 'Certificate',
		'onMailchimp' => 'Mailchimp Status',

	];
	
	
	foreach($result as $key => $item){
		
		if(in_array($key,['birthDate','baptizedDate','membershipDate'])){
			
			$old_value = $old_data[$key] ? date("m/d/Y", $old_data[$key]) : 'N/A';
			$new_value = $item ?  date("m/d/Y", $item)  : 'N/A';
			
			if($old_value!=$new_value) $updateLogs[] = $field[$key] . ': ' . $old_value . ' => ' . $new_value;			
			
		}elseif($key=='inactive'){
			
			$memberCodes = array( 1=>'Inactive', 2=>'Guest', 3=>'Member', 4=>'Pre-Member', 5=>'Ex-Member', 6=>'Pending');	

			$old_value = $old_data[$key] ? $memberCodes[$old_data[$key]] : 'N/A';
			$new_value = $item ?   $memberCodes[$item]  : 'N/A';
			
			$updateLogs[] = $field[$key] . ': ' . $old_value . ' => ' . $new_value;				
			
		}elseif(in_array($key,['testimony','certificate'])){

			$updateLogs[] = $field[$key] . ' Updated';				
			
			
		}elseif(isset($field[$key])){
			
			

			$old_value = $old_data[$key]  ? $old_data[$key] : 'N/A';
			$new_value = $item ?   $item  : 'N/A';
			
			$updateLogs[] = $field[$key] . ': ' . $old_value . ' => ' . $new_value;				
			
		}
		
	}
	

	return $updateLogs;
	
	
}

			
function log_text($field,$old_value,$new_value){
	
	if($old_value==$new_value){
		return '';
	}
	
	$old_value = $old_value ? $old_value : 'N/A';
	$new_value = $new_value ? $new_value : 'N/A';
	
	return $field . ': ' . $old_value . ' => ' . $new_value;
	
	
	
}



function getActivityLog($bid){
	

		$db = db_connect();		
		$builder = $db->table('member_change_log');	
		
		$builder->select('bid,by,log,DATE_FORMAT(change_time, "%m/%d/%Y %H:%i") as change_time');
		
		$builder->where('bid', $bid);
		
		$builder->orderBy('id DESC');
	
	
		$r = $builder->get()->getResultArray(); 
		
		// var_dump($r);
		
		return $r;
	

	
}


		
	

function get_pastoralApprovalMembers($pastor_id){
	
	$sql = "SELECT id from baptism WHERE inactive=4 and zPastor = ?";
	
	$query = $this->db->query($sql,[$pastor_id]);
	
	$result = $query->getResultArray(); 
	
	return count($result);
}



public function db_m_baseInfo($fn,$mn,$ln,$gender,$email){
	$db = db_connect();	
	
	$sql = "INSERT INTO `baptism` (`fName`, `mName`, `lName`, `gender`,`email`) VALUES ('{$fn}', '{$mn}', '{$ln}', '{$gender}', '{$email}');"; 
			
			
	$query = $db->query($sql); 
	
	
	if ($query) {
	  return $db->insertID();	 
	} else {
	  return false;
	}
	

}




public function getBaptistbyId($bid){
	
	$db = db_connect();	
		
	$sql = "SELECT a.*, m.status, m.admin from baptism a left JOIN members m on a.id = m.bid  WHERE a.id=?";
	
	$query = $db->query($sql,array($bid)); 
	
	return $query->getRowArray();
}




public function db_m_getUserField($bid,$field){
	
	if(!$bid){ 
		return '';
	}
	
	$db = db_connect();	
		
	$sql = "SELECT $field from baptism  WHERE id=$bid;";
	
	$query = $db->query($sql); 
	
	return $query->getRow()->$field;
}





public function deleteBaptist($bid){
	
	$db = db_connect();	
		
	$db->query("DELETE FROM baptism WHERE id='{$bid}';"); 
	$db->query("DELETE FROM `members` WHERE bid='{$bid}';"); 
	
	
	return 'OK';
}





public function findSameNameAs($bid){
	
	
		$fName = $this->db_m_getUserField($bid,'fName');
		$lName = $this->db_m_getUserField($bid,'lName');
	
		$db = db_connect();	
	
		$sql = "SELECT concat(fName,' ',mName,' ',lName) as name, email,id FROM `baptism` WHERE `id` != ? and `fName`= ? and `lName` = ?";
		
		$query = $db->query($sql,array($bid,$fName,$lName)); 
		
		return $result = $query->getResultArray(); 		
		
}






public function getUserName($bid,$check_is_pastor=false)
{
		$db = db_connect();	
	
		$sql = "SELECT  CONCAT( fName,' ', lName) as name FROM `baptism`   WHERE `id` = ?;"; 
			
			

		
		$row = $db->query($sql,array($bid))->getRowArray(); 
		
		if($row){
			
			if($check_is_pastor){
				$builder = $db->table('capabilities');	
				$r = $builder->where(['capability'=>'is_pastor', 'bid'=>$bid])->get()->getResultArray();
				
				if($r) return 'Pastor '.$row['name'];
			}
			
			
			return $row['name'];
		}else{
			return false;
		}
		
}







public function searchBaptisms($keywords,$exclude){
	
	$this->select('CONCAT(fName," ",lName) as name, id , mPhone, email');
	
	$this->like('CONCAT(fName," ",lName)', '%' . $keywords . '%');
	if($exclude) $this->whereNotIn('id', $exclude);
	
	$this->limit(10);
	
	$r = $this->get()->getResultArray(); 

	return $r ;
	
	
}

/**
 * Sync MailChimp subscription status based on member status changes
 * 
 * @param int $bid Baptism ID
 * @param int|null $oldStatus Previous inactive status
 * @param int|null $newStatus New inactive status
 * @param string $oldEmail Previous email address
 * @param string $newEmail New email address
 * @param string $fName First name
 * @param string $lName Last name
 * @param int|null $loggedId User ID making the change (for logging)
 * @return string Message string to append to response
 */
public function syncMailchimpOnStatusChange($bid, $oldStatus, $newStatus, $oldEmail, $newEmail, $fName, $lName, $loggedId = null) {
	$mailchimpService = new \App\Libraries\MailchimpService();
	$message = '';
	
	// Validate required fields
	if (!$newEmail || !$fName || !$lName) {
		$missingFields = [];
		if(!$newEmail) $missingFields[] = 'email';
		if(!$fName) $missingFields[] = 'first name';
		if(!$lName) $missingFields[] = 'last name';
		return ' | MailChimp: Cannot sync - missing ' . implode(', ', $missingFields);
	}
	
	try {
		// Case 1: Status changed from Member (3) to non-Member - Unsubscribe
		if($oldStatus == 3 && $newStatus != 3 && $newStatus !== null) {
			$mcResult = $mailchimpService->updateMemberStatus($oldEmail, 'unsubscribed');
			if($mcResult['success']) {
				$this->update($bid, ['onMailchimp' => 'unsubscribed']);
				$message .= ' | MailChimp: Unsubscribed successfully';
			} else {
				$errorMsg = $mcResult['message'] ?? 'Unknown error';
				$message .= ' | MailChimp unsubscribe failed: ' . $errorMsg;
				log_message('error', 'MailChimp unsubscribe failed for bid ' . $bid . ': ' . $errorMsg . ' [Debug: ' . json_encode($mcResult) . ']');
			}
			return $message;
		}
		
		// Case 2: Status changed to Member (3) - Subscribe or Create
		if($newStatus == 3) {
			// Check if member exists in MailChimp
			$mcMember = $mailchimpService->getMemberByEmail($newEmail);
			
			if($mcMember) {
				// Member exists, update to subscribed
				$mcResult = $mailchimpService->createOrUpdateMember($newEmail, $fName, $lName, 'subscribed');
				if($mcResult['success']) {
					$this->update($bid, ['onMailchimp' => 'subscribed']);
					$message .= ' | MailChimp: Updated to subscribed';
					
					// Log the change if loggedId provided
					if($loggedId) {
						$mlogs['bid'] = $bid;
						$mlogs['by'] = $this->getUserName($loggedId, 1);
						$mlogs['log'] = 'MailChimp: Updated to subscribed';
						$this->member_change_log($mlogs);
					}
				} else {
					$message .= $this->handleMailchimpError($mcResult, $bid, $newEmail, 'update');
				}
			} else {
				// Member doesn't exist, create new contact
				$mcResult = $mailchimpService->createOrUpdateMember($newEmail, $fName, $lName, 'subscribed');
				if($mcResult['success']) {
					$this->update($bid, ['onMailchimp' => 'subscribed']);
					$message .= ' | MailChimp: New contact created and subscribed';
					
					// Log the change if loggedId provided
					if($loggedId) {
						$mlogs['bid'] = $bid;
						$mlogs['by'] = $this->getUserName($loggedId, 1);
						$mlogs['log'] = 'MailChimp: New contact created and subscribed';
						$this->member_change_log($mlogs);
					}
				} else {
					$message .= $this->handleMailchimpError($mcResult, $bid, $newEmail, 'creation', $fName, $lName);
				}
			}
			return $message;
		}
		
		// Case 3: Email changed - Update MailChimp email
		if($oldEmail != $newEmail && $newEmail) {
			// Check if old email exists in MailChimp
			$mcMember = $mailchimpService->getMemberByEmail($oldEmail);
			
			if($mcMember) {
				// Member exists, update email address
				$mcResult = $mailchimpService->updateMemberEmail($oldEmail, $newEmail, $fName, $lName);
				if($mcResult['success']) {
					// Keep the current MailChimp status
					$currentStatus = $mcMember['status'] ?? 'subscribed';
					$onMailchimpStatus = ($currentStatus == 'subscribed') ? 'subscribed' : (($currentStatus == 'unsubscribed') ? 'unsubscribed' : 'subscribed');
					$this->update($bid, ['onMailchimp' => $onMailchimpStatus]);
					$message .= ' | MailChimp: Email updated successfully';
				} else {
					$errorMsg = $mcResult['message'] ?? 'Unknown error';
					$message .= ' | MailChimp email update failed: ' . $errorMsg;
					log_message('error', 'MailChimp email update failed for bid ' . $bid . ' (old: ' . $oldEmail . ', new: ' . $newEmail . '): ' . $errorMsg . ' [Debug: ' . json_encode($mcResult) . ']');
				}
			} else {
				// Old email doesn't exist in MailChimp
				// If user is a Member (status = 3), create new contact with new email
				if($newStatus == 3 || ($newStatus === null && $oldStatus == 3)) {
					$mcResult = $mailchimpService->createOrUpdateMember($newEmail, $fName, $lName, 'subscribed');
					if($mcResult['success']) {
						$this->update($bid, ['onMailchimp' => 'subscribed']);
						$message .= ' | MailChimp: New contact created with new email';
					} else {
						$message .= $this->handleMailchimpError($mcResult, $bid, $newEmail, 'creation', $fName, $lName);
					}
				}
			}
			return $message;
		}
		
	} catch(\Exception $e) {
		$message .= ' | MailChimp error: ' . $e->getMessage();
		log_message('error', 'MailChimp exception for bid ' . $bid . ': ' . $e->getMessage());
	}
	
	return $message;
}

/**
 * Manually sync a member to MailChimp (subscribe or update existing)
 * This is used for manual sync operations, not status-based changes
 * 
 * @param int $bid Baptism ID
 * @param string $email Email address
 * @param string $fName First name
 * @param string $lName Last name
 * @param int|null $loggedId User ID making the change (for logging)
 * @return array Result array with 'success' boolean, 'message' string, and 'onMailchimp' status
 */
public function syncMailchimpMember($bid, $email, $fName, $lName, $loggedId = null) {
	// Validate required fields
	if (!$email || !$fName || !$lName) {
		$missingFields = [];
		if(!$email) $missingFields[] = 'email';
		if(!$fName) $missingFields[] = 'first name';
		if(!$lName) $missingFields[] = 'last name';
		return [
			'success' => false, 
			'message' => 'Missing required fields: ' . implode(', ', $missingFields),
			'onMailchimp' => null
		];
	}
	
	$mailchimpService = new \App\Libraries\MailchimpService();
	
	try {
		// Check if member exists in MailChimp
		$mcMember = $mailchimpService->getMemberByEmail($email);
		
		if($mcMember) {
			// Member exists, update to subscribed
			$mcResult = $mailchimpService->createOrUpdateMember($email, $fName, $lName, 'subscribed');
			if($mcResult['success']) {
				$this->update($bid, ['onMailchimp' => 'subscribed']);
				
				// Log the change if loggedId provided
				if($loggedId) {
					$mlogs['bid'] = $bid;
					$mlogs['by'] = $this->getUserName($loggedId, 1);
					$mlogs['log'] = 'MailChimp: Member updated successfully';
					$this->member_change_log($mlogs);
				}
				
				$updatedUser = $this->getBaptistbyId($bid);
				return [
					'success' => true,
					'message' => 'MailChimp: Member updated successfully',
					'onMailchimp' => isset($updatedUser['onMailchimp']) ? $updatedUser['onMailchimp'] : 'subscribed'
				];
			} else {
				$errorMsg = $this->handleMailchimpError($mcResult, $bid, $email, 'update', $fName, $lName);
				// Remove the leading " | " from the error message for JSON response
				$errorMsg = ltrim($errorMsg, ' |');
				return [
					'success' => false,
					'message' => $errorMsg,
					'onMailchimp' => null
				];
			}
		} else {
			// Member doesn't exist, create new contact
			$mcResult = $mailchimpService->createOrUpdateMember($email, $fName, $lName, 'subscribed');
			if($mcResult['success']) {
				$this->update($bid, ['onMailchimp' => 'subscribed']);
				
				// Log the change if loggedId provided
				if($loggedId) {
					$mlogs['bid'] = $bid;
					$mlogs['by'] = $this->getUserName($loggedId, 1);
					$mlogs['log'] = 'MailChimp: New contact created and subscribed';
					$this->member_change_log($mlogs);
				}
				
				$updatedUser = $this->getBaptistbyId($bid);
				return [
					'success' => true,
					'message' => 'MailChimp: New contact created and subscribed',
					'onMailchimp' => isset($updatedUser['onMailchimp']) ? $updatedUser['onMailchimp'] : 'subscribed'
				];
			} else {
				$errorMsg = $this->handleMailchimpError($mcResult, $bid, $email, 'creation', $fName, $lName);
				// Remove the leading " | " from the error message for JSON response
				$errorMsg = ltrim($errorMsg, ' |');
				return [
					'success' => false,
					'message' => $errorMsg,
					'onMailchimp' => null
				];
			}
		}
		
	} catch(\Exception $e) {
		$errorMsg = 'MailChimp error: ' . $e->getMessage();
		log_message('error', 'MailChimp sync exception for bid ' . $bid . ': ' . $e->getMessage());
		return [
			'success' => false,
			'message' => $errorMsg,
			'onMailchimp' => null
		];
	}
}

/**
 * Archive an email address in MailChimp
 * 
 * @param int $bid Baptism ID
 * @param string $email Email address to archive
 * @param int|null $loggedId User ID making the change (for logging)
 * @return array Result array with 'success' boolean and 'message' string
 */
public function archiveMailchimpEmail($bid, $email, $loggedId = null) {
	if (!$email) {
		return ['success' => false, 'message' => 'Email address is required'];
	}
	
	$mailchimpService = new \App\Libraries\MailchimpService();
	
	try {
		// Check if member exists in MailChimp
		$mcMember = $mailchimpService->getMemberByEmail($email);
		
		if (!$mcMember) {
			return ['success' => false, 'message' => 'Member not found in MailChimp'];
		}
		
		// Archive the member
		//$mcResult = $mailchimpService->updateMemberStatus($email, 'archived');
		$mcResult = $mailchimpService->updateMemberStatus($email, 'unsubscribed');

		if ($mcResult['success']) {

			return ['success' => true, 'message' => 'MailChimp: Email unsubscribed successfully'];
		} else {
			$errorMsg = $mcResult['message'] ?? 'Unknown error';
			log_message('error', 'MailChimp unsubscribed failed for bid ' . $bid . ' (email: ' . $email . '): ' . $errorMsg . ' [Debug: ' . json_encode($mcResult) . ']');
			return ['success' => false, 'message' => 'MailChimp unsubscribed failed: ' . $errorMsg];
		}
		
	} catch(\Exception $e) {
		$errorMsg = 'MailChimp error: ' . $e->getMessage();
		log_message('error', 'MailChimp unsubscribed exception for bid ' . $bid . ': ' . $e->getMessage());
		return ['success' => false, 'message' => $errorMsg];
	}
}

/**
 * Handle MailChimp error responses consistently
 * 
 * @param array $mcResult MailChimp service result
 * @param int $bid Baptism ID
 * @param string $email Email address
 * @param string $operation Operation type ('update' or 'creation')
 * @param string|null $fName First name (for logging)
 * @param string|null $lName Last name (for logging)
 * @return string Error message to append
 */
private function handleMailchimpError($mcResult, $bid, $email, $operation, $fName = null, $lName = null) {
	$message = '';
	
	// Check for HTTP 400 error (user unsubscribed themselves)
	$httpCode = $mcResult['debug']['http_code'] ?? $mcResult['http_code'] ?? null;
	if($httpCode == 400) {
		$message .= ' | Since this user unsubscribed his/herself, you can\'t not re-subscribe them without their consent.';
	} else {
		$errorMsg = $mcResult['message'] ?? 'Unknown error';
		$message .= ' | MailChimp ' . $operation . ' failed: ' . $errorMsg;
		
		// Log the error
		$logMsg = 'MailChimp ' . $operation . ' failed for bid ' . $bid . ' (email: ' . $email;
		if($fName && $lName) {
			$logMsg .= ', fName: ' . $fName . ', lName: ' . $lName;
		}
		$logMsg .= '): ' . $errorMsg . ' [Debug: ' . json_encode($mcResult) . ']';
		log_message('error', $logMsg);
	}
	
	return $message;
}




















			
			
			
    }