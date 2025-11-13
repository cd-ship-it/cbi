<?php namespace App\Controllers;

use App\Models\ClassesModel; 

 

class Classdata extends BaseController
{
	public function index()
	{	
	
	
		$data = $this->data;
		
		$data['canonical']=base_url('classdata');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);

		$action = $this->request->getPost('action');		
	
 
		
	
		
		
 
			
		$data['userLg'] = 'en';		
		$data['mloggedinName'] = $this->session->get('mloggedinName');
		
		
		$action = $this->request->getPost('action');			
		
		$data['isLogin'] = $this->session->get('mloggedin') ? $this->session->get('mloggedin') : false;
		
		$data['userLg'] =  $this->lang;  			
		$data['login'] =  $this->session->get('mloggedin');  

		$data['pageTitle'] = 'Class Data';	

		$data['classData'] = [];		
		$data['startDate'] = $data['endDate'] = '';
	
		if($action == 'search'){
			
			$modelClasses = new ClassesModel();	
			
			$startDate = $this->request->getPost('startDate') ? strtotime($this->request->getPost('startDate')) : 0;	
			$endDate = $this->request->getPost('endDate') ? strtotime($this->request->getPost('endDate')) : 0;	

			 
			$data['classData'] = $modelClasses->getClassData($startDate,$endDate);
			$data['startDate'] = $startDate?date("m/d/Y",$startDate):'';
			$data['endDate'] = $endDate?date("m/d/Y",$endDate):'';
			
			
		}
		
		
		 
			$data['menugroup'] = 'classes';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'class_data';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			echo view('theme-sb-admin-2/layout',$data);		
		
		
	
	
	 
	}
	


	

	//--------------------------------------------------------------------

}
