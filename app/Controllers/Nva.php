<?php namespace App\Controllers;


use App\Models\ElearningModel;
use App\Models\WebmetaModel;
use App\Models\ProfilesModel;
use App\Models\ClassesModel;
use App\Models\MembersModel;
use App\Models\CapabilitiesModel;


use App\Models\VisitorsModel;

class Nva extends BaseController
{
	
	
	public $visitor_stage;
	public $campuses;
	public $visitor_life_status;

	
	function __construct() {
		 
		$visitorsModel = new VisitorsModel();
		 
		$visitor_stage = $visitorsModel->get_visitor_stage(); 
		
		$campuses = $visitorsModel->get_campuses();
		
		$visitor_life_status = $visitorsModel->get_visitor_life_status();
		
		$this->visitor_stage = array_column($visitor_stage,'display_name','id');
		
		$this->campuses = array_column($campuses,'campus');
		
		$this->visitor_life_status = array_column($visitor_life_status,'life_status');	
		
		$this->visitorsModel = $visitorsModel;
		 
	 }	
	
	

	





	
	
	public function index()
	{
		
		$data['userCaps']= $this->userCaps;
		
		$data['userLg']= $this->lang;
		
		$data['session']= $this->session;
		
		$data['webConfig']= $this->webConfig;
		
		$data['canonical']= base_url('nva');	
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['dashboard_view'],'exit');
		
 
		$modelProfiles = new ProfilesModel();
		$visitorsModel = new VisitorsModel();
		
		$action = $this->request->getPost('action');

		$data['pageTitle'] = 'New Visitor Assimilation';
		
		$data['current_user_id'] = $this->session->get('mloggedin') ? $this->session->get('mloggedin') : 0;		
		if($data['current_user_id']){			
			$data['userName'] =  $modelProfiles->getUserName($data['current_user_id']);			
			$data['userPicture'] =  $modelProfiles->db_m_getUserField($data['current_user_id'],'picture'); 
		}	
		
		$data['userPicture'] = isset($data['userPicture'])&&$data['userPicture'] ? $data['userPicture'] : base_url().'/assets/images/default_user_profile.jpg';		
		
		
		 
		
		$data['user_summary']['new'] =  $visitorsModel->getSummary($data['current_user_id'],1,0,0,0,0);
		$data['user_summary']['following'] =  $visitorsModel->getSummary($data['current_user_id'],2,0,0,0,0);
		$data['user_summary']['attended'] =  $visitorsModel->getSummary($data['current_user_id'],3,0,0,0,0);
		$data['user_summary']['joined'] =  $visitorsModel->getSummary($data['current_user_id'],4,0,0,0,0);
		
		
		$start = date("Y-m-d",strtotime("-1 year"));
		$end = date("Y-m-d");
		
		$data['zone_summary'] =  $visitorsModel->getZoneSummary(0,$start,$end);
		
// var_dump($data['zone_summary']); exit;



		$data['main_content'] =  view('nva_summary',$data);

		
		
		

		
		
