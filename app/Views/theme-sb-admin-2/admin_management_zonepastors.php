<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 class="card-title" >Pastor who is required to submit monthly reports:  <span class="msg"></span></h1>			  
			<hr />	



 

 
	

 


 




			<div style="    padding: 10px; margin-bottom:40px;" >
			
	
		 
				
			
				<?php
					
					foreach($pastors as $thePastor){
						

						$is_selected =  (in_array($thePastor['bid'],$zone_pastors) ?'checked':'') ;   
						
						 echo '<div class="option"><input '.$is_selected.'  type="checkbox" class="pastor" id="id'. $thePastor['bid'] .'"  value="'. $thePastor['bid'] .'"  > <label for="id'. $thePastor['bid'] .'">'. $thePastor['name'] .'</label> <a class="setting" data-bid="'. $thePastor['bid'] .'" href="javascript:void(0);">設置</a></div>';
						 
						 
					}
					
					//print_r($available_drivers);
				
				?>
			
			<span style="clear: both;display: block;"></span>
			</div>	




 




</div>
</div>
</div>
</div>







 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>
	
	
	

<style>


#pageDescription{margin: 0 10px 20px 10px;}


#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000; z-index:9999; overflow-y: auto;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

.setting{ display:none;}

#adminList p { margin: 5px 0;}

tr.highlight{    background: #FFEB3B;}

.option{width:250px; float:left;} 

@media screen and (max-width:375px ) {
	
	
}
</style>	



<script type="text/javascript">

 


		

$( document ).on( "change", "input.pastor", function() {
	

	

	var uid = $(this).val();
	var val =  $(this).prop("checked")?1:0;
	
	
	$('.msg').html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { uid: uid, val: val, action:'setting'},     
			success:function(data){   
				$('.msg').html('');	console.log(data);				
			}
		});
		

	 
	 
	 
});


$( ".option" ).hover(
  function() {
	  
	var val =  $( this ).children( ".pastor" ).prop("checked")?1:0;
	
	if(val){
		 $( this ).children( ".setting" ).show();
	}
	
	
  }, function() {
    $( this ).children( ".setting" ).hide();
  }
);
		

$( document ).on( "click", ".setting", function() {
	
	$('#addAdminDiv').remove();
	

	var bid = $(this).data('bid');
	var currentElement = $(this);
	
	
	$('.msg').html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { bid: bid, action:'view_report_setting'},     
			success:function(data){   
				$('.msg').html('');	
				$('#settingDiv').remove();
				$(currentElement).parent().after(data);
					
				
			}
		});
		

	 
	 
	 
});		


$( document ).on( "change", ".view_report_change", function() {
	

	

	var reporter = $(this).data('reporter');
	var uid = $(this).val();
	var val =  $(this).prop("checked")?1:0;
	var ajmsg = $(this).siblings('.ajmsg');
	
	$(ajmsg).html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { uid: uid, val: val, reporter: reporter, action:'view_report_change'},     
			success:function(data){ 

				if(data=='OK'){
					$(ajmsg).html('Saved');
				}else{
					$(ajmsg).html('Error');
				}
					console.log(data);				
			}
		});
		

	 
	 
	 
});


$( document ).on( "click", "#closeTw", function() {
	

	
$('#addAdminDiv').remove();

	 
	 
	 
});


</script>

