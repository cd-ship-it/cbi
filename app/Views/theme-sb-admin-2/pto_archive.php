<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
 

<h1 >Unavailable Schedule</h1>			<hr />



 
 <?php if(isset($notAvailable)&&$notAvailable): 

echo '<div class="row my-3">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">';

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
 print '<table class="sortable" id="results" >';
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
  

















echo '</div></div></div></div>';




 endif; //isset($notAvailable)?>




</div>
</div>
</div>
</div>


















<?php  if(isset($archivedPosts)&&$archivedPosts): ?> 
 
 
 <div class="row my-3">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
 

 

<div   id="archivedPosts">


 
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
	
	
	

	
	
	
</div>
</div>
</div>
</div>
</div>









<?php 









endif; //archivedPosts ?>




 


 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>

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
