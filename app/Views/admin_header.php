<?php

$webConfig = new \Config\WebConfig();
$session = \Config\Services::session();
$userCaps = $session->get('capabilities');

?>


	<div id="logedHeader">
	
		<div id="headerWrap">
		
		<h5>Welcome, <?= ($_SESSION['mloggedinName']); ?> | <?= (date("m/d/Y")); // g:i a ?></h5>	

<ul id="header-menu" class="clearfix">
			<li class="header-menu-li"><a href="<?= base_url('member'); ?>">&#8630	Personal</a> || </li>
			
			
			<?php if($webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>

				<li class="header-menu-li"><a href="<?= base_url('xAdmin'); ?>">Search</a> | </li>
				<li class="header-menu-li"><a href="<?= base_url('xAdmin/baptist'); ?>">Add people</a>  | </li>
				<li class="header-menu-li"><a href="<?= base_url('xAdmin/curriculums'); ?>">Classes</a> | 	</li>
				
			
				<li class="header-menu-li"><a href="#">Management</a> | 
				<ul>
				
					<li><a href="<?= base_url('nva'); ?>">New Visitor Assimilation</a> </li>
					
					<?php if($webConfig->checkPermissionByDes('report_summary')): ?>
						<li><a href="<?= base_url('report/summary'); ?>">Pastoral Reports Summary</a> </li>
					<?php endif; ?>
				
					<?php if($webConfig->checkPermissionByDes('management')): ?>	
					
							<li><a href="<?= base_url('xAdmin/management'); ?>">Permission</a> </li>	
							
					<?php endif; ?>			
					
							
					<?php if(in_array($_SESSION['mloggedin'],[207])||$webConfig->checkPermissionByDes('management')): ?>						
							
							<li><a href="<?= base_url('classdata'); ?>">Class Data</a> </li>
							
					<?php endif; ?>	

					
					<?php if(in_array($_SESSION['mloggedin'],[545,336,1187])||$webConfig->checkPermissionByDes('management')): //red debug?>						
							
							<li><a href="<?= base_url('xAdmin/prayer_items'); ?>">Prayer Items</a> </li>
							
					<?php endif; ?>		
					
					<?php 	if( isset($userCaps['manage_zone_pastors'])  ): ?>				
							
							<li><a href="<?= base_url('xAdmin/management/zonepastors'); ?>">Zone Pastors</a> </li>
							
					<?php endif; ?>			
							
							
							
							
							
					
					
							<li><a href="<?= base_url('pto/archive'); ?>">PTO</a> </li>
							

					</ul>
				</li>
			
			
				<li class="header-menu-li"><a href="<?= base_url('xAdmin/pending'); ?>">Membership</a> | </li>
				
				<li class="header-menu-li"><a href="<?= base_url('xAdmin/ministries'); ?>">Serving position</a> | </li>
			
			<?php endif; ?>			
			
			<?php if($webConfig->checkPermissionByDes('view_report')||$webConfig->checkPermissionByDes('edit_report')||$webConfig->checkPermissionByDes('is_senior_pastor')): ?>

				<li class="header-menu-li"><a href="<?= base_url('report'); ?>">Report</a> | </li>
			
			<?php endif; ?>		
						
			<?php if($webConfig->checkPermissionByDes('pto_apply')): ?>

				<li class="header-menu-li"><a href="<?= base_url('pto'); ?>">PTO</a> | </li>
			
			<?php endif; ?>		
			
		

				<li class="header-menu-li"><a href="<?= base_url('xAdmin/to_do'); ?>">To-do</a> | </li>
			
		

			
			<?php 	if( isset($userCaps['edit_class']) || isset($userCaps['edit_groups']) ): ?>

				<li class="header-menu-li"><a href="<?= base_url('xAdmin/group/search'); ?>">Groups</a> | </li>
			
			<?php endif; ?>	




			
			
			<li class="header-menu-li"><a href="<?= base_url().'/?logout=1'; ?>">Logout</a></li>	
</ul>		
		</div>
	
	</div>
	
	
	
	
	
	
	
	
<style>

#header-menu{padding:0;}
#header-menu li{ float:left; padding:2px 4px; height:20px; position:relative;}
#header-menu li ul{ position:absolute; background:#00aaff; padding:0; z-index: 99; display:none;}
#header-menu li li{  padding:5px; font-size:14px; width:200px;}

#header-menu li.header-menu-li:hover ul{display:block; width:200px;}

</style>	
	
	
	
	
	
	
	
