<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReportModel;
use App\Models\MembersModel;
use App\Models\CapabilitiesModel;
		
use App\Models\To_doModel;
use App\Models\ProfilesModel;

	
class Report extends BaseController
{
	
	
	public function summary2()
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('report/summary2');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		if(!$this->webConfig->checkPermissionByDes('management')){
			$this->webConfig->checkPermissionByDes('is_senior_pastor',1);
		}		

		$action = $this->request->getPost('action');
		
		$modelReport = new ReportModel();
		
		$spMonth = date('Ym',strtotime('first day of -5 month'));
		
		
		$data['pageTitle'] = 'Pastoral Reports Summary Dashboard';
		
		$data['chartData'] = $modelReport->get_data_for_chart($spMonth);
		
 
		
			
			

			echo view('report_summary',$data);		


		



	}	
	
	
	public function summary()
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('report/summary');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		

		if(!$this->webConfig->checkPermissionByDes('edit_report') && !$this->webConfig->checkPermissionByDes('view_report')){
			$this->webConfig->checkPermissionByDes('is_senior_pastor',1);
		}		

		$action = $this->request->getPost('action');
		
		$modelReport = new ReportModel();
		
		$spMonth = date('Ym',strtotime('first day of -5 month'));
		
		
		$data['pageTitle'] = 'Pastoral Reports Summary Dashboard';
		
		$data['chartData'] = $modelReport->get_data_for_chart($spMonth);
		
 
		
			// $data['menugroup'] = 'report';
			// $data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			// $data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			// $data['page'] = 'report_summary';
			
			// $data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			

			$ouput =  view('theme-sb-admin-2/report_summary',$data);	

			if($this->webConfig->checkPermissionByDes('is_senior_pastor')){
				$endFix = date('YmdHi');
				file_put_contents(WRITEPATH.'is_senior_pastor_'.$endFix, $ouput ); 
			}
			
			
			echo $ouput;



	}
	
	public function user($uid)
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('report/user/'.$uid);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		if(!$this->webConfig->checkPermissionByDes('edit_report') && !$this->webConfig->checkPermissionByDes('view_report')){
			$this->webConfig->checkPermissionByDes('is_senior_pastor',1);
		}		

		$action = $this->request->getPost('action');			
		
		


		$modelProfiles = new ProfilesModel();	
		$modelReport = new ReportModel();	 
		
		
		$data['author_name'] = $modelProfiles->getUserName($uid); 
		$data['pageTitle'] = $data['author_name'].'’s Monthly Report' ;
		 
		
		$data['goBackUrl'] = base_url('report/summary');		
		
		
		$data['userReport'] = $modelReport->where(['bid'=>$uid])->orderBy('month', 'DESC')->findAll();


			$data['menugroup'] = 'report';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'report_archive';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);			
		
	}



	
	public function index()
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('report');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		if(!$this->webConfig->checkPermissionByDes('edit_report') && !$this->webConfig->checkPermissionByDes('view_report')){
			$this->webConfig->checkPermissionByDes('is_senior_pastor',1);
		}		

		$action = $this->request->getPost('action');			
		
		



		$modelReport = new ReportModel();	
		$modelMembers = new MembersModel();	
		$modelCapabilities = new CapabilitiesModel();

		$data['pageTitle'] = 'Zone Pastor’s Monthly Report';

		
		$senior_pastor =  $modelCapabilities->get_sp_users('is_senior_pastor');
		$exclude = [$senior_pastor,   $this->logged_id];		
		$data['pastors'] = $this->modelPermission->getPermittedUsers('is_pastor');
		
		$data['pastors_selected'] = $modelCapabilities->get_allow_view_myReport_pastors($this->logged_id);
		
		
		if($this->webConfig->checkPermissionByDes('edit_report')){
			
			$fullReport = $myReport = [];
			
			$myReport_pre = $modelReport->where(['bid'=>$this->logged_id])->findAll();
			
			foreach($myReport_pre as $key => $item){
				
				$myReport[$item['month']] = $item;
				
			}
			
			$begin = strtotime('first day of -2 month');
			
			while($begin<strtotime('first day of -1 month')){
				
				$key =  date('Ym',$begin);
				$fullReport[$key] = '';
				
				$begin = strtotime('first day of +1 month',$begin);
				
			}
			
			
			$data['myReport'] = array_replace($fullReport,$myReport);
			
			
			// var_dump($data['myReport']); exit;
			
			
		}
		
		
		if($this->webConfig->checkPermissionByDes('is_senior_pastor')||$this->webConfig->checkPermissionByDes('view_report')){
			
			$dispaly_all_reports = $this->webConfig->checkPermissionByDes('is_senior_pastor') || $this->webConfig->checkPermissionByDes('management');
			
			$data['otherReport'] = $modelReport->get_other_report($this->logged_id,$dispaly_all_reports);
			
		}
		
		
		
		$data['deadline'] = date('m/d (l)',strtotime('second tuesday of this month'));
		
		
		
		
		
		
		if($action=='setting'){
			
			
			
			
			
			$capability = '_view_report_'.$this->logged_id;
			$uid = $this->request->getPost('uid');
			$val = $this->request->getPost('val');
			
			if($val){
				$cap['bid'] = $uid;
				$cap['capability'] = $capability;
				$cap['value'] = $this->logged_id;
				
				$r = $modelCapabilities->replace($cap);				
			}else{
				
				$r = $modelCapabilities->where(['bid'=>$uid,'capability'=>$capability])->delete();
			}
			
			
			 	
			
			
			
			
			
			
			exit;
			
		}		
		
	 		

		
		
		 
		
			$data['menugroup'] = 'report';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'report_archive';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);			
		
		
	}
			
		

	public function submit($year_month)
	{		
		
		$data = $this->data;		
		$data['canonical']=base_url('report/submit/'.$year_month);
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		$this->webConfig->checkPermissionByDes('edit_report',1);
		
		
		$r = preg_match('#^(\d{4})(\d{2})$#i',$year_month,$match);
		
		if($r==false){
			echo 'Error';exit;
		}
		
		
		
		$data['year'] = $year = $match[1];
		$data['month'] = $month = $match[2];
		
		$action = $this->request->getPost('action');	 


		$modelReport = new ReportModel();
		$modelTo_do = new To_doModel();	
		
				
		

		
		$data['pageTitle'] = 'Zone Pastor’s Monthly Report';
		
		
		$data['goBackUrl'] = base_url('report');
		
		$data['report']	= $modelReport->where(['bid'=>$this->logged_id,'month'=>$year_month])->first();
		$data['submitter']	= $this->logged_name;
		
		
		$data['allow_submit'] = date('Ym',strtotime('last month'))==$year_month || !$data['report'] ? TRUE : FALSE;
		
		
		
		if($data['allow_submit'] && $action == 'report_submit'){ 
		
			
			$report_data['bid'] = $this->logged_id;
			$report_data['month'] = $year_month;
			
			$report_data['a1'] = $this->request->getPost('a1');
			$report_data['a2'] = $this->request->getPost('a2');
			$report_data['a3'] = $this->request->getPost('a3');
			$report_data['a4'] = $this->request->getPost('a4');
			$report_data['a_comment'] = $this->request->getPost('a_comment');
			
			$report_data['b1'] = $this->request->getPost('b1');
			$report_data['b2'] = $this->request->getPost('b2');
			$report_data['b3'] = $this->request->getPost('b3');
			$report_data['b4'] = $this->request->getPost('b4');
			$report_data['b_comment'] = $this->request->getPost('b_comment');
			
			$report_data['c1'] = $this->request->getPost('c1');
			$report_data['c_comment'] = $this->request->getPost('c_comment');
			
			$report_data['p1'] = $this->request->getPost('p1');
			$report_data['p2'] = $this->request->getPost('p2');
			$report_data['p3'] = $this->request->getPost('p3');
			$report_data['p4'] = $this->request->getPost('p4');	
			
 
 

			if($modelReport->replace($report_data)){
				
				
				
								$code = 'zone_report_'.$this->logged_id.'_'.$year_month;					
								$modelTo_do->where('code', $code)->delete();		
				
				
				echo 'Submitted successfully';
				
				
			}else{
				
				echo 'Error';
			}				
			
			
			
			
			
			
			
			
			
			
		
		
			exit;
			
		}
		
		
		
		
		
		

	
		
			$data['menugroup'] = 'report';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'report_submit';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);			
		
		
		
	 
	}	



	public function view($report_id)
	{		
		
		$data = $this->data;		
		$data['canonical']=base_url('report/view/'.$report_id);
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$modelProfiles = new ProfilesModel();		
		$modelReport = new ReportModel();
		
		$data['report']	= $modelReport->find($report_id);
		
		if(!$data['report'] || !preg_match('#^(\d{4})(\d{2})$#i',$data['report']['month'],$match)){
			echo 'Error';exit;
		}
		
		
		
		
		$action = $this->request->getPost('action');
		$data['author_id']  = $data['report']['bid'];
		$data['author_name'] = $modelProfiles->getUserName($data['author_id']); 
		$data['year'] = $year = $match[1];
		$data['month'] = $month = $match[2];
		
		
		if(!$this->webConfig->checkPermissionByDes('_view_report_'.$data['author_id']) && !$this->webConfig->checkPermissionByDes('management')){
			$this->webConfig->checkPermissionByDes('is_senior_pastor',1);
		}		
		
				
		$data['pageTitle'] = $data['author_name'].'’s Monthly Report ' . $data['year'] . '/' . $data['month'] .  ( ( $data['report']['month'] == date('Ym',strtotime('last month')))?' <span style="    color: red;">[NEW]</span>':'' );
		 
		
		$data['goBackUrl'] = base_url('report');
		
		
		$data['submitter']	= $data['author_name'];
		
		$data['model']	= 'report_view';
		
		
		$data['allow_submit'] =   FALSE;

		
		
		
		
		
		

			$data['menugroup'] = 'report';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'report_submit';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);		
		
		
		
		
		
		 
	}	



	//--------------------------------------------------------------------

}
