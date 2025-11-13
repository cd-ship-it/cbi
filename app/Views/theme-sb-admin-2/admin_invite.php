<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 ><?= $pageTitle; ?></h1>			  
			<hr />	





<div class="sesstion" >

	<form action="<?= $canonical; ?>" method="post" class="rForm" id="inviteForm" onkeydown="if(event.keyCode==13){return false;}">

		<p>Email(required): <input class="required email form-control" title="Email" type="text" value="<?= $email; ?>" name="toEmail" id="toEmail" /></p>
		
		<div id="messageDiv">Message:<br /> <textarea  class="form-control mb-3"  id="message" name="message" rows="12" cols="50"><?= $preMessage; ?></textarea></div>	

		<p class="bts">	
			<input type="hidden" name="action"  value="send" />
			<input type="submit" class="btn btn-primary px-5" name="action" id="btSubmit" value="<?= $fsubmitLable; ?>" />	
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


$("#inviteForm").submit(function(event){
    event.preventDefault();

	var error='';
	$('.fmsg').html('Loading...');
	
	error = validater(document.getElementById('inviteForm'));
	
	if(error){
		$('.fmsg').html(error);
	}else{
		
		
		var params = $(this).serialize();
		
		
			
			$.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: params,      
				success:function(data){
					
						$("#inviteForm").find("input[type=text], textarea").val("");
						$('#inviteForm .fmsg').html(data);

					
				}
			});
			
		
		 
		
		
	}
	
});
	
	
	
	


</script>







