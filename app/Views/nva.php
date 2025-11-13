<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>New Visitors Assimilation</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/elearning/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/elearning/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	
    <style>
	
.ajaxDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

.ajaxDiv h3{ margin-bottom:10px;}
.ajaxDiv ul{     list-style: none;    padding: 20px 0 0 0;}
.ajaxDiv li{  padding: 5px 0;}
.ajaxDiv li .function{ float:right;}	
	
	
.was-validated .form-control:valid, .was-validated .form-check-input:valid~.form-check-label { border-color:inherit !important; color:inherit !important; background-image:inherit !important;}	
	
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
						<div class="sidebar-brand-text mx-3">
							<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= ($webConfig->checkPermissionByDes(['dashboard_view'])?base_url('nva'):'#'); ?>">           
							   Crosspoint NVA
							</a>
						</div>
						
            <hr class="sidebar-divider">

            
           
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Your action items</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                         <a class="collapse-item" href="<?= (base_url('nva/table/'.$current_user_id.'/1')); ?>">New Visitors</a>      
                        <a class="collapse-item" href="<?= (base_url('nva/table/'.$current_user_id.'/2')); ?>">Following up</a>
                        <a class="collapse-item" href="<?= (base_url('nva/table/'.$current_user_id.'/3')); ?>">Attended Welcome<br /> Reception</a>
                        <a class="collapse-item" href="<?= (base_url('nva/table/'.$current_user_id.'/4')); ?>">Joined LG</a>
                        <a class="collapse-item" href="<?= (base_url('nva/table/'.$current_user_id.'/0')); ?>">All Visitors</a>
                    </div>
                </div>
            </li>
			



            
<?php 	if($webConfig->checkPermissionByDes(['dashboard_view'])): ?>



            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Explore Campuses</span>
                </a>
                <div id="collapseUtilities" class="collapse " aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        
						
						
						<?php foreach($campuses as $item): ?>
							
							 <a class="collapse-item" href="<?=base_url('nva/table/0/0/0/0/'.str_replace(' ','-',strtolower($item))); ?>"><?= $item; ?></a>
						
						<?php endforeach; ?>
						
						
						
						
						
						


                        <a class="collapse-item" href="<?=base_url('nva/table/0'); ?>">All Visitors</a>
                    </div>
                </div>
            </li>
			
<?php 	endif;  ?>

<?php if($webConfig->checkPermissionByDes(['dashboard_view'])): ?>			
			
			
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url('xAdmin'); ?>">
                    <i class="fas fa-reply"></i>
                    <span>Back to CBI Admin</span></a>
            </li>			
			
			
			
			

<?php 	endif;  ?>






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
						
						
						<?php  if($current_user_id): ?>
						
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $userName; ?></span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                            </a>
							
						<?php  else: ?>	
						
	                        <a class="nav-link " href="<?= (base_url('?redirect='.rawurldecode(base_url('nva')))); ?>">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Login</span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                            </a>					
						
						<?php  endif; ?>	
						
						
						
						<?php  if($current_user_id): ?>
						
						
						
					
						
						
						
						
						
						
						
						
						
							
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

   <?=  $main_content; ?>		

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


    <!-- Page level plugins -->  
    <script src="<?= base_url('assets/elearning/vendor/chart.js/Chart.min.js'); ?>"></script>
 
	
	
	
	<script>
	
var timer  = null; 
var ajaxer  = null; 


	
$( document ).on( "change", "#stage_id", function() {
	
	data = {  new_stage_id: $(this).val(), action:'vistor_field_update', field:'stage_id'} ;  
	
	element = $(this);
	
	vistor_field_update(element,data);
});



	
$( document ).on( "change", "#assigned_to", function() {
	
	data = {  new_assigned_id: $(this).val(), action:'vistor_field_update', field:'assigned_id'} ;  
	
	element = $(this);
	
	vistor_field_update(element,data);
});





	
$( document ).on( "click", ".assign_delegate", function() {
	
	delegateName =  $(this).data('name') ;  
	delegateBid =  $(this).data('bid') ;  
	delegatePhone =  $(this).data('phone') ;  
	delegateEmail =  $(this).data('email') ;  
	
	data = {  new_delegate_id: delegateBid, action:'vistor_field_update', field:'delegate_id'} ;  
	
	element = $('#ajaxMsg');
	
	vistor_field_update(element,data);
	 
	
	$('#assigned_delegate_card_body').html(delegateName+'<br />'+delegateEmail+'<br />'+delegatePhone+'<br />');
});



$( document ).on( "click", "#remove-vistor", function() {
	
	
	var r = confirm("Please press 'OK' to continue");	
	
	if(!r) return;
	
	data = {   action:'vistor_field_update', field:'removeVistor'} ;  
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 	
	
	$('.loading').remove();
	
	
	
	var loadingMsgNode1 = '<p class="loading text-success my-3 text-center"><span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span></p>';

	$(this).after(loadingMsgNode1);
	 
				
		ajaxer = $.ajax({
			method: "POST",
			type: "html",
			data: data,
			url: '<?php echo $canonical; ?>',
			  
			success:function(data){  
				
				if(data=='OK'){ 
					$('.loading').html('updated');
					location.href = "<?= (base_url('nva')); ?>";
				}else{
					$('.loading').html('error');
				}				
			}
		});	
	

	
	
});


