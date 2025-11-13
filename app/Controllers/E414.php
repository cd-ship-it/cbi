<?php namespace App\Controllers;


use App\Models\ElearningModel;
use App\Models\WebmetaModel;
use App\Models\ProfilesModel;
use App\Models\ClassesModel;
use App\Models\CapabilitiesModel;
use App\Models\MembersModel;
		


class E414 extends BaseController
{
	
	
	public function index()
	{
		

		$data = $this->data;
		$data['canonical']=base_url('e414');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		#var_dump($this);
		
		#if( !in_array($this->logged_id,[558,132,1187,1251000282,1251000281,310,107,336,133,350,343,149,545,386,207,213]) ){
			
		#	echo 'Error'; exit;
			
		#}
		
		$action = $this->request->getPost('action');
		$return = [];	


		$data['pageTitle']='4/14特別會議';		
		
		
		echo view('e414',$data);
		exit();				
		
		
	}	
	


	


	//--------------------------------------------------------------------

}
