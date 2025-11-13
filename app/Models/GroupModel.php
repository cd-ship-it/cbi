<?php

    namespace App\Models;

    class GroupModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'group_des';
protected $allowedFields = ['campus','name','pastor','pastor_name','description','tags','tag_value','leader_name','publish','note'];




function searchGroupMember($gid,$query){
	
		$db = db_connect();
			
		$condition = [];
		if(preg_match_all('#[a-z]+#i',$query,$keys)){
			foreach($keys[0] as $k){
				$condition[$k] =  "concat(fName,mName,lName) like '%".$k."%'";
			}			
		}else{
			return [];
		}
		
		
		
		$condition[] = "( b.type = 'join' or b.type is null)";
	
		
		
	
	$sql = "SELECT a.id, concat(a.fName,' ',a.mName,' ',a.lName) as name,   concat(',',GROUP_CONCAT( b.group_id)) as log FROM `baptism` a left join group_member b on a.id = b.baptism_id WHERE  ".implode(' and ',$condition)." group by a.id LIMIT 10"; 
	
		$resultObj = $db->query($sql); 
		
		if($resultObj) {
			
		 $r = $resultObj->getResultArray();
		 
		 foreach($r as $key => $item){  
			 
			 if(preg_match('#,'.$gid.'+$#',$item['log'])){
				
				 $r[$key]['log'] = 'joined';
			 }
			 
		 }
			
		 
		return  $r; 
		
			
		}else{
			return [];
		}
		
		
}


function joinGroup($gid,$bid){
	
	$db = db_connect();
	
	$time =   time();
		
	$sql = "INSERT INTO `group_member` (`id`, `baptism_id`, `group_id`, `type`, `value`) VALUES (NULL, '{$bid}', '{$gid}', 'join', '{$time}');";
	
	$resultObj = $db->query($sql); 		
}

function delGroup($gid){
	
	
	
	$this->delete($gid);
	
	$db = db_connect();
		
	$builder = $db->table('group_member');	

	$builder->where('group_id',$gid)->delete(); 	
}


function getGroupMembers($gid){
	
		$db = db_connect();
	
		$sql = "SELECT a.id, concat(a.fName,' ',a.mName,' ',a.lName) as name, GROUP_CONCAT(b.type) as logs FROM `baptism` a left join group_member b on a.id = b.baptism_id WHERE b.group_id = {$gid} group by  a.id"; 
		
		$resultObj = $db->query($sql); 
		
		if($resultObj) {
			
		 return $resultObj->getResultArray();
			
			
		
			
		}else{
			return [];
		}	
}


function removeUsers($gid,$ids){
	
		$db = db_connect();
		
		$builder = $db->table('group_member');	

		$builder->where('group_id',$gid)->whereIn('baptism_id',$ids)->delete(); 		
	
		
}



function removeAdmin($gid,$ids){
	
		$db = db_connect();
		
		$builder = $db->table('group_member');	

		$builder->where(['group_id'=>$gid,'type'=>'leader'])->whereIn('baptism_id',$ids)->delete(); 		
	
		
}

function addAdmin($gid,$ids){
	
		$db = db_connect();
		
		$builder = $db->table('group_member');	
		
		foreach($ids as $key => $uid){
			
			$data[$key]['baptism_id'] = $uid;
			$data[$key]['group_id'] = $gid;
			$data[$key]['type'] = 'leader';
			$data[$key]['value'] = 1;
			
		}

 

		$builder->ignore(true)->insertBatch($data);		
	
		
}




function searchGroupTagsByKeyword($keyword){
	
		$db = db_connect();
		
		$builder = $db->table('group_tags');	
		
		 
		$builder ->groupStart()->orLike('en',$keyword)->orLike('zh-Hant',$keyword)->orLike('zh-Hans',$keyword)->groupEnd();
		$builder->limit(10);
		
		$data = $builder->get()->getResultArray();
		
		return $data;	
	
		
}


function getGroupTags($tagIds){
	
	
		if(!$tagIds) return [];
	
		$db = db_connect();
		
		$builder = $db->table('group_tags');	
		
		 
		$builder->whereIn('id', $tagIds);
		
		$data = $builder->get()->getResultArray();
		
		 
		
		return $data;	
	
		
}


function addNewTag($val1,$val2,$val3){
	
	
		 
	
		$db = db_connect();
		
		$builder = $db->table('group_tags');	
		
		 
		$data = [
			'en' => $val1,
			'zh-Hant'  => $val2,
			'zh-Hans'  => $val3,
		];

		if($builder->insert($data)){
			return $db->insertID();
		}else{
			return false;
		}
		
		
	
		
}


function deleteGTag($tagid){
	
	
		 
	
		$db = db_connect();
		
		$builder = $db->table('group_tags');	
		
		 
		return $builder->where('id',$tagid)->delete(); 
		
		
	
		
}


function findAllTags(){
	
	
		 
	
		$db = db_connect();
		
		$builder = $db->table('group_tags');	
		
		 
		 
		
		$data = $builder->get()->getResultArray();
		
		 
		
		return $data;	
		
		
	
		
}


function updateGTag($data){
	
	
		 
	
		$db = db_connect();
		
		$builder = $db->table('group_tags');	
		
		 
		 
		
		return $builder->replace($data);
		
		
	
		
}



function updateAdminVal($gid){
	
		$query = $this->db->query("SELECT CONCAT(fName,' ',lName) as name FROM baptism  WHERE id in (SELECT `baptism_id` FROM `group_member` WHERE `group_id` = ? AND `type` = 'leader')",[$gid]);	
		
		$result = $query->getResultArray(); 
		
		$r = [];
		
		foreach($result as $row){
			$r[] = $row['name'];
		}
		
		
		$str =  implode(', ',$r);
		
		$this->update($gid, ['leader_name'=>$str]);
		
	

}

function getGTagVal($ids){
	
	
		 if(!$ids) return '';
	
		$db = db_connect();
		
		$builder = $db->table('group_tags');	
		$builder->select('GROUP_CONCAT(en) as val');
		$builder->whereIn('id',$ids);
		
		
		$row = $builder->get()->getRow();
		
		if($row){
			return $row->val;
		}else{
			return '';
		}
		
	
		
}


function searchGroup1($keyword,$site,$pub=''){
	
	

	
	if($keyword){
		
		$condition[] = "name like '%".$keyword."%'";
		$condition[] = "tag_value like '%".$keyword."%'";
		$condition[] = "leader_name like '%".$keyword."%'";
		$condition[] = "pastor_name like '%".$keyword."%'";
		
	}

		
	if($site){
		$condition[] = "campus like '%".$site."%'";
	}
	

		
	
	if($pub !== '') {
		
		$condition[] = "publish = '".$pub."'";
		
		
	}
	
	$sql = "SELECT * FROM `group_des` WHERE ". implode(' and ',$condition);
		 
		 
		
	$data = $this->query($sql)->getResultArray();
	
	 
	
	return $data;		
		
		

		
	
		
}






function searchGroup($keyword,$site,$pastor,$pub=''){
	
	

	
	if($keyword) $this->groupStart()->orLike('name',$keyword)->orLike('tag_value',$keyword)->orLike('leader_name',$keyword)->groupEnd();
		
	if($site) $this->where('campus',$site);
	
	if($pastor) $this->where('pastor',$pastor);
	
	if($pub !== '') $this->where('publish',$pub);
	
	
		 
		 
		
	$data = $this->get()->getResultArray();
	
	 
	
	return $data;		
		
		

		
	
		
}









    }