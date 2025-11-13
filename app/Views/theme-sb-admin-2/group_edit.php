<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 class="pageTitle"><?= $pageTitle; ?> <a class="float-right" href="<?= $goBackUrl; ?>">Back</a></h1>	  
			<hr />	

























<?php if($gid): ?>

<div id="function" style="    background: #f3ffb0;    padding: 10px; margin-bottom:40px;">

			<div style=" padding: 10px; " class="stlists stlists">
			<h3>已參加的用戶</h3><button class="selectAll" >Select All</button> <button class="selectInverse" >Invert Selection</button> | <button data-gid="<?= $gid; ?>" class="adduser" >添加</button> <button data-gid="<?= $gid; ?>" data-saction="removeUsers" class="remove sAction" >移除</button>
			
			<?php if($webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>
				
				 | <button data-gid="<?= $gid; ?>" data-saction="addAdmin" class="addAdmin sAction" >設爲Group Leader</button> <button data-saction="removeAdmin" data-gid="<?= $gid; ?>" class="removeAdmin sAction" >取消Group Leader</button>
			
			<?php endif; ?>			
			
 
			
			
			<div class="stuhtml">
			
				
				
				
			
				<?php
				
				

					
					//var_dump($results);
					


					
					foreach($joinedUsers as $user){
						

						if( strpos($user['logs'], 'leader') !== false    ){							
							echo '<div class="leader">';
						}else{
							echo '<div>';
						}
						
						 echo '<input type="checkbox" class="groupMember" id="sendItem'. $user['id'] .'" value="'. $user['id']  .'"> <label for="sendItem'. $user['id']  .'">'. $user['name'] .'</label></div>';
						 
						 
					}
					
					//print_r($available_drivers);
				
				?>
			
			
			</div>
			
			</div>


 <p style=" margin-bottom: 20px; font-size: 14px; ">備注:  <span  style=" color: #ff6900; ">橙紅高亮者為Group Leader</span></p>
 
</div>



	<?php endif; ?>			
			



<form autocomplete="off" method="post" class="rForm" id="editGroupForm">

 

	<p>Campus: 	
		<select title="Campus" class="required form-control" name="campus" id="campus">
		<option value="">---</option>
		<?php
			foreach($sites as $siteCf){
				$select = isset($theGroup['campus'])&&$theGroup['campus']==$siteCf?'selected':'';
				echo '<option  '.$select.' value ="'.$siteCf.'">'.$siteCf.'</option>';		
			}	
		?>
		</select> 
	</p>
	

	<p>
	
	Group Name: <input class="required form-control"  title="Group Name" type="text" value="<?= (isset($theGroup['name'])?$theGroup['name']:''); ?>" name="name" id="name" />	
	
	</p>	
	
	
	<?php if($webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>
	
  <p>Pastor:
  
		<select name="pastor"  title="Pastor" class="required form-control">
		<option value="">---</option>
		
		<?php 
		
			foreach($admins as $pastor){
				
				if($pastor['admin'] < 3) continue;
				
				$select = isset($theGroup['pastor'])&&$theGroup['pastor']==$pastor['bid']?'selected':'';
				
				echo '<option '.$select.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
		
		<span></span>
	</p>	
	
 <?php endif; ?>		





	<div >Group Description: <br /> 
	
	
	
	
<textarea class="form-control" id="description" name="description" rows="10" cols="50"><?= (isset($theGroup['description'])?$theGroup['description']:''); ?></textarea>	
	
	
	
	
	</div>	






 		<div style=" margin: 25px 0; " id="groupTags">
	
	Group Tags: <input  type="text" id="tags" placeholder="Search group tags here" />



		
		<ul id="groupTagsSaved">
		
			<?php 
				foreach($tags as $item){
					$label = $item[$userLg] ? $item[$userLg] : $item['en'];
					echo '<li>'.ucwords($label).' <input  type="hidden" name="tags[]" value="'. $item['id'].'" />  <a href="javascript:void(0);">x Remove</a></li>';
				}
			?>
		
		</ul> 












	
	
	
	
				<p id="resultsMsg"></p>
		
		<ul id="returnTags">
		
		</ul>
	
	
	
	
	
	
	
	
	</div>	
	
 




	
	<p>Publish or not?: 	
		<select name="publish" id="site" title="Publish or not" class="required form-control">
		<option value="">---</option>
		<option  <?= (isset($theGroup['publish'])&&$theGroup['publish']?'selected':''); ?>  value="1">true</option>
		<option  <?= (isset($theGroup['publish'])&&!$theGroup['publish']?'selected':''); ?>  value="0">false</option>

		</select> 
	</p>	
	
	
	<?php if($webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?>

	<div >Zone Pastor note: <br /> 
	
	
	
	
<textarea id="note" class="form-control" name="note" rows="10" cols="50"><?= (isset($theGroup['note'])?$theGroup['note']:''); ?></textarea>	
	
	
	
	
	</div>	

 <?php endif; ?>	
	
	
	<input  type="hidden" name="gid" value="<?= $gid; ?>" />
	<input  type="hidden" name="action" value="groupUpdate" />
	

	
	
	
	
	<p class="bts">
	

	
	
	<input type="submit"  class="btn btn-primary px-5" id="btSubmit" value="Save" />  
	
 		<?php if($gid && $webConfig->checkPermissionByDes(['is_pastor','is_admin'])): ?> <a onclick="delGroup(<?= $gid;?>)" href="#" title="remove">x Remove this group</a>
	<?php endif; ?>		
	
	</p>
	<p class="fmsg"></p>

</form>

 














</div>
</div>
</div>
</div>




 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css' >

<style>

 
#layout{ padding:0 10px;}
p{ margin: 10px 0;}

textarea {
    width: 100%;
}

.ajaxDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000; z-index:9999;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

.ajaxDiv h3{ margin-bottom:10px;}
.ajaxDiv ul{     list-style: none;    padding: 20px 0 0 0;}
.ajaxDiv li{  padding: 5px 0;}
.ajaxDiv li .function{ float:right;}

.stuhtml{    display: flex;
    flex-wrap: wrap;     margin: 15px 0;}

.stuhtml div{min-width:250px; margin-right:20px;}
.stuhtml div.leader label{color: #ff6900; } 

#groupTagsSaved  {padding-left: 20px; margin-top:10px; }
#returnTags{ padding:0; font-size:12px;}
#returnTags li { color: #03a9f4; padding: 3px 0; font-size: 14px;     list-style: none; }
#groupTagsSaved li {     list-style: disc;}

#tags{    border: none;    border-bottom: 1px solid #ccc;}
#tags:focus{ background:#ffc107;}

#returnTags li:before {
    content: '└ ';
}

@media screen and (max-width:375px ) {
	
	
}
</style>




<script type="text/javascript">

var timer  = null; 
var ajaxer  = null; 


var searchWrap;
$( document ).on( "click", ".adduser", function() {
	
	if(typeof(searchWrap) != "undefined" && searchWrap !== null) {
			return;
	}
	
	
	
	var input = document.createElement('input');
	
	var msg = document.createElement('p');
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Search';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	

	
	searchWrap = document.createElement('div');
	searchWrap.id = 'addStudentDiv';
	searchWrap.classList.add("ajaxDiv");
	
	
	searchWrap.appendChild(btC);
	searchWrap.appendChild(input);
	searchWrap.appendChild(btS);
	searchWrap.appendChild(msg);
	searchWrap.appendChild(lists);
	
	
	document.getElementsByTagName("BODY")[0].appendChild(searchWrap);
	
	
	
	btC.addEventListener('click', function(){
		 close();
	});

	btS.addEventListener('click', function(){
		var query = input.value; 
		
				$.ajax({
					dataType:'json',
					method: "POST",
					url: '<?php echo $canonical; ?>',
					data: {	query:query, action:'searchGroupMember'},      
					success:function(data){  
						
						msg.innerHTML = '';
						lists.innerHTML = '';
						
						if(data.length==0){
							
							msg.innerHTML = 'Did not match any documents.';
							
						}else{
							
							
							
							data.forEach(function(item){
								

								 // console.log(item.log);
								
								var p2 = item.log=='joined'?'<span class="function">Joined</span>':'<a href="javascript:void(0);" data-name="'+item.name+'"  data-bid="'+item.id+'" class="join function">+Add</a>';
								var li = document.createElement('li'); 
								li.innerHTML = item.name+' '+p2;
								
								lists.appendChild(li);
							});
							
							
						}	

						
					},error:function(data){ console.log(data);}
				});			
		
	});		
	
	function close(){
		searchWrap.remove();
		searchWrap = undefined;
	}	
});






$( document ).on( "click", ".join", function() {
	

	
	
	var bid = $(this).data('bid');
	
	var me = 	$(this); 
	var name =  $(this).data('name');
	
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: {	 bid: bid, action:'joinGroup'},     
			success:function(data){   
				if(data=='OK'){ 
					$(me).html('Joined');
					$(me).removeClass('join');
					

					
					
					
					var htmlforsendms = '<div><input type="checkbox" class="" id="sendItem'+bid+'" value="'+bid+'"> <label for="sendItem'+bid+'">'+name+'</label></div>';
					$('.stuhtml').prepend(htmlforsendms);
					
					
					
				}				
			}
		});
		

	 
	 
	 
});



$(".rForm").submit(function(event){
	
    event.preventDefault();

	var error='';
	$('.rForm .fmsg').html('Loading...');
	
	error = validater(document.getElementById('editGroupForm'));
	
	if(error){
		$('.rForm .fmsg').html(error);
	}else{
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = $(this).serialize();
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: '<?= $canonical; ?>',
				data: params,      
				success:function(data){
					
						$('.rForm .fmsg').html(data);

					
				}
			});
			
		 },600);
		 
		
		
	}
	
});



 



