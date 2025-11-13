<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\MinistryModel;

class Ministries extends BaseController
{
	
	
	public function index()
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/ministries');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);			

		$action = $this->request->getPost('action');		
		
		
		
		$modelMinistry = new MinistryModel();		
		
		
		
		$data['ministries'] = $modelMinistry->findAll();
		$data['pageTitle']="Serving position";
		 
		$data['adminUrl'] = base_url('xAdmin');		
		
		$action = $this->request->getPost('action');
		
		if($action == 'update'){ 
			//var_dump($_POST); exit;
			
			$mid = $this->request->getPost('mid');
			$data['en'] = $this->request->getPost('enVal');
			$data['zh-Hant'] = $this->request->getPost('hantVal');
			$data['zh-Hans'] = $this->request->getPost('hansVal');
			
			$r= 'error';
			
			if($mid){
				
				if($modelMinistry->update($mid, $data)){
					$r= 'ok';
				}
				
			}else{
				
				if($modelMinistry->insert($data)){
					$r= 'ok';
				}				
				
			}
			

			
			echo $r;
			exit();
			
		}elseif($action == 'remove'){ 
			
		
			
			$mid = $this->request->getPost('mid');
			if($modelMinistry->delete($mid)){
				$r= 'ok';
			}else{
				$r= 'error';
			}	
			echo $r;
			exit();
			
		}
		
		
		
		
			$data['menugroup'] = 'users';		
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_ministries';

			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);		
		
		}
			
		


	


	//--------------------------------------------------------------------

}
