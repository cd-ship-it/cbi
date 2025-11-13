
 
                        <h1 class="h3 mb-1 text-gray-800">Welcome back, <?= ($logged_name); ?></h1>
						<p class="mb-4">Today is <?= (date("l, M d, Y")); // g:i a ?></p> 
       
	   
	   
	   
	   					
						
	<div class="row">

				  <?php if( $webConfig->checkPermissionByDes('pto_apply') ): ?>
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="mb-3 h5"><a class="text-primary" target="_blank" href="<?= base_url('pto?v='.rand()); ?>">PTO Application</a></div>
                                        </div>
										
										<div class="col-auto">
                                            <i class="fas fa-calendar fa-3x rotate-0 text-primary"></i>
                                        </div>
                                    </div>
									
									                                       
                                </div>
                            </div>
                        </div>  
					<?php endif; ?>		
					
					
					
	 		 
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-secondary" target="_blank" href="<?= base_url('nva/table/'.$logged_id.'/0'); ?>">New Visitors Assimilation</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-3x  text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
			 
					
					

	
					
					
					<?php if( $webConfig->checkPermissionByDes(['is_pastor','is_admin']) ): ?>
				 
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-primary"  href="<?= base_url('xAdmin/edit_prayer_items?v='.rand()); ?>">Prayer items</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-praying-hands fa-3x  text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php endif; ?>	
							
						
						<?php if( $webConfig->checkPermissionByDes(['is_pastor','is_admin']) ): ?>
							<div class="col-xl-3 col-sm-6 mb-4">
								<div class="card border-left-info shadow h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
										
											<div class="col mr-2">                                         
												<div class="h5 mb-3"><a class="text-info" target="_blank" href="<?= base_url('classdata'); ?>">CBI Class Data</a></div>
											</div>
											
											<div class="col-auto">
												<i class="fas fa-database fa-3x  text-info"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>		
						
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="mb-2 h5">Room Approval</div>
											
											<div class="form-group">                                            
                                            <select onchange="selectOnchangeLink(this,this.value);" class="form-control">
                                                <option>Choose an option</option>
                                                <option value="https://docs.google.com/spreadsheet/viewform?formkey=dFV4UTkxci1fbV9BaHU1bDZ2N2hLb3c6MA">Reserve a room (Milpitas)</option>
                                                <option value="https://docs.google.com/spreadsheet/embeddedform?formkey=dFVFUUlpbkRLZ2cxUk00aEhsSTFYdEE6MA">Reserve Gym (Milpitas) </option>
                                                <option value="https://crosspointchurchsv.org/churchoffice/pleasanton-room-calendar/">Reserve Room/Gym (Pleasanton)</option>
                                            </select>
                                        </div>
											
											
                                        </div>
										
                                  
                                    </div>
                                </div>
                            </div>
                        </div>
						
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-dark" target="_blank" href="https://drive.google.com/file/d/1Gb2T1xEiy7YFYwORmK_oQOgx9aKAjMgK/view?usp=sharing">Speaking Engagement</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-bullhorn fa-3x  text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-danger" target="_blank" href="https://docs.google.com/document/d/1n4Bd-7E-alT3B92E3s1VOl7DQCoBJHF7D2ji72RSgzg/edit?usp=drive_link">Announcement Video Application</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-video fa-3x  text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>						
                      
					  <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-dark" target="_blank" href="https://drive.google.com/drive/u/0/folders/1TxKYg5PvRSL9Y96xuQJd-CGLzarROmw1">Pulpit Plan</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-flag-checkered fa-3x  text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>						
                       
					   <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-primary" target="_blank" href="https://drive.google.com/drive/folders/1D255E5sFwlbt5wSNgnSX9DRqrZPy3eJN?usp=share_link">Pastor Training</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-signal fa-3x  text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>	
						
 






                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="mb-2 h5">Zone Materials </div>
											<div class="form-group">                                            
                                            <select onchange="selectOnchangeLink(this,this.value);" class="form-control" >
                                                <option >Choose an option</option>
                                                <option value="https://drive.google.com/drive/folders/0B7C767B9KmqNTGp0d3BqM0wzcXc?resourcekey=0-_nLNg1t1xGIdQmbF73SGcw&usp=share_link">Discipleship</option>
                                                <option value="https://crosspointchurchsv.org/LGadmin/docs/">Life Groups Information</option>
                                            </select>
                                        </div>
											
											
                                        </div>
										
                                  
                                    </div>
                                </div>
                            </div>
                        </div>

				
						
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-info" target="_blank" href="https://sites.google.com/crosspointchurchsv.org/myxp/staff-portal">Staff Portal</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-desktop fa-3x  text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                   

                      
           
                    </div>

                    <!-- Content Row -->
	   
	   
	   
	   
	   <?php if($toDoList||$students||$pastoralApproval): ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Your To-do list</h6>
                        </div>
						
						
						
						
						
	
					
						
						
						
						
						
						
						
						
						
                        <div class="card-body">
						

