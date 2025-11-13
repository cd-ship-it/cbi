<?php

    namespace App\Models;

    class ClassesModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'curriculum';
protected $allowedFields = ['title', 'scount', 'start', 'end', 'sessions', 'scode', 'note', 'material', 'classinfo', 'classmessage','code', 'reminder','title-zh-Hans','title-en','note-zh-Hans','note-en','classinfo-zh-Hans','classinfo-en','pastor','confirmed','zoom_password','zoom_url','is_active'];



 

public function get_monthly_classes_reminder_log($month)
{ 
	
	$db = db_connect();	
		
	$sql = "SELECT * FROM `curriculum_reminder_log` WHERE `month` = ?";
	
	$query = $db->query($sql,[$month]); 
	
	return $query->getResultArray();
	
} 

public function new_monthly_classes_reminder_log($month,$log)
{ 
	
	$db = db_connect();	
		
	$sql = "INSERT INTO `curriculum_reminder_log` (`month`, `log`) VALUES (?, ?)";
	
	return $query = $db->query($sql,[$month,$log]); 
	
	
	
}

public function get_course_subscribers($page=1,$limit=50)
{
	$db = db_connect();	
		
	$offset = ($page - 1)* $limit;
	
	$sql = "SELECT b.`email` FROM `baptism` b JOIN members m on b.id = m.bid WHERE m.status = 1 and b.id in (SELECT l.baptism_id FROM `curriculum` c JOIN curriculum_logs l on c.id = l.curriculum_id WHERE c.code='CBIF101' and l.type='finish' and l.value >= 75) and b.id not in (SELECT `bid` FROM `curriculum_subscribe`) order by b.id ASC LIMIT ?,?;";
	
	$query = $db->query($sql,[$offset,$limit]); 
	
	return $query->getResultArray();
}






public function is_101finished($bid)
{
	$db = db_connect();	
		
	$sql = "SELECT c.code,l.* FROM `curriculum` c  JOIN curriculum_logs l on c.id = l.curriculum_id WHERE c.code='CBIF101' and l.type='finish' and l.value >= 75 and l.baptism_id = ?";
	
	$query = $db->query($sql,[$bid]); 
	
	$r =  $query->getRow(); 
	
	return $r ? 1 : 0;
}


public function getAlready_confirmed($cid)
{
	$db = db_connect();	
		
	$sql = "SELECT confirmed from curriculum  WHERE id=$cid;";
	
	$query = $db->query($sql); 
	
	$r =  $query->getRow(); 
	
	return $r ? json_decode($r->confirmed) : [];
}

public function getAllPublishedCurriculums()
{
	return	$this->asArray()->where('end >', time())->notLike('title', 'e-learning')->orderBy('end', 'ASC')->findAll();
		
}

public function getOngoingClasses()
{
	return	$this->asArray()->where('end >', time())->where('is_active', 1)->notLike('title', 'e-learning')->orderBy('end', 'ASC')->findAll();
		
}

function  updateCurriculum($cid,$class){ 
	
	$resultObj = $this->update($cid, $class);
	
	$this->removeCurriculumLogsBySessions($cid,json_decode($class['sessions']));

	
	if($resultObj){
		return 'Updated successfully! <br /><a href="'.base_url("class/".$cid).'">View Class</a>';
	}
	
	return 'Error';
	
}


function  removeCurriculumLogsBySessions($cid,$sessions){
	
	$db = db_connect();
	
	$sql = "DELETE FROM `curriculum_logs` WHERE `curriculum_id` = '{$cid}' AND `type` = 'signin' AND `value` NOT IN ('".(implode("','",$sessions))."');";
	
	$db->query($sql);
	
}

public function  curriculumUnsigned($cid,$bid,$date){
	
	$db = db_connect();
		
	$sql = "DELETE FROM `curriculum_logs` WHERE `baptism_id` = ? AND `type` = 'signin' AND `curriculum_id` =  ? AND `value` =  ?;";
	
	$query = $db->query($sql,array($bid,$cid,$date)); 
	
	$r = $this->updateCompletion($cid,$bid);
	
	if($r !== false) {		
		return $r;
	}
	
	return 'Error';
}




