<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 ><?= $pageTitle; ?></h1>			  
			<hr />	





<div class="sesstion" id="s-curriculum">




<form action="<?= $canonical; ?>" method="post" class="rForm" id="curriculumForm">



 
<nav>
  <div class="nav nav-tabs mb-0" id="title-tab" role="tablist">
    <button class="nav-link active" id="title-nav-hant" data-toggle="tab" data-target="#title-tab-hant" type="button" role="tab" aria-controls="title-tab-hant" aria-selected="true">Title(繁體)</button>
    <button class="nav-link" id="title-nav-hans" data-toggle="tab" data-target="#title-tab-hans" type="button" role="tab" aria-controls="title-tab-hans" aria-selected="false">Title(簡體)</button>
    <button class="nav-link" id="title-nav-en" data-toggle="tab" data-target="#title-tab-en" type="button" role="tab" aria-controls="title-tab-en" aria-selected="false">Title(En)</button>
  </div>
</nav>

<div class="tab-content border-bottom border-left border-right p-3 mt-0" id="nav-tabContent">
	<div class="tab-pane fade show active" id="title-tab-hant" role="tabpanel" aria-labelledby="title-nav-hant">
		繁: <input class="required form-control"  title="curriculum title" type="text" value="<?= (isset($class_d['title'])?$class_d['title']:''); ?>" name="title" id="title" /> 
	</div>
	<div class="tab-pane fade" id="title-tab-hans" role="tabpanel" aria-labelledby="title-nav-hans">
		简: <input  class="form-control" title="curriculum title" type="text" value="<?= (isset($class_d['title-zh-Hans'])?$class_d['title-zh-Hans']:''); ?>" name="title-zh-Hans" id="title-zh-Hans" />
	</div>
	<div class="tab-pane fade" id="title-tab-en" role="tabpanel" aria-labelledby="title-nav-en">
		EN: <input   class="form-control" title="curriculum title" type="text" value="<?= (isset($class_d['title-en'])?$class_d['title-en']:''); ?>" name="title-en" id="title-en" /> 
	</div>
