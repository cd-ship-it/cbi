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
#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px   0;}
#addAdminDiv li{  padding: 1px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

#uploaddesignbtn {
    background: #fff;
    padding: 10px 20px;
    border: 2px dashed #ccc;
    margin-bottom: 10px;
	cursor: pointer;
}

#curriculumForm div, #curriculumForm p{ margin:10px 0;}

#sessions small{color:#ff9800;}

textarea{width:100%;}

.bts {
    padding: 10px 0;
}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;	

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>

<div class="sesstion" id="s-curriculum">

<?php

/*
<span class="language" data-tra="title"> (<a data-lang="zh-Hant" href="javascript:void(0)">繁</a> / <a data-lang="zh-Hans" href="javascript:void(0)">简</a> / <a data-lang="en" href="javascript:void(0)">英</a>)</span>
*/


?>


<form action="<?= $canonical; ?>" method="post" class="rForm" id="curriculumForm">



	<p>
	
	Title: <br />
	繁: <input style=" width: 500px; " class="required"  title="curriculum title" type="text" value="<?= (isset($class_d['title'])?$class_d['title']:''); ?>" name="title" id="title" /> <br />
	简: <input style=" width: 500px; "  title="curriculum title" type="text" value="<?= (isset($class_d['title-zh-Hans'])?$class_d['title-zh-Hans']:''); ?>" name="title-zh-Hans" id="title-zh-Hans" /> <br />
	En: <input style=" width: 500px; "  title="curriculum title" type="text" value="<?= (isset($class_d['title-en'])?$class_d['title-en']:''); ?>" name="title-en" id="title-en" /> 
	
	
	
	</p>
	
	
	<p>
	
	Zoom URL: <input style=" width: 500px; " title="Zoom URL" type="text" value="<?= (isset($class_d['zoom_url'])?$class_d['zoom_url']:''); ?>" name="zoom_url" id="zoom_url" /> <br />
	Zoom Password: <input title="Zoom Password" type="text" value="<?= (isset($class_d['zoom_password'])?$class_d['zoom_password']:''); ?>" name="zoom_password" id="zoom_password" />
	
	
	
	</p>


	
	<p>
	Category:	
	<select name="code" class="required" title="Category" id="code">
	<option value ="">---</option>
	<?php
		foreach($webConfig->curriculumCodes[$userLg] as $key => $item){
			echo '<option  '.(isset($class_d['code'])&&$item[0]==$class_d['code']?'selected':'').' value =\''.json_encode($webConfig->curriculumCodes[$userLg][$key]).'\'>'.$item[1].'</option>';		
		}	
	?>
	</select>
	</p>

	<p>
	Verification Code:	
	 <input value="<?= (isset($class_d['scode'])?$class_d['scode']:''); ?>" name="scode" id="scode" /> <a onclick="generateCode()" href="javascript:void(0);">Generate</a>
	</p>
	
	<p>
	How many sessions are needed:	
	<select class="required" title="Sessions" name="scount" id="scount">
	<option value ="">---</option>
	<?php
		for($i=1;$i<11;$i++){
			echo '<option '.(isset($class_d['scount'])&&$class_d['scount']==$i?'selected':'').' value ="'.$i.'">'.$i.'</option>';		
		}	
	?>
	</select>
	</p>


	<div id="sessions">
	
	<?php
		
	
		foreach($sessions as $key => $item){
			
			
			$session_time = strtotime($item);
			
			echo '<p>';
			echo 'Session '.($key+1).': <input data-index="'.($key).'" id="sessionInput'.($key).'" value="'.$item.'" name="sessions[]" title="Session '.($key+1).'" class="dateInput sessions required" />';
			
			
			echo ' <a href="javascript: void(0);" class="setPastor" data-target="sessionInput'.($key).'">設置主講牧師</a>';
			
			
				foreach($pastorsObs as $pastor){
					

					
					if(isset($session_pastors[$session_time]) && is_array($session_pastors[$session_time]) && in_array($pastor['bid'],$session_pastors[$session_time])){
						
						echo '<small class="newAdd"><br />';
						echo '└ Pastor '. $pastor['name'];
						echo in_array($pastor['bid'],$confirmed)?'  (已確認)  ':' (未確認) ';
						echo '<a href="'.(base_url('xAdmin/edit_curriculum/notification/'.$cid.'/'.$pastor['bid'].'/'.$session_time)).'">Send notification</a>';
						echo '<input name="pastor[]" value="'. ($key) .'||'. $pastor['bid'] .'" type="hidden" />';
						echo '</small>';			
						
					}else{
						
						
						
					}
					

					
					

				}
			
			
			
			echo '</p>';
		}	
		
		
	
	?>
	</div>
	
	<p>Class material: <input style=" width: 500px; "  type="text" value="<?= (isset($class_d['material'])?$class_d['material']:''); ?>" name="material" id="material" /></p>

	<div id="noteDiv">Note:<br /> 
	
	
	
	
	繁:<br />
	<textarea id="note" name="note" rows="10" cols="50"><?= (isset($class_d['note'])?$class_d['note']:''); ?></textarea>	
	
	简:<br />
	<textarea id="note-zh-Hans" name="note-zh-Hans" rows="10" cols="50"><?= (isset($class_d['note-zh-Hans'])?$class_d['note-zh-Hans']:''); ?></textarea>	
	
	En:<br />
	<textarea id="note-en" name="note-en" rows="10" cols="50"><?= (isset($class_d['note-en'])?$class_d['note-en']:''); ?></textarea>
	
	
	
	
	
	</div>		
	
	
	

	<div>Online meeting information:<br /> 
	
	繁:<br />
	<textarea id="classinfo" name="classinfo" rows="40" cols="50"><?= (isset($class_d['classinfo'])?$class_d['classinfo']:''); ?></textarea>	
	
	简:<br />
	<textarea id="classinfo-zh-Hans" name="classinfo-zh-Hans" rows="40" cols="50"><?= (isset($class_d['classinfo-zh-Hans'])?$class_d['classinfo-zh-Hans']:''); ?></textarea>	
	
	En:<br />
	<textarea id="classinfo-en" name="classinfo-en" rows="40" cols="50"><?= (isset($class_d['classinfo-en'])?$class_d['classinfo-en']:''); ?></textarea>
	
	
	</div>			


		
	
	
	
	
	<div>Registration message:<br /> <textarea id="classmessage" name="classmessage" rows="20" cols="50"><?= (isset($class_d['classmessage'])?$class_d['classmessage']:''); ?></textarea></div>		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<input  type="hidden" name="id" value="<?= $cid; ?>" />
	

	
	
	
	
	<p class="bts">
	
	<?php if($cid && $webConfig->checkPermissionByDes('edit_class')): ?><input type="button" id="del" onclick="delUser('<?= $cid; ?>')"  value="del" />  | <?php endif; ?>
	<input  type="hidden" name="action" value="insert" />
	<input type="submit" id="btSubmit" value="<?= $fsubmitLable; ?>" />
	
	</p>
	<p class="fmsg"></p>

