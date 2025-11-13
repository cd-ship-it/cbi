<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\ProfilesModel; 
use App\Models\WebmetaModel;
use App\Models\PrayerItemsModel;
use App\Models\To_doModel;
use App\Models\CapabilitiesModel; 	

class Debug extends BaseController
{
	
	
	public function index($prayer_id,$day)
	{
		
		$data['canonical']=base_url('xAdmin/edit_prayer_items/'.$prayer_id.'/'.$day);
		
		$webConfig = new \Config\WebConfig();		
		$webConfig->checkMemberLogin($data['canonical']);
		
		$data['webConfig']=$webConfig;
		
 		$session = \Config\Services::session();	
		$data['logged'] = $session->get('mloggedin') ? $session->get('mloggedin') : false;
		
		
		
		
		
		

		$action = $this->request->getPost('action');		

		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();		
		$modelProfiles = new ProfilesModel();
		$modelTo_do = new To_doModel();
		
		$prayerPost = $modelPrayerItems->find($prayer_id);
		
		if(!$prayerPost){
			echo 'post id not found';
			exit();
		}


		$prayers = $modelWebmetaModel->where('meta_key', 'prayerTier'.$prayerPost['tier'])->first()['meta_value']; 		
		if(!$prayers){					
			$keys = array(0, 1, 2, 3, 4, 5, 6);		
			$data['prayers'] =  array_fill_keys($keys, '');	
		}else{
			$prayers = json_decode($prayers);
		}
		
		var_dump($prayers); exit;
		
		
	}
	
	
	
}