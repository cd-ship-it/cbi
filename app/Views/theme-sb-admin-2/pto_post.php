<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h3 ><?= $pageTitle; ?></h3>			  
			<hr />	




  <div class="row">
    <div class="col-md">
     <h5>Submitted by <?= $userName; ?></h5>
    </div>
    <div class="float-right">
      <?php

			 if( $allowDelete ){
				 
				 
				 echo '<a style="padding:0 20px;" onclick="deletePost('.$pid.')" href="javascript: void(0);">x delete post</a> ';
			 }


			 if( $isSubmitter ){	

				if($allowCancel){
					
					echo ' <a style="padding:0 20px;" onclick="cancelPost('.$pid.')" href="javascript: void(0);">x Cancel PTO request</a>';
				 
				}	
			 }

		?>
    </div>
  </div>




 

 
  <?php
  
  if( $isSubmitter ){	

	if(!$allowCancel){
		
		echo '<p style=" color: green; ">僅限'.  $start  .'日前可撤銷</p>';
	 
	}
	
	
	
 }
 ?>

<div  style=" background: #eee; padding: 5px; font-size: 12px; margin: 15px 0; ">
	
<?php if($supervisor): ?>	

	<p>Direct Supervisor:  <?= $supervisor ;?>	</p>

<?php endif; ?>	



	
	<p>PTO Balance: <?= $balance ;?> day(s)  
	
		<?php if( $update_schedule ): ?>
		
			 [update schedule on  <?= $update_schedule; ?> | +<?= $rate_per_month; ?>/per month]
		
		<?php endif; ?>	
	
	
	</p>

	<?php if($showPtoEdit): ?>
	<a href="<?= base_url('xAdmin/baptist/'.$bid.'#pto'); ?>">Edit</a>
	<?php endif; ?>
</div>


<p>Leave Types: <?= $types; ?></p>
<p>Start Date: <?= $start; ?><br />End Date: <?= $end; ?></p>
<p>Notes:<br /> <?= $notes; ?></p>




<div id="comment" style="    margin: 25px 0;    background: #eee;    padding: 5px;">

<?php  

if(isset($supervisorComment) && $supervisorComment): 

$is_approved = $supervisorComment['approved'] ? 'Approved' : 'Disapproved';
$commentContent = $supervisorComment['content'] ? $supervisorComment['content'] : 'N/A';

?>

<p>
<?= strstr($supervisorComment['author_tags'], '(', true); ?> <?= $is_approved ?><br />
Comment: <?= $commentContent ?>
</p>

<?php  elseif(isset($supervisorComment)): ?>

<p>Pending Direct Supervisor Reply</p>

<?php endif; ?>






<?php if(isset($region_pastorComment) && $region_pastorComment): 

$is_approved = $region_pastorComment['approved'] ? 'Approved' : 'Disapproved';
$commentContent = $region_pastorComment['content'] ? $region_pastorComment['content'] : 'N/A';

?>

<p>
<?= strstr($region_pastorComment['author_tags'], '(', true);  ?> <?= $is_approved ?><br />
Comment: <?= $commentContent ?>
</p>

<?php elseif(isset($region_pastorComment)): ?>

<p>Pending Region Pastor  Reply</p>

<?php endif; ?>





<?php if(isset($senior_pastorComment) && $senior_pastorComment): 

$is_approved = $senior_pastorComment['approved'] ? 'Approved' : 'Disapproved';
$commentContent = $senior_pastorComment['content'] ? $senior_pastorComment['content'] : 'N/A';

?>

<p>
<?= strstr($senior_pastorComment['author_tags'], '(', true);   ?> <?= $is_approved ?><br />
Comment: <?= $commentContent ?>
</p>

<?php elseif(isset($senior_pastorComment)): ?>

<p>Pending Senior Pastor (Interim) Reply</p>

<?php endif; ?>



<?php 




if(isset($operations_directorComment) && $operations_directorComment): 

$is_approved = $operations_directorComment['approved'] ? 'Acknowledged' : 'Disapproved';
$commentContent = $operations_directorComment['content'] ? $operations_directorComment['content'] : 'N/A';

?>

<p>
<?= $operations_directorComment['author_tags'] ?> <?= $is_approved ?><br />
Comment: <?= $commentContent ?>
</p>

<?php elseif($waiting_operations_director): ?>

<p>Pending Acknowledgment by Operations Director</p>

<?php endif; ?>
	
	





<?php if($showForm): ?>


<form style=" margin: 20px 0; " action="<?= base_url('pto/'.$pid); ?>" method="post" class="rForm myAjForm" id="ptoCommentForm" onkeydown="if(event.keyCode==13){return false;}">

 <fieldset>
 
 
 <?php if(in_array($logged_id,$users_need_to_approve)): ?>
 
	<legend>Your Opinion</legend>
  

	<p>		
	<select   name="opinion" class="required form-control" title="Your Opinion" id="opinion">
	<option value ="">- please select -</option>
	<option value ="1">Approve</option>
	<option value ="0">Disapprove</option>
	</select>
	</p>
 <?php else: ?>
 
	<input type="hidden"  name="opinion" value="1" />
	
 <?php endif; ?>