$( document ).on( "click", ".remove-delegate", function() {
	
	data = {  new_delegate_id: null, action:'vistor_field_update', field:'delegate_id'} ;  
	
	element = null;
	
	vistor_field_update(element,data);
	 
	$('#assigned_delegate_card_body').html('Updated<br />');
	
});



function vistor_field_update(element,data){
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 	
	
	$('.loading').remove();
	
	
	
	var loadingMsgNode1 = '<p class="loading text-success my-3 text-center"><span class="spinner-border spinner-border-sm " role="status" aria-hidden="true"></span></p>';

	if(element) $(element).after(loadingMsgNode1);
	 
				
		ajaxer = $.ajax({
			method: "POST",
			type: "html",
			data: data,
			url: '<?php echo $canonical; ?>',
			  
			success:function(data){  
				
				if(data=='OK'){ 
					$('.loading').html('updated');
					return 1;
				}else{
					return 0;
				}				
			}
		});	
		
		
}



var searchWrap;
$( document ).on( "click", ".edit-delegate", function() {
	
	if(typeof(searchWrap) != "undefined" && searchWrap !== null) {
			return;
	}
	
	 
	
	var input = document.createElement('input');
	
	var msg = document.createElement('p');
	msg.id = 'ajaxMsg';
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Search';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	
	var title = document.createElement('h3');	
	title.innerHTML = 'Assigned to:';
	
	searchWrap = document.createElement('div');
	searchWrap.id = 'searchBaptismsDiv';
	searchWrap.classList.add("ajaxDiv");
	
	
	searchWrap.appendChild(btC);
	searchWrap.appendChild(title);
	searchWrap.appendChild(input);
	searchWrap.appendChild(btS);
	searchWrap.appendChild(msg);
	searchWrap.appendChild(lists);
	
	
	document.getElementsByTagName("BODY")[0].appendChild(searchWrap);
	
	
	
	btC.addEventListener('click', function(){
		 close();
	});

	btS.addEventListener('click', function(){
		var query = input.value;
		
				$.ajax({
					dataType:'json',
					method: "POST",
					url: '<?php echo $canonical; ?>',
					data: {	query:query, action:'searchBaptisms'},      
					success:function(data){ 
						
						msg.innerHTML = '';
						lists.innerHTML = '';
						
						if(data.length==0){
							msg.innerHTML = 'Did not match any documents.';
						}else{
							
							// console.log(data);
							data.forEach(function(item){
								var p2 = '<a href="javascript:void(0);" data-name="'+item.name+'" data-email="'+item.email+'" data-phone="'+item.mPhone+'" data-bid="'+item.id+'" class="assign_delegate function">Assign</a>';
								var li = document.createElement('li'); 
								li.innerHTML = item.name +' '+p2;
								
								lists.appendChild(li);
							});
							
							
						}	

						
					}
				});			
		
	});		
	
	function close(){
		searchWrap.remove();
		searchWrap = undefined;
	}	
});


    
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
				
				
				
				timer = setTimeout(function() {
					
					ajaxer = $.ajax({
						dataType:'html',
						method: "POST",
						url: '<?= $canonical ?>',
						data: params,      
						success:function(data){
							if(data=='OK'){
								$('.ajaxSubmit .fmsg').html('Updated');
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

 

	</script>
	
	
	
	
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
	
	
<?php

	if(isset($footer)) echo $footer;

?>	

<script>
// Fix sidebar menu expansion for NVA
(function() {
    var currentUrl = window.location.href;
    var currentPath = window.location.pathname;
    
    // Check if URL contains nva/table/0 (Explore Campuses section)
    // This matches URLs like: /nva/table/0 or /nva/table/0/0/0/0/tracy
    if (currentPath.match(/\/nva\/table\/0/)) {
        // Collapse "Your action items"
        $('#collapsePages').removeClass('show');
        // Expand "Explore Campuses"
        $('#collapseUtilities').addClass('show');
        
        // Add active class to matching link in Explore Campuses
        $('#collapseUtilities a.collapse-item').each(function() {
            if ($(this).attr('href') === currentUrl) {
                $(this).addClass('active');
            }
        });
    } 
    // Check if URL contains nva/table/{user_id} where user_id != 0 (Your action items)
    else if (currentPath.match(/\/nva\/table\/\d+/)) {
        // Expand "Your action items"
        $('#collapsePages').addClass('show');
        // Collapse "Explore Campuses"
        $('#collapseUtilities').removeClass('show');
        
        // Add active class to matching link in Your action items
        $('#collapsePages a.collapse-item').each(function() {
            if ($(this).attr('href') === currentUrl) {
                $(this).addClass('active');
            }
        });
    }
    // Default: show "Your action items" for other NVA pages
    else if (currentPath.match(/\/nva/)) {
        $('#collapsePages').addClass('show');
        $('#collapseUtilities').removeClass('show');
    }
})();
</script>
 
	
</body>

</html>