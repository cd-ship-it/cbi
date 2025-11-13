<?php

namespace App\Models;

class MinistryModel extends \CodeIgniter\Model
{
		protected $table = 'ministries';
		protected $allowedFields = ['zh-Hant','zh-Hans','en'];

		



	public function searchMinistry($key,$excludeIds=false){

		
		
		
		$db = db_connect();
		
		$builder = $db->table('ministries');	
		
		if($excludeIds) $builder->whereNotIn('id', $excludeIds);
		$builder ->groupStart()->orLike('en',$key)->orLike('zh-Hant',$key)->orLike('zh-Hans',$key)->groupEnd();
		$builder->limit(10);
		
		$data = $builder->get()->getResultArray();
		
		return $data;	
		
		
		
		
		
		
		
	
	}	






		
		
		
}