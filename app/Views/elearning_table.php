
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
			Do you want to continue?
		  </div>
		  
		<div class="modal-footer">
		  
	

			<button type="submit" data-post_id="" data-action="approve" data-uid="" class="btn btn-success btn-approve btn-icon-split"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Yes</span></button>	 	 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			

  
		
		</div>
		  
		  
		  
			 <p class=" p-3" style="color:red;font-size: 18px;padding-top: 10px;" id="fmsg"></p>  
			 
 
	  
	  	
	 
    </div>
  </div>
</div>	


                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $class_title; ?></h1>
					




				
                    <div class="card shadow mb-4">
					
						<div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                        </div>
					
                        <div class="card-body"> 
						
                           <div class="table-responsive"> 
						   
                                <table class="table table-bordered dataTable" id="" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Names</th> 
											<th>Scores</th>
                                            <th>Took 101 on</th>
                                            <th>Campus</th>
                                            <th>Approve?</th>
                                            <th>Re-assign</th>
                                        </tr>
                                    </thead>
                              
                                    <tbody> 
								  
								  <?php foreach($students as $row): ?>
								  
								  
                                        <tr id="udata<?= $row['id']; ?>">
                                           
                                            <td><a href="<?= base_url('elearning/scores/'.$row['class_id'].'/'.$row['baptism_id']); ?>"><?= ($row['name']); ?></a></td>
                                            <td>
											
												<?= ($row['scores']?explode('_',$row['scores'])[2]:0); ?>%
												
												<?php if( isset($last_session) && isset($_GET['debug']) ): 
												
												
														
														
														if($row['usession'] == 99){
															$displaySession = $last_session;
														}elseif($row['usession']){
															$displaySession = $row['usession'];
														}else{
															$displaySession = 1;
														}
										
														echo '(Session '.$displaySession.')';
												
												
												 endif; ?>
											
											</td>
                                            <td><?= (date('Y-m-d',$row['took_on'])); ?></td>
                                            <td><?= ($row['site']); ?></td>
                                            <td>
												<?php 
												
													if($row['zPastor']==$logged_id||!$pastor_id):
													
													$btnStyle = $row['usession'] == 99 ? 'btn-success' : 'btn-secondary';



												?>
												<a data-toggle="modal" data-target="#confirmModal" data-uid="<?= $row['baptism_id']; ?>" data-post_id="<?= $row['id']; ?>" data-action="approve" class="btn btn-circle <?= $btnStyle; ?>"><i class="fas fa-check"></i></a>
												<?php endif; ?>
											
											</td>
											
											 <td>
											 
											 <form class="re-assign">
											 
												  <div class="row">
													<div class="col">

													   <select class="form-select form-control "  name="zPastor" >
														
																	<option  value="0">Choose</option>
						
																	<?php 
																	
																		foreach($pastors as $pastor){
																			echo '<option '.($row['zPastor']==$pastor['bid']?'selected':'').'   value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
																		}
																	
																	?>
																	
																 
												 
														  </select>
													  
													  </div>
													  
													  <div class="col pl-0">
													  
														<input type="hidden" name="action" value="re-assign" />
														<input type="hidden" name="uid" value="<?= $row['baptism_id']; ?>" />
														<button type="submit" class="btn smbt btn-primary mb-3">Assign</button>
														
														<span class="rMsg"></span>
														
													  </div>
													  </div>
													 </form>
											 </td>
                                        </tr>
										
									  <?php endforeach; ?>		
										
                               
                                    </tbody>
                                </table>
                            </div>
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
		var uid = button.data('uid');
		
		
		
		
		modal.find('.btn-approve').data('post_id',post_id).data('uid',uid).show();
			
 
			
			
			
			
	});
	
	

$( document ).on( "click", ".btn-approve", function() {
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 	
	$('#fmsg').html('');	
	
	var post_id = $(this).data('post_id');
	var action = $(this).data('action');
	var uid = $(this).data('uid');
	var tr = $('tr#udata'+post_id);
	
	var url = '<?php echo $canonical; ?>';
	
	 
	timer = setTimeout(function() {
		$('#fmsg').html('processing...');
		
		ajaxer = $.ajax({
			dataType:'json',
			method: "POST",
			url: url,
			data: {	post_id: post_id, action:action, uid:uid},     
			success:function(data){
				
				if(data && data.code=='APPROVED'){
					$('#fmsg').html('Saved');
					$('#confirmModal').modal('hide');
					$(tr).remove();
					
				}else{
					$('#fmsg').html('Error');
				}				
			}
		});
		
	 },300); 
	 
	 
	 
});		
	

$( document ).on( "click", ".smbt", function() {
	
	event.preventDefault();
	
	
	var form = $(this).parents('form:first');
	var params = $(form).serialize();
	var url = '<?php echo $canonical; ?>';

	
	
	




		
		$(form).find('.rMsg').html('').addClass('spinner-border spinner-border-sm');
 
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 			
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'json',
				method: "POST",
				url: url,
				data: params,      
				success:function(data){
					
					if(data && data.code=='RE_ASSIGN_UPDATED'){
						
						
						
						$(form).find('.rMsg').removeClass('spinner-border spinner-border-sm').html('Saved');
						
		
											
						
						
						
					}else{
						$(form).find('.rMsg').removeClass('spinner-border spinner-border-sm').html('Error');
					}
					
					
				}
			});
			
		 },600);	
	
});






$( document ).on( "change", ".re-assign .form-select", function() {
	
	$('.re-assign .rMsg').removeClass('spinner-border spinner-border-sm').html('');
	
});
	
</script>

 