		$data['campuses'] = $this->campuses; echo view('nva',$data);			
		
		
	}







	public function detail($vistor_id)
	{
		
		$data['userCaps']= $this->userCaps;
		
		$data['userLg']= $this->lang;
		
		$data['session']= $this->session;
		
		$data['webConfig']= $this->webConfig;		
		
		$data['canonical']= base_url('nva/detail/'.$vistor_id);	
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		
		if(!$this->webConfig->checkPermissionByDes('_delegate_'.$vistor_id)){
			$this->webConfig->checkPermissionByDes(['dashboard_view'],'exit');
		}
		
	 
		
		$data['current_user_id'] = $this->session->get('mloggedin');

		$modelMembers = new MembersModel();
		$modelCapabilities = new CapabilitiesModel();
		$modelProfiles = new ProfilesModel();
		$visitorsModel = new VisitorsModel();
		
		if($data['current_user_id']){			
			$data['userName'] =  $modelProfiles->getUserName($data['current_user_id']);			
			$data['userPicture'] =  $modelProfiles->db_m_getUserField($data['current_user_id'],'picture'); 
		}	
		
		$data['userPicture'] = isset($data['userPicture'])&&$data['userPicture'] ? $data['userPicture'] : base_url().'/assets/images/default_user_profile.jpg';				
		
		
		$action = $this->request->getPost('action');	




		$data['detail'] = $visitorsModel->getDetail($vistor_id);
		
		
		if(!$data['detail']){
			
			echo 'visitor does not exists';
			
			exit;
		}
		
		
		if(isset($data['detail']['delegate_id'])&&$data['detail']['delegate_id']){
			$data['assigned_delegate_name'] = $modelProfiles->getUserName($data['detail']['delegate_id']);
			$data['assigned_delegate_email'] = $modelProfiles->db_m_getUserField($data['detail']['delegate_id'],'email');
			$data['assigned_delegate_phone'] = $modelProfiles->db_m_getUserField($data['detail']['delegate_id'],'mPhone');
		}
		
		
		
		$senior_pastor =  $modelCapabilities->get_sp_users('is_senior_pastor');
		$exclude = [$senior_pastor];		
		$data['pastors'] = $this->modelPermission->getPermittedUsers('dashboard_view');		
		
		 
		
		$data['activityLog'] = $visitorsModel->getActivityLog($vistor_id);

		
		// var_dump($excludeIds);
		
		if($action && $action=="vistor_field_update"){
			
			$field = $this->request->getPost('field');

			
			if($field=="stage_id"){
				

				$new_val = $this->request->getPost('new_stage_id');	
				$old_val = $data['detail']['stage_id'];
				
				
				
				if($new_val != $old_val){
					
					
					$log = $data['userName'].' | Stage: '.$this->visitor_stage[$old_val].' -> '.$this->visitor_stage[$new_val];
					
					$visitorsModel->field_update($vistor_id,$field,$new_val,$old_val,$data['current_user_id'],$log);
				}
				
				echo 'OK';
				
				
			}elseif($field=="removeVistor"){
				
				
				if($visitorsModel->where('id', $vistor_id)->delete()){
					
					$log = $data['userName'].' | visitor removed';
					
					$logData['visitor_id'] = $vistor_id;
					$logData['field'] = '';
					$logData['user_login'] = $data['current_user_id'];
					$logData['log'] = $log;				
					
					$visitorsModel->visitor_change_log($logData);
					
					echo 'OK';					
					
					
				}
				
				
				
				
			}elseif($field=="assigned_id"){
				

				$new_val = $this->request->getPost('new_assigned_id');	
				$old_val = $data['detail']['assigned_id'];

				$current_stage = $data['detail']['stage_id'];
				
				$new_name = $new_val ? $modelProfiles->getUserName($new_val) : 'N/A';	
				$old_name = $old_val ? $modelProfiles->getUserName($old_val) : 'N/A';	
				
				
				
				if($new_val != $old_val){		
				
					$log = $data['userName'].' | Assign(Pastor): '.$old_name.' -> '.$new_name;
					$visitorsModel->field_update($vistor_id,$field,$new_val,$old_val,$data['current_user_id'],$log);
					
					$new_email = $new_val ? $modelProfiles->db_m_getUserField($new_val,'email')  : '';	
					if($current_stage != 1 && $new_email) $this->emailNotifications($new_val,$new_name,$new_email,$vistor_id);
					
				}
				
				echo 'OK';
				
				
			}elseif($field=="delegate_id"){
				

				$new_val = $this->request->getPost('new_delegate_id') ? $this->request->getPost('new_delegate_id') : NULL;	
				$old_val = $data['detail']['delegate_id'];
				
				$new_name = $new_val ? $modelProfiles->getUserName($new_val) : 'N/A';	
				$old_name = $old_val ? $modelProfiles->getUserName($old_val) : 'N/A';
							
				
				if($new_val != $old_val){	
				
					$log = $data['userName'].' | Assign(Delegate): '.$old_name.' -> '.$new_name;
					$visitorsModel->field_update($vistor_id,$field,$new_val,$old_val,$data['current_user_id'],$log);
					
					
					$cap['bid'] = $new_val;
					$cap['capability'] = '_delegate_'.$vistor_id;
					$cap['value'] = $vistor_id;
					
					
					if($old_val) $modelCapabilities->where(['bid'=>$old_val,'capability'=>'_delegate_'.$vistor_id,'value'=>$vistor_id])->delete();		
					if($new_val) $modelCapabilities->replace($cap);	
					
					$new_email = $new_val ? $modelProfiles->db_m_getUserField($new_val,'email')  : '';	
					if($new_email) $this->emailNotifications($new_val,$new_name,$new_email,$vistor_id);
					
					
					
				}
				
				echo 'OK';
				
				
			}
			
			
			
			

			

			exit;
		}elseif($action && $action=="searchBaptisms"){
			
			
			$keywords = $this->request->getPost('query');	
			
			$excludeIds = $modelCapabilities->where('capability', 'is_pastor')->findColumn('bid');		
			if($data['detail']['delegate_id'] ){
				array_push($excludeIds,$data['detail']['delegate_id']);
			}
			
			
			
			$r = $modelProfiles->searchBaptisms($keywords,$excludeIds);
			
			echo json_encode($r);
			
			exit;
			
		}elseif($action && $action=="update_visitor"){
			
			
		
				$item['date_visited'] = $this->request->getPost('date_visited') ? date('Y-m-d',strtotime($this->request->getPost('date_visited'))) : '';
				$item['campus'] = $this->request->getPost('campus');
				$item['name'] = $this->request->getPost('name');
				$item['email'] = $this->request->getPost('email');
				$item['phone'] = $this->request->getPost('phone');
				$item['learnAbout'] = $this->request->getPost('learnAbout');
				$item['preferred_language'] = $this->request->getPost('preferred_language');
				$item['greeter'] = $this->request->getPost('greeter');
				$item['notes'] = trim($this->request->getPost('notes'));
				$item['lifeStatus'] = $this->request->getPost('lifeStatus');
				
				
				if(!$item['date_visited'] || !$item['campus'] || !$item['name'] || !$item['greeter']){
					
					echo 'Error';
					
				}else{
					
					
					$r = $visitorsModel->update($vistor_id, $item);
					
					if($r){
						echo 'OK';
					}					
					
					
				}
				
				
				 

			
			exit;
		}
		
		
 
		$data['campuses'] = $this->campuses;
		
		
		$data['visitor_stage'] = $this->visitor_stage;
		
		$data['visitor_life_status'] = $this->visitor_life_status;
		
		$data['campuses'] = $this->campuses; 
		
		$data['main_content'] =  view('nva_detail',$data);			
		
		
		echo view('nva',$data);			
		

	}

	public function table($uid=0,$stage_id=0,$start=0,$end=0,$campus=0,$peferred_lg=0)
	{
		
		$data['userCaps']= $this->userCaps;
		
		$data['userLg']= $this->lang;
		
		$data['session']= $this->session;
		
		$data['webConfig']= $this->webConfig;	

		$campus_real = $campus ? str_replace('-',' ',$campus) : 0;
		
		$data['canonical']= base_url('nva/table/'.$uid.'/'.$stage_id.'/'.$start.'/'.$end.'/'.$campus);	
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$data['current_user_id'] = $this->session->get('mloggedin');	
		$data['current_user_subordinates'] = $this->modelPermission->getSubordinates($data['current_user_id']);
		
		 
				
			if(!$uid || $uid !== $data['current_user_id']){
				$this->webConfig->checkPermissionByDes(['dashboard_view'],'exit');
			}	
				
		 
		 
		
		
		
		$data['enableDataTables'] = 1;	
		
		$data['uid'] = $uid;	
		$data['stage_id'] = $stage_id;	
		$data['start'] = $start;	
		$data['end'] = $end;	
		$data['campus'] = $campus_real;	
		$data['peferred_lg'] = $peferred_lg;	
		
		

		
		$data['filter'] = $uid !== $data['current_user_id'] ;


		
		$modelProfiles = new ProfilesModel();
		$visitorsModel = new VisitorsModel();
		
 
		
		$action = $this->request->getPost('action');

		
			
		if($data['current_user_id']){	
		
			$data['userName'] =  $modelProfiles->getUserName($data['current_user_id']);			
			$data['userPicture'] =  $modelProfiles->db_m_getUserField($data['current_user_id'],'picture'); 
			
			$data['user_summary']['new'] =  $visitorsModel->getSummary($data['current_user_id'],1,0,0,0,0);
			$data['user_summary']['following'] =  $visitorsModel->getSummary($data['current_user_id'],2,0,0,0,0);
			$data['user_summary']['attended'] =  $visitorsModel->getSummary($data['current_user_id'],3,0,0,0,0);
			$data['user_summary']['joined'] =  $visitorsModel->getSummary($data['current_user_id'],4,0,0,0,0);			
			
		}	
		
		$data['userPicture'] = isset($data['userPicture'])&&$data['userPicture'] ? $data['userPicture'] : base_url().'/assets/images/default_user_profile.jpg';		
		
		
		$data['peferred_lg_obs'] = $visitorsModel->getLgObs($uid,$stage_id,$start,$end,$campus_real);
		
		$peferred_lg_for_query = $peferred_lg&&isset($data['peferred_lg_obs'][$peferred_lg]) ? $data['peferred_lg_obs'][$peferred_lg] : 0;
		$data['entries'] =  $visitorsModel->getEntries($uid,$stage_id,$start,$end,$campus_real,$peferred_lg_for_query);
		
		
		// foreach($data['entries'] as $item){
			// if( $item['preferred_language'] && array_search($item['preferred_language'],$data['peferred_lg_obs'])===false){
				// $data['peferred_lg_obs'][] = $item['preferred_language'];
			// }
		// }
		
		
		$data['caseOwnerObs'] = $this->modelPermission->getPermittedUsers('is_pastor');
		
		
		// var_dump($data['caseOwnerObs']); exit;
		
		$data['statusObs'] = $this->visitor_stage;		


		// var_dump($data['entries']); exit;



		$data['main_content'] =  view('nva_table',$data);	
		
		$data['footer'] = '<script>	
		
$( document ).on( "change", "#filter_case_owner", function() {
	
	url1 = "'.base_url('nva/table/filter/'.$stage_id.'/'.$start.'/'.$end.'/'.$campus.'/'.$peferred_lg).'";  
	val = $(this).val();
	
	if(val==""){return;};
	
	url2 = url1.replace("filter", val);	
	window.location.replace(url2);
	
});

$( document ).on( "change", "#filter_status", function() {
	
	url1 = "'.base_url('nva/table/'.$uid.'/filter/'.$start.'/'.$end.'/'.$campus.'/'.$peferred_lg).'";  
	val = $(this).val();
	
	url2 = url1.replace("filter", val);	
	window.location.replace(url2);
	
});

$( document ).on( "change", "#filter_peferred_lg", function() {
	
	url1 = "'.base_url('nva/table/'.$uid.'/'.$stage_id.'/'.$start.'/'.$end.'/'.$campus.'/filter').'";  
	val = $(this).val();
	
	url2 = url1.replace("filter", val);	
	window.location.replace(url2);
	
});

</script>';
		
		
		
		$data['campuses'] = $this->campuses; 
		
		echo view('nva',$data);			
		
		
	}





	public function areachart($stage_id=0,$campus=0)
	{
		
		$data['userCaps']= $this->userCaps;
		
		$data['userLg']= $this->lang;
		
		$data['session']= $this->session;
		
		$data['webConfig']= $this->webConfig;		
		
		$data['canonical']= base_url('nva/areachart');	
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
 
		
		$data['current_user_id'] = $this->session->get('mloggedin');	
		
		
			
		
	
		


		
	 
		$visitorsModel = new VisitorsModel();
		
		$s_date = date('Y-m',strtotime('-11 months'));
		$e_date = date('Y-m');
		
		
		
		$data['areachart_data'] =  $visitorsModel->countByMonth($s_date,$e_date,$stage_id,urldecode($campus));
		
		// var_dump($data['areachart_data']);	





		$chart_title = $this->request->getGet('title');

		
 
		
 
		
		
		
		echo view('nva_areachart',$data);			
		
		
	}


	public function piechart($campus=0,$zone='',$start=0,$end=0)
	{
		
		$data['userCaps']= $this->userCaps;
		
		$data['userLg']= $this->lang;
		
		$data['session']= $this->session;
		
		$data['webConfig']= $this->webConfig;		
		
		$data['canonical']= base_url('nva/piechart/'.$campus);	
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
	 
		
		$data['current_user_id'] = $this->session->get('mloggedin');	
		
		
			
		
	
		


		
	 
		$visitorsModel = new VisitorsModel();
		
		$action = $this->request->getPost('action');
		$piechart_title = $this->request->getGet('title');

		
			
 
		$piechart_data_new =  $visitorsModel->getSummary(0,1,$start,$end,urldecode($campus),urldecode($zone));
		$piechart_data_following =  $visitorsModel->getSummary(0,2,$start,$end,urldecode($campus),urldecode($zone));
		$piechart_data_attended =  $visitorsModel->getSummary(0,3,$start,$end,urldecode($campus),urldecode($zone));
		$piechart_data_joined =  $visitorsModel->getSummary(0,4,$start,$end,urldecode($campus),urldecode($zone));

		// $data['piechart_data_title'] = ['Stage','New', 'Following up','Attended WR','Joined LG']; 
		// $data['piechart_data_value'] = ['Counts',$piechart_data_new, $piechart_data_following,$piechart_data_attended,$piechart_data_joined]; 
		
		
		$data['piechart_data'] = [['Stage','Counts'],['New '.$piechart_data_new,$piechart_data_new], ['Following up '.$piechart_data_following,$piechart_data_following],['Attended WR '.$piechart_data_attended,$piechart_data_attended],['Joined LG '.$piechart_data_joined,$piechart_data_joined]]; 
		
		
		$data['piechart_option_title'] = $piechart_title; 
		
		
		
		echo view('nva_piechart',$data);			
		
		
	}









	
	
	public function import()
	{


		exit();


	
				
				
		$rule['milpitas']['general'] =  213; //Gideon Ip Kei Lee
		$rule['milpitas']['single'] =  142; //Justin Pan
		$rule['milpitas']['youth_students'] =  310; //Caleb William Hankin
		$rule['milpitas']['mandarin_speaking'] =  1185; //Amy En Mei Wang
		$rule['milpitas']['english_speaking'] =  1251000281; //Paul Sin
		// $rule['milpitas']['married_with_no_kids'] =  
		$rule['milpitas']['empty_nesters_and_beyond'] =  1251000281; //Paul Sin
		$rule['milpitas']['with_younger_kids'] =  1251000282; //Samuel Yung
		$rule['milpitas']['with_teen_kids'] =  213; //Gideon Ip Kei Lee	

		
		$rule['sfpeninsula']['general'] =  343; //Alan Chow


		
		$rule['sanleandro']['general'] = 350; //Abraham Chiu

		
		$rule['pleasanton']['general'] = 565; //Jacky Cheng 
		$rule['pleasanton']['youth_students'] =  132; //Zan Situ
		$rule['pleasanton']['mandarin_speaking'] =  107; //Ai-Ling Teng
		//1187 Ching Wa Lee
		//336 Anders Chun
		
		
		$rule['tracy']['general'] =  386; //Jacky Cheng
		$rule['tracy']['single'] = 336; //Anders Chun
		$rule['tracy']['youth_students'] =  132; //Zan Situ
		$rule['tracy']['mandarin_speaking'] =  1185; //Amy En Mei Wang
		// $rule['tracy']['english_speaking'] =  xxx ; //xxxx
		// $rule['tracy']['married_with_no_kids'] = xxx ; //xxxx
		// $rule['tracy']['empty_nesters_and_beyond'] = xxx ; //xxxx
		// $rule['tracy']['with_younger_kids'] = xxx ; //xxxx
		// $rule['tracy']['with_teen_kids'] = xxx ; //xxxx





			
		$format = array('date_visited','campus','name','email','phone','learnAbout','preferred_language','lifeGroup','greeter','welcomepackage_given','notes','lifeStatus'); 
		$r = $this->getFileData(WRITEPATH.'New Visitor Information.csv',$format);
		
		if($r){
			$visitorsModel = new VisitorsModel();
			
			$visitorsModel->where('id>0')->delete();

			
			
			foreach($r as $item){
				
				
				$item['date_visited'] = strtotime($item['date_visited']);
				
				$campus_slug = strtolower(preg_replace( '/[\W]/', '',$item['campus']));
				
				if( $item['lifeStatus'] && preg_match('/with teen kids/i',$item['lifeStatus']) && isset($this->rule[$campus_slug]['with_teen_kids']) ){
					
					$item['assigned_id'] = $rule[$campus_slug]['with_teen_kids'];					
					
				}elseif($item['lifeStatus'] && preg_match('/with younger kids/i',$item['lifeStatus']) && isset($rule[$campus_slug]['with_younger_kids'])){
					
					$item['assigned_id'] = $rule[$campus_slug]['with_younger_kids'];	
					
				}elseif($item['lifeStatus'] && preg_match('/empty Nesters and beyond/i',$item['lifeStatus']) && isset($rule[$campus_slug]['empty_nesters_and_beyond'])){
					
					$item['assigned_id'] = $rule[$campus_slug]['empty_nesters_and_beyond'];	
					
				}elseif($item['lifeStatus'] && preg_match('/Married with no kids yet/i',$item['lifeStatus']) && isset($rule[$campus_slug]['married_with_no_kids'])){
					
					$item['assigned_id'] = $rule[$campus_slug]['married_with_no_kids'];	
					
				}elseif($item['lifeStatus'] && preg_match('/English Speaking/i',$item['lifeStatus']) && isset($rule[$campus_slug]['english_speaking'])){
					
					$item['assigned_id'] = $rule[$campus_slug]['english_speaking'];	
					
				}elseif($item['lifeStatus'] && preg_match('/Mandarin Speaking/i',$item['lifeStatus']) && isset($rule[$campus_slug]['mandarin_speaking'])){
					
					$item['assigned_id'] = $rule[$campus_slug]['mandarin_speaking'];	
					
				}elseif($item['lifeStatus'] && preg_match('/Students/i',$item['lifeStatus']) && isset($rule[$campus_slug]['youth_students'])){
					
					$item['assigned_id'] = $rule[$campus_slug]['youth_students'];	
					
				}elseif($item['lifeStatus'] && preg_match('/Single/i',$item['lifeStatus']) && isset($rule[$campus_slug]['single'])){
					
					$item['assigned_id'] = $rule[$campus_slug]['single'];	
					
				}elseif(isset($rule[$campus_slug]['general'])){
					$item['assigned_id'] = $rule[$campus_slug]['general'];	
				}else{
					$item['assigned_id'] = $this->session->get('mloggedin'); //admin
				}
				
				






				
				
				
				$visitorsModel->insert($item);
				print_r($item);
			}			
		}
		


		exit;
 

	}
		
	public function new()
	{
		
		
		
	
		exit();
		
		
		
		
		
		$data['userCaps']= $this->userCaps;
		
		$data['userLg']= $this->lang;
		
		$data['session']= $this->session;
		
		$data['webConfig']= $this->webConfig;
		

		
		$modelMembers = new MembersModel();
		$modelCapabilities = new CapabilitiesModel();	
		$modelProfiles = new ProfilesModel();
		
		

		
		$action = $this->request->getPost('action');
		
		if($action){
			var_dump($_POST); exit;
		}
		

		$data['pageTitle'] = 'New Visitor';
		
		$data['canonical']= base_url('nva/new');	


		$data['isLogin'] = $this->session->get('mloggedin') ? $this->session->get('mloggedin') : 0;		
		if($data['isLogin']){			
			$data['userName'] =  $modelProfiles->getUserName($data['isLogin']);			
			$data['userPicture'] =  $modelProfiles->db_m_getUserField($data['isLogin'],'picture'); 
		}		
		
		
		
		$data['userPicture'] = isset($data['userPicture'])&&$data['userPicture'] ? $data['userPicture'] : base_url().'/assets/images/default_user_profile.jpg';		
		
		
		
		echo view('nva_new_visitor',$data);			
		
		
	}	


	public function settings()
	{
        $data = $this->data;
        $data['canonical'] = base_url('xAdmin/nva/settings');
        $this->webConfig->checkPermissionByDes('management', 1);
        $action = $this->request->getPost('action');

		if($action=='update'){
			

			$date_range = $this->request->getPost('date_range');
			$old_pastor = $this->request->getPost('old_pastor');
			$visitor_stage = $this->request->getPost('visitor_stage');
			$new_pastor = $this->request->getPost('new_pastor');

			$affectedRows = $this->visitorsModel->updateVisitor($date_range,$old_pastor,$visitor_stage,$new_pastor);

			return redirect()->to(base_url('nva/settings'))->with('success','Updated successfully. ('.$affectedRows.' rows affected)');


		}

		$data['old_pastors'] = $this->visitorsModel->getOldPastors();
		$data['new_pastors'] = $this->modelPermission->getPermittedUsers('is_pastor');	
		$data['visitor_stage'] = $this->visitor_stage;
		

        $data['pageTitle'] = 'NVA settings';
        $data['menugroup'] = '';
        $data['themeUrl'] = base_url('assets/theme-sb-admin-2');
        
        $data['page'] = 'nva_settings';
        $data['mainContent'] =  view($data['page'],$data);
        echo view('theme-sb-admin-2/layout', $data);
	}
	
	
	

