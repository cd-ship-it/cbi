<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;

use App\Models\ClassesModel;
use App\Models\ProfilesModel;


class Import extends BaseController
{
public function index($fileName)
{
	exit;		
	
	$webConfig = new \Config\WebConfig();		
	$webConfig->checkMemberLogin();
	


	if($fileName=='20240110.CSV'){
		
		$format = array('fName','mName', 'lName',     'mPhone', 'email' );
		
		
		$r = $this->getFileData( WRITEPATH. $fileName ,$format);
		
		$modelProfilesModel = new ProfilesModel();
		
		foreach($r as $item){
			$item['inactive'] = 2; //guest
			$modelProfilesModel->insert($item);
			print_r($item);
		}
		
		
		

		exit;		
		
		
	}else{
		
		
		echo 'Error';exit;
	}
		

	
	
	
}	
	
	
 
	
public function inactive()
{
	$webConfig = new \Config\WebConfig();		
	$webConfig->checkMemberLogin();
	$webConfig->checkPermission(8);		
		
	$format = array('lName','mName', 'fName',   'gender', 'marital',   'mPhone', 'email',  'nocb',  'family', 'site');
	$r = $this->getFileData(WRITEPATH.'inactive.csv',$format);
	
	$modelProfilesModel = new ProfilesModel();
	
	foreach($r as $item){
		$item['inactive'] = 1;
		//$modelProfilesModel->insert($item);
		 print_r($item);
	}

	exit;
}
			

public function ezoneData()
{
	$webConfig = new \Config\WebConfig();		
	$webConfig->checkMemberLogin();
	$webConfig->checkPermission(8);		
		
	$format = array('timestamp','email','name', 'phone', 'age', 'from', 'city', 'group' );
	$r = $this->getFileData(WRITEPATH.'2021.CSV',$format);
	
	//print_r($r); exit;
	if($r){
		
	$modelProfilesModel = new ProfilesModel();
	$user = [];
	$log = [];
	
		foreach($r as $item){
			$user['inactive'] = 1;
			$user['email'] =  trim($item['email']);
			$user['hPhone'] =  preg_replace('#[^0-9]#','',$item['phone']);
			$user['city'] =  trim($item['city']);
			
			$nameArr = explode(' ',trim($item['name']));
			$user['fName'] = trim($nameArr[0]);
			$user['mName'] = count($nameArr)==3?trim($nameArr[1]):'';
			$user['lName'] = end($nameArr)==$user['fName']?'':end($nameArr);
			
			$userExist = $modelProfilesModel->where('email', $user['email'])->findAll();
			// var_dump($userExist); exit;
			
			if(!$userExist){
				
				if($uid = $modelProfilesModel->insert($user)){
					//$uid = $modelProfilesModel->insert_id();
					$log[$uid] = trim($item['email']); 
				}else{
					$log[0][] = trim($item['email']);
				}
			}else{
				$log[$userExist[0]['id']] = $userExist[0]['email'];
			}
			
			//$modelProfilesModel->insert($item);
			
		}
		
		echo json_encode($log);
	}

	exit;
}
		
	
public function exmember()
{
	$webConfig = new \Config\WebConfig();		
	$webConfig->checkMemberLogin();
	$webConfig->checkPermission(8);		
		
	$format = array('lName','mName', 'fName',   'gender', 'marital',   'mPhone', 'email',  'nocb',  'family', 'site');
	$r = $this->getFileData(WRITEPATH.'exmember.csv',$format);	
	
	$modelProfilesModel = new ProfilesModel();
	
	foreach($r as $item){
		$item['inactive'] = 5;
		$modelProfilesModel->insert($item);
		 print_r($item);
	}

	exit;
}



private function getFileData($file,$format)
{
    if (!is_file($file)) {
        exit('没有文件');
    }

    $handle = fopen($file, 'r');
    if (!$handle) {
        exit('读取文件失败');
    }
	
	$r = [];

    while (($data = fgetcsv($handle)) !== false) {
        // 下面这行代码可以解决中文字符乱码问题
        // $data[0] = iconv('gbk', 'utf-8', $data[0]);


        // data 为每行的数据，这里转换为一维数组
		$newItem =  array_combine($format, $data);
		//print_r($newItem);
		
		if($newItem){
			$r[] = $newItem;
		}else{
			 exit('error');
		}

    }

    fclose($handle);
	return $r;
}







	//--------------------------------------------------------------------

}
