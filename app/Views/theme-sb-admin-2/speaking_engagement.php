
 <!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	 
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Please confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  
		  <div class="modal-body">
			...
		  </div>
		  
		  <div class="form-group form-group-reasons  p-3"><label  class="form-label">If decline, please provide reasons:</label><textarea required class="form-control" id="reasons" name="reasons" rows="3"></textarea>  <div class="invalid-feedback">       Please enter a valid value.      </div></div>
			 <p class=" p-3" style="color:red;font-size: 18px;padding-top: 10px;" id="fmsg"></p>  
      <div class="modal-footer">
		
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

		<button type="submit" data-post_id="" data-action="accept" class="btn btn-success btn-accept btn-icon-split"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Accept</span></button>	
		<button type="submit" data-post_id="" data-action="decline" class="btn btn-warning btn-decline btn-icon-split"><span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Decline</span></button> 
	   
      </div>
	  
	  	
	 
    </div>
  </div>
</div>	


                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Speaking Engagement</h1>
					
					<?php if(isset($userCaps['is_senior_pastor'])): ?>
						<a href="<?= (base_url('xAdmin/speaking_engagement/details/assign')); ?>" class="btn btn-danger  btn-icon-split mb-4">
							<span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Assign a pastor to an speaking engagement</span>
						</a>
					<?php elseif(isset($userCaps['is_pastor'])): ?>
						<a href="<?= (base_url('xAdmin/speaking_engagement/details/apply')); ?>" class="btn btn-danger  btn-icon-split mb-4">
							<span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Apply an speaking engagement</span>
						</a>					
					<?php endif; ?>


				<?php if(isset($pending_from_pastor_entries) && $pending_from_pastor_entries): ?>
                    <div class="card shadow mb-4">
					
						<div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pending replies from pastors</h6>
                        </div>
					
                        <div class="card-body"> 
						
                           <div class="table-responsive"> 
						   
                                <table class="table table-bordered dataTable" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Speaking Date/time</th>
											<th>Pastor</th>
                                            <th>Venue</th>
                                            <th>Status</th>
                                            <th>Request sent</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                              
                                    <tbody> 
								  
								  <?php foreach($pending_from_pastor_entries as $row): ?>
								  
								  
                                        <tr>
                                            <td><?= (date('m/d/Y',$row['speaking_start_datetime'])); ?></td>
                                            <td><?= ($row['assigned_name']); ?></td>
                                            <td><?= ($row['venue']); ?></td>
                                            <td><?= ($row['status']); ?></td>
                                            <td><?= (date('m/d/Y',$row['request_timestamp'])); ?></td>
                                            <td><a href="<?= (base_url('xAdmin/speaking_engagement/details/'.$row['id'])); ?>" class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a></td>
                                        </tr>
										
									  <?php endforeach; ?>		
										
                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					  <?php endif; ?>		


				<?php if(isset($user_apply_entries) && $user_apply_entries): ?>
                    <div class="card shadow mb-4">
					
						<div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pending replies from senior pastor</h6>
                        </div>
					
                        <div class="card-body"> 
						
                           <div class="table-responsive"> 
						   
                                <table class="table table-bordered dataTable" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Speaking Date/time</th>
                                            <th>Venue</th>
                                            <th>Status</th>
                                            <th>Request sent</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                              
                                    <tbody> 
								  
								  <?php foreach($user_apply_entries as $row): ?>
								  
								  
                                        <tr>
                                            <td><?= (date('m/d/Y',$row['speaking_start_datetime'])); ?></td>
                                           
                                            <td><?= ($row['venue']); ?></td>
                                            <td><?= ($row['status']); ?></td>
                                            <td><?= (date('m/d/Y',$row['request_timestamp'])); ?></td>
                                            <td><a href="<?= (base_url('xAdmin/speaking_engagement/details/'.$row['id'])); ?>" class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a></td>
                                        </tr>
										
									  <?php endforeach; ?>		
										
                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					  <?php endif; ?>		






				<?php  if(isset($assign_entries) && $assign_entries): ?>
                    <div class="card shadow mb-4">
					
						<div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Waiting for your reply</h6>
                        </div>
					
                        <div class="card-body"> 
						
                           <div class="table-responsive"> 
						   
                                <table class="table table-bordered dataTable" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Speaking Date/time</th>
                                            <th>Requested by</th>
                                            <th>Venue</th>
                                            <th>Accept/Decline</th>
											<th>Request sent</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                              
                                    <tbody> 
								  
								  <?php foreach($assign_entries as $row): ?>
								  
								  
                                        <tr>
                                             <td><?= (date('m/d/Y',$row['speaking_start_datetime'])); ?></td>
                                             <td><?= ($row['requester_name']); ?></td>
                                            <td><?= ($row['venue']); ?></td>
                                            <td>
												<a data-toggle="modal" data-target="#confirmModal" data-post_id="<?= $row['id']; ?>" data-action="accept" class="btn btn-success btn-circle "><i class="fas fa-check"></i></a>
												<a data-toggle="modal" data-target="#confirmModal" data-post_id="<?= $row['id']; ?>" data-action="decline" class="btn btn-warning btn-circle "><i class="fas fa-exclamation-triangle"></i></a>
											</td>
                                            <td><?= (date('m/d/Y',$row['request_timestamp'])); ?></td>
                                            <td><a href="<?= (base_url('xAdmin/speaking_engagement/details/'.$row['id'])); ?>" class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a></td>
                                        </tr>
										
									  <?php endforeach; ?>		
										
                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					  <?php endif; ?>


				<?php if(isset($waiting_spastor_entries) && $waiting_spastor_entries): ?>
                    <div class="card shadow mb-4">
					
						<div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Waiting for your reply</h6>
                        </div>
					
                        <div class="card-body"> 
						
                           <div class="table-responsive"> 
						   
                                <table class="table table-bordered dataTable" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Speaking Date/time</th>
                                            <th>Requested by</th>
                                            <th>Venue</th>
                                            <th>Accept/Decline</th>
											<th>Request sent</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                              
                                    <tbody> 
								  
								  <?php foreach($waiting_spastor_entries as $row): ?>
								  
								  
                                        <tr>
                                             <td><?= (date('m/d/Y',$row['speaking_start_datetime'])); ?></td>
                                             <td><?= ($row['requester_name']); ?></td>
                                            <td><?= ($row['venue']); ?></td>
                                            <td>
												<a data-toggle="modal" data-target="#confirmModal" data-post_id="<?= $row['id']; ?>" data-action="accept" class="btn btn-success btn-circle "><i class="fas fa-check"></i></a>
												<a data-toggle="modal" data-target="#confirmModal" data-post_id="<?= $row['id']; ?>" data-action="decline" class="btn btn-warning btn-circle "><i class="fas fa-exclamation-triangle"></i></a>
											</td>
                                            <td><?= (date('m/d/Y',$row['request_timestamp'])); ?></td>
                                            <td><a href="<?= (base_url('xAdmin/speaking_engagement/details/'.$row['id'])); ?>" class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a></td>
                                        </tr>
										
									  <?php endforeach; ?>		
										
                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					  <?php endif; ?>	
					  
					  
					  

				
                    <div class="card shadow mb-4">
					
						<div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">All previous speaking engagement</h6>
                        </div>
					
                        <div class="card-body"> 
						<?php if(isset($all_previous) && $all_previous): ?>
                           <div class="table-responsive"> 
						   
                                <table class="table table-bordered dataTable" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Speaking Date/time</th>
                                            <th>Pastor</th>
                                            <th>Venue</th>
                                            <th>Status</th>
                                            <th>Requested by</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                              
                                    <tbody> 
								  
								  <?php foreach($all_previous as $row): ?>
								  
								  
                                        <tr>
                                             <td><?= (date('m/d/Y',$row['speaking_start_datetime'])); ?></td>
                                             <td><?= ($row['assigned_name']); ?></td>
                                            <td><?= ($row['venue']); ?></td>
                                            <td><?= ($row['status']); ?></td>
                                             <td><?= ($row['requester_name']); ?></td>
                                            <td><a href="<?= (base_url('xAdmin/speaking_engagement/details/'.$row['id'])); ?>" class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a></td>
                                        </tr>
										
									  <?php endforeach; ?>		
										
                               
                                    </tbody>
                                </table>
                            </div>
							
							<?php else: ?>	
							
							<p>N/A</p>
							
							<?php endif; ?>	
                        </div>
						
                    </div>
					





