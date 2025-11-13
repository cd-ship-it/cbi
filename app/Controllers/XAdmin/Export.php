<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;

use App\Models\ClassesModel;
use App\Models\ProfilesModel;


class Export extends BaseController
{
	
	
	public function index($mode=0,$filter='')
	{
		$webConfig = new \Config\WebConfig();		
		$webConfig->checkMemberLogin();
		$webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);		
		
		$modelClasses = new ClassesModel();		
		$modelProfilesModel = new ProfilesModel();
		
		// echo $mode;
		// echo '<br />';
		// echo $filter;
		
		// exit;
		
		$conditionStr = $filter !='all'?rawurldecode( $webConfig->decode($filter)):'';
		$results = $modelClasses->searchBaptismForExport( $conditionStr );  
		
		
		
		// var_dump($results); exit;
		
		
		$classes = $webConfig->curriculumCodes[$this->lang];
		
		
		$over18Title= 'Over 18 as of '. date('m/d/Y');
		
		$arr_title = ['Name','First Name','Last Name','Gender','Marital', $over18Title ,'Occupation','Name Of The Church You Were Baptized','Date Of Your Baptism','Name Of Your Previous Church','Family Member','Site','Membership Date','Home Address','City','Zip Code','Home Phone','Mobile Phone','Email'];
		
		$arr=[];
		
		$arr_title =  array_merge($arr_title, array_keys($classes));
		
		
		
		foreach($results as $key => $item){
			$key++;
			$arr[$key]['Name'] = $item['fName'].' '. $item['mName'].' '. $item['lName'];
			$arr[$key]['First Name'] =  trim($item['fName'].' '. $item['mName']);
			$arr[$key]['Last Name'] = $item['lName'];
			$arr[$key]['Gender'] = $item['gender'];
			$arr[$key]['Marital'] = $item['marital'];
			
			if($item['age'] === NULL){
				$arr[$key]['over18'] = '';
			}elseif($item['age']>=18){
				$arr[$key]['over18'] = 'Yes';
			}else{
				$arr[$key]['over18'] = 'No';
			}
			 
			
			
			$arr[$key]['Occupation'] = $item['occupation'];
			$arr[$key]['nocb'] = $item['nocb'];
			$arr[$key]['baptizedDate'] = $item['baptizedDate']?date("m/d/Y",$item['baptizedDate']):'';
			$arr[$key]['nopc'] = $item['nopc'];			
			$arr[$key]['family'] = $item['family'];
			$arr[$key]['site'] = $item['site'];
			$arr[$key]['membershipDate'] = $item['membershipDate']?date("m/d/Y",$item['membershipDate']):'';
			$arr[$key]['homeAddress'] = $item['homeAddress'];
			$arr[$key]['city'] = $item['city'];
			$arr[$key]['zCode'] = $item['zCode'];
			$arr[$key]['hPhone'] = $item['hPhone'];
			$arr[$key]['mPhone'] = $item['mPhone'];
			$arr[$key]['email'] = $item['email'];
			
			foreach($classes as $code => $c){
				
				$prize = '';
				
						if(preg_match_all('/'.$code.'#(\d+)#(\d+)/i',$item['logs'],$logs)){
							
								$count = 0;
								
							
								foreach($logs[2] as $wcd){
									if($wcd>74) $count++;
								}
								
								if($code == 'CBIB40'||$code == 'CBIE01'){
									
									if($count>1) $prize = 'Completed';
									
								}else{
									
									if($count>0) $prize = 'Completed';
									
								}
							
						}
						
					$arr[$key][$code] = $prize;
				
			}
			
			
			
		}
		
		array_unshift($arr, $arr_title);
		
		$this->download_send_headers("data_export_" . date("Y-m-d") . ".csv");
		echo $this->array2csv($arr);
		exit;


		}
			
		

		
		
		
	
	
	
	




		


private function array2csv(array &$array)
{
   if (count($array) == 0) {
     return null;
   }
   ob_start();
   $df = fopen("php://output", 'w');
   //fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}


private function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
	
	echo "\xEF\xBB\xBF"; // UTF-8 BOM
}
		






	//--------------------------------------------------------------------

}
