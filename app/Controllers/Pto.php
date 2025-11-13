<?php namespace App\Controllers;



use App\Models\PtoRelationModel;
use App\Models\PtoPostModel;
use App\Models\PtoCommentsModel;
use App\Models\ProfilesModel;
use App\Models\MembersModel;
use App\Models\WebmetaModel;
use App\Models\CapabilitiesModel; 	

class Pto extends BaseController
{


	    public function __construct()
    {
       

        $this->modelPtoPost = new PtoPostModel();
		$this->modelPtoRelation = new PtoRelationModel();
		$this->modelPtoComments = new PtoCommentsModel();
		$this->modelProfiles = new ProfilesModel();
		$this->modelMembers = new MembersModel();
		$this->modelWebmetaModel = new WebmetaModel();
		$this->modelCapabilities = new CapabilitiesModel(); 



    }



	public function index($id='')
	{


		$data = $this->data;
		
		$data['canonical']=base_url('pto/'.$id);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		

		$action = $this->request->getPost('action');	




 		

			
		$data['userLg'] = 'en';		
		$data['mloggedinName'] = $this->session->get('mloggedinName');
		

 			
		
		$data['pto_maximum_limit'] = $this->modelWebmetaModel->where('meta_key', 'pto_maximum_limit')->first()['meta_value']; 	
		

		
		

		
		if($id){
			
			
		
			
			
			$data['pid'] = $id;
			
		
			$ptoPost = $this->modelPtoPost->find($id);
			
			
			if(!$ptoPost){
				echo 'post ['.$id.'] not found';
				exit();
			}
			
			
			
			$data['userName'] = $this->modelProfiles->getUserName($ptoPost['bid']);
			
			$data['userOtherPosts'] = $this->modelPtoPost->where('bid',$ptoPost['bid'])->whereNotIn('id',[$id])->orderBy('id', 'DESC')->findAll();
			
			// $ptoComments = $this->modelPtoComments->where('post_id', $id)->findAll();
			// $data['comments'] = $ptoComments;
			
			// $data['commentAlready'] = array_column($ptoComments, 'author');
			
			
			// Approved disapproved no response
			
			if($ptoPost['status']==1){
				$data['status'] = 'Awaiting Approval';
			}elseif($ptoPost['status']==0){
				$data['status'] = 'Disapproved: Please read remark';
			}elseif($ptoPost['status']==-1){
				$data['status'] = 'Cancelled by user';
			}else{
				$data['status'] = 'Approved';
			} 
			
			$data['pageTitle'] = $ptoPost['types'].' ('. date("m/d/Y",$ptoPost['start']) .  ($ptoPost['end']&&$ptoPost['start']!==$ptoPost['end']? '-'.date("m/d/Y",$ptoPost['end']) : '')  . ')' . ' ['.$data['status'].']' ;
			
			
			$data['types'] = $ptoPost['types'];
			$data['start'] = date("m/d/Y",$ptoPost['start']);
			$data['end'] = date("m/d/Y",$ptoPost['end']);
			
		
			
			$data['notes'] = $ptoPost['notes'];	
			
			$data['bid'] = $ptoPost['bid'];			
			
			
			

			$ptoRelation = $this->modelPtoRelation->where('bid', $ptoPost['bid'])->first();	
			
			
				$data['ptoRelation'] = $ptoRelation;	
			
				$data['supervisor'] = $this->modelProfiles->getUserName($data['ptoRelation']['supervisor']);
				$data['region_pastor'] = $this->modelProfiles->getUserName($data['ptoRelation']['region_pastor']);
				$data['senior_pastor'] = $this->modelProfiles->getUserName($data['ptoRelation']['senior_pastor']);
				$data['zone_pastor'] = $this->modelProfiles->getUserName($data['ptoRelation']['zone_pastor']);
				$data['operations_director'] = $this->modelProfiles->getUserName($data['ptoRelation']['operations_director']);
				$data['balance'] = $data['ptoRelation']['balance'];				
			
		
				$data['ft_hire'] = $data['ptoRelation']['ft_hire']?date("m/d/Y",$data['ptoRelation']['ft_hire']):'';
				$data['update_schedule'] = $data['ptoRelation']['update_schedule']?date("m/d/Y",$data['ptoRelation']['update_schedule']):'';
				
				if($data['ptoRelation']['ft_hire']){
					
					$years =  (time()-$data['ptoRelation']['ft_hire']) / 3600/24/365 ;
					
					if($years>15){
						
						$data['rate_per_month'] =  2.5 ;
						
					}elseif($years>10){
						
						$data['rate_per_month'] =  2 ;
						
					}elseif($years>3){
						
						$data['rate_per_month'] =  1.5 ;
						
					}else{
						
						$data['rate_per_month'] = 1;
						
					}
					
					
					
					
				}else{
					$data['rate_per_month'] = false;
				}

		
			
			// $pastorRoles = ['supervisor'=>'Direct Supervisor','region_pastor'=>'Region Pastor','senior_pastor'=>'Senior Pastor','zone_pastor'=>'Zone Pastor','operations_director'=>'Operations Director'];
			
			$commentAlready = [];
	

			if($ptoRelation['supervisor'] && $ptoRelation['supervisor'] != $ptoPost['bid']){
				$data['supervisorComment'] = $this->modelPtoComments->where(array('post_id'=>$id,'author'=>$ptoRelation['supervisor']))->first();	
				if($data['supervisorComment']){
					$commentAlready[] = $data['supervisorComment']['author'];	
				}else{
					$data['supervisorComment'] = '';
				}
			}			
			
			
			if($ptoRelation['region_pastor'] && $ptoRelation['region_pastor'] != $ptoPost['bid']){
				if(!in_array($ptoRelation['region_pastor'],$commentAlready)){
					$data['region_pastorComment'] = $this->modelPtoComments->where(array('post_id'=>$id,'author'=>$ptoRelation['region_pastor']))->first();	
					if($data['region_pastorComment']){
						$commentAlready[] = $data['region_pastorComment']['author'];	
					}else{
						$data['region_pastorComment'] = '';
					} 
				}
			}
			
			if($ptoRelation['senior_pastor'] && $ptoRelation['senior_pastor'] != $ptoPost['bid']){
				if(!in_array($ptoRelation['senior_pastor'],$commentAlready)){
					$data['senior_pastorComment'] = $this->modelPtoComments->where(array('post_id'=>$id,'author'=>$ptoRelation['senior_pastor']))->first();	
					if($data['senior_pastorComment']){
						$commentAlready[] = $data['senior_pastorComment']['author'];	
					}else{
						$data['senior_pastorComment'] = '';
					} 
				}
			}	
			
			if($ptoRelation['zone_pastor'] && $ptoRelation['zone_pastor'] != $ptoPost['bid']){
				if(!in_array($ptoRelation['zone_pastor'],$commentAlready)){
					$data['zone_pastorComment'] = $this->modelPtoComments->where(array('post_id'=>$id,'author'=>$ptoRelation['zone_pastor']))->first();	
					if($data['zone_pastorComment']){
						$commentAlready[] = $data['zone_pastorComment']['author'];	
					}else{
						$data['zone_pastorComment'] = '';
					} 
				}				
			}
			
			
			
			if(!in_array($ptoRelation['operations_director'],$commentAlready)){
				$data['operations_directorComment'] = $this->modelPtoComments->where(array('post_id'=>$id,'author'=>$ptoRelation['operations_director']))->first();	
				if($data['operations_directorComment']){
					$commentAlready[] = $data['operations_directorComment']['author'];	
				}else{
					$data['operations_directorComment'] = '';
				} 
			}
			
			// $data['commentAlready'] = $this->modelPtoComments->where('post_id', $id)->findColumn('author');
			
			
			$allpastor = array_diff(array_filter(array($ptoRelation['supervisor'],$ptoRelation['region_pastor'], $ptoRelation['senior_pastor'],$ptoRelation['zone_pastor'],$ptoRelation['operations_director'] )),[$ptoPost['bid']]);
			
			
			$data['users_need_to_approve'] = array_diff(array_filter(array($ptoRelation['supervisor'],$ptoRelation['region_pastor'], $ptoRelation['senior_pastor'],$ptoRelation['zone_pastor'])),[$ptoPost['bid']]);
			
			
			$allpastorExOd = array_diff(array_unique($allpastor), [$ptoRelation['operations_director']]); 
			

			
			
			if( in_array($this->session->mloggedin,$allpastorExOd)  && !in_array($this->session->mloggedin,$commentAlready) && $ptoPost['status']==1){			
				$data['showForm'] = true;
			}else{
				$data['showForm'] = false;
			}
			
			
			$data['waiting_operations_director'] = $waiting_operations_director = $this->modelPtoComments->waiting_operations_director($id,$allpastorExOd,$ptoRelation['operations_director']);	
			
			if($this->session->mloggedin==$ptoRelation['operations_director'] && $waiting_operations_director && $ptoPost['status']==1){
				$data['is_operations_director'] = true;
				$data['showForm'] = true;
			}
			
			
			
			
			if(!in_array($this->session->mloggedin,$allpastor) && $this->session->mloggedin != $ptoPost['bid'] ){
				
				$this->webConfig->checkPermissionByDes(['is_senior_pastor','is_office_director'],1);	
				
			}	

			
			if( $this->webConfig->checkPermissionByDes(['is_senior_pastor','is_office_director']) ){
				
				$data['showPtoEdit'] = true;
				
			}else{
				$data['showPtoEdit'] = false;
			}
			
			
		
		$office_director = $this->modelCapabilities->get_sp_users('is_office_director');		
			
			
			$data['allowDelete'] = $this->webConfig->checkPermissionByDes('is_sp_admin') ? true : false;			
			
 		
			
			if( $this->session->mloggedin == $ptoPost['bid'] ){
				
				$data['allowCancel'] = false;
				$data['isSubmitter'] = true;
				
				if ($ptoPost['status']==1){
					
					$data['allowCancel'] = true;
					
				}elseif($ptoPost['status']==2 && $ptoPost['start'] > time()){
					
					$data['allowCancel'] = true;
					
				}
				
				
				
				
				
				
			}else{
				$data['isSubmitter'] = false;
			}
			
			
			
			if(count($allpastorExOd)==count($commentAlready)+1){
				
				$data['lastPastor'] = 1;
			}else{
				$data['lastPastor'] = 0;
			}			
			
			
			
		
		
	
		
		
			
			
		 

			
			
			$data['menugroup'] = 'pto';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'pto_post';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);				
			echo view('theme-sb-admin-2/layout',$data);	

			exit;
			
			
			
			
			
			
			
		}
		
		
		
		
		

			
		
		
		
		