<ul class="accordion list-arrow" id="accordionTodo">
 



<?php	if($students): ?>
	
  <li id="online101" class="my-4">
 
        <strong class="mx-0 px-0" type="button" data-toggle="collapse" data-target="#collapse-101" aria-expanded="true" aria-controls="collapse-101">
         You have <i class="text-danger"><?= $students;?></i> people waiting for you to approve the completion of Class 101.
        </strong>
  

    <div id="collapse-101" class="collapse show" data-parent="#accordionTodo">
	 
     <div class="alert alert-secondary" role="alert">
        
		<div>
Click the following button to review their scores<br />
<a class="btn btn-primary" href="<?= base_url('elearning/table/1/'.$logged_id); ?>">Review</a>

</div>
		
      </div>
    </div>
  </li>	

	
<?php	endif; //if($students) ?>

<?php	if($pastoralApproval): ?>
	
  <li class="my-4">
 
        <strong class="mx-0 px-0" type="button" data-toggle="collapse" data-target="#collapse-pastoralApproval" aria-expanded="true" aria-controls="collapse-pastoralApproval">
         You have <i class="text-danger"><?= $pastoralApproval;?></i> people waiting for Pastoral Approval
        </strong>
  

    <div id="collapse-pastoralApproval" class="collapse show" data-parent="#accordionTodo">
	 
     <div class="alert alert-secondary" role="alert">
        
		<div>
Click the following button to review<br />
<a class="btn btn-primary" href="<?= base_url('xAdmin/pending?pid='.$logged_id); ?>">Review</a>

</div>
		
      </div>
    </div>
  </li>	

	
<?php	endif; //if($pastoralApproval) ?>


	
<?php foreach($toDoList as $key => $item): ?>


  <li class="">
 
        <strong class="mx-0 px-0" type="button" data-toggle="collapse" data-target="#collapse<?= $key; ?>" aria-expanded="true" aria-controls="collapse<?= $key; ?>">
         <?= $item['title']; ?> <?= ($item['status']=='1'?'[Archived]':''); ?> 
        </strong>
  

    <div id="collapse<?= $key; ?>" class="collapse " aria-labelledby="heading<?= $key; //show ?>" data-parent="#accordionTodo">
	<a class="remove todoFun btn btn-link" data-action="remove" data-id="<?= $item['id']; ?>" href="javascript: void(0);">x Remove this item</a>
     <div class="alert alert-secondary" role="alert">
        
		<div><?= $item['content']; ?></div>
		
      </div>
    </div>
  </li>




<?php endforeach;  ?>
</ul>


					
						
						</div>
					</div>
 
	<?php endif; //if($toDoList||$students) ?>	  






<script type="text/javascript">


$( document ).on( "click", ".todoFun", function() {
	itemId = $(this).data('id');
	action = $(this).data('action');

	var r = confirm("Please press 'OK' to continue");
	var url = '<?= $canonical; ?>';

	
	if(r){

	
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = {
			action: action,
			itemId: itemId,
		};
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: params,      
				success:function(data){
					
						 location.reload();
					
				}
			});
			
		 },600);	
	
	
	}
	
});	
	
	
	
	


</script>