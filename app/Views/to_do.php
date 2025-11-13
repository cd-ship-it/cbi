
<!DOCTYPE html>
<html lang="en-US">
<head>

<!-- COMMON TAGS -->
<meta charset="utf-8">

<title><?= $pageTitle; ?></title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<meta name="viewport" content="width=device-width, initial-scale=1" />

<script type="text/javascript" src="<?= base_url(); ?>/assets/jquery-3.3.1.min.js"></script>

<link rel="stylesheet" href="<?= base_url(); ?>/assets/layout.css">

<style>

 
#layout{ padding:0 10px;}
p{ margin: 10px 0;}

.list{ margin:  0 0 35px 0;}

.todoFun{ font-size:12px;}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $admin_header;	


?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>
 
<div id="layout">

<?php foreach($toDoList as $key => $item): ?>

<div class="list"  <?= ($key%2==0?"style=' background: #eee; '":'');?> >

 <h4><?= $item['title']; ?> <?= ($item['status']=='1'?'[Archived]':''); ?> </h4>
 
 <p>
 
	<?php /* <a class="archive todoFun" data-id="<?= $item['id']; ?>" data-action="archive" href="javascript: void(0);">Archive</a> */ ?>
 
	<a class="remove todoFun" data-action="remove" data-id="<?= $item['id']; ?>" href="javascript: void(0);">Remove</a>
 
 
 </p>
 
 <div><?= $item['content']; ?></div>

</div>


<?php endforeach; 


if(!$toDoList) echo 'N/A';







?>

</div>

<script type="text/javascript">



var timer  = null; 
var ajaxer  = null; 


	

$( document ).on( "click", ".todoFun", function() {
	

	itemId = $(this).data('id');
	action = $(this).data('action');

	var r = confirm("Please press 'OK' to continue");
	var url = '<?= $canonical; ?>';

	
	if(r){

	
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = {
			action: action,
			itemId: itemId,
		};
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: params,      
				success:function(data){
					
						 location.reload();
					
				}
			});
			
		 },600);	
	
	
	}
	
});	
	
	
	
	


</script>

</body>
</html>