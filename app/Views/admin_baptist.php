<?php



$userPicture = isset($user['picture'])&&$user['picture'] ?  $user['picture'] : base_url().'/assets/images/default_user_profile.jpg';

		$dispalyCurs1 = array_intersect_key($curriculumCodes,['CBIF101'=>1,'CBIF000'=>1,'CBIF100'=>1,'CBIF201'=>1,'CBIF301'=>1,'CBIF401'=>1]);
		$returnHtml1 = mode0html($bid,$dispalyCurs1,$results);
	
		$dispalyCurs2 = array_intersect_key($curriculumCodes,['CBIB01'=>1,'CBIB02'=>1,'CBIB20'=>1,'CBIB30'=>1,'CBIB40'=>1,'CBIE01'=>1]);		
		$returnHtml2 = mode0html($bid,$dispalyCurs2,$results);


function mode0html($bid,$curriculumCodes,$results){ 
	
	$html='';
	
	$curriculumCount = count($curriculumCodes);
	$cWidth = floor(1/$curriculumCount*100);
	
		
		$html .= '<table id="results">';	
	
		$html .=  '<tr class="title"><td class="curriculumTd">';
		
		foreach($curriculumCodes as $c){

			$html .=  '<div class="float-left" style="width:'.$cWidth.'%;">'.$c[1].'</div>';		
		}
		
		$html .=  '</td></tr>';

					
			$html .=  '<tr><td>';
			
				foreach($curriculumCodes as $code => $c){

					$html .=  '<div class="float-left" style="width:'.$cWidth.'%;"><div>';
					
						if(isset($results[$code])){
							
							
							if(preg_match_all('/(\d+)#(\d+)#(\d+)#finish#(\d+)#([^|]+)/i',$results[$code],$logs)){
								
								foreach($logs[1] as $key => $cid){
									//echo $logs[3][$key] . ' - ' . time().'<br />'; 
									$lable = ($logs[3][$key] > time() && $logs[4][$key] != 100)? '進行中' : '完成';	
									$classTitle = $logs[5][$key];
									
									if( stripos($classTitle,'e-learning') !== false ){
										$time = '(Online Class)';
									}elseif( $code == 'CBIB40' || $code == 'CBIE01'){
										$time = '('.$logs[5][$key].')';
									}else{
										$time = '('.date('m/d/Y',$logs[2][$key]).')';
									}
									
									$wcd = $logs[4][$key].'%';
									$html .= '<h4><a href="'.base_url('xAdmin/curriculum/'.$code.'#cid'.$cid.'bid'.$bid).'">'.$lable.' '.$time.'  :  '.$wcd.'</a></h4>';
								}
								
							}else{
								
								$html .= '<h4>Error</h4>';
								
							}
							
							
							
						}else{
							$html .= '<h4>未完成</h4>';
						}
					
					
					
					$html .=  '</div></div>';		
				}
				
			$html .=  '</td></tr>';			
			
	
		
		
		$html .= '</table>';

	
	
	return $html;
}

?>










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

.invite{margin:10px; }

.pto_options_disabled {
	 display: none;
}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>

<?php if($bid && $user['status'] === NULL): ?>

	<p class="invite"><a href="<?= ($adminUrl.'/invite?f='.trim($_SESSION['mloggedinName']).'&to='.$pageTitle.'&email='.$user['email']);?>" >邀請註冊</a></p>
	
<?php elseif($bid): ?>	
	
	
	<?php if($bid && $user['bornagain'] !== NULL): ?>	
		<p class="invite"><a target="_blank" href="<?= (base_url('member/submit?bid='.$bid));?>"><?= $pageTitle;?> 的 [Crosspoint Membership Record and Covenant 匯點會員紀錄和約章]</a></p>
	<?php else: ?>	
		<p class="invite">No Membership Form on file</p>
	<?php endif; ?>	
		


	<?php if(isset($isShapeExists) && $isShapeExists): ?>
		<p class="invite"><a target="_blank" href="<?= (base_url('member/shape?bid='.$bid));?>"><?= $pageTitle;?>  SHAPE Profile</a></p>
	<?php else: ?>	
		<p class="invite">No SHAPE on file</p>
	<?php endif; ?>	

	<?php if(isset($isMinistryExists) && $isMinistryExists): ?>
		<p class="invite"><a target="_blank" href="<?= (base_url('member/ministry?bid='.$bid));?>"><?= $pageTitle;?>  Serving position</a></p>
	<?php else: ?>	
		<p class="invite">No Serving position on file</p>
	<?php endif; ?>	
	
	<p class="invite">
		<a target="_blank" href="<?= (base_url('member/testimony?bid='.$bid));?>">Testimony on file</a>
	</p>	

