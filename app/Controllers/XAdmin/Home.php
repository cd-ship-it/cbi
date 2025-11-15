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
use App\Models\VisitorsModel;

class Home extends BaseController
{
	
	
	public function index()
	{
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('dashboard_view','exit');	

		$action = $this->request->getPost('action');
		
		// Handle search action
		if($action == 'searchBaptism'){
			$query = $this->request->getPost('query');
			
			if(empty($query)){
				echo json_encode([]);
				exit();
			}
			
			$db = db_connect();
			$builder = $db->table('baptism');
			$builder->select('id, CONCAT(fName, " ", COALESCE(mName, ""), " ", lName) as name, email, inactive');
			
			// Only include records where dup IS NULL
			$builder->where('dup IS NULL');
			
			// Check if query is an email
			if(filter_var($query, FILTER_VALIDATE_EMAIL)){
				$builder->like('email', $query);
			}
			// Check if query is a number (phone)
			elseif(preg_match('/^[\d\s\-\(\)]+$/', $query)){
				$phoneQuery = preg_replace('/[\s\-\(\)]/', '', $query);
				$builder->groupStart()
					->like('mPhone', $phoneQuery)
					->orLike('hPhone', $phoneQuery)
					->groupEnd();
			}
			// Otherwise search name fields
			else {
				// Normalize query: trim and normalize spaces
				$query = trim($query);
				$query = preg_replace('/\s+/', ' ', $query); // Normalize multiple spaces to single space
				$words = preg_split('/\s+/', $query);
				
				$builder->groupStart();
				
				// If multiple words, try exact match on first name + last name
				if(count($words) >= 2){
					$escapedQuery = $db->escape($query);
					// Exact match on CONCAT(fName, " ", lName)
					$builder->where("CONCAT(fName, ' ', lName) = {$escapedQuery}", null, false);
					
					// Also try reverse order
					$reversedQuery = trim(end($words) . ' ' . $words[0]);
					$escapedReversedQuery = $db->escape($reversedQuery);
					$builder->orWhere("CONCAT(fName, ' ', lName) = {$escapedReversedQuery}", null, false);
					
					// Match first word as fName and last word as lName
					$firstWord = $words[0];
					$lastWord = end($words);
					if($firstWord !== $lastWord){
						$builder->groupStart()
							->like('fName', $firstWord)
							->like('lName', $lastWord)
							->groupEnd();
						
						// Also match in reverse order
						$builder->groupStart()
							->like('fName', $lastWord)
							->like('lName', $firstWord)
							->groupEnd();
					}
				} else {
					// Single word search - search across all name fields
					$builder->like('fName', $query)
						->orLike('mName', $query)
						->orLike('lName', $query)
						->orLike('cName', $query);
				}
				
				$builder->groupEnd();
			}
			
			// Order by exact match first, then alphabetically
			if(isset($escapedQuery)){
				$builder->orderBy("CONCAT(fName, ' ', lName) = {$escapedQuery} DESC", '', false);
				if(isset($escapedReversedQuery)){
					$builder->orderBy("CONCAT(fName, ' ', lName) = {$escapedReversedQuery} DESC", '', false);
				}
			}
			$builder->orderBy('fName', 'ASC');
			$builder->orderBy('lName', 'ASC');
			
			$builder->limit(20);
			$results = $builder->get()->getResultArray();
			
			// Add inactive status text to each result
			foreach($results as &$result){
				$result['inactiveStatus'] = getInactiveStatusText($result['inactive']);
			}
			
			echo json_encode($results);
			exit();
		}
		
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();
		$modelProfiles = new ProfilesModel();		
		$modelTo_do = new To_doModel();	
		$modelElearning = new ElearningModel();	
		$visitorsModel = new VisitorsModel();
		
		$toDoList = $modelTo_do->where(['bid'=>$this->logged_id])->orderBy('status ASC, end ASC')->findAll(); //,'end >'=>time()

		$students = $modelElearning->get_pending_approve_students(1,$this->logged_id); 

		$pastoralApproval = $modelProfiles->get_pastoralApprovalMembers($this->logged_id); 

		// Get new visitors per campus - allow user to set date range via GET params, check session, default to 3 weeks
		$startDateParam = $this->request->getGet('visitor_start_date');
		$endDateParam = $this->request->getGet('visitor_end_date');
		
		// Check session first, then GET params, then default
		if($startDateParam && $endDateParam){
			// Validate dates from GET params
			$startDate = date('Y-m-d', strtotime($startDateParam));
			$endDate = date('Y-m-d', strtotime($endDateParam));
			
			// Ensure start is before end
			if(strtotime($startDate) > strtotime($endDate)){
				$temp = $startDate;
				$startDate = $endDate;
				$endDate = $temp;
			}
			
			// Save to session
			$this->session->set('visitor_start_date', $startDate);
			$this->session->set('visitor_end_date', $endDate);
		} elseif($this->session->get('visitor_start_date') && $this->session->get('visitor_end_date')){
			// Use dates from session
			$startDate = $this->session->get('visitor_start_date');
			$endDate = $this->session->get('visitor_end_date');
		} else {
			// Default to last 3 weeks
			$startDate = date('Y-m-d', strtotime('-3 weeks'));
			$endDate = date('Y-m-d');
			
			// Save default to session
			$this->session->set('visitor_start_date', $startDate);
			$this->session->set('visitor_end_date', $endDate);
		}
		
		$data['newVisitorsPerCampus'] = $visitorsModel->getNewVisitorsPerCampus($startDate, $endDate);
		$data['newVisitorsDateRange'] = [
			'start' => $startDate,
			'end' => $endDate,
			'startFormatted' => date('M d, Y', strtotime($startDate)),
			'endFormatted' => date('M d, Y', strtotime($endDate)),
			'startInput' => date('m/d/Y', strtotime($startDate)),
			'endInput' => date('m/d/Y', strtotime($endDate))
		];
	 
		
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
