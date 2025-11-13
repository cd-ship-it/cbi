<!DOCTYPE html>
<html lang="en-US">
<head>

<!-- COMMON TAGS -->
<meta charset="utf-8">

<title><?= $pageTitle; ?></title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<meta name="viewport" content="width=device-width, initial-scale=1" />

<script type="text/javascript" src="<?= base_url(); ?>/assets/jquery-3.3.1.min.js"></script>
<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css' type='text/css'/>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/layout.css">

<style>

 
#layout{ padding:0 10px;}
p{ margin: 10px 0;}

textarea {    width: 100%;}
h1.pageTitle{ margin: 0 10px 20px 0;}
h2{ margin:40px 0 10px 0;}
h3{ margin:30px 0 10px 0;}
form div{ margin-bottom:15px;}

.report_view .bts{ display:none; }
.report_view textarea, .report_view input {     border: none;    background: #eee;      font-size: 18px;
    font-weight: bold;
    color: black;}


@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	   

?>


 
<div id="layout" class="<?= (isset($model)&&$model=='report_view'?'report_view':''); ?>">



<h1 class="pageTitle"><?= $pageTitle; ?></h1>
















	
	
			



<form autocomplete="off" method="post" class="rForm" id="report_submit_form">

<p style="font-weight: bold; background: #fff067; padding: 10px; font-size: 21px; }">
Name of Pastor: <?= $submitter; ?><br />
Month of <?= $year; ?>: <?= $month; ?>
</p>


<h2>A. Zone Update</h2>

<h3>a.	Life Groups</h3>



	<p>1.	Number of Life Groups beginning of <?= $year; ?>: <input class="required" <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="a1" name="a1" title="1.	Number of Life Groups beginning of <?= $year; ?>"  value="<?= (isset($report['a1'])?$report['a1']:''); ?>"  />
	</p>

	<p>2.	Number of Life Groups this month: <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="a2" name="a2" title="2. Number of Life Groups this month" value="<?= (isset($report['a2'])?$report['a2']:''); ?>"  />
	</p>

	<p>3.	Approximate number of people who should attend life groups: <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="a3" name="a3" title="3.	Approximate number of people who should attend life groups"  value="<?= (isset($report['a3'])?$report['a3']:''); ?>" />
	</p>

	<p>4.	Approximate number of people attending steadily life groups this month: <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="a4" name="a4" title="4.	Approximate number of people attending steadily life groups this month" value="<?= (isset($report['a4'])?$report['a4']:''); ?>" />
	</p>
	



	<div >Comments on the above data:<br /> 
	
	
	
	
<textarea <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> id="a_comment" name="a_comment" rows="10" cols="50"><?= (isset($report['a_comment'])?$report['a_comment']:''); ?></textarea>	
	
	
	
	
	</div>	
	




<h3>b.	Zone Discipleship System</h3>




	<p>1. NBC(New Believers Class) commenced this month: <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="b1" name="b1" title="1. NBC(New Believers Class) commenced this month"  value="<?= (isset($report['b1'])?$report['b1']:''); ?>"  />
	</p>

	<p>2. How many DNA groups you are having this month? <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="b2" name="b2" title="2. How many DNA groups you are having this month?" value="<?= (isset($report['b2'])?$report['b2']:''); ?>"  />
	</p>

	<p>3. How many people are in your DNA group this month?  <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="b3" name="b3" title="3. How many people are in your DNA group this month?"  value="<?= (isset($report['b3'])?$report['b3']:''); ?>" />
	</p>

	<p>4. How many people are in your LC  group this month?  <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="b4" name="b4" title="4. How many people are in your LC  group this month? " value="<?= (isset($report['b4'])?$report['b4']:''); ?>" />
	</p>
	



	<div >If all of the above numbers are 0, please comment on why this happened:<br /> 
	
	
	
	
<textarea <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> id="b_comment" name="b_comment" rows="10" cols="50"><?= (isset($report['b_comment'])?$report['b_comment']:''); ?></textarea>	
	
	
	
	
	</div>	






<h3>c.	Zone Outreach Activities</h3>
	<p>1.	How many outreach events(target unchurched, all life groups added together) for this month? That may include group based or join campus/church based events <input  class="required"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> type="number" id="c1" name="c1" title="How many outreach events for this month?" value="<?= (isset($report['c1'])?$report['c1']:''); ?>"  />
	</p>
	<div >If the above number is 0, please comment on why this happened:<br /> 
	
	
	
	
<textarea <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> id="c_comment" name="c_comment" rows="10" cols="50"><?= (isset($report['c_comment'])?$report['c_comment']:''); ?></textarea>	
	
	
	
	
	</div>	







<h2>B.	Planning(Please answer the following questions completely)</h2>


<p>As a zone pastor, besides what you have been using to do ministry during the SIP situation(Zoom meetings, daily devotions and prayer, YouTube Live worship, Bible classes), what will you do in addition in the upcoming months in order to :</p>



	<div >1. Reach out to the unchurched or the "disappeared"? <br /> 	
<textarea class="required" title="Reach out to the unchurched or the 'disappeared'?"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> id="p1" name="p1" rows="10" cols="50"><?= (isset($report['p1'])?$report['p1']:''); ?></textarea>	
	</div>	


	<div >2. Facilitate life group growth? <br /> 	
<textarea class="required" title="Facilitate life group growth?"  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> id="p2" name="p2" rows="10" cols="50"><?= (isset($report['p2'])?$report['p2']:''); ?></textarea>	
	</div>	


	<div >3. Promote DNA? <br /> 	
<textarea class="required" title="Promote DNA? "  <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> id="p3" name="p3" rows="10" cols="50"><?= (isset($report['p3'])?$report['p3']:''); ?></textarea>	
	</div>	


	<div >4. What assistance do you want your supervisor/Senior Pastor to offer in order to accomplish the above? <br /> 	
<textarea class="required" title="PWhat assistance do you want your supervisor/Senior Pastor to offer in order to accomplish the above?"   <?= (isset($model)&&$model=='report_view'?'disabled ':''); ?> id="p4" name="p4" rows="10" cols="50"><?= (isset($report['p4'])?$report['p4']:''); ?></textarea>	
	</div>	










	<p class="fmsg"></p>









	<?php if($allow_submit): ?>
	<p class="bts">
	

	
	
	<input type="hidden" name="action" value="report_submit" />  
	<input type="submit" id="btSubmit" value="Submit" />  
	

	
	</p>
	<?php endif; ?>

</form>
















<?php if(isset($model)&&$model=='report_view'): ?>

<h1 class="pageTitle"style=" text-align: right; "><a href="<?= $goBackUrl; ?>">Back</a></h1>


<?php endif; ?>










</div>




<script type="text/javascript">

var timer  = null; 
var ajaxer  = null; 





$(".rForm").submit(function(event){
	
    event.preventDefault();

	var error='';
	$('.rForm .fmsg').html('Loading...');
	
	error = validater(document.getElementById('report_submit_form'));
	
	if(error){
		$('.rForm .fmsg').html(error);
	}else{
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = $(this).serialize();
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: '<?= $canonical; ?>',
				data: params,      
				success:function(data){
					
						$('.rForm .fmsg').html(data);

					
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