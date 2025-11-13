<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 class="pageTitle"><?= $pageTitle; ?> <a href="<?= $backPage; ?>">Back</a></h1>			  
			<hr />	





<div class="sesstion" >

	<form action="<?= $canonical; ?>" method="post" class="rForm" id="sendNotificationForm"  >

		
		
		<div id="messageDiv">Message:<br /> <textarea class="form-control mb-3" style="width:100%" id="message" name="message" rows="18" cols="50"><?= $preMessage; ?></textarea></div>	

		<p class="bts">	
			<input type="hidden" name="action"  value="send" />
			<input class="btn btn-primary px-5" type="submit" name="action" id="btSubmit" value="Send" />	
		</p>
		
		<p class="fmsg"></p>

	</form>

</div>

</div>
</div>
</div>
</div>



 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>


<script type="text/javascript">




$("#sendNotificationForm").submit(function(event){
    event.preventDefault();

	var error='';
	$('.fmsg').html('Loading...');
	

		
		
		var params = $(this).serialize();
		
		
			
			$.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: params,      
				success:function(data){
					
						
						$('#sendNotificationForm .fmsg').html(data);

					
				}
			});
			
		
		 
		
		
	
	
});





</script>