public function presenceDetail($bid){

		$db = db_connect();
			
		
		
		$sql = "SELECT a.`code`, b.baptism_id, GROUP_CONCAT(CONCAT( a.`id`,'#',a.`start`,'#',a.`end`,'#',b.`type`,'#',b.`value`,'#',a.`title`) order by a.`end` asc separator '|||') as logs FROM `curriculum` a join `curriculum_logs` b on a.id = b.curriculum_id  where b.`type` = 'finish' and b.baptism_id = ? GROUP by a.`code`, b.baptism_id;"; 
		
		
		$resultObj = $db->query($sql,array($bid)); 


		
		if($resultObj) {
			
			$results_array = array();
			foreach ($resultObj->getResultArray() as $row){
				

				$code = $row['code'];
				$logs = $row['logs'];;
				
				
			  $results_array[$code] = $logs;
			}
			
			
		
			
		}else{
			return [];
		}
		
		
		return $results_array;
		
}




public function curriculumSigned($cid,$bid,$date){
	
	$db = db_connect();
		
	$sql = "INSERT IGNORE INTO `curriculum_logs` (`id`, `baptism_id`, `curriculum_id`, `type`, `value`) VALUES (NULL, ?, ?, 'signin', ?);";
	
	$query = $db->query($sql,array($bid,$cid,$date)); 
	
	$r = $this->updateCompletion($cid,$bid);
	
	if($r !== false) {		
		return $r;
	}
	
	return 'Error';
}



public function updateCompletion($cid,$bid,$hisABC=false){
	
	$db = db_connect();
		
	$db->query("DELETE FROM `curriculum_logs` WHERE `baptism_id` = '{$bid}' AND `curriculum_id` =  '{$cid}' AND `type` =  'finish';");
	
	
	if($hisABC===false){
		
		$scount = $db->query("SELECT `scount` FROM `curriculum` WHERE `id` = {$cid}")->getRowArray()['scount']; 
		
		$bcount = $db->query("SELECT count(`baptism_id`) as bcount FROM `curriculum_logs` WHERE `baptism_id` = {$bid} AND `type` = 'signin'  AND `curriculum_id` =  {$cid} GROUP by `baptism_id`")->getRowArray();
		
		$bcount = $bcount ? $bcount['bcount'] : 0;
		
		$hisABC = floor(($bcount/$scount)*100); 
	
	}
	 
	 
	$r = $db->query("INSERT INTO `curriculum_logs` (`id`, `baptism_id`, `curriculum_id`, `type`, `value`) VALUES (NULL, '{$bid}', '{$cid}', 'finish', '{$hisABC}');");
	
	if($r) return $hisABC;
	
	return 'Error'; //$db->error
	
	
}


function searchStuByfn($cid,$query){
	
		$db = db_connect();
			
		$condition = [];
		if(preg_match_all('#[a-z]+#i',$query,$keys)){
			foreach($keys[0] as $k){
				$condition[$k] =  "concat(fName,mName,lName) like '%".$k."%'";
			}			
		}else{
			return [];
		}
		
	
		$sql = "SELECT a.id, concat(a.fName,' ',a.mName,' ',a.lName) as name,GROUP_CONCAT(b.curriculum_id) as logs FROM `baptism` a left join curriculum_logs b on a.id = b.baptism_id WHERE  ".implode(' and ',$condition)." group by a.id"; 
		
		$resultObj = $db->query($sql); 
		
		if($resultObj) {
			
			$results_array = array();
			foreach ($resultObj->getResultArray() as $row){
				
				$id = $row['id'];
				$name = $row['name'];
				$logs = preg_match('#\b'.$cid.'\b#i',$row['logs']);
				
				
			  $results_array[] = [$id,$name,$logs];
			}
			
			
		
			
		}else{
			return [];
		}
		
		
		return $results_array;
		
}

function getMsgLogs($cid){
	
	$db = db_connect();
		
$sql = "SELECT  * FROM `mssage_logs` WHERE `cid` = {$cid} order by date desc;";
	
	$resultObj = $db->query($sql); 
	
		if($resultObj) {
			
			$results_array = array();
			foreach ($resultObj->getResultArray() as $row){
			  $results_array[] = $row;
			}
			
			
		
			
		}else{
			return [];
		}
		
		
		return $results_array;
}

public function curriculumLogsRemove($cid,$bid){
	
	$db = db_connect();
		
	$sql = "DELETE FROM `curriculum_logs` WHERE `baptism_id` = '{$bid}' AND `curriculum_id` = '{$cid}';";
	
	$resultObj = $db->query($sql); 
	
	if($resultObj) {		
		return 'OK';		
	}
	
	return 'Error';
}


