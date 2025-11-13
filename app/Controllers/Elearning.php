<?php namespace App\Controllers;


use App\Models\ElearningModel;
use App\Models\WebmetaModel;
use App\Models\ProfilesModel;
use App\Models\ClassesModel;
use App\Models\CapabilitiesModel;
use App\Models\MembersModel;
		


class Elearning extends BaseController
{
	
	
	public function index()
	{
		

		
		
		
		
		
		
	}	
	
	
	
	public function class($class_id,$session_id=0,$mode='')
	{
		
		
		
		$data = $this->data;
		
		$url = 'elearning/class/'. $class_id . ($session_id?'/'.$session_id:'');
		
		$data['mode']=$mode;
		
		$data['canonical']=base_url($url);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$action = $this->request->getPost('action');
		$return = [];		


		////model////
		$modelProfiles = new ProfilesModel();
		$modelElearning = new ElearningModel();	
		$modelCapabilities = new CapabilitiesModel();
		$modelMembers = new MembersModel();
		$modelClasses = new ClassesModel();

		$logged_user = $modelProfiles->getBaptistbyId($this->logged_id);
		$data['class_title'] = $data['pageTitle'] = $modelElearning->getClassTitle($class_id);
		$data['class_sessions'] = $modelElearning->getClassSessions($class_id);
		$data['last_session'] = $modelElearning->getLastSession($class_id);
		$data['user_data'] = $user_data = $modelElearning->get_user_data($this->logged_id,$class_id);
		
		
		
	
		
		if(!$data['class_title'] || !$data['class_sessions']){
			echo 'Error';
			exit;
		}
		
		$data['class_id'] = $class_id;
		$data['user_session'] = $session_id ? $session_id : $data['class_sessions'][0]['id'];
		
		$senior_pastor =  $modelCapabilities->get_sp_users('is_senior_pastor');
		$exclude = [$senior_pastor];		
		$data['pastors'] = $this->modelPermission->getPermittedUsers('is_pastor');		



		
		$data['userInfo'] = $modelElearning->getUserInfo($this->logged_id); 
		
		
		
		
		if(isset($user_data['usession'])&&$user_data['usession']==99){
			
			$data['user_session'] = 0;
			$data['campuses'] = $this->webConfig->sites;
			
			$data['mainContent'] = view('elearning_completed',$data);
			echo view('elearning_layout',$data);
			exit();
		}			
		

		
		
		if($logged_user['zPastor']===null||!$user_data){
			
			if($action=='zPastor_update'){
				
			
				$u_campus = $this->request->getPost('campus');
				$u_zPastor = $this->request->getPost('zPastor');
				
				


				
				
				
				$r1 = $modelProfiles->update($this->logged_id, array('site' => $u_campus, 'zPastor' => $u_zPastor));
				
				
				
				$newStu = array('baptism_id'=>$this->logged_id,'class_id'=>$class_id,'took_on'=>time());				
				$r2 = $modelElearning->save($newStu);
				
				
				if($r1&&$r2){
					$return = ['code'=>'ZPASTOR_UPDATED' , 'url'=> base_url('elearning/class/'. $class_id) ];
				}
				
				echo json_encode($return);
				
				exit();
				
				//if($action=='zPastor_sm')				
			}
			
			$data['user_session'] = 0;
			$data['campuses'] = $this->webConfig->sites;
			
			$data['mainContent'] = view('elearning_form',$data);
			echo view('elearning_layout',$data);
			exit();
		}
		
		
		
		$data['user_ans'] = $user_ans = $user_data['ans'] ? json_decode($user_data['ans'],true) : [];	
		$data['user_zPastor'] = $modelProfiles->getUserName($logged_user['zPastor'],1);
		
		if(!$action && !$session_id){
			$usession = $user_data['usession'];
			
			if($usession){
				$url = base_url('elearning/class/' . $class_id . '/' . $usession);
				header("Location: $url");
				exit();				
			}			
		}
		// var_dump($data['user_session']);  
		
		if($action=='submit_and_next'){
			
			
			
			
			
			
			
			
			
			
			foreach($_POST as $key => $item){
				
				if(preg_match('#q\-(\d+)#', $key, $matches)){
					
					$user_ans[$matches[1]] = $item;	
					
				}elseif($key=='qa'){
					
					$modelElearning->qa_update($this->logged_id,$data['user_session'], $item);
					
				}
				
				
			}
			
			
			
			
			
			if($data['user_ans'] != $user_ans) $modelElearning->ans_update($user_data['id'],$class_id, $user_ans);
			
			
			
			
			
			if($this->request->getPost('saveProgress')){			

				$r = $modelElearning->update($user_data['id'], array('usession'=>$data['user_session']));
				
				if($r){
					$return['code'] = 'PROGRESS_UPDATED';
				}	
				
				echo json_encode($return);
				
				exit();
				
			}				
				
			
			
			
			
			
			$return['is_last_session'] = $return['url'] = false;
			foreach($data['class_sessions'] as $key => $item){
				
				
				if($item['id']==$data['user_session']){
					$next = $key+1;
					
					if(isset($data['class_sessions'][$next])){
						$return['url'] = base_url('elearning/class/'.$class_id.'/'.$data['class_sessions'][$next]['id']);
						
						if($user_data['usession']<$data['class_sessions'][$next]['id']) $modelElearning->update($user_data['id'], array('usession'=>$data['class_sessions'][$next]['id']));
						
					}
					
					
				}
				
				
			}
			
			if($data['last_session'] == $data['user_session']){
				
				
						$emailsVars = []; 
						
						$return['url'] = base_url('elearning/class/'.$class_id);
						$modelElearning->update($user_data['id'], array('usession'=>99));	

						if($logged_user['zPastor']){

							$emailUrl = base_url('elearning/scores/'.$class_id.'/'.$this->logged_id);

							
							
							$message = 'Hi Pastor '.$data['user_zPastor'].':<br /><br />';						
							$message .= $this->logged_name. ' has just completed Online Class 101. Please click the link to review the score and approve their completion of the class. Please be reminded that the course will not be considered complete without your approval.<br /><br />';	
							
							$message .='<a href="'.$emailUrl.'">'.$emailUrl.'</a>';


							$myEmailItem = [];
							$myEmailItem['From'] =  'admin@tracycrosspoint.org';
							$myEmailItem['To'] = $modelProfiles->db_m_getUserField($logged_user['zPastor'],'email'); 
							$myEmailItem['Subject'] =  $this->logged_name.' has just completed Online Class 101';
							$myEmailItem['HtmlBody'] = $message;
							
							$emailsVars[] = $myEmailItem;
							$emailsVars['template']['custom-msg'][] = ["email_address" => ["address" => $myEmailItem['To']], "merge_info" => ['subject'=>$myEmailItem['Subject'], 'msg'=>$myEmailItem['HtmlBody']]];
							
							
						}
						
						
						
						if( !in_array($logged_user['inactive'],[3,4,6])){


							$message = '尊敬的學員：<br />您好！<br /><br />';

							$message .= '多謝你，你已完成網上101自學課程。 你的Zone Pastor區牧師將會與你跟進。<br /><br />';	
							
							$message .= '匯點教會鼓勵你成為會員, 請點擊以下連結提交申請：<br /><a href="'.base_url('member/submit').'">'.base_url('member/submit').'</a><br /><br />';
							
							$message .= '願神賜福予你<br /><br />Crosspoint Church匯點教會';
							
							


							$myEmailItem = [];
							$myEmailItem['From'] =  'admin@tracycrosspoint.org';
							$myEmailItem['To'] = $logged_user['email']; 
							$myEmailItem['Subject'] =  '感謝你完成網上101自學課程！';
							$myEmailItem['HtmlBody'] = $message;
							
							$emailsVars[] = $myEmailItem;
							$emailsVars['template']['custom-msg'][] = ["email_address" => ["address" => $myEmailItem['To']], "merge_info" => ['subject'=>$myEmailItem['Subject'], 'msg'=>$myEmailItem['HtmlBody']]];
			
							
						}


						if($emailsVars) $this->webConfig->Sendbatchemails($emailsVars); 
						
						
			}	
			
			
			$return['code'] = 'ANS_UPDATED';
			
			
			echo json_encode($return);
			
			exit();
			
			//if($action=='ansSubmit')	
				
		}
		
		
		
		///session details////
		$data['session_details'] = $modelElearning->getSessionDetails($data['user_session']);
		$data['session_questions'] = $modelElearning->getSessionQuestions($class_id,$data['user_session']);
		$data['user_qa'] = $modelElearning->get_user_qa($this->logged_id,$data['user_session']);
		
		// var_dump($data['user_ans']);

			
		if(!$data['session_details']){
			echo 'Error';
			exit;
		}
		
		
	 

		if($mode=='record'){
			$data['mainContent'] = view('elearning_session_record',$data);
		}else{
			$data['mainContent'] = view('elearning_session',$data);
		}
		
		
		echo view('elearning_layout',$data);
		exit();		
		
 
	}



	
	public function scores($class_id,$baptism_id)
	{
		
		
		
		$data = $this->data;
		
		$url = 'elearning/scores/'. $class_id . '/'. $baptism_id ;
		
		$data['canonical']=base_url($url);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);
		
