<?php

    namespace App\Models;

    class ReportModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'zone_report';
protected $allowedFields = ['a1','a2','a3','a4','a_comment','b1','b2','b3','b4','b_comment','c1','c_comment','p1','p2','p3','p4','bid','month'];


function removeUsers($gid,$ids){
	
		$db = db_connect();
		
		$builder = $db->table('group_member');	

		$builder->where('group_id',$gid)->whereIn('baptism_id',$ids)->delete(); 		
	
		
}



function get_data_for_chart($month=false){
	
		$db = db_connect();
		
		$w = $month ? 'month >= '.$month : '1';
		
		$sql = "SELECT CONCAT( b.fName,' ', b.lName) as pastor, chartdata.* FROM baptism b join (SELECT bid,GROUP_CONCAT(month) as month,GROUP_CONCAT(a1) as a1,GROUP_CONCAT(a2) as a2, GROUP_CONCAT(a3) as a3,GROUP_CONCAT(a4) as a4, GROUP_CONCAT(b1) as b1, GROUP_CONCAT(b2) as b2,GROUP_CONCAT(b3) as b3,GROUP_CONCAT(b4)  as b4 FROM `zone_report` WHERE {$w} group by bid order by month asc) as chartdata on chartdata.bid = b.id order by pastor asc";
				
		$query = $db->query($sql);

		return $query->getResultArray();
	
		
}


function get_other_report($uid,$is_x_pastor=false){
	
		$db = db_connect();
		$builder = $db->table('zone_report');	
		
		if(!$is_x_pastor){
			$sql = "SELECT z.*, CONCAT( b.fName,' ', b.lName) as author FROM `zone_report` z left join baptism b on z.bid = b.id WHERE `bid` in (SELECT VALUE FROM capabilities WHERE bid = {$uid} and capability like '%view_report%') and z.bid !=  {$uid}  order by author ASC, z.month DESC"; 
		}else{
			$sql = "SELECT z.*, CONCAT( b.fName,' ', b.lName) as author FROM `zone_report` z left join baptism b on z.bid = b.id where  z.bid !=  {$uid} order by author ASC, z.month DESC"; 
		}
				
		$query = $db->query($sql);

		return $query->getResultArray();
	
		
}



///////////////////////////////////////
    }