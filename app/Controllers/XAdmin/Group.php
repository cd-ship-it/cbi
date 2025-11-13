<?php 

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController;
use App\Models\GroupModel;
use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\CapabilitiesModel;
	
class Group extends BaseController
{
	
	
	public function index($gid='')
	{
		
		
		$data = $this->data;
		
		$data['gid']=$gid;
		
		$data['canonical']=base_url('xAdmin/group/'.$gid);
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		

		$action = $this->request->getPost('action');		

		$data['logged'] = $this->session->get('mloggedin') ? $this->session->get('mloggedin') : false;
		$data['capabilities'] = $capabilities = $this->session->get('capabilities');






		$modelGroup = new GroupModel();
		$modelMembers = new MembersModel();
		$modelProfiles = new ProfilesModel();
		$modelCapabilities = new CapabilitiesModel();
		
		
		
		 
		if(!isset($capabilities['_edit_groups_'.$gid])){
				$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],'exit');	
		}
		
		
		
		
		
		$data['sites'] = $this->webConfig->sites;
		$data['admins'] = $this->modelPermission->getPermittedUsers('is_pastor');
		
		$data['pageTitle'] = 'New Group';
		$data['goBackUrl'] = base_url("xAdmin/group/search");
		
		$data['tags'] = [];
		
		$data['userLg'] = $this->lang;
		
		
		if($gid){
			
			$theGroup = $modelGroup->find($gid);
			
			if(!$theGroup){
				echo 'post id not found';
				exit();				
			} 
			
			$data['theGroup'] = $theGroup;
			
			$data['joinedUsers'] = $modelGroup->getGroupMembers($gid);  
			
			$data['pageTitle'] = $theGroup['name'];
			
			$tagIds = json_decode($theGroup['tags']);
			
			
			$data['tags'] = $modelGroup->getGroupTags($tagIds);
		}
		
		
		
		
		
		if($action == 'searchGroupMember'){
			
			
	
			$r = $modelGroup->searchGroupMember($gid,$this->request->getPost('query'));
			echo json_encode($r); 
			exit();	
			
		}elseif($action == 'delete'){
			

				$modelGroup->delGroup($this->request->getPost('gid'));
				echo 'OK';
		
			
			exit;
			
		}elseif($action == 'joinGroup'){
			
			$bid = array_key_exists('bid', $_POST) ? $_POST['bid'] : 0;
			
			if($bid){
				$modelGroup->joinGroup($gid,$bid);
				echo 'OK';
			}
			
			exit;
			
		}elseif($action == 'groupUpdate'){
			
			$newTagIds = $this->request->getPost('tags');
			
			$newGroup['campus'] = $this->request->getPost('campus');
			$newGroup['name'] = $this->request->getPost('name');
			
			
			if($this->request->getPost('pastor')){
				$newGroup['pastor'] = $this->request->getPost('pastor');
				$newGroup['pastor_name'] = $modelProfiles->getUserName($newGroup['pastor']);
			}
			
			
			$newGroup['description'] = $this->request->getPost('description');
			
			$newGroup['tags'] = $newTagIds?json_encode($newTagIds):'[]';
			$newGroup['tag_value'] = $modelGroup->getGTagVal($newTagIds);
			
			
			
			$newGroup['publish'] = $this->request->getPost('publish');
			
			if($this->request->getPost('note')){
				$newGroup['note'] = $this->request->getPost('note');
			}
			
			if($this->request->getPost('gid')){
				
				if($modelGroup->update($this->request->getPost('gid'), $newGroup)){
					$r = 'Update Successfully';
				}else{
					$r= 'Error';
				}
				
				
				
				
			}else{
				
				
				
			
				if($modelGroup->insert($newGroup)){
					
					$insertId = $modelGroup->db->insertID();
					
					$r= 'Inserted Successfully<br /><a href="'.base_url('xAdmin/group/'.$insertId).'">Edit</a> | <a href="'.base_url('xAdmin/group/new').'">Add New Group</a>';
					
				}else{
					$r= 'Error';
				}				
			
			}
			
			
			echo $r;
			
			
			exit();
			
		}elseif($action == 'removeUsers'){
			
			
			$ids = $this->request->getPost('ids');
			$modelGroup->removeUsers($gid,$ids);
			
			
			foreach($ids as $theBid){
				
			
				$modelCapabilities->where(['bid'=>$theBid,'capability'=>'_edit_groups_'.$gid,'value'=>$gid])->delete();				
				
			}			
			
			
			echo 'OK';
			exit();
			
		}elseif($action == 'addAdmin'){
			
			
			$ids = $this->request->getPost('ids');
			$modelGroup->addAdmin($gid,$ids);
			
			$modelGroup->updateAdminVal($gid);
			
			
			foreach($ids as $theBid){
				
				$cap['bid'] = $theBid;
				$cap['capability'] = '_edit_groups_'.$gid;
				$cap['value'] = $gid;
				
				$modelCapabilities->replace($cap);				
				
			}

			
			
			
			echo 'OK';
			exit();
			
		}elseif($action == 'removeAdmin'){
			
			
			$ids = $this->request->getPost('ids');
			$modelGroup->removeAdmin($gid,$ids);
			
			$modelGroup->updateAdminVal($gid);
			
			
			foreach($ids as $theBid){
				
 
				
				$modelCapabilities->where(['bid'=>$theBid,'capability'=>'_edit_groups_'.$gid,'value'=>$gid])->delete();				
				
			}			
			
			
			echo 'OK';
			exit();
			
		}elseif($action=='searchTags'){
				
				$key = $this->request->getPost('query');
				
				
 

				
				$r = $modelGroup->searchGroupTagsByKeyword($key);
				
				$html = '';
				
				foreach($r as $item){
					$lable = $item[$this->lang] ? $item[$this->lang] : $item['en'];
					$html .= '<li data-tagval="'.$lable.'" data-tagid="'.$item['id'].'"><span class="lable">'. $lable.'</span> <a href="javascript:void(0);">+ Add</a></li>';
				}
				
				if(!$html){
					$html .= '<li data-tagval="'.$key.'" data-tagid="0">Item not found - <a href="javascript:void(0);">Suggest this item("'.$key.'")</a></li>';
				}
				
				echo $html;
				
				exit();
				
		}elseif($action=='newTag'){
				
				$val = $this->request->getPost('value');
				
				$newTagId = $modelGroup->addNewTag($val,$val,$val);
				
				if($newTagId!==false){
					echo '<li>'.$val.' <input  type="hidden" name="tags[]" value="'.$newTagId.'" />  <a href="javascript:void(0);">x Remove</a></li>';
				}else{
					echo 'Error';
				}
				
				
				
				exit();
				
		}


	


			$data['menugroup'] = 'group';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'group_edit';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data); 			
			
			echo view('theme-sb-admin-2/layout',$data);	 
		
	 
		
		}
			
		

	public function search()
	{	


		$data = $this->data;
		
		$data['canonical']=base_url('xAdmin/group/search');
			
		$this->webConfig->checkMemberLogin($data['canonical']);
		
		
		

		$action = $this->request->getPost('action');		



		
		
		$modelMembers = new MembersModel();
		$modelGroup = new GroupModel();		



		
		
		$data['capabilities'] = $capabilities = $this->session->get('capabilities');
		
		
		
		
		$usersGroupsIds =	[];	
		
		

		
		
		foreach($data['capabilities'] as $key => $val){
			
			if(stripos($key,'_edit_groups')!==false){
				$usersGroupsIds[] = (int) $val;
			}
			
		}		
		
		
		
		 if($usersGroupsIds){
			 $data['usersGroups']=$usersGroups=$modelGroup->whereIn('id',$usersGroupsIds)->findAll();
		 }else{
			  $data['usersGroups'] = [];
		 }
		
		
		
		
		if(!$usersGroupsIds ){
				$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],'exit')	;
		}		
		
		
		
		
		

		
		$data['pageTitle']="Group Search";
		$data['sites'] = $this->webConfig->sites;
		 

		
		$data['admins'] = $this->modelPermission->getPermittedUsers('is_pastor');
		
		
	
	
		
		
		
		
				
		
		 
				
		
		
		
		if($action == 'search'){ 
		
		
		//var_dump($_POST);
		
			$query = $this->request->getPost('query');
			$site = $this->request->getPost('site');
			$pub = $this->request->getPost('pub');
			$pastor = $this->request->getPost('pastor');
			
			$displayAll = $this->request->getPost('displayAll');
			
			
			if($displayAll){
				
				$r = $modelGroup->findAll();
				
			}else{
				
				
				$r = $modelGroup->searchGroup($query,$site,$pastor,$pub);
				
				
				
				
			}
			
			
			

			
			
			
			if($r){
				
				
				echo '<ul id="searchGroupResults">';
				
			
				foreach($r as $row){
					
					echo '<li>';
					
					echo '<h3><a href="'.base_url('xAdmin/group/'.$row['id']).'">'.$row['name'].'</a></h3>';
					echo '<p>Description: '.$row['description'].'</p>';
					echo '<p>Tags: '.$row['tag_value'].'</p>';
					echo '<p>Campus:'.$row['campus'].' | 	Leader:'.$row['leader_name'].' | Pastor:'.$row['pastor_name'].'</p>';
					echo '<p>Publish: '.($row['publish']?'Yes':'No').'</p>';
	
					echo '</li>';
					
				}
			
				echo '</ul>';
			
			}else{
				
				
				echo '<p id="notMarch">Did not match any documents.</p>';
			}
			
			exit;
			
		}
		
		
		
		
		
		

			$data['menugroup'] = 'group';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'group_search';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data); 			
			
			echo view('theme-sb-admin-2/layout',$data);		
		
		
		 	
	}	

	public function tags()
	{		
		
		$data = $this->data;
		


			
		$this->webConfig->checkMemberLogin();
		
		$this->webConfig->checkPermissionByDes(['is_pastor','is_admin'],1);			

		$action = $this->request->getPost('action');			
		


		
		$modelGroup = new GroupModel();	
		
		
		
		$data['tags'] = $modelGroup->findAllTags();
		$data['pageTitle']="Group tags";
	 
		$data['adminUrl'] = base_url('xAdmin');		
		
 
		
		if($action == 'update'){ 
			 
			
			$tagid = $this->request->getPost('tagid');
			$tagData['en'] = $this->request->getPost('enVal');
			$tagData['zh-Hant'] = $this->request->getPost('hantVal');
			$tagData['zh-Hans'] = $this->request->getPost('hansVal');
			
			
			$r= 'error';
			
			if($tagid){
				$tagData['id'] =  $tagid;
				if($modelGroup->updateGTag( $tagData)){
					$r= 'ok';
				}
				
			}else{
				
				if($modelGroup->addNewTag($tagData['en'],$tagData['zh-Hant'],$tagData['zh-Hans'])){
					$r= 'ok';
				}				
				
			}
			

			
			echo $r;
			exit();
			
		}elseif($action == 'remove'){ 
			
		
			
			$tagid = $this->request->getPost('tagid');
			if($modelGroup->deleteGTag($tagid)){
				$r= 'ok';
			}else{
				$r= 'error';
			}	
			echo $r;
			exit();
			
		}
		
		
		
		
			$data['menugroup'] = 'group';
			$data['themeUrl'] = base_url('assets/theme-sb-admin-2');
			$data['sidebar'] = view('theme-sb-admin-2/sidebar',$data);
			
			$data['page'] = 'admin_group_tags';
			
			$data['mainContent'] = view('theme-sb-admin-2/'.$data['page'],$data); 			
			
			echo view('theme-sb-admin-2/layout',$data);			
		
		
		
 	
		
		
	
	
	}

	//--------------------------------------------------------------------

}
