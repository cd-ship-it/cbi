<?php namespace App\Controllers;




class Privacy extends BaseController
{
	public function index()
	{	
	
		$data = $this->data;
		$data['header'] = view('cbi_header',array('userLg'=>$this->lang,'login'=>$this->logged_id));

		echo view('cbi_privacy',$data);
	
	
	
	
	
	}
	
	
	

	
	

	//--------------------------------------------------------------------

}
