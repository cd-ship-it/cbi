<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">User Permissions Management</h3>
    <div>
        <button class="btn btn-primary mr-2" id="addUserBtn">Add User</button>
        <button class="btn btn-primary" id="addGroupBtn">Add Role</button>
    </div>
</div>

<ul class="nav nav-tabs" id="permissionTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="groups-tab" data-toggle="tab" href="#groups" role="tab" aria-controls="groups" aria-selected="false">Roles</a>
    </li>
</ul>

<div class="tab-content" id="permissionTabContent">
    <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
        <div class="mt-3">
            <table class="table table-borderless table-striped" style="background-color: #fff;">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php 
                    foreach($permissionUsers as $user):
                    if($user['role_name'] == 'Hidden'){
                        continue;
                    }
                        ?>
                    <tr>
                        <td><a href="<?= base_url('xAdmin/baptist/'.$user['id']) ?>"><?= esc($user['user_name']) ?></a></td>
                        <td>
                            <select class="form-control form-control-sm user-role-select" data-user-id="<?= $user['id'] ?>">
								<option value="0">unset</option>
                                <?php foreach($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>" <?= $user['role_id'] == $role['id'] ? 'selected' : '' ?>><?= esc($role['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger"></small>
                        </td>
                        <td>
                            <a href="<?= base_url('xAdmin/permission/'.$user['id']); ?>" class="btn btn-sm btn-warning edit-user" data-id="<?= $user['id'] ?>">Edit Permissions</a>
                            <button class="btn btn-sm btn-danger delete-user" data-id="<?= $user['id'] ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


        


<div id="spUsers" style=" margin: 0 10px 20px 10px; margin: 0 10px 20px 10px; border-top: dashed 1px #ccc; padding-top: 20px; line-height: 24px;">

<p>Senior Pastor (Interim):

<select name="senior_pastor" title="Senior Pastor" data-mkey="is_senior_pastor" class="form-control form-control-sm sp_user_update" id="senior_pastor">
<option value="">---</option>

<?php 

foreach($permissionUsers as $user){
                  
   $is_selected =    $user['id']==$senior_pastor   ?'selected':'' ;
   echo '<option  '.$is_selected.'  value="'.$user['id'].'">'.$user['user_name'].'</option>';
}

?>




</select>

<span></span>
</p>  

<p>Office Director:

<select name="office_director" title="Office Director" data-mkey="is_office_director" class="form-control form-control-sm sp_user_update" id="office_director">
<option value="">---</option>

<?php 

foreach($permissionUsers as $user){
                  
   $is_selected =  ($office_director == $user['id'] ?'selected':'') ;
   echo '<option  '.$is_selected.'  value="'.$user['id'].'">'.$user['user_name'].'</option>';
}

?>




</select>

<span></span>
</p> 

<p>Office Assistant:

<select name="office_assistant" title="Office Assistant" data-mkey="is_office_assistant" class="form-control form-control-sm sp_user_update" id="office_assistant">
<option value="">---</option>

<?php 

foreach($permissionUsers as $user){
                  
   $is_selected =  ($office_assistant == $user['id'] ?'selected':'') ;
   echo '<option  '.$is_selected.'  value="'.$user['id'].'">'.$user['user_name'].'</option>';
}

?>




</select>	
<span></span>
</p>	



<p>BOT  Chair:

<select name="bot_chair" title="BOT  Chair" data-mkey="is_bot_chair" class="form-control form-control-sm sp_user_update" id="bot_chair">
<option value="">---</option>

<?php 

foreach($permissionUsers as $user){
                  
   $is_selected =  ($bot_chair == $user['id'] ?'selected':'') ;
   echo '<option  '.$is_selected.'  value="'.$user['id'].'">'.$user['user_name'].'</option>';
}

?>




</select>	
<span></span>
</p>


</div>


 



        </div>
    </div>
 
	
    <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="groups-tab">
        <div class="mt-3">
            <table class="table table-borderless table-striped" style="background-color: #fff;">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="groupTableBody">
                    <?php  foreach($roles as $role):  ?>
                    <tr>
                        <td><?= esc($role['name']) ?></td>
                        <td>
                            <div class="permission-list">
                                <?php foreach($permissions as $perm): ?>
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input permission-checkbox" 
                                           id="perm_<?= $role['id'] ?>_<?= $perm['id'] ?>"
                                           data-role-id="<?= $role['id'] ?>"
                                           data-perm-id="<?= $perm['id'] ?>"
                                           <?= in_array($perm['id'], explode(',', $role['permissions'])) ? 'checked' : '' ?>>
                                     <label class="form-check-label" for="perm_<?= $role['id'] ?>_<?= $perm['id'] ?>">    
                                        <?= $perm['description'] ? esc($perm['description']) : esc($perm['name']) ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </td>       
                        <td>    
                            <button class="btn btn-sm btn-success save-group" data-id="<?= $role['id'] ?>">Save</button>
                            <button class="btn btn-sm btn-danger delete-group" data-name="<?= $role['name'] ?>" data-id="<?= $role['id'] ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

	        </div>
    </div>


  
    </div>
 

<!-- Add Group Modal -->
<div class="modal fade" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="addGroupModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addGroupModalLabel">Add Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control mb-2" id="groupNameInput" placeholder="Role name">
        <textarea class="form-control" id="groupDesInput" rows="5" placeholder="Role description"></textarea>
      </div>    
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirmAddGroupBtn">Add</button>
      </div>
    </div>
  </div>
</div>




<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content" style="text-align:center; padding:30px 0;">
      <div class="modal-body">
        <div class="spinner-border text-primary mb-2" role="status">
          <span class="sr-only">Loading...</span>
        </div>
        <div id="loadingModalLabel">Loading...</div>
      </div>
    </div>
  </div>
</div>




<script>



$(function(){ 
    $('#addGroupBtn').attr('data-toggle','modal').attr('data-target','#addGroupModal');
	
	
	

var searchWrap;
$( document ).on( "click", "#addUserBtn", function() {
	
	if(typeof(searchWrap) != "undefined" && searchWrap !== null) {
			return;
	}
	
		
	var input = document.createElement('input');
	input.placeholder="name/email";
	
	var msg = document.createElement('p');
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Search User';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	
	
	searchWrap = document.createElement('div');
	searchWrap.id = 'addAdminDiv';
	
	
	searchWrap.appendChild(btC);
	searchWrap.appendChild(input);
	searchWrap.appendChild(btS);
	searchWrap.appendChild(msg);
	searchWrap.appendChild(lists);
	
	
	document.getElementsByTagName("BODY")[0].appendChild(searchWrap);
	
	
	
	btC.addEventListener('click', function(){
		 close();
	});

	btS.addEventListener('click', function(){
		var query = input.value;
		
				$.ajax({
					dataType:'json',
					method: "POST",
					url: '<?php echo  $canonical; ?>',
					data: {	query:query, action:'searchUser'},      
					success:function(data){ 
						
						msg.innerHTML = '';
						lists.innerHTML = '';
						
						if(data.length==0){
							msg.innerHTML = 'Did not match any documents.';
						}else{
							
							
							data.forEach(function(item){
								var p2 = '<a href="javascript:void(0);" data-name="'+item.name+'" data-uid="'+item.id+'" class="join function">+Add</a>';
								var li = document.createElement('li'); 
								li.innerHTML = item.name+' '+p2;
								
								lists.appendChild(li);
							});
							
							
						}	

						
					}
				});			
		
	});		
	
	function close(){
		searchWrap.remove();
		searchWrap = undefined;
	}	
});


$( document ).on( "click", ".join", function() {
	

	

	var uid = $(this).data('uid');
 
	
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo  $canonical; ?>',
			data: { permission_id:1,user_id: uid, action:'addUserPermission'},     
			success:function(data){   
				if(data='Success'){ 
					 location.reload();
				}				
			}
		});
		

	 
	 
	 
});



$( document ).on( "change", "select.user-role-select", function() {
	

	

	var user_id = $(this).data('user-id');
	var role_id =  $(this).val();
	

	
	var sm = $(this).siblings('small');

    $(sm).text('');
	 
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { user_id: user_id, role_id: role_id, action:'setUserRole'},     
			success:function(data){   
				if(data=='Updated'){  

					$(sm).text('Updated');
                    
				}				
			}
		});
		

	 
	 
	 
});


