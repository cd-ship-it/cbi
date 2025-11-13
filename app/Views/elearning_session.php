<?php



function getYoutubeEmbedUrl($url)
{
	
     $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
     $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}




$alpha = array('0','A','B','C','D','E','F','G','H','I','J','K', 'L','M','N','O','P','Q','R','S','T','U','V','W','X ','Y','Z');
$number = array(0,1,2,3,4,5,6,7,8,9,10,11, 12,13,14,15,16,17,18,19,20,21,22,23,24,25,26);






?>












	<div class="d-sm-flex align-items-center justify-content-between mb-4">
					<h2>CBIF101 <?= $session_details['title']; ?></h2>	
					
					
			<?php if($user_data['usession'] <= $user_session): ?>		
 					 <button data-session="<?= $user_session; ?>" class="btn-save-progress d-sm-inline-block btn btn-lg   btn-primary  shadow-sm">Save Progress <i class="rMsg"></i></button>
			<?php endif; ?>		 
					 
					 
					 

    </div>




                

<p><?= $session_details['special_instruction']?$session_details['special_instruction']:''; ?></p>					
  

 <div class="row">

<div class="col-md-12 stretch-card">


<?php if($session_details['video_link_cantonese']): ?>
	<div class="card shadow mb-4">
	
	<?php if($session_details['video_link_mandarin']): ?>
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">粤语視頻</h6>
		</div>		
	<?php endif; //if($session_details['video_link_mandarin']) ?>
	
		<div class="card-body">

		<div style="width: 100%; min-width: 400px; max-width: 800px;">
			<div style="position: relative; width: 100%; overflow: hidden; padding-top: 56.25%;">
				<p><iframe style="position: absolute; top: 0; left: 0; right: 0; width: 100%; height: 100%; border: none;" src="<?php echo getYoutubeEmbedUrl($session_details['video_link_cantonese']); ?>" width="560" height="315" allowfullscreen="allowfullscreen" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe></p>
			</div>
		</div>
			
		</div>
	</div>
<?php endif; //if($session_details['video_link_cantonese']) ?>

<?php if($session_details['video_link_mandarin']): ?>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">普通話視頻</h6>
		</div>	
		<div class="card-body">
		<div style="width: 100%; min-width: 400px; max-width: 800px;">
			<div style="position: relative; width: 100%; overflow: hidden; padding-top: 56.25%;">
				<p><iframe style="position: absolute; top: 0; left: 0; right: 0; width: 100%; height: 100%; border: none;" src="<?php echo getYoutubeEmbedUrl($session_details['video_link_mandarin']); ?>" width="560" height="315" allowfullscreen="allowfullscreen" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe></p>
			</div>
		</div>
			
		</div>
	</div>
<?php endif; //if($session_details['video_link_mandarin']) ?>


 <form data-class="ajaxSubmit needs-validation" id="questionForm" novalidate method="POST" action="<?= $canonical; ?>"  > 

<?php 


$correct = $answered  = 0;

