<!DOCTYPE html>
<html class="html" lang="en-US">
<head>

<!-- COMMON TAGS -->
<meta charset="utf-8">
<title><?= $title; ?></title>


<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<meta name="viewport" content="width=device-width, initial-scale=1" />
<script type="text/javascript" src="<?= base_url(); ?>/assets/jquery-3.3.1.min.js"></script>


<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css' type='text/css'/>

<style type="text/css">

img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}

.qa a{color: #fff;        text-decoration: underline !important;}
</style>
<link rel='stylesheet' id='tribe-common-skeleton-style-css'  href='https://crosspointchurchsv.org/wp-content/plugins/the-events-calendar/common/src/resources/css/common-skeleton.min.css?ver=4.12.5' type='text/css' media='all' />
<link rel='stylesheet' id='tribe-tooltip-css'  href='https://crosspointchurchsv.org/wp-content/plugins/the-events-calendar/common/src/resources/css/tooltip.min.css?ver=4.12.5' type='text/css' media='all' />
<link rel='stylesheet' id='wp-block-library-css'  href='https://crosspointchurchsv.org/wp-includes/css/dist/block-library/style.min.css?ver=5.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='wp-block-library-theme-css'  href='https://crosspointchurchsv.org/wp-includes/css/dist/block-library/theme.min.css?ver=5.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='trp-language-switcher-style-css'  href='https://crosspointchurchsv.org/wp-content/plugins/translatepress-multilingual/assets/css/trp-language-switcher.css?ver=1.7.8' type='text/css' media='all' />
<link rel='stylesheet' id='oceanwp-style-css'  href='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/css/style.min.css?ver=1.0' type='text/css' media='all' />
<link rel='stylesheet' id='child-style-css'  href='https://crosspointchurchsv.org/wp-content/themes/oceanwp-child-theme-master/style.css?ver=5.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='font-awesome-css'  href='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/fonts/fontawesome/css/all.min.css?ver=5.11.2' type='text/css' media='all' />
<link rel='stylesheet' id='simple-line-icons-css'  href='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/css/third/simple-line-icons.min.css?ver=2.4.0' type='text/css' media='all' />
<link rel='stylesheet' id='magnific-popup-css'  href='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/css/third/magnific-popup.min.css?ver=1.0.0' type='text/css' media='all' />
<link rel='stylesheet' id='slick-css'  href='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/css/third/slick.min.css?ver=1.6.0' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-icons-css'  href='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/eicons/css/elementor-icons.min.css?ver=5.7.0' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-animations-css'  href='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/animations/animations.min.css?ver=2.9.14' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-frontend-css'  href='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/css/frontend.min.css?ver=2.9.14' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-pro-css'  href='https://crosspointchurchsv.org/wp-content/plugins/elementor-pro/assets/css/frontend.min.css?ver=2.10.3' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-global-css'  href='https://crosspointchurchsv.org/wp-content/uploads/elementor/css/global.css?ver=1597877237' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-post-1914-css'  href='https://crosspointchurchsv.org/wp-content/uploads/elementor/css/post-1914.css?ver=1597875986' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-post-557-css'  href='https://crosspointchurchsv.org/wp-content/uploads/elementor/css/post-557.css?ver=1596652195' type='text/css' media='all' />
<link rel='stylesheet' id='oe-widgets-style-css'  href='https://crosspointchurchsv.org/wp-content/plugins/ocean-extra/assets/css/widgets.css?ver=5.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='google-fonts-1-css'  href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto+Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMontserrat%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;ver=5.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-icons-shared-0-css'  href='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/font-awesome/css/fontawesome.min.css?ver=5.12.0' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-icons-fa-brands-css'  href='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/font-awesome/css/brands.min.css?ver=5.12.0' type='text/css' media='all' />
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-includes/js/jquery/jquery.js?ver=1.12.4-wp'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.4.1'></script>


<style type="text/css" id="wp-custom-css">
body div.wpforms-container-full .wpforms-form input.wpforms-field-medium,body div.wpforms-container-full .wpforms-form input.wpforms-field-medium,body div.wpforms-container-full .wpforms-form select.wpforms-field-medium,body div.wpforms-container-full .wpforms-form .wpforms-field-row.wpforms-field-medium{max-width:100%}.mybutton{background-color:#FD8E47;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;-webkit-border-radius:3px;border-radius:3px}		</style>
<!-- OceanWP CSS -->
<style type="text/css">
/* General CSS */a:hover,a.light:hover,.theme-heading .text::before,#top-bar-content >a:hover,#top-bar-social li.oceanwp-email a:hover,#site-navigation-wrap .dropdown-menu >li >a:hover,#site-header.medium-header #medium-searchform button:hover,.oceanwp-mobile-menu-icon a:hover,.blog-entry.post .blog-entry-header .entry-title a:hover,.blog-entry.post .blog-entry-readmore a:hover,.blog-entry.thumbnail-entry .blog-entry-category a,ul.meta li a:hover,.dropcap,.single nav.post-navigation .nav-links .title,body .related-post-title a:hover,body #wp-calendar caption,body .contact-info-widget.default i,body .contact-info-widget.big-icons i,body .custom-links-widget .oceanwp-custom-links li a:hover,body .custom-links-widget .oceanwp-custom-links li a:hover:before,body .posts-thumbnails-widget li a:hover,body .social-widget li.oceanwp-email a:hover,.comment-author .comment-meta .comment-reply-link,#respond #cancel-comment-reply-link:hover,#footer-widgets .footer-box a:hover,#footer-bottom a:hover,#footer-bottom #footer-bottom-menu a:hover,.sidr a:hover,.sidr-class-dropdown-toggle:hover,.sidr-class-menu-item-has-children.active >a,.sidr-class-menu-item-has-children.active >a >.sidr-class-dropdown-toggle,input[type=checkbox]:checked:before{color:#136ebf}input[type="button"],input[type="reset"],input[type="submit"],button[type="submit"],.button,#site-navigation-wrap .dropdown-menu >li.btn >a >span,.thumbnail:hover i,.post-quote-content,.omw-modal .omw-close-modal,body .contact-info-widget.big-icons li:hover i,body div.wpforms-container-full .wpforms-form input[type=submit],body div.wpforms-container-full .wpforms-form button[type=submit],body div.wpforms-container-full .wpforms-form .wpforms-page-button{background-color:#136ebf}.widget-title{border-color:#136ebf}blockquote{border-color:#136ebf}#searchform-dropdown{border-color:#136ebf}.dropdown-menu .sub-menu{border-color:#136ebf}.blog-entry.large-entry .blog-entry-readmore a:hover{border-color:#136ebf}.oceanwp-newsletter-form-wrap input[type="email"]:focus{border-color:#136ebf}.social-widget li.oceanwp-email a:hover{border-color:#136ebf}#respond #cancel-comment-reply-link:hover{border-color:#136ebf}body .contact-info-widget.big-icons li:hover i{border-color:#136ebf}#footer-widgets .oceanwp-newsletter-form-wrap input[type="email"]:focus{border-color:#136ebf}a{color:#136ebf}body .theme-button,body input[type="submit"],body button[type="submit"],body button,body .button,body div.wpforms-container-full .wpforms-form input[type=submit],body div.wpforms-container-full .wpforms-form button[type=submit],body div.wpforms-container-full .wpforms-form .wpforms-page-button{background-color:#136ebf}body .theme-button:hover,body input[type="submit"]:hover,body button[type="submit"]:hover,body button:hover,body .button:hover,body div.wpforms-container-full .wpforms-form input[type=submit]:hover,body div.wpforms-container-full .wpforms-form input[type=submit]:active,body div.wpforms-container-full .wpforms-form button[type=submit]:hover,body div.wpforms-container-full .wpforms-form button[type=submit]:active,body div.wpforms-container-full .wpforms-form .wpforms-page-button:hover,body div.wpforms-container-full .wpforms-form .wpforms-page-button:active{background-color:#02b4ea}/* Header CSS */#site-header.has-header-media .overlay-header-media{background-color:rgba(0,0,0,0.5)}#site-header #site-logo #site-logo-inner a img,#site-header.center-header #site-navigation-wrap .middle-site-logo a img{max-height:65px}/* Blog CSS */.single-post.content-max-width .thumbnail,.single-post.content-max-width .entry-header,.single-post.content-max-width ul.meta,.single-post.content-max-width .entry-content p,.single-post.content-max-width .entry-content h1,.single-post.content-max-width .entry-content h2,.single-post.content-max-width .entry-content h3,.single-post.content-max-width .entry-content h4,.single-post.content-max-width .entry-content h5,.single-post.content-max-width .entry-content h6,.single-post.content-max-width .wp-block-image,.single-post.content-max-width .wp-block-gallery,.single-post.content-max-width .wp-block-video,.single-post.content-max-width .wp-block-quote,.single-post.content-max-width .wp-block-text-columns,.single-post.content-max-width .entry-content ul,.single-post.content-max-width .entry-content ol,.single-post.content-max-width .wp-block-cover-text,.single-post.content-max-width .post-tags,.single-post.content-max-width .comments-area,.wp-block-separator.is-style-wide{max-width:708px}.single-post.content-max-width .wp-block-image.alignleft,.single-post.content-max-width .wp-block-image.alignright{max-width:354px}.single-post.content-max-width .wp-block-image.alignleft{margin-left:calc( 50% - 354px)}.single-post.content-max-width .wp-block-image.alignright{margin-right:calc( 50% - 354px)}/* Footer Widgets CSS */#footer-widgets,#footer-widgets p,#footer-widgets li a:before,#footer-widgets .contact-info-widget span.oceanwp-contact-title,#footer-widgets .recent-posts-date,#footer-widgets .recent-posts-comments,#footer-widgets .widget-recent-posts-icons li .fa{color:#dddddd}/* Typography CSS */body{font-size:18px;color:#280202;line-height:1.6}h2{line-height:1.3}#site-navigation-wrap .dropdown-menu >li >a,#site-header.full_screen-header .fs-dropdown-menu >li >a,#site-header.top-header #site-navigation-wrap .dropdown-menu >li >a,#site-header.center-header #site-navigation-wrap .dropdown-menu >li >a,#site-header.medium-header #site-navigation-wrap .dropdown-menu >li >a,.oceanwp-mobile-menu-icon a{font-size:16px}.dropdown-menu ul li a.menu-link,#site-header.full_screen-header .fs-dropdown-menu ul.sub-menu li a{font-size:16px;line-height:1.5}.page-header .page-header-title,.page-header.background-image-page-header .page-header-title{line-height:3.4}
</style>

<style>



.rForm p {padding:3px 0;}
.rForm .bts {padding:10px 0;}
.rForm #btSubmit {    padding-right: 40px;    padding-left: 40px;}
.rForm .fmsg {   color:red;}

body{background:#f3f3f3  url("<?= base_url(); ?>/assets/images/bg_03.jpg") repeat-x top center;     background-size: contain;}




#helloyou {
    color: #fff;
    padding: 40px 40px 0px 40px;
    font-size: 18px;
	    max-width: 1200px;
    margin: 0 auto;
	    text-shadow: 1px 1px 1px #000;
}

#userClasses,#s-baptist{ 	max-width: 1200px; min-height:500px;
	margin:0 auto; background: #fff;
    padding: 40px;
    box-shadow: 0 0 15px 0px #bbb;
    margin-top: 200px;     position: relative;}
									  
	

#cbiClasses{max-width: 1200px;	margin:0 auto;     display: flex; margin-top:50px;}
#cbiClasses .inWrap{  background: #fff;    padding: 40px;    box-shadow: 0 0 15px 0px #bbb;    width: 100%;
 }