		if($action == 'applyLeave'){
			
			$ptoArray['bid'] = $this->session->mloggedin;	
			$ptoArray['types'] = $this->request->getPost('leave-types');	
			$ptoArray['start'] = $this->request->getPost('start-date') ? strtotime($this->request->getPost('start-date')) : NULL;	
			$ptoArray['end'] =  $this->request->getPost('end-date') ? strtotime($this->request->getPost('end-date')) : NULL;	
			$ptoArray['notes'] = $this->request->getPost('notes');	
			$ptoArray['submit_date'] = time();	
			
			if($this->modelPtoPost->insert($ptoArray)){
				
				$insertId = $this->modelPtoPost->db->insertID();
				
				$r= 'Submit Successfully<br /><a href="'.base_url('pto/'.$insertId).'">View</a>';  
				
				
				$ptoRelation = $this->modelPtoRelation->where('bid', $ptoArray['bid'])->first();	
				$allpastor = array_diff(array_filter(array($ptoRelation['supervisor'],$ptoRelation['region_pastor'], $ptoRelation['senior_pastor'],$ptoRelation['zone_pastor'],$ptoRelation['operations_director'] )),[$ptoArray['bid']]);			
				$allpastorExOd = array_diff(array_unique($allpastor), array($ptoRelation['operations_director']));
				
				if($allpastorExOd){
					$this->applyLeaveSendMsg($insertId,$allpastorExOd);
				}else{
					$this->applyLeaveSendMsg($insertId,[$ptoRelation['operations_director']]);
				}
				
			}else{
				$r = 'Error';
			}			
				
				

			
			
			
				// $r= 'Error';
			echo $r;exit;
			
			
		}elseif($action == 'pastorsOpinion'){
			
			
			$bid = $this->request->getPost('bid');	
			
			
			
			$balance = $this->request->getPost('balance');	
			
			$commentArray['post_id'] = $this->request->getPost('pid');	
			
			$commentArray['author'] = $this->session->mloggedin;	
			
			$authorName = ucfirst($this->modelProfiles->getUserName($commentArray['author']));
			
			$ptoRelation = $this->modelPtoRelation->where('bid',$bid)->first();	
	
				
			$allpastor = array_diff(array_filter(array($ptoRelation['supervisor'],$ptoRelation['region_pastor'], $ptoRelation['senior_pastor'],$ptoRelation['operations_director'] )),[$bid]);	
			$allpastorExOd = array_diff(array_unique($allpastor), array($ptoRelation['operations_director']));	
			
			$allpastorSet[$ptoRelation['supervisor']][] = 'Direct Supervisor';
			$allpastorSet[$ptoRelation['region_pastor']][] = 'Region Pastor';
			$allpastorSet[$ptoRelation['senior_pastor']][] = 'Senior Pastor';
			$allpastorSet[$ptoRelation['zone_pastor']][] = 'Zone Pastor';
			$allpastorSet[$ptoRelation['operations_director']][] = 'Operations Director';

			$commentArray['author_tags'] = $authorName . ' ('.implode(', ',$allpastorSet[$this->session->mloggedin]).')';	
			
			$commentArray['content'] = $this->request->getPost('notes');	
			$commentArray['approved'] = $this->request->getPost('opinion');	
			
			// $commentArray['status'] = $commentArray['approved'] ? 1 : 0 ;
			
			if($ptoRelation['operations_director']==$commentArray['author']){
				$postArray['status'] = $commentArray['approved'] ? 2 : 0 ;
			}else{
				$postArray['status'] = $commentArray['approved'] ? 1 : 0 ;
			}
			
			$ptoPost = $this->modelPtoPost->where('id',$commentArray['post_id'])->first();
			

			
			 
			$this->modelPtoPost->update($commentArray['post_id'],$postArray);
			
			 
			
			
			$commentArray['submit_date'] = time();		
			
			
			if($this->modelPtoComments->insert($commentArray)){
				$insertId = $this->modelPtoComments->db->insertID();
				
				
				if($postArray['status'] != $ptoPost['status']){
					
					$this->pastorsOpinionSendMsg($commentArray);
				}
				
				
				if($commentArray['author'] != $ptoRelation['operations_director']){

					$waiting_operations_director = $this->modelPtoComments->waiting_operations_director($commentArray['post_id'],$allpastorExOd,$ptoRelation['operations_director']);	


					if($waiting_operations_director && $commentArray['approved']){
						
						$operations_director_email = $this->modelProfiles->db_m_getUserField($ptoRelation['operations_director'],'email');
						
						$this->requestForAcknowledgment($commentArray['post_id'], $operations_director_email);
						 
					}
				
				}
				
				
				$r= 'OK';  
				
				if($ptoRelation['operations_director']==$commentArray['author'] && $commentArray['approved']){
					
					if($balance != null){
					
						$this->modelPtoRelation->balanceUpdate($bid,$balance);
						
						$logData['old_value'] = $ptoRelation['balance'];
						$logData['new_value'] = $balance;
						$logData['target'] = $bid;
						$logData['related_post'] = $commentArray['post_id'];
						$logData['uid'] =  $this->logged_id;	
						$logData['altered_by'] = 'OD ' . $this->logged_name;										 
						
						
						if($logData['old_value']!=$logData['new_value']) $this->modelPtoRelation->ptolog($logData);		

					}
					
					
					
				}
				
			}else{
				$r= 'Error';
			}	

			echo $r;exit;
			
		}elseif($action == 'cancelPost'){
			
			$pid = $this->request->getPost('pid');
			$ptoPost = $this->modelPtoPost->find($pid);
			
			if( $this->session->mloggedin == $ptoPost['bid'] ){
				

				if ($ptoPost['status']==1){
					
					$allowCancel = true;
					
				}elseif($ptoPost['status']==2 && $ptoPost['start'] > time()){
					
					
					//$day = ceil( ($ptoPost['end'] - $ptoPost['start']) / 3600 / 24 ) + 1;
					
					$allowCancel = true;
					
				}else{
					
					$allowCancel = false;
				}
				
				
				if($allowCancel){
					$r = $this->modelPtoPost->update($pid,['status'=>-1]);
					
					if($r){		

						$ptoRelation = $this->modelPtoRelation->where('bid',  $ptoPost['bid'])->first();
						
						$allpastor = array_filter(array_unique(array($ptoRelation['supervisor'],$ptoRelation['region_pastor'], $ptoRelation['senior_pastor'],$ptoRelation['zone_pastor'],$ptoRelation['operations_director'] )));	
						
						$this->cancelMsg($pid,$allpastor);
					
						if($ptoRelation && isset($day)){
							
							$value = $day + $ptoRelation['balance'];
							$this->modelPtoRelation->fieldUpdateByBid($ptoPost['bid'],'balance',$value);
							
							
							$logData['old_value'] = $ptoRelation['balance'];
							$logData['new_value'] = $value;
							$logData['target'] = $ptoPost['bid'];
							$logData['related_post'] = $pid;
							$logData['uid'] =  $this->logged_id;	
							$logData['altered_by'] = 'Request Cancelled';										 
							
							
							if($logData['old_value']!=$logData['new_value']) $this->modelPtoRelation->ptolog($logData);								
							
							
						}

						echo 'OK';		

					}
				}
				
				
				exit;
			}	
			
		}
		
		

