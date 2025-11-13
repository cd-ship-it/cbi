
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

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>


<div id="adminList" style=" margin: 0 10px 20px 10px; ">


<?php

		echo 'Administrator(s): <a id="addAdmin" href="javascript: void(0);">+ Add</a>';
		
		if(!$admins){
			echo '<p id="noUsers">No users were found</p>';

		}else{
			foreach($admins as $item){ 
				
				$optionsArr = $webConfig->config_permission;  
				unset($optionsArr[$item['admin']]); 
				$optionsHtml = '<select class="permission" data-uid="'.$item['id'].'"><option selected value="'.$item['admin'].'">'.$webConfig->config_permission[$item['admin']]['slug'].'</option>';
				
				foreach($optionsArr as $oprion){
					$optionsHtml .= '<option value="'.$oprion['code'].'">'.$oprion['slug'].'</option>';
				}
				
				$optionsHtml .= '</select>';
								
				
				echo '<p>'.$item['name'].' | Permission: '.$optionsHtml.' | <a href="javascript:void(0);" class="removeAdmin" data-uid="'.$item['id'].'">- remove</a> <span></span></p>';
			}
		}
		
		

?>


</div>



<div id="spUsers" style=" margin: 0 10px 20px 10px; margin: 0 10px 20px 10px; border-top: dashed 1px #ccc; padding-top: 20px; line-height: 24px;">
 
  <p>Senior Pastor:
  
		<select name="senior_pastor" title="Senior Pastor" data-mkey="is_senior_pastor" class="sp_user_update" id="senior_pastor">
		<option value="">---</option>
		
		<?php 
		
			foreach($admins as $pastor){
				
				if($pastor['admin'] < 3) continue;
				
				$is_selected =    $pastor['bid']==$senior_pastor   ?'selected':'' ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
		
		<span></span>
	</p>  
	
  <p>Office Director:
  
		<select name="office_director" title="Office Director" data-mkey="is_office_director" class="sp_user_update" id="office_director">
		<option value="">---</option>
		
		<?php 
		
			foreach($admins as $pastor){
				
				if($pastor['admin'] < 3) continue;
				
				$is_selected =  ($office_director == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
		
		<span></span>
	</p> 
	
  <p>Office Assistant:
  
		<select name="office_assistant" title="Office Assistant" data-mkey="is_office_assistant" class="sp_user_update" id="office_assistant">
		<option value="">---</option>
		
		<?php 
		
			foreach($admins as $pastor){
				
				if($pastor['admin'] < 3) continue;
				
				$is_selected =  ($office_assistant == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>	
			<span></span>
	</p>	
		
  <p>Worship Pastor:
  
		<select name="worship_pastor" title="Worship Pastor" data-mkey="is_worship_pastor" class="sp_user_update" id="worship_pastor">
		<option value="">---</option>
		
		<?php 
		
			foreach($admins as $pastor){
				
				if($pastor['admin'] < 3) continue;
				
				$is_selected =  ($worship_pastor == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		
		?>
		
		
		
		
		</select>
		
		<span></span>
	</p>

</div>

<?php /*
<div  style=" margin: 0 10px 20px 10px; margin: 0 10px 20px 10px; border-top: dashed 1px #ccc; padding-top: 20px; line-height: 24px;">

	Zone pastor:
	
	<br />
	
	
<?php

	$zones = ['milpitas'=>'Milpitas','peninsula'=>'Peninsula','pleasanton'=>'Pleasanton','sanleandro'=>'San Leandro','tracy'=>'Tracy','ezone'=>'E-Zone'];

	foreach($zones as $key => $zone){
		
		 
		
		echo '<p>'.$zone.': <select data-mkey="'.$key.'_pastor" name="'.$key.'_pastor" class="sp_user_update" id="'.$key.'_pastor"><option value="">---</option>';
		
			foreach($admins as $pastor){
				
				if($pastor['admin'] < 3) continue;
				
				$is_selected =  ($zonePastors[$key] == $pastor['bid'] ?'selected':'') ;
				echo '<option  '.$is_selected.'  value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}

		echo '</select> <span></span></p>';
		
	}

?>	
	
	
 
	

</div>

<?php Zone pastor*****/  ?>


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
								var p2 = item.admin!=0?'<span class="function">Joined</span>':'<a href="javascript:void(0);" data-name="'+item.name+'" data-uid="'+item.id+'" class="join function">+Add</a>';
								var li = document.createElement('li'); 
								li.innerHTML = item.name+' '+p2;
								
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


$( document ).on( "click", ".join", function() {
	

	

	var uid = $(this).data('uid');
	var me = 	$(this); 
	var name =  $(this).data('name');
	
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo  $canonical; ?>',
			data: { name:name,uid: uid, action:'join'},     
			success:function(data){   
				if(data!='error'){ 
					$(me).html('Joined');
					$(me).removeClass('join');
					
					
					
					//var html = '<p>'+name+' | Permission: <select name="permission" class="permission" data-uid="'+uid+'"><option  value="1">Admin</option> <option value="8">Super admin</option></select> | <a href="javascript:void(0);" class="removeAdmin" data-uid="'+uid+'">- remove</a> <span></span></p>';
					
					$('#adminList').append(data);
					
					$('#noUsers').remove();
				}				
			}
		});
		

	 
	 
	 
});



$( document ).on( "change", "select.permission", function() {
	

	

	var uid = $(this).data('uid');
	var permission =  $(this).val();
	var span =  $(this).siblings("span")[0];
	
	$(span).html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { uid: uid, permission: permission, action:'permissionChange'},     
			success:function(data){   
				if(data=='OK'){ 
					$(span).html('Saved');
				}				
			}
		});
		

	 
	 
	 
});

$( document ).on( "change", "select.sp_user_update", function() {
	

	
	var bid =  $(this).val();
	var mkey =  $(this).data('mkey');
	var span =  $(this).siblings("span")[0];
	
	$(span).html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: {  bid: bid, meta_key: mkey, action:'sp_user_update'},     
			success:function(data){   
				if(data=='OK'){ 
					$(span).html('Saved');
				}else{
					console.log(data);
				}				
			}
		});
		

	 
	 
	 
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