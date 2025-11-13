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
	
		
		$html .= '<table id="" class="table my-3">';	
	
		$html .=  '<tr class="title"><td class="curriculumTd">';
		
		foreach($curriculumCodes as $c){

			$html .=  '<div class="float-left" style="width:'.$cWidth.'%;">'.$c[1].'</div>';		
		}
		
		$html .=  '</td></tr>';

					
			$html .=  '<tr class="bg-gray-400"><td>';
			
				foreach($curriculumCodes as $code => $c){

					$html .=  '<div class="float-left" style="width:'.$cWidth.'%;"><div>';
					
						if(isset($results[$code])){
							
							
							if(preg_match_all('/(\d+)#(\d+)#(\d+)#finish#(\d+)#([^|]+)/i',$results[$code],$logs)){
								
								foreach($logs[1] as $key => $cid){
									//echo $logs[3][$key] . ' - ' . time().'<br />'; 
									$lable = ($logs[3][$key] > time() && $logs[4][$key] != 100)? 'In Progress' : 'Completed';	
									$classTitle = $logs[5][$key];
									
									if( stripos($classTitle,'e-learning') !== false ){
										$time = '(Online Class)';
									}elseif( $code == 'CBIB40' || $code == 'CBIE01'){
										$time = '('.$logs[5][$key].')';
									}else{
										$time = '('.date('m/d/Y',$logs[2][$key]).')';
									}
									
									$wcd = $logs[4][$key].'%';
									$html .= '<h6><a href="'.base_url('xAdmin/curriculum/'.$code.'#cid'.$cid.'bid'.$bid).'">'.$lable.' '.$time.'  :  '.$wcd.'</a></h6>';
								}
								
							}else{
								
								$html .= '<h6>Error</h6>';
								
							}
							
							
							
						}else{
							$html .= '<h6>Incomplete</h6>';
						}
					
					
					
					$html .=  '</div></div>';		
				}
				
			$html .=  '</td></tr>';			
			
	
		
		
		$html .= '</table>';

	
	
	return $html;
}

?>


<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <?= session()->getFlashdata('success'); ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php endif; ?>


