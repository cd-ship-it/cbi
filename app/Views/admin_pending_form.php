






<!DOCTYPE html>
<html lang="en-US">
<head>

<!-- COMMON TAGS -->
<meta charset="utf-8">

<title><?= $pageTitle;?></title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<meta name="viewport" content="width=device-width, initial-scale=1" />

<script type="text/javascript" src="<?= base_url(); ?>/assets/jquery-3.3.1.min.js"></script>
<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css' type='text/css'/>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/layout.css">

<style>

#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}

#results div div{ padding:10px; border-left: 1px solid #ccc;}
#results p { font-size:14px;line-height:18px;margin: 10px 0;}

.invite{margin:10px;    display: inline-block;}

#baptistForm p{margin:15px 0;}

#baptistForm .disabled{
	color:red;
}


#spp{    padding: 20px;
    background: #ffeb59;
    border-radius: 5px;}
	

.group-1 {    background: #eee;    padding: 10px;    margin: 25px 0;}	

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>



<div class="sesstion" id="s-baptist">

<?php if(isset($baptist['picture'])&&$baptist['picture']): ?>
<div style="    margin: 20px 0;" id="picWrap">
User picture:<br />

<a id="thePictureLink" href="<?= (base_url('xAdmin/baptist/'.$bid));?>"><img id="thePicture" src="<?= $baptist['picture']; ?>" width="150" height="150" /></a><br />

</div>
<?php endif; ?>

<form method="post" class="rForm" id="baptistForm" onkeydown="if(event.keyCode==13){return false;}">

	<p>Applicant’s Full Name: <input  class="required" title="Applicant’s Full Name" type="text" value="<?= (isset($formData['fullname'])?$formData['fullname']:''); ?>" name="fullname" id="fullname" /></p>
	
	<p>Chinese 中文全名: <input type="text" value="<?= (isset($formData['cname'])?$formData['cname']:''); ?>" name="cname" id="cname" /></p>

	<p>
		<input <?= (isset($formData['bornagain'])&&$formData['bornagain']?'checked':''); ?> type="checkbox" id="bornagain" name="bornagain" value="1"> <label for="bornagain">Must be a born-again Christian</label> 
		<?php if($hasMRecord): ?>
			<a target="_blank" href="<?= (base_url('member/submit?bid='.$bid));?>" class="invite">(<?= $formData['fullname'];?>'s Crosspoint Membership Record and Covenant)</a>
		<?php else: ?>	
			(No Membership Form on file)
		<?php endif; ?>
	</p>
	
	<p>
		<input <?= (isset($formData['submittedST'])&&$formData['submittedST']?'checked':''); ?> type="checkbox" id="submittedST" name="submittedST" value="1"> <label for="submittedST">Submitted a salvation testimony</label> 	
		<?php if($hasTestimony): ?>
			<a target="_blank" href="<?= (base_url('member/testimony?bid='.$bid));?>" class="invite">(<?= $formData['fullname'];?>'s Testimony)</a>
		<?php else: ?>	
			<a target="_blank" href="<?= (base_url('member/testimony?bid='.$bid));?>" class="invite">(No Testimony on file)</a>
		<?php endif; ?>
	</p>
	
	<p><input <?= (isset($formData['grouplife'])&&$formData['grouplife']?'checked':''); ?> type="checkbox" id="grouplife" name="grouplife" value="1"> <label for="grouplife">Active Participation in life Group for at least 3 months.</label></p>
	
	<p><input <?= (isset($formData['completedGU1'])&&$formData['completedGU1']?'checked':''); ?> type="checkbox" id="completedGU1" name="completedGU1" value="1"> <label for="completedGU1">Completed Gear-up 1, New Believer’s Class or exempted by Zone Pastor if the person was a former member of a church</label></p>
	
	<p <?= ($attendance101<75?'class="disabled"':''); ?>><input <?= (isset($formData['completed101'])&&$formData['completed101']?'checked':''); ?>  <?= ($attendance101<75?'disabled="disabled"':''); ?> type="checkbox" id="completed101" name="completed101" value="1"> <label for="completed101" >Completed Crosspoint Basics 101</label> (101 attendance must be 75% or more. Current attendance: <?= $attendance101;?>%)</p>
	
	<p><input <?= (isset($formData['completedMAF'])&&$formData['completedMAF']?'checked':''); ?> type="checkbox" id="completedMAF" name="completedMAF" value="1"> <label for="completedMAF">Completed Membership Application Form, including the signed Membership Covenant</label></p>
	
	<p><input <?= (isset($formData['passOI'])&&$formData['passOI']?'checked':''); ?> type="checkbox" id="passOI" name="passOI" value="1"> <label for="passOI">Pass oral interview by Zone Pastors</label></p>
	
	
	<div id="pastorsNote">Pastor’s Note(please specify the reason for exemption on any of the above requirements):<br /> <textarea name="pastorsNote" rows="12" cols="70"><?= (isset($formData['pastorsNote'])?$formData['pastorsNote']:''); ?></textarea>	
	</div>
	
	
	<div class="group-1">
	
	
		<h4>I certify that the named applicant has fulfilled all the above requirements and is recommended to become a Crosspoint member by: </h4>
		
		<p>
		
		<input <?= (isset($formData['receivingBaptism'])&&$formData['receivingBaptism']?'checked':''); ?> type="checkbox" id="receivingBaptism" name="receivingBaptism" value="1"> 
		
		<label for="receivingBaptism">Receiving Baptism at Crosspoint Church on: </label> 
		
		<input class="required dateInput" <?= (isset($formData['receivingBaptism'])&&$formData['receivingBaptism']?'':'disabled'); ?> title="Receiving Baptism at Crosspoint Church on..." type="text" value="<?= (isset($formData['receivingBaptismOn'])&&$formData['receivingBaptismOn']?date('m/d/Y',$formData['receivingBaptismOn']):''); ?>" name="receivingBaptismOn" id="receivingBaptismOn" />
		
		</p>

		<p>- OR -</p>
		
		<p><input <?= (isset($formData['anotherChurch'])&&$formData['anotherChurch']?'checked':''); ?> type="checkbox" id="anotherChurch" name="anotherChurch" value="1"> <label for="anotherChurch">Submitting evidence of baptism and/or church membership from another church: </label>
		
		
		
			<?php if($hasBaptismCertificate): ?>
				<a target="_blank" href="<?= (base_url('member/baptismCertificate?bid='.$bid));?>" class="invite">(<?= $formData['fullname'];?>'s Baptism Certificate)</a>
			<?php else: ?>	
				<a target="_blank" href="<?= (base_url('member/baptismCertificate?bid='.$bid));?>" class="invite">(No Baptism Certificate on file)</a>
			<?php endif; ?>	
		
		
		<br />
		<input <?= (isset($formData['evidence'])&&$formData['evidence']?'':'disabled'); ?> type="text" style="    width: 100%;" value="<?= (isset($formData['evidence'])&&$formData['evidence']?$formData['evidence']:''); ?>" name="evidence" id="evidence" />
		
		
		
		
		</p>
	
	
	</div>
	
	<p>Pastor’s Signature:  <input class="required" title="Pastor’s Signature" type="text" value="<?= (isset($formData['pastor'])?$formData['pastor']:''); ?>" name="pastor" id="pastor" /></p>	
	
	<?php if( isset($formData['date']) && $formData['date'] ): ?>
		<p style=" color: green; font-weight: bold; "> 
			<?= $formData['pastor']; ?> approved on <?= date('m/d/Y',$formData['date']); ?>
		</p>			
	<?php endif; ?>
	
	 


	<?php if($showAdminApproval): ?>
		<p id="spp">Admin Approval:  
		<input id="ig" name="inactive" type="radio" value="3"> <label for="ig">Agree</label> | 
		<input id="ing" name="inactive" type="radio" value="4"> <label for="ing">Not agree</label>
		</p>			
	<?php endif; ?>
	
	
	<p class="bts">	
		<input type="hidden" name="bid" value="<?= $bid; ?>" />
		<input type="hidden" name="action" value="formSubmit" />
		
		<input type="submit"  id="btSubmit" value="<?= $fsubmitLable; ?>" />	
	</p>
	
	<p class="fmsg"></p>

</form>

</div>





<script type="text/javascript">


$('.dateInput').each(function(index) {	
var e = document.getElementById($(this).attr('id'));
  rome(e, {
	  time:false,
	  inputFormat: "MM/DD/YYYY",
	});
});


$('#receivingBaptism').on('change', function() {
	if($(this).is(":checked")){
		$('#anotherChurch').prop('checked', false);
		$('#evidence').val('').prop('disabled', true);
		$('#receivingBaptismOn').prop('disabled', false);

	}
	
});

$('#anotherChurch').on('change', function() { 
	if($(this).is(":checked")){
		$('#receivingBaptism').prop('checked', false);
		$('#receivingBaptismOn').val('').prop('disabled', true);
		$('#evidence').prop('disabled', false);
	}
	
});

var timer  = null; 
var ajaxer  = null; 

$("#baptistForm").submit(function(event){
	
    event.preventDefault();

	var error='';
	$('#baptistForm .fmsg').html('Loading...');
	
	error = validater(document.getElementById('baptistForm'));
	
	
	var n = $( '[type="checkbox"]:checked' ).length; console.log(n);
	if(n<8){
		error += 'All items must be checked to join membership.<br />'
	}
	
	if(error){
		$('#baptistForm .fmsg').html(error);
	}else{
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = $(this).serialize();
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				data: params,      
				success:function(data){
					
						$('#baptistForm .fmsg').html(data);

					
				}
			});
			
		 },600);
		 
		
		
	}
	
});







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

</body>
</html>


