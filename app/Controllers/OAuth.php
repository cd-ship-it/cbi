<?php namespace App\Controllers;


use App\Models\MembersModel;
use App\Models\ProfilesModel;

class OAuth extends BaseController
{
	public function index()
	{	
		$loginMsg = '';
		$session = \Config\Services::session();
		$webConfig = new \Config\WebConfig();
		
		$modelMembers = new MembersModel();
		$modelProfiles = new ProfilesModel();
		
		// Development bypass: Auto-login as user ID 149 in development mode
		if (ENVIRONMENT === 'development' && isset($_GET['dev_bypass']) && $_GET['dev_bypass'] === '149') {
			$devBid = 149;
			$member = $modelMembers->db_m_member($devBid);
			
			if ($member) {
				$modelPermission = new \App\Models\PermissionModel();
				$permissionSet = $modelPermission->getUserPermissionNames($devBid);
				
				$modelMembers->lastactivityUpdate($devBid);
				
				$newdata = [
					'mloggedin'  => $member['bid'],
					'mloggedinName'     => ucwords($member['name']),
					'email'     => $member['email'],
					'capabilities'     => $permissionSet,
					'dsfPicture'     => '',
					'xadmin' => $member['admin']
				];

				$session->set($newdata);
				$loginMsg = 'logged-in';
				
				// Redirect to home or specified redirect
				$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : base_url();
				return redirect()->to($redirect);
			} else {
				die('Development bypass failed: User ID 149 not found in database.');
			}
		} 
		
		$dsfUser = false;
		$action = isset($_GET['action'])&&$_GET['action'] ? $_GET['action'] : 'signup';
		$dsf = isset($_GET['dsf'])&&$_GET['dsf'] ? $_GET['dsf'] : '';
		$refer = isset($_GET['refer'])&&$_GET['refer'] ? $_GET['refer'] : '';


		if($dsf) $data['dsf'] = $dsf;
		if($action) $data['action'] = $action;
		if($refer) $data['refer'] = $refer;

		$redirect_uri_plain = base_url('OAuth').'?'.http_build_query($data);
		$redirect_uri = urlencode($redirect_uri_plain);

		// Dev only: show exact redirect URI for Google Console (fix redirect_uri_mismatch)
		if (ENVIRONMENT === 'development' && isset($_GET['oauth_debug']) && $_GET['oauth_debug'] === '1') {
			header('Content-Type: text/plain; charset=utf-8');
			echo "Add this EXACT URI to Google Cloud Console → Credentials → Your OAuth client → Authorized redirect URIs:\n\n";
			echo $redirect_uri_plain . "\n\n";
			echo "For signup and change flows, add both:\n";
			echo base_url('OAuth').'?dsf=google&action=signup' . "\n";
			echo base_url('OAuth').'?dsf=google&action=change' . "\n";
			return;
		}
		
		if(isset($_GET['dsf'])&&$_GET['dsf']=='yahoo'){
			
			$code = isset($_GET['code'])?$_GET['code']:''; 
			
			$url = 'https://api.login.yahoo.com/oauth2/get_token';
			
			$vars = "code=$code&grant_type=authorization_code&client_id=$webConfig->yahoo_client_id&client_secret=$webConfig->yahoo_client_secret&redirect_uri=$redirect_uri";
			
			$r_token = $this->curlpost($url,$vars,['Content-Type: application/x-www-form-urlencoded']);
			
			
			if($r_token){
				
				$r_token_obj = json_decode($r_token);
				
				if($r_token_obj && isset($r_token_obj->access_token)){
				
					$r_user = $this->curlpost('https://api.login.yahoo.com/openid/v1/userinfo','access_token='.$r_token_obj->access_token);
				
				
			


				if($r_user){
					
					$r_user_obj = json_decode($r_user);
					
					if($r_user_obj && isset($r_user_obj->sub)){
					
						$dsfUser['service'] = 'yahoo';
						$dsfUser['sub'] = $r_user_obj->sub;
						$dsfUser['email'] = $r_user_obj->email;
						$dsfUser['dsfPicture'] = $r_user_obj->profile_images->image192;
						
						
						if(isset($r_user_obj->name)){
							$fullNameArr = explode(' ',$r_user_obj->name);
							$dsfUser['fname'] = $fullNameArr[0];
							$dsfUser['mname'] = isset($fullNameArr[2])?$fullNameArr[1]:'';
							$dsfUser['lname'] = isset($fullNameArr[1])?$fullNameArr[1]:'';
						}else{
							$dsfUser['fname'] = $dsfUser['mname'] = $dsfUser['lname'] = '';
							log_message('error', $r_user);

						}
					
					}
				}
				
				}
			
			}
			
		}elseif(isset($_GET['dsf'])&&$_GET['dsf']=='google'){
			
			
			$code = isset($_GET['code'])?$_GET['code']:''; 
			
			$url = 'https://oauth2.googleapis.com/token';
			
			$vars = "code=$code&client_id=$webConfig->googleCLIENT_ID&client_secret=$webConfig->google_client_secret&grant_type=authorization_code&redirect_uri=$redirect_uri";
			
			$r_token = $this->curlpost($url,$vars,['Content-Type: application/x-www-form-urlencoded']);
			
	 	
			
			
			if($r_token){
				
				$r_token_obj = json_decode($r_token);
				
				if($r_token_obj && isset($r_token_obj->access_token)){
				
					$r_user = $this->curlpost('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$r_token_obj->access_token);
				
				
							
				
				if($r_user){
					
					$r_user_obj = json_decode($r_user);
					
					if($r_user_obj && isset($r_user_obj->id)){
				
					$dsfUser['service'] = 'google';
					$dsfUser['sub'] = $r_user_obj->id;
					$dsfUser['email'] = $r_user_obj->email;
					
					$fullNameArr = explode(' ',$r_user_obj->name);
					$dsfUser['fname'] = $fullNameArr[0];
					$dsfUser['mname'] = isset($fullNameArr[2])?$fullNameArr[1]:'';
					$dsfUser['lname'] = isset($fullNameArr[1])?$fullNameArr[1]:'';
					$dsfUser['dsfPicture'] = $r_user_obj->picture;
					
					// Store refresh token if provided (for long-term authentication)
					if(isset($r_token_obj->refresh_token)){
						$dsfUser['refresh_token'] = $r_token_obj->refresh_token;
					}
				
					}
				}
				
				}
			
			}
			


			
			
		}elseif(isset($_GET['dsf'])&&$_GET['dsf']=='microsoft'){
			
			
			$code = isset($_GET['code'])?$_GET['code']:''; 
			


			
			$url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
			
			$vars = "scope=User.Read&code=$code&client_id=$webConfig->microsoft_client_id&client_secret=$webConfig->microsoft_client_secret&grant_type=authorization_code&redirect_uri=$redirect_uri";
			
			$r_token = $this->curlpost($url,$vars,['Content-Type: application/x-www-form-urlencoded']);
			

			
			
			if($r_token){
				
				$r_token_obj = json_decode($r_token);
				
				if($r_token_obj && isset($r_token_obj->access_token)){
				
					$bearer = "Authorization: Bearer {$r_token_obj->access_token}";
					
					$headers = array( $bearer );
				
					$r_user = $this->curlpost('https://graph.microsoft.com/v1.0/me','',$headers);
 
				// var_dump($r_user);exit;
				
				
							
				
				if($r_user){
					
					$r_user_obj = json_decode($r_user);
					
					if($r_user_obj && isset($r_user_obj->id)){
				
					$dsfUser['service'] = 'microsoft';
					$dsfUser['sub'] = $r_user_obj->id;
					$dsfUser['email'] = $r_user_obj->mail;
					
					$fullNameArr = explode(' ',$r_user_obj->displayName);
					$dsfUser['fname'] = $fullNameArr[0];
					$dsfUser['mname'] = isset($fullNameArr[2])?$fullNameArr[1]:'';
					$dsfUser['lname'] = isset($fullNameArr[1])?$fullNameArr[1]:'';
					$dsfUser['dsfPicture'] = '';
				
					}
				}
				
				}
			
			}
			


			
			
		}elseif(isset($_GET['dsf'])&&$_GET['dsf']=='auth0'){
			


			$code = isset($_GET['code'])?$_GET['code']:''; 
			
			$url = 'https://dev-v7xd6e5vshfmrhw6.us.auth0.com/oauth/token';
 
			
			$vars = "grant_type=authorization_code&client_id={$webConfig->auth0_client_id}&client_secret={$webConfig->auth0_secret}&code={$code}&redirect_uri={$redirect_uri}";
			
			$r_token = $this->curlpost($url,$vars,['Content-Type: application/x-www-form-urlencoded']);
			
			
			
			
			if($r_token){
				
				$r_token_obj = json_decode($r_token);
				
				// var_dump($r_token_obj);exit;
				
				if($r_token_obj && isset($r_token_obj->access_token)){
					
					$bearer = "Authorization: Bearer {$r_token_obj->access_token}";
					
					$headers = array( $bearer );
				
					$r_user = $this->curlpost('https://dev-v7xd6e5vshfmrhw6.us.auth0.com/userinfo','',$headers);
 
				// var_dump($r_user);exit;
				
							
				
				if($r_user){
					
					
					
					$r_user_obj = json_decode($r_user);
					
					if($r_user_obj && isset($r_user_obj->sub)){
				
					$dsfUser['service'] = 'auth0';
					$dsfUser['sub'] = $r_user_obj->sub;
					if(isset($r_user_obj->email)){
						$dsfUser['email'] = $r_user_obj->email;
					}else{
						$dsfUser['email'] = '';
					}
					
					$fullNameArr = explode(' ',$r_user_obj->name);
					$dsfUser['fname'] = $fullNameArr[0];
					$dsfUser['mname'] = isset($fullNameArr[2])?$fullNameArr[1]:'';
					$dsfUser['lname'] = isset($fullNameArr[1])?$fullNameArr[1]:'';
					$dsfUser['dsfPicture'] = $r_user_obj->picture;
				
					}
				}
				
				}
			
			}




		} 


		


		if($dsfUser){



			
			
			if($action=='signup'){
			
				$member = $modelMembers->getDsfMember($dsfUser['service'],$dsfUser['sub']);
				
				if($member&&$member['blocked']){
					
					
					echo $loginMsg = 'error';	exit();
					
					
				}elseif($member){
					
					
					
				
					
					$permissionSet = $this->modelPermission->getUserPermissionNames($member['bid']);
					
			 
					
					$modelMembers->lastactivityUpdate($member['bid']);				
					
					$newdata = [
						'mloggedin'  => $member['bid'],
						'mloggedinName'     => ucwords($member['name']),
						'email'     => $dsfUser['email'],
						'capabilities'     => $permissionSet,
						'dsfPicture'     => $dsfUser['dsfPicture'],
						'xadmin' => $member['admin']
					];

					$session->set($newdata);					
					
					
					
					
					
					//eZone login
					// $vars = http_build_query($newdata);			
					// $ezToken = $this->curlpost($webConfig->ezoneLogin,$vars,['Content-Type: application/x-www-form-urlencoded'],0);		

					$loginMsg = 'logged-in';	
					// $loginMsg = $ezToken;	
					
					
				}else{					
					
					$newdata = [
						'dsfService'  => $dsfUser['service'],
						'dsfEmail'     => $dsfUser['email'],
						'dsfId' => $dsfUser['sub'],
						'dsfFname' => $dsfUser['fname'],
						'dsfMname' => $dsfUser['mname'],
						'dsfLname' => $dsfUser['lname'],
						'dsfPicture' => $dsfUser['dsfPicture'],
					];

					$session->set($newdata);
					
					$loginMsg = 'signup';					
				}

			}elseif($action=='change'){
				
				$email = $dsfUser['email'] ? $dsfUser['email'] : false;
				
				
				$loginMsg = $modelMembers->dsfChange($session->get('mloggedin'),$dsfUser['service'],$dsfUser['sub']);
				
				if($email&&$loginMsg=='changed'){
					$modelProfiles->update($session->get('mloggedin'), array('email'=>$email));
					$modelMembers->where('bid', $session->get('mloggedin'))->set(array('subemail' => $email))->update();
				}
				
				
			}	
			
		}else{
			
			$loginMsg = 'error';
		}



echo 
'<!DOCTYPE html>
<html lang="en-US">
<head>
<!-- COMMON TAGS -->
<meta charset="utf-8">
<title>OAuth</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
</style>
</head>
<body>
<h1>Processing...</h1>
<p><a href="'.base_url().'">refresh</a></p>
<script>	
window.opener.OAuthReturn("'.$loginMsg.'");
</script>
</body>
</html>
';

	

	}
	
	
	


		
		
		
		private function curlpost($url,$vars='',$headers=[],$proxy=1)
		{
			
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);

			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
			
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
			
			if($_SERVER['HTTP_HOST'] == 'localhost' && $proxy){
				curl_setopt($ch,CURLOPT_PROXYTYPE,CURLPROXY_SOCKS5);
				curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1:19593");  
			}
			
			
			if($vars){
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);
			}else{
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			}				
			
			

			if($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$server_output = curl_exec ($ch);

			curl_close ($ch);
			
			return $server_output;

		}	
	

	//--------------------------------------------------------------------

}
