<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;

use App\Models\ClassesModel;
use App\Models\ProfilesModel;
use App\Models\MinistryModel;

class Search extends BaseController
{
	// Configurable page limit for pagination
	private $pageLimit = 40;
	
	
	public function index($mode=0,$filter='')
	{
		
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/search');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$this->webConfig->checkPermissionByDes('user_view',1);			

		$action = $this->request->getPost('action');		
		



		
		$modelClasses = new ClassesModel();		
		$modelProfilesModel = new ProfilesModel();
		
		$inputName='';
		$inputtheClass='';
		$inputcStatus='';
		$inputmStatus='';		
		$is_over18=1;		
		
		
		$data['returnHtml'] = '';
		 	
		
		if($action == 'search'){  				
		

	
				$condition = [];
				if(preg_match_all('#\S+#i',$_POST['query'],$keys)){
					
					
					if(count($keys[0])>1){
						foreach($keys[0] as $k){
							$condition[$k] =  "concat(a.fName,a.mName,a.lName) like '%".$k."%'";
						}	
					}else{
						$keyword = $keys[0][0];
						$condition[] =  "(concat(a.fName,a.mName,a.lName) like '%".$keyword."%' or a.email REGEXP '".$keyword."')";
					}

					
				}
				
				
				$is_over18=$this->request->getPost('over18'); 
				
				
				if($is_over18==1){
					$condition[] =  "(TIMESTAMPDIFF(YEAR,DATE_ADD(FROM_UNIXTIME(0), INTERVAL (a.birthDate - 3600*8) SECOND),CURDATE()) >= 18 or a.birthDate is null)";
				}
				
				
			if($_POST['theClass']){
				
				if($_POST['cStatus']=='completed'){
					
					$fix = ($_POST['theClass']=='CBIB40'||$_POST['theClass']=='CBIE01')? 'HAVING (count(b.baptism_id)) > 1' : '';
					
					$condition[] =  "a.id in (SELECT b.baptism_id FROM `curriculum` a JOIN curriculum_logs b on a.id = b.curriculum_id WHERE a.`code` = '{$_POST['theClass']}' and b.type = 'finish' and b.value > 74 GROUP by b.baptism_id $fix)";
					
				}elseif($_POST['cStatus']=='incompleted'){
					$ctime = time();
					$condition[] =  "a.id in (SELECT b.baptism_id FROM `curriculum` a JOIN curriculum_logs b on a.id = b.curriculum_id WHERE a.`code` = '{$_POST['theClass']}' and b.type = 'finish' and b.value != '100' and a.end > {$ctime} GROUP by b.baptism_id)";
				}else{
					$condition[] =  "a.id not in (SELECT b.baptism_id FROM `curriculum` a JOIN curriculum_logs b on a.id = b.curriculum_id WHERE a.`code` = '{$_POST['theClass']}' GROUP by b.baptism_id)";
				}
				
				if(in_array($_POST['theClass'],['CBIF101','CBIF000','CBIF100','CBIF201','CBIF301','CBIF401'])){
					$mode = 0; 
				}else{
					$mode = 4; 
				}
						
			}elseif($_POST['mStatus']){
				
				$mode = 1; 
				
				if($_POST['mStatus']=='member'){ //AND (m.status != 0 or m.status is null) 
					$condition[] = "a.`inactive` = 3";
				}elseif($_POST['mStatus']=='pending'){
					$condition[] = "a.`inactive` = 6";
					$mode = 0; 
				}elseif($_POST['mStatus']=='premember'){
					$condition[] = "a.`inactive` = 4";
					$condition[] =  "a.id not in (SELECT b.baptism_id FROM `curriculum` a JOIN curriculum_logs b on a.id = b.curriculum_id WHERE a.`code` = 'CBIF101' and b.type = 'finish' and b.value > 74 GROUP by b.baptism_id)";
					$mode = 0; 
				}elseif($_POST['mStatus']=='exmember'){
					$condition[] = "a.`inactive` = 5";
				}elseif($_POST['mStatus']=='inactive'){
					$condition[] = "a.`inactive` = 1";
				}elseif($_POST['mStatus']=='registered'){
					$condition[] = "m.status is not null";
				}elseif($_POST['mStatus']=='unregistered'){
					$condition[] = "m.status is null";
				}else{
					$condition[] = "a.`inactive` = 2";
				}
				
				
				
			}	
				
			$ingClassesIds = $modelClasses->getNowClassIds(); 
			
			// var_dump($condition);
			
			if($_POST['displayAll']&&$_POST['displayAll']==1){
				
				$results = $modelClasses->dbSearchBaptism2($mode);   
				$returnHtml = $this->resultsToHtml($mode,$results,$ingClassesIds,'');
				
			}elseif($condition){
				
				$conditionStr = implode(' and ',$condition);
				$results = $modelClasses->dbSearchBaptism2($mode,$conditionStr);
				$returnHtml = $this->resultsToHtml($mode,$results,$ingClassesIds,$conditionStr);
				
			}else{
				$returnHtml = '<p id="notMarch">Please enter at least one item.</p>';
			}
			
			echo $returnHtml;
			

			
			exit();
			
		}elseif($action == 'singelUpdate'){
	

			$mid = $_POST['mid'];
			$cc = $_POST['name'];
			$value = $_POST['val'];
			
			if(in_array($cc,['birthDate','baptizedDate','membershipDate'])){
				$value = strtotime($value);
				if($value===false){
					echo 'Error';
					exit();
				}
			}elseif(in_array($cc,['hPhone','mPhone'])){
				$value = preg_replace('#[^\d]#i','',$value);
				if(strlen($value)==0){
					echo 'Error';
					exit();
				}
			}else{
				$value = addslashes($value);
			}
			
			

			$modifiedBy = $modelProfilesModel->getUserName($this->logged_id,1);	
			
			$r = $modelProfilesModel->userUpdate($mid, array($cc => $value),$modifiedBy);
			
			if($r){
				echo 'Updated';
	
				
			}else{
				echo 'Error';
			}
			
			exit();
			
		}elseif($action == 'shapeSearch'){

	
			$condition = [];


				
			if(isset($_POST['myS'])&&$_POST['myS']){
				
				foreach($_POST['myS'] as $item){
						$condition[] = 's.`myS` REGEXP "[^0-9]'.$item.'[^0-9]"';
					}
			}
			
			if(isset($_POST['myH'])&&$_POST['myH']){
				
				foreach($_POST['myH'] as $item){
						$condition[] = 's.`shapeP2` REGEXP "\:[^0-9]'.$item.'[^0-9]"';
					}
			}
			
			if(isset($_POST['myA'])&&$_POST['myA']){
				
				foreach($_POST['myA'] as $item){
						$condition[] = 's.`shapeP3` REGEXP "\:[^0-9]'.$item.'[^0-9]"';
					}
			}
			
			if(isset($_POST['myC'])&&$_POST['myC']){
				
				foreach($_POST['myC'] as $item){
						$condition[] =  's.`shapeP6` REGEXP "[^0-9]'.$item.'[^0-9]"';
					}
			}
			
			if(isset($_POST['site'])&&$_POST['site']){
				
				$condition[] =  "a.`site` IN ('".(implode("','",$_POST['site']))."')";
			}	
				
			$mode = 5; 
						
		
			//var_dump($condition);
				
			$ingClassesIds = $modelClasses->getNowClassIds(); 
			
			
			
			if($condition){
				
													   
	
				$conditionStr = implode(' and ',$condition);
	
																														 
		 
	
	
				$results = $modelClasses->dbSearchBaptism2($mode,$conditionStr);
				$returnHtml = $this->resultsToHtml($mode,$results,$ingClassesIds,$conditionStr);
				
			}else{
				$returnHtml = '<p id="notMarch">Did not match any documents.</p>';
			}
			
			
			echo $returnHtml;

			exit();


		

			
		}elseif($action == 'servingSearch'){

			$modelMinistry = new MinistryModel();	
			
			$key = $this->request->getPost('query');
			$r1 = $modelMinistry->searchMinistry($key);	
			
			$condition = [];
			
			if($r1){
				foreach($r1 as $row){
					$condition[] = 's.`ministry` REGEXP "[^0-9]'.$row['id'].'[^0-9]"';
				}
			}
			
	
				
			$mode = 5; 
						
		
			 
				
			$ingClassesIds = $modelClasses->getNowClassIds(); 
			
			
			
			if($condition){
				
				$conditionStr = '('.implode(' or ',$condition).')';
				
			if(isset($_POST['site'])&&$_POST['site']){
				
				$conditionStr .=  " AND a.`site` IN ('".(implode("','",$_POST['site']))."')";
			}					
				
				
				$results = $modelClasses->dbSearchBaptism2($mode,$conditionStr);
				$returnHtml = $this->resultsToHtml($mode,$results,$ingClassesIds,$conditionStr);
				
			}else{
				$returnHtml = '<p id="notMarch">Did not match any documents.</p>';
			}
			
			
			echo $returnHtml;

			exit();


		

			
		}
				
		
		
		
		if($filter){
			 
			$conditionStr = $filter !='all'?rawurldecode( $this->webConfig->decode($filter)):'';
			
			
			
			
			$inputName = preg_match_all('#\%(.*)\%#Ui',$conditionStr,$match)?implode(' ',$match[1]):'';
			
			$is_over18 =  stripos($conditionStr,'FROM_UNIXTIME') !== false?1:0;
				
				
				if(stripos($conditionStr,'`inactive` = 3') !== false){
					$inputmStatus = 'member';
				}elseif(stripos($conditionStr,'`inactive` = 2') !== false){
					$inputmStatus = 'guest';
				}elseif(stripos($conditionStr,'`inactive` = 4') !== false && stripos($conditionStr,'CBIF101') !== false){
					$inputmStatus = 'premember';
				}elseif(stripos($conditionStr,'`inactive` = 6' ) !== false){					
					$inputmStatus = 'pending';					
				}elseif(stripos($conditionStr,'`inactive` = 5') !== false){
					$inputmStatus = 'exmember';
				}elseif(stripos($conditionStr,'`inactive` = 1') !== false){
					$inputmStatus = 'inactive';
				}elseif(stripos($conditionStr,'m.status is not null') !== false){
					$inputmStatus = 'registered';
				}elseif(stripos($conditionStr,'m.status is null') !== false){
					$inputmStatus = 'unregistered';
				}elseif(preg_match('#code\` \= \'([a-z0-9]+)\'#i',$conditionStr,$match)){
					
					$inputtheClass = $match[1];
					if(preg_match('#b\.value \= \'100\'#i',$conditionStr,$match)){
						$inputcStatus = 'completed';
					}elseif(preg_match('#b\.value \!\= \'100\'#i',$conditionStr,$match)){
						$inputcStatus = 'incompleted';
					}else{
						$inputcStatus = 'unjoined';
					}					
				}
				
		 
			
			$ingClassesIds = $modelClasses->getNowClassIds(); 
			$results =  $modelClasses->dbSearchBaptism2( $mode,$conditionStr );  
			$data['returnHtml'] = $this->resultsToHtml($mode,$results,$ingClassesIds,$conditionStr);
			
			

			
			
		}else{
			// Default: Show latest records from baptism table ordered by id desc with pagination
			// Get page number from query parameter, default to 1
			$page = (int)($this->request->getGet('page') ?: 1);
			
			// Get sort parameters from query string
			$sortColumn = $this->request->getGet('sort') ?: 'id';
			$sortDirection = $this->request->getGet('dir') ?: 'DESC';
			
			// Get paginated results from model
			$paginationData = $modelClasses->getPaginatedBaptismList($page, $this->pageLimit, $sortColumn, $sortDirection);
			
			// Use custom default display with specific columns and pagination
			$data['returnHtml'] = $this->defaultHtml($paginationData['results'], $paginationData['currentPage'], $paginationData['totalPages'], $paginationData['totalRecords'], $paginationData['sortColumn'], $paginationData['sortDirection']);
		}
		
		
			$data['is_over18'] = $is_over18;
			$data['inputName'] = $inputName;
			$data['inputtheClass'] = $inputtheClass;
			$data['inputcStatus'] = $inputcStatus;
			$data['inputmStatus'] = $inputmStatus;		
				
			$data['pageTitle'] = 'Crosspoint CBI';
			$data['curriculumCodes'] = $this->webConfig->curriculumCodes[$this->lang];
			$data['sites'] = $this->webConfig->sites;
			
			
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['mainContent'] = view('theme-sb-admin-2/searchUsers',$data);
			
 
			
			echo view('theme-sb-admin-2/layout',$data);				

				
		}
			
		

		
		
		
	
	
	
	




		


private function resultsToHtml($mode,$results,$ingClassesIds,$conditionStr){ 
	$conditionStr;
	$webConfig = new \Config\WebConfig();
	
	$fter = $conditionStr?$webConfig->encode(rawurlencode($conditionStr)):'all';
	$links = $this->getLinks(count($results),$mode,$fter);

	if($mode==0){
		
		return $this->mode0html($links,$results,$ingClassesIds);
		
	}elseif($mode==4){
		
		return $this->mode0html($links,$results,$ingClassesIds,2);
		
	}elseif($mode==1){
		
		return $this->mode1html($links,$results);
		
	}elseif($mode==2||$mode==5){
		
		return $this->mode2html($links,$results);
		
	}

}

private function getLinks($count,$mode,$fter){
	
		
		$html = '';
			
		$html .= '<p class="x-results">';
		$html .= '<span>'.$count.' results were found</span>';
		
		if($mode==5){
			$html .= '</p>';			
			return $html;			
		}
		
		$webConfig = new \Config\WebConfig();
		
		if($webConfig->checkPermissionByDes(['is_admin'])){
			$html .= ' | <a  href="'. base_url('xAdmin/export/'.$mode.'/'.$fter) .'">Export</a>';
		}

		
		$html .= '<a class="'.($mode=='0'?'current':'').' float-right" href="'.($mode!='0'? base_url('xAdmin/search/0/'.$fter):'javascript:void(0)').'">Basic Courses</a>';	
		
		
		$html .= '<a class="'.($mode=='4'?'current':'').' float-right" href="'.($mode!='4'?base_url('xAdmin/search/4/'.$fter):'javascript:void(0)').'">Biblical Courses</a>';	

		$html .= '<a class="'.($mode=='1'?'current':'').' float-right" href="'.($mode!='1'?base_url('xAdmin/search/1/'.$fter):'javascript:void(0)').'">Profile</a>';
		
		
		$html .= '<a class="'.($mode=='2'?'current':'').' float-right" href="'.($mode!='2'?base_url('xAdmin/search/2/'.$fter):'javascript:void(0)').'">Contact</a>';
		
		$html .= '</p>';
		
		return $html;
}


private function mode1html($links,$results){ 
	
	$html='';
	
	
	if($results){
		
		$html .= $links;
		
		$html .= '<table class="table sortable" id="results"><thead>';
	
		$columns = ['Name','Email',	'Gender',	'Marital',	'Over 18','Occupation','Date of your baptism','Site','Membership Date'];
		
		$html .=  '<tr class="title">';  //'Name of the church you were baptized', 'Name of your previous church', 'Family member',
		
		foreach($columns as $c){

			$html .=  '<th class="c'.md5('mit'.$c).'">'.$c.'</th>';		
		}
		$html .=  '</tr> </thead> <tbody>';



		foreach($results as $item){
			
			$icon = '';
			if($item['status']=== '1'){
				$icon .= ' signin';
			}else{
				$icon .= ' signin';
			} 	
			
			$html .=  '<tr class="list '.$icon.'">';
			$id = $item['id'];
			
			$html .=  '<td class=""><a class="profile" href="'.base_url('xAdmin/baptist/'.$id).'">'.$item['fName'].' '.$item['mName'].' '.$item['lName'].'</a></td>';
			
			$html .=  '<td data-lable="email" data-mid="'.$id.'" class="tditem">'.$item['email'].'</td>';

			$html .=  '<td data-lable="gender" data-mid="'.$id.'" class="tditem genderInput">'.$item['gender'].'</td>';
			$html .=  '<td data-lable="marital" data-mid="'.$id.'" class="tditem maritalInput">'.$item['marital'].'</td>';
			
				// $html .=  '<td data-lable="birthDate" data-mid="'.$id.'" data-rval="'.($item['birthDate']?$item['birthDate']:-99999999999).'" class="tditem dateInput">'.($item['birthDate']!==NULL?($item['birthDate']==0?'???':date("m/d/Y",$item['birthDate'])):'').'</td>';
			
			
			if($item['age'] === NULL){
				$over18 = '';
			}elseif($item['age']>=18){
				$over18 = 'Yes';
			}else{
				$over18 = 'No';
			}
				
			
			
			
			$html .=  '<td >'. $over18 .'</td>';
			
			$html .=  '<td data-lable="occupation" data-mid="'.$id.'" class="tditem">'.$item['occupation'].'</td>';
			// $html .=  '<td data-lable="nocb" data-mid="'.$id.'" class="tditem">'.$item['nocb'].'</td>';
				$html .=  '<td data-lable="baptizedDate" data-mid="'.$id.'" data-rval="'.($item['baptizedDate']?$item['baptizedDate']:-99999999999).'" class="tditem dateInput">'.($item['baptizedDate']!==NULL?($item['baptizedDate']==0?'???':date("m/d/Y",$item['baptizedDate'])):'').'</td>';			
			
			// $html .=  '<td data-lable="nopc" data-mid="'.$id.'" class="tditem">'.$item['nopc'].'</td>';
			
			// $html .=  '<td data-lable="family" data-mid="'.$id.'" class="tditem">'.$item['family'].'</td>';
				
				
			$html .=  '<td data-lable="site" data-mid="'.$id.'" class="tditem siteInput">'.$item['site'].'</td>';
			
				$html .=  '<td data-lable="membershipDate" data-mid="'.$id.'" data-rval="'.($item['membershipDate']?$item['membershipDate']:-99999999999).'" class="tditem dateInput">'.($item['membershipDate']!==NULL?($item['membershipDate']==0?'???':date("m/d/Y",$item['membershipDate'])):'').'</td>';
			$html .=  '</tr>';
		}
		
		$html .= ' </tbody></table>';

	}else{
		
		$html = '<p id="notMarch">Did not match any documents.</p>';
	}
	
	return $html;


}


private function mode2html($links,$results){ 
	
	$html='';
	
	if($results){
		
		$html .= $links;
		
		
		$html .= '<table class="table sortable"  id="results"> <thead>';
	
		$columns = ['Name',	 'Home Address','City','Zip Code',	'Home Phone',	'Mobile Phone',	'Email'];
		
		$html .=  '<tr class="title">';
		
		foreach($columns as $c){

			$html .=  '<th class="c'.md5('mit'.$c).'">'.$c.'</th>';		
		}
		$html .=  '</tr> </thead><tbody>';



		foreach($results as $item){
			
			$icon = '';
			if($item['status']=== '1'){
				$icon .= ' signin';
			}else{
				$icon .= ' unsign';
			} 				
			
			$html .=  '<tr class="list '.$icon.'">';
			$id = $item['id'];
			
			$html .=  '<td class=""><a class="profile" href="'.base_url('xAdmin/baptist/'.$id).'">'.$item['fName'].' '.$item['mName'].' '.$item['lName'].'</a></td>';
			
			
			$html .=  '<td data-lable="homeAddress" data-mid="'.$id.'" class="tditem">'.$item['homeAddress'].'</td>';
			$html .=  '<td data-lable="city" data-mid="'.$id.'" class="tditem">'.$item['city'].'</td>';
			$html .=  '<td data-lable="zCode" data-mid="'.$id.'" class="tditem">'.$item['zCode'].'</td>';
				$html .=  '<td data-lable="hPhone" data-mid="'.$id.'" class="tditem">'.($item['hPhone']?$item['hPhone']:'').'</td>';
				$html .=  '<td data-lable="mPhone" data-mid="'.$id.'" class="tditem">'.($item['mPhone']?$item['mPhone']:'').'</td>';
			$html .=  '<td data-lable="email" data-mid="'.$id.'" class="tditem">'.$item['email'].'</td>';
			
			$html .=  '</tr>';
		}
		
		$html .= '</tbody></table>';

	}else{
		
		$html = '<p id="notMarch">Did not match any documents.</p>';
	}
	
	return $html;


}

private function defaultHtml($results, $currentPage = 1, $totalPages = 1, $totalRecords = 0, $sortColumn = 'id', $sortDirection = 'DESC'){
	helper('status');
	$html='';
	
	// Map column display names to sort keys
	$columnSortMap = [
		'Name' => 'name',
		'Email' => 'email',
		'Membership Date' => 'membershipDate',
		'Site' => 'site',
		'Membership Status' => 'inactive',
		'Mailchimp Status' => 'onMailchimp'
	];
	
	$baseUrl = base_url('xAdmin/search');
	
	if($results){
		$startRecord = ($currentPage - 1) * $this->pageLimit + 1;
		$endRecord = min($currentPage * $this->pageLimit, $totalRecords);
		$html .= '<p class="x-results"><span>Showing '.$startRecord.'-'.$endRecord.' of '.$totalRecords.' results. From newest to oldest</span></p>';
		
		$html .= '<table class="table" id="results"><thead>';
		
		$columns = ['Name','Email','Membership Date','Site','Membership Status','Mailchimp Status'];
		
		$html .=  '<tr class="title">';
		
		foreach($columns as $c){
			$sortKey = isset($columnSortMap[$c]) ? $columnSortMap[$c] : null;
			$isCurrentSort = ($sortKey && $sortKey === $sortColumn);
			$newDirection = ($isCurrentSort && $sortDirection === 'ASC') ? 'DESC' : 'ASC';
			
			if($sortKey){
				$sortUrl = $baseUrl . '?sort=' . $sortKey . '&dir=' . $newDirection;
				$sortIndicator = '';
				if($isCurrentSort){
					$sortIndicator = $sortDirection === 'ASC' ? ' ↑' : ' ↓';
				}
				$html .=  '<th class="c'.md5('mit'.$c).'" style="cursor: pointer;"><a href="'.$sortUrl.'" style="text-decoration: none; color: inherit;">'.$c.$sortIndicator.'</a></th>';
			}else{
				$html .=  '<th class="c'.md5('mit'.$c).'">'.$c.'</th>';
			}
		}
		$html .=  '</tr> </thead> <tbody>';

		foreach($results as $item){
			$icon = '';
			// if($item['status']=== '1'){
			// 	$icon .= ' unsign';
			// }else{
			// 	$icon .= ' unsign';
			// } 	
			
			$html .=  '<tr class="list '.$icon.'">';
			$id = $item['id'];
			
			$html .=  '<td class=""><a class="profile" href="'.base_url('xAdmin/baptist/'.$id).'">'.$item['fName'].' '.$item['mName'].' '.$item['lName'].'</a></td>';
			
			$html .=  '<td data-lable="email" data-mid="'.$id.'" class="tditem">'.$item['email'].'</td>';
			
			$html .=  '<td data-lable="membershipDate" data-mid="'.$id.'" data-rval="'.($item['membershipDate']?$item['membershipDate']:-99999999999).'" class="tditem dateInput">'.($item['membershipDate']!==NULL?($item['membershipDate']==0?'???':date("m/d/Y",$item['membershipDate'])):'').'</td>';
			
			$html .=  '<td data-lable="site" data-mid="'.$id.'" class="tditem siteInput">'.$item['site'].'</td>';
			
			// Map inactive values to display text
			$inactiveValue = isset($item['inactive']) ? $item['inactive'] : null;
			$inactiveDisplay = getInactiveStatusText($inactiveValue);
			$html .=  '<td data-lable="inactive" data-mid="'.$id.'" class="tditem">'.$inactiveDisplay.'</td>';
			
			$mailchimpStatus = isset($item['onMailchimp']) && $item['onMailchimp'] ? ucfirst($item['onMailchimp']) : 'Not set';
			$html .=  '<td class="tditem">'.$mailchimpStatus.'</td>';
			
			$html .=  '</tr>';
		}
		
		$html .= ' </tbody></table>';
		
		// Add pagination links
		if($totalPages > 1){
			$html .= $this->generatePaginationLinks($currentPage, $totalPages, $sortColumn, $sortDirection);
		}

	}else{
		$html = '<p id="notMarch">Did not match any documents.</p>';
	}
	
	return $html;
}

private function generatePaginationLinks($currentPage, $totalPages, $sortColumn = 'id', $sortDirection = 'DESC'){
	$html = '<div class="pagination mt-3 mb-3">';
	$baseUrl = base_url('xAdmin/search');
	
	// Build query string with sort parameters
	$queryParams = 'sort=' . urlencode($sortColumn) . '&dir=' . urlencode($sortDirection);
	
	// Previous link
	if($currentPage > 1){
		$prevPage = $currentPage - 1;
		$html .= '<a href="'.$baseUrl.'?page='.$prevPage.'&'.$queryParams.'" class="btn btn-sm btn-secondary mr-2">&laquo; Previous</a>';
	}else{
		$html .= '<span class="btn btn-sm btn-secondary mr-2 disabled">&laquo; Previous</span>';
	}
	
	// Page numbers
	$html .= '<span class="mr-2">';
	
	// Show first page
	if($currentPage > 3){
		$html .= '<a href="'.$baseUrl.'?page=1&'.$queryParams.'" class="btn btn-sm btn-outline-secondary mr-1">1</a>';
		if($currentPage > 4){
			$html .= '<span class="mr-1">...</span>';
		}
	}
	
	// Show pages around current page
	$startPage = max(1, $currentPage - 2);
	$endPage = min($totalPages, $currentPage + 2);
	
	for($i = $startPage; $i <= $endPage; $i++){
		if($i == $currentPage){
			$html .= '<span class="btn btn-sm btn-primary mr-1">'.$i.'</span>';
		}else{
			$html .= '<a href="'.$baseUrl.'?page='.$i.'&'.$queryParams.'" class="btn btn-sm btn-outline-secondary mr-1">'.$i.'</a>';
		}
	}
	
	// Show last page
	if($currentPage < $totalPages - 2){
		if($currentPage < $totalPages - 3){
			$html .= '<span class="mr-1">...</span>';
		}
		$html .= '<a href="'.$baseUrl.'?page='.$totalPages.'&'.$queryParams.'" class="btn btn-sm btn-outline-secondary mr-1">'.$totalPages.'</a>';
	}
	
	$html .= '</span>';
	
	// Next link
	if($currentPage < $totalPages){
		$nextPage = $currentPage + 1;
		$html .= '<a href="'.$baseUrl.'?page='.$nextPage.'&'.$queryParams.'" class="btn btn-sm btn-secondary">Next &raquo;</a>';
	}else{
		$html .= '<span class="btn btn-sm btn-secondary disabled">Next &raquo;</span>';
	}
	
	$html .= '</div>';
	
	return $html;
}

private function mode0html($links,$results,$ingClassesIds,$part=1){ 
	
	$html='';
	$webConfig = new \Config\WebConfig();
	$curriculumCodes = $webConfig->curriculumCodes[$this->lang];
	
	if($part===1){
		$dispalyCurs = array_intersect_key($curriculumCodes,['CBIF101'=>1,'CBIF000'=>1,'CBIF100'=>1,'CBIF201'=>1,'CBIF301'=>1,'CBIF401'=>1]);
	}else{
		$dispalyCurs = array_intersect_key($curriculumCodes,['CBIB01'=>1,'CBIB02'=>1,'CBIB20'=>1,'CBIB30'=>1,'CBIB40'=>1,'CBIE01'=>1]);
	}
	
	
	$curriculumCount = count($dispalyCurs);
	$cWidth = floor(1/$curriculumCount*100);	
	
	
	
	
	
	if($results){
		
		
		
		
		$html .= $links;
		
		$html .= '<table id="results" class="table sortable"> <thead>';	
	
		$html .=  '<tr class="title"><th class="nameTd"></th><th class="curriculumTd">';
		
		foreach($dispalyCurs as $c){

			$html .=  '<div class="float-left" style="width:'.$cWidth.'%;">'.$c[1].'</div>';		
		}
		
		$html .=  '</th></tr> </thead><tbody>';



		foreach( $results as $item) {
			
			
			
			$icon = '';
			if($item['status']=== '1'){
				$icon .= ' signin';
			}else{
				$icon .= ' unsign';
			}	

		
			
			$html .=  '<tr class="'.$icon.'">';
					
			$html .=  '<td><a class="profile" href="'.base_url('xAdmin/baptist/'.$item['id']).'">'.$item['name'].'</a></td><td>';
			
				foreach($dispalyCurs as $code => $c){

					$html .=  '<div class="float-left" style="width:'.$cWidth.'%;"><div>';
					$prize = [];
					
					//CBIF100#10#100|||CBIF101#36#100|||CBIF301#45#100|||CBIB20#60#100|||CBIB01#72#100|||CBIB30#96#100|||CBIB30#100#100
					//CBIB20#63#100
					
						if($item['logs'] && preg_match_all('/'.$code.'#(\d+)#(\d+)/i',$item['logs'],$logs)){
							
								$count = 0;
								$ing = false;
							
								foreach($logs[2] as $key => $wcd){
									if($wcd>74) $count++;
									
									if($wcd!=100 && in_array($logs[1][$key],$ingClassesIds)) $ing = true;
								}
								
								if($code == 'CBIB40'||$code == 'CBIE01'){
									
									if($count>1) $prize[0] = 'Completed';
									
								}else{
									
									if($count>0) $prize[0] = 'Completed';
									
								}
								
								if($ing) $prize[1]  = 'In Progress';
							
						}
					
	
						$html .= '<h4>'.implode(', ',$prize).'</h4>';					
					
					$html .=  '</div></div>';		
				}
				
			$html .=  '</td></tr>';			
			
	
		}
		
		$html .= '</tbody></table>';

	}else{
		
		$html = '<p id="notMarch">Did not match any documents.</p>';
	}
	
	return $html;
}
		
	
		




	//--------------------------------------------------------------------

}
