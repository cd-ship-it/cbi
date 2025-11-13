
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

<h3 style=" margin-bottom:  30px; ">Pastoral Approval (Pre-member):</h3>
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
					
					echo '<p '.$itemClass.'"><a href="'.base_url('xAdmin/baptist/'.$item['id']).'">'.ucwords($item['name']).' ('.$item['site'].') </a> | <a href="'.base_url('xAdmin/pending/form/'.$item['id']).'" class="">Edit Pastoral Approval</a></p>';
					
				}
				

			}
		}
		
		

?>


<h3 style=" margin-top: 20px; ">Admin Approval (Pending):</h3>
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



<div class="newMemberTemplate" style=" margin-top:100px;
    padding: 20px 20px 60px 20px;
    background: #eee;
">
<div class="wrap">
當會員被通過後，系統將會自動寄出以下內容給該會員。<br><br>Available shortcode: [username]<br><span id="rMsg" style="
    color: red;
    font-size: 12px;
"></span><br> 
<textarea id="newMemberTemplate" class="note" name="note" rows="30" cols="100" style="
    width: 100%;
    margin: 5px 0;
">

<?= $newMemberTemplate;?>

</textarea>
<br><button class="rSAVE">Save</button>

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