#cbiClasses .left{ margin-right:30px;}	
#cbiClasses a:hover{color:#4054B2;}	

#cbiClasses p{ font-size: 18px;
    color: #7a7a7a;
    line-height: 24px;}	

#cbiClasses h2 {
    color: #4054B2;
    font-size: 24px;
    font-weight: 900;
    line-height: 1.1em;
}

#cbiClasses a{     font-weight: 600;
    margin: 0 0 20px;
    color: #333;
    line-height: 1.4;}	

#results{width:100%;     display: flex;  flex-wrap:   wrap ;}
.flex-item{    
    margin: 20px 30px 20px 0;
    padding: 20px 20px 20px 0; width: 240px;}
	
#results .code{       background: #fd8e47;    padding: 3px 5px; font-size:14px; }

#results h4{ margin: 15px 0 20px 0;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;     font-weight: bold;
    font-size: 21px;
}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}

#results div div{ padding:10px; border-left: 1px solid #ccc;}
#results a {     font-size: 14px;
    line-height: 18px;
    margin: 6px 0;
    display: inline-block;}



p#des{ font-size:14px;line-height:18px;margin: 20px ;}

#s-recommend{margin-top: 30px;    border-top: 1px solid #ccc;    padding-top: 20px;}
p.recommend{ margin-top:20px;}

