<?php

    namespace App\Models;

    class SpeakingEngagementModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'speaking_engagement';

protected $allowedFields = ['speaking_start_datetime', 'speaking_end_datetime', 'requester_id', 'assigned_id', 'requester_name', 'assigned_name', 'status_id', 'request_timestamp', 'last_response_timestamp', 'venue', 'address', 'contact_info', 'note', 'reason'];


const EMAIL_TEMPLATE_APPROVAL_NEEDED = "Approval Needed for Speaking Engagement<br />Dear Senior Pastor, <br />Pastor [name] has applied for a speaking engagement. Your approval is requested to proceed. <br />Please click the link below for more information, <br />[link]";
const EMAIL_TEMPLATE_CONFIRMATION = "Confirmation of Speaking Engagement<br />Dear Pastor [name],<br />The Senior Pastor has assigned you to an upcoming speaking engagement.<br />Please confirm your availability for this engagement on the following link,<br />[link]";
const EMAIL_TEMPLATE_DECLINED = "Speaking Engagement Application Declined<br />Dear [name],<br />Your application/assignment for the speaking engagement has been declined.<br />Reasons:[reasons]<br />Please click the link below for more information,<br />[link]"; 
const EMAIL_TEMPLATE_CONFIRMED  = "Speaking Engagement Confirmed for [date]<br />Hello Everyone,<br />The speaking engagement on [date] is all set and confirmed by all parties.<br />Event Details:<br />Date: [date]<br />Pastor: [pastor]<br />Venue: [venue]<br />More information: [link]"; 


function get_pending_from_pastor_entries(){

	
	$sql = "SELECT s1.*, s2.status	FROM `speaking_engagement` s1 LEFT JOIN speaking_engagement_status s2 on s1.`status_id` = s2.id where s1.requester_id != s1.assigned_id  and s1.status_id = 3 order by s1.request_timestamp DESC";

	$query = $this->db->query($sql);	
	
	$result = $query->getResultArray(); 
	
	return $result;
}
 
function get_user_apply_entries($uid,$status_id=3){

	
	$sql = "SELECT s1.*, s2.status	FROM `speaking_engagement` s1 LEFT JOIN speaking_engagement_status s2 on s1.`status_id` = s2.id where s1.requester_id = ? and s1.assigned_id = ? and s1.status_id = ? order by s1.request_timestamp DESC";

	$query = $this->db->query($sql,[$uid,$uid,$status_id]);	
	
	$result = $query->getResultArray(); 
	
	return $result;
}
 
 
function get_assign_entries($uid,$status_id=3){

	
	$sql = "SELECT s1.*, s2.status FROM `speaking_engagement` s1 LEFT JOIN speaking_engagement_status s2 on s1.`status_id` = s2.id where s1.requester_id != ? and s1.assigned_id = ? and s1.status_id = ?  order by s1.request_timestamp DESC";

	$query = $this->db->query($sql,[$uid,$uid,$status_id]);	
	
	$result = $query->getResultArray(); 
	
	return $result;
}


function get_waiting_spastor_entries(){

	
	$sql = "SELECT s1.*, s2.status	FROM `speaking_engagement` s1 LEFT JOIN speaking_engagement_status s2 on s1.`status_id` = s2.id where s1.requester_id = s1.assigned_id and s1.status_id = 3 order by s1.request_timestamp DESC";

	$query = $this->db->query($sql);	
	
	$result = $query->getResultArray(); 
	
	return $result;
}

 
function get_all_previous_entries(){

	
	$sql = "SELECT s1.*, s2.status FROM `speaking_engagement` s1 LEFT JOIN speaking_engagement_status s2 on s1.`status_id` = s2.id where s1.status_id != 3  order by s1.speaking_start_datetime DESC";

	$query = $this->db->query($sql);	
	
	$result = $query->getResultArray(); 
	
	return $result;
}


 
function get_post($post_id){

	
	$sql = "SELECT s1.*, s2.status FROM `speaking_engagement` s1 LEFT JOIN speaking_engagement_status s2 on s1.`status_id` = s2.id where s1.id  = ?";

	$query = $this->db->query($sql,[$post_id]);	
	
	$result = $query->getRowArray(); 
	
	return $result;
}


    }