				$awaitingPosts = $this->modelPtoPost->awaitingPostsForPastor($this->session->mloggedin);
				
						foreach($awaitingPosts as $key => $post){
	
							$needReply = $post['supervisor'] ? explode(',',$post['supervisor']) : [];
							if( !in_array($data['senior_pastor'],$needReply) && $data['senior_pastor'] != $post['bid']  ) $needReply[] = $data['senior_pastor'];
							
							$replyed = $post['replyed'] ? explode(',',$post['replyed']) : [];
							
							
							if($this->logged_id != $data['office_director']){
								
								if( !in_array($this->logged_id,$needReply) ) unset($awaitingPosts[$key]);
							
								if( in_array($this->logged_id,$replyed) ) unset($awaitingPosts[$key]);
								
							}else{
								
						
								
								if( count($replyed) != count($needReply) || count($replyed) ==0 ){			
									unset($awaitingPosts[$key]);
								}
								
								
							}
							
						}
		
		
			if($awaitingPosts || $this->webConfig->checkPermissionByDes(['is_bot_chair']) ){

				$data['awaitingPosts'] = $awaitingPosts;
			}	
				
				
		
				$data['pageTitle'] = 'Your PTO';
				$data['ptoRelation'] = $this->modelPtoRelation->where('bid', $this->session->mloggedin)->first();	


