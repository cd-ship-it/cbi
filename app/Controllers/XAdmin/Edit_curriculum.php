<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\ClassesModel;
use App\Models\ProfilesModel;
use App\Models\To_doModel;

class Edit_curriculum extends BaseController
{
	
	
	public function index($cid=0)
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/edit_curriculum/'.$cid);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('edit_class',1);			

		$action = $this->request->getPost('action');			
		
	
		
		$modelClasses = new ClassesModel();	
		$modelMembers = new MembersModel();	
		$modelProfiles = new ProfilesModel();	
		$modelTo_do = new To_doModel();			
		

		


		$data['cid']=$cid;
		
		
		if($cid){
			
			$class = $modelClasses->asArray()->find($cid);
			
			if($class){
				
				
				$data['publicUrl'] = base_url('class/'.$cid); 
				
				if(!$class['classmessage']){
			$class['classmessage'] = "Dear [name]:

Thanks for joining {$class['title']}!

For how to join the online meeting and other information, please log into your account from {$data['publicUrl']}

Information will be added there as it becomes available. Please check back soon before the class for the latest update.

Note that course attendance is recorded by your account. So, each member of the household should create a separate account and register individually or else the histories of all members will be lumped together as one. Please help our church keep informative records to help pastoring and teaching.

If you have further questions, you can email training@crosspointchurchsv.org


In Him,

Grow ministry";
				}
				
				$data['class_d']=$class;
				
				$data['pageTitle']='Edit Curriculum';
				$data['fsubmitLable']='Update';
				
			}else{
				echo 'curriculum ['.$cid.'] not found';
				exit();
			}
			
		}else{
			
			$data['pageTitle']='New Class';
			$data['fsubmitLable']='Submit';			
			
		} 		
		
		

		
		$data['userLg']=$this->lang;
		
		$data['pastors'] = $this->modelPermission->getPermittedUsers('is_pastor');
		$data['interns'] = $modelMembers->getInterns();
		
		$data['pastorsObs'] = array_merge($data['pastors'],$data['interns']);
		

		
		$data['sessions'] =   isset($class['sessions']) && $class['sessions'] ? array_map(function($v){ return date('m/d/Y g:i a',$v);},json_decode($class['sessions'])) : [];		
		$data['session_pastors'] =  isset($class['pastor']) && $class['pastor'] ? json_decode($class['pastor'],true) : [];
		$data['confirmed'] =  isset($class['confirmed']) && $class['confirmed'] ? json_decode($class['confirmed']) : [];	