private function emailNotifications($to_id,$to_name,$to_email,$vistor_id)
{
					
					 
					
					$subject = 'New Visitor Notification. (UID:'.$vistor_id.')';
					
					$message ='Dear '. $to_name.':<br /><br />';
					
					
					$message .='You\'ve been entrusted with a new visitor.<br />You can find their details here:<br />';
					
					$message .='<a href="'.base_url('nva/detail/'.$vistor_id).'">'.base_url('nva/detail/'.$vistor_id).'</a>';
					
					
				 

					$this->webConfig->sendtomandrill($subject,$message,$to_email);	


}
	
	
	

private function getFileData($file,$format)
{
    if (!is_file($file)) {
        exit('没有文件');
    }

    $handle = fopen($file, 'r');
    if (!$handle) {
        exit('读取文件失败');
    }
	
	$r = [];

    while (($data = fgetcsv($handle)) !== false) {
        // 下面这行代码可以解决中文字符乱码问题
        // $data[0] = iconv('gbk', 'utf-8', $data[0]);


        // data 为每行的数据，这里转换为一维数组
		$newItem =  array_combine($format, $data);
		//print_r($newItem);
		
		if($newItem){
			$r[] = $newItem;
		}else{
			 exit('error');
		}

    }

    fclose($handle);
	return $r;
}



	


	//--------------------------------------------------------------------

}
