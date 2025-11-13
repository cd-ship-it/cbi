
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


#pageDescription{margin: 0 10px 20px 10px;}
#layout{ padding:0 10px;}


#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}

tr.bg{ background: #fafafa; }
tr.selected{background: #fff38a; }

#adminList p { margin: 5px 0;}

tr.highlight{    background: #FFEB3B;}

#userOtherPosts p {margin: 5px 0;}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>

<div id="layout">


<form style=" margin: 30px 0; " action="<?= $canonical; ?>" method="post" class="rForm myAjForm" id="ptoForm" onkeydown="if(event.keyCode==13){return false;}">



	
	<p>Start Date: <input autocomplete=off value="<?= $startDate; ?>"  name="startDate" title="Start Date" id="start-date" class="dateInput required" /></p>
	
	<p>End Date: <input autocomplete=off value="<?= $endDate; ?>" name="endDate"  title="End Date"  id="end-date" class="dateInput required" /></p>
	
	

	
	

	
	<p class="bts">
	
	<input type="hidden"  name="action" value="search" />
	
	<input type="submit"  id="btSubmit" value="Search" />  
	
	</p>
	
	 

</form>





<?php

//var_dump($classData);

if($classData): ?>
 
<div id="userOtherPosts">

 


<table class="sortable" id="results"> 

	<thead>
		<tr class="title">
			<th class="">系列</th>
			<th class="">課程</th>
			<th class="">實際出席</th>
			<th class="">應當出席</th>
			<th class="">出席率</th>
		</tr> 
	</thead>
	
	<tbody>

	<?php 
	
		$signinTotal = $sbPresentTotal = 0;
	
		foreach($classData as $post): 

		$title = $post['title'] . ' ('.date("m/d/Y",  $post['start']).'-'.date("m/d/Y",  $post['end']).')';
		$sessions =  json_decode($post['sessions']);

		foreach($sessions as $k => $v){
			if($v>time()) unset($sessions[$k]);
		}
		
		$should_be_present = $post['joined'] * count($sessions);
		
		
		if(!$should_be_present) continue;
		
		$post['signin'] = $post['signin'] ? $post['signin'] : 0;
		
		$percent = ceil($post['signin']/$should_be_present*100)."％" ;
		
		$signinTotal = $signinTotal + $post['signin'];
		
		$sbPresentTotal = $sbPresentTotal + $should_be_present;
		
		//base_url('xAdmin/curriculum/'.$post['code'].'#title'.$post['id'])
	?>

	 
		<tr class="">
			<td class=""><?= $post['code']; ?></td>
			<td class=""><?= $title ; ?></td>
			<td class=""><?= $post['signin']; ?></td>
			<td class=""><?= $should_be_present; ?></td>
			<td class=""><?= $percent; ?></td>
		</tr>
			
	
	<?php endforeach; ?> 
	
	</tbody>
</table>
	
<p style="    text-align: right;" >統計: <?= $signinTotal ; ?> / <?= $sbPresentTotal ; ?> / <?= (ceil($signinTotal/$sbPresentTotal*100)."％") ; ?></p>	
	
</div>



<?php elseif(isset($_POST)):  ?>


	<p>No results found</p>


<?php endif; //userOtherPosts ?>



</div>



<script type="text/javascript">



var startDate = document.getElementById('start-date');
var endDate = document.getElementById('end-date');

rome(startDate, {
  time:false,
  inputFormat: "MM/DD/YYYY",
  dateValidator: rome.val.beforeEq(endDate) 
});


rome(endDate, {
  time:false,
  inputFormat: "MM/DD/YYYY",
  dateValidator: rome.val.afterEq(startDate) 
});


 




</script>

</body>
</html>