<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">	
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'); ?>"></script>


<script>

// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('.dataTable').DataTable();  
});



 
	$('#confirmModal').on('show.bs.modal', function (event) {
		
		
		$('#fmsg').html('');
		var modal = $(this);
		var button = $(event.relatedTarget);
		var action = button.data('action');
		var post_id = button.data('post_id');
		var url = '<?php echo base_url("xAdmin/speaking_engagement/details/0"); ?>';
		
		
		if(action=='accept'){			
			modal.find('.btn-decline').hide();
			modal.find('.btn-accept').data('post_id',post_id).show();
		}else{
			modal.find('.btn-accept').hide();			
			modal.find('.btn-decline').data('post_id',post_id).show();			
		}
			
		
		// console.log(modal.find('.btn-decline').data('post_id'));
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: {	  post_id:post_id, action:'confirm'},
				success:function(data){
							

					modal.find('.modal-body').html(data);
					

					
							
				}
			});
			
 
			
			
			
			
	});
	
	
$( document ).on( "click", ".btn-decline,.btn-accept", function() {
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 	
	$('#fmsg').html('');	
	
	var post_id = $(this).data('post_id');
	var action = $(this).data('action');
	var reasons = $('#reasons').val();
	var url = '<?php echo base_url("xAdmin/speaking_engagement/details/0"); ?>';
	
	if(action=='decline'&&!reasons){
		$('#fmsg').html('please provide reasons');
		return;
	}
	 
	timer = setTimeout(function() {
		$('#fmsg').html('processing...');
		
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: url,
			data: {	post_id: post_id, reasons: reasons, action:action,confirmed:1},     
			success:function(data){  
				if(data=='OK'){ 
					$('#fmsg').html('Saved');
					 location.reload();
				}				
			}
		});
		
	 },300); 
	 
	 
	 
});	
	
</script>

 