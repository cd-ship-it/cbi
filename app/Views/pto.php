
<!DOCTYPE html>
<html lang="en-US">
<head>

<!-- COMMON TAGS -->
<meta charset="utf-8">

<title><?= $pageTitle; ?></title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<meta name="viewport" content="width=device-width, initial-scale=1" />

<script type="text/javascript" src="<?= base_url(); ?>/assets/jquery-3.3.1.min.js"></script>
<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.js'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css' type='text/css'/>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/layout.css">

<style>


#pageDescription{margin: 0 10px 20px 10px;}
#layout{ padding:0 10px;}

#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

#adminList p { margin: 5px 0;}

tr.highlight{    background: #FFEB3B;}

#userOtherPosts p {margin: 5px 0;}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>

<div id="layout">

<div  style=" background: #eee; padding: 15px; font-size: 12px; margin: 15px 0; ">
	
	<p>Direct Supervisor:  <?= $supervisor ;?>
	</p>

	
	<p>Region Pastor:  <?= $region_pastor ;?>
	</p>

	
	<p>Senior Pastor:  <?= $senior_pastor ;?>
	</p>




	
	<p>Operations Director: <?= $operations_director ;?>
	</p>	
	
	<p>PTO Balance: <?= $balance ;?> day(s) 
	
		<?php if( $update_schedule ): ?>
		
			(update schedule on  <?= $update_schedule; ?> | +<?= $rate_per_month; ?>/per month)
		
		<?php endif; ?>	
	
	
	</p>



</div>
	



<form style=" margin: 30px 0; " action="<?= base_url('pto'); ?>" method="post" class="rForm myAjForm" id="ptoForm" onkeydown="if(event.keyCode==13){return false;}">

 <fieldset>
  <legend>Apply Leave</legend>
  
	<p>
	Leave Types:	
	<select name="leave-types" class="required" title="Leave Types" id="leave-types">
	<option value ="">---</option>
	<option value ="PTO">PTO</option>
	<option value ="Speaking Engagement">Speaking Engagement</option>
	<option value ="Sick Leave">Sick Leave</option>
	<option value ="No Paid Leave">No Paid Leave</option>
	<option value ="Sabbatical Leave">Sabbatical Leave</option>
	<option value ="Other Leave">Other Leave</option>
	</select>
	</p>

	
	<p>Start Date: <input autocomplete=off value=""  name="start-date" title="Start Date" id="start-date" class="dateInput required" /></p>
	
	<p>End Date: <input autocomplete=off value="" title="End Date"  name="end-date" id="end-date" class="dateInput required" /></p>
	
	
	Remarks/Notes:<br /> <textarea id="notes" name="notes" rows="4" cols="50"></textarea>
	
	
	

	
	<p class="bts">
	
	<input type="hidden"  name="action" value="applyLeave" />
	<input <?= ($operations_director?'':'disabled'); ?> type="submit"  id="btSubmit" value="Submit" />  
	
	</p>
	<p class="fmsg"></p>
</fieldset>
</form>





<?php

//var_dump($userOtherPosts);

 if($userOtherPosts): ?>
<div id="userOtherPosts">

<h3>Your Application(s): </h3>

	<?php foreach($userOtherPosts as $post): 
	
			if($post['status']==1){
				$theStatus = 'Awaiting Approval';
			}elseif($post['status']==0){
				$theStatus = 'Disapproved: Please read remark';
			}elseif($post['status']==-1){
				$theStatus = 'Cancelled';;
			}else{
				$theStatus = 'Approved';
			}
	
		$postTitle = $post['types'].' ('. date("m/d/Y",$post['start']) .  ($post['end']&&$post['start']!==$post['end']? '-'.date("m/d/Y",$post['end']) : '')  . ') submitted  on '. date("m/d/Y",$post['submit_date']) . ' ['.$theStatus.']' ;
	
	?>

		<p><a href="<?= base_url('pto/'.$post['id']); ?>"><?= $postTitle; ?></a></p>
			
	
	<?php endforeach; ?>
</div>
<?php endif; //userOtherPosts ?>



</div>



<script type="text/javascript">



var startDate = document.getElementById('start-date');
var endDate = document.getElementById('end-date');

rome(startDate, {
  time:false,
  inputFormat: "MM/DD/YYYY",
  min: '<?= (date("m/d/Y"));?>',
});





rome(endDate, {
  time:false,
  inputFormat: "MM/DD/YYYY",
  dateValidator: rome.val.afterEq(startDate) 
});

	 
	 







var timer  = null; 
var ajaxer  = null; 

$(".myAjForm").submit(function(event){
	
    event.preventDefault();

	var error='';
	$('.myAjForm .fmsg').html('Loading...');
	
	error = validater($('.myAjForm'));
	
	if(error){
		$('.myAjForm .fmsg').html(error);
	}else{
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = $(this).serialize();
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: '<?= base_url("pto"); ?>',
				data: params,      
				success:function(data){
					
						$('.myAjForm .fmsg').html(data);

					
				}
			});
			
		 },600);
		 
		
		
	}
	
});
 




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




function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}






</script>

</body>
</html>