function delGroup(id){
	var r = confirm("Please press 'OK' to continue");
	var url = '<?= base_url("xAdmin/group/search"); ?>';
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: {	gid: id,action:'delete'},      
				success:function(data){		
					if(data=='OK'){location.href = url;}else{ $('.rForm .fmsg').html(data); }
											
				}
			});
	}


}




$( document ).on( "click", ".sAction", function() {
		var selected = [];

		$('.stuhtml input').each(function() { 
			
		
		if($(this).is(':checked')){
				selected.push($(this).val());
			}
		});
		
		var action = $(this).data('saction');
		
		console.log(action);
		
		if(selected){
			
			ajaxer = $.ajax({
				
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: {	 ids: selected, action:action},     
				success:function(data){  
				console.log(data);
					if(data=='OK'){ 
						
						if(action=='removeUsers'){
							$('.stuhtml input:checked').parent('div').remove();
						}else if(action=='addAdmin'){
							$('.stuhtml input:checked').parent('div').addClass('leader');
						}else if(action=='removeAdmin'||action=='removeGF'){
							$('.stuhtml input:checked').parent('div').removeClass();
						}
						
					}				
				}
			});	
			
		}
				

		

	 
	 
	 
});






$('#tags').bind('input propertychange',  function() {
	
	
		$('#returnTags').html('');	
	

	var query_str = $('#tags').val().trim();	
	
	if(!query_str){ return;}
	
	
	
	if(timer) {clearTimeout(timer); }
	if(ajaxer) {ajaxer.abort(); }
  
	params = {
		query: query_str,  
		action: 'searchTags',
	};
	 
	timer = setTimeout(function() {
		$('#resultsMsg').html('Loading...');
		
		ajaxer = $.ajax({
			dataType:'html',			
			url: "<?= $canonical; ?>", 
			data: params, 
			method: "POST",			
			success:function(data){
				
					$('#returnTags').html(data);
					$('#resultsMsg').html('');
				
			}
		});
		
	 },600);	
	
	
	
	
	
	
});

 



	