#logedHeader{ margin-bottom:20px;}

#loginMsg{color:red;}

#signInWrapper{margin: 20px 0;}
#customGGBtn:hover {  cursor: pointer;}

.changeEmail,.saveEmail,.cancelEmail{font-size: 12px;}

.saveEmail,.cancelEmail{display:none;}

#emailFunction{
    font-size: 12px;
    color: red;
    padding-bottom: 5px;
}



.pageHeader{
    
	text-align: center;
	margin-bottom:30px;
}

.pageHeader h1 {
    color: #4054B2;
    font-size: 35px;
    font-weight: 900;
    line-height: 1.1em;
	    margin: 0 0 20px;
}

.elementor-divider{    
    padding-top: 15px;
    padding-bottom: 15px;    line-height: 0;
    font-size: 0;}
	
.elementor-divider span{ display: inline-block;     width: 70px;
    margin: 0 auto;   border:2px #FD8E47 solid;}

.pageDescription{    margin: 10px 0;}


form input[type="text"]{
	border-width: 0px 0px 1px 0px;
    border-radius: 0px 0px 0px 0px;
	width:50%;
}

form input[type=text]:focus{background-color: #eaeded;}

form select{width:50%;}

#group_name{
	display:flex;
}

#group_name p{ padding-right:20px;     width: 100%;}
#group_name input[type="text"]{
	width:100%;
}

