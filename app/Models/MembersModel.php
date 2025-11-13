<?php

    namespace App\Models;

    class MembersModel extends \CodeIgniter\Model
    {
		
	


	
		
protected $table = 'members';

protected $allowedFields = ['service','subid','subemail','xpw','status','admin','blocked','lastactivity'];





function getAdminList(){
	
		$db = db_connect();
		
		$builder = $db->table('members');	
		$builder->join('baptism', 'members.bid = baptism.id','inner');
		$builder->select('members.id, CONCAT(baptism.`fName`," ",baptism.`mName`," ",baptism.`lName`) as name, members.admin, members.bid');
		$builder->where('members.`admin` BETWEEN 1 AND 8');
		$builder->orderBy('members.admin', 'DESC');

	

		$data = $builder->get()->getResultArray();
		return $data;

		
}




function getPtoUsers(){
	
		$db = db_connect();
		
		$builder = $db->table('members');	
		$builder->join('baptism', 'members.bid = baptism.id','inner');
		$builder->join('pto_relation', 'members.bid = pto_relation.bid','left');
		$builder->select('members.id, CONCAT(baptism.`fName`," ",baptism.`mName`," ",baptism.`lName`) as name, members.admin, members.bid');
		$builder->where('members.`admin` BETWEEN 1 AND 8');
		$builder->where('pto_relation.`ft_hire` is not null AND pto_relation.`operations_director` is not null');
		$builder->where("members.`bid` not in (SELECT bid FROM `capabilities` WHERE `capability` = 'pto_apply' and value = 0)");
		$builder->orderBy('members.admin', 'DESC');

	

		$data = $builder->get()->getResultArray();
		return $data;

		
}



function getStaff(){
	
		$sql = "SELECT * FROM `staff`"; 
		
		$query = $this->db->query($sql);

		return $query->getResultArray();

		
}



function getStagingLoginData($uid){
	

		$sql = "SELECT CONCAT(b.`fName`,' ',b.`lName`) as name, m.admin, m.bid,b.email,b.fName,b.lName FROM baptism b JOIN members m on b.id = m.bid WHERE m.status = 1 and  m.bid = ?"; 
		
		$query = $this->db->query($sql,[$uid]);

		return $query->getRowArray();

		
}


function stagingLoginUsers($include=false,$exclude=false){
	
		$db = db_connect();
		
		$includeCondition = $include ?  ' or b.id in ('. implode(',',$include).') ' : '';
		$excludeCondition = $exclude ?  ' and b.id in ('. implode(',',$exclude).') ' : '';

		$sql = "SELECT m.id, CONCAT(b.`fName`,' ',b.`mName`,' ',b.`lName`) as name, b.fName,b.lName, m.admin, m.bid FROM baptism b JOIN members m on b.id = m.bid WHERE (b.id in (SELECT bid FROM capabilities WHERE capability = 'is_pastor') or m.admin >= 3 {$includeCondition} ) and m.admin < 9 and m.status = 1 {$includeCondition} order by m.admin, b.fName "; 
		
		$query = $db->query($sql);

		return $query->getResultArray();

		
}

function getPastors($exclude=false){
	
		$db = db_connect();
		
		

		$sql = "SELECT m.id, CONCAT(b.`fName`,' ',b.`mName`,' ',b.`lName`) as name, m.admin, m.bid FROM baptism b JOIN members m on b.id = m.bid WHERE b.id in (SELECT bid FROM capabilities WHERE capability = 'is_pastor') or m.admin = 3"; 
		

		
		if($exclude){
			$sql .= ' and b.id not in ('. implode(',',$exclude).')';
		}
		
		
		$sql .= ' order by b.fName'; 
		
				
		$query = $db->query($sql);

		return $query->getResultArray();

		
}

function getInterns(){
	
		$db = db_connect();
		
		$sql = "SELECT m.id, CONCAT(b.`fName`,' ',b.`mName`,' ',b.`lName`) as name, m.admin, m.bid FROM baptism b JOIN members m on b.id = m.bid WHERE m.admin = 2"; 
		
		$query = $db->query($sql);

		return $query->getResultArray();

		
}

function searchUserByName($name){	
	
		$condition = ['members.status = 1'];
		
		if(preg_match_all('#[a-z]+#i',$name,$keys)){
			foreach($keys[0] as $k){
				$condition[$k] =  "concat(baptism.fName,baptism.mName,baptism.lName) like '%".$k."%'";
			}			
		}else{
			return [];
		}	
	
		$db = db_connect();
		
		$builder = $db->table('members');	
		$builder->join('baptism', 'members.bid = baptism.id','inner');
		
		$builder->select('members.id, concat(baptism.fName," ",baptism.mName," ",baptism.lName) as name,members.admin,baptism.id as bid');
		$builder->where(implode(' and ',$condition));
		
		
		$data = $builder->get()->getResultArray();
		return $data;

		
}




public function getDsfMember($ser,$subid){

	$db = db_connect();
	
	$builder = $db->table('members');	
	$builder->join('baptism', 'members.bid = baptism.id','inner');
	$builder->select('members.*, CONCAT(baptism.fName," ",baptism.lName) as name');
	$builder->where('service', $ser);
	$builder->where('subid', $subid);
	$builder->where('status', 1);
	
	if($builder->countAllResults(false) > 0){
		$data = $builder->get()->getRowArray();
		return $data;
	}
	
	return false;
}











public function lastactivityUpdate($bid){
	$this->where('bid', $bid)->set(['lastactivity' =>  date("Y-m-d H:i:s")])->update();
}






public function dsfChange($bid,$ser,$subid){
	
	
	$check = $this->getDsfMember($ser,$subid);
	
	if($check){
		return 'Error, 您申請的賬號已綁定了其他賬號';
	}

	$this->where('bid', $bid)->set(['service' => $ser , 'subid' => $subid , 'lastactivity' =>  date("Y-m-d H:i:s")])->update(); 	
	

					
	return 'changed';
	

}





public function checkMemberExist($email,$fn,$ln){
		$db = db_connect();

		$name = $fn.' '.$ln;
		$sql = "SELECT a.id, CONCAT(a.`fName`,' ',a.`lName`) as name, b.status, a.email FROM `baptism` a left JOIN members b on a.id = b.bid where (a.email = ? or CONCAT(a.`fName`,' ',a.`lName`)  = ?) AND (b.status !=1 or b.status is null);"; 
				
		$query = $db->query($sql,array($email,$name));

		return $query->getResultArray();
	

}






public function db_m_signup_original($bid,$pw,$dsfService,$dsfId,$dsfEmail){

	$db = db_connect();
	
	$sql = "INSERT INTO `members` ( `bid`, `xpw`, `service`,`subid` , `subemail`, `status`) VALUES ('{$bid}', '{$pw}', '{$dsfService}', '{$dsfId}', '{$dsfEmail}', '0') ON DUPLICATE KEY UPDATE  `xpw` =  case when `status` = 0 then '{$pw}' else `xpw` end, `service` =  case when `status` = 0 then '{$dsfService}' else `service` end, `subid` =  case when `status` = 0 then '{$dsfId}' else `subid` end,`subemail` =  case when `status` = 0 then '{$dsfEmail}' else `subemail` end, `lastactivity` = now();"; 
			
	$query = $db->query($sql); 
	
	return $query;
	

}



public function db_m_member($bid){
	$db = db_connect();
	
	$sql = "SELECT a.*, b.email, CONCAT(b.fName,' ',b.lName) as name FROM `members` a join `baptism` b on a.bid = b.id WHERE a.`bid` = ?;"; 
			
	$query = $db->query($sql,array($bid)); 
	
	return $query->getRowArray();
	

}




public function db_m_getUnconfirm($bid){
	$db = db_connect();
	
	$sql = "SELECT a.*, b.email, CONCAT(b.fName,' ',b.lName) as name FROM `members` a join `baptism` b on a.bid = b.id WHERE a.`bid` = ? and a.status = 0;"; 
			
	$query = $db->query($sql,array($bid)); 
	
	$r = $query->getRowArray();
	
	if($r && $this->getDsfMember($r['service'],$r['subid'])){
		return false;
	}
	
	return $r;
}


public function getpLists(){
	$db = db_connect();
	
	$sql = "SELECT a.*, b.email, CONCAT(b.fName,' ',b.lName) as name FROM `members` a join `baptism` b on a.bid = b.id WHERE a.`bid` = ? and a.status = 0;"; 
			
	$query = $db->query($sql,array($bid)); 
	
	return $query->getRowArray();
	

}












			
			
			
    }