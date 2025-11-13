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









 				
  

 <div class="row">

<div class="col-md-12 stretch-card">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
					<h2>CBIF101 <?= $session_details['title']; ?></h2>	
 		 

    </div>


 
									<?php 
										foreach($session_questions as $item):
										
										 

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
 



					
								<?php endforeach; //foreach($session_questions as $question) 
								
								
								
								
								
								if(!$session_questions){
									
									
									echo 'N/A';
								}
								
								
								
								
								
								?>



						 
												
 								  
															


</div>
</div>

 
 


