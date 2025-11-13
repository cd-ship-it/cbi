<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\MembersModel;


class Invite extends BaseController
{
	
	
	public function index()
	{
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/invite');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);			

		$action = $this->request->getPost('action');		
		

		
		$modelMembersModel = new MembersModel();		
		
		

		
		$data['pageTitle']='invite people';
		$data['pageSlug']='invite-people';
		$data['fsubmitLable']='Send';			
		
		if($action=='send'){
			
			$toEmail = $this->request->getPost('toEmail');
			$message = $this->request->getPost('message');
			
			$message = nl2br(preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1">$1</a>',   $message));
			
			$this->webConfig->sendtomandrill('You\'re invited to register with Crosspoint Bible Institute (CBI)', $message, $toEmail);		
			
			echo 'Sent successfully';	
			exit();
			
		}
		
		$data['from'] = $this->request->getGet('toEmail');
		$to = ucwords($this->request->getGet('to'));
		$data['email'] = $this->request->getGet('email');;		
		
		
		$data['preMessage'] = "Hello $to :

As a member or frequent attendee of Crosspoint Church, you're invited to register with Crosspoint Bible Institute (CBI) at the following link ".base_url()." in order to register for CBI classes and manage your class registration and attendance records. You can register with your Google or Yahoo account.

If you have further questions, please email training@crosspointchurchsv.org.

Crosspoint Bible Institute";
		



			$data['menugroup'] = 'users';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_invite';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data); 			
			
			echo view('theme-sb-admin-2/layout',$data);	 
			
			
		
		}
			
		

		
		
	
	


	//--------------------------------------------------------------------

}
