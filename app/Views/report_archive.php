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

textarea {    width: 100%;}
h1.pageTitle{ margin: 0 10px 20px 0;}
h2{ margin:40px 0 10px 0;}
h3{ margin:30px 0 10px 0;}
form div{ margin-bottom:15px;}

#otherReport a{     text-decoration: none;    padding-right: 20px;}
p.reports { word-break: break-all;}
p.reports a{          padding-right: 20px;    display: inline-block;    width: 130px;  }
p.reports a.overdue{   color:red;}

.stuhtml{    display: flex;
    flex-wrap: wrap;     margin: 15px 0;}

.stuhtml div{min-width:150px; margin-right:20px;} 

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	   

?>


 
<div id="layout">


<?php 
if(isset($userReport)):  ?>
<h2 class="pageTitle  "><?= $pageTitle; ?> <a href="<?= $goBackUrl; ?>">Back</a></h2>		
<div style=" padding: 10px; background: #eee; " id="otherReport">

 
	<p class="reports">
	
	
<?php 

$count = 0;

foreach($userReport as $key => $item){
	
	
	

	


 
		
		if(!preg_match('#^(\d{4})(\d{2})$#i',$item['month'],$match)){
			continue;
		}		
		
		echo '<a href="'.(base_url('report/view/'.$item['id'])).'">'.$match[1].'/'.$match[2].'</a>';
		


	
	
	$count++;
	
	
}

if(!$count) echo 'N/A';

?>



	</p>
	
	
	</div>
	

<?php endif; ?>		
 



<?php 

if(isset($myReport)):  ?>


<h2>My Monthly Report</h2>


<a href="<?= (base_url('report/submit/'.date('Ym',strtotime('last month')))); ?>"><?= (date('Y/m',strtotime('last month'))); ?>  [Please submit before <?= $deadline; ?>]</a>

	<p class="reports">
	Archived:<br />	

<?php 

$count = 0;

foreach($myReport as $key => $item){
	
	
	

	
	if($item){
		
		if($item['month'] == date('Ym',strtotime('last month'))){
			continue;
		}
		
		if(!preg_match('#^(\d{4})(\d{2})$#i',$item['month'],$match)){
			continue;
		}		
		
		echo '<a href="'.(base_url('report/submit/'.$item['month'])).'">'.$match[1].'/'.$match[2].'</a>';
		
	}else{
		
		echo '<a class="overdue" href="'.(base_url('report/submit/'.$key)).'">'.$key.' [Overdue]</a>';
	
	}
	
	
	$count++;
	
	
}

if(!$count) echo 'N/A';

?>



	</p>
	
	
	
	

<?php endif; ?>











<?php if(isset($otherReport)&&$otherReport): ?>




<div style=" padding: 10px; background: #eee; " id="otherReport">

<?php 

foreach($otherReport as $key => $item){
	
	if($key==0 || $item['author']!=$otherReport[$key-1]['author']){
		echo '<h2>'.$item['author'].'\'s Monthly Report</h2>';
	}
	
	
	if(!preg_match('#^(\d{4})(\d{2})$#i',$item['month'],$match)){
		continue;
	}
	
	
	echo '<a href="'.(base_url('report/view/'.$item['id'])).'">'.$item['author'] . ' ' .$match[1].'/'.$match[2];
	
	
	if($item['month'] == date('Ym',strtotime('last month'))){
		echo ' <span style="    color: red;">[NEW]</span>';
	}
	
	echo '</a> ';
}

?>

</div>

<?php endif; ?>








</div>




<script type="text/javascript">

var timer  = null; 
var ajaxer  = null; 


$( document ).on( "click", "#setting", function() {
	 $( "#settingDiv" ).toggle( "slow" );
});


$( document ).on( "change", "input.pastor", function() {
	

	

	var uid = $(this).val();
	var val =  $(this).prop("checked")?1:0;
	
	
	$('.msg').html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { uid: uid, val: val, action:'setting'},     
			success:function(data){   
				$('.msg').html('');	console.log(data);				
			}
		});
		

	 
	 
	 
});



</script>

</body>
</html>