		if($action){ 
		
			$r = '';

			$updateID = $this->request->getPost('id');
			
			if($action == 'insert'){				
				
				
				$data['class']['code'] =  json_decode($this->request->getPost('code'))[0];
				
				
				$data['class']['title'] = $this->request->getPost('title');
				$data['class']['title-zh-Hans'] = $this->request->getPost('title-zh-Hans');
				$data['class']['title-en'] = $this->request->getPost('title-en');

				
				$data['class']['scount'] = $this->request->getPost('scount');
				
				
				$sessions=array_map(function($v){ return strtotime($v);},$this->request->getPost('sessions'));
				$data['class']['sessions'] = json_encode($sessions);
				
		
				$data['class']['start'] = $sessions[0];
				$data['class']['end'] = strtotime(date("m/d/Y",(end($sessions)+3600*24)))-1;

				

				$data['class']['note'] = $this->request->getPost('note');
				$data['class']['note-zh-Hans'] = $this->request->getPost('note-zh-Hans');
				$data['class']['note-en'] = $this->request->getPost('note-en');
				
				$data['class']['classinfo']  = $this->request->getPost('classinfo');
				$data['class']['classinfo-zh-Hans']  = $this->request->getPost('classinfo-zh-Hans');
				$data['class']['classinfo-en']  = $this->request->getPost('classinfo-en');
				
				$data['class']['material']   = $this->request->getPost('material');
				$data['class']['classmessage'] = $this->request->getPost('classmessage');

				$data['class']['scode'] = $this->request->getPost('scode');	
				$data['class']['is_active'] = $this->request->getPost('is_active');	
				
				$data['class']['zoom_password'] = $this->request->getPost('zoom_password');	
				$data['class']['zoom_url'] = $this->request->getPost('zoom_url');	
				
				$spastor = [];
				if($this->request->getPost('pastor')){
					
					foreach($this->request->getPost('pastor') as $item){
						
						list($sIndex,$bid) = explode('||',$item);
						
						
						
						$stime = $sessions[$sIndex];
						
						$spastor[$stime][] = $bid;
						
					}
					
					
					 
				}
				
				$data['class']['pastor'] = $spastor ? json_encode($spastor) : null;

				
			}
			


			if($updateID && $action=='delete'){
				
				
				
				$code = 'uploadPPT_'. $updateID .'_';	

				$modelTo_do->like('code', $code)->delete();
				
				
				if($modelClasses->delete($updateID)){
					$r = 'OK';	
				}else{
					$r = 'Error';	
				}

				
			}elseif($action=='insert' && $updateID){
				
				$r = $modelClasses->updateCurriculum($updateID,$data['class']);	
				$insertId = $updateID;				
				
			}elseif($action=='insert'){
				
					if($modelClasses->insert($data['class'])){
						$insertId = $modelClasses->db->insertID();
						
						$r= 'Inserted successfully! <br /><a href="'.base_url('class/'.$insertId).'">View Class</a> | <a href="'.base_url('xAdmin/edit_curriculum').'">Add New Class</a>';
						
					}else{
						$r= 'Error';
					}
				
			}elseif($action=='searchPastor'){
				
				
				$str_time  = $this->request->getPost('stime');
				$stime = strtotime($str_time);
			 
				
				foreach($data['pastorsObs'] as $item){
					
					$checked = isset($data['session_pastors'][$stime])&&in_array($item['bid'],$data['session_pastors'][$stime]) ? 'checked' : '';
					
					echo '<li><input '.$checked.' type="checkbox" class="session_pastors" id="p'.$item['bid'].'" data-bid="'.$item['bid'].'" data-name="'.$item['name'].'"> <label for="p'.$item['bid'].'">'. $item['name'] .'</label></li>';
					
					
					
				}
				
	 
				
			}
			
			
			
			if(isset($spastor)){
				
				$code = 'uploadPPT_'. $insertId .'_';	

				$modelTo_do->like('code', $code)->delete();
				
				
				$already_confirmed = $modelClasses->getAlready_confirmed($insertId);
				

				foreach($spastor as $theStime => $pastors){
					
									
									foreach($pastors as $pastor){
										
										if($already_confirmed&&in_array($pastor,$already_confirmed)) continue;
										
										$toDoArray = [];
										
										$toDoArray['bid'] = $pastor;	

										$classData = date('D m/d Y',$theStime);
										
										$toDoArray['title'] = $data['class']['title']." will start soon on ".$classData;	
										
										
									
										
										
										
										$confirmUrl = base_url('xAdmin/edit_curriculum/confirm/'.$insertId);
										
										
										
										$toDoArray['content'] = "
This is a kind reminder that you will teach {$data['class']['title']} on {$classData}. 
<br /><br />
Please click here to confirm that you will be teaching the class:<br />
<a class='button button--' target='_blank' style='padding: 0px 30px;color: #FFFFFF; background-color: #FFC107; border-top: 10px solid #FFC107; border-right: 18px solid #FFC107; border-bottom: 10px solid #FFC107; border-left: 18px solid #FFC107; display: inline-block; text-decoration: none; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); -webkit-text-size-adjust: none; box-sizing: border-box;' href='{$confirmUrl}'>Confirm</a>
<br /><br />
Please upload your PPT here: <br />
<a target='_blank'  href='{$data['class']['material']}'>{$data['class']['material']}</a>
<br /><br />
Zoom Url: <a target='_blank'  href='{$data['class']['zoom_url']}'>{$data['class']['zoom_url']}</a><br />
Zoom Password: {$data['class']['zoom_password']}";			
										
										$toDoArray['notification1'] = $theStime - 3600*24*7;	
										$toDoArray['repetition'] = '+1 day';
										
										$toDoArray['end'] = $theStime;						
										$toDoArray['status'] = 0;	
										$toDoArray['code'] = 'uploadPPT_'. $insertId .'_'.$pastor.'_'.$theStime;	
										
										$is_exist = $modelTo_do->where(['code'=>$toDoArray['code'], 'bid'=>$pastor])->first();
										
										if(!$is_exist && $toDoArray['end']>time()) $modelTo_do->replace($toDoArray);											
										
										
										
										
										
									}
									
									
									
									
				
	
		
				}
				
			}
			
			
			
			echo $r; 
			
			exit();
		}

		
			$data['menugroup'] = 'classes';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_editCurriculum';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			echo view('theme-sb-admin-2/layout',$data);		
		
		}
			
		

	public function notification($cid,$bid,$time)
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/edit_curriculum/notification/'.$cid.'/'.$bid.'/'.$time);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('edit_class',1);			

		$action = $this->request->getPost('action');			
		
		
	
		
		$modelClasses = new ClassesModel();	
		$modelMembers = new MembersModel();		
		

		

		
		
		
		$data['pageTitle']='New Class';
		$data['pageSlug']='edit_curriculum';
		$data['fsubmitLable']='Submit';
		
		$data['userLg']=$this->lang;				

		$data['backPage'] = base_url('xAdmin/edit_curriculum/'.$cid);
	
		if($cid){
			
			$class = $modelClasses->asArray()->find($cid);
			
			if($class){
				
					$session_pastors =  isset($class['pastor']) && $class['pastor'] ? json_decode($class['pastor'],true) : [];			
					
					if( isset($session_pastors[$time]) && is_array($session_pastors[$time]) && in_array($bid,$session_pastors[$time])){
						
						$modelProfiles = new ProfilesModel();
						$data['pastorName'] = $modelProfiles->getUserName($bid);
					
					
					
						$data['pageTitle'] = 'Send Notification';
						
						$classData = date('D m/d Y',$time);
						
						$confirmUrl = base_url('xAdmin/edit_curriculum/confirm/'.$cid);
						
						
						$data['preMessage'] = "Dear Pastor {$data['pastorName']}: 

This is a kind reminder that you will teach {$class['title']} on {$classData}. 

Please click here to confirm that you will be teaching the class:
{$confirmUrl}

Please upload your PPT here: 
{$class['material']}

Zoom Url: {$class['zoom_url']}
Zoom Password: {$class['zoom_password']}

Thank you!

CBI;";							
						
						
					}else{
						
						
						
						echo 'Error';
						exit();						
						
						
						
					}
				
				
			
				
				
				
				
				
				
				
				
				


			}else{
				echo 'curriculum ['.$cid.'] not found';
				exit();
			}
			
		} 		
		
		

		
		
		
		
		$action = $this->request->getPost('action');
		if($action && $action=='send'){
			
			$pastorEmail = $modelProfiles->db_m_getUserField($bid,'email'); 
			
			$eTitle = $class['title']." will start soon on ".date('D m/d Y',$time);	
			
			 
			
			$message = $this->request->getPost('message');	
			
			$message = nl2br(preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1">$1</a>',   $message));
			
			$this->webConfig->sendtomandrill($eTitle, $message, $pastorEmail);		
			
			echo 'Sent successfully';	
			exit();
			
			
			
			
			
			
		}
		
		
		
		
			$data['menugroup'] = 'classes';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'prayer_items_notification';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			echo view('theme-sb-admin-2/layout',$data);	
		
		
		
		
		
	}		
		
	
	

	public function confirm($cid)
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/edit_curriculum/confirm/'.$cid);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		

		$action = $this->request->getPost('action');			
		
		

		
				
		
		$modelClasses = new ClassesModel();	
		$modelMembers = new MembersModel();		
		$modelTo_do = new To_doModel();	
		
	

		$data['backPage'] = base_url('class/'.$cid);

			
			$class = $modelClasses->asArray()->find($cid);
			

			
			if($class){
				
				$confirmed =  $class['confirmed'] ? json_decode($class['confirmed']) : [];
				$session_pastors =  isset($class['pastor']) && $class['pastor'] ? json_decode($class['pastor'],true) : [];	
				$finder = 0;
				
				foreach($session_pastors as $item){
					
					if(in_array($this->logged_id,$item)){
						$finder = 1;
						break;
					}
					
				}
				
					
				if($finder){
					
					
					echo 'Thank you for your confirmation!';
					
					
					$confirmed[] = $this->logged_id;
					
					$confirmed = json_encode(array_filter(array_unique($confirmed)));
					
					$modelClasses->update($cid,array('confirmed'=>$confirmed));
					
					$code = 'uploadPPT_'. $cid .'_'. $this->logged_id .'_';	
									
					$modelTo_do->like('code', $code)->delete();				
					
					
					
				}else{
					
					
							$code = 'uploadPPT_'. $cid .'_'. $this->logged_id .'_';		
											
							$modelTo_do->like('code', $code)->delete();						
					
					
							$noPermission =  'You don\'t have permission to access this page. <a href="'.base_url('xAdmin').'">Back to homepage</a>';
							echo $noPermission;						
							exit();	

				}
				
				

	
					echo '<br />';
					echo '<br />';
					echo 'Class Public URL: <a href="'.$data['backPage'].'">'.$data['backPage'].'</a>';					
									
				
				
				
				
				
				


			}else{
				echo 'curriculum ['.$cid.'] not found';
				$code = 'uploadPPT_'. $cid .'_';
				$modelTo_do->like('code', $code)->delete();				
				exit();
			}
			

		
		
		



		
		
		
		
		
	}		
		
	
	


	//--------------------------------------------------------------------

}
