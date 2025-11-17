<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\WebmetaModel;		
use App\Models\ClassesModel;		
use App\Models\RecommendationModel;		
use App\Models\ProfilesModel;
use App\Models\CapabilitiesModel; 	
use App\Models\ElearningModel;		

class Pending extends BaseController
{
	
	
	public function index()
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/pending');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);			

		$action = $this->request->getPost('action');
		

		
 
		
		$modelMembersModel = new MembersModel();	
		$modelClasses = new ClassesModel();		
		$modelWebmetaModel = new WebmetaModel();			
		

		
		

		
		if($action=='newMemberTemplateSave'){
			
			$newMemberTemplate = trim($this->request->getPost('content'));
			$r=$modelWebmetaModel->where('meta_key', 'newMemberTemplate')->set(['meta_value' => $newMemberTemplate])->update();
			if($r){
				echo 'OK';
			}
			
			exit;
		}	


		
		$row = $modelWebmetaModel->where('meta_key', 'newMemberTemplate')->first(); //var_dump($row);exit;
		$data['newMemberTemplate']=$row['meta_value'];		
		
			
		
		$condition[] = "a.`inactive` = 4";


		$mode = 6; 
		
		$conditionStr = implode(' and ',$condition);
		
		$pLists[0] = $modelClasses->dbSearchBaptism2($mode,$conditionStr);	 
		
		$pLists[1] = $modelClasses->dbSearchBaptism2($mode,'a.`inactive` = 6');
		

		$spPid = $this->request->getGet('pid');
		$data['pid'] =  $spPid ? $spPid : $this->logged_id ;
		
		$data['pLists'] = $pLists;
		$data['pageTitle'] = 'Membership';
		
		
		