foreach($session_questions as $item):

 ?>

	<div class="card shadow mb-4">
		<div class="card-body">
		 									<?php 
									
									
						 						
									
									
									 	$type= preg_match('#[^\d]#i', $item['correct_ans'])?'checkbox':'radio'; 
										$correct_ans = explode(',',$item['correct_ans']);

										 
										if(isset($user_ans[$item['id']])){
											
											$answered++;
											
											if( $user_ans[$item['id']] == $correct_ans ){
												
												$correct++;
											}
											
											
										}
										
										
										 
									
										
									?>

									<div class="jumbotron">
												  <h5 class="display-9"><strong class="fw-semibold"><?= $item['question']; ?></strong></h5>	
												  
												  <hr class="my-4">
												  
												  
  
												  
												  
												  
												   
												   <div class="list-group list-group-radio d-grid gap-2 border-0 w-auto">
												   
												   <div class="form-group">
														<div class="form-check">
												  
													<?php foreach([1,2,3,4,5,6,7,8] as $key) : 
													
														if($item['option_'.$key]): 
														
														$classFix = '';
														if(in_array($key,$correct_ans)){
															$classFix = ' rightAnswer';
														}else{
															$classFix = ' wrongAnswer';
														}	


														$checked = isset($user_ans[$item['id']]) && in_array($key,$user_ans[$item['id']]) ? 'checked ' : '';
														
														?>
													
													
													

      <input <?= $checked; ?> class="form-check-input rec position-absolute top-50 end-0 me-3 fs-5 <?= $classFix; ?>" type="<?= $type; ?>" value="<?= $key; ?>" id="q-<?= $item['id']; ?>-<?= $key; ?>" name="q-<?= $item['id']; ?>[]" required>
     <label class="list-group-item py-3 pe-5 <?= $classFix; ?>" for="q-<?= $item['id']; ?>-<?= $key; ?>"><?= $alpha[$key]; ?>. <?= $item['option_'.$key]; ?></label>
												
											
											

											
									 
													 
											 
													  

												 
													
													<?php endif; //if($item['option_'.$key])
													endforeach;  //foreach([1,2,3,4,5,6,7,8]
														?>	

													
											
													  <div class="invalid-feedback">
														You must select at least 1 option.
													  </div>
													</div>
												  </div>
																							
												
												</div>
												
												
												
												
											 
												
												
												
												
									 

									 										
												
												
													
												</div>	
 





	</div>
	</div>	


<?php endforeach;//foreach($session_questions) ?>





<div class="card shadow mb-4">
		<div class="card-body">
 
									<div class="jumbotron">
												  						 
											 <h5 class="display-9"><strong class="fw-semibold">CBIF101 <?= $session_details['title']; ?></strong></h5>												  
												  <hr class="my-4">
												  
												
												   
												  
												  
					 
																					
														<div class="list-group list-group-radio d-grid gap-2 border-0 w-auto">
														  <label for="qa">你在學習這一課時或之後，有任何相關的疑問，請在這裡寫出，以便導師稍後跟進:</label>
														  <textarea class="form-control" id="qa" name="qa" rows="5"><?= (isset($user_qa)&&$user_qa?$user_qa:''); ?></textarea>
														 									
													
											
											
			
			

													

												
	 
												
												
												</div>
												
												
												
												
											
												
												
												
												
									 

									 										
												
												
													
												</div>	
 





	</div>
	</div>	



	<input   type="hidden" name="action" value="submit_and_next" >
											
	<button type="submit" class="d-sm-inline-block btn btn-lg   btn-primary  shadow-sm">
		<i class="fas fa-arrow-right fa-lg text-white-50"></i> Submit <?= $last_session===$user_session ? '' : 'and Next'; ?> 
	</button>
	
	<div class="rMsg"></div>	
									 
												
			 									   
 </form>									  
															


</div>
</div>



 
					




								<?php 
								
									if($user_data['scores']){
										$user_data_str = explode('_',$user_data['scores']); 
										
										$user_scores_str = 'Your Scores: '. $user_data_str[0] . '/'. $user_data_str[1].' ('.$user_data_str[2].'%)';
									}else{
										 $user_data_str[2] = 0;
										$user_scores_str = 'Your Scores: 0%';
									}
								
	
                                    /*
									
														<div class="card shadow mt-5">
						<div class="card-body">
									
									
									<div class="mb-1 "><?= $user_scores_str; ?></div>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $user_data_str[2]; ?>%"
                                            aria-valuenow="<?= $user_data_str[2]; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
									
									
									   <label for="zPastor" class="form-label">Your Zone Pastor：<?= $user_zPastor; ?></label>		



						</div>
					</div>

									   
									
									*/

	
								?>

							
							
									
									
									
									
					
	             


						
                      
        
	

							

					
									
									
									
									
									
									
									
									



