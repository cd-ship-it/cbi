<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Crosspoint E-learning</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/elearning/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/elearning/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	
    <style>
	
	@media (max-width: 575.98px) {
		.topicTitle { margin-bottom: 15px !important;}
		.pageTitle { font-size: 24px !important;}
	}     
	
	form.valid .valid-fb{ display: block !important;}
	form.invalid .invalid-fb{ display: block !important;}
	
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url('elearning/class/'.$class_id); ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3"><?= $classTitle; ?></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <!-- <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Introduction</span></a>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <!-- <div class="sidebar-heading">
                Section 1
            </div> -->
			
			
			
			
		<?php  foreach($navItems as $key => $item) : 	?>
		
				
				
			
	            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse<?= $key; ?>"
                    aria-expanded="true" aria-controls="collapse<?= $key; ?>">
                    <i class="bi bi-bookmark"></i>
                    <span><?= ucfirst($item['title']); ?></span>
                </a>
				
				    <div id="collapse<?= $key; ?>" class="collapse <?= (isset($item['show'])||!$current_topic?'show':''); ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
				
				<?php	 	
				
					
					foreach($item['topic'] as $content_id => $topic) :	
					$prevRowKey = $content_id-1;
					
					if(isset($item['topic'][$prevRowKey]) && $topic == $item['topic'][$prevRowKey]) continue ;
					
				 
				
				?>
				
            
                        <a <?= ($userProgression<$content_id? 'data-container="body" data-toggle="popover" data-placement="right" data-content="請先完成當前頁面的學習内容后, 點擊右上角的\'Next\'按鈕繼續"':''); ?> class="collapse-item text-wrap" href="<?= ($userProgression>=$content_id?base_url('elearning/class/'.$class_id.'/'.$content_id):'javascript: void(0);'); ?>"><?=  ucfirst(subtext($topic,150)); ?>  <?php if($userProgression>=$content_id&&$topic==$current_topic):  ?>  <span class="badge badge-secondary">You're here</span>   <?php endif; ?></a>
     
				
				<?php	 endforeach;	?>	
	               </div>
                </div> 
							
				
				</li>		
			


		<?php	 endforeach;	?>	
			
			
			

            
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
          
            <!-- Sidebar Message -->
            <!-- <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <h2 class="pageTitle">Crosspoint E-learning</h2>

                   
				   <?= $elearning_nav;?>

                </nav>
                <!-- End of Topbar -->
				
				
										<?php if($user_wcd>=100 && $lastTopic != $current_content_id): ?> 
										
										
											<div class="alert alert-primary alert-dismissible fade show mx-4 mb-5" role="alert">
											 		  您已完成了该课程的全部内容!		
											</div>										
										
										
										<?php elseif($userProgression>$current_content_id): ?> 
									
											<div class="alert alert-primary alert-dismissible fade show mx-4 mb-5" role="alert">
											  您在此課程的進度為  <?= $all_content[$userProgression]['chapter']; ?> :: <?= $all_content[$userProgression]['topic']; ?>, <a href="<?=base_url('elearning/class/'.$class_id.'/'.$userProgression); ?>" class="alert-link">轉至<?= $all_content[$userProgression]['chapter']; ?> :: <?= $all_content[$userProgression]['topic']; ?>繼續學習</a>
											  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											  </button>
											</div>
									       

										  						
									
									<?php 	endif; ?>			
				

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                       
						
						
					<?php if($lastTopic != $current_content_id && 0 != $current_content_id): ?>	
                        <a href="<?=base_url('elearning/class/'.$class_id.'/'.($current_content_id-1)); ?>" class="d-none d-sm-inline-block btn btn-lg btn-primary shadow-sm"><i
                                class="fas fa-arrow-left  fa-lg text-white-50"></i> Prev</a>
					<?php endif; ?>	  	
					
					
					 <h1 class="topicTitle h3 mb-0 text-gray-800"><?= ($userProgression>=$current_content_id?$content['topic']:''); ?></h1>

					
					<?php if($lastTopic > $current_content_id+1): ?>	
                        <a href="<?=base_url('elearning/class/'.$class_id.'/'.($current_content_id+1)); ?>"  class="btn-next d-sm-inline-block btn btn-lg   <?= ($userProgression>$current_content_id||$content['type']=='text'?'btn-success':'btn-secondary'); ?>  shadow-sm"><i
                                class="fas fa-arrow-right fa-lg text-white-50"></i>  Next</a> 
					<?php elseif($lastTopic == $current_content_id+1): ?>	
                        <a href="<?=base_url('elearning/class/'.$class_id.'/'.($current_content_id+1)); ?>"  class="btn-next  d-sm-inline-block btn btn-lg <?= ($userProgression>$current_content_id||$content['type']=='text'?'btn-success':'btn-secondary'); ?> shadow-sm"><i
                                class=" bi bi-award fa-lg text-white-50"></i> Finish</a>
					<?php endif; ?>	 
								
                    </div>

  
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-5">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                   

                                </div>
								
								
                                <!-- Card Body -->
                                <div class="card-body">
                             
                                    <div>
									
									
									<?php 									
									
									if($isLogin==false): ?>
									
										
						 
									<div class="alert alert-warning  m-0 p-5" role="alert">
									 Please <a href="<?= (base_url('?redirect='.rawurldecode($canonical))); ?>" class="alert-link">login</a> first to continue.
									</div>
									
									<?php 									
									
									elseif($userProgression<$current_content_id): ?> 
									
									
									       
											<h5 class="card-title">進度出錯</h5>
											<p class="card-text">您在此課程的進度為:  <?= $all_content[$userProgression]['chapter']; ?> :: <?= $all_content[$userProgression]['topic']; ?></p>
											<a href="<?=base_url('elearning/class/'.$class_id.'/'.$userProgression); ?>" class="btn btn-primary">轉至<?= $all_content[$userProgression]['chapter']; ?> :: <?= $all_content[$userProgression]['topic']; ?>繼續學習</a>
										  						
									
									<?php 									
									
									elseif($lastTopic == $current_content_id): ?>
									
											<h5 class="card-title">You can close this page now</h5>
											 
											<a href="<?= base_url('member/submit'); ?>" class="btn btn-primary">關閉頁面并申請匯點會員</a>
											
											
											
											
											
											
											<br />
											<br />
											<br />
											<br />
											
											
											
											
											
											<a href="<?= base_url('elearning/class/'.$class_id.'?m=debug'); ?>" class="btn btn-danger">清除測試數據(僅方便測試用)</a> 
										  
										  
										  
									<?php 									
									
									elseif($content['type']=="text"): ?>
									
                                      
									  <?= nl2br($content['content']); ?>
										  
										  
										  
																		<?php 									
									
									elseif($content['type']=="video"): ?>
									
                                        <video id="video" width="100%;" style="max-width:700px;" controls autoplay controlsList="nodownload">
                                            <source src="<?= $content['content']; ?>" type="video/mp4" />
                                          </video>
										  
										  
										  
									<?php elseif($content['type']=="test"):
									

										
										preg_match_all("#\[option([^\]]*?)\](.*)\[\/option\]#Ui", $content['content'], $matches_op);							
										preg_match("#\[question([^\]]*?)\](.*)\[\/question\]#Ui", $content['content'], $matches_qa);							
									
									
									 	$type= preg_match('#checkbox#i', $matches_qa[1])?'checkbox':'radio'; 

										 
									
									
										
									?>	  
										  
                                  
								   
								   
								   
								   
												<div class="jumbotron">
												  <h1 class="display-9"><?= $matches_qa[2]; ?><?= ($type=='checkbox'?'(多選)':'');?></h1>									 
												  <hr class="my-4">
												  
												   <form  class="elearningTest needs-validation" novalidate> 
												   
												   <div class="list-group list-group-radio d-grid gap-2 border-0 w-auto">
												  
													<?php foreach($matches_op[2] as $key => $item) : 
													
													$classFix = '';
													
											
											
											
													if(preg_match('#is_answer#i', $matches_op[1][$key])){
														$classFix = ' rightAnswer';
													}else{
														$classFix = ' wrongAnswer';
													}
											
											
											?>	  
													<div class="custom-control custom-checkbox">
													   <input  class="form-check-input position-absolute top-50 end-0 me-3 fs-5 <?= $classFix; ?>" type="<?= $type; ?>" name="answerSubmit[]" id="answerSubmit<?= $key; ?>" value="<?= $key; ?>">
													  <label class="list-group-item py-3 pe-5 <?= $classFix; ?>" for="answerSubmit<?= $key; ?>"><strong class="fw-semibold"><?= $item; ?></strong></label>

													</div>
													<?php endforeach; ?>	

													
											

												<button type="submit" class="btn testSubmit btn-primary my-4">Submit</button>
												
												
												
														<div class="d-none alert alert-danger invalid-fb" role="alert">Oops, try again. You seem to have gotten the wrong answer. </div>										   
									  
														<div class="d-none alert alert-success  valid-fb" role="alert">Yeah~ well done!!</div>		
												
												
												
												
												
												</div>
												
												
												
												
												</form>	
												
												
												
												
									 

									 										
												
												
													
												</div>					   
								   
								   
                                   
                                       
									 
                                         
										  
                                          
                                       
                                      
									
									
                                    



										  
			
									<?php 	elseif($content['type']=="activity"):	 //[title]小活动標題[/title][item]小活动名目1[/item][item]小活动名目2[/item][item]小活动名目3[/item][item]小活动名目4[/item]
                                          
											  
											preg_match_all("#\[item\](.*)\[\/item\]#Ui", $content['content'], $matches_item);							
											preg_match("#\[title\](.*)\[\/title\]#Ui", $content['content'], $matches_title);	
											
											
											
									?>	
									
									
									
									
									<div class="jumbotron">
									  <h1 class="display-9"><?= $matches_title[1]; ?></h1>									 
									  <hr class="my-4">
									  
									  
										<?php foreach($matches_item[1] as $key => $item) : ?>	  
										<div class="custom-control custom-checkbox">
										  <input type="checkbox" class="custom-control-input activityChecked" id="customCheck<?= $key; ?>">
										  <label class="custom-control-label" for="customCheck<?= $key; ?>"><?= $item; ?></label>
										</div>
										<?php endforeach; ?>	  
										
									</div>
									
									
									
											
											
											
											
											  
									<?php endif; ?>	  
										  
										  
                                    </div>
                                </div>
								
								
								
								
								
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
							
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Your progression</h6>
                                </div>
								
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Class 101 <span
                                            class="float-right"><?= $user_wcd; ?>%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width:<?= $user_wcd; ?>%" ></div>
                                    </div>
          
                                </div>
                            </div>



                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Any questions?</h6>
                                </div>
                                <div class="card-body">
                                    <p>Free free to ask our pastors if you have any questions</p>
                                    <a target="_blank" rel="nofollow" href="mailto:training@crosspointchurchsv.org">Contact us &rarr;</a>
                                </div>
                            </div>

                           

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Crosspoint E-learning</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/elearning/vendor/jquery/jquery.min.js'); ?>"></script> 
    <script src="<?= base_url('assets/elearning/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/elearning/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/elearning/js/sb-admin-2.min.js'); ?>"></script>

