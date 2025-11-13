<?php 
namespace App\Libraries;

define("CURL_TIMEOUT",   2000);
define("URL",            "https://openapi.youdao.com/api");
define("APP_KEY",        "444a47f3e5056d2a"); 
define("SEC_KEY",        "d3wdH4J7rtO4FgbsVxigBzY9PeiwCJ08");
 

class TranslationYD
{



// private $url = "https://openapi.youdao.com/api" ;
// private $timeout = 2000 ;
// private $appid = '444a47f3e5056d2a' ;
// private $seckey = 'd3wdH4J7rtO4FgbsVxigBzY9PeiwCJ08' ;

// zh-CHT
// zh-CHS
// en


// $q = "待输入的文字";

// $ret = do_request($q);
// print_r($ret);
// $ret = json_decode($ret, true);

//,"translation":["The text to be entered"],"errorCode":"0",

public function tra($query, $compress=false , $from='zh-CHT', $to='zh-CHS'){
	
	
	if($compress){
		$query = $this->higrid_compress_html($query);
	} 
	
	
	$return = '';
	
	 
		
		
		
		$ret = $this->do_request($query, $from, $to);  // var_dump($ret);
		
		if(isset($ret['errorCode'])&&$ret['errorCode']==0){
			
		
		
			 
			foreach($ret['translation'] as $line){
				
				$return .= $line ;
				
			}
			
			
			
		}else{
			$return =  'error';
		}
		
 
	
	
	return $return;
	
}



function higrid_compress_html($higrid_uncompress_html_source )
{
	$chunks = preg_split( '/(<pre.*?\/pre>)/ms', $higrid_uncompress_html_source, -1, PREG_SPLIT_DELIM_CAPTURE );
	$higrid_uncompress_html_source = '';//[higrid.net]
	foreach ( $chunks as $c )
	{
	if ( strpos( $c, '<pre' ) !== 0 )
	{
	//[higrid.net] remove new lines & tabs
	$c = preg_replace( '/[\\n\\r\\t]+/', ' ', $c );
	// [higrid.net] remove extra whitespace
	$c = preg_replace( '/\\s{2,}/', ' ', $c );
	// [higrid.net] remove inter-tag whitespace
	$c = preg_replace( '/>\\s{2,}</', '> <', $c );
	// [higrid.net] remove CSS & JS comments
	$c = preg_replace( '/\\/\\*.*?\\*\\//i', '', $c );
	}
	$higrid_uncompress_html_source .= $c;
	}
	return $higrid_uncompress_html_source;
}


function do_request($q,$from,$to)
{
    $salt = $this->create_guid();
    $args = array(
        'q' => $q,
        'appKey' => APP_KEY,
        'salt' => $salt,
    );
    $args['from'] = $from;
    $args['to'] = $to;
    $args['signType'] = 'v3';
    $curtime = strtotime("now");
    $args['curtime'] = $curtime;
    $signStr = APP_KEY . $this->truncate($q) . $salt . $curtime . SEC_KEY;
    $args['sign'] = hash("sha256", $signStr);
    $ret = $this->call(URL, $args);
    return json_decode($ret, true);
}


function call($url, $args=null, $method="post", $testflag = 0, $timeout = CURL_TIMEOUT, $headers=array())
{
    $ret = false;
    $i = 0;
    while($ret === false)
    {
        if($i > 1)
            break;
        if($i > 0)
        {
            sleep(1);
        }
        $ret = $this->callOnce($url, $args, $method, false, $timeout, $headers);
        $i++;
    }
    return $ret;
}

function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = CURL_TIMEOUT, $headers=array())
{
    $ch = curl_init();
    if($method == "post")
    {
        $data = $this->convert($args);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, 1);
    }
    else
    {
        $data = $this->convert($args);
        if($data)
        {
            if(stripos($url, "?") > 0)
            {
                $url .= "&$data";
            }
            else
            {
                $url .= "?$data";
            }
        }
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(!empty($headers))
    {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    if($withCookie)
    {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
    }
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function convert(&$args)
{
    $data = '';
    if (is_array($args))
    {
        foreach ($args as $key=>$val)
        {
            if (is_array($val))
            {
                foreach ($val as $k=>$v)
                {
                    $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                }
            }
            else
            {
                $data .="$key=".rawurlencode($val)."&";
            }
        }
        return trim($data, "&");
    }
    return $args;
}

// uuid generator
function create_guid(){
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);
    $dec_hex = dechex($a_dec* 1000000);
    $sec_hex = dechex($a_sec);
    $this->ensure_length($dec_hex, 5);
    $this->ensure_length($sec_hex, 6);
    $guid = "";
    $guid .= $dec_hex;
    $guid .= $this->create_guid_section(3);
    $guid .= '-';
    $guid .= $this->create_guid_section(4);
    $guid .= '-';
    $guid .= $this->create_guid_section(4);
    $guid .= '-';
    $guid .= $this->create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= $this->create_guid_section(6);
    return $guid;
}

function create_guid_section($characters){
    $return = "";
    for($i = 0; $i < $characters; $i++)
    {
        $return .= dechex(mt_rand(0,15));
    }
    return $return;
}

function truncate($q) {
    $len = $this->abslength($q);
    return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
}

function abslength($str)
{
    if(empty($str)){
        return 0;
    }
    if(function_exists('mb_strlen')){
        return mb_strlen($str,'utf-8');
    }
    else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}

function ensure_length(&$string, $length){
    $strlen = strlen($string);
    if($strlen < $length)
    {
        $string = str_pad($string, $length, "0");
    }
    else if($strlen > $length)
    {
        $string = substr($string, 0, $length);
    }
}

 




}
