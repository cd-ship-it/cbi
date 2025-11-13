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




















			
			
			
    }