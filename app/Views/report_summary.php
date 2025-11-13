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
    <link href="<?= base_url('assets/elearning/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/elearning/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	





</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url('xAdmin'); ?>">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Staff Page</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

 
            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url('xAdmin'); ?>">
                    <i class="fas fa-reply"></i>
                    <span>Back to CBI Admin</span></a>
            </li>	
			
	
	
            







            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

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

              
                    

                    <!-- Topbar Navbar -->
				<ul class="navbar-nav ml-auto">



                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
						
						
						<?php  if($logged_id): ?>
						
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $logged_name; ?></span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                            </a>
							
						<?php  else: ?>	
						
	                        <a class="nav-link " href="<?= (base_url('?redirect='.rawurldecode(base_url('nva')))); ?>">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Login</span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                            </a>					
						
						<?php  endif; ?>	
						
						
						
						<?php  if($logged_id): ?>
						
						
						
					
						
						
						
						
						
						
						
						
						
							
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                               

								
                       
								
							   <a class="dropdown-item"  href="<?= base_url('?logout=1'); ?>">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw  mr-2 text-gray-400"></i>
                                    Logout
                                </a>
								
         
								
								
                            </div>
							
						<?php  endif; ?>		
							
							
							
							
							
							
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->



 <!-- Begin Page Content -->
                <div class="container-fluid">

   

                
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><?= ($pageTitle); ?></h1>
                </div>

                    <!-- Content Row -->
                    <div class="row">


 
		  
		  
		  
 
		  
		  
		  <?php foreach($chartData as $key => $item): ?>
		  
		  
                        
                        <div class="col-lg-6 mb-4">

                           
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><a href="<?= base_url('report/user/'.$item['bid']); ?>"><?= $item['pastor']; ?></a></h6>
                                   
                                </div>
                                <div class="card-body">
                                    <div id="piechart<?= $key; ?>a" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">

                             
                           <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary"><a href="<?= base_url('report/user/'.$item['bid']); ?>"><?= $item['pastor']; ?></a></h6>
                                   
                                </div>
                                <div class="card-body">
                                    <div id="piechart<?= $key; ?>b" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>		  
		  
		  
		  
		  <?php endforeach; ?>
		  
		  



			
          </div>


</div></div>



	

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Crosspoint</span>
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
    <script src="<?= base_url('assets/elearning/vendor/jquery/jquery.min.js'); ?>"></script> 
    <script src="<?= base_url('assets/elearning/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/elearning/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/elearning/js/sb-admin-2.min.js'); ?>"></script>



 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

var timer  = null; 
var ajaxer  = null; 

function validater(selector){
	var error='';
	
	$(selector).find('.required').each(function(index) {
		
		 if (!$( this ).is( ":visible" )){
			 return;
		 }	
		 
		 if ($( this ).is( ":disabled" )){
			 return;
		 }
		
		if($(this).val().trim()==''){
			error += 'The "' + $(this).attr('title')+ '" field is required.<br/>';
		}
	});	
	
	$(selector).find('.email').each(function(index) {
		if(!validateEmail($(this).val().trim())){
			error += 'Please enter a valid email address.<br/>';
		}
	});
	
	
	return error;
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


</script>

<script type="text/javascript">
	
	google.charts.load('current', {'packages':['corechart']});

  <?php 
  
	foreach($chartData as $key => $item): 
	
	$data1 = $data2 = [];
  
	$month_arr = explode(',',$item['month']);
	
	$a1 = explode(',',$item['a1']);
	$a2 = explode(',',$item['a2']);
	$a3 = explode(',',$item['a3']);
	$a4 = explode(',',$item['a4']);	
	
	$b1 = explode(',',$item['b1']);
	$b2 = explode(',',$item['b2']);
	$b3 = explode(',',$item['b3']);
	$b4 = explode(',',$item['b4']);
  
	$data1[] =  ["Month", "LG since 2022", "LG this month", "Avg. att.", "Avg steady att."];
	$data2[] =  ["Month","NBC commenced", "# of DNA", "# of ppl in your DNA group", "# ppl oin your LC"];
	
	foreach($month_arr as $mKey => $mItem){  
		
		$data1[] =  [date('Y M',strtotime($mItem.'01')), $a1[$mKey], $a2[$mKey], $a3[$mKey], $a4[$mKey]];
		$data2[] =  [date('Y M',strtotime($mItem.'01')), $b1[$mKey], $b2[$mKey], $b3[$mKey], $b4[$mKey]];
		
	}
  
  ?>

      
      google.charts.setOnLoadCallback(function(){
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($data1,JSON_NUMERIC_CHECK ); ?>);
        var options = {title: 'Life Groups',legend: { position: 'bottom' },chartArea:{left:30,top:45,width:'90%',height:'70%'}};
        var chart = new google.visualization.LineChart(document.getElementById('<?= ("piechart".$key."a"); ?>'));
        chart.draw(data, options);
      });  
	  
      google.charts.setOnLoadCallback(function(){
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($data2,JSON_NUMERIC_CHECK ); ?>);
        var options = {title: 'Zone Discipleship System (Current month)',legend: { position: 'bottom' },chartArea:{left:30,top:45,width:'90%',height:'70%'}};
        var chart = new google.visualization.LineChart(document.getElementById('<?= ("piechart".$key."b"); ?>'));
        chart.draw(data, options);
      });


	

  <?php endforeach; ?>



</script>

 
	
	
	
	


</body>

</html>



 





 
