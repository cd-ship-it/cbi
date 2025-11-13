<?php namespace Config;

class WebConfig extends \CodeIgniter\Config\BaseConfig
{
	
	
	public $ptoSpecialUser = '369';
	

	
	
	public $enable_pto_monthly_update  = TRUE;
	public $enable_classes_reminder = TRUE;	
	public $enable_NVA_update_and_email = TRUE;


	
	public $googleCLIENT_ID = '627452465726-ccmjlaks98jnsb3ftr4ppis2e10su1od.apps.googleusercontent.com';
	public $google_client_secret = '93f_WvGhW3n_i07U5aLaUSi0';

	public $yahoo_client_id = 'dj0yJmk9dEc3MEhiNWxVTWhGJmQ9WVdrOVNWTlJXRmR4YW5JbWNHbzlNQT09JnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PWM1';
	public $yahoo_client_secret = 'b2e7b9b60bb8eb8a585276b810656d1a395d19d7';
	
	public $microsoft_client_id = '8dcf6ecd-7b6e-4473-a8df-53e47573da0d';
	public $microsoft_client_secret = 'getenv("MICROSOFT_CLIENT_SECRET") ?: ""';		

	public $sites =['Milpitas','Pleasanton','Peninsula','Tracy','San Leandro','Stockton'];
	public $memberCodes = array( 1=>'Inactive', 2=>'Guest', 3=>'Member', 4=>'Pre-Member', 5=>'Ex-Member', 6=>'Pending');

	public $curriculumCodes = array('en'=>array(						
								'CBIB01'=>array('CBIB01','CBIB01 - Walk Through the Bible 1',''),
								
								'CBIB02'=>array('CBIB02','CBIB02 - Walk Through the Bible 2',''),
								
								'CBIB20'=>array('CBIB20','CBIB20 - Bible Study Methods',''),
								
								'CBIB30'=>array('CBIB30','CBIB30 - Creative Teaching',''),
								
								'CBIB40'=>array('CBIB40','CBIB40 - Bible book',''),						
								
								'CBIF101'=>array('CBIF101','CBIF101 - Crosspoint Basics',''),
								
								'CBIF000'=>array('CBIF000','CBIF000 - New Believers Class',''),
								
								'CBIF100'=>array('CBIF100','CBIF100 - Basic Christian Beliefs',''),
								
								'CBIF201'=>array('CBIF201','CBIF201 - Spiritual Formation',''),
								
								'CBIF301'=>array('CBIF301','CBIF301 - Spiritual Gift Inventory',''),
								
								'CBIF401'=>array('CBIF401','CBIF401 - Religions and Cults',''),
								
								'CBIE01'=>array('CBIE01','CBIE01 - Elective classes','')),
								
								'zh-Hant'=>array(							
								'CBIB01'=>array('CBIB01','CBIB01 - 聖言空間 1',''),
								
								'CBIB02'=>array('CBIB02','CBIB02 - 聖言空間 2',''),
								
								'CBIB20'=>array('CBIB20','CBIB20 - 十步釋經',''),
								
								'CBIB30'=>array('CBIB30','CBIB30 - 創意教學',''),
								
								'CBIB40'=>array('CBIB40','CBIB40 - 聖經書卷研讀',''),						
								
								'CBIF101'=>array('CBIF101','CBIF101 - 認識匯點',''),
								
								'CBIF000'=>array('CBIF000','CBIF000 - 初信班',''),
								
								'CBIF100'=>array('CBIF100','CBIF100 - 基要信仰班',''),
								
								'CBIF201'=>array('CBIF201','CBIF201 - 靈命塑造',''),
								
								'CBIF301'=>array('CBIF301','CBIF301 - 恩賜與事奉',''),
								
								'CBIF401'=>array('CBIF401','CBIF401 - 宗教與異端',''),
								
								'CBIE01'=>array('CBIE01','CBIE01 - 選修課','')),
								
								'zh-Hans'=>array(
									'CBIB01'=>array('CBIB01','CBIB01 - 圣言空间 1',''),

									'CBIB02'=>array('CBIB02','CBIB02 - 圣言空间 2',''),

									'CBIB20'=>array('CBIB20','CBIB20 - 十步释经',''),

									'CBIB30'=>array('CBIB30','CBIB30 - 创意教学',''),

									'CBIB40'=>array('CBIB40','CBIB40 - 圣经书卷研读',''),

									'CBIF101'=>array('CBIF101','CBIF101 - 认识汇点',''),

									'CBIF000'=>array('CBIF000','CBIF000 - 初信班',''),

									'CBIF100'=>array('CBIF100','CBIF100 - 基要信仰班',''),

									'CBIF201'=>array('CBIF201','CBIF201 - 灵命塑造',''),

									'CBIF301'=>array('CBIF301','CBIF301 - 恩赐与事奉',''),

									'CBIF401'=>array('CBIF401','CBIF401 - 宗教与异端',''),

									'CBIE01'=>array('CBIE01','CBIE01 - 选修课','')),
								
							);