</form>

</div>


<?php if($cid): ?>

<hr />

<div class="sesstion" id="s-public">

Public URL:<br />
<a target="_blank" href="<?= $publicUrl; ?>"><?= $publicUrl; ?></a> 

</div>
<?php endif; ?>

<script type="text/javascript">

curriculumStar  = new Date();

var timer  = null; 
var ajaxer  = null; 

var searchWrap;
$( document ).on( "click", ".setPastor", function() {
	
	if(typeof(searchWrap) != "undefined" && searchWrap !== null) {
			 close();
	}
	
	var element = $(this);
	var targetElementId = $(this).data('target');
	var targetElement =  document.getElementById(targetElementId);
	var sIndex =  targetElement.getAttribute('data-index');
	
	var stime = $(targetElement).val();  
	var title = $(targetElement).attr('title');  
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Update';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	
	var msg = document.createElement('p');
	msg.innerHTML = '<h3>設置主講牧師('+title+')</h3>Loading...';
	
	searchWrap = document.createElement('div');
	searchWrap.id = 'addAdminDiv';	
	

	


 
	

	
	
	searchWrap.appendChild(btC);
	searchWrap.appendChild(msg);
	searchWrap.appendChild(lists);
	
	searchWrap.appendChild(btS);
	
	
	
	
	
	document.getElementsByTagName("BODY")[0].appendChild(searchWrap);
	
	
	
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		$.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: {	  stime:stime, action:'searchPastor'},
				success:function(data){
							
							msg.innerHTML = '<h3>設置主講牧師('+title+')</h3>';
							$(lists).html(data);
					
							
				}
			});	
	
	
	
	
	
	btC.addEventListener('click', function(){
		 close();
	});
	
	
	btS.addEventListener('click', function(){
		
		$(element).siblings('.newAdd').remove();
		
		$(lists).find('input').each(function(index,item){
			
			if($(this).prop('checked')){
				
				
				 
				
				
				$(element).after('<small class="newAdd"><br />└ Pastor '+ $(this).data('name') +'<input name="pastor[]" value="'+ sIndex +'||'+ $(this).data('bid') +'" type="hidden" /></small>'); 
				
			}
			
		})
		
		
		
	});


	
	
	function close(){
		searchWrap.remove();
		searchWrap = undefined;
	}	
});


