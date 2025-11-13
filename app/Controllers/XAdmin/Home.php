<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\WebmetaModel;
use App\Models\To_doModel;
use App\Models\PrayerItemsModel;
use App\Models\ElearningModel;

use App\Models\ClassesModel;
use App\Models\MinistryModel;

class Home extends BaseController
{
	
	
	public function index()
	{
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('dashboard_view','exit');	

		$action = $this->request->getPost('action');
		
		
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();
		$modelProfiles = new ProfilesModel();		
		$modelTo_do = new To_doModel();	
		$modelElearning = new ElearningModel();	
		
		$toDoList = $modelTo_do->where(['bid'=>$this->logged_id])->orderBy('status ASC, end ASC')->findAll(); //,'end >'=>time()

		$students = $modelElearning->get_pending_approve_students(1,$this->logged_id); 

		$pastoralApproval = $modelProfiles->get_pastoralApprovalMembers($this->logged_id); 

	 
		
		$data['pastoralApproval'] = $pastoralApproval;
		$data['students'] = $students;
		$data['toDoList'] = $toDoList;
		
		$data['pageTitle'] = 'Crosspoint Staff Page';
		
		
		
		
			
		
		
		
		$itemId = $this->request->getPost('itemId');
		
		if($itemId && $action=='archive'){
			
			
			$modelTo_do->where('id', $itemId)->set(array('status' => 1))->update();

			exit();
			
			
			
			
		}elseif($itemId && $action=='remove'){
			
			
			
			$modelTo_do->where('id', $itemId)->delete();
			
			exit();
			
			
		}
		
		
		
		
		
		
		
		
		
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['mainContent'] = view('theme-sb-admin-2/to_do',$data);
			
			echo view('theme-sb-admin-2/layout',$data);	
		
		
		
		
		
		
		
	}	
	
	
	
	
	
 
		
	
	


	//--------------------------------------------------------------------

}
