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




<p>Week: <?= date("m/d/Y",$displayingWeek['start']); ?> to <?= date("m/d/Y",$displayingWeek['end']); ?></p>





<p id="weekList" <?= ($week?'class="week'.$week.'"':''); ?> >
<?php


 foreach($newestPrayers as $key => $item){
	 
	 $weekNum = $key +1 ;
	 
	 if($item==$activeWeek){
		 
		 echo '<a class="activeWeek" href="'.base_url('xAdmin/prayer_items').'" >'.date('m/d',$item['start']).' - '.date('m/d',$item['end']).' (Tier '.$item['tier'].')</a>';
	 
	 }else{
		 
		  echo '<a class="week'.$weekNum.'" href="'.base_url('xAdmin/prayer_items/'.$weekNum).'" >'.date('m/d',$item['start']).' - '.date('m/d',$item['end']).' (Tier '.$item['tier'].')</a>';
		 
	 }

	 
 }

 ?>
 
 </p>





<ul id="prayerItems">


<?php 


foreach($prayers as $key => $prayer_id): 

$date =  strtotime(  '+'.$key.' day' , $displayingWeek['start'] );  


?>

	<li <?= ($key%2==0?"style=' background: #eee; '":'');?>>
		<p>
			<p><?= date('D m/d ',$date); ?></p>
			
			<select class="selectPrayer" name="tierUser[]" data-day="<?= $key; ?>"  data-tier="<?= $displayingWeek['tier']; ?>" data-week="<?= $displayingWeek['id']; ?>">
			<option value="0">---</option>
			
			<?php 
			
				foreach($admins as $pastor){
					
					if($prayer_id){
						$is_selected =  ($prayer_id == $pastor['id'] ?'selected':'') ;					
					}else{
						$is_selected =  false ;
					}
					
					echo '<option  '.$is_selected.'  value="'.$pastor['id'].'">'.$pastor['user_name'].'</option>';
				}
			
			?>
			
			
			
			
			</select>
			
			<span class="ajmsg"></span>
			
	
		</p>
		
		
			<h3>
			
			Prayer Items
			
			 
				<a <?= (!$prayer_id?'style="display:none;"':''); ?> class="singleSend itemFun" href="<?= (base_url('xAdmin/prayer_items/notification/'.$displayingWeek['id'].'/'.$key)); ?>">Send notification</a>
			 
				<a <?= (!$prayer_id?'style="display:none;"':''); ?> class="editItem itemFun" href="<?= (base_url('xAdmin/edit_prayer_items/'.$displayingWeek['id'].'/'.$key.'?b='.$canonical)); ?>">Edit item</a>
			 
			
			</h3>
			
			<div class="itemContentWrap">English: <div class="itemContent"><?= ($prayerItems[$key]?$prayerItems[$key]['en']:'N/A'); ?></div></div>
			<div class="itemContentWrap">T Chinese: <div class="itemContent"><?= ($prayerItems[$key]?$prayerItems[$key]['zh_hant']:'N/A'); ?></div></div>
			<div class="itemContentWrap">S Chinese: <div class="itemContent"><?= ($prayerItems[$key]?$prayerItems[$key]['zh_hans']:'N/A'); ?></div></div>
		
		
	</li>
	
<?php endforeach; ?>	

</ul>


<div id="options">

<form action="<?= $canonical; ?>" method="post" class="rForm myAjForm" id="prayerForm" onkeydown="if(event.keyCode==13){return false;}">




<fieldset>
    <legend> Week <?= date("m/d/Y",$displayingWeek['start']); ?> to <?= date("m/d/Y",$displayingWeek['end']); ?> 選項設置 </legend>

<p>日期: <input <?= ($displayingWeek['start']<time()?'disabled':''); ?> autocomplete=off value="<?= (date("m/d/Y",$displayingWeek['start'])); ?>"  name="start-date" title="Start Date" id="start-date" class="dateInput required" /> - <input <?= ($displayingWeek['start']<time()?'disabled':''); ?> autocomplete=off value="<?= (date("m/d/Y",$displayingWeek['end'])); ?>" title="End Date"  name="end-date" id="end-date" class="dateInput required" /></p>


<p>