#signInWrapper{margin: 20px 0;    }
.signinBt{  cursor: pointer;
    display: inline-block;
    width: 220px;
    font-size: 14px;
    height: 45px;
    line-height: 45px;
    border-radius: 2px;
    padding-left: 22px;
    text-align: center; border: 1px solid #eee;
    }
.signinBt:hover{     font-weight: bold;
}	
	
	
.sbt_google{background:#fff url(<?= base_url().'/assets/images/btn_google.png'; ?>) no-repeat  10px center;}	
.sbt_yahoo{background:#fff url(<?= base_url().'/assets/images/btn_yahoo.png'; ?>) no-repeat  10px center;}	


#joinUsTip{background: #FFC107;
    padding: 10px;
    margin-bottom: 30px;
    font-weight: bold;
    border-radius: 5px;
    border: 1px dashed #000; }

#joinUsTip a{ text-decoration: underline;}

.tabMenu{ position: absolute; right: 42px;         top: -33px;}

.tab{   
   
    background: #FFC107;
    padding: 7px 15px;
    color: #000;
    font-weight: bold;  text-decoration: underline;
}

#membershipTab{ }
	   
#shapeTab{ right:  372px;
       top: -42px;}
	   
#testimonyTab{ right:202px;
       top: -42px;}	   

