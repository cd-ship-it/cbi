<!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                     <form  method="POST"  class="needs-validation ajaxSubmit" novalidate>
					 
					 
                        <div class="form-group">
                         <label for="name" class="form-label">Name*：</label>
                          <input type="text" class="form-control" id="name"  name="name" required  value="<?= ($detail['name']); ?>">
						  <div class="invalid-feedback">       Please enter a valid name.      </div>
                        </div>

                          <div class="form-group">
                            <div class="row">
                            <div class="col-lg-6 mb-4">
                                <!-- Approach -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Assimilation Stage </h6>
										
										
                                    </div>
                                    <div class="card-body">
                                        <select class="form-control" id="stage_id">
										
										
										
										
						<?php foreach($visitor_stage as $key => $item): ?>
							
                       <option <?= ($detail['stage_id']==$key?'selected':''); ?> value="<?= $key; ?>"><?= $item; ?></option>
						
						<?php endforeach; ?>										
										
										
										
                                
                                        </select>
										
                                  
								  
                                            <p class="mt-3">New: A new visitor who visited Crosspoint pending for a Pastoral follow up</p>
                                    
                                            <p>Following up: A new visitor has been followed up by either a pastor or a delegate</p>
                                            
                                            <p>Attended Welcome Reception: The visitor has attended a Welcome Reception</p>
                                            
                                            <p>Joined a Life Group - A new visitor committed to a Life Group</p>
                    
                                            <p>Closed: A new visitor decided not to join Crosspoint, moved out of the area, or has not been responeding for a period of time.</p>
                                    

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Assigned to:</h6>
                                    </div>                                
                                    <div class="card-body">
                                        <select class="form-control" id="assigned_to" <?= (!$webConfig->checkPermissionByDes(['is_pastor','is_admin'])?'disabled':''); ?>>
										
													 <option value="">Choose</option>
		
													<?php 
													
														foreach($pastors as $pastor){
															
															$is_selected =  ($detail['assigned_id'] == $pastor['bid'] ?'selected':'') ;
															echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
														}
													
													?>
								 
                                          </select>
										  
										    
                                    </div>
                                </div>   

								<div class="card shadow my-3">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Assigned to (Delegate):</h6>
                                    </div>                                
                                    <div class="card-body">
											
											
											<div id="assigned_delegate_card_body">
											<?php 
												if(isset($assigned_delegate_name)){
													echo $assigned_delegate_name . '<br />';
													echo $assigned_delegate_email . '<br />';
													echo $assigned_delegate_phone . '<br />';
												}else{
													echo 'N/A';
												}
												
												
												 
											?>
											

											 </div>
											 
											 
									<?php if($webConfig->checkPermissionByDes(['dashboard_view'])): ?>
											
											<br /><br /><a href="javascript:void(0);" class="edit-delegate"><i class="fas fa-edit"></i>Edit</a> | <a href="javascript:void(0);" class="remove-delegate"><i class="fas fa-trash"></i>Remove</a>
											
									<?php 	endif; ?>
											
								
                                    </div>
                                </div>
                            </div>
                            </div> <!-- row -->
                          </div>                 
                                                       
                        <hr>   
						
                        <div class="form-group">
                            <label for="date_visited">Date Visited*</label>
                            <input type="text" class="form-control" id="date_visited" required name="date_visited" value="<?= ($detail['visited']); ?>">
							<div class="invalid-feedback">       Please enter a valid date.      </div>							
                          </div>
						  
                        <div class="form-group">
                          <label for="campus" class="form-label">Campus*：</label>
                          <select class="form-select form-control " id="campus" name="campus" required>
							<option disabled value="">Choose</option>
							
							
						<?php foreach($campuses as $item): ?>
							
                         <option value='<?= $item; ?>' <?= (strcasecmp($detail['campus'], $item)==0?'selected':''); ?>><?= $item; ?></option>
						
						<?php endforeach; ?>							
							
		 
                          </select>
						    <div class="invalid-feedback">      Please select a valid Campus item      </div>
                        </div>
						
                        <div class="form-group">
						
                            <label for="lifeStatus">Life Stage</label>
                            <select class="form-control" id="lifeStatus" name="lifeStatus">
							
							
							<option value='Not sure'>Not sure</option>							
							
						<?php foreach($visitor_life_status as $item): ?>
							
                         <option value='<?= $item; ?>' <?= (strcasecmp($detail['lifeStatus'], $item)==0?'selected':''); ?>><?= $item; ?></option>
						
						<?php endforeach; ?>								
							
							

			
                              
                            </select>
                          </div> 
						  
                          <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= ($detail['email']); ?>">
							<div class="invalid-feedback">       Please enter a valid email address.      </div>
                          </div> 
						  
                          <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= ($detail['phone']); ?>">
                          </div> 
						  
                          <div class="form-group">
                            <label for="learnAbout">How did you know about Crosspoint (can check multiple)</label>
                            <input type="text" class="form-control" id="learnAbout" name="learnAbout" value="<?= ($detail['learnAbout']); ?>">
                          </div> 
						  
                          <div class="form-group">
                            <label for="preferred_language">Language Preference</label>
                            <select class="form-control" id="preferred_language" name="preferred_language">
							                                <option  value="Doesn’t Matter"  >Doesn’t Matter</option>
                                <option   value="Cantonese" <?= (strcasecmp($detail['preferred_language'],'Cantonese')==0?'selected':''); ?>>Cantonese</option>
                                <option value="Mandarin"  <?= (strcasecmp($detail['preferred_language'],'Mandarin')==0?'selected':''); ?>>Mandarin</option>
                                <option value="English"  <?= (strcasecmp($detail['preferred_language'],'English')==0?'selected':''); ?>>English</option>

                              </select>
                        </div> 


						<div class="form-group">
									<label for="greeter" class="form-label">Greeter's Name*：</label>
								
                                    <input type="text" class="form-control " id="greeter" name="greeter" required value="<?= ($detail['greeter']); ?>" />
									 <div class="invalid-feedback">       Please enter a valid name.      </div>
                                </div>                                                                                                        
                        <div class="form-group">
                          <label for="notes">Notes</label>
                          <textarea class="form-control" id="notes" name="notes" rows="3"><?= ($detail['notes']); ?></textarea>
                        </div>
						
						
								<input   type="hidden" name="action" value="update_visitor" >
								
                                <button class="btn btn-primary btn-user btn-block" type="submit">Update</button>



						
					 				
							<p style="color:red;font-size: 18px;padding-top: 10px;" class="fmsg"></p>
							
								<?php if($webConfig->checkPermissionByDes('management')): ?>
									<br /><br />
									<a id="remove-vistor" class="badge badge-danger" href="javascript: void(0);">x Remove Visitor</a>
								<?php endif; ?>							
							
                      </form>
 
                    <hr>
                    <h5>Activity Log</h5>
					
					
					<?php foreach($activityLog as $item): ?>
					
						 <p><?= $item['change_time'];?> | <?= $item['log'];?></p>
					
					<?php endforeach; ?>
					
                 
                </div>
                <!-- /.container-fluid -->