				if($this->logged_id == $data['senior_pastor'] && $data['bot_chair'] && $data['bot_chair'] != $data['ptoRelation']['supervisor']){
					
					$data['ptoRelation']['supervisor'] = $data['bot_chair'];
					$this->modelPtoRelation->update($this->logged_id,['supervisor'=>$data['bot_chair']]);

				}

		
			
			$this->webConfig->checkPermissionByDes(['pto_apply','is_bot_chair'],'exit');	
				
			if($data['ptoRelation']){
			
				$data['supervisor'] = $data['ptoRelation']['supervisor'] ? $this->modelProfiles->getUserName($data['ptoRelation']['supervisor']) : '';
				$data['region_pastor'] = $data['ptoRelation']['region_pastor'] ?  $this->modelProfiles->getUserName($data['ptoRelation']['region_pastor']) : '';
				$data['senior_pastor'] = $data['ptoRelation']['senior_pastor'] ? $this->modelProfiles->getUserName($data['ptoRelation']['senior_pastor']) : '';
				$data['zone_pastor'] = $data['ptoRelation']['zone_pastor'] ? $this->modelProfiles->getUserName($data['ptoRelation']['zone_pastor']) : '';
				$data['operations_director'] = $data['ptoRelation']['operations_director'] ? $this->modelProfiles->getUserName($data['ptoRelation']['operations_director']) : '';
				
				$data['balance'] = $data['ptoRelation']['balance'];
				
				$data['ft_hire'] = $data['ptoRelation']['ft_hire']?date("m/d/Y",$data['ptoRelation']['ft_hire']):'';
				$data['update_schedule'] = $data['ptoRelation']['update_schedule']?date("m/d/Y",$data['ptoRelation']['update_schedule']):'';
				
					if($data['ptoRelation']['ft_hire']){
						
						$years =  (time()-$data['ptoRelation']['ft_hire']) / 3600/24/365 ;
						
						if($years>15){
							
							$data['rate_per_month'] =  2.5 ;
							
						}elseif($years>10){
							
							$data['rate_per_month'] =  2 ;
							
						}elseif($years>3){
							
							$data['rate_per_month'] =  1.5 ;
							
						}else{
							
							$data['rate_per_month'] = 1;
							
						}
						
						
						
						
					}else{
						$data['rate_per_month'] = false;
					}
				
			}

				
				$data['canonical']=base_url('pto');			