	public $supporedFileFormat =['gif','jpg','bmp','png','psd','ico','rar','7z','zip','rmvb','3gp','flv','mp3','wav','doc','xls','ppt','pdf','docx','jpeg','xlsx','dwg','skp','txt','mpg','mp4','avi','pptx','mov'];
	public $supporedFileSize = 2; //mb

	public $config_permission = array(
						
						8=>array('code'=>8,'slug'=>'super admin','view_report'=>1,'edit_class'=>1,'add_class'=>1,'management'=>1,'pto_apply'=>1,'add_people'=>1),
						5=>array('code'=>5,'slug'=>'admin','edit_class'=>1,'add_class'=>1,'pto_apply'=>1,'add_people'=>1),
						3=>array('code'=>3,'slug'=>'pastor','edit_class'=>1,'pto_apply'=>1,'add_people'=>1),
						2=>array('code'=>2,'slug'=>'intern','add_people'=>1),
						1=>array('code'=>1,'slug'=>'office','add_people'=>1),
						0=>array('code'=>0,'slug'=>'user'),


						);


	public $capabilitiesOps =  ['is_pastor','manage_zone_pastors','add_people'];




	public $ezoneUrl = 'https://crosspointchurchsv.org/ezone/';
	public $ezoneDb = 'crossp11_cbi';
	public $ezoneDbPrefix = 'ezwp_';





/////////////////////////////////////////


 

function debug(){
		$session = \Config\Services::session();	
		if($_SERVER['HTTP_HOST'] == 'localhost') return true;
		return ($session->get('xadmin') &&  $session->get('xadmin') == 9);
}

 

function checkPermissionByDes($des,$do_exit=false){
	
	$session = \Config\Services::session();
	$user_id =  $session->get('mloggedin');
	$userCaps = $session->get('capabilities');
	$permission = false;
	
	
	
	if( !is_array($des) ){
		$des = [$des];
	}
	
	
	if( !$userCaps || !is_array($userCaps) ){
		
		$permission = false;
  
	}elseif(in_array('is_super_admin',$userCaps)){

		$permission = true;
		
	}else{

		foreach($des as $d){
			if( in_array($d,$userCaps) ){
				$permission = true;
				break;
			}
		}

	}
	
	

	
	
	if(!$do_exit){
		
		return $permission;
		
	}elseif($do_exit && !$permission){
		
			$url =  $userCaps && is_array($userCaps) && in_array('dashboard_view',$userCaps) ? base_url('xAdmin') : base_url();
		
		
			$noPermission =  'You don\'t have permission to access this page. <a href="'.$url.'">Back to homepage</a>';
			echo $noPermission;
			log_message('error','Permission check fail. '.uri_string().' user:'.$user_id.' des:'.implode(',',$des));
			exit();		
	}
	

}


function checkMemberLogin($url=''){	
	$session = \Config\Services::session();
	
	if( !$session->get('mloggedin') )
		{			
	
			$session->destroy();
			
			if($url){
				$redirectUrl = base_url().'?redirect='.rawurldecode($url);	
			}else{
				$redirectUrl = base_url();
			}	
	
	
			header("Location: $redirectUrl");
			exit();
		}
}

function isLogin(){	
	$session = \Config\Services::session();
	
	if( !$session->get('mloggedin') ){			
		return false;
	}else{
		return true;	
	}
}



function encode($string = '', $skey = 'mit') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}


