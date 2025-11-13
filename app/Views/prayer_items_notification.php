
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
#message{width:100%;}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	

?>

<h1 class="pageTitle"><?= $pageTitle; ?> <a href="<?= $backPage; ?>">返回</a></h1>



<div class="sesstion" >

	<form action="<?= $canonical; ?>" method="post" class="rForm" id="sendNotificationForm"  >

		
		
		<div id="messageDiv">Message:<br /> <textarea id="message" name="message" rows="18" cols="50"><?= $preMessage; ?></textarea></div>	

		<p class="bts">	
			<input type="hidden" name="action"  value="send" />
			<input type="submit" name="action" id="btSubmit" value="Send" />	
		</p>
		
		<p class="fmsg"></p>

	</form>

</div>





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

</body>
</html>