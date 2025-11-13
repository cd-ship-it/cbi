
		 



<?php 
if(isset($userReport)):  ?>

<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">

<h1 class="pageTitle  "><?= $pageTitle; ?> <a href="<?= $goBackUrl; ?>">Back</a></h1>		
<hr />
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
</div>
</div>
</div>
	

<?php endif; ?>		 



<?php 
if(isset($myReport)):  ?>

<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">

<h2 class="card-title">My Monthly Report</h2>
	<hr />	

<a href="<?= (base_url('report/submit/'.date('Ym',strtotime('last month')))); ?>"><?= (date('Y/m',strtotime('last month'))); ?>  [Please submit before <?= $deadline; ?>]</a>

<h5 class="mt-3">Archived:</h5>	

	<div class="reports row row-cols-md-5">
	

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
		
		echo '<a class="col" href="'.(base_url('report/submit/'.$item['month'])).'">'.$match[1].'/'.$match[2].'</a>';
		
	}else{
		
		echo '<a class="col overdue" href="'.(base_url('report/submit/'.$key)).'">'.$key.' [Overdue]</a>';
	
	}
	
	
	$count++;
	
	
}

if(!$count) echo 'N/A';

?>



	</div>
	
	
	</div>
</div>
</div>
</div>
	

<?php endif; ?>











<?php if(isset($otherReport)&&$otherReport): ?>






 

<?php 

foreach($otherReport as $key => $item){
	
	if($key==0 || $item['author']!=$otherReport[$key-1]['author']){
		echo '<h4 class="mt-5">'.$item['author'].'\'s Monthly Report</h4><div class="reports row row-cols-5 mb-3">';
	}
	
	
	if(!preg_match('#^(\d{4})(\d{2})$#i',$item['month'],$match)){
		continue;
	}
	
	
	echo '<a class="col" href="'.(base_url('report/view/'.$item['id'])).'">'.$item['author'] . ' ' .$match[1].'/'.$match[2];
	
	
	if($item['month'] == date('Ym',strtotime('last month'))){
		echo ' <span style="    color: red;">[NEW]</span>';
	}
	
	echo '</a> ';
	
	
	if(!isset($otherReport[$key+1]) ||  $item['author']!=$otherReport[$key+1]['author']){
	 
		echo '</div> '."\n";
	}
}

?>



<?php endif; ?>















 



	
 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>

<style>

 
#layout{ padding:0 10px;}
p{ margin: 10px 0;}

textarea {    width: 100%;}


form div{ margin-bottom:15px;}

#otherReport a{     text-decoration: none;    padding-right: 20px;}
p.reports { word-break: break-all; margin-top:30px;}
p.reports a{          padding-right: 20px;    display: inline-block;    width: 150px;  }
p.reports a.overdue{   color:red;}

.stuhtml{    display: flex;
    flex-wrap: wrap;     margin: 15px 0;}

.stuhtml div{min-width:150px; margin-right:20px;} 

@media screen and (max-width:375px ) {
	
	
}
</style>




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

