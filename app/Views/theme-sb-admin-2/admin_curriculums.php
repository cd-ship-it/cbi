<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">


<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0"><?= $pageTitle; ?></h3>
    <div>
	
       	 <a class="btn btn-primary mr-2" href="<?= base_url('xAdmin/edit_curriculum'); ?>">Add class</a>
		
		  <a class="btn btn-primary mr-2"  href="<?= base_url('classdata'); ?>">Class Data</a> 
        
	</div>
</div>


		
			<hr />






<?php



	foreach($curriculumCodes as $key => $item){ 
		
		echo '<h4><a href="'.base_url('xAdmin/curriculum/'.$key).'">'.$item[1].'</a></h4>';
		
		
		
		echo '<ul class="classes">';
		
		
		
		
		if(!isset($classes[$key])){
				echo '<li>';
				echo 'curriculum ['.$key.'] not found';
				echo '</li>';
		}else{
				
				
				
			foreach($classes[$key] as $class){
				
				if($class['end']<time()) continue;
				
				
				$url = base_url('xAdmin/curriculum/'.$class['code'].'#title'.$class['id']);
				$title = $class['title'];
				$sessions = json_decode($class['sessions']);
				
				$confirmed =  $class['confirmed'] ? json_decode($class['confirmed']) : [];
				$session_pastors =  isset($class['pastor']) && $class['pastor'] ? json_decode($class['pastor'],true) : [];	
				$needConfirm = [];
				
				foreach($session_pastors as $item){
					
					$needConfirm = array_merge($needConfirm,array_values($item));
					
				}
				
			 
				$aDiff =  array_diff( $needConfirm,$confirmed);  
				
				if($session_pastors && $aDiff!=[]){
					$confirmed_html = '<a style=" margin-top: 15px; display: inline-block; color: RED; " href="'.base_url('xAdmin/edit_curriculum/'.$class['id']).'">[Pastor in Charge not yet confirmed]</a>';
				}else{
					$confirmed_html = '';
				}
				
				
				echo '<li><a href="'.$url.'">'.$title.'</a>';	
				
				if(!$class['is_active']) echo ' <span style="color: #ffffff;background: #ffafaf;padding: 0px 5px;">Cancelled</span>';
				
				echo '<p class="curriculumDate">Date: '.(implode(', ',array_map(function($v){return date("m/d/Y g:i a",$v);},$sessions))).'</p>'.$confirmed_html.'</li>';					

				
			}	
			
				
			
		}
		
		echo '</ul>';			
		
	}

?>







<div class="Remindertemplate" style="
    padding: 20px 20px 60px 20px;
    background: #eee;
">
<div class="wrap">

Reminder Template: Message below will be sent 24 hours before “the first class” of the course automatically.<br /><br />Available shortcode: [classname], [time&date], [classURL]<br/><span id="rMsg" style="
    color: red;
    font-size: 12px;
"></span><br> 
<textarea class="form-control" id="remindertemplate" class="note" name="note" rows="10" cols="100" style="
    width: 100%;
    margin: 5px 0;
"><?= $reminder;?></textarea>
<br><button class="rSAVE btn btn-primary">Save</button>

</div>
</div>





</div>
</div>
</div>
</div>







 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>

<style>


#add_class,h2{padding:15px;}
.classes{padding:5px 15px 25px 15px;}

.classes li {
    padding: 7px 0px;
    list-style: square;
    margin-left: 20px;
}


@media screen and (max-width:375px ) {
	
	
}
</style>


<script type="text/javascript">

$( document ).on( "click", ".rSAVE", function() {
	
		
	var content = $('#remindertemplate').val();
	 
	
		$('#rMsg').html('processing...');
		
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: {	 content: content, action:'reminderSave'},     
			success:function(res){  console.log(res);
				if(res=='OK'){ 
					$('#rMsg').html('Saved');
				}				
			}
		});
		
	
	 
	 
	 
});

</script>