				$data['userOtherPosts'] = $this->modelPtoPost->where('bid',$this->session->mloggedin)->orderBy('id', 'DESC')->findAll();		


	
				
				
			 
			$data['menugroup'] = 'pto';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'pto';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);		
			echo view('theme-sb-admin-2/layout',$data);		
	
	 
	}
	
	public function confirm ()
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('pto/confirm');	
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		

		if(!$this->webConfig->checkPermissionByDes('management')){
			$noPermission =  'You don\'t have permission to access this page. <a href="'.base_url('xAdmin').'">Back to homepage</a>';
			echo $noPermission;
			 
			exit();			
		}
		

		
		$ptoUsers = $this->modelPermission->getPtoUsers();
		
		echo '<style>
table {
  border-collapse: collapse; /* 合并相邻单元格的边框 */
  width: 100%;
}

th, td {
  border: 1px solid black; /* 设置边框样式、宽度和颜色 */
  padding: 8px; /* 可选：添加内边距，使内容不紧贴边框 */
  text-align: left; /* 可选：设置文本对齐方式 */
}
</style><table>
  <thead>
    <tr>
      <th> </th>
      <th> </th> 
      <th>supervisor</th>
      <th>senior_pastor</th>
      <th>operations_director</th>
    </tr>
  </thead>
  <tbody>
   ';
		 
		foreach($ptoUsers as $key => $user){
			
			 
				echo ' <tr><td>'.($key).'</td>';
				
				echo '<td>'.$user['name'].'</td>';
			 
			
				$ptoRelation = $this->modelPtoRelation->where('bid', $user['bid'])->first();	
				
				 
					echo '<td>';
					if($ptoRelation){
						echo $this->modelProfiles->getUserName($ptoRelation['supervisor']);
					}
					echo '</td>';
				 
				 
			
				 		
			 
					echo '<td>';
					if($ptoRelation){
						echo $this->modelProfiles->getUserName($ptoRelation['senior_pastor']);
					}
					 
					echo '</td>';
				 		
		 			
				 
					echo '<td>';
					if($ptoRelation){
						echo $this->modelProfiles->getUserName($ptoRelation['operations_director']);
					}
					 
					echo '</td>';
				 
				
			
	 		echo '</tr>';
		}
		
		echo ' </tbody></table>';

	}		
	
	public function staff ()
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('pto/confirm');	
		$data['pageTitle']='Staff';	
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		

		if(!$this->webConfig->checkPermissionByDes('management')){
			$noPermission =  'You don\'t have permission to access this page. <a href="'.base_url('xAdmin').'">Back to homepage</a>';
			echo $noPermission;
			 
			exit();			
		}
		

		
		$pastors = $this->modelPermission->getHierarchyPastors();
		dd($pastors);
		 
		
		
		// $this->data['senior_pastor'] = $this->modelCapabilities->get_sp_users('is_senior_pastor');
		// $this->data['office_director'] = $this->modelCapabilities->get_sp_users('is_office_director');
		// $this->data['office_assistant'] = $this->modelCapabilities->get_sp_users('is_office_assistant');
		
		// $relationships  = [];
		// foreach($staff as $item){
			
			// unset($item['email']);
			
			// if($item['supervisor'] && $item['supervisor']  != $item['id']){
				// $relationships[$item['supervisor']][] = $item;
			// }else{
				// $relationships[$data['senior_pastor']][] = $item;
			// }
			
		// }
 
 