生效Tier: 
<select class="selectTier" name="selectTier" id="selectTier">
			<option <?= ($displayingWeek['tier']==1?'selected':''); ?> value="1">1</option>
			<option <?= ($displayingWeek['tier']==2?'selected':''); ?> value="2">2</option>
			<option <?= ($displayingWeek['tier']==3?'selected':''); ?> value="3">3</option>
			<option <?= ($displayingWeek['tier']==4?'selected':''); ?> value="4">4</option>
</select>

</p>



<p>

還沒有輸入禱文的,提前多少天提醒該牧者:
<select class="selectRemind" name="selectRemind1" id="selectRemind1">


			<?php 
						
					echo '<option '.($displayingWeek['remind1']==0?'selected':'').' value="0">---</option>';
					for ($i=1; $i<=7; $i++)
					{
						echo '<option '.($displayingWeek['remind1']==$i?'selected':'').' value="'.$i.'">'.$i.'天</option>';
					}
			
			?>

</select>
<br />


提前多少天二次提醒該牧者:
<select class="selectRemind" name="selectRemind2" id="selectRemind2">


			<?php 
					
					echo '<option '.($displayingWeek['remind2']==0?'selected':'').' value="0">---</option>';
					for ($i=1; $i<=7; $i++)
					{
						echo '<option '.($displayingWeek['remind2']==$i?'selected':'').' value="'.$i.'">'.$i.'天</option>';
					}
			
			?>

</select>
<br />



最後提醒牧者和Worship Pastor:
<select >
<option  value="0">提前一天</option>

		

</select>
<br />







</p>

</fieldset>

<br />

<p>當前周的禱文内容: <a target="_blank" href="<?= (base_url('xAdmin/prayer_items/output')); ?>"><?= (base_url('xAdmin/prayer_items/output')); ?></a></p>

<div class="prayerNeedHelp">

需要協助翻譯的牧者: <br />




 
			
			<?php 
			
				foreach($admins as $pastor){	
					
					 
					
					echo '<span> <input name="prayerNeedHelp[]"  id="p'.$pastor['id'].'" type="checkbox" '.(in_array($pastor['id'],$prayerNeedHelp)?'checked':'').' value="'.$pastor['id'].'"> <label for="p'.$pastor['id'].'">'.$pastor['user_name'].'</label></span>';
					
				}
			
			?>
			
			
			
			
			 

</div>

<p>
幫助翻譯的翻譯員: (*當[需要協助翻譯的牧者]提交了禱文後，翻譯員將會收到電郵通知。) <br />
翻譯員:  <input value="<?= (implode(', ',$prayerProvideHelp)); ?>" name="prayerProvideHelp" id="prayerProvideHelp" class="" />



</p>





	<p class="bts">
	
	<input type="hidden"  name="action" value="prayerSave" />
	<input type="hidden"  name="prayer_id" value="<?= $displayingWeek['id']; ?>" />
	
	<input type="submit"  id="btSubmit" value="Save" />  
	
	</p>
	
	
	<p class="fmsg"></p> 
</form>	


</div>


</div>
</div>
</div>
</div>






 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>




<script type="text/javascript">



var timer  = null; 
var ajaxer  = null; 

$("#prayerForm").submit(function(event){
    event.preventDefault();

	 
	$('.fmsg').html('Loading...');
	

		
		
		var params = $(this).serialize();
		
		
			
			$.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: params,      
				success:function(data){
					
						
						$('#prayerForm .fmsg').html(data);
						location.reload();

					
				}
			});
			
		
		 
		
		
	
	
});
 

$( document ).on( "change", ".selectPrayer", function() {
	
	tierUsers = [];
	targetTier = $(this).data('tier');
	targetWeekId = $(this).data('week');
	targetDay = $(this).data('day');
	
	ajmsg =  $(this).next('.ajmsg');
	parent = $(this).closest('li');
	mValue =  $(this).val();
	
	$(ajmsg).html('Loading...');
	
	
	$('.selectPrayer').each(function(){
		tierUsers.push( $(this).val());
	});
	
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = {
			action: 'prayerChange',
			tierUsers: tierUsers,
			targetWeekId: targetWeekId,
			targetDay: targetDay,
			targetTier: targetTier
		};
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: '<?= $canonical; ?>',
				data: params,      
				success:function(data){
					
						$(ajmsg).html(data);						
						$(parent).find('.itemContent').html('N/A');
						
						if(mValue != 0) {
							$(parent).find('.itemFun').show();
						}else{
							$(parent).find('.itemFun').hide();
						}
					
				}
			});
			
		 },600);	
	
	
	
	
});
 
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
