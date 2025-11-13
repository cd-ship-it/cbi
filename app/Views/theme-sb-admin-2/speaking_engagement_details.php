
 


                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Speaking Engagement Application</h1>


<!-- Begin Page Content -->
                <div class="container-fluid">
				
				
				
				
 

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

		<button type="submit" data-post_id="<?= $post_id; ?>" data-action="accept" class="btn btn-success btn-accept btn-icon-split"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Accept</span></button>	
		<button type="submit" data-post_id="<?= $post_id; ?>" data-action="decline" class="btn btn-warning btn-decline btn-icon-split"><span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Decline</span></button> 
	   
      </div>
	  
	  	
	 
    </div>
  </div>
</div>			
				
				
				
				
				
				
				
				<?= (isset($ptoInformation)?$ptoInformation:''); ?>
				
				<?php if($post['status']=='accepted'):   ?>
				
					<div class="card mb-4 py-3 border-left-success">
                                <div class="card-body">
                                   <p>Last response at: <?= (date('m/d/Y g:i a',$post['last_response_timestamp'])); ?></p>
								   <p>Status: <button disabled class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text"><?= ($post['status']); ?></span></button></p>
								    <p>Reason: <?= ($post['reason']?$post['reason']:'N/A'); ?></p>
                                </div>
					</div>	
					
				<?php elseif($post['status']=='declined'):   ?>
				
					<div class="card mb-4 py-3 border-left-warning">
                                <div class="card-body">
                                   <p>Last response at: <?= (date('m/d/Y g:i a',$post['last_response_timestamp'])); ?></p>
								   <p>Status: <button disabled class="btn btn-warning  btn-icon-split"><span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text"><?= ($post['status']); ?></span></button></p>
								   <p>Reason: <?= ($post['reason']?$post['reason']:'N/A'); ?></p>
                                </div>
					</div>
					
				<?php elseif( $post['requester_id'] == $post['assigned_id'] && !isset($userCaps['is_senior_pastor']) ):   ?>	
				
					<div class="card mb-4 py-3 border-left-primary">
                                <div class="card-body">
                                   <p>Waiting for senior pastor's reply</p>
									
                                </div>
					</div>		
					
				
				
				<?php elseif( $post['requester_id'] != $post['assigned_id'] &&  $logged_id != $post['assigned_id'] ):   ?>	
				

					<div class="card mb-4 py-3 border-left-primary">
                                <div class="card-body">
                                   <p>Waiting for <?= $post['assigned_name']; ?>'s reply</p>
									
                                </div>
					</div>		
					
				<?php else:   ?>	
				
				
				
					<div class="card mb-4 py-3 border-left-primary">
                                <div class="card-body">
                                   <p>Waiting for your reply: </p>
								   
									<p>
									<button data-toggle="modal" data-target="#confirmModal" data-post_id="<?= $post_id; ?>" data-action="accept" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Accept</span></button>
									<button data-toggle="modal" data-target="#confirmModal" data-post_id="<?= $post_id; ?>" data-action="decline" class="btn btn-warning  btn-icon-split"><span class="icon text-white-50"><i class="fas fa-exclamation-triangle"></i></span><span class="text">Decline</span></button>
									</p>
									
                                </div>
					</div>				
				
				
				<?php endif; ?>
				

                    <!-- Page Heading -->
                     <form  method="POST"  class="needs-validation ajaxSubmit" novalidate>
					 
					 
                        <div class="form-group">
                         <label  class="form-label">Requester</label>
                          <input type="text" class="form-control" disabled value="<?= ($post['requester_name']); ?>">
                        </div>			
						
                        <div class="form-group">
                         <label  class="form-label">Request on</label>
                          <input type="text" class="form-control" disabled value="<?= (date('m/d/Y',$post['request_timestamp'])); ?>">
                        </div>	
						
                        <div class="form-group">
                         <label  class="form-label">Pastor</label>
                          <input type="text" class="form-control" disabled value="<?= ($post['assigned_name']); ?>">
                        </div>						
						
						
                        <div class="form-group">
                         <label  class="form-label">Speaking Date</label>
                          <input type="text" class="form-control" disabled value="<?= (date('m/d/Y',$post['speaking_start_datetime'])); ?>">
                        </div>
						
                        <div class="form-group">
                         <label  class="form-label">Speaking Start time</label>
                          <input type="text" class="form-control" disabled value="<?= (date('g:i a',$post['speaking_start_datetime'])); ?>">
                        </div>	
						
                        <div class="form-group">
                         <label  class="form-label">Speaking End time</label>
                          <input type="text" class="form-control" disabled value="<?= (date('g:i a',$post['speaking_end_datetime'])); ?>">
                        </div>					 
					 	
						
                        <div class="form-group">
                         <label  class="form-label">Venue</label>
                          <input type="text" class="form-control" disabled value="<?= ($post['venue']); ?>">
                        </div>					 
					 	
						
                        <div class="form-group">
                         <label  class="form-label">Address</label>
                          <input type="text" class="form-control" disabled value="<?= ($post['address']); ?>">
                        </div>						 
					 	
						
                        <div class="form-group">
                         <label  class="form-label">Venue contact person name, phone and/or email</label>
                          <input type="text" class="form-control" disabled value="<?= ($post['contact_info']); ?>">
                        </div>					 
					 
					 
                        <div class="form-group">
                          <label  class="form-label">Notes</label>
                          <textarea class="form-control" disabled rows="3"><?= ($post['note']); ?></textarea>
                        </div>						

						
						<a href="<?= (base_url('xAdmin/speaking_engagement')); ?>" class="btn btn-secondary " >Cancel</a>
						
								<?php if($webConfig->checkPermissionByDes('management') && $post['status_id'] == 3 ): ?>
									<br /><br />
									<a id="remove-post" class="badge badge-danger" href="javascript: void(0);">x Remove Post</a>
								<?php endif; ?>				 				
							
                      </form>
 

					
                 
                </div>
                <!-- /.container-fluid -->




<script type="text/javascript">
	$('#confirmModal').on('show.bs.modal', function (event) {
		
		
		$('#fmsg').html('');
		var modal = $(this);
		var button = $(event.relatedTarget);
		var action = button.data('action');
		var post_id = button.data('post_id');
		
		if(action=='accept'){			
			modal.find('.btn-decline').hide();
			modal.find('.btn-accept').show();
		}else{
			modal.find('.btn-accept').hide();			
			modal.find('.btn-decline').show();			
		}
			
		
		
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		$.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
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
	 
	
	if(action=='decline'&&!reasons){
		$('#fmsg').html('please provide reasons');
		return;
	}
	 
	timer = setTimeout(function() {
		$('#fmsg').html('processing...');
		
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
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



$( document ).on( "click", "#remove-post", function() {
	
	
	var r = confirm("Please press 'OK' to continue");	
	
	if(!r) return;
	
	data = {   action:'remove_post' } ;  
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 	
	
	$('.loading').remove();
	
	
	
	var loadingMsgNode1 = '<p class="loading text-success my-3 text-center"><span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span></p>';

	$(this).after(loadingMsgNode1);
	 
				
		ajaxer = $.ajax({
			method: "POST",
			type: "html",
			data: data,
			url: '<?php echo $canonical; ?>',
			  
			success:function(data){  
				
				if(data=='OK'){ 
					$('.loading').html('updated');
					location.href = "<?= (base_url('xAdmin/speaking_engagement')); ?>";
				}else{
					$('.loading').html('error');
				}				
			}
		});	
	

	
	
});
	
</script>