function getStudentsByCid($cid){
	
	$db = db_connect();
		
$sql = "SELECT a.`id`, a.`baptism_id`, b.email, CONCAT(b.`fName`,' ',b.`mName`,' ',b.`lName`) as name, GROUP_CONCAT(CONCAT(a.`value`,a.`type`) separator '|||') as logs FROM `curriculum_logs` a JOIN `baptism` b on a.`baptism_id` = b.id WHERE `curriculum_id` = {$cid} group by `baptism_id` order by name asc;";
	
	$resultObj = $db->query($sql); 
	
		if($resultObj) {
			
			$results_array = array();
			foreach ($resultObj->getResultArray() as $row){
			  $results_array[] = $row;
			}
			
			
		
			
		}else{
			return [];
		}
		
		
		return $results_array;
}


function getCurriculumsByCode($code){
	
	$db = db_connect();
		
	$sql = "SELECT * FROM `curriculum` WHERE `code` = '{$code}' order by start desc";
	
			$resultObj = $db->query($sql); 
		
		if($resultObj) {
			
			$results_array = array();
			foreach ($resultObj->getResultArray() as $row){
			  $results_array[$row['id']] = $row;
			}
			
			
		
		}else{
			return [];
		}
		
		
		return $results_array;
}



function db_m_addmslogs($sender,$cid,$time,$sendTo,$files,$message){
	
	$db = db_connect();
		
	$sql = "INSERT INTO `mssage_logs` (`id`, `sender`, `cid`, `date`, `sendto`, `files`, `message`) VALUES (NULL, ?, ?, ?, ?, ?, ?)";
	
	$resultObj = $db->query($sql,array($sender,$cid,$time,$sendTo,$files,$message)); 
}


function db_m_getemailsbyCid($cid){
	
	$db = db_connect();
		
$sql = "SELECT b.`id`, b.`email`, CONCAT(b.`fName`,' ',b.`mName`,' ',b.`lName`) as name from `baptism` b WHERE b.`id` IN (SELECT `baptism_id` FROM `curriculum_logs` WHERE `curriculum_id` = ? and `type` = 'join' GROUP by `baptism_id`);";
	
	$resultObj = $db->query($sql,array($cid)); 
	
		if($resultObj) {
			
			$results_array = array();
			foreach ($resultObj->getResultArray() as $row){
				
				
				$results_array[0][] = $row['name'];
				$results_array[1][] = $row['email'];
			}
			
			
		
			
		}else{
			return [];
		}
		
		
		return $results_array;
}


function db_m_getemails($arr){
	
	
	if(!$arr){
		return [];
	}
	
	$db = db_connect();
		
$sql = "SELECT b.`id`, b.`email`, CONCAT(b.`fName`,' ',b.`mName`,' ',b.`lName`) as name from `baptism` b WHERE b.`id` IN (".implode(',',$arr).");";
	
	$resultObj = $db->query($sql); 
	
		if($resultObj) {
			
			$results_array = array();
			foreach ($resultObj->getResultArray() as $row){
				
				
				$results_array[0][] = $row['name'];
				$results_array[1][] = $row['email'];
			}
			
			
		
			
		}else{
			return [];
		}
		
		
		return $results_array;
}



public function curriculumJoin($cid,$bid,$time=false,$updateCompletion=true){
	
		$db = db_connect();
		
		$time = $time ? $time : time();
		
	$sql = "INSERT IGNORE INTO `curriculum_logs` (`id`, `baptism_id`, `curriculum_id`, `type`, `value`) VALUES (NULL, '{$bid}', '{$cid}', 'join', '{$time}');";
	
	$resultObj = $db->query($sql); 	
	
	if($updateCompletion&&$db->insertID()) {
		$this->updateCompletion($cid,$bid);		
		return 'OK';		
	}
	
	return 'Error';
}


 


public function getSPSInclass($cid,$bid){
	
	$db = db_connect();
		
	$sql = "SELECT GROUP_CONCAT(CONCAT(`value`,`type`) separator '|||') as logs FROM `curriculum_logs` WHERE `curriculum_id` = {$cid} and `baptism_id` = {$bid} group by `baptism_id`;";
	
	
		
	$row = $db->query($sql)->getRowArray(); 
	
	if($row){
		return $row['logs'];
	}else{
		return '';
	}
	
}

 




public function getNowClassIds(){
	
		$getOngoingClassesIds = $this->where('end >', time())->findColumn('id');
		
		
		return $getOngoingClassesIds ? $getOngoingClassesIds : [];
		
}


