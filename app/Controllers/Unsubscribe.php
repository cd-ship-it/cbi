<?php namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\CurriculumSubscribeModel;			

class Unsubscribe extends BaseController
{
	public function index()
	{


		$data = $this->data;
		
		$data['canonical']=base_url('unsubscribe');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		

		$modelCurriculumSubscribe = new CurriculumSubscribeModel();	
	 
		$r =  $modelCurriculumSubscribe->where('bid', $this->logged_id )->delete();
		$data= ['bid'=>$this->logged_id,'unsubscribe'=>1];
		$r =  $modelCurriculumSubscribe->save($data);
					
			
		$member_page = base_url('member?v='.rand());
				
				
				
				if($r){
					echo 'Settings saved！系統將不再發送任何課程提醒給您.<br />';
					echo '您可在<a href="'.$member_page.'">Member頁面</a>修改您的設置';
					
				}else{
					echo 'Error';
				}
				

				
				exit();	

	}


	

	//--------------------------------------------------------------------

}