		$action = $this->request->getPost('action');	
		
		////model////
		$modelProfiles = new ProfilesModel();
		$modelElearning = new ElearningModel();	
		$modelCapabilities = new CapabilitiesModel();
		$modelMembers = new MembersModel();
		$modelClasses = new ClassesModel();
		
		
			if($action=='zPastor_update_by_admin'){
				
			
				$u_campus = $this->request->getPost('campus');
				$u_zPastor = $this->request->getPost('zPastor');
				$u_id = $this->request->getPost('uid');
				
				
	 			
				
				
				
				$r1 = $modelProfiles->update($u_id, array('site' => $u_campus, 'zPastor' => $u_zPastor));
				
				if($r1){
					$return = ['code'=>'ZPASTOR_UPDATED'  ];
				}
				
				echo json_encode($return);
				
				exit();
				
				
			}elseif($action=='remove_user_data'){
				
				
				
				
				
				$modelElearning->where('baptism_id', $baptism_id)->delete();
				
				echo 'OK'; 
				exit();				
				
			}		
		

		
		$data['class_title'] = $data['pageTitle'] = $modelElearning->getClassTitle($class_id);
		$data['class_sessions'] = $modelElearning->getClassSessions($class_id);
		
		
		
		if(!$data['class_title'] || !$data['class_sessions']){
			echo 'Error';
			exit;
		}
		
