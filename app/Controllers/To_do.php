<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\WebmetaModel;
use App\Models\To_doModel;
use App\Models\PrayerItemsModel;

class To_do extends BaseController
{
	
	
	public function index()
	{
		$data['canonical']=base_url('to_do');
		
		$webConfig = new \Config\WebConfig();		
		$webConfig->checkMemberLogin($data['canonical']);
		$data['webConfig']=$webConfig;
		$action = $this->request->getPost('action');
		$session = \Config\Services::session();	
		

		
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();
		$modelProfiles = new ProfilesModel();		
		$modelTo_do = new To_doModel();	
		
		$toDoList = $modelTo_do->where(['bid'=>$session->mloggedin])->orderBy('status ASC, end ASC')->findAll(); //,'end >'=>time()

	
	 
		
		$data['toDoList'] = $toDoList;
		$data['pageTitle'] = 'To-do List';
		

		
			
		
		
		
		$itemId = $this->request->getPost('itemId');
		
		if($itemId && $action=='archive'){
			
			
			$modelTo_do->where('id', $itemId)->set(array('status' => 1))->update();

			exit();
			
			
			
			
		}elseif($itemId && $action=='remove'){
			
			
			
			$modelTo_do->where('id', $itemId)->delete();
			
			exit();
			
			
		}
		
		
		
		
			$data['mloggedinName'] = $session->get('mloggedinName');
			
			$data['cbi_header'] = view('cbi_header',array('userLg'=>$this->lang,'login'=>$session->get('mloggedin')));
			
			echo view('member_to_do',$data);	
		
		
		
		
		
		
		
	}	
	
	
	
	
	
	
	
		
		
	
	


	//--------------------------------------------------------------------

}
