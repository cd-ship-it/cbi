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
		if($(this).val().trim()!='' && !validateEmail($(this).val().trim())){
			error += 'Please enter a valid email address.<br/>';
		}
	});
	
	
	return error;
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}


function validURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return !!pattern.test(str);
}

function selectOnchangeLink(e,val){
	
	if(validURL(val)){
		
		// $(e).after('<div class="spinner-border  text-secondary spinner-border-sm" role="status">  <span class="sr-only">Loading...</span></div>');
		// location = val;
		
		window.open(val, "_blank");
	} 
	
}

</script> 


<style>
body{ color:#000;}
.clear{clear:both}
.float-left{float:left}
.float-right{float:right}
.clearfix:after{content:" ";display:block;height:0;clear:both;visibility:hidden}
.clearfix{display:inline-block}

.rForm .fmsg {   color:red;}
</style>

  
  
  <?= (isset($customHeader)?$customHeader:''); ?>



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

 

		<?php if( $webConfig->checkPermissionByDes('user_add') ): ?>		
				  <li class="nav-item">
				<a class="nav-link" href="<?= base_url('xAdmin/baptist'); ?>"> 
				  <i class="fas fa-fw fa-user"></i>
				  <span class="menu-title">Add People</span>
				</a>
			  </li>		
		<?php endif; ?>	
            


		
		
			
		<?php if( $webConfig->checkPermissionByDes('user_view') ): ?>
			  <li class="nav-item">
				<a class="nav-link" href="<?= base_url('xAdmin/search'); ?>"> 
				  <i class="fas fa-fw fa-search"></i>
				  <span class="menu-title">Search Member</span>
				</a>
			  </li>		
		<?php endif; ?>		  

				
		<?php if( $webConfig->checkPermissionByDes(['is_pastor','is_admin','edit_class']) ): ?>		  
			  <li class="nav-item">
				<a class="nav-link" href="<?= base_url('xAdmin/curriculums'); ?>"> 
				  <i class="fas fa-fw fa-book"></i>
				  <span class="menu-title">Classes</span>
				</a>
			  </li>		
		<?php endif; ?>		
		






			<?php if($webConfig->checkPermissionByDes(['is_admin','is_pastor'])): ?>	
			  <li class="nav-item">
				<a class="nav-link" href="<?= base_url('xAdmin/pending'); ?>"> 
				  <i class="fas fa-fw fa-check"></i>
				  <span class="menu-title">Membership</span>
				</a>
			  </li>	
			  <?php endif; ?>	
			  
		



			
 
			 
			
						
		<?php if($webConfig->checkPermissionByDes(['pto_apply','is_bot_chair'])): ?>	
				
			  <li class="nav-item">
				<a class="nav-link" href="<?= base_url('pto'); ?>"> 
				  <i class="fas fa-fw fa-calendar"></i>
				  <span class="menu-title">PTO</span>
				</a>
			  </li>					
			
			<?php endif; ?>		
					
						
		 
			 
			  <li class="nav-item">
				<a class="nav-link" href="<?= base_url('nva/table/'.$logged_id.'/0'); ?>">
				  <i class="bi bi-people-fill"></i>
				  <span class="menu-title">New Visitor Assimilation</span>
				</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="<?= base_url('meetingminutes'); ?>">
				  <i class="fas fa-fw fa-file-alt"></i>
				  <span class="menu-title">Meeting Minutes</span>
				</a>
			  </li>					
			 
			 				
						

			
		
		
		
		
				<?php if($webConfig->checkPermissionByDes('management')): ?>	
						
									
          <li class="nav-item">
          <a class="nav-link" href="<?= base_url('xAdmin/permission'); ?>">
            <i class="fas fa-fw fa-cogs"></i>
            <span class="menu-title">Permission</span>
          </a>
          </li>				
        <?php endif; ?>			



		



			
	
		
		
		
		
		
		
		
	
	










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
						
						
						 <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $logged_name; ?></span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                          </a>	
						
						
						
					
						
						
						
					
						
						
						
						
						
						
						
						
						
							
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                               

								
                       
				
								
								<a class="dropdown-item" href="<?= base_url('member?v='.rand()); ?>"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Personal</a>
								<a class="dropdown-item" href="<?= base_url('to_do?v='.rand()); ?>"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> To-do</a>
								<a class="dropdown-item" href="<?= base_url('?logout=1'); ?>"><i class="fas fa-sign-out-alt fa-sm fa-fw  mr-2 text-gray-400"></i> Logout</a>
								
								
                            </div>
							
				
							
							
							
							
							
							
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->



 <!-- Begin Page Content -->
                <div class="container-fluid">

   

                


 <?php  echo $mainContent; ?>





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




<script>



    var sidebar = $('.sidebar');

    function addActiveClass(element) {
		//console.log(element.attr('href'));
      if (current === "") {
        //for root url
        if (element.attr('href').indexOf("index.html") !== -1) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
        }
      } else {
        //for other url
		
        if (element.attr('href')==current) { 
		//console.log(element.attr('href').indexOf(current));
          element.parents('.nav-item').last().addClass('active');
          if (element.siblings('.collapse-item').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
          if (element.parents('.submenu-item').length) {
            element.addClass('active');
          }
        }
      }
    }

    var current = location.href;
	//console.log(current);
	
    $('.sidebar li a').each(function() { 
      var $this = $(this);  
      addActiveClass($this);
    });



</script> 
 
	
	
<?= (isset($customFooter)?$customFooter:''); ?>	
	
<script>
    document.querySelectorAll('.modal').forEach(modal => {
      modal.addEventListener('hide.bs.modal', () => {
        document.activeElement.blur();
      });
    });
  </script>

</body>

</html>