<?php

    namespace App\Models;

    class VisitorsModel extends \CodeIgniter\Model
    {
		
const  ZONE = array('Married with older kids','Married with younder kids','Married Empty Nesters','Married wit no kids yet','Single','Youth/Students','English Adult','Mandarin as first language');		
		
protected $table = 'visitors';



protected $allowedFields = ['date_visited','campus','name','email','phone','learnAbout','preferred_language','lifeGroup','greeter','welcomepackage_given','notes','lifeStatus','stage_id','assigned_id','delegate_id'];


 
		
		
		

function preferred_language_update(){		

		
	$query = $this->db->query("SELECT `preferred_language` FROM `visitors_backup_0824_r` GROUP by `preferred_language`");	
	
	$result = $query->getResultArray(); 
	
	 
	$rr = [
"all the above" => "Doesn’t Matter",
"both Cantonese and Mandarin" => "Cantonese",
"Cantonese, English and Mandarin" => "Doesn’t Matter",
"Cantonese, Mandarin, Indonesian" => "Doesn’t Matter",
"Cantonese/English" => "Doesn’t Matter",
"Cantonese/English/Mandarin" => "Doesn’t Matter",
"Cantonese/Mandarin" => "Cantonese",
"doesn't check this box" => "Cantonese",
"Doesn\'t matter" => "Doesn’t Matter",
"English/Mandarin" => "Doesn’t Matter",
"heard about our church from Chinese Christian Herald Newspaper" => "Doesn’t Matter",
"Indian" => "Doesn’t Matter",
"Mandarin/Cantonese/English" => "Doesn’t Matter",
"Maybe" => "Doesn’t Matter",
"N/A" => "Doesn’t Matter",
"No" => "Doesn’t Matter",
"not check this question" => "Doesn’t Matter",
"Peferred Language Cantonese" => "Cantonese",
"Peferred Language English" => "English",
"Peferred Language Mandarin" => "Mandarin",
"Preferred both Cantonese and English" => "Doesn’t Matter",
"select both Cantonese and English" => "Doesn’t Matter",
"Winnie can speak cantonese" => "Cantonese",
"Yes" => "Doesn’t Matter",
"Yes (Peferred Language Cantonese)" => "Cantonese",
"Yes (Peferred Language English)" => "English",
"Yes (Peferred Language Mandarin)" => "Mandarin",
"台山話" => "Doesn’t Matter"
	];
	
	
	foreach($result as $row){
		
		if(isset($rr[$row['preferred_language']])){
			
		echo $row['preferred_language']  . ' => ' . $rr[$row['preferred_language']];
			echo '<br />';
			
			
		}
	}
	
	

	
}	 
		
		

function get_campuses(){		

	$db = db_connect();		
	$builder = $db->table('campuses');	
	
	
	$r = $builder->get()->getResultArray();
	
	return $r;
	
		
}	 

 
		
		
		

function get_visitor_life_status(){		

	$db = db_connect();		
	$builder = $db->table('visitor_life_status');	
	
	
	$r = $builder->get()->getResultArray();
	
	return $r;
	
		
}	 
		
		
		

function get_visitor_stage(){		

	$db = db_connect();		
	$builder = $db->table('visitor_stage');	
	
	
	$r = $builder->get()->getResultArray();
	
	return $r;
	
		
}		



function getLgObs($uid,$stage_id,$start,$end,$campus){

	
	$this->select('preferred_language');
	
	$this->groupBy("preferred_language");	

	$this->orderBy('preferred_language ASC');
	
	$this->whereNotIn('preferred_language',['']); 
	
	
	if($uid) $this->groupStart()->where('assigned_id', $uid)->orWhere('delegate_id', $uid)->groupEnd();
	
	if($stage_id) $this->where('stage_id', $stage_id);
	
	if($start){
		$this->where('date_visited >=', $start);
	}else{
		$this->where('date_visited >=', '2021-07-01');
	}
	
	if($end){
		$this->where('date_visited <=', $end);
	}else{
		$this->where('date_visited <=',  date('Y-m-d'));
	}
	
	if($campus) $this->where('campus =', $campus);	
	
	
	
	
	$r = $this->get()->getResultArray(); 
	
	if($r){
		$rr =  array_column($r,"preferred_language");
	}else{
		$rr = [];
	}
 
	array_unshift($rr,'Peferred Language');
	
	return $rr;
	
	
	
	
	
}


function getCaseOwnerObs($campus){

	
	$this->select('CONCAT(baptism.fName," ",baptism.lName) as case_owner_name, baptism.id as case_owner_id');
	
	$this->groupBy("visitors.assigned_id");	
	
	$this->join('baptism', 'baptism.id = visitors.assigned_id','left');	
	
	if($campus) $this->where('campus =', $campus);
	
	$this->where('baptism.id  IS NOT NULL', null);
	
	$r = $this->get()->getResultArray(); 
	

 
	
	return $r ;
	
	
	
	
	
}

function get_summary_for_notifications (){

	
	$this->select('visitors.assigned_id, visitors.stage_id, count(*) as count, CONCAT(baptism.fName," ",baptism.lName) as case_owner_name, baptism.id as case_owner_id, baptism.email as case_owner_email');
	$this->groupBy(["visitors.assigned_id", "visitors.stage_id"]);	
	$this->join('baptism', 'baptism.id = visitors.assigned_id','left');	
	
	$this->where('visitors.assigned_id is not null',null);
	
	$r = $this->get()->getResultArray(); 
	

 
	
	return $r ;
	
	
	
	
	
}


function getAreachart ($start,$end,$campus){
	//SELECT `date_visited`,`campus`,count(*) as count FROM `visitors` group by `date_visited`,`campus`

	
	$this->select('date_visited, campus, count(*) as count');
	$this->groupBy(["date_visited", "campus"]);	

	$this->where('date_visited >=', $start);
	$this->where('date_visited <=', $end);
	
	if($campus) $this->where('campus =', $campus);
	
	$r = $this->get()->getResultArray(); 
	

 
	
	return $r ;
	
	
	
	
	
}

function countByMonth ($start,$end,$stage_id,$campus){
	
// SELECT DATE_FORMAT(created, '%Y') as 'year',
// DATE_FORMAT(created, '%m') as 'month',
// COUNT(id) as 'total'
// FROM table_name
// GROUP BY DATE_FORMAT(created, '%Y%m')
	$_end = date('Y-m',strtotime('+1 months',strtotime($end)));
	
	$this->select("DATE_FORMAT(date_visited, '%Y-%m') as 'month', count(id) as total,campus");
	$this->groupBy("DATE_FORMAT(date_visited, '%Y-%m'),campus");	

	$this->where('date_visited >=', $start);
	$this->where('date_visited <=', $_end);
	
	if($campus) $this->where('campus =', $campus);
	if($stage_id) $this->where('stage_id', $stage_id);
	
	$r = $this->get()->getResultArray(); 
	
	$current_timestamp = $start_timestamp = strtotime($start);
	$end_timestamp = strtotime($end);
	
	$base_arr = [];
 
  while ($current_timestamp <= $end_timestamp) {
	
	$key = date('Y-m',$current_timestamp);
	$base_arr[$key] = 0;



    $current_timestamp =  strtotime('+1 month',$current_timestamp);
  }
 
	
	$groupByCampus_arr=[];
	foreach($r as $row){
		$key = md5($row['campus']);
		$groupByCampus_arr[$key]['campus'] = $row['campus'] ; 
		$groupByCampus_arr[$key]['data'][$row['month']] = (int) $row['total'] ; 
	}
	
	
	foreach($groupByCampus_arr as $key => $item){
		$groupByCampus_arr[$key]['data'] = array_merge( $base_arr ,$item['data']);
	}
	
	
	return array_values($groupByCampus_arr);
	
	
}



function getZoneSummary ($campus,$start,$end){
		
	$this->select('lifeStatus, campus,stage_id, count(*) as count');
	$this->groupBy(["lifeStatus", "campus","stage_id"]);	

	$this->where('date_visited >=', $start);
	$this->where('date_visited <=', $end);
	
	if($campus) $this->where('campus =', $campus);
	
	
	$this->orderBy('campus ASC','lifeStatus ASC');
	
	$r = $this->get()->getResultArray(); 
	$rr = [];
	
	foreach($r as $row){
		
		if(!$row['lifeStatus']||$row['lifeStatus']=='Not sure'){
			$lifeStatus = 'Adults/Not sure/General';
		}else{
			$lifeStatus = $row['lifeStatus'];
		}
		
		$key = 'key_'.md5($row['campus'].$lifeStatus);
		
		 
		
		if(isset($rr[$key][$row['stage_id']])){
			$rr[$key][$row['stage_id']] =  $row['count'] + $rr[$key][$row['stage_id']];
		}else{
			$rr[$key][$row['stage_id']] =  $row['count'];
		}
		
		$rr[$key]['campus'] = $row['campus'];
		$rr[$key]['lifeStatus'] = $lifeStatus;
		
		

	}
	
	
	
	return $rr ;
	
	// echo $r = $this->getCompiledSelect();
}

function getSummary ($uid,$stage_id,$start,$end,$campus,$zone){
	
	
	$this->selectCount('id','count');

	if($uid) $this->groupStart()->where('assigned_id', $uid)->orWhere('delegate_id', $uid)->groupEnd();
	if($stage_id) $this->where('stage_id', $stage_id);
	
	if($start){
		$this->where('date_visited >=', $start);
	}else{
		$this->where('date_visited >=', '2021-07-01');
	}
	if($end) $this->where('date_visited <=', $end);
	
	if($campus) $this->where('campus =', $campus);
	
	if($zone && strtolower($zone) == 'not sure'){
		$this->whereNotIn('lifeStatus', self::ZONE);
	}elseif($zone){
		$this->where('lifeStatus =', $zone);
	}
	
	$r = $this->get()->getRow();
	
	if($r){
		
		return $r->count ;
		
	}else{
		
		
		return 0 ;
	}


	
	
	
	
	
}

function getNewVisitorsPerCampus($start, $end){
	
	$db = db_connect();
	$escapedStart = $db->escape($start);
	$escapedEnd = $db->escape($end);
	
	$builder = $db->table('campuses');
	$builder->select('campuses.campus, COALESCE(COUNT(visitors.id), 0) as count');
	$builder->join('visitors', "visitors.campus = campuses.campus AND visitors.stage_id = 1 AND visitors.date_visited >= {$escapedStart} AND visitors.date_visited <= {$escapedEnd}", 'left', false);
	$builder->groupBy('campuses.id, campuses.campus');
	$builder->orderBy('campuses.campus ASC');
	
	$r = $builder->get()->getResultArray();
	
	return $r ? $r : [];
}

function getDetail($visitors_id){
	
	$this->select('visitors.*,visitor_stage.display_name as stage, CONCAT(baptism.fName," ",baptism.lName) as case_owner_name, baptism.id as case_owner_id,DATE_FORMAT(visitors.date_visited, "%m/%d/%Y") as visited');
	
	$this->join('visitor_stage', 'visitor_stage.id = visitors.stage_id','left');
	
	$this->join('baptism', 'baptism.id = visitors.assigned_id','left');
	
	$this->where('visitors.id', $visitors_id);
	

	
	// echo $r = $this->getCompiledSelect();
	
	
	
	$r = $this->get()->getRowArray();

 

	
	return $r ;
	
}




function getEntries($uid,$stage_id,$start,$end,$campus,$peferred_lg){
	
	$this->select('visitors.*,visitor_stage.display_name as stage, CONCAT(baptism.fName," ",baptism.lName) as case_owner_name, baptism.id as case_owner_id,DATE_FORMAT(visitors.date_visited, "%m/%d/%Y") as visited');
	
	$this->join('visitor_stage', 'visitor_stage.id = visitors.stage_id','left');
	
	$this->join('baptism', 'baptism.id = visitors.assigned_id','left');
	
	if($uid) $this->groupStart()->where('assigned_id', $uid)->orWhere('delegate_id', $uid)->groupEnd();
	if($stage_id) $this->where('stage_id', $stage_id);
	
	if($start){
		$this->where('date_visited >=', $start);
	}else{
		$this->where('date_visited >=', '2021-07-01');
	}
	
	
	
	if($end){
		$this->where('date_visited <=', $end);
	}else{
		$this->where('date_visited <=',  date('Y-m-d'));
	}
	
	
	
	 
	
	if($campus) $this->where('campus =', $campus);
	
	if($peferred_lg) $this->where('preferred_language =', $peferred_lg);
	
	// echo $r = $this->getCompiledSelect();
	
	$this->orderBy('visitors.date_visited DESC','case_owner_name ASC');
	
	
	$r = $this->get()->getResultArray(); 

	// var_dump($r);

	
	if($r){
		
		return $r ;
		
	}else{
		
		
		return [] ;
	}


	
	
	
	
	
}





function field_update($vistor_id,$field,$new_val,$old_val,$current_user_id,$log=''){
	
	$this->set($field, $new_val);
	$this->where('id', $vistor_id);
	$this->update(); 


	if($log){

		
		$data['visitor_id'] = $vistor_id;
		$data['field'] = $field;
		$data['old_value'] = $old_val;
		$data['new_value'] = $new_val;
		$data['user_login'] = $current_user_id;
		$data['log'] = $log;
		
		$this->visitor_change_log($data);
	
	}
	
	
}



function visitor_change_log($data){
	
		$db = db_connect();		
		$builder = $db->table('visitor_change_log');	
		
		$builder->insert($data);
	
	
}





function getActivityLog($vistor_id){
	

		$db = db_connect();		
		$builder = $db->table('visitor_change_log');	
		
		$builder->select('visitor_id,log,DATE_FORMAT(change_time, "%m/%d/%Y %H:%i") as change_time');
		
		$builder->where('visitor_id', $vistor_id);
		
		$builder->orderBy('id DESC');
	
	
		$r = $builder->get()->getResultArray(); 
		
		// var_dump($r);
		
		return $r;
	

	
}


function getNewVisitorsForWEmail($after){
	
	return $this->query("SELECT id,date_visited,campus,stage_id,name,email FROM visitors WHERE date_visited >= ? and id not in (SELECT `visitor_id` FROM `visitor_change_log` WHERE `field` = 'email_sent')",[$after])
	->getResultArray(); 


	
}


function getNewVisitorStats($after){
	
	return $this->db->table('visitors v')
	->select('count(v.`assigned_id`) as total,v.`assigned_id`,b.email,concat(b.fName," ",b.lName) as pastor_name')
	->join('baptism b', 'b.id = v.assigned_id')
	->where('date_visited >=', $after)
	->groupBy('assigned_id')
	->get()
	->getResultArray(); 
}


function getOldPastors(){
	
	return  $this->db->table('baptism b')
	->select('concat(b.fName," ",b.lName) as name,b.id,b.email')
	->where('b.id in (SELECT `assigned_id` FROM visitors group by assigned_id)')
	->get()
	->getResultArray(); 
}


function updateVisitor($date_range,$old_pastor,$visitor_stage,$new_pastor){

	if(!$old_pastor||!$visitor_stage){
		 return 0;
	}
	
	if($date_range){
		$this->where('date_visited >=', date('Y-m-d', strtotime("-$date_range days")));
	}

	$this->where('assigned_id', $old_pastor);

	
	$this->where('stage_id', $visitor_stage);
	

	 
	$this->set(['assigned_id' => $new_pastor])->update();

	log_message('info', 'Last query: '.$this->getLastQuery());

	return $this->affectedRows();

}



































    }