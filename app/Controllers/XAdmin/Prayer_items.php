<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\WebmetaModel;
use App\Models\To_doModel;
use App\Models\PrayerItemsModel;
use App\Models\CapabilitiesModel; 	

class Prayer_items extends BaseController
{
	
	
	public function notification($prayer_id,$day)
	{
		
		$data = $this->data;
		
		
			
		$this->webConfig->checkMemberLogin();
		
	

		$action = $this->request->getPost('action');		
		

		
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();
		$modelProfiles = new ProfilesModel();		
		$modelCapabilities = new CapabilitiesModel();
		
		$worship_pastor =  $modelCapabilities->get_sp_users('is_worship_pastor'); 

		$this->webConfig->checkPermissionByDes(['is_worship_pastor','is_senior_pastor'],'exit');	
	

		$prayerPost = $modelPrayerItems->find($prayer_id);
		
		$thePrayerEmail = $thePrayerName = $thePage = '' ;
		$data['backPage'] = base_url('xAdmin/prayer_items/');
		 
		$date =  strtotime(  '+'.$day.' day' , $prayerPost['start'] );  		
		
		$theDay = date('D m/d Y',$date);		
		
		$prayers = $modelWebmetaModel->where('meta_key', 'prayerTier'.$prayerPost['tier'])->first()['meta_value']; 
		
		if(!$prayers){			
			echo 'error'; exit();
		}else{
			$prayers = json_decode($prayers);
			$thePrayerId =  isset($prayers[$day])?$prayers[$day]:false;
		}
		

		
		if($thePrayerId){			
			$thePrayerEmail = $modelProfiles->db_m_getUserField($thePrayerId,'email'); 
			
			$thePrayer = $modelMembersModel->db_m_member($thePrayerId); 
			
			if($thePrayer['admin']==3 || preg_match('#anders|jacky|admin#i',$thePrayer['name'])){
				$thePrayerName = 'Pastor '.$thePrayer['name'];
			}else{
				$thePrayerName = $thePrayer['name'];
			}			
			

			$thePage = base_url('xAdmin/edit_prayer_items/'.$prayerPost['id'].'/'.$day);
		}
		
		
		
		$data['pageTitle'] = 'Send Notification';  
		$data['canonical']=base_url('xAdmin/prayer_items/notification/'.$prayerPost['id'].'/'.$day);	
		
		$data['preMessage'] = "Dear {$thePrayerName}:\n\n";
		$data['preMessage'] .= "Please fill in your prayer items for ".$theDay."\n\n";
		$data['preMessage'] .= $thePage;
		
		
		

		if($action && $action=='send'){
			
			
			
			$message = $this->request->getPost('message');	
			
			$message = nl2br(preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1">$1</a>',   $message));
			
			$this->webConfig->sendtomandrill('Please fill in your prayer items for '.$theDay, $message, $thePrayerEmail);		
			
			echo 'Sent successfully';	
			exit();
			
			
			
			
			
			
		}
		
		
		
			$data['menugroup'] = 'prayer_items';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'prayer_items_notification';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);			
		
		
 
		
		
		
		
		
	}	
	
	public function output()
	{
		
		$modelPrayerItems = new PrayerItemsModel();	
 	
		
		
		$item = $modelPrayerItems->getLastPrayerItem();
		//dd($prayerItems);	
		
	



// echo'
// <header class="entry-header clr">
	// <h2 class="single-post-title entry-title" itemprop="headline">Weekly Prayers</h2>
// </header>';

echo '<div class="entry-content clr"  itemprop="text">';


 
	
	if($item['en']||$item['zh_hant']||$item['zh_hans']){
	
		//$date =  strtotime(  '+'.$key.' day' , $thisWeekPrayer['start'] );  		
		//echo '<h2>'.date('D m/d ',$date).'</h2>';
		echo '<div style="margin-bottom:50px;">';
		echo '<p>'.($item['en']?nl2br($item['en']):'N/A').'</p>';
		echo '<p>'.($item['zh_hant']?nl2br($item['zh_hant']):'N/A').'</p>';
		echo '<p>'.($item['zh_hans']?nl2br($item['zh_hans']):'N/A').'</p>';
		echo '</div>';
	}
	
 

echo '</div><!-- .entry -->';





	
		

		// $output = [];

		// foreach($prayerItemsArr as $key => $item){
			
			// if($item){
				
				
				// $date =  strtotime(  '+'.$key.' day' , $thisWeekPrayer['start'] );  				
				
				// $output[$key]['day'] = date('D m/d ',$date);
				// $output[$key]['en'] =  $item['en'];
				// $output[$key]['zh_hant'] =  $item['zh_hant'];
				// $output[$key]['zh_hans'] =  $item['zh_hans'];
				
				
			// }
		// }
		
		// echo json_encode($output);


	}		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
			
