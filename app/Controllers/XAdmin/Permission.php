<?php

namespace App\Controllers\XAdmin;

use App\Controllers\BaseController; 
use App\Models\PtoRelationModel;
use App\Models\ProfilesModel;

class Permission extends BaseController
{
    public function index()
    {
        $data = $this->data;
        $data['canonical'] = base_url('xAdmin/permission');
        $this->webConfig->checkPermissionByDes('management', 1);

         $modelProfiles = new ProfilesModel();

        $action = $this->request->getPost('action');



       
        
        if($action=='searchUser'){
			
			$r = $this->modelPermission->getNoPermissionUsers($this->request->getPost('query'));
			echo json_encode($r); 
			exit();	
			
		}elseif ($action == 'setUserRole') {
			
            $user_id = $this->request->getPost('user_id');
            $role_id = $this->request->getPost('role_id');
            $r = $this->modelPermission->setUserRole($user_id, $role_id);
			
            echo $r ? 'Updated' : 'Error';
            exit();
			
        }elseif ($action == 'addRole') {

            $role_name = $this->request->getPost('role_name');
            $role_des = $this->request->getPost('role_des');
            $r = $this->modelPermission->addRole($role_name, $role_des);
 
            echo $r ? 'Success' : 'Error';
            exit();
			
        }elseif ($action == 'removeAllPermissions') {

            $user_id = $this->request->getPost('user_id');
			
            $r = $this->modelPermission->removeAllPermissions($user_id);
 
            echo $r ? 'Success' : 'Error';
            exit();
        }elseif($action=='addUserPermission'){
            $user_id = $this->request->getPost('user_id');
            $permission_id = $this->request->getPost('permission_id');
            $r = $this->modelPermission->addUserPermission($user_id, $permission_id);
            echo $r ? 'Success' : 'Error';
            exit();
        }elseif ($action == 'setRolePermissions') {
            $role_id = $this->request->getPost('role_id');
            $permission_ids = $this->request->getPost('permission_ids');

    
            $r = $this->modelPermission->setRolePermissions($role_id, $permission_ids);
            echo $r ? 'Success' : 'Error';
            exit();
        }elseif ($action == 'deleteRole') {
            $role_id = $this->request->getPost('role_id');
            $r = $this->modelPermission->deleteRole($role_id);
            echo $r ? 'Success' : 'Error';
            exit();
        }elseif ($action == 'sp_user_update') {
            $user_id = $this->request->getPost('user_id');
            $permission_name = $this->request->getPost('permission_name');
            $r = $this->modelPermission->sp_user_update($user_id, $permission_name);
            echo $r ? 'Success' : 'Error';
            exit();
        }
        
 



        $data['permissions'] = $this->modelPermission->getPermissions();
        $data['roles'] = $this->modelPermission->getRoles();

        

        $data['permissionUsers'] = $this->modelPermission->getPermissionUsers();
        $permissionUsersIds = array_column($data['permissionUsers'],'id');
       
		
        if($data['bot_chair'] && !in_array($data['bot_chair'],$permissionUsersIds)){

            $data['permissionUsers'][]=['id'=>$data['bot_chair'],'role_id'=>NULL,'role_name'=>'Hidden','user_name'=>$modelProfiles->getUserName($data['bot_chair'])];

        }
		
		



        $data['pageTitle'] = 'user permissions';
        $data['menugroup'] = 'users';
        $data['themeUrl'] = base_url('assets/theme-sb-admin-2');
        
        $data['page'] = 'permissions_index';
        $data['mainContent'] =  view('theme-sb-admin-2/'.$data['page'],$data);
        echo view('theme-sb-admin-2/layout', $data);
    }
	
	
    public function spuser($uid)
    {
        $data = $this->data;
        $data['canonical'] = base_url('xAdmin/permission');
        $this->webConfig->checkPermissionByDes('management', 1);

        $modelPtoRelation = new PtoRelationModel();
        $modelProfiles = new ProfilesModel();

        $ptoRelation = $modelPtoRelation->where('bid', $uid)->first(); 
        
      
        $data['day_of_ft_hire'] = isset($ptoRelation['ft_hire'])&&$ptoRelation['ft_hire']?date("m/d/Y",$ptoRelation['ft_hire']):'Unset';

        $data['user_supervisor'] = isset($ptoRelation['supervisor'])&&$ptoRelation['supervisor'] ? $modelProfiles->getUserName($ptoRelation['supervisor']) :'Unset';       
     
        
        
       
        
		
		$data['all_permissions'] = $this->modelPermission->getPermissions();
		
        $data['user_permissions'] = $this->modelPermission->getUserPermissions($uid);  

        $data['user_role'] = $this->modelPermission->getUserRole($uid);
        if ($data['user_role']) {
            $data['user_role_permissions'] = $this->modelPermission->getRolePermissions($data['user_role']['id']);
        } else {
            $data['user_role_permissions'] = [];
        }

        $data['user'] = $this->modelPermission->getPermissionUserById($uid);  

		
		$action = $this->request->getPost('action');	
			
			
		if ($action == 'saveUserPermissions') {
			
            $permissions = $this->request->getPost('permissions'); //[1=>1,3=>0]
			$this->modelPermission->removeAllUserPermission($uid);
			
			foreach($permissions as $key => $val){
				
				$user_role_permissions_ids = array_column( $data['user_role_permissions'], 'id');
				
				if(in_array($key,$user_role_permissions_ids) && !$val){
					$this->modelPermission->addUserPermission($uid, $key, 0);
				}elseif(!in_array($key,$user_role_permissions_ids) && $val){
					$this->modelPermission->addUserPermission($uid, $key, 1);
				}
				
			}
			
 
			
 
            echo 'Updated' ;
            exit();
        }

        $data['pageTitle'] = 'user permissions';
        $data['menugroup'] = 'users';
        $data['themeUrl'] = base_url('assets/theme-sb-admin-2');
        
        $data['page'] = 'permissions_user';
        $data['mainContent'] =  view('theme-sb-admin-2/'.$data['page'],$data);
        echo view('theme-sb-admin-2/layout', $data);
    }
}