<?php

    namespace App\Models;

    class PtoRelationModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'pto_relation';

protected $primaryKey = 'bid';

protected $allowedFields = ['supervisor','region_pastor','senior_pastor','zone_pastor','operations_director','balance','ft_hire','update_schedule'];



public function balanceUpdate($bid,$balance){
	$this->where('bid', $bid)->set(['balance' =>  $balance])->update();
}


public function scheduleUpdate($bid,$update_schedule){
	$this->where('bid', $bid)->set(['update_schedule' =>  $update_schedule])->update();
}



public function fieldUpdateByBid($bid,$field,$value){
	return $this->where('bid', $bid)->set([$field =>  $value])->update();
}





function ptolog($data){
	
		$db = db_connect();		
		$builder = $db->table('pto_log');	
		
		$builder->insert($data);

		
}




function getPtoUsersUpdateSchedule(){
	
		$db = db_connect();
		
		$builder = $db->table('members');	
		$builder->join('pto_relation', 'members.bid = pto_relation.bid','inner');
		$builder->select('pto_relation.*');
		$builder->where('pto_relation.`update_schedule` is not null ');
		$builder->where('pto_relation.`ft_hire` is not null ');
		$builder->where('pto_relation.`update_schedule` < '.time());
		$builder->where('members.`admin` BETWEEN 1 AND 8');
		$builder->where("members.`bid` not in (SELECT bid FROM `capabilities` WHERE `capability` = 'pto_apply' and value = 0)");
		$builder->orderBy('members.admin', 'DESC');

	
		// echo $r = $builder->getCompiledSelect(); exit;
		$data = $builder->get()->getResultArray();
		 
		return $data;

		
}




public function pto_maximum_limit_update($value){
	
$db = db_connect();	

$db->query("DROP TRIGGER IF EXISTS before_insert_pto_relation;"); 	
$db->query("DROP TRIGGER IF EXISTS before_update_pto_relation;"); 	
	
$sql1 = "
CREATE TRIGGER `before_insert_pto_relation` BEFORE INSERT ON `pto_relation` FOR EACH ROW 
BEGIN 
 if NEW.balance>{$value}  THEN 
	set NEW.balance={$value}; 
 end if;
END
";	

$sql2 = "
CREATE TRIGGER `before_update_pto_relation` BEFORE UPDATE ON `pto_relation` FOR EACH ROW 
BEGIN 
 if NEW.balance>{$value}  THEN 
	set NEW.balance={$value}; 
 end if;
END
";

$query = $db->query($sql1); 
$query = $db->query($sql2); 


}










    }