<?php namespace App\Controllers;


 

class Translation extends BaseController
{

private $url = "http://api.fanyi.baidu.com/api/trans/vip/translate" ;
private $timeout = 10 ;
private $appid = '20221029001423659' ;
private $seckey = 'PYpWuZMVFvHGDvXHeCYT' ;



public function index(){
	
	
	$query = $this->request->getPost('query')?$this->request->getPost('query'):"testing<br />testing";
	$from = $this->request->getPost('from')?$this->request->getPost('from'):'en';
	$to = $this->request->getPost('to')?$this->request->getPost('to'):'cht';
	
	
	
	
	if($query){
		
		
		
		$ret = $this->translate($query, $from, $to); 
		
		if(isset($ret['trans_result'])){
			
		
		
			
			foreach($ret['trans_result'] as $line){
				
				echo $line['dst'] . "\n";
				
			}
			
			
			
		}else{
			echo 'error';
		}
		
	}
	
	
	
	
	
}


//翻译入口
private function translate($query, $from, $to)
{
    $args = array(
        'q' => $query,
        'appid' => $this->appid,
        'salt' => rand(10000,99999),
        'from' => $from,
        'to' => $to,

    );
    $args['sign'] = $this->buildSign($query, $this->appid, $args['salt'], $this->seckey);
    $ret = $this->call($this->url, $args);
    $ret = json_decode($ret, true);
    return $ret; 
}

//加密
private function buildSign($query, $appID, $salt, $secKey)
{/*{{{*/
    $str = $appID . $query . $salt . $secKey;
    $ret = md5($str);
    return $ret;
}/*}}}*/

//发起网络请求
private function call($url, $args=null, $method="post", $testflag = 0, $timeout = 0, $headers=array())
{/*{{{*/
	if($timeout) $timeout = $this->timeout ; 


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
}/*}}}*/

private function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = 0, $headers=array())
{/*{{{*/

	if($timeout) $timeout = $this->timeout ;
	
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
}/*}}}*/

private function convert(&$args)
{/*{{{*/
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
}/*}}}*/





}
