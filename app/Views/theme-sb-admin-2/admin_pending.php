<div class="row">
	<div class="col-md-12 stretch-card my-3">
		<div class="card shadow mb-4">
			<div class="card-body">
				<h3 class="card-title">Pastoral Approval (Pre-member):</h3>
				<hr />

<?php

		
		
		if(!$pLists[0]){
			echo '<p id="noUsers">No users were found</p>';

		}else{
			
			$pListsTmp = [];
			
			foreach($pLists[0] as $item){ 
				
				$pListsTmp[$item['site']][] = 	$item;		
				
				
			}
			
			foreach($pListsTmp as $pListsGroup){
				
				foreach($pListsGroup as $key => $item){
					
					$itemClass = ($key+1==count($pListsGroup)) ? 'style=" margin-bottom:  30px; "' : '';
					
					echo '<p data-zpid="'.$item['zPastor'].'" '.$itemClass.'><a style="'.($pid&&$pid==$item['zPastor']?'color: #ff9800;':'').'" href="'.base_url('xAdmin/baptist/'.$item['id']).'">'.ucwords($item['name']).' ('.$item['site'].') </a> | <a style="'.($pid&&$pid==$item['zPastor']?'color: #ff9800;':'').'" href="'.base_url('xAdmin/pending/form/'.$item['id']).'" class="">Edit Pastoral Approval</a></p>';
					
				}
				

			}
		}
		
		

?>					


			</div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-12 stretch-card my-3">
		<div class="card shadow mb-4">
			<div class="card-body">
				<h3 class="card-title">Admin Approval (Pending):</h3>
				<hr />

<?php

		
		
		if(!$pLists[1]){
			echo '<p id="noUsers">No users were found</p>';

		}else{
			foreach($pLists[1] as $item){ 
				
							
				
				echo '<p><a href="'.base_url('xAdmin/baptist/'.$item['id']).'">'.ucwords($item['name']).' ('.$item['site'].') </a> | <a href="'.base_url('xAdmin/pending/form/'.$item['id']).'" class="">Edit Admin Approval</a></p>';
			}
		}
		
		

?>				


			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12 stretch-card my-3">
		<div class="card shadow mb-4">
			<div class="card-body" style="    background: #eee;">


<div class="newMemberTemplate" >
<div class="wrap">
Once a member is approved, the system will automatically send the following content to that member.<br><br>Available shortcode: [username]<br><br> 
<textarea id="newMemberTemplate" class="note" name="note" rows="30" cols="100" style="
    width: 100%;
    margin: 5px 0;
">

<?= $newMemberTemplate;?>

</textarea>
<br><button class="rSAVE">Save</button> 
<span id="rMsg" style="
    color: red;
    font-size: 12px;
"></span>
</div>
</div>

			</div>
		</div>
	</div>
</div>






<script type="text/javascript">

$( document ).on( "click", ".rSAVE", function() {
	
		
	var content = $('#newMemberTemplate').val();
	 
	
		$('#rMsg').html('processing...');
		
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: {	 content: content, action:'newMemberTemplateSave'},     
			success:function(res){ // console.log(res); 
				if(res=='OK'){ 
					$('#rMsg').html('Saved');
				}				
			}
		});
		
	
	 
	 
	 
});

</script>

