<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    // 权限表
    protected $table      = 'p_permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description'];

    // 获取所有权限
    public function getPermissions()
    {
        return $this->db->table('p_permissions')->orderBy('name', 'ASC')->get()->getResultArray();
    }

    // 获取所有角色
    public function getRoles()
    {
        return $this->db->table('p_roles r')
            ->select('r.*, GROUP_CONCAT(p.id) as permissions')
            ->join('p_role_permissions rp', 'r.id = rp.role_id', 'left')
            ->join('p_permissions p', 'rp.permission_id = p.id', 'left')
            ->groupBy('r.id')
            ->get()
            ->getResultArray();
    }

 

    //创建 role
    public function addRole($role_name, $role_des)
    {
        $this->db->transStart();

        // Insert the new role
        $this->db->table('p_roles')->insert([
            'name' => $role_name,
            'description' => $role_des
        ]);

        // Get the newly inserted role ID
        $role_id = $this->db->insertID();

        // Assign default permission (ID = 1) to the new role
        $this->db->table('p_role_permissions')->insert([
            'role_id' => $role_id,
            'permission_id' => 1
        ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }


    //删除 role
    public function deleteRole($role_id)
    {
		$this->db->table('members')
            ->where('admin', $role_id)
            ->set('admin', 0)
            ->update();
		
        return $this->db->table('p_roles')->where('id', $role_id)->delete();
    }

    //设置角色权限
    public function setRolePermissions($role_id, $permission_ids)
    {
        $this->db->transStart();

        // 删除旧权限
        $this->db->table('p_role_permissions')->where('role_id', $role_id)->delete();


		if($permission_ids){

			$data = [];
      
            foreach ($permission_ids as $permission_id) {
                $data[] = [
                    'role_id' => $role_id,
                    'permission_id' => $permission_id
                ];
            } 
        
			$this->db->table('p_role_permissions')->insertBatch($data);
		
		}

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    // 获取权限对应的角色
    public function getPermissionRoles($permission_id)
    {
        return $this->db->table('p_role_permissions')
            ->select('p_roles.*')
            ->join('p_roles', 'p_role_permissions.role_id = p_roles.id')
            ->where('p_role_permissions.permission_id', $permission_id)
            ->get()->getResultArray();
    }

    // 给权限分配角色
    public function addPermissionRole($permission_id, $role_id)
    {
        return $this->db->table('p_role_permissions')->insert([
            'role_id' => $role_id,
            'permission_id' => $permission_id
        ]);
    }

    // 移除权限的角色
    public function removePermissionRole($permission_id, $role_id)
    {
        return $this->db->table('p_role_permissions')
            ->where(['role_id' => $role_id, 'permission_id' => $permission_id])
            ->delete();
    }

      // 获取用户的权限的name集（包含直接权限和角色继承权限）
    public function getUserPermissionNames($user_id)
    {
        $userPermissions = $this->getUserPermissions($user_id);
        $permissionNames = [];
        foreach ($userPermissions as $permission) {
            $permissionNames[] = $permission['name'];
        }


        $userRole = $this->getUserRole($user_id);
        if ($userRole) {
            $permissionNames[] = 'is_'.str_replace(' ', '_', $userRole['name']);
        }
		
		
		$userCapabilities = $this->getCapabilities($user_id);
		


        return array_unique(array_merge($permissionNames, $userCapabilities));
    }
	

	//获取用户的权限的name集from table capabilities
	function getCapabilities($bid){
		
		
		$rows = $this->db->table('capabilities')
            ->select('*')
            ->where('bid', $bid)
            ->get()->getResultArray();
		
		$capabilities = [];
		
		foreach($rows as  $row){
		
				$capabilities[] = $row['capability'];
				
				
				if(stripos($row['capability'],'_delegate')!==false){
					$capabilities['is_delegate'] = 'is_delegate';
					
				}
			
		}
			
		
		return $capabilities;
		
	}	
	


    // 获取用户的权限（包含直接权限和角色继承权限）
    public function getUserPermissions($user_id)
    {
        // 角色继承的权限
        $rolePermissions = $this->db->table('members m')
            ->select('p.*')
            ->join('p_role_permissions rp', 'm.admin = rp.role_id')
            ->join('p_permissions p', 'rp.permission_id = p.id')
            ->where('m.bid', $user_id)
            ->get()->getResultArray();
        // 用户直接权限
        $userPermissions = $this->db->table('p_user_permissions up')
            ->select('p.*, up.allow')
            ->join('p_permissions p', 'up.permission_id = p.id')
            ->where('up.user_id', $user_id)
            ->get()->getResultArray();
        // 合并权限
        $permissions = [];
        foreach ($rolePermissions as $perm) {
            $permissions[$perm['id']] = $perm;
        }
        foreach ($userPermissions as $perm) {
            if ($perm['allow']) {
                $permissions[$perm['id']] = $perm;
            } else {
                unset($permissions[$perm['id']]);
            }
        }
        return array_values($permissions);
    }

    // 给用户分配权限
    public function addUserPermission($user_id, $permission_id, $allow = 1)
    {
        return $this->db->table('p_user_permissions')->replace([
            'user_id' => $user_id,
            'permission_id' => $permission_id,
            'allow' => $allow
        ]);
    }

    //sp_user_update($user_id, $permission_name)
    public function sp_user_update($user_id, $permission_name)
    {
        // First delete the existing capability
        $this->db->table('capabilities')
            ->where(['capability' => $permission_name])
            ->delete();

        // Then insert the new capability
        return $this->db->table('capabilities')->insert([
            'bid' => $user_id,
            'capability' => $permission_name,
            'value' => $user_id
        ]);
    }

    //get_sp_users
    public function get_sp_users($permission_name)
    {
        $result = $this->db->table('capabilities')
            ->select('bid')
            ->where(['capability' => $permission_name])
            ->get()
            ->getRowArray();
            
        return $result ? $result['bid'] : 0;
    }

    // 移除用户的权限
    public function removeUserPermission($user_id, $permission_id)
    {
        return $this->db->table('p_user_permissions')
            ->where(['user_id' => $user_id, 'permission_id' => $permission_id])
            ->delete();
    }


    // 移除用户的所有指定权限
    public function removeAllUserPermission($user_id)
    {
        return $this->db->table('p_user_permissions')
            ->where(['user_id' => $user_id])
            ->delete();
    }

    // 获取角色的权限
    public function getRolePermissions($role_id)
    {
        return $this->db->table('p_role_permissions')
            ->select('p_permissions.*')
            ->join('p_permissions', 'p_role_permissions.permission_id = p_permissions.id')
            ->where('p_role_permissions.role_id', $role_id)
            ->get()->getResultArray();
    }

    // 给角色分配权限
    public function addRolePermission($role_id, $permission_id)
    {
        return $this->db->table('p_role_permissions')->insert([
            'role_id' => $role_id,
            'permission_id' => $permission_id
        ]);
    }

    // 移除角色的权限
    public function removeRolePermission($role_id, $permission_id)
    {
        return $this->db->table('p_role_permissions')
            ->where(['role_id' => $role_id, 'permission_id' => $permission_id])
            ->delete();
    }

    // 获取用户的角色
    public function getUserRole($user_id)
    {
        return $this->db->table('members m')
            ->select('p_roles.id, p_roles.name, p_roles.description')
            ->join('p_roles', 'm.admin = p_roles.id')
            ->where('m.bid', $user_id)
            ->get()->getRowArray();
    }

    // 给用户分配角色
    public function setUserRole($user_id, $role_id)
    {
	    // Remove user permissions
        $this->db->table('p_user_permissions')
            ->where('user_id', $user_id)
            ->delete();	
		
        // 更新 members 表的 admin 字段
        return $this->db->table('members')
            ->where('bid', $user_id)
            ->set('admin', $role_id)
            ->update();
    }

    //removeAllPermissions
    public function removeAllPermissions($user_id)
    {
        // Remove user permissions
        $this->db->table('p_user_permissions')
            ->where('user_id', $user_id)
            ->delete();
        // Remove user roles（将 admin 字段设为 0）
        return $this->db->table('members')
            ->where('bid', $user_id)
            ->set('admin', 0)
            ->update();
    }

    // 移除用户的角色
    public function removeUserRole($user_id, $role_id)
    {
        // 将 admin 字段设为 0
        return $this->db->table('members')
            ->where(['bid' => $user_id, 'admin' => $role_id])
            ->set('admin', 0)
            ->update();
    }

     // Get users with their permissions and roles
    public function getPermissionUsers()
    {
        return $this->db->table('baptism b')
            ->select('b.id, CONCAT(b.fName, " ", b.lName) as user_name, b.email, r.name as role_name, r.id as role_id')
            ->join('p_user_permissions up', 'b.id = up.user_id', 'left')
            ->join('members m', 'b.id = m.bid', 'left')
            ->join('p_roles r', 'r.id = m.admin', 'left')
            ->where('up.user_id IS NOT NULL OR m.admin > 0')
            ->groupBy('b.id')
            ->orderBy('r.id', 'ASC')
            ->get()
            ->getResultArray();
    }

    //getNoPermissionUsers
    public function getNoPermissionUsers($query)
    {
        $subquery = $this->db->table('baptism b')
            ->select('b.id, CONCAT(b.fName, " ", b.lName) as name, b.email')
            ->join('members m', 'b.id = m.bid')
            ->where('m.status >', 0)
            ->where('b.id NOT IN (
                SELECT DISTINCT up.user_id 
                FROM p_user_permissions up
                UNION
                SELECT DISTINCT m.bid
                FROM members m
                WHERE m.admin > 0
            )');

        if (!empty($query)) {
            $subquery->groupStart()
                ->like('b.fName', $query)
                ->orLike('b.lName', $query)
                ->orLike('b.email', $query)
                ->groupEnd();
        }

        return $subquery->get()->getResultArray();
    }

    /**
     * 获取指定用户的权限和角色信息
     */
    public function getPermissionUserById($uid)
    {
        return $this->db->table('baptism b')
            ->select('b.id, CONCAT(b.fName, " ", b.lName) as user_name, b.email, r.name as role_name, r.id as role_id')
            ->join('p_user_permissions up', 'b.id = up.user_id', 'left')
            ->join('members m', 'b.id = m.bid', 'left')
            ->join('p_roles r', 'r.id = m.admin', 'left')
            ->where('b.id', $uid)
            ->get()
            ->getRowArray();
    }


    //获取特定组的权限ids
    public function getRolePermissionIds($role_id)
    {
        $result = $this->db->table('p_role_permissions')
            ->select('p_permissions.id')
            ->join('p_permissions', 'p_role_permissions.permission_id = p_permissions.id')
            ->where('p_role_permissions.role_id', $role_id)
            ->get()
            ->getResultArray();
            
        return array_map(function($item) {
            return (int)$item['id'];
        }, $result);
    }


    // Get permission ID by name from p_permissions table
    public function getPermissionIdByName($name)
    {
        $result = $this->db->table('p_permissions')
            ->select('id')
            ->where('name', $name)
            ->get()
            ->getRowArray();
        return $result ? $result['id'] : null;
    }


    // Get role IDs that have the specified permission
    public function getRoleIdsByPermissionId($permission_id) 
    {
        $result = $this->db->table('p_role_permissions')
            ->select('role_id')
            ->where('permission_id', $permission_id)
            ->get()
            ->getResultArray();

        return array_map(function($item) {
            return (int)$item['role_id']; 
        }, $result);
    }


      // Get users with their permissions and roles where b.id in ...
      public function getPermissionUsersByRoleIds($role_ids)
      {
          return $this->db->table('baptism b')
              ->select('b.id,b.id as bid, CONCAT(b.fName, " ", b.lName) as name, b.email')
              ->join('members m', 'b.id = m.bid', 'left')
              ->join('p_roles r', 'r.id = m.admin', 'left')
              ->whereIn('r.id', $role_ids)
              ->get()
              ->getResultArray();
      }   
            
 
      // 根据 $permission_id 获取在p_user_permissions有数据的用户
      public function getPermissionUsersByPermission($permission_id)
      {
          return $this->db->table('baptism b')
              ->select('b.id,b.id as bid, CONCAT(b.fName, " ", b.lName) as name, b.email, up.allow')
              ->join('p_user_permissions up', 'b.id = up.user_id', 'left')
              ->where('up.permission_id', $permission_id)
              ->get()
              ->getResultArray();
      }     

    //找到有pto_apply权限的组下的所有用户
    public function getPtoUsers()
    {
        return $this->getPermittedUsers('pto_apply');

    }
	
	
	
	public function getPermittedUsers(string $permissionDescription): array
{
    $users = [];


	$permission_id = $this->getPermissionIdByName($permissionDescription);
	if(!$permission_id) return $users;

	$role_ids = $this->getRoleIdsByPermissionId($permission_id);

	
	$users_1 = $role_ids ? $this->getPermissionUsersByRoleIds($role_ids):[];
	$users_2 = $this->getPermissionUsersByPermission($permission_id);

	

	foreach($users_1 as $k=>$v){
		$users[$v['id']] = $v;
	}

	foreach($users_2 as $k=>$v){
		if($v['allow'] == 1){
			$users[$v['id']] = $v;
		}else{
			unset($users[$v['id']]);
		}            
	}

	
    return $users;
}


    //getPtoUsersUpdateSchedule
    public function getPtoUsersUpdateSchedule()
    {
        $users = $this->getPtoUsers();
        $users_ids = array_column($users, 'bid' );
		
		if(!$users_ids) return [];

        return $this->db->table('pto_relation')
            ->select('pto_relation.*')
            ->whereIn('pto_relation.bid', $users_ids)
			->where('pto_relation.`update_schedule` is not null')
			->where('pto_relation.`ft_hire` is not null ')
			->where('pto_relation.`update_schedule` < '.time())
            ->get()
            ->getResultArray();
        
    }
	
	


    public function getHierarchyPastors()
    {


        $result = $this->query("SELECT b.id,CONCAT(b.`fName`,' ',b.`lName`) as name,b.site as campus, pr.supervisor from baptism b JOIN members m on m.bid = b.id left JOIN pto_relation pr on pr.bid = b.id WHERE m.admin =3")
            ->getResultArray();
			
		dd($result);	
		return $this->buildHierarchy($result,0);
        

        
    }	
	

	
	
	function buildHierarchy(array $elements, $parentId = 0) {
    $branch = [];

    foreach ($elements as $element) {
        if ($element['supervisor'] == $parentId) {
            // Recursively process children first
            $children = $this->buildHierarchy($elements, $element['id']);

            $subordinateCities = [];
            if ($children) {
                // Attach children to the current element
                $element['children'] = $children;

                // Collect cities from direct children after their own aggregation
                foreach ($children as $child) {
                    // If the child's city is a string, add it directly
                    // If it's an array (from aggregated children), merge it
                    if (is_string($child['campus'])) {
                        $subordinateCities[] = $child['campus'];
                    } elseif (is_array($child['campus'])) {
                        $subordinateCities = array_merge($subordinateCities, $child['campus']);
                    }
                }
            }

            // Ensure the current node's city is treated as an array for aggregation
            $nodeCurrentCities = is_string($element['campus']) ? [$element['campus']] : $element['campus'];

            // Combine unique cities (current node's cities + all subordinate cities)
            $element['campus'] = array_values(array_unique(array_merge($nodeCurrentCities, $subordinateCities)));

            // If after aggregation, the city array has only one element, convert it back to a string
            if (count($element['campus']) === 1) {
                $element['campus'] = $element['campus'][0];
            }

            $branch[] = $element;
        }
    }

    return $branch;
}

    //get subordinates base on pto_relation
    public function getSubordinates($bid)
    {
        $result = $this->db->table('pto_relation')
            ->select('bid')
            ->where('supervisor', $bid)
            ->get()
            ->getResultArray();

            return $result ? array_column($result,'bid') : [];
    }


 


    
}