<?php namespace App\Controllers;


use App\Models\ClassesModel;
use App\Models\WebmetaModel;
use App\Models\To_doModel;
use App\Models\PtoRelationModel;
use App\Models\PrayerItemsModel;
use App\Models\ProfilesModel;
use App\Models\CapabilitiesModel; 
use App\Models\VisitorsModel;			

class Cronjobs extends BaseController
{
	
	public $emailsVars = [];
	
	
	public function index()
	{
		
				
		
		
		$logs =  '------- '.date('Ymd H:i').' --------'.PHP_EOL;
		
		$webConfig = new \Config\WebConfig();	
		
		$modelWebmetaModel = new WebmetaModel();		
		$modelProfiles = new ProfilesModel();
		$modelTo_do = new To_doModel();	
		$modelCapabilities = new CapabilitiesModel();
		$modelClasses = new ClassesModel();	
		$modelPrayerItems = new PrayerItemsModel();
		$modelPtoRelation = new PtoRelationModel();	
		$modelVisitors = new VisitorsModel();
		
		

			
		if(isset($webConfig->enable_classes_reminder) && $webConfig->enable_classes_reminder){
			
			
			
			
			$t1 = time();
			$t2 = time()+3600*27;
			$t3 =  time()+3600*24*7; //reminder for pastor

			$cxx = "`start` > {$t1} AND`reminder` = 0";

			$reminderJobs = $modelClasses->where($cxx)->notLike('title', 'e-learning')->orderBy('start', 'ASC')->findAll();


			if($reminderJobs){
				
				
				foreach($reminderJobs as $reminderJob){
					
					
					
					if($reminderJob['start']<=$t2){
						
						$reminder = $modelWebmetaModel->where('meta_key', 'reminder')->first()['meta_value']; 	
						
						$send_to_arr = $modelClasses->db_m_getemailsbyCid($reminderJob['id']);
						$sessions = json_decode( $reminderJob['sessions']);
						
						
				
			
						
						if($send_to_arr){ 
							 
							$classUrl = base_url('class/'.$reminderJob['id']);
							
							$arr_s = array('[classname]','[time&date]','[classURL]');
							$arr_r =  array($reminderJob['title'],date("m/d/Y g:i a",$sessions[0]),'<a href="'.$classUrl.'">'.$classUrl.'</a>');
							
							$msgCon = str_replace($arr_s,$arr_r,$reminder);			
							
							foreach($send_to_arr[0] as $key => $stu){
								
								$myEmailItem = [];
								$myEmailItem['From'] =  'admin@tracycrosspoint.org';
								$myEmailItem['To'] = $send_to_arr[1][$key]; 
								$myEmailItem['Subject'] = 'The lesson will start soon (' . $reminderJob['title'] . ')';
								$myEmailItem['HtmlBody'] = nl2br($msgCon);
								
								$this->emailsVars[] = $myEmailItem;
								$this->emailsVars['template']['custom-msg'][] = ["email_address" => ["address" => $myEmailItem['To']], "merge_info" => ['subject'=>$myEmailItem['Subject'], 'msg'=>$myEmailItem['HtmlBody']]];

							}	

							
							
								

								
						}
						
						
						
						
						$session_pastors =  isset($reminderJob['pastor']) && $reminderJob['pastor'] ? json_decode($reminderJob['pastor'],true) : [];	
						$needConfirm = [];
						
						foreach($session_pastors as $item){							
							$needConfirm = array_merge($needConfirm,array_values($item));							
						}
						
						$send_to_pastor_arr = $modelClasses->db_m_getemails($needConfirm);
						
						
						if($send_to_pastor_arr){ 
						
								
								$classUrl = base_url('class/'.$reminderJob['id']);
								$msgCon = "This is a kind reminder that you will teach {$reminderJob['title']}<br /><br />$classUrl";
						
							foreach($send_to_pastor_arr[0] as $key => $pastorName){
								
								$myEmailItem = [];
								$myEmailItem['From'] =  'admin@tracycrosspoint.org';
								$myEmailItem['To'] = $send_to_pastor_arr[1][$key]; 
								$myEmailItem['Subject'] = 'The lesson will start soon (' . $reminderJob['title'] . ')';
								$myEmailItem['HtmlBody'] = nl2br($msgCon);
								
								$this->emailsVars[] = $myEmailItem;
								$this->emailsVars['template']['custom-msg'][] = ["email_address" => ["address" => $myEmailItem['To']], "merge_info" => ['subject'=>$myEmailItem['Subject'], 'msg'=>$myEmailItem['HtmlBody']]];

							}	
						
						}
						
						
						
						
						
						
						$modelClasses->update($reminderJob['id'],array('reminder'=>1));	
						
					}		
				}
				
			}
			
			
			
					$currentTime = time();
					$wArray = ['end >' => $currentTime, 'status =' => 0];
					
					$uploadPPT_notifications = $modelTo_do->where($wArray)->like('code', 'uploadPPT')->findAll();


			 
					 
					
					
					foreach($uploadPPT_notifications as $item){
						
						
								$cid = explode('_',$item['code'])[1]; //uploadPPT_356_453_1707883200						

								$class = $modelClasses->asArray()->find($cid);
								
								if(!$class){
									
									 $modelTo_do->where('id', $item['id'])->delete();
									 continue;
									
								}else{
									
									
									$session_pastors =  isset($class['pastor']) && $class['pastor'] ? json_decode($class['pastor'],true) : [];	
									$finder = 0;
									
									foreach($session_pastors as $sessions){
										
										if(in_array($item['bid'],$sessions)){
											$finder = 1;
											break;
										}
										
									}

									if(!$finder){
										$modelTo_do->where('id', $item['id'])->delete();
										continue;										
									}
									
									
								}
								
								




								$theEmail = $modelProfiles->db_m_getUserField($item['bid'],'email');
								$theName = $modelProfiles->getUserName($item['bid']); 
								
								
								
								$myEmailItem = [];
								
								$myEmailItem['From'] =  'admin@tracycrosspoint.org';
								$myEmailItem['To'] = $theEmail; 
								
								
								$myEmailItem['Subject'] =  $item['title'];
								$myEmailItem['HtmlBody'] = $item['content'];								
								
								
								
								
								
								if($item['end'] < time()){
									
									
									$modelTo_do->update($item['id'],array('status'=>1));
									
									
									
								}elseif($item['notification1'] != 0 && time() > $item['notification1']){
									
									$this->emailsVars[] = $myEmailItem;
									$this->emailsVars['template']['custom-msg'][] = ["email_address" => ["address" => $myEmailItem['To']], "merge_info" => ['subject'=>$myEmailItem['Subject'], 'msg'=>$myEmailItem['HtmlBody']]];

									
									
									if($item['repetition']){
										
										$new_notification = strtotime($item['repetition'], $item['notification1']);		

										while($new_notification<time()){
											
											$new_notification = strtotime($item['repetition'], $new_notification);		
											
										}


										
									}else{
										$new_notification = 0 ;
									}
									
									$modelTo_do->update($item['id'],array('notification1'=>$new_notification));
									
								}


			
								
 
						
						

					
					}			
			
			
			

			$logs .=  'classes_reminder checked ' .PHP_EOL;
			
		}
		
				

		if(isset($webConfig->enable_pto_monthly_update) && $webConfig->enable_pto_monthly_update){
			
			
			 $t = time();
			
			 
			$PTO_monthly_update  = $this->modelPermission->getPtoUsersUpdateSchedule();

			if($PTO_monthly_update){	
				
				foreach($PTO_monthly_update as $item){
					
					if(!$item['update_schedule']) continue;
					if(!$item['ft_hire']) continue;
					
					

					
					
						$years =  (time()-$item['ft_hire']) / 3600/24/365 ;
						
						if($years>15){
							
							$rate_per_month =  2.5 ;
							
						}elseif($years>10){
							
							$rate_per_month =  2 ;
							
						}elseif($years>3){
							
							$rate_per_month =  1.5 ;
							
						}else{
							
							$rate_per_month = 1;
							
						}
						
					$update_schedule = strtotime(  '+1 month' , $item['update_schedule'] );	
					
					
					if($update_schedule>time()){
						
						$modelPtoRelation->scheduleUpdate($item['bid'],$update_schedule);
						
						$balanceValue = $rate_per_month + $item['balance'];
						
						if($balanceValue+$rate_per_month>=30){
							
									$toDoArray = [];
							
									$toDoArray['bid'] = $item['bid'];							
									$toDoArray['title'] = "Your Paid Time Off (PTO) balance is approaching the maximum limit";	
									$toDoArray['content'] = 'This is a friendly reminder that your Paid Time Off (PTO) balance is approaching the maximum limit. Please note that when your PTO balance reaches or exceeds 30 days, it will not be automatically incremented.<br /><br />We kindly recommend that you consider submitting your PTO days off request soon.<br /><br />Thank you!';			
									
									$toDoArray['notification1'] = 0;	
									$toDoArray['notification2'] = 0;	
									$toDoArray['notification3'] = 0;	
									
									$toDoArray['end'] = strtotime(  '+2 month' , $item['update_schedule'] );						
									$toDoArray['status'] = 0;	
									$toDoArray['code'] = 'balance_maximum_'.$item['bid'].'_'.$update_schedule;	
									
									$is_exist = $modelTo_do->where(['code'=>$toDoArray['code']])->first();
									
									if(!$is_exist){
										
										$modelTo_do->replace($toDoArray);
										
										$userName = $modelProfiles->getUserName($item['bid'],1); 
										
										$myEmailItem = [];
										
										$myEmailItem['From'] =  'admin@tracycrosspoint.org';
										$myEmailItem['To'] = $modelProfiles->db_m_getUserField($item['bid'],'email'); 
										
										
										$myEmailItem['Subject'] =  $toDoArray['title'];
										
										$myEmailItem['HtmlBody'] = 'Dear '.$userName.':<br /><br />';	
										$myEmailItem['HtmlBody'] .= $toDoArray['content'];	
										


									$this->emailsVars[] = $myEmailItem;
									$this->emailsVars['template']['custom-msg'][] = ["email_address" => ["address" => $myEmailItem['To']], "merge_info" => ['subject'=>$myEmailItem['Subject'], 'msg'=>$myEmailItem['HtmlBody']]];

										
										
									}
									$logs .= 'to-do item created ('.$toDoArray['code'].')' .PHP_EOL;							
							
							
						}else{
							
								$code = 'balance_maximum_'.$item['bid'].'_';	
							
								$modelTo_do->like('code', $code)->delete();	
							 
						}
						
						
						
						
						
						
						
						
						if($modelPtoRelation->fieldUpdateByBid($item['bid'],'balance',$balanceValue)){
							
									$logData = [];	
									$logData['old_value'] = $item['balance'];
									$logData['new_value'] = $balanceValue;
									$logData['target'] = $item['bid'];	
									$logData['altered_by'] = 'pto_monthly_update';										 
									
									
									if($logData['old_value']!=$logData['new_value']) $modelPtoRelation->ptolog($logData);								
							
							
							
							
						}
						
						
						
						
						
						$logs .=  'pto_monthly_update Updated: '.$item['bid'].' - '.date("m/d/Y",$update_schedule).' - +'. $rate_per_month .PHP_EOL;	

		
						
					}else{
						
						$logs .=  'pto_monthly_update error: '.$item['bid'].' - '.date("m/d/Y",$update_schedule).PHP_EOL;	

							
					}
					
				}
				
				
			}

			$logs .=  'pto_monthly_update checked' . PHP_EOL;
			
			
		}
		
		
		
		
		if(isset($webConfig->enable_NVA_update_and_email) && $webConfig->enable_NVA_update_and_email){
			
			$after = '2025-06-01';

			$newVisitors = $modelVisitors->getNewVisitorsForWEmail($after);
			foreach($newVisitors as $key=>$visitor){
				
				if(!filter_var($visitor['email'], FILTER_VALIDATE_EMAIL)){
					continue;
				} 
				
				$logData = [];
	

				$this->emailsVars['template']['welcome-new-visitor'][] = ["email_address" => ["address" => $visitor['email']], "merge_info" => ['subject'=>'Welcome to Crosspoint', 'name'=>$visitor['name']]];
				
				$logData['visitor_id'] = $visitor['id'];
				$logData['field'] = 'email_sent';
				$logData['user_login'] = '';
				$logData['log'] = 'Welcome email sent on '.date('F j, Y');				
				
				$modelVisitors->visitor_change_log($logData);

			}


		$code = 'newVisitorStats_'.date('Ymd');			
		 
		$currentDay = date('l');
		$currentHour = date('H');

		



		if(in_array($currentDay, ['Tuesday','Friday']) && $currentHour >= 10){ 
 

			$codeExist = $modelTo_do->where('code', $code)->find();	

				

			if(!$codeExist){

			$newVisitorStats  = $modelVisitors->getNewVisitorStats($after);
			 
				 
				
				foreach($newVisitorStats as $item){
					$item['link'] = base_url('nva/table/'.$item['assigned_id'].'/0');

					$mData = [];

					$mData['email_address'] =  ["address" => $item['email']];
					$mData['merge_info'] =  ['total'=>$item['total'], 'name'=>$item['pastor_name'], 'link'=>$item['link']];

					$this->emailsVars['template']['new-visitors'][] = $mData;	

					if($item['assigned_id']==$this->data['senior_pastor']){


						$mData['email_address'] =  ["address" => 'schiu4678@gmail.com'];
						$mData['merge_info'] =  ['total'=>$item['total'], 'name'=>$item['pastor_name'], 'link'=>$item['link']];

						$this->emailsVars['template']['new-visitors'][] = $mData;	
					}
 

				}

				 

				$modelTo_do->save([
					'code'=>$code,
					'title'=>'New Visitors Email',
					'content'=>'',
					'notification1'=>0,
					'notification2'=>0,
					'notification3'=>0,
					'status'=>0,
				]);


			}
		}




		 



			


			
			
			
			$logs .=  'enable_NVA_update_and_email checked' . PHP_EOL;
			
			
		}
		
		





		$logs .=  '------- '.date('Ymd H:i').' --------'.PHP_EOL . PHP_EOL . PHP_EOL;

		
		file_put_contents(WRITEPATH.'cronjobLogs', $logs ,FILE_APPEND);





		//check_logs_daily
		if(1){ 

			$code = 'check_logs_daily_'.date('Ymd');
			$checkedToday = $modelTo_do->where('code', $code)->find();	
	
			if(!$checkedToday) {


				$dir = WRITEPATH . 'logs/';
				$files = scandir($dir);

				foreach ($files as $file) {
					if (strpos($file, '.log') !== false) {
						if ($file != '.' && $file != '..') {

							$this->emailsVars['template']['system-logs'][] = ["email_address" => ["address" => 'red.chenwen@gmail.com'], 
							"merge_info" => ['today'=>date('Y-m-d')]];	


							$modelTo_do->save([
								'code'=>$code,
								'title'=>'System Logs',
								'content'=>'',
								'notification1'=>0,
								'notification2'=>0,
								'notification3'=>0,
								'status'=>0,
							]);						

						
							break;

						}
					}
				}



		

			}
			
		}//check_logs_daily	
		
			
		
		
		if($this->emailsVars){ 
			//dd($this->emailsVars);	
			$webConfig->Sendbatchemails($this->emailsVars);
		}	
		
		
	}
		
		
	
	


	//--------------------------------------------------------------------

}