$( document ).on( "click", ".delete-user", function() {
	

	

	var r = confirm("Please press 'OK' to continue");
	var url = '<?php echo  $canonical; ?>';	
	
	var user_id = $(this).data('id');	 
	
	if(r){
		
			$('#loadingModal').modal('show');
		
			$.ajax({
				dataType:'text',
				method: "POST",
				url: url,
				data: { user_id: user_id, action:'removeAllPermissions'},      
				success:function(data){		
					if(data=='Success'){ 
						
						$('#loadingModal').modal('hide');
						location.reload();
					}											
				}
			});
	}	
});



$( document ).on( "change", "select.sp_user_update", function() {
	

	
	var user_id =  $(this).val();
	var permission_name =  $(this).data('mkey');
	
	
	$('#loadingModal').modal('show');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: {  user_id: user_id, permission_name: permission_name, action:'sp_user_update'},     
			success:function(data){   
				if(data=='Success'){ 
					$('#loadingModal').modal('hide');
				}else{
					console.log(data);
				}				
			}
		});
		

	 
	 
	 
});

 

	
	
	
    $('.user-role-select').change(function(){
        var userId = $(this).data('user-id');
        var roleId = $(this).val();
        // Ajax request to update user group
        // ...
    });
 
    $('#confirmAddUserBtn').click(function(){
        // Ajax add selected user
        // ...
    });


    $('#confirmAddGroupBtn').click(function(){
        var groupName = $('#groupNameInput').val();
        var groupDes = $('#groupDesInput').val();

        if(!groupName) {
            alert('Please enter a role name');
            return;
        }

        $.ajax({
            dataType: 'text',
            method: 'POST', 
            url: '<?php echo $canonical; ?>',
            data: {
                role_name: groupName,
                role_des: groupDes,
                action: 'addRole'
            },
            success: function(response) {
                if(response == 'Success') {
                    $('#addGroupModal').modal('hide');
                    location.reload();
                }
            },
            error: function() {
                alert('Error occurred while adding role');
            }
        });
    });


    $('.save-group').click(function(){
        var roleId = $(this).data('id');
        var $row = $(this).closest('tr');
        var checkedPerms = [];
        $row.find('.permission-checkbox:checked').each(function(){
            checkedPerms.push($(this).data('perm-id'));
        });
        $('#loadingModal').modal('show');
        $.ajax({
            dataType: 'text',
            method: 'POST',
            url: '<?php echo $canonical; ?>',
            data: {role_id: roleId, permission_ids: checkedPerms, action: 'setRolePermissions'},
            success: function(response) {
              
                if(response == 'Success') {
                    $('#loadingModal').modal('hide');
                } 
            },
            error: function() {
                $('#loadingModal').modal('hide');
                alert('Error occurred while saving permissions');
            }
        });
    });
});
$(document).on('click', '.delete-group', function() {
    var $row = $(this).closest('tr');
    var roleId =$(this).data('id');
 
    var roleName = $(this).data('name');
    if (!confirm('Are you sure you want to delete this role: '+roleName+'?')) {
        return;
    }
    
    $('#loadingModal').modal('show');
    $.ajax({
        url: '<?php echo $canonical; ?>',
        type: 'POST',
        data: { role_id: roleId,action:'deleteRole' },
        success: function(response) {

            if(response == 'Success'){
                $('#loadingModal').modal('hide');
                 location.reload();
            } else{
                alert('Failed to delete role.');
            }
        },
        error: function(xhr) {
            $('#loadingModal').modal('hide');
            alert('Failed to delete role.');
        }
    });
	
	
	
	

});
 


</script>

 <script>
    document.querySelectorAll('.modal').forEach(modal => {
      modal.addEventListener('hide.bs.modal', () => {
        document.activeElement.blur();
      });
    });
  </script>

<style>
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f9f9f9;
}
.table-borderless th, .table-borderless td, .table-borderless thead th, .table-borderless tbody + tbody {
    border: 0;
}

#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;z-index:9999;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}
</style>