function decode($string = '', $skey = 'mit') {
	if($string == '') return '';
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

function sendEmail($to,$subject,$message,$headers=[]){
	//return;
	//$to = 'red@projectsatoz.com'; //debug
	$headersStr = $headers?implode("\r\n",$headers):'';
	mail($to, $subject, $message, $headersStr);
	
	
}





function Sendbatchemails($vars)
{



	if(isset($vars['template'])&&$vars['template']){
		
		
		
		foreach($vars['template'] as $key => $item){
			
			$data = [];	
			
			$data['from'] =  ["address" => "no-reply@crosspointchurch.cc","name"=>"Crosspoint Church"];
			$data['mail_template_key'] =  $key;
			$data['to'] = $item;
			
			
			
				$sendData = json_encode($data);
				
				// file_put_contents(WRITEPATH.'emailLogs',  $sendData  .PHP_EOL . PHP_EOL , FILE_APPEND);
				// exit;
				
				$curl = curl_init();

					curl_setopt_array($curl, array(
						CURLOPT_URL => "https://api.zeptomail.com.cn/v1.1/email/template/batch",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS => $sendData,
						CURLOPT_HTTPHEADER => array(
						"accept: application/json",
						"authorization: Zoho-enczapikey eiwqDPgJvGtdKwYRnCdiL+C43OJgW++c/KLP5hYpVfBBS+HPSHtAUllgpAu+KQR3Ki/wFexvafcjz5DxtQEk/MkTZX8MuaCq+CaF7ISNMHtAL/6LeVmGwB9Vhgc1aqgJWKMG/EA0Bpuj",
						"cache-control: no-cache",
						"content-type: application/json",
					),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
					
					echo "cURL Error #:" . $err;
					
				} else {
					
					$sResult = json_decode($response);
					
					if(isset($sResult->error)){
						file_put_contents(WRITEPATH.'emailLogs', $response  .PHP_EOL . $sendData  .PHP_EOL . PHP_EOL , FILE_APPEND);
						unset($vars['template']);
						$this->Sendbatchemails2($vars);
					}
					
				}



			
			
			
			
			
			
		}
		
		
		
	}else{
		$this->Sendbatchemails2($vars);
	}
	

	return 1;

	
}







function sendtomandrill($subject,$message,$email,$cc=0)
{
	

	
	$myEmailItem = [];	
	$myEmailItem['from'] =  ["address" => "no-reply@crosspointchurch.cc","name"=>"Crosspoint Church"];
	$myEmailItem['to'] = [["email_address" => ["address" => $email]]]; 	
	$myEmailItem['subject'] =  $subject;
	$myEmailItem['htmlbody'] = $message;

	if($cc){
		$myEmailItem['cc'] = [["email_address" => ["address" => $cc]]]; 	
	}		

	$sendData = json_encode($myEmailItem);

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.zeptomail.com.cn/v1.1/email",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $sendData,
		CURLOPT_HTTPHEADER => array(
			"accept: application/json",
			"authorization: Zoho-enczapikey eiwqDPgJvGtdKwYRnCdiL+C43OJgW++c/KLP5hYpVfBBS+HPSHtAUllgpAu+KQR3Ki/wFexvafcjz5DxtQEk/MkTZX8MuaCq+CaF7ISNMHtAL/6LeVmGwB9Vhgc1aqgJWKMG/EA0Bpuj",
			"cache-control: no-cache",
			"content-type: application/json",
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		
		echo "cURL Error #:" . $err;
		return 0;
		
	} else {
		
		$sResult = json_decode($response);
		
		if(isset($sResult->error)){
			file_put_contents(WRITEPATH.'emailLogs', $response  .PHP_EOL . $sendData  .PHP_EOL . PHP_EOL , FILE_APPEND);
			$this->sendtomandrill2($subject,$message,$email);
			return 0;
		}
		
	}


	return 1;

	
	
}	





function Sendbatchemails2($vars)
{
	$sendData = json_encode($vars);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/batch");

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);  //Post Fields

	$headers = array();
	$headers[] = "Accept: application/json";
	$headers[] = "Content-Type: application/json";
	$headers[] = "X-Postmark-Server-Token: 68d811c1-5543-44e4-8bf2-4ca6a302a762";

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec ($ch);
	
	$sResult = json_decode($server_output);
	
	file_put_contents(WRITEPATH.'emailLogs', $server_output  .PHP_EOL . $sendData  .PHP_EOL . PHP_EOL , FILE_APPEND);
	

	curl_close ($ch);
	
	return $server_output;
	

	
}

function sendtomandrill2($subject,$message,$email)
{
	


	$myEmailItem = [];	
	$myEmailItem['From'] =  'admin@tracycrosspoint.org';
	$myEmailItem['To'] = $email; 	
	$myEmailItem['Subject'] =  $subject;
	$myEmailItem['HtmlBody'] = $message;	
	
	
	
	$sendData = json_encode($myEmailItem);

	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email");

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);

	curl_setopt($ch, CURLOPT_POSTFIELDS,$sendData);  //Post Fields

	$headers = array();
	$headers[] = "Accept: application/json";
	$headers[] = "Content-Type: application/json";
	$headers[] = "X-Postmark-Server-Token: 68d811c1-5543-44e4-8bf2-4ca6a302a762";

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec ($ch);
	
	$sResult = json_decode($server_output);
	
	file_put_contents(WRITEPATH.'emailLogs', $server_output  .PHP_EOL . $sendData  .PHP_EOL . PHP_EOL , FILE_APPEND);

	curl_close ($ch);
	
	return $server_output;
	

	
	
}


function makeConfirmationCode($bid,$pw,$subid,$email){
	
	$str = $bid.'jm'.$pw.'jm'.$subid.'jm'.$email;
	$str = $this->encode($bid.'|||'.md5($str),'jmforConfirmation');
	return $str;
	
}



function makeEZoneUrl($url=''){
	
	$session = \Config\Services::session();
	
	if( !$session->get('mloggedin') ){			
		return false;
	}

		if(!$url) $url = $this->ezoneUrl.'account';
		
		$lcode[] = rand();
		$lcode[] = $session->get('mloggedin');
		$lcode[] = $session->get('mloggedinName');
		$lcode[] = $session->get('email');
		$lcode[] = $session->get('xadmin');
		$lcode[] = time();		
		
		$key = md5($_SERVER['HTTP_USER_AGENT'].'e2one');
		
		$lcodeEncode = $this->encode(implode('##',$lcode),$key);
		
		
	$url_ob = parse_url($url);
	$url = $url_ob['scheme'] . '://' . $url_ob['host']  . $url_ob['path'];	
		
	$url = $url.'?code='.$lcodeEncode . ( isset($url_ob['query'])&&$url_ob['query'] ? '&'.$url_ob['query'] : '' );
		
		
		
		return $url;
	
	
	
}





	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