#joinUsTip a:hover,.tab:hover{     text-decoration: none;
}

.cbiorezone { text-align:center; }
.cbiorezone a { margin:0 20px; border:5px #fff solid; display:inline-block; }
.cbiorezone a:hover {     opacity: 0.8;  border:5px #12369a solid;  }

@media screen and (max-width:1200px ) {
	
	    body{ background-size: auto 450px; }
		
		div#s-baptist {    margin-top: 100px;}
}

@media screen and (max-width:800px ) {
	
	    #cbiClasses{flex-wrap:   wrap ;}
		#cbiClasses .left{ margin-right:0;}	
}


@media screen and (max-width:600px ) {
		#group_name{flex-wrap:   wrap ;}
	   .flex-item{ width:100%;}
}
</style>
</head>

<body class="page-template page-template-elementor_header_footer page wp-custom-logo wp-embed-responsive translatepress-en_US tribe-no-js oceanwp-theme sidebar-mobile default-breakpoint content-full-width content-max-width page-header-disabled elementor-default elementor-template-full-width elementor-kit-1914 elementor-page">

	
	
	<div id="outer-wrap" class="site clr">

		<a class="skip-link screen-reader-text" href="#main">Skip to content</a>

		
		<div id="wrap" class="clr">

			
			
<header id="site-header" class="minimal-header clr" data-height="74" itemscope="itemscope" itemtype="https://schema.org/WPHeader" role="banner">

	
					
			<div id="site-header-inner" class="clr container">

				
				

<div id="site-logo" class="clr" itemscope itemtype="https://schema.org/Brand">

	
	<div id="site-logo-inner" class="clr">

		<a href="https://crosspointchurchsv.org/" class="custom-logo-link" rel="home"><img width="293" height="74" src="https://crosspointchurchsv.org/wp-content/uploads/2020/07/cropped-Xpt-ID2015-1_300.jpg" class="custom-logo" alt="Crosspoint Church" /></a>
	</div><!-- #site-logo-inner -->

	
	
</div><!-- #site-logo -->

			<div id="site-navigation-wrap" class="clr">
			
			
			
			<nav id="site-navigation" class="navigation main-navigation clr" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement" role="navigation">

<?= $header; ?>

			</nav><!-- #site-navigation -->

			
			
					</div><!-- #site-navigation-wrap -->
			
		
	
				
	<div class="oceanwp-mobile-menu-icon clr mobile-right">

		
		
		
		<a href="#" class="mobile-menu" aria-label="Mobile Menu">
							<i class="fa fa-bars" aria-hidden="true"></i>
								<span class="oceanwp-text">Menu</span>

						</a>

		
		
		
	</div><!-- #oceanwp-mobile-menu-navbar -->


			</div><!-- #site-header-inner -->

			
			
			
		
		
</header><!-- #site-header -->

			<main id="main" class="site-main clr"  role="main">

						<div data-elementor-type="wp-page" data-elementor-id="557" class="elementor elementor-557" data-elementor-settings="[]">
			<div class="elementor-inner">
				<div class="elementor-section-wrap">
				
				

	
	


		<div id="mian">
		
			<h5 id="helloyou">Welcome, <?= $mloggedinName; ?></h5>	
			
			<p class="qa" style="color: #fff;padding: 10px 40px 0px 40px;font-size: 14px;max-width: 1200px;      text-shadow: 1px 1px 1px #000;  margin: 0 auto;"><?= lang('cbi_lang.havequestions', [],$userLg); ?>
			</p>
			
			
			
	<div class="sesstion cbiorezone" id="s-baptist">

<a href="<?php echo base_url('member');?>" title="CBI"><img src="<?= base_url(); ?>/assets/images/cbi.jpg" alt="cbi" /></a> 
<a href="<?php echo base_url('goToEZone');?>" title="eZone"><img src="<?= base_url(); ?>/assets/images/ezone.jpg" alt="ezone" /></a><br />





				</div>
				
				
	</div> <?php //div#mian ?>				   
		</main><!-- #main -->
	
</div>
			</div>
		</div>
		
	</main><!-- #main -->

	
	
<footer id="footer" class="site-footer" itemscope="itemscope" itemtype="https://schema.org/WPFooter" role="contentinfo">

	
	<div id="footer-inner" class="clr">

		

<div id="footer-widgets" class="oceanwp-row clr">

	
	<div class="footer-widgets-inner container">

					<p style=" margin-top: 20px; "><?= lang('cbi_lang.havequestions', [],$userLg); ?></p>
				
			
			
	</div><!-- .container -->

	
</div><!-- #footer-widgets -->



<div id="footer-bottom" class="clr no-footer-nav">

	
	<div id="footer-bottom-inner" class="container clr">

		
		   


			
	</div><!-- #footer-bottom-inner -->

	
</div><!-- #footer-bottom -->


	</div><!-- #footer-inner -->

	
</footer><!-- #footer -->


</div><!-- #wrap -->


</div><!-- #outer-wrap -->



<a id="scroll-top" class="scroll-top-right" href="#"><span class="fa fa-angle-up" aria-label="Scroll to the top of the page"></span></a>



<div id="sidr-close">
	<a href="#" class="toggle-sidr-close" aria-label="Close mobile Menu">
		<i class="icon icon-close" aria-hidden="true"></i><span class="close-text">Close Menu</span>
	</a>
</div>


<div id="mobile-menu-search" class="clr">

</div><!-- .mobile-menu-search -->

		<script>
		( function ( body ) {
			'use strict';
			body.className = body.className.replace( /\btribe-no-js\b/, 'tribe-js' );
		} )( document.body );
		</script>
		<script> /* <![CDATA[ */var tribe_l10n_datatables = {"aria":{"sort_ascending":": activate to sort column ascending","sort_descending":": activate to sort column descending"},"length_menu":"Show _MENU_ entries","empty_table":"No data available in table","info":"Showing _START_ to _END_ of _TOTAL_ entries","info_empty":"Showing 0 to 0 of 0 entries","info_filtered":"(filtered from _MAX_ total entries)","zero_records":"No matching records found","search":"Search:","all_selected_text":"All items on this page were selected. ","select_all_link":"Select all pages","clear_selection":"Clear Selection.","pagination":{"all":"All","next":"Next","previous":"Previous"},"select":{"rows":{"0":"","_":": Selected %d rows","1":": Selected 1 row"}},"datepicker":{"dayNames":["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],"dayNamesShort":["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],"dayNamesMin":["S","M","T","W","T","F","S"],"monthNames":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesShort":["January","February","March","April","May","June","July","August","September","October","November","December"],"monthNamesMin":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"nextText":"Next","prevText":"Prev","currentText":"Today","closeText":"Done","today":"Today","clear":"Clear"}};/* ]]> */ </script><script type='text/javascript' src='https://crosspointchurchsv.org/wp-includes/js/imagesloaded.min.js?ver=3.2.0'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/js/third/magnific-popup.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/js/third/lightbox.min.js?ver=1.0'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var oceanwpLocalize = {"isRTL":"","menuSearchStyle":"drop_down","sidrSource":"#sidr-close, #site-navigation, #mobile-menu-search","sidrDisplace":"1","sidrSide":"left","sidrDropdownTarget":"link","verticalHeaderTarget":"link","customSelects":".woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select","ajax_url":"https:\/\/crosspointchurchsv.org\/wp-admin\/admin-ajax.php"};
/* ]]> */
</script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/js/main.min.js?ver=1.0'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-includes/js/wp-embed.min.js?ver=5.4.2'></script>
<!--[if lt IE 9]>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/themes/oceanwp/assets/js/third/html5.min.js?ver=1.0'></script>
<![endif]-->
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/js/frontend-modules.min.js?ver=2.9.14'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor-pro/assets/lib/sticky/jquery.sticky.min.js?ver=2.10.3'></script>
<script type='text/javascript'>
var ElementorProFrontendConfig = {"ajaxurl":"https:\/\/crosspointchurchsv.org\/wp-admin\/admin-ajax.php","nonce":"ff1ea0433d","i18n":{"toc_no_headings_found":"No headings were found on this page."},"shareButtonsNetworks":{"facebook":{"title":"Facebook","has_counter":true},"twitter":{"title":"Twitter"},"google":{"title":"Google+","has_counter":true},"linkedin":{"title":"LinkedIn","has_counter":true},"pinterest":{"title":"Pinterest","has_counter":true},"reddit":{"title":"Reddit","has_counter":true},"vk":{"title":"VK","has_counter":true},"odnoklassniki":{"title":"OK","has_counter":true},"tumblr":{"title":"Tumblr"},"delicious":{"title":"Delicious"},"digg":{"title":"Digg"},"skype":{"title":"Skype"},"stumbleupon":{"title":"StumbleUpon","has_counter":true},"mix":{"title":"Mix"},"telegram":{"title":"Telegram"},"pocket":{"title":"Pocket","has_counter":true},"xing":{"title":"XING","has_counter":true},"whatsapp":{"title":"WhatsApp"},"email":{"title":"Email"},"print":{"title":"Print"}},"facebook_sdk":{"lang":"en_US","app_id":""},"lottie":{"defaultAnimationUrl":"https:\/\/crosspointchurchsv.org\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json"}};
</script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor-pro/assets/js/frontend.min.js?ver=2.10.3'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-includes/js/jquery/ui/position.min.js?ver=1.11.4'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/dialog/dialog.min.js?ver=4.7.6'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/waypoints/waypoints.min.js?ver=4.0.2'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/swiper/swiper.min.js?ver=5.3.6'></script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/lib/share-link/share-link.min.js?ver=2.9.14'></script>
<script type='text/javascript'>
var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnTwitter":"Share on Twitter","pinIt":"Pin it","downloadImage":"Download image"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"version":"2.9.14","urls":{"assets":"https:\/\/crosspointchurchsv.org\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"general":{"elementor_global_image_lightbox":"yes","elementor_lightbox_enable_counter":"yes","elementor_lightbox_enable_fullscreen":"yes","elementor_lightbox_enable_zoom":"yes","elementor_lightbox_enable_share":"yes","elementor_lightbox_title_src":"title","elementor_lightbox_description_src":"description"},"editorPreferences":[]},"post":{"id":557,"title":"Giving%20%E2%80%93%20Crosspoint%20Church","excerpt":"<h2>Giving<\/h2>\t\t\n\t\t\t<h1>God is our provider<\/h1>\t\t\n\t\t<p>He promises in Matthew 6:33 to provide for our needs. The Bible teaches that everything we have comes from God and belongs to Him. Psalm 24:1 says \u201cThe earth is the Lord\u2019s and everything in it, the world, and all who live in it.\u201d<\/p><p>Therefore, we are mere stewards of what God has given to us. Tithing is giving back to God what is already His. We return 10% back to Him. In His generosity, He provides for us to keep 90%.<\/p><h2>Online Giving<\/h2><p>Online giving via Tithe.ly provides a secure and convenient way to give a one-time contribution, and\/or recurring offering.<\/p><p>Transaction Fees:<\/p><ul><li>Credit\/Debit Cards (2.9%)<\/li><li>ACH a.k.a eCheck (1%)<\/li><li>American Express (3.5%)<\/li><\/ul><p>To give via Tithe.ly, begin by selecting the campus you attend.<\/p>\t\t\n\t\t\t<a href=\"https:\/\/tithe.ly\/give?c=149158\" role=\"button\">\n\t\t\t\t\t\tMilpitas\n\t\t\t\t\t<\/a>\n\t\t\t<a href=\"https:\/\/tithe.ly\/give?c=152014\" role=\"button\">\n\t\t\t\t\t\tPleasanton\n\t\t\t\t\t<\/a>\n\t\t\t<a href=\"https:\/\/tithe.ly\/give?c=1271344\" role=\"button\">\n\t\t\t\t\t\tPeninsula\n\t\t\t\t\t<\/a>\n\t\t\t<a href=\"https:\/\/tithe.ly\/give?c=1271486\" role=\"button\">\n\t\t\t\t\t\tTracy\n\t\t\t\t\t<\/a>\n\t\t<h3>PayPal Giving Fund<\/h3><p>You can donate with a Credit Card without any transaction fees to you and Crosspoint Church!!<br \/>Simply use the <a href=\"https:\/\/www.paypal.com\/us\/fundraiser\/charity\/2438491\" target=\"_blank\" rel=\"noopener noreferrer\">PayPal Giving Fund<\/a> to donate to Crosspoint Church<\/p><p>NOTE: Instead of Crosspoint Church, PayPal Giving Fund will directly issue donation transaction receipts to you for your offerings.<\/p>\t\t\n\t\t\t<a href=\"https:\/\/www.paypal.com\/us\/fundraiser\/charity\/2438491\" target=\"_blank\" role=\"button\" rel=\"noopener noreferrer\">\n\t\t\t\t\t\tPayPal Giving Fund\n\t\t\t\t\t<\/a>\n\t\t<h2>Other ways of giving<\/h2><h3>Checks\/Cash<\/h3><p>Please write your check payable to Crosspoint Church of Silicon Valley. You can place the check and cash inside the basket during offering time in worship. You can also place your check in any secured offering box around the building.<\/p><h3>Company Matching Gifts<\/h3><p>Did you know many companies offer a matching gift program to encourage philanthropy among their employees? And that some companies will even match to spouses and retirees? Use the websites below to find out if your employer participates in<strong> a <a style=\"color: #3366ff;\" href=\"https:\/\/www.bestbuddies.org\/matching-gift-search\/\">matching gift program.<\/a><\/strong><\/p><p>If you do not find your employer listed, please check with your company\u2019s Human Resources department to see if your company offers a matching gift program.<br \/>For Crosspoint Church tax ID or additional information about Matching Gifts, please contact the finance department at finance@crosspointchurchsv.org.<\/p><h3>Stocks\/Bonds\/ETF\/Mutual Funds<\/h3><p>Appreciated Marketable Securities are a great way to maximize your giving, because you receive an immediate income tax deduction for the full fair market value of your securities. In addition, you pay no capital gains tax on the difference between your cost and the fair market value.\u2028 \u2028For more information, please contact the finance department at finance@crosspointchurchsv.org.<\/p><h3>Donor-Advised Funds<\/h3><p>If you have a Donor-Advised Fund, you can make a grant recommendation to Crosspoint Church without any cost to you and Crosspoint. Any questions, please contact the finance department at finance@crosspointchurchsv.org.<\/p><h3>Car Donation<\/h3><p>Crosspoint has partnered with Riteway Car Donations to make donating your car as easy as 1, 2, 3! And your donation is 100% Tax Deductible!<\/p><p>Click<strong> <a style=\"color: #3366ff;\" href=\"https:\/\/www.ritewaycardonations.org\/give\/crosspoint-chinese-church-of-silicon-valley-car-donation\">here<\/a> <\/strong>for more information about car donations.<\/p>","featuredImage":false}};
</script>
<script type='text/javascript' src='https://crosspointchurchsv.org/wp-content/plugins/elementor/assets/js/frontend.min.js?ver=2.9.14'></script>



</body>
</html>