<?php namespace App\Controllers;


use App\Models\ClassesModel;
use App\Models\ProfilesModel;

class Xclass extends BaseController
{
	public function index($cid)
	{	
	
		$webConfig = new \Config\WebConfig();
		$session = \Config\Services::session();	
		
		$action = $this->request->getPost('action');		
		$data['pageUrl'] = $pageUrl = base_url('/class/'.$cid);
		$data['cid'] = $cid;
		
		$modelClassesModel = new ClassesModel();
		$modelProfilesModel = new ProfilesModel();
		
		$data['class'] = $modelClassesModel->asArray()->find($cid);

		if(!$data['class']){
				echo 'Class not found';
				exit(); 
		}
		
		
		if( stripos($data['class']['title'],'e-learning') !== false ){
			
			$url = base_url('elearning/class/1');
			
			header("Location: $url"); 
			
			exit();
			
		}
		
		
		if($this->lang!=='zh-Hant'){
			$data['class']['title'] = $data['class']['title-'.$this->lang] ? $data['class']['title-'.$this->lang] : $data['class']['title'];
			$data['class']['classinfo'] = $data['class']['classinfo-'.$this->lang] ? $data['class']['classinfo-'.$this->lang] : $data['class']['classinfo'];
			$data['class']['note'] = $data['class']['note-'.$this->lang] ? $data['class']['note-'.$this->lang] : $data['class']['note'];
		}
		
		
		$class = $data['class'];
		

		if( $session->get('mloggedin') && $action =='qd' ){
			
			$r = 'Verification Code invalid!';
			$qd_target_date = $this->request->getPost('date');	
			$qd_target_cid = $this->request->getPost('cid');	
			//$qd_target_code = $this->request->getPost('code');	
			
			if($qd_target_date > time()){ 
				
				$r = '簽到功能於 '.date("m/d/Y g:i a",$qd_target_date).' 啟動';
				
			}else{
				
				$r = $modelClassesModel->curriculumSigned($qd_target_cid,$session->get('mloggedin'),$qd_target_date);
				
			}
			
			echo $r; 
			exit();	

		}elseif(isset($_GET['join'])){
			
			$webConfig->checkMemberLogin($pageUrl.'?join='.$_GET['join']);  
			
			if($_GET['join']==0){		
			
				 if( $webConfig->checkPermissionByDes(['is_pastor','is_admin']) ) $modelClassesModel->curriculumLogsRemove($cid,$_SESSION['mloggedin']);
				 
			}else{
				
				$r = $modelClassesModel->curriculumJoin($cid,$_SESSION['mloggedin']);
				
				if($r == 'OK'){
					
					$email = $modelProfilesModel->db_m_getUserField($_SESSION['mloggedin'],'email');
					
					if($email){
						
						
						
						if(!$class['classmessage']){
							
					$class['classmessage'] = "Dear [name]:

Thanks for joining ".$class['title']."!

For how to join the online meeting and other information, please log into your account from [classURL]

Information will be added there as it becomes available. Please check back soon before the class for the latest update.

Note that course attendance is recorded by your account. So, each member of the household should create a separate account and register individually or else the histories of all members will be lumped together as one. Please help our church keep informative records to help pastoring and teaching.

If you have further questions, you can email training@crosspointchurchsv.org


In Him,

Grow ministry";
						}
						
						$classUrl = base_url('class/'.$cid);
						
						$msgCon = str_replace('[name]',ucwords($_SESSION['mloggedinName']),$class['classmessage']);
						
						$msgCon = str_replace('[classURL]','<a href="'.$classUrl.'">'.$classUrl.'</a>',$msgCon);
						
						
						$to = $email;
						$subject = 'Thanks for joining '.$class['title'];

						$message ='
						<html>
						<head>
						<title>'.$subject.'</title>
						</head>
						<body>'.nl2br($msgCon).'</body>
						</html>';				
				
						$rr = $webConfig->sendtomandrill($subject, $message, $to);	
						
					}

				}
			
			}
			
			
			
		}








		if(isset($_SESSION['mloggedin'])&&$_SESSION['mloggedin']){
			
			$data['logs'] =  $logs = $modelClassesModel->getSPSInclass($cid,$_SESSION['mloggedin']);
			
			$logsInfo = [];
			$logsInfoTmp = explode('|||',$logs);
			
			foreach($logsInfoTmp as $item){
				
				if(strpos($item, 'signin')!==false) $logsInfo['signin'][] = strstr($item, 'signin',true);
				
				if(strpos($item, 'finish')!==false) $logsInfo['finish'] = strstr($item, 'finish',true);
				
				if(strpos($item, 'join')!==false) $logsInfo['join'] = true;
				
			}
			
			
			$data['logsInfo'] =  $logsInfo;
			
			
		}



		$data['webConfig'] = $webConfig;
		$data['pageTitle'] = $class['title'];
		
		$data['mloggedinName'] = $session->get('mloggedinName');
		
		$data['curriculumCodes'] = $webConfig->curriculumCodes[$this->lang];
		
		$data['memberUrl'] = base_url('member');
		$data['adminUrl'] = base_url('xAdmin');
		$data['OAuthUrl']  = base_url('OAuth');		
		
		

		$data['header'] = view('cbi_header',array('userLg'=>$this->lang,'login'=>$session->get('mloggedin')));
		$data['userLg'] = $this->lang;
		

		$data['curriculumInfo'] = isset($data['curriculumCodes'][$class['code']])?$data['curriculumCodes'][$class['code']]:false;
		$data['sessions'] = json_decode( $class['sessions']);
		$data['scodeClass'] =  $class['scode'] ? 'scode' : 'noscode';


		echo view('cbi_class',$data);
	
	
	
	
	
	}
	
	
	

	
	

	//--------------------------------------------------------------------

}
