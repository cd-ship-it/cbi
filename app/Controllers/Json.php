<?php namespace App\Controllers;


use App\Models\ClassesModel;


class Json extends BaseController
{
	public function index($lg='english')
	{	
	
		$class_language = ['en'=>'english', 'zh-Hant'=>'cantonese', 'zh-Hans'=>'mandarin' ];
		
		// if(!in_array($lg,$class_language)){
			// echo 'error';
			// exit;
		// }
	
	
		
		$model = new ClassesModel();
		$classes  = $model->getOngoingClasses();
		
		
		$json = [];
		
		
		
		foreach($classes as $key => $item){
			
			//var_dump($item);
			
			if($item['start']<time()){ continue; }
			
			$json[$key]['class_id'] = $item['id'];
			$json[$key]['class_full_url'] = base_url('class/'.$item['id']);
			
			if($item['title-en']){
				$json[$key]['class_name'] = $item['title-en'];
			}else{
				$json[$key]['class_name'] = $item['title'];
			}
			
			
			$json[$key]['class_name_in_chinese'] = $item['title'];
			
			$json[$key]['class_start_date'] = date("m/d/Y",$item['start']);
			$json[$key]['class_end_date'] = date("m/d/Y",$item['end']);
			$json[$key]['class_start_time'] = date("g:i a",$item['start']);
			
			//$json[$key]['class_language'] = $lg;
			
			
			

			
		}
		
		
		
		
		echo json_encode($json, JSON_UNESCAPED_UNICODE);
	}
	
	
	

	
	

	//--------------------------------------------------------------------

}
