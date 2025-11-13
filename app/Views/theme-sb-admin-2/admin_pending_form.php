<div class="row">
	<div class="col-md-12 stretch-card">
		<div class="card shadow mb-4">
			<div class="card-body">
				<h1 ><?= $pageTitle; ?></h1>			  
				<hr />	
				
				
				
				
				
				
				
				

<div class="sesstion" id="s-baptist">

<?php if(isset($baptist['picture'])&&$baptist['picture']): ?>
<div style="    margin: 20px 0;" id="picWrap">
User picture:<br />

<a id="thePictureLink" href="<?= (base_url('xAdmin/baptist/'.$bid));?>"><img id="thePicture" src="<?= $baptist['picture']; ?>" width="150" height="150" /></a><br />

</div>
<?php endif; ?>

<form method="post" class="rForm" id="baptistForm" onkeydown="if(event.keyCode==13){return false;}">

	<p>Applicant’s Full Name: <input  class="required form-control" title="Applicant’s Full Name" type="text" value="<?= (isset($formData['fullname'])?$formData['fullname']:''); ?>" name="fullname" id="fullname" /></p>
	
	<p>Chinese 中文全名: <input class="form-control" type="text" value="<?= (isset($formData['cname'])?$formData['cname']:''); ?>" name="cname" id="cname" /></p>

	<p>
		<input  <?= (isset($formData['bornagain'])&&$formData['bornagain']?'checked':''); ?> type="checkbox" id="bornagain" name="bornagain" value="1"> <label for="bornagain">Must be a born-again Christian</label> 
		<a target="_blank" href="<?= base_url('xAdmin/baptist/'.$bid.'#membership') ?>" class="invite">(<?= $formData['fullname'];?>'s Crosspoint Membership Record)</a>

	</p>
	
	<p>
		<input <?= (isset($formData['submittedST'])&&$formData['submittedST']?'checked':''); ?> type="checkbox" id="submittedST" name="submittedST" value="1"> <label for="submittedST">Submitted a salvation testimony</label> 	
		<?php if($hasTestimony): ?>
			<a target="_blank" href="<?= base_url('xAdmin/baptist/'.$bid.'#testimonyFile') ?>" class="invite">(<?= $formData['fullname'];?>'s Testimony)</a>
		<?php else: ?>	
			<a target="_blank" href="<?= base_url('xAdmin/baptist/'.$bid.'#testimonyFile') ?>" class="invite">(No Testimony on file)</a>
		<?php endif; ?>
	</p>
	

	
	
	
	<p <?= ($attendance101<75?'class="disabled"':''); ?>>
		<input <?= ($completed101Checked?'checked':''); ?> type="checkbox" id="completed101" name="completed101" value="1"> 
		<label for="completed101" >
			<?php if($is_self_paced): ?>
				Completed Crosspoint Basics (Self-paced online class completed and zone pastor approved.<?= ($completionDate?' Completion date: '.$completionDate:''); ?>) 
			<?php else: ?>
				Completed Crosspoint Basics (101 attendance must be 75% or more. Current attendance: <?= $attendance101;?>%.<?= ($completionDate?' Completion date: '.$completionDate:''); ?>)
			<?php endif; ?>	
		</label>
	</p>	
	
	

	
	
	
	
	
	<p><input <?= (isset($formData['completedMAF'])&&$formData['completedMAF']?'checked':''); ?> type="checkbox" id="completedMAF" name="completedMAF" value="1"> <label for="completedMAF">Completed Membership Application Form, including the signed Membership Covenant</label></p>
	
	<p><input <?= (isset($formData['passOI'])&&$formData['passOI']?'checked':''); ?> type="checkbox" id="passOI" name="passOI" value="1"> <label for="passOI">Pass oral interview by Zone Pastor</label></p>
	
	
	<div id="pastorsNote">Pastor’s Note(please specify the reason for exemption on any of the above requirements):<br /> <textarea class="form-control" name="pastorsNote" rows="12" cols="70"><?= (isset($formData['pastorsNote'])?$formData['pastorsNote']:''); ?></textarea>	
	</div>
	
	
	<div class="group-1">
	
	
		<h4>I certify that the named applicant has fulfilled all the above requirements and is recommended to become a Crosspoint member by: </h4>
		
		<p>
		
		<input <?= (isset($formData['receivingBaptism'])&&$formData['receivingBaptism']?'checked':''); ?> type="checkbox" id="receivingBaptism" name="receivingBaptism" value="1"> 
		
		<label for="receivingBaptism">Received Baptism at Crosspoint Church on:</label> 
		
		<input class="required dateInput form-control" <?= (isset($formData['receivingBaptism'])&&$formData['receivingBaptism']?'':'disabled'); ?> title="Receiving Baptism at Crosspoint Church on..." type="text" value="<?= (isset($formData['receivingBaptismOn'])&&$formData['receivingBaptismOn']?date('m/d/Y',$formData['receivingBaptismOn']):''); ?>" name="receivingBaptismOn" id="receivingBaptismOn" />
		
		</p>

		<p>- OR -</p>
		
		<p><input  <?= (isset($formData['anotherChurch'])&&$formData['anotherChurch']?'checked':''); ?> type="checkbox" id="anotherChurch" name="anotherChurch" value="1" /> <label for="anotherChurch">Submitted evidence of immersion baptism and/or church membership from another church that practices immersion baptism.</label>
		
		
		
			<?php if($hasBaptismCertificate): ?>
				<a target="_blank" href="<?= base_url('xAdmin/baptist/'.$bid.'#baptismCertificateFile') ?>" class="invite">(<?= $formData['fullname'];?>'s Baptism Certificate)</a>
			<?php else: ?>	
				<a target="_blank" href="<?= base_url('xAdmin/baptist/'.$bid.'#baptismCertificateFile') ?>" class="invite">(No Baptism Certificate on file)</a>
			<?php endif; ?>	
		
		
		<br />
		<input class="form-control" <?= (isset($formData['evidence'])&&$formData['evidence']?'':'disabled'); ?> type="text" style="width: 100%;" value="<?= (isset($formData['evidence'])&&$formData['evidence']?$formData['evidence']:''); ?>" name="evidence" id="evidence" />
		
		
		
		
		</p>
	
	
	</div>
	
	<p>Pastor’s Signature:  <input  class="required form-control" title="Pastor’s Signature" type="text" value="<?= (isset($formData['pastor'])?$formData['pastor']:''); ?>" name="pastor" id="pastor" /></p>	
	
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


	

	
<?php if($baptist['inactive']==3): ?>


 

<p style="font-weight: bold; background: #fff067; padding: 10px; font-size: 21px; }">
<?= $baptist['fName']; ?> <?= $baptist['lName']; ?> is already a member!</p>


<?php else: ?>
	<p class="bts">	
		<input class="form-control" type="hidden" name="bid" value="<?= $bid; ?>" />
		<input type="hidden" name="action" value="formSubmit" />
		
		<input type="submit"  class="btn btn-primary px-5" id="btSubmit" value="<?= $fsubmitLable; ?>" />	
	</p>
<?php endif; ?>	




	
	<p class="fmsg"></p>

</form>

</div>				
				
				
				
				
				
				
				
				
				
			</div>
		</div>
	</div>
</div>













	
	
<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>
 

<style>

#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}
input,input:focus{ border: 1px #444 solid;}
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



$("#baptistForm").submit(function(event){
	
    event.preventDefault();

	var error='';
	$('#baptistForm .fmsg').html('Loading...');
	
	error = validater(document.getElementById('baptistForm'));
	
	
	var n = $( '[type="checkbox"]:checked' ).length; console.log(n);
	if(n<6){
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






</script>
