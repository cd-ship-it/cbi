<?php

    namespace App\Models;

    class PtoCommentsModel extends \CodeIgniter\Model
    {
		
		
protected $table = 'pto_comments';

protected $allowedFields = ['post_id','author','content','approved','submit_date','author_tags'];






public function waiting_operations_director($post_id,$allpastorExOd,$od){
	
		
		$od_commented = $this->where(['post_id'=>$post_id,'author'=>$od])->first();
		
		if($od_commented){
			return false;
		}
	
		$commented = $this->where('post_id =', $post_id)->findColumn('author');
		
		
		$commented =  $commented ? $commented : [];	
		
		$r = (array_diff($allpastorExOd,$commented)==[] ? true : false);
		
		//log_message('error', $r);  
		//log_message('error', json_encode($allpastorExOd));  
		
		return $r;
		
		
	
	

		
 

		
	
}













    }