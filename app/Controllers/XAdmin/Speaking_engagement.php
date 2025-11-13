<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;



use App\Models\SpeakingEngagementModel; 
use App\Models\CapabilitiesModel; 	
use App\Models\MembersModel;
use App\Models\PtoPostModel;		
use App\Models\ProfilesModel; 
use App\Models\To_doModel;

class Speaking_engagement  extends BaseController
{
	
	
	
 
		
	
	
	
	
	
	
	
	
	public function index()
	{


		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/speaking_engagement');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$data['pageTitle'] = 'Speaking Engagement Application System';
		
		$action = $this->request->getPost('action');
		
		//check Permissions
		$this->webConfig->checkPermission(3);
		
		//model
		$modelSpeakingEngagement = new SpeakingEngagementModel();	
		$modelCapabilities = new CapabilitiesModel();
		$modelMembers = new MembersModel();	

 

							

		$data['pastors'] = $modelMembers->getPastors();			
		$data['senior_pastor'] = $modelCapabilities->get_sp_users('is_senior_pastor'); 
		
		$data['user_apply_entries'] =  $modelSpeakingEngagement->get_user_apply_entries($this->logged_id);	 
		$data['assign_entries'] =  $modelSpeakingEngagement->get_assign_entries($this->logged_id);
		
		$data['all_previous'] =  $modelSpeakingEngagement->get_all_previous_entries();

		if($this->webConfig->checkPermissionByDes('is_senior_pastor')){
			$data['pending_from_pastor_entries'] =	$modelSpeakingEngagement->get_pending_from_pastor_entries();
			$data['waiting_spastor_entries'] =	$modelSpeakingEngagement->get_waiting_spastor_entries();
		}
		
		$data['menugroup'] = 'speaking_engagement';
		$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
		$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
		
		$data['page'] = 'speaking_engagement';
		
		$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);		
		echo view('theme-sb-admin-2/layout',$data);		
		
		
	}//function index()	
	
	
	
	
	public function details($post_id)
	{


		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/speaking_engagement/details/'.$post_id);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$data['pageTitle'] = 'Speaking Engagement Application';
		
		$data['post_id'] = $post_id;
		
		$action = $this->request->getPost('action');
		
		//check Permissions  
		if( !$this->webConfig->checkPermissionByDes('management') ){			
			if($post_id=='apply'){
				$this->webConfig->checkPermissionByDes('is_pastor',1);
			}elseif($post_id=='assign'){
				$this->webConfig->checkPermissionByDes('is_senior_pastor',1);
			}else{
				$this->webConfig->checkPermissionByDes('edit_class',1); 
			}
		}
		
		
		//model
		$modelSpeakingEngagement = new SpeakingEngagementModel();	
		$modelCapabilities = new CapabilitiesModel();	
		$modelMembers = new MembersModel();		
		$modelPtoPost = new PtoPostModel();
		$modelProfiles = new ProfilesModel();
		$modelTo_do = new To_doModel();	
		

		
		$data['senior_pastor'] = $modelCapabilities->get_sp_users('is_senior_pastor'); 
		$data['office_director'] = $modelCapabilities->get_sp_users('is_office_director'); 		

		if(isset($action) && $action=='confirm'){
			
			$confirm_pid = $this->request->getPost('post_id');
				
			
			$post = $modelSpeakingEngagement->get_post($confirm_pid);
			
			$isConflict = $this->checkConflict($post['speaking_start_datetime'], $post['speaking_end_datetime'], $post['assigned_id']);
			
			if($isConflict){
				echo '<div class="card mb-4  border-left-danger">
                                <div class="card-body">
                                   Conflicts with '.$post['assigned_name'].'\'s PTO schedule.
                                </div>
                            </div>';
			}
			
			echo 'Pastor: '.($post['assigned_name']);
			echo '<br />Speaking Date: '.(date('m/d/Y',$post['speaking_start_datetime']));
			// echo '<br />Speaking Start time: '.(date('g:i a',$post['speaking_start_datetime']));
			// echo '<br />Speaking End time: '.(date('g:i a',$post['speaking_end_datetime']));
			// echo '<br />Venue: '.($post['venue']);
			
		
			exit();
			
		}elseif(isset($action) && $action=='getPTO'){
			
			
			$target_id = $this->request->getPost('uid');
			
			echo $this->getPtoInformation($target_id);

			
			exit();
			
		}elseif(isset($action) && $action=='remove_post'){
			
			
			if($this->webConfig->checkPermissionByDes('management')){
				
				$target_id = $post_id;
				
				$r = $modelSpeakingEngagement->where('id', $target_id)->delete();
				
				if($r){
					$modelTo_do->like(['code'=>'general_speaking_'.$target_id.'_'])->delete();
					echo 'OK';
				}else{
					
					echo 'Error';
				}
			}
			

			
			exit();
			
		}elseif($this->request->getPost('confirmed') && ($action=='accept'||$action=='decline')){
			
			
			$item['status_id'] = $action=='accept' ? 1 : 2;
			$item['reason'] = $this->request->getPost('reasons');
			$target_id = $this->request->getPost('post_id');
			$item['last_response_timestamp'] = time();
			
			$thePost =  $modelSpeakingEngagement->get_post($target_id); 
			
			
			$r = $modelSpeakingEngagement->update($target_id,$item);
			
			if($r){
				
						
							
						$modelTo_do->like(['code'=>'general_speaking_'.$target_id.'_'])->delete();
				
						if($action=='decline'){  
							
							$template = SpeakingEngagementModel::EMAIL_TEMPLATE_DECLINED;	
							$name = $thePost['requester_name'];
							$reasons = $item['reason'];
							$link = base_url('xAdmin/speaking_engagement/details/'.$target_id);
							
							$search  = array('[name]', '[reasons]', '[link]');
							$replace = array($name, $reasons, $link);
							$emailMain = str_replace($search,$replace,$template);
							
							$emailSubject =  strstr($emailMain, '<br />', true);
							$emailContent =  strstr($emailMain, '<br />');
							
							$emailTo = $modelProfiles->db_m_getUserField($thePost['requester_id'],'email');
							 
							
							$this->webConfig->sendtomandrill($emailSubject, $emailContent, $emailTo);
							
						}elseif($action=='accept'){  
						
						// "Speaking Engagement Confirmed for [date]<br />Hello Everyone,<br />
						// The speaking engagement on [date] is all set and confirmed by all parties.
						// <br />Event Details:Date: [date]<br />Pastor: [pastor]<br />Venue: [venue]<br />More information: [link]"; 

							
							$template = SpeakingEngagementModel::EMAIL_TEMPLATE_CONFIRMED;	
							
							$date = date("m/d/Y",$thePost['speaking_start_datetime']);
							$pastor = $thePost['assigned_name'];
							$venue = $thePost['venue'];
							$link = base_url('xAdmin/speaking_engagement/details/'.$target_id);
							
							$search  = array('[date]', '[pastor]', '[venue]', '[link]');
							$replace = array($date, $pastor, $venue, $link);
							$emailMain = str_replace($search,$replace,$template);
							
							$emailSubject =  strstr($emailMain, '<br />', true);
							$emailContent =  strstr($emailMain, '<br />');
							
							
							 
							$emailTo[$thePost['assigned_id']] = $modelProfiles->db_m_getUserField($thePost['assigned_id'],'email');
							$emailTo[$data['senior_pastor']] = $modelProfiles->db_m_getUserField($data['senior_pastor'],'email');
							$emailTo[$data['office_director']] = $modelProfiles->db_m_getUserField($data['office_director'],'email');
							
							$emailsVars = [];
							foreach($emailTo as $email){
								$myEmailItem = [];
								$myEmailItem['From'] =  'admin@tracycrosspoint.org';
								$myEmailItem['To'] = $email; 
								$myEmailItem['Subject'] = $emailSubject;
								$myEmailItem['HtmlBody'] = $emailContent;
								
								$emailsVars['template']['se-confirmed'][] = ["email_address" => ["address" => $email], "merge_info" => ['date'=>$date, 'venue'=>$venue, 'subject'=>$emailSubject, 'link'=>$link, 'pastor'=>$pastor]];

								
								$emailsVars[] = $myEmailItem;
							}

							$this->webConfig->Sendbatchemails($emailsVars); 
						
						}				
				
				
				
				
				
				
				echo 'OK';
			}else{
				
				echo 'Error';
			}
			
			exit();
			
		}elseif(isset($action) && $action=='new_speaking_engagement'){
			
				
			
				$item['speaking_start_datetime'] = strtotime($this->request->getPost('speakingDate').' '.$this->request->getPost('speakingTimeStart'));
				$item['speaking_end_datetime'] = strtotime($this->request->getPost('speakingDate').' '.$this->request->getPost('speakingTimeEnd'));
			
				$item['requester_id'] = $this->logged_id;
				
				$item['assigned_id'] =$this->request->getPost('assigned_id');	
				
				$item['requester_name'] = $modelProfiles->getUserName($item['requester_id']);
				
				$item['assigned_name'] = $modelProfiles->getUserName($item['assigned_id']);
				
				$item['request_timestamp'] = time();
				
				$item['venue'] =$this->request->getPost('venue');
				
				$item['address'] =$this->request->getPost('address');
				
				$item['contact_info'] =$this->request->getPost('contact_info');
				
				$item['note'] =$this->request->getPost('note');
				
				$item['status_id'] = ($this->logged_id == $data['senior_pastor'] && $this->logged_id ==  $item['requester_id'] && $this->logged_id ==  $item['assigned_id'] ? 1 : 3 );
				
				
				
				
				
				if($item['speaking_start_datetime'] && $item['speaking_end_datetime'] && ($item['speaking_end_datetime']-$item['speaking_start_datetime']>=0) && $item['requester_id'] && $item['assigned_id'] && $item['venue']){
					
					
					
					$r = $modelSpeakingEngagement->insert($item);
					$insertId = $modelSpeakingEngagement->db->insertID();
					
					if($r){
						
						
						if($item['status_id'] == 3 && $item['requester_id'] == $item['assigned_id'] ){ //apply
							
							$template = SpeakingEngagementModel::EMAIL_TEMPLATE_APPROVAL_NEEDED;	
							$name = $item['requester_name'];
							$link = base_url('xAdmin/speaking_engagement/details/'.$insertId);
							
							$search  = array('[name]', '[link]');
							$replace = array($name, $link);
							$emailMain = str_replace($search,$replace,$template);
							
							$emailSubject =  strstr($emailMain, '<br />', true);
							$emailContent =  strstr($emailMain, '<br />');
							
							$emailTo = $modelProfiles->db_m_getUserField($data['senior_pastor'],'email');
							 
							
							
							$toDoArray['bid'] = $data['senior_pastor'];
							$toDoArray['title'] = $emailSubject;
							
							$toDoArray['content'] =  $emailContent;			
							
							$toDoArray['notification1'] = time() + 3600*24;	
							$toDoArray['repetition'] = '+1 day';
							
							$toDoArray['end'] = $item['speaking_start_datetime'];						
							$toDoArray['status'] = 0;	
							$toDoArray['code'] = 'general_speaking_'. $insertId .'_'.$data['senior_pastor'];	
							
							$is_exist = $modelTo_do->where(['code'=>$toDoArray['code']])->first();
							
							if(!$is_exist && $toDoArray['end']>time()) $modelTo_do->replace($toDoArray);								
							
							
							
							$this->webConfig->sendtomandrill($emailSubject, $emailContent, $emailTo);
							
						}elseif($item['status_id'] == 3 && $item['requester_id'] != $item['assigned_id'] ){ //assign
							
							$template = SpeakingEngagementModel::EMAIL_TEMPLATE_CONFIRMATION;	
							$name = $item['assigned_name'];
							$link = base_url('xAdmin/speaking_engagement/details/'.$insertId);
							
							$search  = array('[name]', '[link]');
							$replace = array($name, $link);
							$emailMain = str_replace($search,$replace,$template);
							
							$emailSubject =  strstr($emailMain, '<br />', true);
							$emailContent =  strstr($emailMain, '<br />');
							
							$emailTo = $modelProfiles->db_m_getUserField($item['assigned_id'],'email');
							 
							
							
										 
										
							$toDoArray['bid'] = $item['assigned_id'];
							$toDoArray['title'] = $emailSubject;
							
							$toDoArray['content'] =  $emailContent;			
							
							$toDoArray['notification1'] = time() + 3600*24;	
							$toDoArray['repetition'] = '+1 day';
							
							$toDoArray['end'] = $item['speaking_start_datetime'];						
							$toDoArray['status'] = 0;	
							$toDoArray['code'] = 'general_speaking_'. $insertId .'_'.$item['assigned_id'];	
							
							$is_exist = $modelTo_do->where(['code'=>$toDoArray['code']])->first();
							
							if(!$is_exist && $toDoArray['end']>time()) $modelTo_do->replace($toDoArray);								
							
							
							
							$this->webConfig->sendtomandrill($emailSubject, $emailContent, $emailTo);							
						
						}elseif($item['status_id'] == 1){
							
							$template = SpeakingEngagementModel::EMAIL_TEMPLATE_CONFIRMED;	
							
							$date = date("m/d/Y",$item['speaking_start_datetime']);
							$pastor = $item['assigned_name'];
							$venue = $item['venue'];
							$link = base_url('xAdmin/speaking_engagement/details/'.$insertId);
							
							$search  = array('[date]', '[pastor]', '[venue]', '[link]');
							$replace = array($date, $pastor, $venue, $link);
							$emailMain = str_replace($search,$replace,$template);
							
							$emailSubject =  strstr($emailMain, '<br />', true);
							$emailContent =  strstr($emailMain, '<br />');
							
							
							 
							$emailTo[$item['assigned_id']] = $modelProfiles->db_m_getUserField($item['assigned_id'],'email');
							$emailTo[$data['senior_pastor']] = $modelProfiles->db_m_getUserField($data['senior_pastor'],'email');
							$emailTo[$data['office_director']] = $modelProfiles->db_m_getUserField($data['office_director'],'email');
							
							$emailsVars = [];
							foreach($emailTo as $email){
								$myEmailItem = [];
								$myEmailItem['From'] =  'admin@tracycrosspoint.org';
								$myEmailItem['To'] = $email; 
								$myEmailItem['Subject'] =  $emailSubject;
								$myEmailItem['HtmlBody'] = $emailContent;
								
								$emailsVars['template']['se-confirmed'][] = ["email_address" => ["address" => $email], "merge_info" => ['date'=>$date, 'venue'=>$venue, 'subject'=>$emailSubject, 'link'=>$link, 'pastor'=>$pastor]];

								
								$emailsVars[] = $myEmailItem;
							}

							$this->webConfig->Sendbatchemails($emailsVars); 							
						}
						
						
						
						
						
						
						
						
						
						echo 'OK';
					}else{
						
						echo 'Error';
					}					
					
				}else{
					
					
					echo 'Error';
					
					
				}			
			
			exit();
		}
		
		

		
		

		
		$data['pastors'] = $modelMembers->getPastors();
		
		
		$data['post'] =  $modelSpeakingEngagement->get_post($post_id); 
		
		if(!$data['post'] && $post_id!='assign' && $post_id!='apply'){
			echo 'post id not found';
			exit();			
		}
		

		if($post_id=='apply'|| ($data['post']['status_id']=='3' && $this->logged_id == $data['post']['assigned_id'])){
			$data['ptoInformation'] = $this->getPtoInformation($this->logged_id,'Your PTO schedule:');
		}elseif($data['post']['status_id']=='3'){
			if($this->logged_id == $data['senior_pastor']||$this->logged_id == $data['office_director']||isset($this->userCaps['management'])){
				$data['ptoInformation'] = $this->getPtoInformation($data['post']['assigned_id']);
			}
		}
		
		
		$data['menugroup'] = 'speaking_engagement';
		$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
		$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
		
		
		if($post_id=='assign'||$post_id=='apply'){
			$data['page'] = 'speaking_engagement_new';
		}else{
			$data['page'] = 'speaking_engagement_details';
		}
		
		
		$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);		
		
		
		echo view('theme-sb-admin-2/layout',$data);		
		
		
	}//function details()	
	
	
	
	
	private function getPtoInformation($assigned_id,$title='')
	{

	
		//model
		$modelPtoPost = new PtoPostModel();
		$modelProfiles = new ProfilesModel();
		 
		$ptoInformation = $modelPtoPost->where(['bid'=>$assigned_id,'end >='=>time(),'status >='=>1])->orderBy('start', 'ASC')->findAll();
		
		
		if(!$title) $title =  $modelProfiles->getUserName($assigned_id)."'s PTO schedule:";
		
		$html =  '<div class="card mb-4 py-3 border-left-danger"><div class="card-body"><h5 class="mb-3">'.$title.'</h5>';
		
		if($ptoInformation){
			foreach($ptoInformation as $ptoPost){
				
				if($ptoPost['status']==1){
					$status = 'Awaiting Approval';
				}elseif($ptoPost['status']==0){
					$status = 'Disapproved: Please read remark';
				}elseif($ptoPost['status']==-1){
					$status = 'Cancelled by user';
				}else{
					$status = 'Approved';
				}
			
				$item = $ptoPost['types'].' ('. date("m/d/Y",$ptoPost['start']) .  ($ptoPost['end']&&$ptoPost['start']!==$ptoPost['end']? '-'.date("m/d/Y",$ptoPost['end']) : '')  . ')' . ' ['.$status.']' ;
			
				$html .=   '<p>'.$item.'</p>';
			}
		}else{
			$html .=  ' N/A';
		}
		
		$html .=  '</div></div>';	
		
		return $html;
		
	}//function getPtoInformation()	
	
	
	private function checkConflict ($start, $end, $assigned_id) {
		
		
		
		//model
		$modelPtoPost = new PtoPostModel();
		 
		$ptoInformation = $modelPtoPost->where(['bid'=>$assigned_id,'end >='=>time(),'status >='=>1])->orderBy('start', 'ASC')->findAll(); 
		
		if($ptoInformation){
			foreach($ptoInformation as $ptoPost){
				

				if ($start <= $ptoPost['end'] && $ptoPost['start'] < $end) {			
					return true;
				}

			}
		}
		
		

		
		return false;		

	}//function checkConflict()	
	
	
	
	
	
	
	
	
	
	
	

}// class Speaking_engagement 