		$data['menugroup'] = 'users';
		$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
		$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);

		$data['page'] = 'admin_pending';
		$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
		
		
	
		
		
		
		echo view('theme-sb-admin-2/layout',$data);
		
		
		


		
		}
			
		

	public function form($bid)
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/pending/form/'.$bid);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);			

		$action = $this->request->getPost('action');		
		
		
		
		
	
		
		$modelClasses = new ClassesModel();			
		$modelProfiles = new ProfilesModel();		
		$modelRecommendationModel = new RecommendationModel();		
		$modelWebmetaModel = new WebmetaModel();	
		$modelCapabilities = new CapabilitiesModel();	
		$modelElearning = new ElearningModel();
		
		$the_office_director = $modelCapabilities->get_sp_users('is_office_director');
		$the_office_assistant = $modelCapabilities->get_sp_users('is_office_assistant');	
		

		$the_office_assistant_email = $modelProfiles->db_m_getUserField($the_office_assistant,'email');
		

		
		$baptist = $modelProfiles->getBaptistbyId($bid); 
		$formData = $modelRecommendationModel->where('bid', $bid)->first();
		
 
		

		
		if(!$baptist){
				echo 'user not found';
				exit();
		}
			
			
		if($action=='formSubmit'){ //var_dump($_POST);exit;
		
		
			if($baptist['inactive']==3){
				echo $baptist['fName'].' '. $baptist['lName'].' is already a member!';
				exit();
			}
			
			$formData['fullname'] = $this->request->getPost('fullname');
			$formData['cname'] = $this->request->getPost('cname');
			
			$formData['bornagain'] = $this->request->getPost('bornagain')?$this->request->getPost('bornagain'):0;
			$formData['submittedST'] = $this->request->getPost('submittedST')?$this->request->getPost('submittedST'):0;
			$formData['grouplife'] = $this->request->getPost('grouplife')?$this->request->getPost('grouplife'):0;
			$formData['completedGU1'] = $this->request->getPost('completedGU1')?$this->request->getPost('completedGU1'):0;
			$formData['completed101'] = $this->request->getPost('completed101')?$this->request->getPost('completed101'):0;
			$formData['completedMAF'] = $this->request->getPost('completedMAF')?$this->request->getPost('completedMAF'):0;
			$formData['passOI'] = $this->request->getPost('passOI')?$this->request->getPost('passOI'):0;
			
			$formData['pastorsNote'] = $this->request->getPost('pastorsNote');
			
			$formData['receivingBaptism'] = $this->request->getPost('receivingBaptism')?$this->request->getPost('receivingBaptism'):0;
			$formData['anotherChurch'] = $this->request->getPost('anotherChurch')?$this->request->getPost('anotherChurch'):0;
			
			$formData['pastor'] = $this->request->getPost('pastor');
			
			$formData['receivingBaptismOn'] = $this->request->getPost('receivingBaptismOn')&&$this->request->getPost('receivingBaptismOn')?strtotime($this->request->getPost('receivingBaptismOn')):null;
			$formData['evidence'] = $this->request->getPost('evidence')?$this->request->getPost('evidence'):null;
		
			
			$formData['bid'] = $bid;
			
			$inactiveCode = $this->request->getPost('inactive');
			$r=0;
		
			$isExists = $modelRecommendationModel->where('bid',$bid)->first();
			if($isExists){
				
				$formId = $isExists['id'];
				$r = $modelRecommendationModel->update($formId,$formData);
				
			}else{
				$r = $modelRecommendationModel->insert($formData);
				$formId =  $modelRecommendationModel->db->insertID();
			}
			
			$doInactiveUpdate = $formData['bornagain'] && $formData['submittedST'] && $formData['completed101'] && $formData['completedMAF'] && $formData['passOI'] && ($formData['receivingBaptism']||$formData['anotherChurch']);
			
			$modelProfiles = new ProfilesModel();
			
			// Get old status before update
			$oldStatus = isset($baptist['inactive']) ? $baptist['inactive'] : null;
			$oldEmail = isset($baptist['email']) ? $baptist['email'] : '';
			$userEmail = isset($baptist['email']) ? $baptist['email'] : '';
			$userFName = isset($baptist['fName']) ? $baptist['fName'] : '';
			$userLName = isset($baptist['lName']) ? $baptist['lName'] : '';

			
			if($inactiveCode){		
			
				$upData=array('inactive'=>$inactiveCode);
				
				if($inactiveCode==3){
					log_message('debug', 'Pending form: Status changed to 3 (Member) for bid ' . $bid);
					$upData['membershipDate'] = time(); 
					$modelProfiles->update($bid,$upData);
					
							$mlogs['bid'] = $bid;
							$mlogs['by'] = $modelProfiles->getUserName($this->logged_id,1);
							$mlogs['log'] = 'Status: Pre-Member => Member';
							$modelProfiles->member_change_log($mlogs);						
					
					
					$row = $modelWebmetaModel->where('meta_key', 'newMemberTemplate')->first(); //var_dump($row);exit;
					$newMemberTemplate = $row['meta_value'];

					if($newMemberTemplate){
						
						$msgCon = str_replace('[username]',ucwords($formData['fullname']),$newMemberTemplate);
						$msgCon = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1">$1</a>',   $msgCon);
						
						$to = $baptist['email'];
						$subject = '歡迎你正式成為一個『匯點人』';

						$message ='
						<html>
						<head>
						<title>'.$subject.'</title>
						</head>
						<body>'.nl2br($msgCon).'</body>
						</html>';				
				
						$rr = $this->webConfig->sendtomandrill($subject, $message, $to, $the_office_assistant_email);
						$mlogs['bid'] = $bid;
						$mlogs['by'] = $modelProfiles->getUserName($this->logged_id,1);
						$mlogs['log'] = 'An email has been sent to the new member: '.$to;
						$modelProfiles->member_change_log($mlogs);		
						
					}
					
					// MailChimp sync when status becomes Member (3)
					$mailchimpMessage = $modelProfiles->syncMailchimpOnStatusChange(
						$bid, 
						$oldStatus, 
						$inactiveCode, 
						$oldEmail, 
						$userEmail, 
						$userFName, 
						$userLName, 
						$this->logged_id
					);
							
					
					
				}elseif($inactiveCode==4){
					$modelProfiles->update($bid,array('inactive'=>4));
					$modelRecommendationModel->update($formId,['date'=>NULL]);
					
				}
				
				
			}elseif($doInactiveUpdate){
				
				 
				$modelRecommendationModel->update($formId,['date'=>time()]);
				 
				
				if($baptist['inactive']!=6){
				
					
					$emails = $modelProfiles->whereIn('id', [$the_office_assistant,$the_office_director])->findColumn('email');
					$emailsVars = [];
					$pUrl = base_url('xAdmin/pending/form/'.$bid);
					
					$subject = 'A membership candidate has been recommended or approved by a zone pastor (UID:'.$bid.')';
					
					$message ='
					<html>
					<head>
					<title></title>
					</head>
					<body>
					
					<p>'.$subject.':<br />
					Please click below link for more information: <a href="'.$pUrl.'">'.$pUrl.'</a>
					</p>
					</body>
					</html>';
					
					
					
					
					if($emails){
							foreach($emails as   $email){
								
								$myEmailItem = [];
								$myEmailItem['From'] =  'admin@tracycrosspoint.org';
								$myEmailItem['To'] = $email; 
								
								$myEmailItem['Subject'] = $subject;
								$myEmailItem['HtmlBody'] = $message;
								
								$emailsVars['template']['more-information'][] = ["email_address" => ["address" => $email], "merge_info" => ['subject'=>$subject, 'link'=>$pUrl ]];

								
								$emailsVars[] = $myEmailItem;
							}	

							$r = $this->webConfig->Sendbatchemails($emailsVars);		
					}
							
					
					
					$modelProfiles->update($bid,array('inactive'=>6));
					
					
				}
			}
			
			
			$responseMessage = '';
			if($r){
				$responseMessage = 'Updated successfully';
				
			}else{
				$responseMessage = 'error';
			}
			
			// Append MailChimp message to response (always append, even if empty for debugging)
			//log_message('debug', 'Pending form response: mailchimpMessage = "' . $mailchimpMessage . '"');
			//log_message('debug', 'Pending form response: inactiveCode = ' . ($inactiveCode ?? 'null'));
			
			if($mailchimpMessage) {
				$responseMessage .= $mailchimpMessage;
				//log_message('debug', 'Pending form: Appending MailChimp message to response');
			} else if($inactiveCode == 3) {
				// If status is 3 but no MailChimp message, indicate why
				$responseMessage .= ' | MailChimp: Sync not performed (check logs for details)';
				//log_message('debug', 'Pending form: Status is 3 but no MailChimp message generated');
			}
			
			//	log_message('debug', 'Pending form final response: ' . $responseMessage);
			
			echo $responseMessage;
			
			exit();
			
		}			
		
		$data['baptist'] = $baptist;
		$data['formData'] = $formData; 
		
		$data['hasBaptismCertificate'] = $baptist['certificate'];
		$data['hasTestimony'] = $baptist['testimony'];
		$data['hasMRecord'] = $baptist['bornagain'] !== NULL ? 1 : 0;
		
		$logs = $modelClasses->presenceDetail($bid);	
		$data['attendance101'] = $data['completionDate'] = 0;
		
		
		
		if(isset($logs['CBIF101'])&&preg_match_all('/#(\d+)#(\d+)#finish#(\d+)#/i',$logs['CBIF101'],$match_a)){
			
			foreach($match_a[3] as $key => $val){
				
				if($val > $data['attendance101']){
					
					$data['attendance101'] = $val ;
					if($val==100) $data['completionDate'] = date('Y/m/d',$match_a[1][$key]);
					
				}
				
				
			}			
		}
		
 
		
			// var_dump($logs);
			// var_dump($data['attendance101']);
		
		
 
		
		if(!isset($data['formData']['fullname'])) $data['formData']['fullname'] = ucwords(implode(' ',array_filter(array($baptist['fName'],$baptist['mName'],$baptist['lName']))));
		if(!isset($data['formData']['cname'])) $data['formData']['cname'] = $baptist['cName'];
		
		
		
		if(!isset($data['formData']['pastor'])){
			$data['formData']['pastor'] = ($_SESSION['xadmin']==3 || preg_match('#anders|jacky|admin#i',$_SESSION['mloggedinName']))?$_SESSION['mloggedinName']:'';
		}
		
			$data['bid'] = $bid;
			
			$data['fsubmitLable'] = 'Submit';			
			
			$data['loginMsg'] = '';
			$data['pageTitle'] =  ucwords($baptist['fName'].' '.$baptist['mName'].' '.$baptist['lName']) . ' Membership Recommendation Form';
			
			
			$data['mloggedinName'] = $this->session->get('mloggedinName');
			
			 
			$data['sites'] = $this->webConfig->sites;
		
			
			$data['memberUrl'] = base_url('member');
			$data['adminUrl'] = base_url('xAdmin');
			$data['OAuthUrl']  = base_url('OAuth');
			
			$data['userLg'] = $this->lang;
			
			
			
			// Check if running on local server
			if(isLocalServer()){
				// Always show admin approval on local server
				$data['showAdminApproval'] = true;
			}elseif(  $_SESSION['mloggedin'] && in_array($_SESSION['mloggedin'],[$the_office_assistant,$the_office_director]) && $baptist['inactive']==6 ){
				
				$data['showAdminApproval'] = true;
				
			}else{
				
				$data['showAdminApproval'] = false;
			}
			
			
		$user_data = $modelElearning->get_user_data($bid,1);
		$data['is_self_paced'] = $user_data && $user_data['approved'] ? 1 : 0;	
		if($data['is_self_paced']) $data['completionDate'] = date('Y/m/d',$user_data['took_on']); 
		
		
		
		$data['completed101Checked'] = ($data['completionDate']||(isset($formData['completed101'])&&$formData['completed101'])) ? true : false ;		
			
		$data['menugroup'] = 'users';			
			
		$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
		$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);

		$data['page'] = 'admin_pending_form';
		$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
		

		
	
		
		
		
		echo view('theme-sb-admin-2/layout',$data);			
			
			
			
					
		
	}
	


	//--------------------------------------------------------------------

}
