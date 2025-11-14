<?php



function checkPermission($code){
	GLOBAL $baseUrl;
	
	if( !isset($_SESSION['xadmin']) || $_SESSION['xadmin'] < $code )
		{
			$noPermission =  'You don\'t have permission to access this page.';
			echo $noPermission;
			session_destroy();
			exit();
		}
}

function debug(){	
		if($_SERVER['HTTP_HOST'] == 'localhost') return true;
		return (isset($_SESSION['mloggedin']) &&  $_SESSION['mloggedin'] == 545);
}

/**
 * Check if the application is running on a local server
 * 
 * @return bool True if running on local server (development environment or localhost)
 */
function isLocalServer(){
	return (defined('ENVIRONMENT') && ENVIRONMENT === 'development') || 
		   (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost');
}

function checkPermissionOnly($code){

	
	if( !isset($_SESSION['xadmin']) || $_SESSION['xadmin'] < $code )
		{
			return false;
		}
		
		return true;
}


function checkPermissionByDes($des){
	
	GLOBAL $config_permission;
	$adminCode = isset($_SESSION['xadmin']) ? $_SESSION['xadmin'] : 0 ;
	
	if( $adminCode == 9 ){
		
		return true;
		
	}elseif( isset($config_permission[$adminCode]) && isset($config_permission[$adminCode][$des]) ){
		
		return true;
	}
		
	return false;
}

function checkMemberLogin($url=''){	
	
	if( !isset($_SESSION['mloggedin']) || !$_SESSION['mloggedin'] )
		{			
	
			GLOBAL $baseUrl;
			
			if($url){
				$_SESSION["memberLoginTo"] = $url;	
			}else{
				if(isset($_SESSION["memberLoginTo"])) unset($_SESSION['memberLoginTo']);
			}	
	
	
			header("Location: $baseUrl");
			exit();
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
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email/batch");

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);

	curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields

	$headers = array();
	$headers[] = "Accept: application/json";
	$headers[] = "Content-Type: application/json";
	$headers[] = "X-Postmark-Server-Token: 68d811c1-5543-44e4-8bf2-4ca6a302a762";

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec ($ch);

	curl_close ($ch);
	
	return $server_output;
	
	
	//$sResult = json_decode(sendtomandrill($subject, $message, $to));	

	// if($sResult->ErrorCode == 0){
		// echo 'Your feedback has been sent to our offices. Thank you!';
	// }else{
		// echo 'Error, Please contact your website administrator.';
	// }	
	
}

function sendtomandrill($subject,$message,$email)
{
	
	
	$vars = '{"From": "admin@tracycrosspoint.org", "To": "'.$email.'", "Subject": "'.$subject.'", "HtmlBody": "'.addslashes($message).'"}';

	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api.postmarkapp.com/email");

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);

	curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields

	$headers = array();
	$headers[] = "Accept: application/json";
	$headers[] = "Content-Type: application/json";
	$headers[] = "X-Postmark-Server-Token: 68d811c1-5543-44e4-8bf2-4ca6a302a762";

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec ($ch);

	curl_close ($ch);
	
	return $server_output;
	
	
	//$sResult = json_decode(sendtomandrill($subject, $message, $to));	

	// if($sResult->ErrorCode == 0){
		// echo 'Your feedback has been sent to our offices. Thank you!';
	// }else{
		// echo 'Error, Please contact your website administrator.';
	// }	
	
}	


function makeConfirmationCode($bid,$pw,$subid,$email){
	
	$str = $bid.'jm'.$pw.'jm'.$subid.'jm'.$email;
	$str = encode($bid.'|||'.md5($str),'jmforConfirmation');
	return $str;
	
}



