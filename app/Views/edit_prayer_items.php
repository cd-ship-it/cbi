
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



@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	   

?>

<h1 class="pageTitle"><?= $pageTitle; ?> <a href="<?= $goBackUrl; ?>">返回</a></h1>
 
<div id="layout">

<form action="<?= $canonical; ?>" method="post" class="rForm myAjForm" id="prayerEditForm" >

	English: <a href="javaScript: void(0);" onclick="han2han('zh_hant','zh-CHT','en','en','hant2enMsg');">從 T Chinese 翻譯</a> <span id="hant2enMsg"></span> <br />
	<textarea id="en" name="en" rows="9" cols="50"><?= ($theItem?$theItem['en']:''); ?></textarea><br /><br />
	
	T chinese:  <a href="javaScript: void(0);" onclick="han2han('zh_hans','zh-CHS','zh-CHT','zh_hant','hans2hantMsg');">從 S Chinese 提取</a> <span id="hans2hantMsg"></span> <br />
	<textarea id="zh_hant" name="zh_hant" rows="9" cols="50"><?= ($theItem?$theItem['zh_hant']:''); ?></textarea><br /><br />
	
	S chinese: <a href="javaScript: void(0);" onclick="han2han('zh_hant','zh-CHT','zh-CHS','zh_hans','hant2hansMsg');">從 T Chinese 提取</a> <span id="hant2hansMsg"></span> <br /> 
	<textarea id="zh_hans" name="zh_hans" rows="9" cols="50"><?= ($theItem?$theItem['zh_hans']:''); ?></textarea><br /><br />




	<p class="bts">
	
	<input type="hidden"  name="action" value="prayerItemSave" />
	<input type="hidden"  name="prayer_id" value="<?= $prayer_id; ?>" />
	<input type="hidden"  name="day" value="<?= $day; ?>" />
	<input type="hidden"  name="author_id" value="<?= $author_id; ?>" />
	
	<input type="submit" <?= ($author_id?'':'disabled'); ?> id="btSubmit" value="Save" />  
	
	</p>
	
	
	<p class="fmsg"></p> 
</form>	


</div>


</div>

<script type="text/javascript">

var timer  = null; 
var ajaxer  = null; 



function han2han(fromElement,from,to,toElement,msgElement){
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		myMsgElement = document.getElementById(msgElement);
		myToElement = document.getElementById(toElement);
		
		var params = {
			query: document.getElementById(fromElement).value,
			from: from,
			to: to,
		};
		
		$(myMsgElement).html('Loading...');
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: '<?= $translation_url; ?>',
				data: params,      
				success:function(data){
					
						$(myMsgElement).html('');
						 $(myToElement).val(data);
					
				}
			});
			
		 },600);
}






$("#prayerEditForm").submit(function(event){
    event.preventDefault();

	var error='';
	$('.fmsg').html('Loading...');
	

		
		
		var params = $(this).serialize();
		
		
			
			$.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: params,      
				success:function(data){
					
						
						$('#prayerEditForm .fmsg').html(data);

					
				}
			});
			
		
		 
		
		
	
	
});





</script>

</body>
</html>