<?php

    namespace App\Models;

    class PtoPostModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'pto_post';

protected $allowedFields = ['bid','types','start','end','notes','status','submit_date'];



function awaitingPostsForPastor($pastorID,$all=false){
	
		$db = db_connect();
		
		
		
		if($all){
			
			$query = $db->query("SELECT a.*, CONCAT(b.`fName`,' ',b.`lName`) as name FROM `pto_post` a join  `baptism` b on a.bid = b.id  where  a.status = 1 ORDER BY a.`id` DESC;");
			
		}else{		

			// $sql = "SELECT a.*, CONCAT(b.`fName`,' ',b.`lName`) as name FROM `pto_post` a join  `baptism` b on a.bid = b.id  where a.bid in (SELECT bid FROM `pto_relation` where `supervisor` = ? or `region_pastor` = ? or `senior_pastor` = ? or `zone_pastor` = ? or `operations_director` = ? ) and a.status = 1 ORDER BY a.`id` DESC;"; 


			$sql = "SELECT CONCAT(b.`fName`,' ',b.`lName`) as name, pp.*,pr.supervisor,GROUP_CONCAT(pc.author) as replyed FROM `pto_post` pp left JOIN pto_relation pr on pp.bid = pr.bid left JOIN pto_comments pc on pp.id = pc.post_id  join  `baptism` b on pp.bid = b.id WHERE pp.status = 1 group by pp.id"; 	
						
			$query = $db->query($sql);	
			
		}		
		
		


		return $query->getResultArray();

		
}





public function notAvailable($excludeIds=[]){
	

 
	
	$this->select('types,start,end,concat(fname,\' \',lname) as name');	
	$this->join('baptism', 'baptism.id = pto_post.bid','left');	
	
	
	$this->where('pto_post.status', 2);
	$this->where('pto_post.end >', time());
	if($excludeIds) $this->whereNotIn('pto_post.bid', $excludeIds);
	$this->orderBy('pto_post.start', 'ASC');
	
	$r = $this->get()->getResultArray(); 
	
	return $r;
	
}



function archivedPostsForPastor($pastorID,$all=false){
	
		$db = db_connect();
		
		$limitEnd = time()-3600*24*366;
		
		if($all){
			
			$query = $db->query("SELECT a.*, CONCAT(b.`fName`,' ',b.`lName`) as name FROM `pto_post` a join  `baptism` b on a.bid = b.id where a.end > {$limitEnd} ORDER BY  b.`fName` ASC;");
			
		}else{		
			$sql = "SELECT a.* , CONCAT(b.`fName`,' ',b.`lName`) as name FROM `pto_post` a  join  `baptism` b on a.bid = b.id   where a.bid in (SELECT bid FROM `pto_relation` where `supervisor` = ? or `region_pastor` = ? or `senior_pastor` = ? or `zone_pastor` = ? or `operations_director` = ? ) and a.end > {$limitEnd}  ORDER BY b.`fName` ASC;"; 
			$query = $db->query($sql,array($pastorID,$pastorID,$pastorID,$pastorID,$pastorID));		
			
		}		
		
		

				
		

		return $query->getResultArray();

		
}








    }