$( document ).on( "click", "#returnTags li", function() {
    
	
	var gTagId = $(this).data('tagid');
	var gTagVal = $(this).data('tagval');
	var item =  $(this);
	
	
	var tagsNum = $("#groupTagsSaved li").length;
	
	
	if(tagsNum<3){
	
		if(gTagId){
			newItem = '<li>'+gTagVal+' <input  type="hidden" name="tags[]" value="'+gTagId+'" />  <a href="javascript:void(0);">x Remove</a></li>';
			$('#groupTagsSaved').append(newItem);
		}else{
			
			
			
			
				if(timer) {clearTimeout(timer); }
				if(ajaxer) {ajaxer.abort(); }
			  
				params = {
					value: gTagVal,  
					action: 'newTag',
				};
				 
				timer = setTimeout(function() {
					$('#resultsMsg').html('Loading...');
					
					ajaxer = $.ajax({
						dataType:'html',			
						url: "<?= $canonical; ?>", 
						data: params, 
						method: "POST",			
						success:function(data){
							
								newItem = data;
								$('#resultsMsg').html('');
								$('#groupTagsSaved').append(newItem);
							
						}
					});
					
				 },600);			
			
			
			
		}
		
		 
		 $('#returnTags').html('');
		 $(item).remove();	
		 $('#tags').val('');

	}else{
		
		alert('最多能設置3個標簽');
		
	}	 
		

	
});
	

$( document ).on( "click", "#groupTagsSaved li a", function() {
    
	
	 $(this).parent('li').remove();		
	
		

	
});




$( document ).on( "click", ".selectAll", function() {
	
	var cid = $(this).data('cid');
	
	$('.groupMember').each(function() { 
		
			$(this).prop( "checked", true );
		
		});
	
});

$( document ).on( "click", ".selectInverse", function() {
	
	var cid = $(this).data('cid');
	
	$('.groupMember').each(function() { 
		
			$(this).prop( "checked", !$(this).is(':checked'));
		
		});
	
});



</script>