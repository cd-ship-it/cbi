<?php





$alpha = array('0','A','B','C','D','E','F','G','H','I','J','K', 'L','M','N','O','P','Q','R','S','T','U','V','W','X ','Y','Z');
$number = array(0,1,2,3,4,5,6,7,8,9,10,11, 12,13,14,15,16,17,18,19,20,21,22,23,24,25,26);






?>



                    <!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
					<h2><?= $userInfo['name']; ?>'s <?= $class_title; ?></h2>		

					
						
                        <a href="<?=  base_url('elearning/table/'.$class_id.'/'.$logged_id);?>" class="btn-next d-sm-inline-block btn btn-lg   btn-primary  shadow-sm">Go back</a> 
						 
								
                    </div>
					
					
			
					
					
					
					
								<?php if($user_data['scores']): $user_data_str = explode('_',$user_data['scores']); ?>
   					<div class="card shadow mb-4">
						<div class="card-body">                                

								   <div class="mb-1 "><?= $userInfo['name']; ?>'s Scores:  <?= $user_data_str[0]; ?>/<?= $user_data_str[1]; ?> (<?= $user_data_str[2]; ?>%)</div>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $user_data_str[2]; ?>%"
                                            aria-valuenow="<?= $user_data_str[2]; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

									<?php if($user_data['approved']): ?>
										<button type="button" class="btn btn-secondary">Completion Approved</button>

									<?php 
										else: $btnStyle = $user_data['usession'] == 99 ? 'btn-success' : 'btn-secondary';
									?> 
										<button id="btn-approve" 
											data-post_id="<?= $user_data['id']; ?>" 
											data-uid="<?= $user_data['baptism_id']; ?>" 
											class="btn  <?= $btnStyle; ?>">
											<i class="fas fa-check"></i> Approve
										</button>
										<span class="text-danger ajMsg" ></span>


									<?php endif; ?>	

									<?php if($user_data['usession'] == 99): ?>
										<br />All 9 lessons have been completed.
									<?php else: ?>
										<br />All 9 lessons are not yet completed. 
									<?php endif; ?>

	
									
									
											</div>
					</div>				
									
									<?php endif;// ?>						
					
					

					
					<div class="card shadow mb-4">
						<div class="card-body">
									
					
	                     <form  method="POST" action="<?= $canonical; ?>"  class="needs-validation ajaxSubmit" novalidate>
					 
                        <div class="form-group">
                          <label for="campus" class="form-label"><?= $userInfo['name']; ?>在哪個分堂聚會：</label>
                          <select class="form-select form-control " id="campus" name="campus" required>
							<option  value="">Choose</option>
							
							
						<?php foreach($campuses as $item): ?>
							
                         <option <?= ($item==$userInfo['site']?'selected':''); ?> value='<?= $item; ?>'><?= $item; ?></option>
						
						<?php endforeach; ?>							
							
		 
                          </select>
						    <div class="invalid-feedback">      Please select a valid item      </div>
                        </div>	

						
                        <div class="form-group">
                          <label for="zPastor" class="form-label"><?= $userInfo['name']; ?>的Zone Pastor是：</label>



                                       <select class="form-select form-control " id="zPastor" name="zPastor" required>
										
													<option  value="">Choose</option>
													
													
		
													<?php 
													
														foreach($pastors as $pastor){
															echo '<option '.($pastor['bid']==$userInfo['zPastor']?'selected':'').'   value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
														}
													
													?>
													
													<option <?= (0==$userInfo['zPastor']?'selected':''); ?> value="0">不知道</option>
								 
                                          </select>


						    <div class="invalid-feedback">      Please select a valid item      </div>
                        </div>					 
        
	
								<input   type="hidden" name="action" value="zPastor_update_by_admin" >
								<input   type="hidden" name="uid" value="<?= $user_data['baptism_id']; ?>" >
								
                               <?php if( $webConfig->checkPermissionByDes('management') ):  ?>

											<button class="btn-next d-sm-inline-block btn btn-lg   btn-primary  shadow-sm" type="submit">Save</button> 
								 <?php endif;  ?>

						
					 				
							<p style="color:red;font-size: 18px;padding-top: 10px;" class="fmsg"></p>
							

					
							
                      </form>										
									
									
									
									
									
								<?php if($webConfig->checkPermissionByDes('management')||$userInfo['zPastor']==$logged_id): ?>
									<br /><br />
									<a id="remove-udata" class="badge badge-danger" href="javascript: void(0);">x 刪除<?= $userInfo['name']; ?>的綫上課程數據</a>
								<?php endif; ?>			
									

						</div>
					</div>
				
					
					
				<div data-spy="scroll" data-target="#collapseUtilities">	
				<?php 
				
					foreach($class_sessions as $c_session): 
					
					$s_questions = explode(',',$c_session['questions']);
					
					
					if(!$c_session['questions'] && !isset($user_qa[$c_session['id']])) continue;
				
				
				?>
                    <div  id="session_<?= $c_session['id']; ?>" class="card shadow mb-4">
					
						<div class="card-header py-3">
                            <h3 class="m-0 font-weight-bold text-primary"><?= $c_session['title']; ?></h3>
                        </div>
					
                        <div class="card-body"> 
						
						
									<?php 
										foreach($session_questions as $item):
										
										if(!in_array($item['id'],$s_questions)) continue;

									?>



		 									<?php 
									
									
						 						
									
									
									 	$type= preg_match('#[^\d]#i', $item['correct_ans'])?'checkbox':'radio'; 
										$correct_ans = explode(',',$item['correct_ans']);

										$smile = isset($user_ans[$item['id']])&&$user_ans[$item['id']] == $correct_ans ? 1 : 0 ; 
									
										// if($smile) continue;
										
									?>

									<div class="jumbotron p-3">
												  <h5 class="display-9"><strong class="fw-semibold"><?= $item['question']; ?></strong></h5>	
												  
												  <hr class="my-4">
												  
												
												
												   
												   <div class="list-group list-group-radio d-grid gap-2 border-0 w-auto">
												  
													<?php foreach([1,2,3,4,5,6,7,8] as $key) : 
													
														if($item['option_'.$key]): 
														
														$classFix = '';
														if(in_array($key,$correct_ans)){
															$classFix = ' rightAnswer';
														}else{
															$classFix = ' wrongAnswer';
														}														
														
														?>
													
													
													
											
											
											

											
									 
													 
													   
													  <label class="list-group-item py-3 pe-5 <?= $classFix; ?>" for="q-<?= $item['id']; ?>-<?= $key; ?>"><?= $alpha[$key]; ?>. <?= $item['option_'.$key]; ?></label>

												 
													
													<?php endif; //if($item['option_'.$key])
													endforeach;  //foreach([1,2,3,4,5,6,7,8]
														?>	

													
											
 	
												
											<?php 
											
												 
													
													
													$correct_ans_alpha = str_replace($number, $alpha, $item['correct_ans']);
													$user_ans_alpha = isset($user_ans[$item['id']]) ? str_replace($number, $alpha, implode(',',$user_ans[$item['id']])) : '未作答';
													
													
													
												
											
											?>	
												<div class="issetAns card mb-4 py-3 border-bottom-<?= ($smile ? 'success' :'danger'); ?>">
													<div class="card-body text-<?= ($smile ? 'success' :'danger'); ?>">
														正確答案是[<?=  $correct_ans_alpha; ?>], 
														
														學員的選擇是[<?= $user_ans_alpha; ?>] 
														
														<?= ($smile? '<i class="bi bi-emoji-smile-fill"></i>' :'<i class="bi bi-emoji-frown-fill"></i>'); ?>

														

													</div>
												</div>
										
										
												
												
												</div>
												
												
												
												
											
											
												
												
												
												
									 

									 										
												
												
													
												</div>	
 



					
								<?php endforeach; //foreach($session_questions as $question) ?>
						
						
								<?php if(isset($user_qa[$c_session['id']])): ?>
								<div class="jumbotron p-3">
									
												  <h5 class="display-9"><strong class="fw-semibold">你在學習這一課時或之後，有任何相關的疑問:</strong></h5>	
												  
												  <hr class="my-4">
												  
												  
												  <div class="list-group list-group-radio d-grid gap-2 border-0 w-auto">
													  <label class="bg-gray-400 text-gray-800 list-group-item py-3 pe-5 ">
														<?php echo nl2br($user_qa[$c_session['id']]); ?>
													  </label>
												  </div>
												  
								</div>
								<?php endif; ?>

                        </div>
                    </div>
				<?php endforeach; //foreach($class_sessions as $c_session) ?>


					  
</div>


 

<script>

 

$( document ).on( "click", "#btn-approve", function() {
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 	

	$('.ajMsg').html(''); 

 

	
	var post_id = $(this).data('post_id');
	var action = 'approve';

	var uid = $(this).data('uid'); 
	
	var url = '<?php echo base_url('elearning/table/1/0'); ?>';

	
	 
	timer = setTimeout(function() {
		$('.ajMsg').html('processing...');
		
		ajaxer = $.ajax({
			dataType:'json',
			method: "POST",
			url: url,
			data: {	post_id: post_id, action:action, uid:uid},     
			success:function(data){
				
				if(data && data.code=='APPROVED'){
					$('.ajMsg').html('Saved');
					location.reload();

 
					
				}else{
					$('.ajMsg').html('Error');

				}				
			}
		});
		
	 },300); 
	 
	 
	 
});		

	
</script>

 