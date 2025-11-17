<?php namespace App\Controllers;


use App\Models\MembersModel;
use App\Models\ProfilesModel;


class Signup extends BaseController
{
	
	
	public function index()
	{
		$session = \Config\Services::session();
		
		$action = $this->request->getPost('action');
		$webConfig = new \Config\WebConfig();
		
		$data['loginMsg'] = '';
		$data['title'] = 'Sign up';
		$data['webConfig'] = $webConfig;
		
		$modelMembers = new MembersModel();
		$modelProfiles = new ProfilesModel();		
		


		if( $action == 'signup' ){ 

			$bid = isset($_POST['itMe'])?$_POST['itMe']:false;
			$email = isset($_POST['mEmail'])&&$_POST['mEmail']?trim($_POST['mEmail']):false;
			$pw = isset($_POST['mPw1'])&&trim($_POST['mPw1'])? md5($_POST['mPw1'].'users\'pw') : '';
			
			$dsfService = $session->get('dsfService');
			$dsfId = $session->get('dsfId');
			$dsfEmail = $session->get('dsfEmail');
			$dsfPicture = $session->get('dsfPicture');			
			
			$fn = isset($_POST['fn'])&&$_POST['fn']?$_POST['fn']:'';
			$mn = isset($_POST['mn'])&&$_POST['mn']?$_POST['mn']:'';
			$ln = isset($_POST['ln'])&&$_POST['ln']?$_POST['ln']:'';
			$gender = isset($_POST['gender'])&&$_POST['gender']?$_POST['gender']:'';
			$site = isset($_POST['site'])&&$_POST['site']?$_POST['site']:'';
			
			
			if(!$email || (!$pw && !$dsfId)){ echo 'OK';exit();}  

			if($bid===false){	
				$member = $modelMembers->checkMemberExist($email,$fn,$ln); 
				
				if($member){
						
						$html = '數據庫內發現匹配的數據:<br />';
						foreach($member as $key => $mem){
							$html .= '<input class="itsMe" '.($key==0?'checked':'').' type="radio" name="itMe" id="itMe'.$key.'" value="'.$mem['id'].'"> <label for="itMe'.$key.'">'.ucwords($this->jiami($mem['name'])).' ('.$this->emailjiami($mem['email']).'), 是我</lable><br>';
						}
						$html .='<input  class="itsMe" type="radio" name="itMe" id="notMe" value="0"> <label for="notMe">不是我，創建新用戶</lable><br>';
						
						echo $html;
						exit();
					}		
			}	
			

			if($bid){
				
				$r = $modelMembers->db_m_signup_original($bid,$pw,$dsfService,$dsfId,$dsfEmail);
				
			}else{
				
				$bid = $modelProfiles->db_m_baseInfo($fn,$mn,$ln,$gender,$email); 
				
				if($bid){
					
					$r = $modelMembers->db_m_signup_original($bid,$pw,$dsfService,$dsfId,$dsfEmail);
	
				}else{
					
					echo 'error';exit();
				}		
			}
			
			if($r){
				
					if($dsfPicture){
						$modelProfiles->update($bid, array('picture' => $dsfPicture));
					}


					if($site){
						$modelProfiles->update($bid, array('site' => $site));
					}
						
				
					$preConfirm = $modelMembers->db_m_member($bid); 

					if(!$dsfEmail || strtolower($preConfirm['email']) !== strtolower($dsfEmail)){
						
						$confirmationLink = base_url('signup').'?confirm='.$webConfig->makeConfirmationCode($preConfirm['bid'],$preConfirm['xpw'],$preConfirm['subid'],$preConfirm['email']); 
						
						$to = $preConfirm['email'];
						$subject = 'Verify your Email (expires at '.date('F j, Y, g:i a',time()+3600*48).')';

						$message ='
						<html>
						<head>
						<title>Verify your Email</title>
						</head>
						<body>
						<p>Verify your Email to finish signing up for Crosspoint</p>

						<p>
							Please confirm that '.$preConfirm['email'].' is your Email address by clicking on the link below:<br />
							<a href="'.$confirmationLink.'">'.$confirmationLink.'</a>
						</p>
						</body>
						</html>';				
				
						$webConfig->sendtomandrill($subject, $message, $to);
			
						echo $to;
						
					}else{
						
						$modelMembers->where('bid', $bid)->set(array('status' => 1))->update();
						$_SESSION['mloggedin'] = $bid;
						$_SESSION['email'] = $dsfEmail;
						$_SESSION['mloggedinName'] = ucwords($preConfirm['name']);
						
						echo 'logged-in';
						
					}
					
					 	
				
			}else{
				
				echo 'error';
				
			}
			
			exit();
			
		}elseif(isset($_GET['confirm'])){


			$secr = explode('|||', $webConfig->decode($_GET['confirm'],'jmforConfirmation'));
			
			if(count($secr)!==2){
				echo 'OK';exit();
			}
			
			
			$preConfirm = $modelMembers->db_m_getUnconfirm($secr[0]); 
			
			if($preConfirm && strtotime($preConfirm['lastactivity'])>time()-3600*48){
				
				
				
				$confirmationCode = $webConfig->makeConfirmationCode($preConfirm['bid'],$preConfirm['xpw'],$preConfirm['subid'],$preConfirm['email']); 
				
				if($confirmationCode === $_GET['confirm']){
					$data['confirmed'] = true; 			
					
					$modelMembers->where('bid', $preConfirm['bid'])->set(array('status' => 1))->update();
					
					if($preConfirm['subemail'] && $preConfirm['subemail'] != $preConfirm['email']){
						
					
						$modelProfiles->update($preConfirm['bid'], array('email' => $preConfirm['subemail']));
						
					}
					
					$newdata = [
						'mloggedin'  =>  $preConfirm['bid'],
						'mloggedinName'     => ucwords($preConfirm['name']),
						'email'     => $preConfirm['subemail']
					];

					$session->set($newdata);	
					
				}else{
					$data['confirmed'] = false; 
				}
			}else{
				$data['confirmed'] = false; 
			}

		}elseif(isset($_GET['submit'])){
			
			echo 'OK';
			exit();
			
		}elseif(!isset($_SESSION["dsfEmail"])){
			$session->destroy();
			$url = base_url();
			header("Location: $url");
			exit();	
		}
		
		$data['header'] = view('cbi_header',array('userLg'=>$this->lang,'login'=>$session->get('mloggedin')));
		$data['userLg'] = $this->lang;
		echo view('cbi_signup',$data);
	}
	
	
	






private function jiami($words){
	
	$arr1 = explode(' ',$words);
	$arr2 = [];
	
	foreach($arr1 as $item ){
		$arr2[] = $this->jx($item);
	}
	
	
	return implode(' ',$arr2);
	


	
}

private function emailjiami($email){
	
	$arr = explode('@',$email);
	
	
	return $this->jx($arr[0]).'@'.$arr[1];
	
	

	
}

 


private function jx($v){ 
	$v = trim($v);
		
	if(strlen($v)>4){
		$f = substr( $v, 0 ,2);
		$l = substr( $v, -2);
		$m = '***';
	}elseif(strlen($v)==4){
		$f = substr( $v, 0 ,1);
		$l = substr( $v, -1);
		$m = '***';
	}elseif(strlen($v)==3){
		$f = substr( $v, 0 ,1);
		$l = substr( $v, -1);
		$m = '**';
	}elseif(strlen($v)==2){
		$f = substr( $v, 0 ,1);
		$l = '';
		$m = '**';
	}else{
		return '';
	}
	
	return $f.$m.$l;
	
}
















	//--------------------------------------------------------------------

}
