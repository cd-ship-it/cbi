
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


#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

#adminList p { margin: 5px 0;}

tr.highlight{    background: #FFEB3B;}

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

 


<div id="adminList" style=" margin: 0 10px 20px 10px; ">

 


		<select  class="sp_user" id="sp_user">
		<option value="">---</option>
		
		<?php 
		
			foreach($admins as $user){
				 
				
				$is_selected =  ($uid == $user['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$user['bid'].'">'.$user['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select> | <a id="addAdmin" href="javascript: void(0);">+ Search User</a>
		
		
		
		<?php if($uid): ?>
		
			<h1 style=" margin: 10px 0; "><?= $uname; ?>'s Capabilities: <span class="msg"></span></h1>
			
			
			
			<div style="    background: #f3ffb0;    padding: 10px; margin-bottom:40px;" class="stuhtml">
			
				
				
				
			
				<?php
					
					foreach($capabilitiesOps as $capability){
						

						$is_selected =  (isset($ucaps[$capability]) ?'checked':'') ;
						
						 echo '<div><input '.$is_selected.' type="checkbox" class="capability" id="'. $capability .'" data-uid="'.$uid.'" value="1"  data-capability="'. $capability  .'"> <label for="'. $capability  .'">'. $capability .'</label></div>';
						 
						 
					}
					
					//print_r($available_drivers);
				
				?>
			
			
			</div>		
		
		<?php endif; ?>

</div>








<script type="text/javascript">

var searchWrap;
$( document ).on( "click", "#addAdmin", function() {
	
	if(typeof(searchWrap) != "undefined" && searchWrap !== null) {
			return;
	}
	
		
	var input = document.createElement('input');
	
	var msg = document.createElement('p');
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Search User';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	
	
	searchWrap = document.createElement('div');
	searchWrap.id = 'addAdminDiv';
	
	
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
					url: '<?php echo  $canonical; ?>',
					data: {	query:query, action:'searchUser'},      
					success:function(data){ 
						
						msg.innerHTML = '';
						lists.innerHTML = '';
						
						if(data.length==0){
							msg.innerHTML = 'Did not match any documents.';
						}else{
							
							
							data.forEach(function(item){
								 
								var li = document.createElement('li'); 
								var theUrl =  '<?= base_url("xAdmin/management/capabilities"); ?>/'+item.bid;
								li.innerHTML = item.name+' <a href="'+theUrl+'" data-name="'+item.name+'" data-uid="'+item.id+'" class="">Set capabilities</a>';
								
								lists.appendChild(li);
							});
							
							
						}	

						
					}
				});			
		
	});		
	
	function close(){
		searchWrap.remove();
		searchWrap = undefined;
	}	
});


 



$( document ).on( "change", "input.capability", function() {
	

	

	var uid = $(this).data('uid');
	var capability =  $(this).data('capability');
	var val =  $(this).prop("checked")?1:0;
	
	
	$('.msg').html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { uid: uid, val: val, capability: capability, action:'capabilityChange'},     
			success:function(data){   
				$('.msg').html('');	console.log(data);				
			}
		});
		

	 
	 
	 
});

$( document ).on( "change", "select.sp_user", function() {
	

	
	var bid =  $(this).val();
	var url =  '<?= base_url("xAdmin/management/capabilities"); ?>/'+bid;
	
	location.href = url;
		

	 
	 
	 
});

$( document ).on( "click", ".removeAdmin", function() {
	
	var r = confirm("Please press 'OK' to continue");
	var url = '<?php echo  $canonical; ?>';	
	
	var uid = $(this).data('uid');	
	var me = $(this).parents('p')[0];
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: { uid: uid, action:'remove'},      
				success:function(data){		
					if(data=='OK'){ 
						$(me).remove();
					}											
				}
			});
	}	
});





</script>

</body>
</html>