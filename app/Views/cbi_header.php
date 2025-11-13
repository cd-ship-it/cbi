<?php

$webConfig = new \Config\WebConfig();
$langLabel = array('zh-Hant'=>'繁體中文','zh-Hans'=>'简体中文','en'=>'English');


$userCaps = isset($_SESSION['capabilities'])?$_SESSION['capabilities']:[];
 
$logout = isset($_GET['logout']);

?>



				<ul id="menu-main-menu" class="main-menu dropdown-menu sf-menu">
				
						<?php if($login && $webConfig->checkPermissionByDes('dashboard_view')): ?>	
						
							<li  class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= base_url('xAdmin?v='.rand()); ?>" class="menu-link">
								<span class="text-wrap">&#8630	<?= lang('cbi_lang.menu_admin', [],$userLg); ?></span></a></li>
							
							
						<?php endif; ?>					
				
				
						<?php if($login && $webConfig->checkPermissionByDes('is_bot_chair')): ?>	
						
							<li  class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= base_url('pto'); ?>" class="menu-link">
								<span class="text-wrap">PTO</span></a></li>		

						<?php endif; ?>			
				

						<?php if($login && $webConfig->checkPermissionByDes('is_delegate')): ?>	
						
							<li  class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= base_url('nva/table/'.$login.'/0'); ?>" class="menu-link">
								<span class="text-wrap">NVA</span></a></li>		

						<?php endif; ?>		



				


					<?php if($login): ?>	
						
						
				
						
						<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= base_url('member/classes'); ?>" class="menu-link"><span class="text-wrap"><?= lang('cbi_lang.menu_myclasses', [],$userLg); ?></span></a></li>
						
						<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?= base_url('member/profile'); ?>" class="menu-link"><span class="text-wrap"><?= lang('cbi_lang.menu_profile', [],$userLg); ?></span></a></li>
						
						<li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="<?php echo base_url('?logout=1'); ?>" class="menu-link"><span class="text-wrap"><?= lang('cbi_lang.menu_logout', [],$userLg); ?></span></a></li>
						
						
						
					<?php endif; //login ?>	
					
					
						<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children dropdown <?= $userLg; ?>"><a href="javascript:void(0);" class="menu-link"><span class="text-wrap"><?= $langLabel[$userLg]; ?></span><span class="nav-arrow fa fa-angle-down"></span></a>
						
						
						
						<ul class="sub-menu" style="display: none;">
						
						
						<?php if($userLg!='zh-Hant'): ?>
							<li class="trp-language-switcher-container menu-item menu-item-type-post_type menu-item-object-language_switcher">
								<a href="javascript:void(0);" onclick="setLg('zh-Hant')" class="menu-link zh-Hant"><span class="text-wrap">繁體中文</span></a></li>
						<?php endif; ?>		
								
						<?php if($userLg!='zh-Hans'): ?>
							<li class="trp-language-switcher-container menu-item menu-item-type-post_type menu-item-object-language_switcher">
								<a href="javascript:void(0);" onclick="setLg('zh-Hans')"  class="menu-link zh-Hans"><span class="text-wrap">简体中文</span></a></li><?php endif; ?>		
						
						<?php if($userLg!='en'): ?>
							<li class="trp-language-switcher-container menu-item menu-item-type-post_type menu-item-object-language_switcher">
								<a href="javascript:void(0);" onclick="setLg('en')"  class="menu-link en"><span class="text-wrap">English</span></a></li><?php endif; ?>		
								


						
						</ul>
						
						
					
				</ul>