<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <?= session()->getFlashdata('error'); ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php endif; ?>


 <h1 class="h3 mb-3 text-gray-800"><?= $pageTitle; ?></h1>


 <ul class="nav nav-tabs" id="userTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile-content" role="tab" aria-controls="profile-content" aria-selected="true">Profile</a>

    </li>

	<?php if($bid): ?>

	<?php if($bid && $webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>	
    <li class="nav-item">
        <a class="nav-link" id="classes-tab" data-toggle="tab" href="#classes-content" role="tab" aria-controls="classes-content" aria-selected="false">Classes</a>

    </li>
	<li class="nav-item">
        <a class="nav-link" id="same-name-tab" data-toggle="tab" href="#same-name-content" role="tab" aria-controls="same-name-content" aria-selected="false">Same Name Account</a>
    </li>
	<?php endif; ?>
		

	<?php if(isset($isShapeExists) && $isShapeExists): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= (base_url('member/shape?bid='.$bid));?>" aria-selected="false">SHAPE</a>
    </li>
	<?php endif;?>

	<?php if(isset($isMinistryExists) && $isMinistryExists): ?>
	<li class="nav-item">
        <a class="nav-link" href="<?= (base_url('member/ministry?bid='.$bid));?>" aria-selected="false">Serving position</a>
    </li>
	<?php endif;?>

	<?php if(in_array($bid,$adminsIds) &&  $webConfig->checkPermissionByDes(['is_office_director','is_senior_pastor'])): ?>
	<li class="nav-item">
        <a class="nav-link" id="pto-tab" data-toggle="tab" href="#pto-content" role="tab" aria-controls="pto-content" aria-selected="false">PTO</a>
    </li>
	<?php endif; ?>		


	<li class="nav-item">
        <a class="nav-link" id="modification-log-tab" data-toggle="tab" href="#modification-log-content" role="tab" aria-controls="modification-log-content" aria-selected="false">Modification logs</a>
    </li>

	<?php if(!$user['status'] && $webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>
	<li class="nav-item">
        <a class="nav-link"   href="<?= (base_url('xAdmin/invite?f='.trim($_SESSION['mloggedinName']).'&to='.$pageTitle.'&email='.$user['email']));?>"  aria-selected="false">Invite to Register</a>
    </li>
	<?php endif;?>






	<?php endif; //if($bid) ?>	




</ul>


<div class="tab-content mt-4" id="userTabContent">


	<div class="tab-pane fade show active" id="profile-content" role="tabpanel" aria-labelledby="profile-tab">

		
	<div class="sesstion" id="s-baptist">










	<form action="<?= base_url('xAdmin/baptist'); ?>" method="post" class="rForm" id="baptistForm" onkeydown="if(event.keyCode==13){return false;}">

		<p>First Name (required): <input class="form-control required" title="First Name" type="text" value="<?= (isset($user['fName'])?$user['fName']:''); ?>" name="fName" id="fName" /></p>
		
			
		<p>Last Name (required): <input class="required form-control" title="Last Name" type="text" value="<?= (isset($user['lName'])?$user['lName']:''); ?>" name="lName" id="lName" /></p>
		

	<p>Site (required):	
			<select class="form-control required"  title="Site 聚會地點" name="site" id="site">


			<option value="">---</option>
			<?php
				foreach($sites as $siteCf){
					echo '<option '.($bid && $siteCf==$user['site']?'selected':'').' value ="'.$siteCf.'">'.$siteCf.'</option>';		
				}	
			?>
			</select> 
	</p>		

		<p>Middle Name: <input class="form-control" title="Middle Name" type="text" value="<?= (isset($user['mName'])?$user['mName']:''); ?>" name="mName" id="mName" /></p>	
		
		<p>Mobile Phone: <input class="form-control" title="Mobile Phone" value="<?= (isset($user['mPhone'])?$user['mPhone']:'') ; ?>" name="mPhone" id="mPhone" /></p>
		
		<p>Email: <input class="email form-control" value="<?= (isset($user['email'])?$user['email']:'') ; ?>" name="email" title="Email" id="email" /></p>
		
		<p>Chinese Name: <input  class="form-control" title="Chinese Name" type="text" value="<?= (isset($user['cName'])?$user['cName']:''); ?>" name="cName" id="cName" /></p>
		
		<p>
		Gender:	
		<select name="gender" class="form-control" title="Gender" id="gender">
		<option value ="">---</option>
		<option <?= (isset($user['gender'])&&$user['gender']=='M'?'selected':'') ;?> value ="M">Male</option>
		<option <?= (isset($user['gender'])&&$user['gender']=='F'?'selected':'') ;?> value ="F">Female</option>
		</select>
		</p>

		<p>
		Marital Status:	
		<select class="form-control" name="marital" id="marital">
		<option value ="">---</option>
		<option <?= (isset($user['marital'])&&$user['marital']=='M'?'selected':'') ;?> value ="M">Married</option>
		<option <?= (isset($user['marital'])&&$user['marital']=='S'?'selected':'') ;?> value ="S">Single</option>
		</select>
		</p>

		
		
		<p>Birth Date: <input title="Birth Date" value="<?= (isset($user['birthDate'])&&$user['birthDate']?date("m/d/Y",$user['birthDate']):''); ?>" name="birthDate" id="birthDate" class="dateInput form-control" /></p>
		
		<p>Home Address: <input value="<?= (isset($user['homeAddress'])?$user['homeAddress']:''); ?>" name="homeAddress" id="homeAddress" class="form-control" /></p>
		
		<p>City: <input class="form-control" value="<?= (isset($user['city'])?$user['city']:'') ; ?>" name="city" id="city" /></p>
		
		<p>Zip code: <input class="form-control" value="<?= (isset($user['zCode'])?$user['zCode']:'') ; ?>" name="zCode" id="zCode" /></p>
		
		<p>Home Phone: <input class="form-control" value="<?= (isset($user['hPhone'])?$user['hPhone']:'') ; ?>" name="hPhone" id="hPhone" /></p>
		

		

		
		<p>Occupation: <input class="form-control" value="<?= (isset($user['occupation'])?$user['occupation']:'') ; ?>" name="occupation" id="occupation" /></p>
		
 
		

		
 
		


		
		
		<input  type="hidden" name="bid" value="<?= $bid; ?>" />
		<input  type="hidden" name="action" value="insert" />
		<input  type="hidden" id="thePictureInput" value="" />
		
			<div id="familyDiv">Family member:<br /> <textarea  class="form-control" id="family" name="family" rows="4" cols="50"><?= (isset($user['family'])?$user['family']:''); ?></textarea>
		</div>	
		



		<div id="membership"  class="mt-5" style=" background: #eee; padding: 5px;  margin: 30px 0; ">
		
		<p>Are you sure you are already a born-again Christian? 你是否肯定自己是一個重生得救的基督徒？:<br />	
			<select class="form-control" name="bornagain" id="bornagain"  title="Are you sure you are already a born-again Christian?">
			<option value="">---</option>
			<option <?= ($bid && $user['bornagain']=='1'?'selected':'') ;?> value="1">Yes 是</option>
			<option <?= ($bid && $user['bornagain']=='0'?'selected':'') ;?> value="0">No 不是</option>
			</select>
		</p>
		
		<p>Are you attending a Crosspoint life group regularly? 你現今是否固定參加匯點的「生命小組」？:<br />	
			<select class="form-control" name="attendingagroup" id="attendingagroup"   title="Are you attending a Crosspoint life group regularly?">
			<option value="">---</option>
			<option <?= ($bid && $user['attendingagroup']=='1'?'selected':'') ;?> value="1">Yes 是</option>
			<option <?= ($bid && $user['attendingagroup']=='0'?'selected':'') ;?> value="0">No 不是</option>
			</select>
		</p>	
		
		
		<p>If yes, what is the name of your life group or your life group leader? 若有，你現今在匯點參加的小組名稱或組長名字是甚麼？:<br />
		<input class="form-control" value="<?= (isset($user['lifegroup'])?$user['lifegroup']:'') ; ?>" name="lifegroup" id="lifegroup" /></p>
		
		
		<p>Were you baptized or officially accepted as member of a previous church? 你是否已受浸或在一間教會擁有過會籍？:<br />	
			<select class="form-control" name="baptizedprevious" id="baptizedprevious" title="Were you baptized or officially accepted as member of a previous church?">
			<option value="">---</option>
			<option <?= ($bid && $user['baptizedprevious']=='1'?'selected':'') ;?> value="1">Yes 是</option>
			<option <?= ($bid && $user['baptizedprevious']=='0'?'selected':'') ;?> value="0">No 不是</option>
			</select>
		</p>	
		
		
		<p>If Yes, name of the church you were baptized or accepted as a member. 若有，曾接受浸禮或擁有會籍的教會名稱:<br />
			<input class="form-control" value="<?= (isset($user['nocb'])?$user['nocb']:'') ; ?>" name="nocb" id="nocb" />
		</p>	
		
		<p>If you were baptized, were you baptized by immersion? 若曾接受水禮，接受的水禮是否全身入水的浸禮？:<br />	
			<select class="form-control" name="byImmersion" id="byImmersion">
			<option value="">---</option>
			<option <?= ($bid && $user['byImmersion']=='1'?'selected':'') ;?> value="1">Yes 是</option>
			<option <?= ($bid && $user['byImmersion']=='0'?'selected':'') ;?> value="0">No 不是</option>
			</select>
		</p>	

	<p>Date of your baptism 接受浸禮日期: 
		<input value="<?= (isset($user['baptizedDate'])&&$user['baptizedDate']?date("m/d/Y",$user['baptizedDate']):''); ?>" name="baptizedDate" id="baptizedDate" class="form-control dateInput"   />
	</p>	

	<p>Name of your previous church you attended regularly before coming to Crosspoint 來匯點之前定期參加的教會名稱:<br />
		<input class="form-control" value="<?= (isset($user['nopc'])?$user['nopc']:'') ; ?>" name="nopc" id="nopc" />
	</p>


	<p class="mb-0">Please upload your Baptism Certificate here if you are from another church 上傳您的浸禮證書: 	</p>			

		<div class="form-group " id="baptismCertificateFile" style=" padding-left: 20px; border-left: 1px solid #ccc; margin-left: 20px; ">

			
				
				<small style="color:gray;font-size:11px">supported file format(doc, ppt, pdf, docx, txt, pptx, jpg, jpeg, png)</small>

				 <input class="form-control fileUpload" data-multiple="0" data-action="baptismCertificateUpdate" data-name="baptismCertificate" data-bid="<?= $bid; ?>" type="file" id="certificate" />
			
				<div id="certificateArea" class="filesContainer">

				<?php 
				
				if(isset($user['certificate']) && $user['certificate']){

						
						
							
							echo '<p class="my-2"><a target="_blank" href="'. $user['certificate'] .'">'.basename($user['certificate']).'</a>';
							
							echo ' <a class="fileRemove text-danger ml-3" href="javascript:void(0);">x Remove</a>'; 

							echo '<input type="hidden" name="baptismCertificate" value="'.$user['certificate'].'" />';

							
							echo '</p>';
							
						
					
				}
				
				?>
				</div>
				
				<p class="ajMsg" style=" color: red; "></p>
		</div>	




	<p class="mb-0">Please upload your testimony here 請上傳您的見證: 	</p>		
		<div class="form-group " id="testimonyFile" style=" padding-left: 20px; border-left: 1px solid #ccc; margin-left: 20px; ">

			
				
				<small style="color:gray;font-size:11px">supported file format(doc, ppt, pdf, docx, txt, pptx, jpg, jpeg, png)</small>

				 <input class="form-control fileUpload" data-multiple="1" data-action="testimonyUpdate" data-name="testimony" data-bid="<?= $bid; ?>" type="file" id="testimony" />
			
				<div id="testimonyArea" class="filesContainer">

				<?php 
				 
				if(isset($user['testimony']) && $user['testimony']){
					$testimony = json_decode($user['testimony']);

					if(is_array($testimony)){
						foreach($testimony as $v){

							
							echo '<p class="my-2"><a target="_blank" href="'. $v .'">'.basename($v).'</a>';
							
							echo ' <a class="fileRemove text-danger ml-3" href="javascript:void(0);">x Remove</a>'; 

							echo '<input type="hidden" name="testimony[]" value="'.$v.'" />';

							
							echo '</p>';		
						}					
						
					}

						

							
						
					
				}
				
				?>
				</div>
				
				<p class="ajMsg" style=" color: red; "></p>
		</div>	


		</div> <!-- membership -->


	<?php if($bid && $webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>
 		<p class="form-group mt-5">
		Status:	
		<select  class="form-control" name="inactive" id="inactive">		
		<option <?= ($bid && $user['inactive']==2?'selected':'') ;?> value="2">Guest</option>
		<option <?= ($bid && $user['inactive']==4?'selected':'') ;?> value="4">Pre-Member</option>		 
		<option <?= ($bid && $user['inactive']==1?'selected':'') ;?> value="1">Inactive</option>
		<option <?= ($bid && $user['inactive']==5?'selected':'') ;?> value="5">Ex-Member</option>	
		<option <?= ($bid && $user['inactive']==6?'selected':'') ;?> value="6">Pending Approval</option>		
		<option <?= ($bid && $user['inactive']==3?'selected':'') ;?> value="3">Member</option>
		</select>
		</p>		
		<p>Membership Date: <input  class="form-control dateInput required"  value="<?= (isset($user['membershipDate'])&&$user['membershipDate']?date("m/d/Y",$user['membershipDate']):''); ?>" name="membershipDate" id="membershipDate" required title="Membership Date" /></p>
		
		<p>Mailchimp Status: <input class="form-control" value="<?= (isset($user['onMailchimp'])&&$user['onMailchimp']?ucfirst($user['onMailchimp']):'Not set'); ?>" name="onMailchimp" id="onMailchimp" readonly disabled style="background-color: #e9ecef; cursor: not-allowed;" /></p>
	<?php endif; ?>
		

<?php /*>
		<div style="    margin: 20px 0;" id="picWrap">
		User picture:<br />

		<a id="thePictureLink" href="<?= $userPicture; ?>" target="_blank"><img id="thePicture" src="<?= $userPicture; ?>" width="150" height="150" /></a><br />

		<p><input class="form-control"  data-bid="<?= $bid; ?>" type="file" name="baptistPicture" id="baptistPicture" /></p>
		<span class="picError" style=" color: red; "></span>

		</div>
<?php */ ?>		

			
			<p class="bts mt-5">
			
			<?php if($bid && $webConfig->checkPermissionByDes('user_delete')): ?>
			
				<input class="btn btn-secondary " type="button" id="del" onclick="delUser('<?= $bid; ?>')"  value="del" />  | 
			
			<?php endif; ?>
			

			
			<?php if(!$bid && $webConfig->checkPermissionByDes('user_add') ): ?>
			
				<input type="submit"  class="btn btn-primary "  id="btSubmit" value="Add new people" />
				
			<?php elseif($bid && $allowEdit): ?>
				
				<input type="submit"  class="btn btn-primary "  id="btSubmit" value="Update" />	
				
			<?php endif; ?>
			
			</p>
			<p class="fmsg"></p>

	</form>

	</div><!-- #s-baptist -->


	</div><!-- #profile-tab -->
	
	<!-- MailChimp Loading Overlay -->
	<div id="mailchimpOverlay">
		<div class="loader">
			<div class="spinner"></div>
			<div>Updating MailChimp...</div>
		</div>
	</div>


	<div class="tab-pane fade" id="classes-content" role="tabpanel" aria-labelledby="classes-tab">

			<div class="" id="s-searchResult">
			<?php

			if($results){

			if(isset($returnHtml1)&&$returnHtml1) echo $returnHtml1;

			if(isset($returnHtml2)&&$returnHtml2) echo $returnHtml2;

			}else{
				echo 'N/A';

			}

			?>
			</div>
		
	</div><!-- #classes -->

	<?php if($bid && $webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>
	<div class="tab-pane fade" id="same-name-content" role="tabpanel" aria-labelledby="same-name-tab">		
			<div style="margin-bottom: 1rem;">
				<a onclick="findSameNameAs(<?=$bid;?>)" href="javascript:void(0);">搜索同名同姓賬號</a>				
				<div id="findSameNameAs"></div>
			</div>
	</div>
	<?php endif; ?>


	<div class="tab-pane" id="modification-log-content" role="tabpanel" aria-labelledby="modification-log-tab">
		<div class="card">
			<div class="card-body">
				<?php if( isset($activityLog) && $activityLog && !empty($activityLog) ): ?>
					<?php foreach($activityLog as $item): ?>
						<h5><i class="fas fa-fw fa-edit"></i>Updated on <?= $item['change_time'];?> by <?= $item['by'];?></h5>	
						<p class="pl-4 pb-4"><?= $item['log'];?></p>
					<?php endforeach; ?>
				<?php else: ?>
					<p>N/A</p>
				<?php endif; ?>
			</div>
		</div>
	</div>

	

		
		<?php 

			if($bid && in_array($bid,$adminsIds) &&  $webConfig->checkPermissionByDes(['is_office_director','is_senior_pastor'])): 

		?>

		<div class="tab-pane fade" id="pto-content" role="tabpanel" aria-labelledby="pto-tab">
		
		

		<div id="pto_options" style=" background: #eee; padding: 5px; font-size: 12px; margin: 15px 0; " class="">


			<form method="post" action="<?= base_url('xAdmin/baptist/user_pto_update'); ?>">	

			<?php if($bid != $senior_pastor): ?>	

			<p>
				Direct Supervisor:  
				<select class="form-control" name="supervisor" id="supervisor">
				<option value="">---</option>
				
				<?php 
				
					foreach($pastors as $pastor){
						
						if($pastor['bid'] == $senior_pastor) continue;
						
						$is_selected =  ($ptoRelation&&$ptoRelation['supervisor'] == $pastor['bid'] ?'selected':'') ;
						echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
					}
				
				?>
				
				
				
				
				</select>
			</p>
			<?php endif; ?>







			


			<p>
				Day of FT Hire:   <input value="<?= $ft_hire; ?>" name="ft_hire" id="ft_hire" class="dateInput form-control" /><br /> 
				<?php //echo ($ptoRelation['update_schedule']?'[update schedule on  '.(date('m/d/Y',$ptoRelation['update_schedule'])):''); ?>
			</p>
			
			
			<p>
				PTO Balance:   <input value="<?= ($ptoRelation&&$ptoRelation['balance']?$ptoRelation['balance']:''); ?>" name="balance" id="balance" class="form-control" />
				

				
			</p>	
			


		<input type="submit"  class="btn btn-primary"  value="Update" />	
		<input type="hidden" name="bid" value="<?= $bid; ?>" />
		<input type="hidden" name="action" value="user_pto_update" />

		</form>




		</div>
		
		</div> <!-- pto-content -->
		
		<?php endif; //if($bid && in_array($bid,$adminsIds) ) ?>							
		
	

	
</div><!-- #userTabContent -->











 

 
			







       
	   
		  

	
	


 

 

<style>
.clearfix:after{content:" ";display:block;height:0;clear:both;visibility:hidden}
.clearfix{display:inline-block}
input,input:focus{ border: 1px #444 solid;}
 
#results .title{   text-transform: capitalize;} 
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}

#results div div{ padding:10px; border-left: 1px solid #ccc;}
#results p { font-size:14px;line-height:18px;margin: 10px 0;}

.sortable th {     cursor: pointer;}

#s-searchBar{    margin: 20px 10px;}


#searchBox .bts{margin-top:10px;}
#searchBox p{margin:5px 0;}

#searchBox .hide{ display:none;}

tr.signin a.profile{ background:url(<?= base_url(); ?>/assets/images/member.png) no-repeat 0 2px;     padding-left: 20px;}

#btFilter{ padding-right:40px;padding-left:40px;}

.x-results{margin-bottom:10px;}

#wrapNode{background: #fff; position: fixed; width: 300px; left: 50%; margin-left: -150px; margin-top: 15%; padding: 25px; box-shadow: 0 0 20px 2px #000; z-index:999; }
#wrapNode input,#wrapNode select{ width:100%; margin-bottom:15px; }
#wrapNode textarea{ width:100%; height:150px; margin-bottom:15px; }

#wrapNode span{  display: block; color: red; }

.list td.selected{    background: #fff38a;}

/* Loading overlay for MailChimp operations */
#mailchimpOverlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
#mailchimpOverlay.active {
    display: flex;
}
#mailchimpOverlay .loader {
    text-align: center;
    color: white;
}
#mailchimpOverlay .spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}




.x-results a{padding: 0 5px; }
.x-results a.current{background: #ccc; }
.x-results a.current:after{ content:' ▼'; }


 

.hidenBox ul,.hidenBox p{ margin: 0 0 10px 0;
    padding: 0;}
.hidenBox li{        padding-bottom: 10px;
    padding-right: 20px;}
.hidenBox h3{    margin: 10px 0;}	

  #returnMinistries li{   text-decoration: underline;
    color: blue;
    cursor: pointer;
  }
  
#returnMinistries li:hover{ text-decoration: none;}  

#returnMinistries li:before{ content:'└ '; }

#mySDiv li, #myHDiv li, #siteDiv li{
	
    display: inline-block;
}

@media screen and (max-width:575px ) {
	
	#mySDiv li, #myHDiv li{
	    width: 90%;
    display: inline-block;
}

}
</style>
	
	
	

<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css?v=001' type='text/css'/>


	<script type="text/javascript">
	
	// Store original values for comparison
	var originalInactiveStatus = $('#inactive').val();
	var originalEmail = $('#email').val();
	
	function importDataFrom(e){
		
			const elem = e;

			const bid = elem.getAttribute('data-bid');	
		
			if(timer) clearTimeout(timer); 
			if(ajaxer) ajaxer.abort(); 
			elem.innerHTML='Loading...';
								
			
			timer = setTimeout(function() {
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?= $canonical; ?>',
					data: {	bid: bid,action:'importDataFrom'},      
					success:function(data){
						
							alert(data);
							window.location.reload();

						
					}
				});
				
			 },600);	
	}	
	
	function findSameNameAs(bid){
			if(timer) clearTimeout(timer); 
			if(ajaxer) ajaxer.abort(); 
			$('#findSameNameAs').html('Loading...');
								
			
			timer = setTimeout(function() {
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?= $canonical; ?>',
					data: {	bid: bid,action:'findSameNameAs'},      
					success:function(data){
						
							$('#findSameNameAs').html(data);

						
					}
				});
				
			 },600);	
	}


	$('.dateInput').each(function(index) {	
	var e = document.getElementById($(this).attr('id'));
	  rome(e, {
		  time:false,
		  inputFormat: "MM/DD/YYYY",
		});
	});



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
			
			// Check if we need to show MailChimp overlay
			// Do not show overlay when adding new person (!$bid)
			var bid = $('input[name="bid"]').val();
			var isAddingNewPerson = !bid || bid == '0' || bid == '';
			
			var showMailchimpOverlay = false;
			
			// Only show overlay when editing existing user (bid exists) and status/email changes
			if(!isAddingNewPerson) {
				var currentInactiveStatus = $('#inactive').val();
				var currentEmail = $('#email').val();
				var statusChanged = (originalInactiveStatus != currentInactiveStatus);
				var statusChangedToMember = (originalInactiveStatus != '3' && currentInactiveStatus == '3');
				var statusChangedFromMember = (originalInactiveStatus == '3' && currentInactiveStatus != '3');
				var emailChanged = (originalEmail != currentEmail);
				showMailchimpOverlay = (statusChanged && (statusChangedToMember || statusChangedFromMember)) || emailChanged;
			}
			
			if(timer) clearTimeout(timer); 
			if(ajaxer) ajaxer.abort(); 
			
			var params = $(this).serialize();
			
			// Show overlay if MailChimp update is expected
			if(showMailchimpOverlay) {
				$('#mailchimpOverlay').addClass('active');
			}
			
			timer = setTimeout(function() {
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?= $canonical; ?>',
					data: params,      
					success:function(data){
						// Hide overlay
						$('#mailchimpOverlay').removeClass('active');
						
						// Update fmsg with response
						$('#baptistForm .fmsg').html(data);
						
						// Update original values if successful
						if(data.indexOf('Updated successfully') !== -1 || data.indexOf('Inserted Successfully') !== -1) {
							originalInactiveStatus = $('#inactive').val();
							originalEmail = $('#email').val();
							
							// Refresh MailChimp status field if MailChimp was updated
							if(data.indexOf('MailChimp') !== -1) {
								var bid = $('input[name="bid"]').val();
								if(bid) {
									// Fetch updated user data to get latest MailChimp status
									$.ajax({
										dataType:'json',
										method: "GET",
										url: '<?= base_url('xAdmin/baptist/getMailchimpStatus'); ?>',
										data: {bid: bid},
										success: function(response) {
											if(response && response.onMailchimp) {
												var status = response.onMailchimp;
												$('#onMailchimp').val(status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Not set');
											}
										},
										error: function() {
											// Silently fail - status will update on next page load
										}
									});
								}
							}
						}
					},
					error: function(xhr, status, error) {
						// Hide overlay on error
						$('#mailchimpOverlay').removeClass('active');
						$('#baptistForm .fmsg').html('Error: ' + error);
					}
				});
				
			 },600);
		}
		
	});

	function delUser(id){
		var r = confirm("Please press 'OK' to continue");
		var url = '<?= $canonical; ?>';
		
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
										url: '<?= $canonical; ?>',     
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





$('.fileUpload').on('change',function(){

        
		var mb=5*1024*1024;
		var ext=['doc','ppt','pdf','docx','txt','pptx','jpg','jpeg','png'];

		
		var name = $(this).data('name');
		var url = '<?= base_url('member'); ?>';
		var ajMsgSpan = $(this).siblings('.ajMsg');
		var action = $(this).data('action');
		var filesContainer = $(this).siblings('.filesContainer');
		var multiple = $(this).data('multiple');





		
             
        
            if($(this)[0].files[0]!='undifined'){
				$(ajMsgSpan).html('Loading...');
                
                var file=$(this)[0].files[0];
                
                if(file.size <= mb){
				    
               	       var stringarray=file.name.split('.');
                       
                       if(jQuery.inArray(stringarray[stringarray.length-1].toLowerCase(),ext)!= -1){

						

				 
								var fd = new FormData();
								fd.append(name, file);	
								fd.append("action", action);	
				 
				 
				 				$.ajax({
									dataType:'json',
									method: "POST",
									url: url,     
									data: fd,
									processData: false,
									contentType: false,									
									cache: false,
									success: function (res) {
										
										if(res.code == '1'){

											if(multiple){
												$(filesContainer).append('<p class="my-2"><a target="_blank" href="'+res.fileUrl+'">'+res.fileName+'</a><a class="fileRemove text-danger ml-3" href="javascript:void(0);">x Remove</a><input type="hidden" name="'+name+'[]" value="'+res.fileUrl+'" /></p>');
											}else{
												$(filesContainer).html('<p class="my-2"><a target="_blank" href="'+res.fileUrl+'">'+res.fileName+'</a><a class="fileRemove text-danger ml-3" href="javascript:void(0);">x Remove</a><input type="hidden" name="'+name+'" value="'+res.fileUrl+'" /></p>');
											}
											
											$(ajMsgSpan).html('');
											
										}else{
											$(ajMsgSpan).html('Error');


										}
										
										


									},
									complete:function (res) { console.log(res); }
								});	


						}else{

			             $(ajMsgSpan).html('Invalid file extension');

						}

				}else{

			       $(ajMsgSpan).html('Invalid file size');

				}
        
   
        }
        
      
        
    });

	$( document ).on( "click", ".fileRemove", function() {
		$(this).parent('p').remove();
	});



	</script>
	


