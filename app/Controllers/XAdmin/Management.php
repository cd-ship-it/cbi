<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\WebmetaModel;
use App\Models\CapabilitiesModel; 	
use App\Models\ProfilesModel;

class Management extends BaseController
{
	
	
	public function index()
	{
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/management');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('management',1);			

		$action = $this->request->getPost('action');	
		

		
		
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();
		$modelCapabilities = new CapabilitiesModel();		
		
		
		
		$data['admins'] = $modelMembersModel->getAdminList();
		$data['pageTitle'] = 'Management';
		
		// need search and change
		// $data['office_director'] =  $modelWebmetaModel->where('meta_key', 'office_director')->first()['meta_value']; 	
		// $data['office_assistant'] =  $modelWebmetaModel->where('meta_key', 'office_assistant')->first()['meta_value']; 	
		// $data['worship_pastor'] =  $modelWebmetaModel->where('meta_key', 'worship_pastor')->first()['meta_value']; 
		
		$data['senior_pastor'] = $modelCapabilities->get_sp_users('is_senior_pastor');
		$data['office_director'] = $modelCapabilities->get_sp_users('is_office_director');
		$data['office_assistant'] = $modelCapabilities->get_sp_users('is_office_assistant');
		$data['worship_pastor'] = $modelCapabilities->get_sp_users('is_worship_pastor');
		$data['temporary_ministry_coordinator'] = $modelCapabilities->get_sp_users('is_temporary_ministry_coordinator');

		
		
		// $data['zonePastors']['milpitas'] = $modelWebmetaModel->where('meta_key', 'milpitas_pastor')->first()['meta_value']; 
		// $data['zonePastors']['peninsula'] = $modelWebmetaModel->where('meta_key', 'peninsula_pastor')->first()['meta_value']; 
		// $data['zonePastors']['pleasanton'] = $modelWebmetaModel->where('meta_key', 'pleasanton_pastor')->first()['meta_value']; 
		// $data['zonePastors']['sanleandro'] = $modelWebmetaModel->where('meta_key', 'sanleandro_pastor')->first()['meta_value']; 
		// $data['zonePastors']['tracy'] = $modelWebmetaModel->where('meta_key', 'tracy_pastor')->first()['meta_value']; 
		// $data['zonePastors']['ezone'] = $modelWebmetaModel->where('meta_key', 'ezone_pastor')->first()['meta_value']; 



		
		
		if($action=='searchUser'){
			
			$r = $modelMembersModel->searchUserByName($this->request->getPost('query'));
			echo json_encode($r); 
			exit();	
			
		}elseif($action=='join'){
			
			$uid = $this->request->getPost('uid');
			$name = $this->request->getPost('name');
			$r = $modelMembersModel->update($uid,array('admin'=>1));
			
			if($r){
				
				$optionsArr = $this->webConfig->config_permission;  
				$optionsHtml = '<select class="permission" data-uid="'.$uid.'">';
				
				foreach($optionsArr as $oprion){
					$selected = $oprion['code'] == 1 ? 'selected' : '';
					$optionsHtml .= '<option '.$selected.' value="'.$oprion['code'].'">'.$oprion['slug'].'</option>';
				}
				
				$optionsHtml .= '</select>';		
				
				echo '<p>'.$name.' | Permission: '.$optionsHtml.' | <a href="javascript:void(0);" class="removeAdmin" data-uid="'.$uid.'">- remove</a> <span></span></p>';
				
				
			}else{
				
				echo 'error';
			} 
			
			
			
			
			exit();	
			
		}elseif($action=='remove'){
			
			$uid = $this->request->getPost('uid');
			
			$r = $modelMembersModel->update($uid,array('admin'=>0));
			
			if($r) {		
				echo 'OK';		
			}else{
				echo 'Error';		
			}
	
			exit();	
			
		}elseif($action=='permissionChange'){
			
			$permission = $this->request->getPost('permission');
			$uid = $this->request->getPost('uid');
			
			$r = $modelMembersModel->update($uid,array('admin'=>$permission));
			
			if($r) {		
				echo 'OK';		
			}else{
				echo 'Error';		
			}
			
			exit();	
			
		}elseif($action=='sp_user_update'){
			
			
			$meta_key = $this->request->getPost('meta_key'); 			
			$bid  = $this->request->getPost('bid') ? $this->request->getPost('bid') : 0 ;
			
				$r=$modelCapabilities->where('capability', $meta_key)->delete();
			
				$r=$modelCapabilities->insert(['capability'=>$meta_key, 'bid' => $bid, 'value' => $bid]);
			
			if($r) {		
				echo 'OK';		
			}else{
				echo 'Error';		
			}
			
			exit();	
			
		}		
				

			$data['menugroup'] = 'users';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_management';

			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);
		
	 
		
		}
			
		

		
	public function zonepastors()
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/management/zonepastors');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('manage_zone_pastors',1);			

		$action = $this->request->getPost('action');			
		
		

		
		
		$modelMembers = new MembersModel();
		$modelCapabilities = new CapabilitiesModel();	
		$modelProfiles = new ProfilesModel();
		
		


		
		$data['pastors'] = $this->modelPermission->getPermittedUsers('is_pastor');
		
		$data['zone_pastors'] = $modelCapabilities->getZonePastors();

		$data['pageTitle'] = 'Zone Pastors Management';
		
		 
		




		
		
		if($action=='setting'){
			
			
			
			
			
			$capability = 'edit_report';
			$uid = $this->request->getPost('uid');
			$val = $this->request->getPost('val');
			
			if($val){
				$cap['bid'] = $uid;
				$cap['capability'] = $capability;
				$cap['value'] = $val;
				
				$r = $modelCapabilities->replace($cap);				
			}else{
				
				$r = $modelCapabilities->where(['bid'=>$uid,'capability'=>$capability])->delete();
			}
			
			
			 	
			
			
			
			
			
			
			exit;
			
		}elseif($action=='view_report_change'){
			
			
			
			$reporter_id = $this->request->getPost('reporter');
			
			$capability = '_view_report_'.$reporter_id;
			$uid = $this->request->getPost('uid');
			$val = $this->request->getPost('val');
			
			if($val){
				$cap['bid'] = $uid;
				$cap['capability'] = $capability;
				$cap['value'] = $reporter_id;
				
				$r = $modelCapabilities->replace($cap);				
			}else{
				
				$r = $modelCapabilities->where(['bid'=>$uid,'capability'=>$capability])->delete();
			}
			
			
			 	
			echo $r?'OK':'Error';
			
			
			
			
			
			exit;			
			
			
			
			exit;
		}elseif($action=='view_report_setting'){
			
			
			$reporter_id = $this->request->getPost('bid');
			$reporter_name =  $modelProfiles->getUserName($reporter_id);
			$pastors_selected = $modelCapabilities->get_allow_view_myReport_pastors($reporter_id);
			

			$senior_pastor =  $modelCapabilities->get_sp_users('is_senior_pastor');
			$exclude = [$senior_pastor,   $this->logged_id];	

			
			$ops = $this->modelPermission->getPermittedUsers('is_pastor');
			
			
			
		echo '<div id="addAdminDiv"><span id="closeTw">x Close</span> <h3 style=" margin: 10px 0; padding: 0; ">Who is authorized to view '.$reporter_name.'\'s reports?</h3><ul>';
		
		
					foreach($ops as $thePastor){
						
						$is_selected =  in_array($thePastor['bid'],$pastors_selected) ? 'checked' :''  ;   
						
						
						 echo '<li><input  data-reporter="'.$reporter_id.'" '.$is_selected.'  type="checkbox" class="view_report_change" id="view_report_option_'. $thePastor['bid'] .'"  value="'. $thePastor['bid'] .'"  > <label for="view_report_option_'. $thePastor['bid'] .'">'. $thePastor['name'] .'</label> <span class="ajmsg"></span></li>';
						 
						 
					}
 
		echo '</ul></div>';	
			
			 
			
			
			
			
			
			
			 	
			
			
			
			
			
			
			exit;
			
		}		
		
			$data['menugroup'] = 'users';		
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);

			$data['page'] = 'admin_management_zonepastors';
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);
		
 
		
	}
		
	public function capabilities($uid=0)
	{
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/management/capabilities');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('management',1);			

		$action = $this->request->getPost('action');
		
		
	
		
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();
		$modelCapabilities = new CapabilitiesModel();	
		$modelProfiles = new ProfilesModel();			
		
	 
		
 
		
		$data['admins'] = $modelMembersModel->getAdminList();
		$data['pageTitle'] = 'Capabilities';
		
		$data['capabilitiesOps'] = $this->webConfig->capabilitiesOps;
		
		$data['uid'] = $uid;
		

		if($uid){
			
			$data['uname'] = $modelProfiles->getUserName($uid,1);
			
			
			
			$data['ucaps'] = $modelCapabilities->getCapabilities($uid); 
			
			if(!$data['uname']){
				
				echo 'Error'; exit;
				
			}			
			
		}
		
		


		
		
		if($action=='searchUser'){
			
			$r = $modelMembersModel->searchUserByName($this->request->getPost('query'));
			echo json_encode($r); 
			exit();	
			
		}elseif($action=='capabilityChange'){
			
			$capability = $this->request->getPost('capability');
			$uid = $this->request->getPost('uid');
			$val = $this->request->getPost('val');
			
			if($val){
				$cap['bid'] = $uid;
				$cap['capability'] = $capability;
				$cap['value'] = $val;
				
				$r = $modelCapabilities->replace($cap);				
			}else{
				
				$r = $modelCapabilities->where(['bid'=>$uid,'capability'=>$capability])->delete();
			}
			
			
			 
			
			exit();	
			
		} 
				


 
			$data['menugroup'] = 'users';		
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);

			$data['page'] = 'admin_capabilities';
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);		
		
 
		
		}
			
		

		
		
	
	


	//--------------------------------------------------------------------

}
