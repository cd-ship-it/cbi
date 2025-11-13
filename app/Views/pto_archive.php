
<!DOCTYPE html>
<html lang="en-US">
<head>

<!-- COMMON TAGS -->
<meta charset="utf-8">

<title>PTO Archive</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<meta name="viewport" content="width=device-width, initial-scale=1" />

<script type="text/javascript" src="<?= base_url(); ?>/assets/jquery-3.3.1.min.js"></script>
<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css' type='text/css'/>
<link rel="stylesheet" href="<?= base_url(); ?>/assets/layout.css">

<style>


#pageDescription{margin: 0 10px 20px 10px;}
#layout{ padding:0 10px;}
#layout p{ padding:5px 0 ;}

#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

#adminList p { margin: 5px 0;}

p.status1 a{     color: blue;}
p.status-1 a{     color: blueviolet;}
p.status0 a{     color: brown;}
p.status2 a{    color: green;}

tr.highlight{    background: #FFEB3B;}

#pastors { margin-bottom:30px; background:#eee;}
#pastors li{ float:left; width:200px; text-transform: capitalize;}


#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px; }
#results td.count{text-align: center ;}

tr.bg{ background: #d0d0d0; }
tr.selected{background: #fff38a; }

#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	

?>

 

<div id="layout">

 



<div id="awaitingPosts">

<h3>Awaiting Approval: </h3>

<?php

//var_dump($awaitingPosts);

 if($awaitingPosts): ?>

	<?php foreach($awaitingPosts as $post): 
	
	
		$postTitle = ucwords($post['name']) . ' -  ('. date("m/d/Y",$post['start']) .  ($post['end']&&$post['start']!==$post['end']? '-'.date("m/d/Y",$post['end']) : '')  . ') - ' . $post['types'].' - Submitted  on '. date("m/d/Y",$post['submit_date']) ;
	
	?>

		<p><a href="<?= base_url('pto/'.$post['id']); ?>"><?= $postTitle; ?></a></p>
			
	
	<?php endforeach; ?>

<?php else: //awaitingPosts ?>
  <p>暫無提交待您處理</p>
<?php endif; //awaitingPosts ?>


</div>



<?php if(isset($notAvailable)): 



function findMissingWeekend($start_timestamp,$end_timestamp){


  // Count the number of Saturdays and Sundays between the two dates
  $num_saturdays = 0;
  $num_sundays = 0;
  $current_timestamp = $start_timestamp;
  $missingWeekends = array();
  $dformat = "D M d";
  while ($current_timestamp <= $end_timestamp) {
    $current_day = date('w', $current_timestamp);
    if ($current_day == 6) { // Saturday
      $num_saturdays++;
      $found_date = date($dformat, $current_timestamp);
     # print $found_date;
      
      array_push($missingWeekends, $found_date);
      
      #print "$found_date";
    } elseif ($current_day == 0) { // Sunday
      $num_sundays++;
      $found_date = date($dformat, $current_timestamp);
     # print $found_date;
      array_push($missingWeekends, $found_date);
   }
    $current_timestamp += 86400; // Add one day in seconds
  }
  return implode(", ",$missingWeekends);

}


 #  print $sql."\n";
 $current_header="xxx";
 print '<table class="sortable" id="results" style=" margin-top: 40px; ">';
 print "<tr class='title'><th>Types</th><th>Start Date</th><th>End Date</th><th>Who</th><th>will NOT be available in these weekends</th></tr>";


    foreach($notAvailable as $row) {
      
 
      
      $start_date = date('D M d, Y', $row["start"]);
      $end_date = date('D M d, Y', $row["end"]);
      $current_month = date('M Y',$row["start"]);

      if ($current_header <> $current_month){
        print "<tr class='bg'>";
        print "<td colspan=5>";
        print "<b>".$current_month."</b>";
        print "</td></tr>";
        $current_header = $current_month;

      }

      print "<tr>";
      print "<td>";print $row["types"];print "</td>";

      print "<td>";print $start_date;print "</td>";
      print "<td>";print $end_date;print "</td>";
      print "<td>";print $row["name"];print "</td>";
      print "<td>";
      print findMissingWeekend($row["start"],$row["end"]);print "</td>";
      print "</tr>";
      
    }
    print "</table>";
  






















 endif; //isset($notAvailable)?>



<?php if($uid != $webConfig->ptoSpecialUser): ?>










<?php

//var_dump($awaitingPosts);

 if($archivedPosts): ?> 

<div style=" padding-top: 40px; " id="archivedPosts">


 
<h3>
Search:  

<select id="select_person">
	
<option value="">Select a person</option>

<?php foreach($pastors as $thePastor): ?>

<option value="p<?= $thePastor['bid']; ?>"><?= $thePastor['name']; ?></option>

<?php endforeach; ?>

</select>



<select id="select_status">
	
<option value="">Select post status</option>

<option value="status1">Awaiting Approval</option>

<option value="status2">Approved</option>

<option value="status0">Disapproved</option>

<option value="status-1">Cancelled</option>




</select>



</h3>
 

	<?php 
	
	$table_data= [];
	
	foreach($archivedPosts as $post): 
	
			if($post['status']==1){
				$theStatus = 'Awaiting Approval';
			}elseif($post['status']==0){
				$theStatus = 'Disapproved';
			}elseif($post['status']==-1){
				$theStatus = 'Cancelled';
			}else{
				$theStatus = 'Approved';
			}
	
		$postTitle = ucwords($post['name']) . ' -  ('. date("m/d/Y",$post['start']) .  ($post['end']&&$post['start']!==$post['end']? '-'.date("m/d/Y",$post['end']) : '')  . ') - '.$post['types'].' - Submitted  on '. date("m/d/Y",$post['submit_date'])  . ' ['.$theStatus.']' ;
		
		
		$table_data[$post['bid']]['name'] = ucwords($post['name']);
		$table_data[$post['bid']]['status'.$post['status']][] = $post['id'];
		
		
		
	?>

		<p style="display:none;" class="pto_post <?= ('status'.$post['status']); ?> <?= ('p'.$post['bid']); ?>"><a target="_blank" href="<?= base_url('pto/'.$post['id']); ?>"><?= $postTitle; ?></a></p>
			
	
	<?php endforeach; ?>
	
	
	
	
<?php /*?>	
<table class="sortable" id="results"> 

	<thead>
		<tr class="title">
			<th class="">Name</th>
			<th class="">Awaiting</th>
			<th class="">Approved</th>
			<th class="">Disapproved</th>
			<th class="">Cancelled</th>
		</tr> 
	</thead>
	
	<tbody>

	<?php 
	
	

		$line = 0;
	
		foreach($table_data as $key => $row): 
		
		
		
		$class = '';
		if($line%2 != 0 ) $class .=" bg";
		
		$line++;

	?>

	 
		<tr class="<?= ($class); ?>">
			<td class=""><?= (isset($row['name'])?$row['name']:'N/A'); ?></td>
			<td data-pid="p<?= ($key); ?>" data-status="status1" class="count"><a href="<?= (base_url('pto/archive/'.$key.'/1')); ?>"><?= (isset($row['status1'])?count($row['status1']):0); ?></a></td>
			<td data-pid="p<?= ($key); ?>" data-status="status2" class="count"><a href="<?= (base_url('pto/archive/'.$key.'/2')); ?>"><?= (isset($row['status2'])?count($row['status2']):0); ?></a></td>
			<td data-pid="p<?= ($key); ?>" data-status="status0" class="count"><a href="<?= (base_url('pto/archive/'.$key.'/0')); ?>"><?= (isset($row['status0'])?count($row['status0']):0); ?></a></td>
			<td data-pid="p<?= ($key); ?>" data-status="status-1" class="count"><a href="<?= (base_url('pto/archive/'.$key.'/-1')); ?>"><?= (isset($row['status-1'])?count($row['status-1']):0); ?></a></td>
		</tr>
			
	
	<?php endforeach; ?> 
	
	</tbody>
</table>
	
	
	
	
	<?php */ ?>	
	
	
	
</div>









<?php 









endif; //archivedPosts ?>
<?php endif; ?>





<?php if($webConfig->checkPermissionByDes('management')||$webConfig->checkPermissionByDes('is_office_director')||in_array($uid,$operationsDirector) || in_array($uid,$seniorPastor)): ?>
 
<h3 style=" margin-top: 30px; margin-bottom: 10px; ">Options: 	<select id="options_toggle">	
	<option value="0">Hide</option>
	<option value="1">Show</option>
	</select></h3>
 
 <div style="    display: none;" id="options_wrap">
 
	
<?php if($webConfig->checkPermissionByDes('management')||$webConfig->checkPermissionByDes('is_office_director')): ?>

<div id="options"   style="margin-bottom: 30px;    background: #eee;    padding: 20px;    color: #03a9f4;"  >
PTO balance maximum limit: <input value="<?= $maximum_limit; ?>" name="maximum_limit" type="number" id="maximum_limit" class="" /> <span style="    color: red;" id="maximum_limit_msg"></span><br />
When PTO balance reaches or exceeds [<span id="maximum_limit_val"><?= $maximum_limit; ?></span>] days, it will not be automatically incremented.
</div>

<?php  endif; ?>


 



<?php if($uid != $webConfig->ptoSpecialUser): 




	if( in_array($uid,$operationsDirector) || in_array($uid,$seniorPastor) || $webConfig->checkPermissionOnly(8) ):



?>



<div id="pastors">
<h3>Pastors / employees: </h3>
<ul style=" padding: 10px; " class="clearfix">

<?php foreach($pastors as $thePastor):

	


 ?>

<li><a href="<?= base_url('xAdmin/baptist/'.$thePastor['bid'].'#pto'); ?>"><?= $thePastor['name']; ?></a></li>

<?php endforeach; ?>

</ul>


</div>
<?php endif; endif; ?>

 </div>

<?php endif; //options_wrap?>






</div>



<script type="text/javascript">
var timer  = null; 
var ajaxer  = null; 

 
	
 $( document ).on( "change", "#options_toggle", function() {
	 
	 
	 $( "#options_wrap" ).toggle();
	 
	 
 });
 
	
 $( document ).on( "change", "#select_person,#select_status", function() {
	 
	 

	 
	select_person_val = $('#select_person').val() ? $('#select_person').val() : 'pto_post';
	select_status_val = $('#select_status').val()  ? $('#select_status').val() : 'pto_post';
	
	$( "#archivedPosts p" ).hide();
	
	count = 0;
	
	$( "#archivedPosts p" ).each(function( index ) {
		
		if($( this ).hasClass( select_person_val ) && $( this ).hasClass( select_status_val )){
			$( this ).show();
			count++;
		}
	  

	  
	});
	
	
		if(count == 0){
			$( "#archivedPosts" ).append('<p>N/A</p>');
		}
	 
	
	// console.log(select_person_val);
	// console.log(select_status_val);
	
 
	
	 
	 
 });
	
	
$('#maximum_limit').bind('input',function(){ 	
	
	val = $(this).val();
	$( "#maximum_limit_msg" ).html('Loading...');
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		

		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: '<?= $canonical; ?>',
				data:  {action: 'maximum_limit_submit', maximum_limit_val: val},      
				success:function(data){
					
							$( "#maximum_limit_msg" ).html(data);	 
							$( "#maximum_limit_val" ).html(val);	 
						
						}				
		
			});
			
		 },500);
	
	
	
});










</script>

</body>
</html>