	public function index($week=false)
	{  
	
	
		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/prayer_items');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		

		$action = $this->request->getPost('action');	
	
	
	
	
 
		
		
		$modelMembersModel = new MembersModel();
		$modelWebmetaModel = new WebmetaModel();		
		$modelPrayerItems = new PrayerItemsModel();			
		$modelTo_do = new To_doModel();	
		$modelCapabilities = new CapabilitiesModel();		
		
		$worship_pastor =  $modelCapabilities->get_sp_users('is_worship_pastor'); 

	
		$this->webConfig->checkPermissionByDes(['is_worship_pastor','is_senior_pastor'],'exit');	
		
		
		 
		
		//$data['admins'] = $modelMembersModel->getAdminList();
		$data['admins'] = $this->modelPermission->getPermissionUsers();
		
		$data['pageTitle'] = 'Crosspoint Church Weekly Prayer Items';
		
		
		$newestPrayers = $modelPrayerItems->getNewest4Prayer();
		
		
		
		
		
		
		$data['newestPrayers'] = $newestPrayers = array_reverse($newestPrayers);
		
	 
		$data['week'] = false;
		 
		
		
		foreach($newestPrayers as $key => $item){
			if($item['start']>time()){
				$data['activeWeek'] = $item;
				break;
			}
			
			$data['activeWeek'] = $newestPrayers[0];
		}		
		
		
		if($week && in_array($week,array(4,1,2,3))){
			
			$weekNum = $week -1 ;
			$displayingWeek = $newestPrayers[$weekNum];
			$data['week'] = $week;
			
		}else{
			
			foreach($newestPrayers as $key => $item){
				if($item['start']>time()){
					$displayingWeek = $item;
					break;
				}
				
				$displayingWeek = $newestPrayers[0];
			}
			
		}		

		$data['displayingWeek'] = $displayingWeek;
		
		
		
		$prayers = $modelWebmetaModel->where('meta_key', 'prayerTier'.$displayingWeek['tier'])->first()['meta_value']; 
		
		
		$keys = array(0, 1, 2, 3, 4, 5, 6);		
		
		if(!$prayers){			
			$data['prayers'] =  array_fill_keys($keys, '');	
		}else{
			$data['prayers'] = json_decode($prayers);
		}
		
		
		
		
		// $data['prayers'] = [0,0,336,565, 0,0,0];
		
		$prayerItems = $modelPrayerItems->getPrayerItems($displayingWeek['id']);
		$data['prayerItems'] = array_fill_keys($keys, '');	
		
		
	
		
		foreach($prayerItems as  $prayerItem){
			
			//$prayerIndex = array_keys($data['prayers'],$prayerItem['bid']);
			$theDay = $prayerItem['day'];
			
			if(isset($data['prayers'][$theDay])&&$data['prayers'][$theDay]==$prayerItem['pid']) $data['prayerItems'][$theDay] = $prayerItem;
					
			
		}
		
		
	
		//var_dump($prayerItems); var_dump( $data['prayerItems']); exit;



		
		$data['prayerProvideHelp'] =  [];	$data['prayerNeedHelp'] =  [];


		
		
		if($action && $action=='prayerChange'){
		
					

			
			$tierUsers = json_encode($this->request->getPost('tierUsers'));
			$targetTier = $this->request->getPost('targetTier');			
			$targetWeekId = $this->request->getPost('targetWeekId');			
			$targetDay = $this->request->getPost('targetDay');			
			$targetUser = $this->request->getPost('tierUsers')[$targetDay];			
					
			$modelWebmetaModel->where('meta_key', 'prayerTier'.$targetTier)->delete();
			$modelWebmetaModel->insert(['meta_key'=>'prayerTier'.$targetTier, 'meta_value' => $tierUsers]);			


 
			
								$weekPrayer = $modelPrayerItems->find($targetWeekId);
			
						
								
								
								
								
								$code1 = 'prayeritem_'.$weekPrayer['id'].'_'.$targetDay;
								$modelTo_do->like('code', $code1)->delete();
					
					
			
			
				
					
					
	
	
					


					
			
						
						
						
				
				
							
					
					
								 

													
							
							
							
		
						
 
				
				
				
				
				
				
				
				
		
			
			


		 



































						
			echo 'Updated';
			exit();
		
		
		}elseif($action && $action=='prayerSave'){
			
			// $tier0 = $this->request->getPost('tier0')?$this->request->getPost('tier0'):0;	
			// $tier1 = $this->request->getPost('tier1')?$this->request->getPost('tier1'):0;	
			// $tier2 = $this->request->getPost('tier2')?$this->request->getPost('tier2'):0;	
			// $tier3 = $this->request->getPost('tier3')?$this->request->getPost('tier3'):0;	
			// $tier4 = $this->request->getPost('tier4')?$this->request->getPost('tier4'):0;	
			// $tier5 = $this->request->getPost('tier5')?$this->request->getPost('tier5'):0;	
			// $tier6 = $this->request->getPost('tier6')?$this->request->getPost('tier6'):0;	
			
			$prayer_id = $this->request->getPost('prayer_id');
			
			$targetTier = $this->request->getPost('selectTier');
			
			$start = strtotime($this->request->getPost('start-date'));
			$end = strtotime($this->request->getPost('end-date'));
			
			$remind1 = $this->request->getPost('selectRemind1')?$this->request->getPost('selectRemind1'):0;
			$remind2 = $this->request->getPost('selectRemind2')?$this->request->getPost('selectRemind2'):0;
			// $remind3 = $this->request->getPost('selectRemind3')?$this->request->getPost('selectRemind3'):0;
			
			$modelWebmetaModel->where('meta_key', 'prayerNeedHelp')->delete();
			if($this->request->getPost('prayerNeedHelp')){
				$prayerNeedHelp = json_encode($this->request->getPost('prayerNeedHelp'));				
				$modelWebmetaModel->insert(['meta_key'=>'prayerNeedHelp', 'meta_value' => $prayerNeedHelp]);					
			}



			$modelWebmetaModel->where('meta_key', 'prayerProvideHelp')->delete();
			if($this->request->getPost('prayerProvideHelp')){
				$prayerProvideHelpValue =  json_encode(array_map('trim',explode(',',$this->request->getPost('prayerProvideHelp'))));				
				$modelWebmetaModel->insert(['meta_key'=>'prayerProvideHelp', 'meta_value' => $prayerProvideHelpValue]);					
			}
			

			


				
			
			// $newTierPrayers = [$tier0,$tier1,$tier2,$tier3,$tier4,$tier5,$tier6];			
			// $modelWebmetaModel->where('meta_key', 'prayerTier'.$targetTier)->delete();
			// $modelWebmetaModel->insert(['meta_key'=>'prayerTier'.$targetTier, 'meta_value' => json_encode($newTierPrayers)]);	
			
			$newPrayerOptions = array('tier'=>$targetTier ,'start'=>$start ,'end'=>$end ,'remind1'=>$remind1,'remind2'=>$remind2  );
			$modelPrayerItems->update($prayer_id,$newPrayerOptions);
			
			
			
								$code2 = 'prayeritem_'.$prayer_id.'_';
								$modelTo_do->like('code', $code2)->delete();
			
			
		
			echo 'updated';
			exit();	
			
		}	
				

		
			$data['menugroup'] = 'prayer_items';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_prayer_items';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data); 			
			
			echo view('theme-sb-admin-2/layout',$data);	
		 
		
		}
			
		

		
private function curlpost($url,$timeout=0,$vars='',$headers=[])
{
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
	
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	
	
	if($vars) curl_setopt($ch, CURLOPT_POST, true);
	if($vars) curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  

	if($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	

	$server_output = curl_exec ($ch);
	
    if ($server_output === false) {
        var_dump(curl_error($ch));
    }	
	

	curl_close ($ch);
	
	return $server_output;

}	
			
	
	


	//--------------------------------------------------------------------

}
