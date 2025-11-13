<?php namespace App\Controllers;


use App\Models\ClassesModel;


class Home extends BaseController
{
	public function index()
	{	
		$webConfig = new \Config\WebConfig();
		$session = \Config\Services::session();
		
		
		$logout = $this->request->getGet('logout');
		$api = $this->request->getGet('api');
		
		$redirectUrl = $this->request->getGet('redirect');
		if($redirectUrl){
			$session->set('redirect', $redirectUrl);
		}
		
		$ref = $this->request->getGet('ref');
		if($ref){
			$session->set('ref', $ref);
		}		
		
		$data['userLg'] = $this->lang;		
		$data['loginMsg'] = '';		
		$data['webConfig'] = $webConfig;	
		
		$data['mloggedinName'] = $session->get('mloggedinName');
		
		
		$data['title'] = 'Welcome to Crosspoint CBI';		
		
		


	
		
		$data['header'] = view('cbi_header',array('userLg'=>$this->lang,'login'=>$session->get('mloggedin')));
		
		if($logout){ 
	
			
			unset($_SESSION['mloggedin']);

			$session->destroy();
			
			$data['header'] = view('cbi_header',array('userLg'=>$this->lang,'login'=>0));
			
			
		}elseif($session->get('mloggedin')){
			
	
			$data['isLogin'] = true;
			

			if($session->get('redirect')){
				
				$url = $session->get('redirect');
				$session->remove('redirect');
				header("Location: $url");
				exit();	
				
			}elseif($webConfig->checkPermissionByDes('dashboard_view')){
				
				$url = base_url('xAdmin?v='.rand());
				$session->remove('ref');
				$session->remove('redirect');
				header("Location: $url");	
				exit();
				
			}else{
				
				$url = base_url('member?v='.rand());
				$session->remove('ref');
				$session->remove('redirect');
				header("Location: $url");	
				exit();
				
			}
			
			
			

		}
		
		$data['isLogin'] = false;
		
		
			$model = new ClassesModel();
			$data['classes'] = $model->getOngoingClasses();			

		if($api=='getClasses'){			
			
			
			echo json_encode($data['classes'],JSON_UNESCAPED_UNICODE);	exit;	
			
		}else{			
			
	
			echo view('cbi_home',$data);			
			
		}
		
	}
	
	
	

	

	//--------------------------------------------------------------------

}