<?php /*>
    <!-- Page level plugins -->
    <script src="<?= base_url('assets/elearning/vendor/chart.js/Chart.min.js'); ?>"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url('assets/elearning/js/demo/chart-area-demo.js'); ?>"></script>
    <script src="<?= base_url('assets/elearning/js/demo/chart-pie-demo.js'); ?>"></script>
*/ ?>


<script>
var timer  = null; 
var ajaxer  = null; 

var video = document.getElementById('video');
var supposedCurrentTime = 0;


function userProgressionUpdate(url){
	
			if(timer) clearTimeout(timer); 
			if(ajaxer) ajaxer.abort(); 
				
			var data = {
				action: 'userProgressionUpdate'
			};	

			
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?= $canonical; ?>',
					data: data,      
					success:function(data){
						
						window.location.href = url;
						
						
					}
				});	


			
	
}


if(video){
video.addEventListener('timeupdate', function() {
  if (!video.seeking) {
		//supposedCurrentTime = video.currentTime;
  }
});
// prevent user from seeking
video.addEventListener('seeking', function() {
  // guard agains infinite recursion:
  // user seeks, seeking is fired, currentTime is modified, seeking is fired, current time is modified, ....
  var delta = video.currentTime - supposedCurrentTime;
  // delta = Math.abs(delta); // disable seeking previous content if you want
  if (delta > 0.01) {
    //video.currentTime = supposedCurrentTime;
  }
});
video.addEventListener('ended', function() {
  // reset state in order to allow for rewind
	//supposedCurrentTime = 0;
	$(".btn-next").removeClass('btn-secondary').addClass('btn-success').popover('disable');
			
		
	
	
	
	
	
});
}