<?php endif; //acount user or not?>

<?php if($ezoneGData):
		$eHtml = [];
		foreach($ezoneGData as $item){
			$eHtml[] = '<a href="'.$webConfig->ezoneUrl.'?groups='.$item['slug'].'">'.$item['name'].'</a>';
		}
		
		echo '<p class="invite">'.$pageTitle.'參加的EZone活動: '.implode(', ',$eHtml);
endif; //ezone ?>	


<div class="sesstion" id="s-searchResult">
<?php

if($results){

if(isset($returnHtml1)&&$returnHtml1) echo $returnHtml1;

if(isset($returnHtml2)&&$returnHtml2) echo $returnHtml2;

}

?>
</div>


<div class="sesstion" id="s-baptist">

<div style="    margin: 20px 0;" id="picWrap">
User picture:<br />

<a id="thePictureLink" href="<?= $userPicture; ?>" target="_blank"><img id="thePicture" src="<?= $userPicture; ?>" width="150" height="150" /></a><br />

<p><input data-bid="<?= $bid; ?>" type="file" name="baptistPicture" id="baptistPicture" /></p>
<span class="picError" style=" color: red; "></span>

</div>

<form action="<?= base_url('xAdmin/baptist'); ?>" method="post" class="rForm" id="baptistForm" onkeydown="if(event.keyCode==13){return false;}">

	<p>First Name(required): <input class="required" title="First Name" type="text" value="<?= (isset($user['fName'])?$user['fName']:''); ?>" name="fName" id="fName" /></p>
	
	<p>Middle Name(Optional): <input title="Middle Name" type="text" value="<?= (isset($user['mName'])?$user['mName']:''); ?>" name="mName" id="mName" /></p>
	
	<p>Last Name(required): <input class="required" title="Last Name" type="text" value="<?= (isset($user['lName'])?$user['lName']:''); ?>" name="lName" id="lName" /></p>
	
	<p>Chinese Name: <input  title="Chinese Name" type="text" value="<?= (isset($user['cName'])?$user['cName']:''); ?>" name="cName" id="cName" /></p>
	
	<p>
	Gender(required):	
	<select name="gender" class="required" title="Gender" id="gender">
	<option value ="">---</option>
	<option <?= (isset($user['gender'])&&$user['gender']=='M'?'selected':'') ;?> value ="M">Male</option>
	<option <?= (isset($user['gender'])&&$user['gender']=='F'?'selected':'') ;?> value ="F">Female</option>
	</select>
	</p>

	<p>
	Marital Status:	
	<select name="marital" id="marital">
	<option value ="">---</option>
	<option <?= (isset($user['marital'])&&$user['marital']=='M'?'selected':'') ;?> value ="M">Married</option>
	<option <?= (isset($user['marital'])&&$user['marital']=='S'?'selected':'') ;?> value ="S">Single</option>
	</select>
	</p>

	
	
	<p>Birth Date: <input value="<?= (isset($user['birthDate'])&&$user['birthDate']?date("m/d/Y",$user['birthDate']):''); ?>" name="birthDate" id="birthDate" class="dateInput" /></p>
	
	<p>Home Address: <input value="<?= (isset($user['homeAddress'])?$user['homeAddress']:''); ?>" name="homeAddress" id="homeAddress" /></p>
	
	<p>City: <input value="<?= (isset($user['city'])?$user['city']:'') ; ?>" name="city" id="city" /></p>
	
	<p>Zip code: <input value="<?= (isset($user['zCode'])?$user['zCode']:'') ; ?>" name="zCode" id="zCode" /></p>
	
	<p>Home Phone: <input value="<?= (isset($user['hPhone'])?$user['hPhone']:'') ; ?>" name="hPhone" id="hPhone" /></p>
	
	<p>Mobile Phone: <input value="<?= (isset($user['mPhone'])?$user['mPhone']:'') ; ?>" name="mPhone" id="mPhone" /></p>
	
	<p>Email: <input class="required email" value="<?= (isset($user['email'])?$user['email']:'') ; ?>" name="email" title="Email" id="email" /></p>
	
	<p>Occupation: <input value="<?= (isset($user['occupation'])?$user['occupation']:'') ; ?>" name="occupation" id="occupation" /></p>
	
	<p>Name of the church you were baptized: <input value="<?= (isset($user['nocb'])?$user['nocb']:'') ; ?>" name="nocb" id="nocb" /></p>
	
	<p>Baptized Date: <input value="<?= (isset($user['baptizedDate'])&&$user['baptizedDate']?date("m/d/Y",$user['baptizedDate']):''); ?>" name="baptizedDate" id="baptizedDate" class="dateInput"   /></p>	
	
	<p>Name of your previous church: <input value="<?= (isset($user['nopc'])?$user['nopc']:'') ; ?>" name="nopc" id="nopc" /></p>
	

	
	<p>Site:	
		<select name="site" id="site">
		<option value="">---</option>
		<?php
			foreach($sites as $siteCf){
				echo '<option '.($bid && $siteCf==$user['site']?'selected':'').' value ="'.$siteCf.'">'.$siteCf.'</option>';		
			}	
		?>
		</select> 
	</p>
	
	<p>Membership Date: <input value="<?= (isset($user['membershipDate'])&&$user['membershipDate']?date("m/d/Y",$user['membershipDate']):''); ?>" name="membershipDate" id="membershipDate" class="dateInput" /></p>
	
	
	<p>
	Status:	
	<select <?= ($bid && !$webConfig->checkPermissionByDes('add_class')?'disabled':'name="inactive"');?> id="inactive">
	<option <?= ($bid && $user['inactive']==2?'selected':'') ;?> value="2">Guest</option>
	<option <?= ($bid && $user['inactive']==1?'selected':'') ;?> value="1">Inactive</option>
	<option <?= ($bid && $user['inactive']==3?'selected':'') ;?> value="3">Member</option>
	<option <?= ($bid && $user['inactive']==4?'selected':'') ;?> value="4">Pre-Member</option>
	<option <?= ($bid && $user['inactive']==5?'selected':'') ;?> value="5">Ex-Member</option>
	<option <?= ($bid && $user['inactive']==6?'selected':'') ;?> value="6">Pending</option>
	</select> <?= ($bid && !$webConfig->checkPermissionByDes('add_class')?'Please contact administrators to change status.':'');?>
	</p>	
	
	
	<input  type="hidden" name="bid" value="<?= $bid; ?>" />
	<input  type="hidden" name="action" value="insert" />
	<input  type="hidden" id="thePictureInput" value="" />
	
		<div id="familyDiv">Family member:<br /> <textarea id="family" name="family" rows="4" cols="50"><?= (isset($user['family'])?$user['family']:''); ?></textarea>
	</div>	
	