public function dbSearchBaptism2($mode,$condition=''){
	
		$db = db_connect();
			
		if($condition) $condition = ' AND '. $condition;
		
		
		if($mode==0||$mode==4){			
	
			$sql = "SELECT a.`id`, m.status, m.admin, CONCAT(a.`fName`,' ',a.`mName`,' ',a.`lName`) as name, GROUP_CONCAT(CONCAT(c.`code`,'#',b.curriculum_id,'#',b.`value`) separator '|||') as logs from baptism a left JOIN curriculum_logs b on a.id = b.baptism_id LEFT JOIN curriculum c on b.curriculum_id = c.id left JOIN members m on a.id = m.bid WHERE (b.`type` = 'finish' or b.`type` is null) AND (m.admin != 9 or m.admin is null) ".$condition." GROUP by a.id order by a.fName"; 
		}elseif($mode==5){
			
			$sql = "SELECT a.*, m.status, m.admin from baptism a left JOIN members m on a.id = m.bid join shape s on s.bid = a.id WHERE (m.admin != 9 or m.admin is null) ".$condition." order by a.fName"; 
		}elseif($mode==6){
			
			$sql = "SELECT a.`id`, a.`zPastor`, m.status, m.admin, CONCAT(a.`fName`,' ',a.`mName`,' ',a.`lName`) as name,  r.bid as rbid, a.`site` from baptism a left JOIN members m on a.id = m.bid left join recommendation r on r.bid = a.id WHERE (m.admin != 9 or m.admin is null) ".$condition." order by a.fName"; 
		}else{
			
			$sql = "SELECT a.*, TIMESTAMPDIFF(YEAR,DATE_ADD(FROM_UNIXTIME(0), INTERVAL (a.birthDate - 3600*8) SECOND),CURDATE()) as age, m.status, m.admin from baptism a left JOIN members m on a.id = m.bid WHERE (m.admin != 9 or m.admin is null) ".$condition." order by a.fName"; 
		}
	
	
		
		$query = $db->query($sql); 
		
		return $query->getResultArray();
		
}





public function searchBaptismForExport($condition=''){
	
		$db = db_connect();
			
		if($condition) $condition = ' AND '. $condition;
		
		
		$sql = "SELECT a.*, TIMESTAMPDIFF(YEAR,DATE_ADD(FROM_UNIXTIME(0), INTERVAL (a.birthDate - 3600*8) SECOND),CURDATE()) as age, GROUP_CONCAT(CONCAT(c.`code`,'#',b.curriculum_id,'#',b.`value`) separator '|||') as logs from baptism a left JOIN curriculum_logs b on a.id = b.baptism_id LEFT JOIN curriculum c on b.curriculum_id = c.id left JOIN members m on a.id = m.bid WHERE (b.`type` = 'finish' or b.`type` is null) AND (m.admin != 9 or m.admin is null) ".$condition." GROUP by a.id order by a.fName"; // AND (m.status != 0 or m.status is null) 
		
	
		// echo $sql; exit;
		
		$query = $db->query($sql); 
		
		return $query->getResultArray();
		
}



public function getClassData($startDate,$endDate){
	
		$db = db_connect();
		
		if(!$endDate){
			$endDate = 99999999999;
		}
			
		$condition = " where a.`start` >= {$startDate} AND  a.`start` <= {$endDate} AND  a.`title` NOT LIKE '%e-learning%'";
		 

		
		
		$sql = "SELECT a.`id`, a.`code`, a.`title`, a.`scount`, a.`start`, a.`end`, a.`sessions`,b.signin, c.joined FROM `curriculum` a LEFT JOIN (SELECT `curriculum_id`, COUNT(*) as signin FROM curriculum_logs WHERE `type`= 'signin' GROUP BY `curriculum_id`) b on a.`id` = b.curriculum_id LEFT JOIN (SELECT `curriculum_id`, COUNT(*) as joined FROM curriculum_logs WHERE `type`= 'join' GROUP BY `curriculum_id`) c on a.`id` = c.curriculum_id {$condition} ORDER BY `a`.`start` DESC "; 
		
	
		
		
		$query = $db->query($sql); 
		
		return $query->getResultArray();
		
}



public function importDataFrom($from_id,$to_id){
	
		$db = db_connect();
			
		
		
		$sql = "SELECT * FROM `curriculum_logs` WHERE `baptism_id` = ?"; 
		
		
		$resultObj = $db->query($sql,array($from_id)); 


		
		if($resultObj) {
			
			
			foreach ($resultObj->getResultArray() as $row){
				

					$db->query("INSERT IGNORE INTO `curriculum_logs` (`baptism_id`, `curriculum_id`, `type`, `value`) VALUES (?, ?, ?, ?)",[$to_id,$row['curriculum_id'],$row['type'],$row['value']]); 

			}
			
			
		
			
		}
		
		
		 

		
}










    }