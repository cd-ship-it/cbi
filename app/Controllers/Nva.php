<?php namespace App\Controllers;


use App\Models\ElearningModel;
use App\Models\WebmetaModel;
use App\Models\ProfilesModel;
use App\Models\ClassesModel;
use App\Models\MembersModel;
use App\Models\CapabilitiesModel;


use App\Models\VisitorsModel;
use App\Models\CampusModel;
use App\Models\VisitorLifestatusPastorMapModel;

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
		
		// Use session dates if URL params are 0 or not provided
		if(($start == 0 || $start == '0') && ($end == 0 || $end == '0')){
			$sessionStart = $this->session->get('visitor_start_date');
			$sessionEnd = $this->session->get('visitor_end_date');
			if($sessionStart && $sessionEnd){
				$start = $sessionStart;
				$end = $sessionEnd;
			}
		}
		
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
		
		// Format date range for display
		if($start && $end){
			$data['dateRange'] = [
				'start' => $start,
				'end' => $end,
				'startFormatted' => date('M d, Y', strtotime($start)),
				'endFormatted' => date('M d, Y', strtotime($end)),
				'startInput' => date('m/d/Y', strtotime($start)),
				'endInput' => date('m/d/Y', strtotime($end))
			];
		} else {
			$data['dateRange'] = null;
		}	
		
		

		
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
		
		$data['footer'] = '<script type="text/javascript" src="'.base_url('assets/rome/rome.min.js').'"></script>
<script>	
		
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