		$data['class_id'] = $class_id;
		
		
		$senior_pastor =  $modelCapabilities->get_sp_users('is_senior_pastor');
		$exclude = [$senior_pastor];		
		$data['pastors'] = $this->modelPermission->getPermittedUsers('is_pastor');		
		
		$data['user_data'] = $modelElearning->get_user_data($baptism_id,$class_id);
		
		if(!$data['user_data']){
			echo 'Error';
			exit;			
		}
		
		
		$data['userInfo'] = $modelElearning->getUserInfo($baptism_id); 
		
		$data['session_questions'] = $modelElearning->getSessionQuestions($class_id);
		$data['user_qa'] = $modelElearning->get_all_user_qa($baptism_id,$class_id);
		$data['user_ans'] = $user_ans = $data['user_data']['ans'] ? json_decode($data['user_data']['ans'],true) : [];	
		
		// var_dump($data['class_sessions']);

		
		
		
		$data['campuses'] = $this->webConfig->sites;
		$data['sidebar'] = view('elearning_side',$data);
		
		$data['mainContent'] = view('elearning_scores',$data);
		echo view('elearning_layout',$data);
		exit();		
 
	}	

		
		
	
		public function table($class_id,$pastor_id=0)
	{
		
		
		
		$data = $this->data;
		
		$url = 'elearning/table/'. $class_id . ($pastor_id?'/'.$pastor_id:'');
		
		$data['canonical']=base_url($url);
		$data['pastor_id']=$pastor_id;
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);
		
		$action = $this->request->getPost('action');	
		
		////model////
		$modelElearning = new ElearningModel();	
		$modelClasses = new ClassesModel();
		$modelProfiles = new ProfilesModel();
		$modelCapabilities = new CapabilitiesModel();
		$modelMembers = new MembersModel();
		
		$data['last_session'] = $modelElearning->getLastSession($class_id);
		
		$senior_pastor =  $modelCapabilities->get_sp_users('is_senior_pastor');
		$exclude = [$senior_pastor];		
		$data['pastors'] = $this->modelPermission->getPermittedUsers('is_pastor');		
		
		if($action=='approve'){
 

		
			$post_id = $this->request->getPost('post_id'); 
			$u_id = $this->request->getPost('uid'); 
			
			$userInactive = $modelProfiles->db_m_getUserField($u_id,'inactive');
			
			
			$eClass = $modelClasses->where('code','CBIF101')->like('title', 'e-learning')->orderBy('id','desc')->first();
			if($eClass){
				
				$r1 = $modelClasses->curriculumJoin($eClass['id'],$u_id,time(),false);
				$r2 = $modelClasses->updateCompletion($eClass['id'],$u_id,'100');
				$r3 = $modelElearning->update($post_id,array('approved'=>1,'usession'=>99,'took_on'=>time()));


				if( !in_array($userInactive,[3,4,6])){
					$modelProfiles->update($u_id,array('inactive'=>4));
					
							$mlogs['bid'] = $u_id;
							$mlogs['by'] = '完成了E-learning CBIF101';
							$mlogs['log'] = 'Status: Guest => Pre-Member';
							$modelProfiles->member_change_log($mlogs);				
					
				}
				

				$return = ['code'=>'APPROVED' ];
				echo json_encode($return);
				
			}else{
				echo 'Error';
			}
			
			exit();
			
			//if($action=='approve')		
		}elseif($action=='re-assign'){
			
			$zPastor = $this->request->getPost('zPastor'); 
			$u_id = $this->request->getPost('uid'); 

			$r1 = $modelProfiles->update($u_id, array('zPastor' => $zPastor));
			
			if($r1){
				$return = ['code'=>'RE_ASSIGN_UPDATED'  ];
			}
			
			echo json_encode($return);
			
			exit();			
			
		}	
			


		$data['students'] = $modelElearning->get_students($class_id,$pastor_id); 
		
		
		if($pastor_id && !count($data['students'])){
			// $data['students'] = $modelElearning->get_students($class_id); 
		}		
		
		
		$data['class_title'] = $data['pageTitle'] = $modelElearning->getClassTitle($class_id);
		
		$data['menugroup'] = 'elearning';
		$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
		$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
		
		$data['page'] = 'elearning_table';
		
		$data['mainContent'] = view($data['page'],$data);		
		echo view('theme-sb-admin-2/layout',$data);	
		
		
 
	}	

		
		
	
	


	//--------------------------------------------------------------------

}
