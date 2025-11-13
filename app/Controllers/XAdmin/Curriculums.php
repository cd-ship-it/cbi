<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;

use App\Models\ClassesModel;
use App\Models\WebmetaModel;


class Curriculums extends BaseController
{
	
	
	public function index()
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/curriculums');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('edit_class',1);			

		$action = $this->request->getPost('action');			
		
		
	
		
		$modelClasses = new ClassesModel();		
		$modelWebmetaModel = new WebmetaModel();		
		

		
		$data['pageTitle']='Classes';	
		$data['curriculumCodes']=$this->webConfig->curriculumCodes[$this->lang];	
		
		$data['classes']  =  array();
		
		$ongoingClasses = $modelClasses->getAllPublishedCurriculums();
		foreach($ongoingClasses as $class){
			$data['classes'][$class['code']][] = $class;			
		}
		
		


	$row = $modelWebmetaModel->where('meta_key', 'reminder')->first(); //var_dump($row);exit;
	$data['reminder']=$row['meta_value'];

	if($action=='reminderSave'){
		
		$newReminder = $this->request->getPost('content');
		$r=$modelWebmetaModel->where('meta_key', 'reminder')->set(['meta_value' => $newReminder])->update();
		if($r){
			echo 'OK';
		}
		
		exit;
	}	
		

				

			$data['menugroup'] = 'classes';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_curriculums';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);	

	
		
		}
			
		

		
		
	
	


	//--------------------------------------------------------------------

}
