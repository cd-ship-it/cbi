<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\WebmetaModel;
use App\Models\To_doModel;
use App\Models\PrayerItemsModel;
use App\Models\CapabilitiesModel; 	

class Weekly_prayers extends BaseController
{
	

	
	
	
	
	
	
	
			
	public function index()
	{  
	
	
		$data = $this->data;
		
		$data['canonical']=base_url('weekly_prayers');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('dashboard_view','exit');

		$action = $this->request->getPost('action');	
	
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();
		$modelProfiles = new ProfilesModel();		
		$modelCapabilities = new CapabilitiesModel();
		
		$data['pageTitle'] = 'Weekly Prayers';
		
		
		$newestPrayers = $modelPrayerItems->getNewest4Prayer();
		
		
		
		
		
		
		$data['newestPrayers'] = $newestPrayers = array_reverse($newestPrayers);
		
	 
		 
		 
		
		
		foreach($newestPrayers as $key => $item){
			if($item['start']>time()){
				$activeWeek = $item;
				break;
			}
			
			$activeWeek = $newestPrayers[0];
		}		
		
		
 		
		$data['activeWeek'] =  $activeWeek;
 
		
		
		
		$prayers = $modelWebmetaModel->where('meta_key', 'prayerTier'.$activeWeek['tier'])->first()['meta_value']; 
		$keys = array(0, 1, 2, 3, 4, 5, 6);		
		
		if(!$prayers){			
			$data['prayers'] =  array_fill_keys($keys, '');	
		}else{
			$data['prayers'] = json_decode($prayers);
		}
 
		
		$prayerItems = $modelPrayerItems->getPrayerItems($activeWeek['id']);
		$data['prayerItems'] = array_fill_keys($keys, '');	
		
		
		foreach($prayerItems as  $prayerItem){
			
			
			$theDay = $prayerItem['day'];
			
			if(isset($data['prayers'][$theDay])&&$data['prayers'][$theDay]==$prayerItem['pid']) $data['prayerItems'][$theDay] = $prayerItem;
					
			
		}		
		
		
		
		


// var_dump($this->logged_id);
// var_dump($data['prayers']);
// var_dump($data['prayerItems']); exit;
				

		
			$data['menugroup'] = 'prayer_items';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			
			
			$data['page'] = 'weekly_prayers';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data); 			
			
			echo view('theme-sb-admin-2/layout',$data);	
		 
		
		}
			
		

		
private function curlpost($url,$timeout=0,$vars='',$headers=[])
{
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
	
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	
	
	if($vars) curl_setopt($ch, CURLOPT_POST, true);
	if($vars) curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  

	if($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	

	$server_output = curl_exec ($ch);
	
    if ($server_output === false) {
        var_dump(curl_error($ch));
    }	
	

	curl_close ($ch);
	
	return $server_output;

}	
			
	
	


	//--------------------------------------------------------------------

}
