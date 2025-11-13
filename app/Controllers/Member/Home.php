<?php 

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\ClassesModel;
use App\Models\ShapeModel;
use App\Models\MinistryModel;
use App\Models\ElearningModel;
use App\Models\CurriculumSubscribeModel;

use App\Libraries\TranslationYD;


class Home extends BaseController
{
		

	
	
	public function index($mode='classes')
	{
		if($mode!='profile'&&$mode!='submit'&&$mode!='shape'&&$mode!='testimony'&&$mode!='ministry'&&$mode!='baptismCertificate') $mode='classes';
		
		
		if($mode=='submit'){
			
			$url = base_url('member/submit');
			
		}else{
			
			$url = '';
		}

		$webConfig = new \Config\WebConfig();		
		$webConfig->checkMemberLogin($url);
		
		$modelProfiles = new ProfilesModel();
		$session = \Config\Services::session();	

		$modelShape = new ShapeModel();		
		$modelMinistry = new MinistryModel();
		
		$modelElearning = new ElearningModel();	
		
		$modelCurriculumSubscribe = new CurriculumSubscribeModel();	
		$modelClasses = new ClassesModel();		

		$spBid = $redirectUrl = $this->request->getGet('bid');
		
		// $modelMembersModel = new MembersModel();		
		// $r = $modelMembersModel->getAdminList();
		// var_dump(json_encode($r));
		if($spBid){
			
			$webConfig->checkPermissionByDes('user_view',1);
			
			
			$baptist = $modelProfiles->getBaptistbyId($spBid);// var_dump($baptist);
			
		}else{
			$baptist = $modelProfiles->getBaptistbyId($session->get('mloggedin'));
		}	
		
		if(!$baptist){
		
			$session->destroy();
			$url = base_url();
			//header("Location: $url");
			exit();
		}else{
			
			$data['disName'] = ucwords(implode(' ',array_filter([$baptist['fName'],$baptist['mName'],$baptist['lName']])));
			
			$action = $this->request->getPost('action');
			
			if($action=='removeMinistry'){
				
				$submitMinistry = $this->request->getPost('ministry');
				$theId = $this->request->getPost('bid');
				
				$newMinistry = $submitMinistry ? json_encode($submitMinistry) : '[]';
				

				
				$r = $modelShape->updateMinistryBybid($theId, $newMinistry);
				
				if($r){
					echo 'updated successfully';
					
				}else{
					echo 'error';
				}
				
				// var_dump($r);
				
				exit();
				
			}elseif($action=='curriculum_subscribe'){
				
				$unsubscribe = $this->request->getPost('unsubscribe');
	 
				$r =  $modelCurriculumSubscribe->where('bid', $baptist['id'])->delete();
				
				if($unsubscribe){
					
					$data= ['bid'=>$baptist['id'],'unsubscribe'=>1];
					$r =  $modelCurriculumSubscribe->save($data);
					
				}
				
				
				
				
				if($r){
					echo 'Updated';
					
				}else{
					echo 'Error';
				}
				
				// var_dump($r);
				
				exit();
				
			}elseif($action=='addMinistry'){
				
				$ministryId = $this->request->getPost('ministryId');
				$ministryVal = $this->request->getPost('ministryVal');
				$r=false;
				
				if($ministryId==="0"&&$ministryVal){
					
					if($modelMinistry->insert(array('en'=>$ministryVal,'zh-Hant'=>$ministryVal,'zh-Hans'=>$ministryVal))){
					$insertId = $modelMinistry->db->insertID();
					
					$subject = $data['disName'].'  Suggest A Ministry';
					$message ='
						<html>
						<head>
						<title>'.$subject.'</title>
						</head>
						<body>
						<p>'.$subject.'</p>

						<p>
							<a href="'.base_url('member/ministry?bid='.$baptist['id']).'">'.$ministryVal.'</a>
						</p>
						</body>
						</html>';							
 	

					$webConfig->sendtomandrill($subject,$message,'red.chenwen@gmail.com');	
					
					
					
					}else{
						echo 'Thank you very much for your suggestion.';exit;
					}
					
				}
				
					
				$dataExists = $modelShape->where('bid', $baptist['id'])->first(); 
				
				if($dataExists && $dataExists['ministry'] &&  json_decode($dataExists['ministry'])){
					$userMinistryIds = json_decode($dataExists['ministry']);						
				}else{
					$userMinistryIds = [];
				}
				
				$newId = $ministryId ? $ministryId  : $insertId;

				$userMinistryIds[] = $newId;
				
				$userMinistryIds = array_unique($userMinistryIds);
				shuffle($userMinistryIds);
				
				if($dataExists){
					
					//var_dump(array('ministry'=>json_encode($userMinistryIds)));
					
					$r = $modelShape->update($dataExists['id'], ['ministry'=>json_encode($userMinistryIds)]);
				
				}else{
					
					$r = $modelShape->insert(array('bid'=>$baptist['id'],'shapeP1'=>'[]','ministry'=>json_encode($userMinistryIds)));
					
				}
				
				
			
				if($r){
					$html = '<li>'.ucwords($ministryVal).' <span data-bid="'.$baptist['id'].'" data-ministryid="'.$newId.'" class="rmMinistry">x Remove</span></li>';
					echo $html;
					
					
				}else{
					echo 'error';
				}
				
				//var_dump($r);
				
				exit();
				
			}elseif($action=='searchMinistry'){
				
				$key = $this->request->getPost('query');
				$admin = $this->request->getPost('admin');
				
				
				$dataExists = $modelShape->where('bid', $baptist['id'])->first(); 				
				
				if(!$admin && $dataExists && $dataExists['ministry'] &&  json_decode($dataExists['ministry'])){
					$excludeIds = json_decode($dataExists['ministry']);				
				}else{
					$excludeIds = [];
				}

				
				$r = $modelMinistry->searchMinistry($key,$excludeIds);
				
				$html = '';
				
				foreach($r as $item){
					$lable = $item[$this->lang] ? $item[$this->lang] : $item['en'];
					$html .= '<li data-ministryval="'.$lable.'" data-ministryid="'.$item['id'].'"><span class="lable">'. $lable.'</span> <span>+ Add</span></li>';
				}
				
				if(!$html){
					$html .= '<li data-ministryval="'.$key.'" data-ministryid="0">Item not found - Suggest this item</li>';
				}
				
				echo $html;
				
				exit();
				
			}elseif($action=='rmTestimony'){
				
				if(!$webConfig->checkPermissionByDes(['is_pastor','is_admin'])){
					echo 'error'; exit;
				}
				
				$submitTestimony = $this->request->getPost('testimony');
				$theId = $this->request->getPost('bid');
				
				$newTestimony = $submitTestimony ? json_encode($submitTestimony,JSON_UNESCAPED_UNICODE) : '';
				
				// var_dump($baptist['id']);
				
				$r = $modelProfiles->update($theId, array('testimony'=>$newTestimony));
				
				if($r){
					echo 'updated successfully';
					
				}else{
					echo 'error';
				}
				
				exit();
				
			}elseif($action=='rmBaptismCertificate'){
				
				if(!$webConfig->checkPermissionByDes(['is_pastor','is_admin'])){
					echo 'error'; exit;
				}
				
				 
				$theId = $this->request->getPost('bid');
				
 
				
				$r = $modelProfiles->update($theId, array('certificate'=>''));
				
				if($r){
					echo 'updated successfully';
					
				}else{
					echo 'error';
				}
				
				exit();
				
			}elseif($action=='baptismCertificateUpdate'){
				

				$theId = $this->request->getPost('bid');
				$file = $this->request->getFile('baptismCertificate');

				if($theId != $this->logged_id){

					if(!$this->webConfig->checkPermissionByDes('user_edit')){

						$r = ['code'=>0,'error'=>'Invalid Permission'];	
						echo json_encode($r); 
						exit;


					}
					
				}

				
				
				
				if ($file && $file->isValid() && ! $file->hasMoved())
				{
					$upload_dir =  FCPATH.'uploads/baptismCertificate/' . date('Ymd').'/';
					$newName = $file->getRandomName();
					
					$file->move($upload_dir,$newName);
						
					
					$file_url =  base_url().'/uploads/baptismCertificate/' . date('Ymd') . '/'.$file->getName();	
					
					
					
					if($theId){
						$baptist = $modelProfiles->getBaptistbyId($theId);	
						$modelProfiles->update($baptist['id'], array('certificate'=>$file_url));				
					}
					

					$r = ['code'=>1,'fileName'=>$file->getName(),'fileUrl'=>$file_url];	
					

					
				}else{
					
					$r = ['code'=>0,'error'=>'Invalid file extension'];	
					
				}
				
				
				echo json_encode($r); 
				
				
				exit();
				
			}elseif($action=='testimonyUpdate'){
				
				$theId = $this->request->getPost('bid');


				if($theId != $this->logged_id){

					if(!$this->webConfig->checkPermissionByDes('user_edit')){

						$r = ['code'=>0,'error'=>'Invalid Permission'];	
						echo json_encode($r); 
						exit;


					}
					
				}




				
				$file = $this->request->getFile('testimony');
				
				
				if ($file && $file->isValid() && ! $file->hasMoved())
				{
					$upload_dir =  FCPATH.'uploads/testimony/' . date('Ymd').'/';
					$newName = $file->getRandomName();
					
					$file->move($upload_dir,$newName);
						
					
					$file_url =  base_url().'/uploads/testimony/' . date('Ymd') . '/'.$file->getName();	

					if($theId){
						$baptist = $modelProfiles->getBaptistbyId($theId);				
						$currentData = $baptist['testimony'] && is_array(json_decode($baptist['testimony'])) ? json_decode($baptist['testimony']) : [];					
						$currentData[] = $file_url;					
						$modelProfiles->update($baptist['id'], array('testimony'=>json_encode($currentData,JSON_UNESCAPED_UNICODE)));
					}

					
					$r = ['code'=>1,'fileName'=>$file->getName(),'fileUrl'=>$file_url];	
					
				}else{
					
					$r = ['code'=>0,'error'=>'Invalid file extension'];	
				}
				
				echo json_encode($r); 
				exit();
				
			}elseif($action=='shapeUpdate'){
				$shapeP1 =  $shapeP1ForDb =  array_map(function($value) {   return intval($value);}, $this->request->getPost('shapeP1'));
				$shapeP2 = $this->request->getPost('shapeP2');
				$shapeP3 = $this->request->getPost('shapeP3');
				$shapeP4 = $shapeP4ForDb = array_map(function($value) {   return intval($value);}, $this->request->getPost('shapeP4'));
				$shapeP5 = $this->request->getPost('shapeP5')?$this->request->getPost('shapeP5'):['','',''];
				$shapeP6 = $this->request->getPost('shapeP6');
				
				
				array_unshift ( $shapeP1,'fix');
				$myS = [];
				$myS[0]= $shapeP1[1] + $shapeP1[15] + $shapeP1[29] + $shapeP1[43] + $shapeP1[57] + $shapeP1[71] + $shapeP1[85];
				$myS[1]= $shapeP1[2] + $shapeP1[16] + $shapeP1[30] + $shapeP1[44] + $shapeP1[58] + $shapeP1[72] + $shapeP1[86];
				$myS[2]= $shapeP1[3] + $shapeP1[17] + $shapeP1[31] + $shapeP1[45] + $shapeP1[59] + $shapeP1[73] + $shapeP1[87];
				$myS[3]= $shapeP1[4] + $shapeP1[18] + $shapeP1[32] + $shapeP1[46] + $shapeP1[60] + $shapeP1[74] + $shapeP1[88];
				$myS[4]= $shapeP1[5] + $shapeP1[19] + $shapeP1[33] + $shapeP1[47] + $shapeP1[61] + $shapeP1[75] + $shapeP1[89];
				$myS[5]= $shapeP1[6] + $shapeP1[20] + $shapeP1[34] + $shapeP1[48] + $shapeP1[62] + $shapeP1[76] + $shapeP1[90];
				$myS[6]= $shapeP1[7] + $shapeP1[21] + $shapeP1[35] + $shapeP1[49] + $shapeP1[63] + $shapeP1[77] + $shapeP1[91];
				$myS[7]= $shapeP1[8] + $shapeP1[22] + $shapeP1[36] + $shapeP1[50] + $shapeP1[64] + $shapeP1[78] + $shapeP1[92];
				$myS[8]= $shapeP1[9] + $shapeP1[23] + $shapeP1[37] + $shapeP1[51] + $shapeP1[65] + $shapeP1[79] + $shapeP1[93];
				$myS[9]= $shapeP1[10] + $shapeP1[24] + $shapeP1[38] + $shapeP1[52] + $shapeP1[66] + $shapeP1[80] + $shapeP1[94];
				$myS[10]= $shapeP1[11] + $shapeP1[25] + $shapeP1[39] + $shapeP1[53] + $shapeP1[67] + $shapeP1[81] + $shapeP1[95];
				$myS[11]= $shapeP1[12] + $shapeP1[26] + $shapeP1[40] + $shapeP1[54] + $shapeP1[68] + $shapeP1[82] + $shapeP1[96];
				$myS[12]= $shapeP1[13] + $shapeP1[27] + $shapeP1[41] + $shapeP1[55] + $shapeP1[69] + $shapeP1[83] + $shapeP1[97];
				$myS[13]= $shapeP1[14] + $shapeP1[28] + $shapeP1[42] + $shapeP1[56] + $shapeP1[70] + $shapeP1[84] + $shapeP1[98];
				
				arsort($myS);
				$mySForDb = array_slice(array_keys($myS),0,3);
				
				array_unshift ( $shapeP4,'fix');
				$myP = [];
				$myP[0]= $shapeP4[1] + $shapeP4[8] + $shapeP4[11] + $shapeP4[14] + $shapeP4[19];				
				$myP[1]= $shapeP4[2] + $shapeP4[7] + $shapeP4[9] + $shapeP4[16] + $shapeP4[20];
				$myP[2]= $shapeP4[3] + $shapeP4[6] + $shapeP4[12] + $shapeP4[13] + $shapeP4[18];
				$myP[3]= $shapeP4[4] + $shapeP4[5] + $shapeP4[10] + $shapeP4[15] + $shapeP4[17];
				
				
				$insertArray = ['bid'=>$baptist['id'],'shapeP1'=>json_encode($shapeP1ForDb),'shapeP2'=>json_encode($shapeP2,JSON_UNESCAPED_UNICODE),'shapeP3'=>json_encode($shapeP3,JSON_UNESCAPED_UNICODE),'shapeP4'=>json_encode($shapeP4ForDb),'shapeP5'=>json_encode($shapeP5),'shapeP6'=>json_encode($shapeP6),'myS'=>json_encode($mySForDb),'myP'=>json_encode($myP)];
				
				$isExists = $modelShape->where('bid', $baptist['id'])->first();
				if($isExists){
					$r = $modelShape->update($isExists['id'],$insertArray);
				}else{
					$r = $modelShape->insert($insertArray);
				}
			
				
				
				if($r){
					echo 'ok';
					
				}else{
					echo 'error';
				}
				
				
				exit;
				
				
			}elseif($action=='profileUpdate'){
				
				$baptistArr['fName'] = addslashes($_POST['fName']);
				$baptistArr['mName'] = addslashes($_POST['mName']);
				$baptistArr['lName'] = addslashes($_POST['lName']);
				
				if(isset($_POST['gender'])) $baptistArr['gender'] = $_POST['gender'];
				if(isset($_POST['marital'])) $baptistArr['marital'] = $_POST['marital'];
				if(isset($_POST['birthDate'])) $baptistArr['birthDate'] = $_POST['birthDate']&&strtotime($_POST['birthDate'])!==false?strtotime($_POST['birthDate']):NULL;
				
				if(isset($_POST['homeAddress'])) $baptistArr['homeAddress'] = addslashes($_POST['homeAddress']);
				if(isset($_POST['city'])) $baptistArr['city'] = addslashes($_POST['city']);
				if(isset($_POST['zCode'])) $baptistArr['zCode'] = addslashes($_POST['zCode']);
				if(isset($_POST['hPhone'])) $baptistArr['hPhone'] = preg_replace('#[^\d]#i','',$_POST['hPhone']);
				if(isset($_POST['mPhone'])) $baptistArr['mPhone'] = preg_replace('#[^\d]#i','',$_POST['mPhone']);
				if(isset($_POST['email'])) $baptistArr['email'] = addslashes($_POST['email']);
				if(isset($_POST['occupation'])) $baptistArr['occupation'] = addslashes($_POST['occupation']);
				
				if(isset($_POST['nocb'])) $baptistArr['nocb'] = addslashes($_POST['nocb']);
				if(isset($_POST['baptizedDate'])) $baptistArr['baptizedDate'] = $_POST['baptizedDate']&&strtotime($_POST['baptizedDate'])!==false?strtotime($_POST['baptizedDate']):NULL;
				
				if(isset($_POST['nopc'])) $baptistArr['nopc'] = addslashes($_POST['nopc']);
				
				if(isset($_POST['family'])) $baptistArr['family'] = addslashes($_POST['family']);
				
				if(isset($_POST['membershipDate'])) $baptistArr['membershipDate'] = $_POST['membershipDate']&&strtotime($_POST['membershipDate'])!==false?strtotime($_POST['membershipDate']):NULL;
				
				if(isset($_POST['site'])) $baptistArr['site'] = addslashes($_POST['site']);
				
				if(isset($_POST['inactive'])) $baptistArr['inactive'] = $_POST['inactive'];
				
				
				if(isset($_POST['cName'])) $baptistArr['cName'] = $_POST['cName'];
				if(isset($_POST['bornagain'])) $baptistArr['bornagain'] = $_POST['bornagain'];
				if(isset($_POST['attendingagroup'])) $baptistArr['attendingagroup'] = $_POST['attendingagroup'];
				if(isset($_POST['lifegroup'])) $baptistArr['lifegroup'] = $_POST['lifegroup'];
				if(isset($_POST['baptizedprevious'])) $baptistArr['baptizedprevious'] = $_POST['baptizedprevious'];
				if(isset($_POST['byImmersion'])) $baptistArr['byImmersion'] = $_POST['byImmersion'];
				
				
				if(isset($_POST['iAgreer'])) $baptistArr['inactive'] = 4;
				
				if(isset($_POST['rmBaptismCertificate'])&&$_POST['rmBaptismCertificate']==1) $baptistArr['certificate'] = '';
				
				
				
				
				
				$r = $modelProfiles->update($baptist['id'], $baptistArr);
				
				if($r){
					echo 'Updated successfully';
					
					if(isset($_POST['iAgreer'])){
							$mlogs['bid'] = $baptist['id'];
							$mlogs['by'] = '用戶自主申請';
							$mlogs['log'] = 'Status: Guest => Pre-Member';
							$modelProfiles->member_change_log($mlogs);
					}							
					
				}else{
					echo 'Error';
				}
				
				
				exit();
			
			}
			
			
			if($this->request->getGet('unsubscribe')){
				
				$modelCurriculumSubscribe->where('bid', $baptist['id'])->delete();
				
				$unsubscribe_data= ['bid'=>$baptist['id'],'unsubscribe'=>1];
				$modelCurriculumSubscribe->save($unsubscribe_data);
				
				$data['showUnsubscribeAlert'] = true;
				
			}			
			
			
			

				$data['user']['status'] = $baptist['status'];
				
				$data['user']['fName'] = trim($baptist['fName']);
				$data['user']['mName'] =  trim($baptist['mName']);
				$data['user']['lName'] =  trim($baptist['lName']);
				
				$data['user']['gender'] = $baptist['gender'];
				$data['user']['marital'] = $baptist['marital'];
				$data['user']['birthDate'] = $baptist['birthDate']?date("m/d/Y",$baptist['birthDate']):'';
				
				$data['user']['homeAddress'] = $baptist['homeAddress'];
				$data['user']['city'] = $baptist['city'];
				$data['user']['zCode'] = $baptist['zCode'];
				$data['user']['hPhone'] = $baptist['hPhone'];
				$data['user']['mPhone'] = $baptist['mPhone'];
				$data['user']['email'] = $baptist['email'];
				$data['user']['occupation'] = $baptist['occupation'];
				
				$data['user']['nocb'] = $baptist['nocb'];
				$data['user']['baptizedDate'] = $baptist['baptizedDate']?date("m/d/Y",$baptist['baptizedDate']):'';
				$data['user']['nopc'] = $baptist['nopc'];
				
				$data['user']['family'] = $baptist['family']?$baptist['family']:'';
				
				$data['user']['membershipDate'] = $baptist['membershipDate']?date("m/d/Y",$baptist['membershipDate']):'';
				
				$data['user']['site'] = $baptist['site'];
				
				$data['user']['inactive'] = $baptist['inactive'];
				
				
				$data['user']['bornagain'] = $baptist['bornagain'];
				$data['user']['attendingagroup'] = $baptist['attendingagroup'];
				$data['user']['lifegroup'] = $baptist['lifegroup'];
				$data['user']['baptizedprevious'] = $baptist['baptizedprevious'];
				$data['user']['cName'] = $baptist['cName'];
				$data['user']['byImmersion'] = $baptist['byImmersion'];
				
				$data['user']['testimony'] = $baptist['testimony'];
				$data['user']['baptismCertificate'] = $baptist['certificate'];

		
			$data['bid'] = $baptist['id'];
			
			$data['fsubmitLable'] = 'Submit';			
			
			$data['loginMsg'] = '';
			$data['title'] = 'Crosspoint CBI';
			$data['webConfig'] = $webConfig;
			$data['mloggedinName'] = $session->get('mloggedinName');
			
			$data['curriculumCodes'] = $webConfig->curriculumCodes[$this->lang];
			
			$data['memberUrl'] = base_url('member');
			$data['adminUrl'] = base_url('xAdmin');
			$data['OAuthUrl']  = base_url('OAuth');
			$data['header'] = view('cbi_header',array('userLg'=>$this->lang,'login'=>$session->get('mloggedin')));
			$data['userLg'] = $this->lang;
			$data['mode'] = $mode;
			
			$data['unsubscribe'] =  $modelCurriculumSubscribe->where('bid', $baptist['id'] )->first()  ? 1 : 0;
			$data['is_101finished'] =  $modelClasses->is_101finished( $baptist['id']);
			
			if($mode=='classes' || $baptist['inactive'] == 4 || $baptist['inactive'] == 3){
				
				
				$data['nowClass'] = $modelClasses->getOngoingClasses(); 		
				$data['logs'] =  $modelClasses->presenceDetail($data['bid']);	
				
				$data['userElearning101'] = $modelElearning->get_user_data($data['bid'],1);
				
			}
			
			if($mode=='submit'){
				$data['spBid'] = $spBid;
				
				
				
				$TranslationYD = new TranslationYD();
				
				$data['member_submit_form_1'] = view('member_submit_form_1',$data);
				$data['member_submit_form_2'] = view('member_submit_form_2',$data);
		
		
				if($data['userLg']=='zh-Hans'){
					
					$data['member_submit_form_1'] = $TranslationYD->tra($data['member_submit_form_1'],1);
					$data['member_submit_form_2'] = $TranslationYD->tra($data['member_submit_form_2'],1);
					
				}
				
				
				echo view('member_submit',$data);
				
				
				
				
			}elseif($mode=='testimony'){
				
				$data['spBid'] = $spBid;
				$data['testimony'] = $baptist['testimony'] && is_array(json_decode($baptist['testimony'])) ? json_decode($baptist['testimony']) : [];
				
				$data['testimonyGuideline'] = view('testimonyGuideline_'.$this->lang);
				$data['knowingBaptism'] = view('knowingBaptism_'.$this->lang);
				
				echo view('member_testimony',$data);
				
			}elseif($mode=='baptismCertificate'){
				
				$data['spBid'] = $spBid;				
				echo view('member_baptismCertificate',$data);
				
			}elseif($mode=='ministry'){
				
				$data['spBid'] = $spBid;
				
				
				$dataExists = $modelShape->where('bid', $baptist['id'])->first(); //var_dump($dataExists); exit;
				
				if($dataExists && $dataExists['ministry'] &&  json_decode($dataExists['ministry'])){
					$ministryIds = json_decode($dataExists['ministry']);
					
					$data['ministry'] = $modelMinistry->find($ministryIds);
				}else{
					$data['ministry'] = [];
				}
				
				
				echo view('member_ministry',$data);
				
			}elseif($mode=='shape'){
				$data['spBid'] = $spBid;
				
				
				
				
				$dataExists = $modelShape->where('bid', $baptist['id'])->first();
				
				if($dataExists){
					$data['shapeP1Answer'] = json_decode($dataExists['shapeP1']);
					$data['myH'] = (array) json_decode($dataExists['shapeP2']);
					$data['myA'] = (array) json_decode($dataExists['shapeP3']);
					$data['shapeP4Answer'] = json_decode($dataExists['shapeP4']);
					$data['myE'] = json_decode($dataExists['shapeP5']);
					$data['myC'] = json_decode($dataExists['shapeP6']);
					$data['myS'] = json_decode($dataExists['myS']);
					$data['myP'] = json_decode($dataExists['myP']);
				}else{
					$data['myS'] = $data['myH'] = $data['myA'] = $data['myP'] =  $data['myE'] = $data['myC'] = [];
				}
				
				
				echo view('member_shape',$data);
			}else{
				echo view('member_home',$data);		
			}
			
			
				
		}
			
		

		
		
		
	}
	
	
	

	
	
	











	//--------------------------------------------------------------------

}