// Rome date picker for date range
$(document).ready(function() {
	if(typeof rome !== "undefined"){
		var startDatePicker = null;
		var endDatePicker = null;
		var dateChangeTimer = null;
		
		// Initialize start date picker
		var startEl = document.getElementById("date_range_start");
		if(startEl) {
			startDatePicker = rome(startEl, {
				time: false,
				inputFormat: "MM/DD/YYYY",
				outputFormat: "MM/DD/YYYY"
			});
			
			startDatePicker.on("ready", function() {
				startDatePicker.dateValidator(function(date) {
					if(endDatePicker && endDatePicker.getDate()){
						return date <= endDatePicker.getDate();
					}
					return true;
				});
			});
			
			startDatePicker.on("data", function(value) {
				if(value && dateChangeTimer) clearTimeout(dateChangeTimer);
				if(value) {
					dateChangeTimer = setTimeout(function() {
						updateDateRange();
					}, 500);
				}
			});
		}
		
		// Initialize end date picker
		var endEl = document.getElementById("date_range_end");
		if(endEl) {
			endDatePicker = rome(endEl, {
				time: false,
				inputFormat: "MM/DD/YYYY",
				outputFormat: "MM/DD/YYYY"
			});
			
			endDatePicker.on("ready", function() {
				endDatePicker.dateValidator(function(date) {
					if(startDatePicker && startDatePicker.getDate()){
						return date >= startDatePicker.getDate();
					}
					return true;
				});
			});
			
			endDatePicker.on("data", function(value) {
				if(value && dateChangeTimer) clearTimeout(dateChangeTimer);
				if(value) {
					dateChangeTimer = setTimeout(function() {
						updateDateRange();
					}, 500);
				}
			});
		}
		
		// Function to update URL with new date range
		function updateDateRange() {
			var startDate = $("#date_range_start").val();
			var endDate = $("#date_range_end").val();
			
			if(!startDate || !endDate) {
				return; // Don\'t update if both dates aren\'t set
			}
			
			// Convert MM/DD/YYYY to YYYY-MM-DD
			function convertToUrlFormat(dateStr) {
				if(!dateStr) return "0";
				var parts = dateStr.split("/");
				if(parts.length === 3) {
					return parts[2] + "-" + parts[0] + "-" + parts[1];
				}
				return "0";
			}
			
			var startUrl = convertToUrlFormat(startDate);
			var endUrl = convertToUrlFormat(endDate);
			
			// Build new URL with updated dates
			var baseUrl = "'.base_url('nva/table/'.$uid.'/'.$stage_id).'";
			var newUrl = baseUrl + "/" + startUrl + "/" + endUrl + "/'.$campus.'/'.$peferred_lg.'";
			
			// Update URL
			window.location.replace(newUrl);
		}
	}
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



	


	public function campus()
	{
		$data = $this->data;
		
		$data['canonical'] = base_url('cbi/nva');
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['dashboard_view'],'exit');
		
		$modelCampus = new CampusModel();
		$modelProfiles = new ProfilesModel();
		
		$data['current_user_id'] = $this->session->get('mloggedin') ? $this->session->get('mloggedin') : 0;
		if($data['current_user_id']){
			$data['userName'] = $modelProfiles->getUserName($data['current_user_id']);
			$data['userPicture'] = $modelProfiles->db_m_getUserField($data['current_user_id'],'picture');
		}
		$data['userPicture'] = isset($data['userPicture']) && $data['userPicture'] ? $data['userPicture'] : base_url().'/assets/images/default_user_profile.jpg';
		
		$action = $this->request->getPost('action');
		
		// Handle search for campus pastor
		if($action == 'searchPastor'){
			$keywords = $this->request->getPost('query');
			
			$db = db_connect();
			$builder = $db->table('baptism');
			$builder->select('id, CONCAT(fName, " ", lName) as name');
			$builder->where('inactive', 3);
			$builder->like('CONCAT(fName, " ", lName)', '%' . $keywords . '%');
			$builder->limit(10);
			
			$results = $builder->get()->getResultArray();
			
			echo json_encode($results);
			exit();
		}
		
		// Handle CRUD operations
		if($action == 'update'){
			$campus_id = $this->request->getPost('campus_id');
			$campus_name = $this->request->getPost('campus_name');
			$campus_pastor = $this->request->getPost('campus_pastor');
			
			$data_update = [
				'campus' => $campus_name,
				'campus_pastor' => $campus_pastor
			];
			
			$r = 'error';
			
			if($campus_id){
				if($modelCampus->update($campus_id, $data_update)){
					$r = 'ok';
				}
			} else {
				if($modelCampus->insert($data_update)){
					$r = 'ok';
				}
			}
			
			echo $r;
			exit();
		} elseif($action == 'remove'){
			$campus_id = $this->request->getPost('campus_id');
			
			$r = 'error';
			if($modelCampus->delete($campus_id)){
				$r = 'ok';
			}
			
			echo $r;
			exit();
		}
		
		// Get all campuses
		$data['campusList'] = $modelCampus->findAll();
		$data['pageTitle'] = 'Edit Campus Pastors';
		$data['adminUrl'] = base_url('cbi/nva');
		
		// Get all pastors for dropdown (inactive = 3)
		$db = db_connect();
		$builder = $db->table('baptism');
		$builder->select('id, CONCAT(fName, " ", lName) as name');
		$builder->where('inactive', 3);
		$builder->orderBy('fName', 'ASC');
		$data['pastors'] = $builder->get()->getResultArray();
		
		// Use nva layout - keep campuses for sidebar
		$data['campuses'] = $this->campuses;
		$data['main_content'] = view('nva_campus', $data);
		
		echo view('nva', $data);
	}

	public function caseOwner()
	{
		$data = $this->data;
		
		$data['canonical'] = base_url('cbi/nva/caseowner');
		
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes(['dashboard_view'],'exit');
		
		$modelMap = new VisitorLifestatusPastorMapModel();
		$modelCampus = new CampusModel();
		$modelProfiles = new ProfilesModel();
		$visitorsModel = new VisitorsModel();
		
		$data['current_user_id'] = $this->session->get('mloggedin') ? $this->session->get('mloggedin') : 0;
		if($data['current_user_id']){
			$data['userName'] = $modelProfiles->getUserName($data['current_user_id']);
			$data['userPicture'] = $modelProfiles->db_m_getUserField($data['current_user_id'],'picture');
		}
		$data['userPicture'] = isset($data['userPicture']) && $data['userPicture'] ? $data['userPicture'] : base_url().'/assets/images/default_user_profile.jpg';
		
		$action = $this->request->getPost('action');
		
		// Handle CRUD operations
		if($action == 'update'){
			$map_id = $this->request->getPost('map_id');
			$campus_id = $this->request->getPost('campus_id');
			$lifestatus_id = $this->request->getPost('lifestatus_id');
			$pastor_id = $this->request->getPost('pastor_id');
			
			$r = 'error';
			
			if($map_id){
				// Parse composite key: campus_id_lifestatus_id_pastor_id
				$parts = explode('_', $map_id);
				if(count($parts) == 3){
					$old_campus_id = $parts[0];
					$old_lifestatus_id = $parts[1];
					$old_pastor_id = $parts[2];
					
					// Delete old record and insert new one (since table may not have id column)
					$db = db_connect();
					$builder = $db->table('visitor_lifestatus_pastor_map');
					$builder->where('campus_id', $old_campus_id);
					$builder->where('lifestatus_id', $old_lifestatus_id);
					$builder->where('pastor_id', $old_pastor_id);
					$builder->delete();
					
					// Insert new record
					$data_insert = [
						'campus_id' => $campus_id,
						'lifestatus_id' => $lifestatus_id,
						'pastor_id' => $pastor_id
					];
					if($builder->insert($data_insert)){
						$r = 'ok';
					}
				}
			} else {
				// Check if record already exists
				$db = db_connect();
				$builder = $db->table('visitor_lifestatus_pastor_map');
				$builder->where('campus_id', $campus_id);
				$builder->where('lifestatus_id', $lifestatus_id);
				$builder->where('pastor_id', $pastor_id);
				$exists = $builder->get()->getRowArray();
				
				if(!$exists){
					$data_insert = [
						'campus_id' => $campus_id,
						'lifestatus_id' => $lifestatus_id,
						'pastor_id' => $pastor_id
					];
					if($builder->insert($data_insert)){
						$r = 'ok';
					}
				} else {
					$r = 'ok'; // Already exists
				}
			}
			
			echo $r;
			exit();
		} elseif($action == 'remove'){
			$map_id = $this->request->getPost('map_id');
			
			$r = 'error';
			// Parse composite key: campus_id_lifestatus_id_pastor_id
			$parts = explode('_', $map_id);
			if(count($parts) == 3){
				$db = db_connect();
				$builder = $db->table('visitor_lifestatus_pastor_map');
				$builder->where('campus_id', $parts[0]);
				$builder->where('lifestatus_id', $parts[1]);
				$builder->where('pastor_id', $parts[2]);
				if($builder->delete()){
					$r = 'ok';
				}
			}
			
			echo $r;
			exit();
		}
		
		// Get all mappings with joined data
		$db = db_connect();
		// Use raw SQL query to handle the table structure properly
		$sql = "SELECT 
			m.lifestatus_id, 
			m.campus_id, 
			m.pastor_id,
			c.campus, 
			c.id as campus_id_val, 
			CONCAT(b.fName, ' ', b.lName) as pastor_name, 
			b.id as pastor_id_val, 
			v.life_status, 
			v.id as lifestatus_id_val
		FROM visitor_lifestatus_pastor_map m
		LEFT JOIN visitor_life_status v ON m.lifestatus_id = v.id
		LEFT JOIN campuses c ON m.campus_id = c.id
		LEFT JOIN baptism b ON m.pastor_id = b.id
		ORDER BY c.campus ASC, v.life_status ASC";
		
		$query = $db->query($sql);
		$mappings = $query->getResultArray();
		
		// Add a composite key as 'id' for the view to use
		foreach($mappings as &$mapping){
			$mapping['id'] = $mapping['campus_id'] . '_' . $mapping['lifestatus_id'] . '_' . $mapping['pastor_id'];
			$mapping['campus_id'] = $mapping['campus_id_val'] ?? $mapping['campus_id'];
			$mapping['pastor_id'] = $mapping['pastor_id_val'] ?? $mapping['pastor_id'];
			$mapping['lifestatus_id'] = $mapping['lifestatus_id_val'] ?? $mapping['lifestatus_id'];
		}
		$data['mappings'] = $mappings;
		
		// Get all campuses for dropdown
		$data['campusList'] = $modelCampus->findAll();
		
		// Get all life statuses for dropdown
		$data['lifeStatuses'] = $visitorsModel->get_visitor_life_status();
		
		// Get all pastors for dropdown (inactive = 3)
		$builder = $db->table('baptism');
		$builder->select('id, CONCAT(fName, " ", lName) as name');
		$builder->where('inactive', 3);
		$builder->orderBy('fName', 'ASC');
		$data['pastors'] = $builder->get()->getResultArray();
		
		$data['pageTitle'] = 'Edit default case owner';
		$data['adminUrl'] = base_url('cbi/nva/caseowner');
		
		// Use nva layout - keep campuses for sidebar
		$data['campuses'] = $this->campuses;
		$data['main_content'] = view('nva_caseowner', $data);
		
		echo view('nva', $data);
	}

	//--------------------------------------------------------------------

}