<?php if(isset($is_operations_director) && $is_operations_director): ?>
<p class="new-pto-balance">
PTO Balance Update:<br /> <input type="text" title="PTO Balance" name="balance" id="balance" class="form-control required" value="" /> current: <?= $balance ;?> day(s)
</p>
<?php endif; ?>
	
	Remarks/Notes:<br /> <textarea class="form-control mb-3" id="notes" title="Remarks/Notes" name="notes" rows="4" cols="50"></textarea>
	
	
	

	
	<p class="bts">
	
	<input type="hidden"  name="lastPastor" value="<?= $lastPastor; ?>" />
	<input type="hidden"  name="action" value="pastorsOpinion" />
	<input type="hidden"  name="bid" value="<?= $bid; ?>" />
	<input type="hidden"  name="pid" value="<?= $pid; ?>" />
	
<?php if(in_array($logged_id,$users_need_to_approve)): ?>	
	<input type="submit" class="btn btn-primary px-5" id="btSubmit" value="Submit" />
 <?php else: ?>
	<input type="submit" class="btn btn-primary px-5" id="btSubmit" value="Acknowledged" />	
 <?php endif; ?>	
	
	</p>
	<p class="fmsg"></p>
</fieldset>
</form>



<?php endif; ?>
</div>





</div>
</div>
</div>
</div>




<?php

//var_dump($userOtherPosts);

 if(isset($userOtherPosts)&&$userOtherPosts): ?>
 
 
 <div class="row my-5">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
		<h3><?= $userName; ?>'s Other Applications: </h3>		  
			<hr />	
 
<div id="userOtherPosts">





	<?php foreach($userOtherPosts as $post): 
	
			if($post['status']==1){
				$theStatus = 'Awaiting Approval';
			}elseif($post['status']==-1){
				$theStatus = 'Cancelled';
			}elseif($post['status']==0){
				$theStatus = 'Disapproved';
			}else{
				$theStatus = 'Approved';
			}
	
		$postTitle = $post['types'].' ('. date("m/d/Y",$post['start']) .  ($post['end']&&$post['start']!==$post['end']? '-'.date("m/d/Y",$post['end']) : '')  . ') submitted  on '. date("m/d/Y",$post['submit_date']) . ' ['.$theStatus.']';
	
	?>

		<p><a href="<?= base_url('pto/'.$post['id']); ?>"><?= $postTitle; ?></a></p>
			
	
	<?php endforeach; ?>
</div>
<?php endif; //userOtherPosts ?>















	
 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>
<style>


#pageDescription{margin: 0 10px 20px 10px;}
#layout{ padding:0 10px;}
#layout p{ padding: 10px 0;}

#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

#adminList p { margin: 5px 0;}

tr.highlight{    background: #FFEB3B;}

@media screen and (max-width:375px ) {
	
	
}
</style>



<script type="text/javascript">



var startDate = document.getElementById('start-date');
var endDate = document.getElementById('end-date');

if(startDate){
	rome(startDate, {
	  time:false,
	  inputFormat: "MM/DD/YYYY",
	  dateValidator: rome.val.beforeEq(endDate) 
	});
}

if(endDate){
	rome(endDate, {
	  time:false,
	  inputFormat: "MM/DD/YYYY",
	  dateValidator: rome.val.afterEq(startDate) 
	});
}



var timer  = null; 
var ajaxer  = null; 

$(".myAjForm").submit(function(event){
	
    event.preventDefault();

	var error='';
	$('.myAjForm .fmsg').html('Loading...');
	
	error = validater($('.myAjForm'));
	
	
	balanceVal = $('#balance').val();
	pto_options_abled = $('#opinion').val();	
	
	
	if(pto_options_abled && balanceVal > <?= $pto_maximum_limit; ?>){
		error += 'PTO Balance值最多為[<?= $pto_maximum_limit; ?>]天.<br/>';
	}	
	
	
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
					
						
						
						
						if(data=='OK'){location.reload(); }else{ $('.myAjForm .fmsg').html(data); }


					
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


function deletePost(pid){
	var r = confirm("Please press 'OK' to continue");
	var url = '<?= base_url("pto/archive"); ?>';
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: {	pid: pid,action:'deletePost'},      
				success:function(data){		
					if(data=='OK'){location.href = url;}else{ alert('Error'); }
											
				}
			});
	}	
}

function cancelPost(pid){
	var r = confirm("Please press 'OK' to continue");
	var url = '<?= base_url("pto"); ?>';
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: {	pid: pid,action:'cancelPost'},      
				success:function(data){		
				
					
				
					if(data=='OK'){location.href = url;}else{ alert('Error'); }
											
				}
			});
	}	
}

$('#opinion').on('change',function(){

  var selection = $(this).val(); 
  
  if(selection==1){
	  $('.new-pto-balance').show();
	  $('#notes').removeClass('required');
  }else{
	  $('.new-pto-balance').hide();
	  $('#notes').addClass('required');
  }
  
});






</script>
 