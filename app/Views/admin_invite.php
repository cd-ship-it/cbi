
<!DOCTYPE html>
<html lang="en-US">
<head>

<!-- COMMON TAGS -->
<meta charset="utf-8">

<title><?= $pageTitle; ?></title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<meta name="viewport" content="width=device-width, initial-scale=1" />

<script type="text/javascript" src="<?= base_url(); ?>/assets/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/layout.css">

<style>


@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>



<div class="sesstion" >

	<form action="<?= $canonical; ?>" method="post" class="rForm" id="inviteForm" onkeydown="if(event.keyCode==13){return false;}">

		<p>Email(required): <input class="required email" title="Email" type="text" value="<?= $email; ?>" name="toEmail" id="toEmail" /></p>
		
		<div id="messageDiv">Message:<br /> <textarea id="message" name="message" rows="8" cols="50"><?= $preMessage; ?></textarea></div>	

		<p class="bts">	
			<input type="hidden" name="action"  value="send" />
			<input type="submit" name="action" id="btSubmit" value="<?= $fsubmitLable; ?>" />	
		</p>
		
		<p class="fmsg"></p>

	</form>

</div>





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



function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function validater(selector){
	var error='';
	
	$(selector).find('.required').each(function(index) {
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

</script>

</body>
</html>