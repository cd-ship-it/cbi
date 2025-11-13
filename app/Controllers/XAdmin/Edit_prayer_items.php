<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\ProfilesModel; 
use App\Models\WebmetaModel;
use App\Models\PrayerItemsModel;
use App\Models\To_doModel;
use App\Models\CapabilitiesModel; 	

class Edit_prayer_items extends BaseController
{
	
	
	public function index()
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/edit_prayer_items');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],'exit');

		$action = $this->request->getPost('action');		
		
		


	 
		
		
		
		
		

	
	

		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();		
		$modelProfiles = new ProfilesModel();
		$modelTo_do = new To_doModel();
		$modelCapabilities = new CapabilitiesModel();
		

		$theItem = $modelPrayerItems->getLastPrayerItem();


 
		
		
		$data['pageTitle'] = 'Prayer Items';
		
		 
		
 	
		
		
		$data['theItem'] = $theItem;
		
		$data['author_name'] = $theItem ? $modelProfiles->getUserName($theItem['pid']) : 0;
		$data['prayer_id'] = $theItem ? $theItem['id'] : 0;
		
		
		//dd($data);

		
		if($action && $action=='prayerItemSave'){
			
			$prayerItem['pid'] = $this->logged_id;
			
			if($this->request->getPost('prayer_id'))$prayerItem['id'] = $this->request->getPost('prayer_id');
			
			$prayerItem['day'] = time();
			
			$prayerItem['en'] = trim($this->request->getPost('en'));
			$prayerItem['zh_hant'] = trim($this->request->getPost('zh_hant'));
			$prayerItem['zh_hans'] = trim($this->request->getPost('zh_hans'));
			
			
 
 
			$r = $modelPrayerItems->insertOrUpdate($prayerItem);
		
		
			if($r){
				
				echo 'updated';
				
			}else{
				echo 'error';
			}
			
			exit();	
			
		}	
				


		 
		
		$data['translation_url']=base_url('translation2');
		
		
		
		
			$data['menugroup'] = 'prayer_items';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			
			
			$data['page'] = 'edit_prayer_items';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data); 			
			
			echo view('theme-sb-admin-2/layout',$data);		
		
		
	
		
		}
			
		

		
		
	
	


	//--------------------------------------------------------------------

}