$(function () {
	$('[data-toggle="popover"]').popover( {trigger: 'focus'});
})


$( document ).on( "change", ".activityChecked", function() {
	
	
	
    if(this.checked == true)
    {
      $(".btn-next").removeClass('btn-secondary').addClass('btn-success').popover('disable');
    }
	
	
	
	
});

$( document ).on( "click", ".btn-next", function() {
	
	event.preventDefault();
	
	url = $(this).attr('href');
	
	if($(this).hasClass("btn-secondary")){
		
		
		 
		$(this).popover({
			  content: '請先完成當前頁面的學習内容',
			  trigger: 'focus',
			  placement: 'bottom',
		}).popover('show');
		 
		 
		
	 
	}else{
		
		
		$(this).children('i')[0].className = 'spinner-border'; 
		
		
		userProgressionUpdate(url);
		
		
		
	}
	
	
	
	
});

$( document ).on( "submit", ".elearningTest", function() {
	
	event.preventDefault();	
	
	var valid = true;
	
	
	
	$( this ).find('input.rightAnswer').each(function( index, element ) {
		
		 if (element.checked != 1) valid = false;
		
	});	
	
	$( this ).find('input.wrongAnswer').each(function( index, element ) {
		
		 if (element.checked == 1) valid = false;
		
	});
	
	
	if(valid){ 
		$( this ).removeClass("invalid");
		$( this ).addClass("valid");
		$( this ).find('label.rightAnswer').addClass('alert-success');
		
		$(".btn-next").removeClass('btn-secondary').addClass('btn-success').popover('disable');
		
		
		
		
	}else{
		$( this ).removeClass("valid");
		$( this ).addClass("invalid");
	}
	
	
	 
	
	
});


 

</script>



</body>

</html>



<?php








function subtext($text, $length)
{
	if(mb_strlen($text,'utf8') > $length)
	return mb_substr($text,0,$length,'utf8').'…';
	else{
		return $text;
	}
}












?>

