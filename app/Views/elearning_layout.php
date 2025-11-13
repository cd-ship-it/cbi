<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $pageTitle; ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/theme-sb-admin-2/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/theme-sb-admin-2/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	

  <script src="<?= base_url('assets/theme-sb-admin-2'); ?>/vendor/jquery/jquery.min.js"></script> 
  
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


<?php if(isset($sidebar)): echo $sidebar; ?>

	
<?php else: ?>

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">E-Learning</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">




 


 
            <li class="nav-item">
                <a class="nav-link collaps" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="bi bi-bookmark"></i>
                    <span>CBIF101</span>
                </a>
				
                <div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
				
							<?php //var_dump($user_session);
							
								foreach($class_sessions as $key => $item){
									
									$url = 'javascript:void(0);' ;
									$badge = '';
									$linkStyle = 'style="color: #9e9e9e;"';
								
									
									// if(isset($mode)&&$mode=='record'){
										
										// $url = base_url('elearning/class/'.$class_id.'/'.$item['id'].'/record');
																				
									// }
										
									
									
									
									
									
									if($item['id'] == $user_session){
										
										$badge = ' <span class="badge badge-primary">You\'re here</span>';
										
										$linkStyle = '';
										
										$prev = $key-1;
										$next = $key+1;
										
										if(isset(($class_sessions[$next]))){
											$nextSession = $class_sessions[$next]['id'];
										}
										
										
										if(isset(($class_sessions[$prev]))){
											$prevSession = $class_sessions[$prev]['id'];;
										}
										
									}elseif($item['id'] < $user_session){
										$badge = ' <span class="badge badge-secondary">Completed</span>';
									}
									
									
									if(isset($user_data['usession'])&&$user_data['usession']==99){
										$badge = ' <span class="badge badge-secondary">Completed</span>';
										
									}
									

									

									
									
									echo '<a '.$linkStyle.' class="collapse-item text-wrap" href="'.$url.'">'.$item['title'] . $badge .'</a>';
								}
							
							?>	


					
	               </div>
                </div> 
							
				
				</li>


				
 	
		
		





            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
          

        </ul>
        <!-- End of Sidebar -->

<?php endif; ?>



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

              
                    

                    <!-- Topbar Navbar -->
				<ul class="navbar-nav ml-auto">
				
				
				
				
				



                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
						
						
						 <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $logged_name; ?></span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                          </a>	
						
						
						
					
						
						
						
					
						
						
						
						
						
						
						
						
						
							
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                               

								
                       
				
								
								<a class="dropdown-item" href="<?= base_url('member?v='.rand()); ?>"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Personal</a>
								<a class="dropdown-item" href="<?= base_url('to_do?v='.rand()); ?>"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> To-do</a>
								<a class="dropdown-item" href="<?= base_url().'/?logout=1'; ?>"><i class="fas fa-sign-out-alt fa-sm fa-fw  mr-2 text-gray-400"></i> Logout</a>
								
								
                            </div>
							
				
							
							
							
							
							
							
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->


 <!-- Begin Page Content -->
                <div class="container-fluid">

<?= $mainContent; ?>






</div></div>



	

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                         <span><?= isset($webConfig->copyright)?$webConfig->copyright:'';?></span>
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




    <!-- Bootstrap core JavaScript-->
    
    <script src="<?= base_url('assets/theme-sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/theme-sb-admin-2/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/theme-sb-admin-2/js/sb-admin-2.min.js'); ?>"></script>



<?php if(isset($enableDataTables)&&$enableDataTables): ?>
	<link href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">	
	<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
	<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'); ?>"></script>
	<script>
	// Call the dataTables jQuery plugin
	$(document).ready(function() {
	  $('#dataTable').DataTable({ "columnDefs": [ { type: 'date', "targets": [0] } ],  order: [[0, 'desc']]  });  
	});
	</script>
<?php endif; ?>	




  <script> 

var timer  = null; 
var ajaxer  = null;   
  
