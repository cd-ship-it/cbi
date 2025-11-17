<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;

use App\Models\ClassesModel;
use App\Models\ProfilesModel;
use App\Models\MembersModel;
use App\Libraries\Mypdfmaker;
use App\Models\ShapeModel;
use App\Models\PtoRelationModel;
use App\Models\CapabilitiesModel;
use App\Models\WebmetaModel;

class Baptist extends BaseController
{

	public function user_pto_update(){

		$modelPtoRelation = new PtoRelationModel();

		$this->webConfig->checkPermissionByDes(['is_office_director','is_senior_pastor'],'exit');
				
		$data = $this->data;				
						 
									
		$bid = $dataPtoRelation['bid'] =   $this->request->getPost('bid'); 
		
		if(!$bid){
			return redirect()->to(base_url('xAdmin/baptist'))->with('error', 'Baptist not found');
		}


									
		$dataPtoRelation['supervisor'] = $this->request->getPost('supervisor') ?: NULL; 
		$dataPtoRelation['region_pastor'] =  NULL; 

		if($bid == $data['senior_pastor']&&$data['bot_chair']){
			$dataPtoRelation['supervisor'] = $data['bot_chair'];
		}


		$dataPtoRelation['senior_pastor'] = $data['senior_pastor']; 
		$dataPtoRelation['operations_director'] = $data['office_director']; 
		
		if($this->request->getPost('ft_hire')){
			$dataPtoRelation['ft_hire'] = strtotime($this->request->getPost('ft_hire')); 
			
				
			
			$update_schedule_time = strtotime(  '+1 month' , $dataPtoRelation['ft_hire'] );
			
			while ($update_schedule_time <  time()) {
				
				$update_schedule_time = strtotime(  '+1 month' , $update_schedule_time );
				
			}
			
			$dataPtoRelation['update_schedule'] = $update_schedule_time;
			
		}else{
			$dataPtoRelation['ft_hire'] = $dataPtoRelation['update_schedule'] = NULL ;
		}
		
			$dataPtoRelation['balance'] =  $this->request->getPost('balance') ?: 0; 

			
		
			$userPtoRelation = $modelPtoRelation->where('bid', $bid)->first(); 
								
			$logData['old_value'] = $userPtoRelation ? $userPtoRelation['balance'] : 0 ;
			$logData['new_value'] = $dataPtoRelation['balance'];
			$logData['target'] = $bid;
			$logData['uid'] =  $this->logged_id;	
			$logData['altered_by'] = $this->logged_name;										 
			
			
						
	
 
		
		if($modelPtoRelation->replace($dataPtoRelation)){


			if($logData['old_value']!=$logData['new_value']){
				$modelPtoRelation->ptolog($logData);
			} 



			return redirect()->to(base_url('xAdmin/baptist/'.$bid))->with('success', 'PTO information updated successfully');
		}
		
		return redirect()->to(base_url('xAdmin/baptist/'.$bid))->with('error', 'PTO information update failed');

		 
	}

	
	public function index($bid=0)
	{
		
		
		$data = $this->data;
		
		$url = 'xAdmin/baptist'. ($bid?'/'.$bid:'');
		
		$data['canonical']=base_url($url);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		 

		$action = $this->request->getPost('action');		

		
		

		
		$data['curriculumCodes'] = $this->webConfig->curriculumCodes[$this->lang];
		
		$modelClassesModel = new ClassesModel();	
		$modelProfilesModel = new ProfilesModel();
		$modelCapabilities = new CapabilitiesModel();
		$modelWebmetaModel = new WebmetaModel();
		$modelPtoRelation = new PtoRelationModel();
		$modelMembersModel = new MembersModel();		
		
		$data['pto_maximum_limit'] = $modelWebmetaModel->where('meta_key', 'pto_maximum_limit')->first()['meta_value']; 	
		

		
		$data['current_uid'] = $this->session->mloggedin;
		
		$data['pageTitle'] = 'Add people';
		$data['fsubmitLable'] = 'Submit';
		
		$data['results'] = []; 
		$data['activityLog'] = []; // Initialize activityLog

		$data['bid'] = $bid;
		
		if($bid){
			
				$baptist = $modelProfilesModel->getBaptistbyId($bid);
				
				if(!$baptist){
					echo 'baptist ['.$bid.'] not found';
					exit();
				}
				
				$authorizedUser = $baptist['created_by'] && !$baptist['status'] ? $baptist['created_by'] : 0;
				$data['allowEdit'] = $allowEdit = $this->webConfig->checkPermissionByDes('user_edit') ? true : ($authorizedUser == $this->logged_id);
			
				$data['ptoRelation'] = $modelPtoRelation->where('bid', $bid)->first();  
				
				$data['ft_hire'] = $data['ptoRelation']&&$data['ptoRelation']['ft_hire']?date("m/d/Y",$data['ptoRelation']['ft_hire']):'';
				$data['update_schedule'] = $data['ptoRelation']&&$data['ptoRelation']['update_schedule']?date("m/d/Y",$data['ptoRelation']['update_schedule']):'';
				
				if($data['ft_hire']){
					
					$years =  (time()-$data['ptoRelation']['ft_hire']) / 3600/24/365 ;
					
					if($years>15){
						
						$data['rate_per_month'] =  2.5 ;
						
					}elseif($years>10){
						
						$data['rate_per_month'] =  2 ;
						
					}elseif($years>3){
						
						$data['rate_per_month'] =  1.5 ;
						
					}else{
						
						$data['rate_per_month'] = 1;
						
					}
					
				}else{
					
					$data['rate_per_month'] = false;
					
				}
				
		}
		


		if($action){ 
		
				$r='';

			if($action == 'insert'){

				
			
				$bid = $_POST['bid'];

				$data['user']['fName'] = trim(addslashes($_POST['fName']));
				$data['user']['mName'] = isset($_POST['mName'])?addslashes($_POST['mName']):'';
				$data['user']['lName'] = trim(addslashes($_POST['lName']));
				
				$data['user']['gender'] = $_POST['gender'];
				$data['user']['marital'] = $_POST['marital'];
				$data['user']['birthDate'] = $_POST['birthDate']&&strtotime($_POST['birthDate'])!==false?strtotime($_POST['birthDate']):NULL;
				
				$data['user']['homeAddress'] = addslashes($_POST['homeAddress']);
				$data['user']['city'] = addslashes($_POST['city']);
				$data['user']['zCode'] = addslashes($_POST['zCode']);
				$data['user']['hPhone'] = preg_replace('#[^\d]#i','',$_POST['hPhone']);
				$data['user']['mPhone'] = preg_replace('#[^\d]#i','',$_POST['mPhone']);
				$data['user']['email'] = addslashes($_POST['email']);
				$data['user']['occupation'] = addslashes($_POST['occupation']);
				
				$data['user']['nocb'] = addslashes($_POST['nocb']);
				$data['user']['baptizedDate'] = $_POST['baptizedDate']&&strtotime($_POST['baptizedDate'])!==false?strtotime($_POST['baptizedDate']):NULL;
				
				$data['user']['nopc'] = addslashes($_POST['nopc']);
				
				$data['user']['family'] = addslashes($_POST['family']);
				
				
				$data['user']['site'] = addslashes($_POST['site']);
				
				if(isset($_POST['inactive'])){
					$data['user']['inactive'] = $_POST['inactive'];
				}

				if(isset($_POST['membershipDate'])){
					$data['user']['membershipDate'] = $_POST['membershipDate']&&strtotime($_POST['membershipDate'])!==false?strtotime($_POST['membershipDate']):NULL;
				}
				
				if($this->request->getPost('picture')) $data['user']['picture'] = $this->request->getPost('picture');
				
				
				$data['user']['bornagain'] = $_POST['bornagain']==''?NULL:$_POST['bornagain'];
				$data['user']['attendingagroup'] = $_POST['attendingagroup']==''?NULL:$_POST['attendingagroup'];
				$data['user']['baptizedprevious'] = $_POST['baptizedprevious']==''?NULL:$_POST['baptizedprevious'];		
				$data['user']['byImmersion'] = $_POST['byImmersion']==''?NULL:$_POST['byImmersion'];		
				
				$data['user']['lifegroup'] = addslashes($_POST['lifegroup']);
				$data['user']['cName'] = addslashes($_POST['cName']);

				$data['user']['certificate'] = $this->request->getPost('baptismCertificate')?:'';
				$data['user']['testimony'] = $this->request->getPost('testimony')?json_encode($this->request->getPost('testimony')):'[]';

				
				if(!$bid){
					
					$data['user']['created_by'] = $this->logged_id;
				
					if($this->webConfig->checkPermissionByDes('user_add') && $modelProfilesModel->insert($data['user'])){
						$insertId = $modelProfilesModel->db->insertID();
						
						$r= 'Inserted Successfully<br /><a href="'.base_url('xAdmin/baptist/'.$insertId).'">Edit</a> | <a href="'.base_url('xAdmin/baptist').'">Add New Baptist</a>';
						$mlogs['bid'] = $insertId;
						$mlogs['by'] = $modelProfilesModel->getUserName($this->logged_id,1);
						$mlogs['log'] = 'Created by Admin: '.$this->logged_name;
						$modelProfilesModel->member_change_log($mlogs);						
				
					}else{
						$r= 'Error';
					}

			}else{
				
				$modifiedBy = $modelProfilesModel->getUserName($this->logged_id,1);	

				// Get old user data before updating
				$oldUser = $modelProfilesModel->getBaptistbyId($bid);
				
				// Check for status change (inactive field at line 425)
				$oldStatus = isset($oldUser['inactive']) ? $oldUser['inactive'] : null;
				$newStatus = isset($data['user']['inactive']) ? $data['user']['inactive'] : null;
				$oldEmail = isset($oldUser['email']) ? $oldUser['email'] : '';
				$newEmail = isset($data['user']['email']) ? $data['user']['email'] : '';
				
				// Update the database first
				if($allowEdit && $modelProfilesModel->userUpdate($bid,$data['user'],$modifiedBy) ){ 
					
					$r= 'Updated successfully';
					
					// Handle MailChimp operations after successful database update
					$mailchimpMessage = $modelProfilesModel->syncMailchimpOnStatusChange(
						$bid,
						$oldStatus,
						$newStatus,
						$oldEmail,
						$newEmail,
						$data['user']['fName'],
						$data['user']['lName'],
						$this->logged_id
					);
					
					// Append MailChimp message to response
					$r .= $mailchimpMessage;
					
				}else{
					$r= 'Error';
				}
			}
				
			}elseif($action=='delete'){
				$this->webConfig->checkPermissionByDes(['user_delete'],'exit');
				
				$bid = $_POST['bid'];
				
				// Get user data before deletion to archive email in MailChimp
				$userToDelete = $modelProfilesModel->getBaptistbyId($bid);
				if($userToDelete && isset($userToDelete['email']) && $userToDelete['email']) {
					$archiveResult = $modelProfilesModel->archiveMailchimpEmail($bid, $userToDelete['email'], $this->logged_id);
					// Note: Archive result is logged but not returned to user since deletion continues
				}
				
				$r = $modelProfilesModel->deleteBaptist($bid);
				
			}elseif($action=='findSameNameAs'){
				
				
				
				$rows = $modelProfilesModel->findSameNameAs($bid);
				
				if($rows){
					echo '<div style="background: #eee;    padding: 10px;">搜索到以下同姓同名賬號:<br /><br />';
					foreach($rows as $row){
						echo $row['name'] . '/' . $row['email']  . '/UID:' . $row['id'];
						echo '<br />';
						echo '<a target="_blank" href="'.base_url('xAdmin/baptist/'.$row['id']).'">User details</a> ';
						echo ' | <a onclick="importDataFrom(this)" data-bid="'. $row['id'].'" href="javascript:void(0);">導入其課程數據</a>';
						echo '<br /><br />';
					}
					echo '</div>';
				}else{
					echo '沒有搜索到同姓同名賬號';
				}
				
				exit;
				
			}elseif($action=='importDataFrom'){
				
				
				
				$from_id = $_POST['bid'];
				$to_id = $bid;
				
				$modelClassesModel->importDataFrom($from_id,$to_id);
				
				
				echo '成功複製了用戶'.$from_id.'的課程數據到當前用戶,請刷新頁面';
				
				exit;
				
			}elseif($action=='pictureUpdate'){
				
				$file = $this->request->getFile('pic');
				
				if ($file && $file->isValid() && ! $file->hasMoved())
				{
					 $upload_dir =  FCPATH.'uploads/picture/' . date('Ymd');
					
						$file->move($upload_dir);
					
					$r =  $file_url =  base_url().'/uploads/picture/' . date('Ymd') . '/'.$file->getName();	

				}else{
					
					$r = 'error';
				}
			}
			
			// Return only the response message, not the full page
			// Clear any output buffers first
			while (ob_get_level()) {
				ob_end_clean();
			}
			// Use die() to ensure execution stops completely
			die($r);
		}



		if($bid){
			//dd($baptist);
			
			$data['activityLog'] = $modelProfilesModel->getActivityLog($bid);
			
				if($authorizedUser != $this->logged_id){
				
					$this->webConfig->checkPermissionByDes(['user_view','user_edit'],1);
				
				}
				
				
				$data['user'] = $baptist;
				
				$data['pageTitle']=ucwords(implode(' ',array_filter([$data['user']['fName'],$data['user']['mName'],$data['user']['lName']])));
				$data['fsubmitLable']='Update';
				
				$data['results'] =  $modelClassesModel->presenceDetail($bid);	
				
				$modelShape = new ShapeModel();
				$data['isShapeExists'] = $modelShape->where('bid', $bid)->first();
				
				$data['isMinistryExists'] = $data['isShapeExists'] && json_decode($data['isShapeExists']['ministry']);
				

				
				
				
				
				

				
				
				
				

				
				
				
				
				
				
				
				
				
				
				
				
				
				
				$data['pastors'] = $this->modelPermission->getPtoUsers();
				

				
				
				
				
				
				
				$data['adminsIds'] = array_column($data['pastors'],'bid');
				
				
				
				

				
				$data['senior_pastor'] = $modelCapabilities->get_sp_users('is_senior_pastor'); 
				$data['temporary_ministry_coordinator'] = $modelCapabilities->get_sp_users('is_temporary_ministry_coordinator');
				$data['office_director'] = $modelCapabilities->get_sp_users('is_office_director');				
				
			 
				

				
				
			
			
		} 

		// Only render view if no action was processed (actions would have already exited with die())
		if(!isset($action) || !$action) {
			$data['sites'] = $this->webConfig->sites;
			
			
			
			
			$data['menugroup'] = 'users';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_baptist';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			echo view('theme-sb-admin-2/layout',$data);
		}
	}
			
		







	public function syncMailchimp()
	{
		$bid = $this->request->getPost('bid');
		
		if(!$bid) {
			return $this->response->setJSON(['success' => false, 'message' => 'BID required']);
		}
		
		$modelProfilesModel = new ProfilesModel();
		$user = $modelProfilesModel->getBaptistbyId($bid);
		
		if(!$user) {
			return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
		}
		
		$email = isset($user['email']) ? $user['email'] : '';
		$fName = isset($user['fName']) ? $user['fName'] : '';
		$lName = isset($user['lName']) ? $user['lName'] : '';
		
		// Use the reusable helper method from ProfilesModel
		$result = $modelProfilesModel->syncMailchimpMember($bid, $email, $fName, $lName, $this->logged_id);
		
		return $this->response->setJSON($result);
	}

	//--------------------------------------------------------------------

}
