<?php

    namespace App\Models;

    class EzoneModel extends \CodeIgniter\Model
    {
        
		



public function getUserEzoneID($bid,$edb,$DBprefix){
	
		$db = db_connect();	
	
		$sql = "SELECT user_id FROM {$edb}.`{$DBprefix}usermeta` WHERE `meta_key` = 'cbi_ID' AND `meta_value` = ?;"; 
			
			

		
		$row = $db->query($sql,array($bid))->getRowArray(); 
		
		if($row){
			return $row['user_id'];
		}else{
			return false;
		}
	

}

public function getUserEzoneGroupsData($eid,$edb,$DBprefix){
	
		$db = db_connect();	
	
		$sql = "SELECT distinct  a.meta_value as term_id, c.slug, c.name FROM {$edb}.`{$DBprefix}usermeta` a JOIN {$edb}.{$DBprefix}users b on a.`user_id` = b.ID LEFT JOIN {$edb}.{$DBprefix}terms c on c.term_id = a.meta_value WHERE a.`meta_key` = 'join' and a.meta_value != 0 and b.ID = ?;"; 
			
			
		$query = $db->query($sql,array($eid)); 
		
		
		return $query->getResultArray();
	

}





			
			
			
    }