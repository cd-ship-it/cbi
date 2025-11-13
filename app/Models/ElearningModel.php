<?php

    namespace App\Models;

    class ElearningModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'online_cbi_userdata';
protected $allowedFields = ['baptism_id', 'ans', 'scores', 'usession', 'class_id', 'took_on', 'approved'];


const E101_CURRICULUM_ID = 0;
const E101_TEST_STUS = [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];




















public function removeData($uids){
	
	
	$sql = "DELETE FROM `curriculum_logs` WHERE `baptism_id` in ?";
	$query = $this->db->query($sql,[$uids]);	
	
	$sql = "DELETE FROM `online_cbi_userdata` WHERE 1";
	$query = $this->db->query($sql);	
	
	$sql = "DELETE FROM `online_cbi_userqa` WHERE 1";
	$query = $this->db->query($sql);	
	
	$sql = "UPDATE `baptism` SET  `inactive`=2  WHERE `inactive` in ?";
	$query = $this->db->query($sql,[[4,6]]);	
	
	
	
}




public function get_students($class_id,$pastor_id=0,$count=false){
	
	
		
	$pastor_condition = $pastor_id ? ' and b.`zPastor`='. $pastor_id :'';
	
	// if($count||$pastor_id) $pastor_condition .= ' and a.usession = 99';
	
	$sql = "SELECT concat(b.fname,' ',b.lname) as name,b.`site`,b.`inactive`,b.`zPastor`,a.* FROM `baptism` b left join online_cbi_userdata a on b.id = a.baptism_id WHERE a.class_id = {$class_id} and a.approved = 0 {$pastor_condition}";
	
	
	$query = $this->db->query($sql);
	
	$result = $query->getResultArray(); 
	
	if($count){
		return count($result);
	}else{
		return $result;
	}

		
	
	
	
}

 
public function get_pending_approve_students($class_id,$pastor_id){
	
	 
	$row = $this->db->table('online_cbi_userdata')
	->join('baptism', 'baptism.id = online_cbi_userdata.baptism_id', 'left')
	->select('COUNT(*) as total')
	->where('class_id', $class_id)
	->where('approved', 0)
	->where('usession', 99)
	->where('baptism.zPastor', $pastor_id)
	->get()->getRowArray();
	
	return $row['total'] ?? 0;
	
}



public function get_user_data($baptism_id,$class_id){
	
	
	
	$sql = "SELECT * FROM `online_cbi_userdata` WHERE `baptism_id` = ? and `class_id` = ?";
	
	
		
	$row = $this->db->query($sql,array($baptism_id,$class_id))->getRowArray(); 
	
	if($row){
		return $row;
	}else{
		return [];
	}
	
	
}











public function getUserInfo($baptism_id){
	
		
	$sql = "SELECT  CONCAT( fName,' ', lName) as name, `site`,`zPastor` FROM `baptism` WHERE `id` = ?;";
			
	$query = $this->db->query($sql,[$baptism_id]);	
	
	return $result = $query->getRowArray(); 
	
	
}



public function get_all_user_qa($baptism_id,$class_id){
	
		
	$sql = "SELECT a.`value`,b.id FROM `online_cbi_userqa` a left JOIN online_cbi_sessions b on a.session_id = b.id WHERE a.`baptism_id` = ? and b.class_id= ?";
			
	$query = $this->db->query($sql,array($baptism_id,$class_id)); 
	
	$result = $query->getResultArray(); 
	
	$r = [];
	
	foreach($result as $item){
		
		$r[$item['id']] = $item['value'];
		
	}
	
	return $r;
	
	
}




public function get_user_qa($baptism_id,$session_id){
	
		
	$sql = "SELECT `value` FROM `online_cbi_userqa` WHERE `baptism_id` = ? and `session_id` = ?";
	
	
		
	$row = $this->db->query($sql,array($baptism_id,$session_id))->getRowArray(); 
	
	if($row){
		return $row['value'];
	}else{
		return '';
	}
	
	
}








public function qa_update($baptism_id,$session_id, $val){
	

	
	
	if($val){
		
		$sql = "INSERT INTO `online_cbi_userqa`(`baptism_id`, `session_id`, `value`) VALUES (?,?,?) on duplicate key update `value` = ?";
		
		return $this->db->query($sql,[$baptism_id,$session_id,$val,$val]);
		
	}else{
		
		$sql = "DELETE FROM `online_cbi_userqa` WHERE baptism_id = ? and session_id = ?";
		
		return $this->db->query($sql,[$baptism_id,$session_id]);
		
	}
	
	

	
	

	
	
}



public function ans_update($user_data_id, $class_id, $ansData){
	
 
	$val = json_encode($ansData); 
	
	$r1 = $this->update($user_data_id,array('ans'=>$val));
	
	$r2 = $this->update_scores ($user_data_id,$class_id, $ansData);
	
	
	
	return $r1&&$r2;
	
	
}




public function update_scores ($user_data_id,$class_id, $ansData){
	
		$rows = $this->db->query("SELECT `id`,  `correct_ans` FROM `online_cbi_questions` WHERE `class_id` = ?",[$class_id])->getResultArray(); 
		$correct_ans = [];
		
		foreach($rows as $row){			
			if(isset($ansData[$row['id']])&&$ansData[$row['id']]==explode(',',$row['correct_ans'])){
				$correct_ans[$row['id']] = explode(',',$row['correct_ans']);
			}
		}
		
	 
	$val = count($correct_ans).'_'.count($rows).'_'.ceil(count($correct_ans) / count($rows) * 100); 
	 
	
	$r = $this->update($user_data_id,array('scores'=>$val));
	
	
	
	
	
	return $r;
	
}




public function getClassTitle($class_id){
	
	
	 
		
	$sql = "SELECT * FROM `online_cbi_classes` WHERE `id` = ?";
	
	
		
	$row = $this->db->query($sql,array($class_id))->getRowArray(); 
	
	if($row){
		return $row['title'];
	}else{
		return '';
	}	
	
	
}









public function getLastSession($class_id){
	
	
	$sql = 'SELECT MAX(id) as id FROM `online_cbi_sessions` WHERE `class_id` = ?';	
		
	$row = $this->db->query($sql,array($class_id))->getRowArray(); 
	
	if($row){
		return $row['id'];
	}else{
		return '';
	}
	
	
}





public function getClassSessions($class_id){
	
	
		$query = $this->db->query("SELECT a.`id`, a.`title`,GROUP_CONCAT(b.id) as questions FROM `online_cbi_sessions` a left join `online_cbi_questions` b on a.id = b.`session_id`  WHERE a.`class_id` = ? group by  b.session_id",[$class_id]);	
		
		return $result = $query->getResultArray(); 
	
	
}




public function getSessionQuestions($class_id,$session_id=0){
	
	
	
		$session_condition = $session_id ? ' and  `session_id`='. $session_id :'';
		
		$sql = "SELECT * FROM `online_cbi_questions` WHERE `class_id` = {$class_id} {$session_condition}";	
	
	
		$query = $this->db->query($sql);	
		
		return $result = $query->getResultArray(); 
	
	
}







public function getSessionDetails($session_id){
	
	
		$query = $this->db->query("SELECT * FROM `online_cbi_sessions` WHERE `id` = ?",[$session_id]);	
		
		return $result = $query->getRowArray(); 
	
	
}








































    }