(function() {
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
		 
		event.preventDefault();
		$('.ajaxSubmit .fmsg').html('').addClass('spinner-border spinner-border-sm');
		
		
        if (form.checkValidity() === false) {          
          event.stopPropagation();
		  $('.ajaxSubmit .fmsg').removeClass('spinner-border spinner-border-sm');

		  $('.ajaxSubmit .fmsg').html('Oops, please check your input and submit the form again.');
        }else{
			
				$('.ajaxSubmit .fmsg').addClass('spinner-border spinner-border-sm');

				
				if(timer) clearTimeout(timer); 
				if(ajaxer) ajaxer.abort(); 
				
				var params = $(this).serialize();
				var url = $(this).attr('action');
				
				
				timer = setTimeout(function() {
					
					ajaxer = $.ajax({
						dataType:'json',
						method: "POST",
						url: url,
						data: params,      
						success:function(data){
							
							if(data && data.code=='ZPASTOR_UPDATED'){
								
								$('.ajaxSubmit .fmsg').html('Updated');								
								if(data.url) window.location.replace(data.url);
								
								
							}else{
								$('.ajaxSubmit .fmsg').html('Error');
							}
							
							$('.ajaxSubmit .fmsg').removeClass('spinner-border spinner-border-sm');
						}
					});
					
				 },600);			
			
		}
		
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();






$( document ).on( "click", ".btn-save-progress", function() {
	
	var params = $("#questionForm").serialize();
	var url = "<?= $canonical; ?>";
	var mthis = $(this);
	
	params = params +"&"+"saveProgress=1" ;


	

	
		$(this).find('.rMsg').html('').addClass('spinner-border spinner-border-sm');
 
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 	

		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'json',
				method: "POST",
				url: url,
				data: params,      
				success:function(data){
					
					if(data && data.code=='PROGRESS_UPDATED'){
						
						$(mthis).html('Saved').find('.rMsg').removeClass('spinner-border spinner-border-sm');
						
					}else{
						$(mthis).html('Error').find('.rMsg').removeClass('spinner-border spinner-border-sm');
					}
					
					
				}
			});
			
		 },600);	

		

});

$( document ).on( "submit", "#questionForm", function() {
	
	event.preventDefault();

	var params = $(this).serialize();
	var url = $(this).attr('action');
	var mthis = $(this);
	
	
	




		
		$(this).find('.rMsg').html('').addClass('spinner-border spinner-border-sm');
 
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 			
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'json',
				method: "POST",
				url: url,
				data: params,      
				success:function(data){
					
					if(data && data.code=='ANS_UPDATED'){
						
						
						
						$(mthis).find('.rMsg').removeClass('spinner-border spinner-border-sm').html('Saved');
						
						if(data.url){
							window.location.replace(data.url);
						}	 				
											
						
						
						
					}else{
						$(mthis).find('.rMsg').removeClass('spinner-border spinner-border-sm').html('Error');
					}
					
					
				}
			});
			
		 },600);			
			
		


	
	

	
	

	
	

	
	
	
	

	
	
	 
	
	
});






$( document ).on( "click", "#remove-udata", function() {
	
	
	var r = confirm("Please press 'OK' to continue");	
	
	if(!r) return;
	
	data = {   action:'remove_user_data'} ;  
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 	
	
 
	
	
	
	var loadingMsgNode1 = '<p class="loading text-success my-3"><span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span></p>';

	$(this).after(loadingMsgNode1);
	 
				
		ajaxer = $.ajax({
			method: "POST",
			type: "html",
			data: data,
			url: '<?php echo $canonical; ?>',
			  
			success:function(data){  
				
				if(data=='OK'){ 
					$('.loading').html('updated');
					location.href = "<?= (base_url('elearning/table/'.$class_id.'/'.$logged_id)); ?>";
				}else{
					$('.loading').html('error');
				}				
			}
		});	
	

	
	
});







</script>

</body>

</html>