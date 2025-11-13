<?php

    namespace App\Models;

    class To_doModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'to_do';

protected $allowedFields = ['title','notification1','notification2','notification3', 'end','content','bid','status','repetition','code','merge_info'];



function monthly_report_reminder($month){
	
		$db = db_connect();
		
		$sql = "SELECT bid FROM `capabilities` WHERE bid not in (SELECT bid FROM `zone_report` WHERE `month` = '{$month}') and capability = 'edit_report'"; 
				
		$query = $db->query($sql);

		$array1 =  $query->getResultArray();
		
		$arr = array_map (function($value){
			return $value['bid'];
		} , $array1);
 
		
		return $arr;
    }
	
	
	
	
	
	

function get_general_reminder(){
	
		$currentTime = time();
		
		$this->join('baptism', 'to_do.bid = baptism.id','inner');
		$this->select('to_do.*, CONCAT(baptism.`fName`," ",baptism.`lName`) as name, baptism.email');		
		
			
		$this->where(['end >' => $currentTime, 'status =' => 0]);
		
		$this->like('code','general_');  
			
		$data = $this->get()->getResultArray(); 
		
		return $data;	
	
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
////////////////////////////////////////	
	}	