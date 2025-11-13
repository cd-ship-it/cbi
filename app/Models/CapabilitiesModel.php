<?php

    namespace App\Models;

    class CapabilitiesModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'capabilities';
protected $allowedFields = ['bid','capability','value'];



function isPtoUser($bid){
	
		$db = db_connect();
		
		$builder = $db->table('members');	
		$builder->join('baptism', 'members.bid = baptism.id','inner');
		$builder->join('pto_relation', 'members.bid = pto_relation.bid','left');
		$builder->select('members.id, CONCAT(baptism.`fName`," ",baptism.`mName`," ",baptism.`lName`) as name, members.admin, members.bid');
		$builder->where('members.`admin` BETWEEN 1 AND 8');
		$builder->where('pto_relation.`ft_hire` is not null AND pto_relation.`operations_director` is not null');
		$builder->where("members.`bid` not in (SELECT bid FROM `capabilities` WHERE `capability` = 'pto_apply' and value = 0)");
		$builder->where('baptism.`id`',$bid);

	

		$data = $builder->get()->getResultArray();
		return $data ? 1 : 0;

		
}

function getCapabilities($bid, $default=[]){
	
	
	$rows = $this->asArray()->where('bid',$bid)->findAll();
	
	
	if(isset($default['slug'])&&$default['slug']=='pastor') $default['is_pastor'] = 1;
	if(isset($default['slug'])&&$default['slug']=='intern') $default['is_intern'] = 1;
	
	
	foreach($rows as $row){
		
		if($row['value']){
			
			$default[$row['capability']] = $row['value'];
			
			if(stripos($row['capability'],'_edit_groups')!==false){
				$default['edit_groups'] = 1;
				
			}
			
			if(stripos($row['capability'],'_view_report')!==false){
				$default['view_report'] = 1;
				
			}
			
			if(stripos($row['capability'],'_delegate')!==false){
				$default['is_delegate'] = 1;
				
			}
			
		}elseif(!$row['value']){
			
			unset($default[$row['capability']]);

		}
		
	}
	
	$default['pto_apply'] = $this->isPtoUser($bid); 
	
	
	return $default;
	
}




function getZonePastors(){
	
	
		$zonePastors = $this->where('capability', 'edit_report')->findColumn('bid');
		
		
		return $zonePastors ? $zonePastors : [];
	
}



function hasCapability($bid,$capability){
	
	
		 
		
		
		return $this->where(['capability'=>$capability,'bid'=>$bid])->first();
	
}





function get_sp_users ($sp,$returnArr=false){ //return array [bid,...]
	
	
		$db = db_connect();	
	
		$sp_users = $this->where('capability', $sp)->findColumn('bid');  
		
		if(!$sp_users){
			
			if($returnArr){
				return [];
			}else{
				return false;
			}	
			
		}else{
			
			if($returnArr){
				return $sp_users;
			}else{
				return $sp_users[0];
			}
			
		}
		
		

		
}






function get_allow_view_myReport_pastors ($bid){
	
	
	
	
		$allow_view_myReport_pastors = $this->where('capability', '_view_report_'.$bid)->findColumn('bid');
		
		
		return $allow_view_myReport_pastors ? $allow_view_myReport_pastors : [];
	
}
































    }