$indexedStaff = [];
$rootNodes = []; // To hold staff members who don't report to anyone (top-level)
 

foreach ($allStaff as $staffMember) {
    // Convert ID and supervisor to appropriate types if they were strings from DB (optional, but good practice)
    // For this example, we keep them as strings as they are used as array keys too.
    $id = $staffMember['id'];
    $supervisorId = $staffMember['supervisor']; // Can be NULL
	
	// if ($supervisorId === null ||  $supervisorId === $id) {
			// if($id != $data['senior_pastor']) $staffMember['supervisor'] = $data['senior_pastor'];
		// }	

    $indexedStaff[$id] = $staffMember;
    $indexedStaff[$id]['children'] = []; // Initialize an empty array for their direct reports
}

// --- Step 2: Build the hierarchical tree structure ---
// Assign children to their respective supervisors
foreach ($indexedStaff as $id => &$staffMember) { // Use & to modify original array elements directly
    $supervisorId = $staffMember['supervisor'];

    // If supervisor is NULL or their ID doesn't exist in our indexed list (meaning they are top-level or their supervisor is not in the current dataset)
    if ($supervisorId === null || !isset($indexedStaff[$supervisorId]) || $supervisorId === $id) {
        $rootNodes[$id] = &$staffMember; // Add to root nodes
    } else {
        // Add this staff member as a child to their supervisor
        $indexedStaff[$supervisorId]['children'][$id] = &$staffMember;
    }
}
unset($staffMember); // Break the reference to the last element

// Filter out any root nodes that might have been accidentally added if their supervisor was *later* found
// This refinement ensures only true top-level entities remain in $rootNodes if we have a full dataset
// If your data always has proper supervisor chains, this step might be less critical.
$finalRootNodes = [];
foreach ($rootNodes as $id => &$staffMember) {
    // Check if this "root" actually has a supervisor that IS in our indexed list and is not itself
    if ($staffMember['supervisor'] !== null && isset($indexedStaff[$staffMember['supervisor']]) && $staffMember['supervisor'] !== $id) {
        // This is not a true root, it reports to someone else in the tree.
        // It's already linked via the $indexedStaff[$supervisorId]['children'] line above.
        continue;
    }
    $finalRootNodes[$id] = &$staffMember;
}
unset($staffMember); // Break the reference