$( "#code" ).change(function() {
	//changeTitle(); 
});

$( "#scount" ).change(function() {
	var count = Number($(this).val());
	$('#sessions').html('');
	
	
	for(var i=1; i<=count; i++){
		
		var inputNode = document.createElement('input');
		 

		var today  = new Date();
		var nextDay = new Date(today);
		nextDay.setDate(today.getDate() + i);
		
		inputNode.value = (nextDay.getMonth()+1)+'/'+nextDay.getDate()+'/'+nextDay.getFullYear()+' 8:00 pm';	
		
		inputNode.setAttribute ('data-index',i-1);
		inputNode.name = 'sessions[]';
		inputNode.title = 'Session '+i;
		inputNode.id = 'sessionInput'+(i-1);
		inputNode.setAttribute('class','required sessions sessionInput'+i);
		
		rome(inputNode, {
					inputFormat: "MM/DD/YYYY h:mm a",
					timeFormat: 'h:mm a'
				});	
				
		var pNode =  document.createElement('p');
		
		
		var sNode1 =  document.createElement('span');
		sNode1.innerHTML = 'Session '+i+': ';
		
		pNode.appendChild(sNode1);
		 
		pNode.appendChild(inputNode);
		
		var sNode2 =  document.createElement('span');
		sNode2.innerHTML = ' <a href="javascript: void(0);" class="setPastor" data-target="sessionInput'+(i-1)+'">設置主講牧師</a>';
		
		pNode.appendChild(sNode2); 

		document.getElementById("sessions").appendChild(pNode);
	}
		
});



function changeTitle(){
	
	var codeValue = $( '#code' ).val();
	
	if(!codeValue){ return;}
	
	var curriculumObj = JSON.parse(codeValue);	
	var curriculumTitle = curriculumObj[1]+' '+(curriculumStar.getMonth()+1)+'/'+curriculumStar.getDate()+'/'+curriculumStar.getFullYear();
	var curriculumDes = curriculumObj[2];
	
	$( "#title" ).val(curriculumTitle);
	$( "#des" ).html(curriculumDes);	
	
}




$('.dateInput').each(function(index) {	
var e = document.getElementById($(this).attr('id'));
  rome(e, {
	  inputFormat: "MM/DD/YYYY h:mm a",
	  timeFormat: 'h:mm a'
	});
});



$("#curriculumForm").submit(function(event){
    event.preventDefault();
	
	var error='';
	$('#curriculumForm .fmsg').html('Loading...');
	
	$('#curriculumForm').find('.required').each(function(index) {
		if($(this).val().trim()==''){
			error += 'The "' + $(this).attr('title')+ '" field is required.<br/>';
		}
	});


	
	if($('#material').val().trim() && !validURL($('#material').val().trim())){
		error += 'Please enter a valid url for class material.<br/>';
	}
	
	
	if(error==''){
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = $(this).serialize();
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: '<?php echo $canonical; ?>',
				data: params,      
				success:function(data){
					
						$('#curriculumForm .fmsg').html(data);

					
				}
			});
			
		 },600);
		 
		
		
	}else{
		$('#curriculumForm .fmsg').html(error);
	}
	
});

function delUser(id){
	var r = confirm("Please press 'OK' to continue");
	var url = '<?php echo $canonical; ?>';
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: {	id: id,action:'delete'},      
				success:function(data){		
					if(data=='OK'){location.href = url;}else{ $('#curriculumForm .fmsg').html(data); }
											
				}
			});
	}


}


function generateCode(){
	var rm =  randomWord(false,4);
	$('#scode').val(rm);
	
}

function randomWord(randomFlag, min, max){
    var str = "",
        range = min,
        arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
 
    if(randomFlag){
        range = Math.round(Math.random() * (max-min)) + min;
    }
    for(var i=0; i<range; i++){
        pos = Math.round(Math.random() * (arr.length-1));
        str += arr[pos];
    }
    return str.toUpperCase();
}






function validURL(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  return !!pattern.test(str);
}

</script>

</body>
</html>