</div>




 
	
	
	<p>
	
	Zoom URL: <input class="form-control"   title="Zoom URL" type="text" value="<?= (isset($class_d['zoom_url'])?$class_d['zoom_url']:''); ?>" name="zoom_url" id="zoom_url" /> <br />
	Zoom Password: <input class="form-control" title="Zoom Password" type="text" value="<?= (isset($class_d['zoom_password'])?$class_d['zoom_password']:''); ?>" name="zoom_password" id="zoom_password" />
	
	
	
	</p>


	
	<p>
	Category:	
	<select name="code" class="required form-control" title="Category" id="code">
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
	 <input class="form-control" value="<?= (isset($class_d['scode'])?$class_d['scode']:''); ?>" name="scode" id="scode" /> <a onclick="generateCode()" href="javascript:void(0);">Generate</a>
	</p>
	
	<p>
	How many sessions are needed:	
	<select class="required form-control" title="Sessions" name="scount" id="scount">
	<option value ="">---</option>
	<?php
		for($i=1;$i<13;$i++){
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
			echo 'Session '.($key+1).': <input data-index="'.($key).'" id="sessionInput'.($key).'" value="'.$item.'" name="sessions[]" title="Session '.($key+1).'" class="form-control dateInput sessions required" />';
			
			
			echo ' <a href="javascript: void(0);" class="setPastor" data-target="sessionInput'.($key).'">Assign Pastor</a>';
			
			
				foreach($pastorsObs as $pastor){
					

					
					if(isset($session_pastors[$session_time]) && is_array($session_pastors[$session_time]) && in_array($pastor['bid'],$session_pastors[$session_time])){
						
						echo '<small class="newAdd"><br />';
						echo '└ Pastor '. $pastor['name'];
						echo in_array($pastor['bid'],$confirmed)?'  (Confirmed)  ':' (Unconfirmed) ';
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
	
	<p>Class material: <input class="form-control"   type="text" value="<?= (isset($class_d['material'])?$class_d['material']:''); ?>" name="material" id="material" /></p>






<nav>
  <div class="nav nav-tabs mb-0" id="note-tab" role="tablist">
    <button class="nav-link active" id="note-nav-hant" data-toggle="tab" data-target="#note-tab-hant" type="button" role="tab" aria-controls="note-tab-hant" aria-selected="true">Note(繁體)</button>
    <button class="nav-link" id="note-nav-hans" data-toggle="tab" data-target="#note-tab-hans" type="button" role="tab" aria-controls="note-tab-hans" aria-selected="false">Note(簡體)</button>
    <button class="nav-link" id="note-nav-en" data-toggle="tab" data-target="#note-tab-en" type="button" role="tab" aria-controls="note-tab-en" aria-selected="false">Note(En)</button>
  </div>
</nav>

<div class="tab-content border-bottom border-left border-right p-3 mt-0" id="nav-tabContent">
	<div class="tab-pane fade show active" id="note-tab-hant" role="tabpanel" aria-labelledby="note-nav-hant">
		繁: <textarea class="form-control" id="note" name="note" rows="10" cols="50"><?= (isset($class_d['note'])?$class_d['note']:''); ?></textarea>
	</div>
	<div class="tab-pane fade" id="note-tab-hans" role="tabpanel" aria-labelledby="note-nav-hans">
		简: <textarea class="form-control" id="note-zh-Hans" name="note-zh-Hans" rows="10" cols="50"><?= (isset($class_d['note-zh-Hans'])?$class_d['note-zh-Hans']:''); ?></textarea>	
	</div>
	<div class="tab-pane fade" id="note-tab-en" role="tabpanel" aria-labelledby="note-nav-en">
		EN: <textarea class="form-control" id="note-en" name="note-en" rows="10" cols="50"><?= (isset($class_d['note-en'])?$class_d['note-en']:''); ?></textarea>
	</div>
</div>




 	
	

<nav>
  <div class="nav nav-tabs mb-0" id="classinfo-tab" role="tablist">
    <button class="nav-link active" id="classinfo-nav-hant" data-toggle="tab" data-target="#classinfo-tab-hant" type="button" role="tab" aria-controls="classinfo-tab-hant" aria-selected="true">Online meeting information(繁體)</button>
    <button class="nav-link" id="classinfo-nav-hans" data-toggle="tab" data-target="#classinfo-tab-hans" type="button" role="tab" aria-controls="classinfo-tab-hans" aria-selected="false">Online meeting information(簡體)</button>
    <button class="nav-link" id="classinfo-nav-en" data-toggle="tab" data-target="#classinfo-tab-en" type="button" role="tab" aria-controls="classinfo-tab-en" aria-selected="false">Online meeting information(En)</button>
  </div>
</nav>

<div class="tab-content border-bottom border-left border-right p-3 mt-0" id="nav-tabContent">
	<div class="tab-pane fade show active" id="classinfo-tab-hant" role="tabpanel" aria-labelledby="classinfo-nav-hant">
		繁: <textarea class="form-control" id="classinfo" name="classinfo" rows="40" cols="50"><?= (isset($class_d['classinfo'])?$class_d['classinfo']:''); ?></textarea>	
	</div>
	<div class="tab-pane fade" id="classinfo-tab-hans" role="tabpanel" aria-labelledby="classinfo-nav-hans">
		简: <textarea class="form-control" id="classinfo-zh-Hans" name="classinfo-zh-Hans" rows="40" cols="50"><?= (isset($class_d['classinfo-zh-Hans'])?$class_d['classinfo-zh-Hans']:''); ?></textarea>	
	</div>
	<div class="tab-pane fade" id="classinfo-tab-en" role="tabpanel" aria-labelledby="classinfo-nav-en">
		EN: <textarea class="form-control" id="classinfo-en" name="classinfo-en" rows="40" cols="50"><?= (isset($class_d['classinfo-en'])?$class_d['classinfo-en']:''); ?></textarea>
	</div>
</div>	
	

 		


		
	
	
	
	
	<div>Registration message:<br /> <textarea class="form-control" id="classmessage" name="classmessage" rows="20" cols="50"><?= (isset($class_d['classmessage'])?$class_d['classmessage']:''); ?></textarea></div>		
	
	
	
	
	
	
	
	
	
	

	
	
	<p>
	Status:	
		<select name="is_active" class="form-control" id="is_active">
			<option <?= isset($class_d)&&$class_d['is_active']?'selected':''; ?> value="1">Published</option>
			<option <?= isset($class_d)&&$class_d['is_active']==0?'selected':''; ?> value="0">Cancelled</option>
		</select>
	</p>	
	
	
	
	
	
	
	<input  type="hidden" name="id" value="<?= $cid; ?>" />
	

	
	
	
	
	<p class="bts">
	
	<?php if($cid && $webConfig->checkPermissionByDes('edit_class')): ?><input class="btn btn-secondary " type="button" id="del" onclick="delUser('<?= $cid; ?>')"  value="del" />  | <?php endif; ?>
	<input  type="hidden" name="action" value="insert" />
	<input class="btn btn-primary px-5" type="submit" id="btSubmit" value="<?= $fsubmitLable; ?>" />
	
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




</div>
</div>
</div>
</div>








	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>

<style>
#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;z-index:9999;      overflow-y: scroll;}
input,input:focus{ border: 1px #444 solid;}
#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 5px   0;}
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
	msg.innerHTML = '<h3>Assign Pastor('+title+')</h3>Loading...';
	
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
							
							msg.innerHTML = '<h3>Assign Pastor('+title+')</h3>';
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
		sNode2.innerHTML = ' <a href="javascript: void(0);" class="setPastor" data-target="sessionInput'+(i-1)+'">Assign Pastor</a>';
		
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








</script>


