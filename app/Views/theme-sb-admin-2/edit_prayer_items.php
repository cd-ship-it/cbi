<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
		<h1 class="pageTitle"><?= $pageTitle; ?></h1>  
			<hr />	


 <?php if($theItem): ?>
 
	<div style=" border: 1px solid #FF9800; border-radius: 5px; padding: 5px 10px; margin: 20px 0; background: #fff7ec; ">
 Last updated on <?= date('Ymd', $theItem['day']);?> by <?= $author_name; ?>
	</div>


 <?php endif; ?>


<form action="<?= $canonical; ?>" method="post" class="rForm myAjForm" id="prayerEditForm" >

	English: <a href="javaScript: void(0);" onclick="han2han('zh_hant','zh-CHT','en','en','hant2enMsg');">Extract content from 'T Chinese' Input</a> <span id="hant2enMsg"></span> <br />
	<textarea class="form-control" id="en" name="en" rows="9" cols="50"><?= ($theItem?$theItem['en']:''); ?></textarea><br /><br />
	
	T chinese:  <a href="javaScript: void(0);" onclick="han2han('en','en','zh-CHT','zh_hant','hans2hantMsg');">Extract content from 'English' Input</a> <span id="hans2hantMsg"></span> <br />
	<textarea class="form-control" id="zh_hant" name="zh_hant" rows="9" cols="50"><?= ($theItem?$theItem['zh_hant']:''); ?></textarea><br /><br />
	
	S chinese: <a href="javaScript: void(0);" onclick="han2han('zh_hant','zh-CHT','zh-CHS','zh_hans','hant2hansMsg');">Extract content from 'T Chinese' Input</a> <span id="hant2hansMsg"></span> <br /> 
	<textarea class="form-control" id="zh_hans" name="zh_hans" rows="9" cols="50"><?= ($theItem?$theItem['zh_hans']:''); ?></textarea><br /><br />




	<p class="bts">
	
	<input type="hidden"  name="action" value="prayerItemSave" />
	<input type="hidden" id="prayer_id"  name="prayer_id" value="<?= $prayer_id; ?>" /> 
	
	<input  class="btn btn-primary px-5" type="submit" id="btSubmit" value="Save" /> 
	<input  class="btn btn-secondary px-5" type="reset" id="btReset" value="Reset" />
	
	</p>
	
	
	<p class="fmsg"></p> 
</form>	




</div>
</div>
</div>
</div>





 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>

<style>
textarea{ width:100%}
</style>

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


$("#btReset").on("click", function(e){
    e.preventDefault();
    $("#prayerEditForm")[0].reset();
    $('.fmsg').html('');
    $("#prayerEditForm textarea").val("");
    $("#prayer_id").val(0);
    $("#hant2enMsg,#hans2hantMsg,#hant2hansMsg").html("");
});

</script>