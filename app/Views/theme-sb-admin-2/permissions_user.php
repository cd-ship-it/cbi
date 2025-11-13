
<div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">User Permissions Management</h3>
        <a href="<?= base_url('xAdmin/permission') ?>" class="btn btn-secondary">&larr; Back</a>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="user-info mb-3">
                <h5>User ID: <?= $user['id'] ?></h5>
                <h5>Username: <?= htmlspecialchars($user['user_name'] ?? 'Unset') ?></h5>
                <h5>Email: <?= htmlspecialchars($user['email'] ?? 'Unset') ?></h5>
                <h5>Role: <?= htmlspecialchars($user['role_name'] ?? 'Unset') ?></h5>

                <?php if(in_array('pto_apply', array_column($user_permissions, 'name'))): ?>
                    <hr />
                    <h5>Day of FT Hire: <a href="<?= base_url('xAdmin/baptist/' . $user['id'].'#pto_options') ?>" class=""><?= $day_of_ft_hire; ?></a></h5>
                    <h5>Supervisor: <a href="<?= base_url('xAdmin/baptist/' . $user['id'].'#pto_options') ?>" class=""><?= $user_supervisor; ?></a></h5>
               
                <?php endif; ?>   
            </div>
        </div>
    </div>








    <div class="card">
        <div class="card-header">Assign/Remove Permissions</div>
        <div class="card-body">
            <form method="post" action="<?= base_url('xAdmin/permission/' . $user['id']) ?>">
                <div class="form-group">
                   
                    <div>
                        <?php foreach ($all_permissions as $perm): ?>
                            <div class="permission-group mb-2 d-flex align-items-center">
                                <label class="font-weight-bold mb-0 mr-3" style="min-width:180px;"><?= htmlspecialchars(!empty($perm['description']) ? $perm['description'] : $perm['name']) ?>:</label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-success <?php if (in_array($perm['id'], array_column($user_permissions, 'id'))) echo 'active'; ?>">
                                        <input type="radio" name="permissions[<?= $perm['id'] ?>]" value="1" <?php if (in_array($perm['id'], array_column($user_permissions, 'id'))) echo 'checked'; ?>>
                                        Allow <?php if (in_array($perm['id'], array_column($user_role_permissions, 'id'))): ?><span class="">(role default)</span><?php endif; ?>
                                    </label>
                                    <label class="btn btn-outline-danger <?php if (!in_array($perm['id'], array_column($user_permissions, 'id'))) echo 'active'; ?>">
                                        <input type="radio" name="permissions[<?= $perm['id'] ?>]" value="0" <?php if (!in_array($perm['id'], array_column($user_permissions, 'id'))) echo 'checked'; ?>>
                                        Disallow <?php if (!in_array($perm['id'], array_column($user_role_permissions, 'id'))): ?><span class="">(role default)</span><?php endif; ?>
                                    </label>
                                </div>
								
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input type="hidden" name="action" value="saveUserPermissions" /> 
                <button id="saveUserPermissions" type="submit" class="btn btn-primary">Save Permissions </button> <small class="msg"></small>
            </form>
        </div>
    </div>

<script>


$(function(){
 
    $('#saveUserPermissions').click(function(e){
        e.preventDefault();
        
        // Get form data
        var formData = $('form').serialize();
		$('.msg').html('Loading...');
        
        // Send AJAX request
        $.ajax({
            url: $('form').attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'text',
			success:function(data){   
				$('.msg').html(data);	
			}
        });
    });
 
});


</script>