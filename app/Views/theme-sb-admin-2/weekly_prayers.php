<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 class="pageTitle"><?= $pageTitle; ?></h1>
			<hr />	

<style>

 
#layout{ padding:0 10px;}
p{ margin: 10px 0;}
#weekList { margin: 30px 0;}
#weekList a{ padding: 5px 25px;  background: #eee; color: #ababab; text-decoration: none; border-radius: 5px;     margin: 0 20px 10px 0;    display: inline-block; }
#weekList a.activeWeek{   background: #03a9f4; color: #fff; }

.itemContentWrap {margin-top: 15px;}

.week1 .week1, .week2 .week2, .week3 .week3, .week4 .week4{ background: #ffeb3b !important; color: #979797 !important; }
 
#prayerItems { list-style: none; padding: 0 ; margin: 0;} 
#prayerItems li{  padding: 10px ; margin-bottom: 30px ;} 

#options{ background: #f5ff94; padding: 15px;}
#btSubmit { padding: 8px 40px; background: #00bcd4; border: none; color: #fff;     cursor: pointer;}

.selectPrayer{     margin-right: 30px;}

.itemFun{ font-size: 14px; float: right; margin-right:20px;}

.prayerNeedHelp span{ padding-right:50px;    width: 200px;    display: inline-block;}

@media screen and (max-width:375px ) {
	
	
}
</style>



 

 




<ul id="prayerItems">


<?php 

$prayerCount = 0;

foreach($prayers as $key => $prayer_id): 

if($prayer_id!=$logged_id) continue;

$prayerCount++;

$date =  strtotime(  '+'.$key.' day' , $activeWeek['start'] );  


?>

	<li <?= ($prayerCount%2==0?"style=' background: #eee; '":'');?>>
	
			<h3><?= date('D m/d ',$date); ?> <a <?= (!$prayer_id?'style="display:none;"':''); ?> class="editItem itemFun" href="<?= (base_url('xAdmin/edit_prayer_items/'.$activeWeek['id'].'/'.$key.'?b='.$canonical)); ?>">Edit item</a></h3>
			

			

		
		
 
			
			<div class="itemContentWrap">English: <div class="itemContent"><?= ($prayerItems[$key]?$prayerItems[$key]['en']:'N/A'); ?></div></div>
			<div class="itemContentWrap">T Chinese: <div class="itemContent"><?= ($prayerItems[$key]?$prayerItems[$key]['zh_hant']:'N/A'); ?></div></div>
			<div class="itemContentWrap">S Chinese: <div class="itemContent"><?= ($prayerItems[$key]?$prayerItems[$key]['zh_hans']:'N/A'); ?></div></div>
		
		
	</li>
	
<?php endforeach; ?>	

</ul>

<?= ($prayerCount?'':' You do not have any Weekly Prayer items to write this week.');?>



</div>
</div>
</div>
</div>