<div  style=" background: #eee; padding: 5px; font-size: 12px; margin: 15px 0; ">
	
	<p>Are you sure you are already a born-again Christian? 你是否肯定自己是一個重生得救的基督徒？:<br />	
		<select name="bornagain" id="bornagain"  title="Are you sure you are already a born-again Christian?">
		<option value="">---</option>
		<option <?= ($bid && $user['bornagain']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($bid && $user['bornagain']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>
	
	<p>Are you attending a Crosspoint life group regularly? 你現今是否固定參加匯點的「生命小組」？:<br />	
		<select name="attendingagroup" id="attendingagroup"   title="Are you attending a Crosspoint life group regularly?">
		<option value="">---</option>
		<option <?= ($bid && $user['attendingagroup']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($bid && $user['attendingagroup']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>	
	
	
	<p>If yes, what is the name of your life group or your life group leader? 若有，你現今在匯點參加的小組名稱或組長名字是甚麼？:<br />
	<input value="<?= (isset($user['lifegroup'])?$user['lifegroup']:'') ; ?>" name="lifegroup" id="lifegroup" /></p>
	
	
	<p>Were you baptized or officially accepted as member of a previous church? 你是否已受浸或在一間教會擁有過會籍？:<br />	
		<select name="baptizedprevious" id="baptizedprevious" title="Were you baptized or officially accepted as member of a previous church?">
		<option value="">---</option>
		<option <?= ($bid && $user['baptizedprevious']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($bid && $user['baptizedprevious']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>	
	
	

	
	<p>If you were baptized, were you baptized by immersion? 若曾接受水禮，接受的水禮是否全身入水的浸禮？:<br />	
		<select name="byImmersion" id="byImmersion">
		<option value="">---</option>
		<option <?= ($bid && $user['byImmersion']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($bid && $user['byImmersion']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>	
</div>



<?php 
//var_dump($operationsDirector); 

if($bid && in_array($bid,$adminsIds) ): 

	if( in_array($current_uid,$operationsDirector) || $webConfig->checkPermissionOnly(8) ):

?>


	
	

<div id="pto_options" style=" background: #eee; padding: 5px; font-size: 12px; margin: 15px 0; " class="">

	
	<p><?= ($bid==207?'BOT':'Direct Supervisor'); ?>:  
		<select name="supervisor" id="supervisor">
		<option value="">---</option>
		
		<?php 
		
			foreach($pastors as $pastor){
				
				$is_selected =  ($ptoRelation['supervisor'] == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
	</p>

	
	<p>Region Pastor:  
		<select name="region_pastor" id="region_pastor">
		<option value="">---</option>
		
		<?php 
		
			foreach($pastors as $pastor){
				
				$is_selected =  ($ptoRelation['region_pastor'] == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
	</p>

	
	<p>Senior Pastor:  
		<select name="senior_pastor" id="senior_pastor">
		<option value="">---</option>
		
		<?php 
		
			foreach($pastors as $pastor){
				
				$is_selected =  ($ptoRelation['senior_pastor'] == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
	</p>

	
	<p>Operations Director:  
		<select name="operations_director" title="Operations Director" class="" id="operations_director">
		<option value="">---</option>
		
		<?php 
		
			foreach($pastors as $pastor){
				
				$is_selected =  ($ptoRelation['operations_director'] == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
	</p>
	


	


	<p>
		Day of FT Hire:   <input value="<?= $ft_hire; ?>" name="ft_hire" id="ft_hire" class="dateInput" /><br /> 
		<?php //echo ($ptoRelation['update_schedule']?'[update schedule on  '.(date('m/d/Y',$ptoRelation['update_schedule'])):''); ?>
	</p>
	
	
	<p>
		PTO Balance:   <input value="<?= $ptoRelation['balance']; ?>" name="balance" id="balance" class="" />
		

		
	</p>	
	





	</div>

	
<?php 

	endif; //if( in_array($current_uid,$operationsDirector) || in_array($current_uid,$seniorPastor) || $webConfig->checkPermissionOnly(8) ):

 endif; //if($bid && in_array($bid,$adminsIds) )?>


	
	<p class="bts">
	
	<?php if($bid): ?><input type="button" id="del" onclick="delUser('<?= $bid; ?>')"  value="del" />  | <?php endif; ?>
	
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

var timer  = null; 
var ajaxer  = null; 

$("#baptistForm").submit(function(event){
	
    event.preventDefault();

	var error='';
	$('#baptistForm .fmsg').html('Loading...');
	
	error = validater(document.getElementById('baptistForm'));
	
	balanceVal = $('#balance').val();
	pto_options_abled = $('#pto_apply').val();
	
	if(pto_options_abled && balanceVal > <?= $pto_maximum_limit; ?>){
		error += 'PTO Balance值最多為[<?= $pto_maximum_limit; ?>]天.<br/>';
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
				url: '<?= base_url("xAdmin/baptist"); ?>',
				data: params,      
				success:function(data){
					
						$('#baptistForm .fmsg').html(data);

					
				}
			});
			
		 },600);
		 
		
		
	}
	
});

function delUser(id){
	var r = confirm("Please press 'OK' to continue");
	var url = '<?= base_url("xAdmin/baptist"); ?>';
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: {	bid: id,action:'delete'},      
				success:function(data){		
					if(data=='OK'){location.href = url;}else{ $('#baptistForm .fmsg').html(data); }
											
				}
			});
	}


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




function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}







$( document ).on( "change", "select#pto_apply", function() {
	

	

	var selected = $(this).val(); 
	
	if(selected == 1){
		$('#pto_options').show();
	}else if(selected==0){
		$('#pto_options').hide();
	}

		

	 
	 
	 
});



$('#baptistPicture').on('change',function(){
        
		var mb=(1 *1024)*1024;

		var ext=['jpg','png','jpeg','bmp'];
		var bid = $(this).data('bid');

		
             
        
            if($(this)[0].files[0]!='undifined'){
				$('.picError').html('Loading...');
                
                var pic=$(this)[0].files[0];
                
                if(pic.size <= mb){
				    
               	       var stringarray=pic.name.split('.');
                       
                       if(jQuery.inArray(stringarray[stringarray.length-1].toLowerCase(),ext)!= -1){

						

				 
								var fd = new FormData();
								fd.append("pic", pic);	
								fd.append("bid", bid);	
								fd.append("action", 'pictureUpdate');	
				 
				 
				 				$.ajax({
									dataType:'html',
									method: "POST",
									url: '<?= base_url("xAdmin/baptist"); ?>',     
									data: fd,
									processData: false,
									contentType: false,									
									cache: false,
									success: function (res) {
										
										if(res !== 'error'){
											
											var picUrl = res;
											$('#thePicture').attr('src',picUrl);												
											$('#thePictureLink').attr('href',picUrl);												
											$('#thePictureInput').val(picUrl);
											$('#thePictureInput').attr('name','picture');
										}
										
										$('.picError').html('');


									},
									// complete:function (res) { console.log(res); }
								});	


						}else{

			             $('.picError').html('Invalid file extension');

						}

				}
                
   
				else{

			       $('.picError').html('Invalid file size');

				}
        
   
        }
        
      
        
    });







</script>

</body>
</html>