// Sort the final root nodes by last name, then first name for consistent display
usort($finalRootNodes, function($a, $b) {
    $cmp = strcmp($a['lName'], $b['lName']);
    if ($cmp === 0) {
        return strcmp($a['fName'], $b['fName']);
    }
    return $cmp;
});


		
		
		$data['finalRootNodes'] = $finalRootNodes;
		
		

			$data['menugroup'] = 'pto';		
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);

			$data['page'] = 'admin_staff';
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);
			
			
		
			
			
			
			echo view('theme-sb-admin-2/layout',$data);				
		
		
		
		
	}		
	
	
	public function archive ($spid=false,$status=false)
	{
		
		
		$data = $this->data;
		
		$data['canonical']=base_url('pto/archive');	
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		$data['pageTitle'] = 'PTO management';
		$action = $this->request->getPost('action');			
		
		
	
		

		$this->webConfig->checkPermissionByDes(['pto_apply','is_bot_chair'],'exit');	
		
		$data['userLg'] = 'en';		
		$data['mloggedinName'] = $this->session->get('mloggedinName');

		
		
		
		$operations_director_set = $this->modelPtoRelation->findColumn('operations_director'); 
		$data['operationsDirector'] = $operations_director_set ? array_unique($operations_director_set) : [];
		
		$senior_pastor_set = $this->modelPtoRelation->findColumn('senior_pastor');
		$data['seniorPastor'] = $senior_pastor_set ? array_unique($senior_pastor_set) : [];	
		
		$data['maximum_limit'] = $this->modelWebmetaModel->where('meta_key', 'pto_maximum_limit')->first()['meta_value']; 	
		
		$data['pastors'] = $this->modelPermission->getPtoUsers();
		
		
		$data['current_user_id'] = $data['uid'] = $this->session->mloggedin;
	
		
		$action = $this->request->getPost('action');	



		
		
		
		$data['senior_pastor'] = $this->modelCapabilities->get_sp_users('is_senior_pastor');
		$data['office_director'] = $this->modelCapabilities->get_sp_users('is_office_director');		
		
		
		if($this->webConfig->checkPermissionByDes(['is_senior_pastor','is_office_director'])){
		
			$data['notAvailable'] = $this->modelPtoPost->notAvailable();
			$data['archivedPosts'] = $this->modelPtoPost->archivedPostsForPastor($data['current_user_id'],true);

		}




			
		if($action == 'deletePost'){
				
		
			$office_director = $this->modelCapabilities->get_sp_users('is_office_director');				
			
			
			if(!$this->webConfig->checkPermissionByDes(['is_senior_pastor','is_office_director'])){
				echo 'Error'; exit();
			}
			
			$pid = $this->request->getPost('pid');	
			$r = $this->modelPtoPost->where('id', $pid)->delete();
			
			if($r){
				echo 'OK';
			}
			
			exit;
			
		}elseif($action == 'maximum_limit_submit'){
			
			$maximum_limit_val = $this->request->getPost('maximum_limit_val');
			
			
			$this->modelWebmetaModel->where('meta_key', 'pto_maximum_limit')->set(['meta_value' => $maximum_limit_val])->update();
			$this->modelPtoRelation->pto_maximum_limit_update($maximum_limit_val);
			
			echo 'Saved';
			
			exit;
			
		}	





		
			
 
		
			$data['menugroup'] = 'pto';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'pto_archive';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data);		
			echo view('theme-sb-admin-2/layout',$data);				
		
		
		
		//var_dump($data['archivedPosts']);		
	}	
	
	
	private function cancelMsg($pid,$allpastor){	
	
		if(!$allpastor) return;
	
		$webConfig = new \Config\WebConfig();
		



		$ptoPost = $this->modelPtoPost->find($pid);
		$userName = $this->modelProfiles->getUserName($ptoPost['bid']);
		
		$emails = $this->modelProfiles->whereIn('id', $allpastor)->findColumn('email');
		$emailsVars = [];
		$pUrl = base_url('pto/'.$pid);
		
		$subject = 'PTO Cancellation Alert';
		
		$message ='
		<html>
		<head>
		<title></title>
		</head>
		<body>
		
		<p>'.$subject.':<br />
		'.$userName.' has canceled a PTO request as follows:<br />
		<a href="'.$pUrl.'">'.$pUrl.'</a>
		</p>
		</body>
		</html>';
		
		
		
		
		if($emails){
				foreach($emails as   $email){
					
					$myEmailItem = [];
					$myEmailItem['From'] =  'admin@tracycrosspoint.org';
					$myEmailItem['To'] = $email; 
					
					$myEmailItem['Subject'] = $subject;
					$myEmailItem['HtmlBody'] = $message;
					
					$templateSubject = $userName.' has canceled a PTO request';
					$emailsVars['template']['more-information'][] = ["email_address" => ["address" => $email], "merge_info" => ['subject'=>$templateSubject, 'link'=>$pUrl]];

					
					$emailsVars[] = $myEmailItem;
				}	

				$r = $webConfig->Sendbatchemails($emailsVars);		
		}
		
		
	
	}
	
	
	private function requestForAcknowledgment($pid,$email){	
	
		
		
	
		$webConfig = new \Config\WebConfig();
		



		$ptoPost = $this->modelPtoPost->find($pid);
		$userName = $this->modelProfiles->getUserName($ptoPost['bid']);
		


		
		$pUrl = base_url('pto/'.$pid);
		$subject = ' PTO requested by '.$userName.'('. date("m/d/Y",$ptoPost['start']) .  ($ptoPost['end']&&$ptoPost['start']!==$ptoPost['end']? '-'.date("m/d/Y",$ptoPost['end']) : '')  . ') requires your acknowledgement';
		
		$message ='
		<html>
		<head>
		<title></title>
		</head>
		<body>
		
		<p>'.$subject.':<br />
			Please click below link for more information: <a href="'.$pUrl.'">'.$pUrl.'</a>
		</p>
		</body>
		</html>';
		
		
		
		
		$webConfig->sendtomandrill($subject, $message, $email);	
		
		
	
	}	
	
	private function applyLeaveSendMsg($pid,$allpastorExOd){	
	
		if(!$allpastorExOd) return;
	
		$webConfig = new \Config\WebConfig();
		


		$ptoPost = $this->modelPtoPost->find($pid);
		$userName = $this->modelProfiles->getUserName($ptoPost['bid']);
		
		$emails = $this->modelProfiles->whereIn('id', $allpastorExOd)->findColumn('email');
		$emailsVars = [];
		$pUrl = base_url('pto/'.$pid);
		$subject = ' PTO requested by '.$userName.'('. date("m/d/Y",$ptoPost['start']) .  ($ptoPost['end']&&$ptoPost['start']!==$ptoPost['end']? '-'.date("m/d/Y",$ptoPost['end']) : '')  . ') needs your approval';
		
		$message ='
		<html>
		<head>
		<title></title>
		</head>
		<body>
		
		<p>'.$subject.':<br />
			Please click below link for more information: <a href="'.$pUrl.'">'.$pUrl.'</a>
		</p>
		</body>
		</html>';
		
		
		
		
		if($emails){
				foreach($emails as   $email){
					
					$myEmailItem = [];
					$myEmailItem['From'] =  'admin@tracycrosspoint.org';
					$myEmailItem['To'] = $email; 
					
					$myEmailItem['Subject'] = $subject;
					$myEmailItem['HtmlBody'] = $message;
					
					$emailsVars['template']['more-information'][] = ["email_address" => ["address" => $email], "merge_info" => ['subject'=>$subject, 'link'=>$pUrl ]];

					
					$emailsVars[] = $myEmailItem;
				}	

				$r = $webConfig->Sendbatchemails($emailsVars);		
		}
		
		
	
	}
	
	
	
	

	
	
	
	
	
	
	
	
	
	private function pastorsOpinionSendMsg($comment){
		
		$webConfig = new \Config\WebConfig();
		



		$ptoPost = $this->modelPtoPost->find($comment['post_id']);
		$to = $this->modelProfiles->db_m_getUserField($ptoPost['bid'],'email'); 
		
		$toName = $this->modelProfiles->getUserName($ptoPost['bid']);
		$content = trim($comment['content']) ? trim($comment['content']) : 'N/A';
		$pUrl = base_url('pto/'.$comment['post_id']);
		
		$status = $comment['approved']==1 ? 'Approved' : 'Disapproved' ;
		
		if($comment['approved']==1){
			$subject = 'Regarding your PTO request';
		}else{
			$subject = 'Regarding your PTO request';
		}
		
	 
						

						$message ='
						<html>
						<head>
						<title></title>
						</head>
						<body>
						<p>Dear '.$toName.':</p>

						<p>
							Your PTO application status:  '.$status.'.<br />
							Please click below link for more information:<br />
							<a href="'.$pUrl.'">'.$pUrl.'</a>
						</p>
						</body>
						</html>';				
				
						$webConfig->sendtomandrill($subject, $message, $to);		
		
		
	}
	

	//--------------------------------------------------------------------

}
