
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

.filesUpload{display:none;}

  h3{  margin-bottom: 20px;}
  h3 span{ float:right;    font-size: 16px;
}

.msgTitle,.classMsgLogs, .classFunction{padding:10px;}
.classMsgLogs, .classFunction{display:none;}
textarea.message{width:90%;}
.classFunction{background: #fff7b5;}

.msgTitle{ background:#cddc39;  cursor: pointer; margin-top:30px;}
.msgTitle span{ float:right;}

.filesPreview a{ font-size: 12px;
    line-height: 21px;
    color: #000;}
	
.filesPreview a.removeFile{ color:blue;}

.uploadfilesbtn{padding:10px 0;}
.supported{ font-size:12px; color:#ccc;}
.preUploadError,.smsError{ font-size:12px; color:red;}

.filesPreview{margin:20px 0;}

.stuhtml{    display: flex;
    flex-wrap: wrap;     margin: 15px 0;}

.stuhtml div{min-width:150px; margin-right:20px;}

.messageSend{     padding: 4px 50px;
    cursor: pointer;}
	
	
.slog{    margin: 25px 15px;
    border-left: 3px solid #ccc;
    padding-left: 15px;}
	
	
.slog h4	{    margin-top: 20px;}
.slog .msgcon	{  background:#eee;padding: 5px; }
	

.curriculumInfo{margin: 50px 10px 20px 10px;     position: relative; border-top: 1px solid #ccc; padding-top: 50px;}

.tdLogs div{ text-align:center;}

.tdLogs .sbt{ display:block; width:30px; margin:0 auto;} 
.tdLogs .sbt:hover{background: #8BC34A;}

.tdLogs .presence:before {
   content: " \2714 ";
   font-size:20px;
}

 .tdLogs .absence:before {
   content: " x ";
   color:#ccc;
} 

.curriculumDate{margin:10px 0;}
.curriculumInfo .note{ width: 500px; max-width: 100%; height: 150px; padding-bottom: 30px;}
.curriculumInfo .noteSAVE{ position: absolute; bottom: 0; padding: 5px 30px;}

.curriculumTb{width:100%;}
.curriculumTbTitle td{     font-weight: bold;    border-bottom: 1px solid #000;     background: #d1d1d1;}
.curriculumTb td	{ padding: 5px;}
.tdFunction {text-align: right;}


.logsWrap {width:100%;}
.logsWrap div{ float:left;}

.sbt,button{    cursor: pointer;}
.sbt:hover{text-decoration: underline;}

.ajaxDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

.ajaxDiv h3{ margin-bottom:10px;}
.ajaxDiv ul{     list-style: none;    padding: 20px 0 0 0;}
.ajaxDiv li{  padding: 5px 0;}
.ajaxDiv li .function{ float:right;}

tr.highlight{    background: #FFEB3B;}

.progressbar{width:100%;max-width:400px;background:#eee;}
.progressbar span{display:block; background:#8BC34A; text-align:center;}

@media screen and (max-width:375px ) {
	
	
}
</style>

</head>

<body>

<?php

echo $header;

?>

<h1 class="pageTitle"><?= $pageTitle; ?></h1>
<p id="pageDescription"><?= $pageDescription; ?></p>




<?php

	foreach($curriculums as $curriculum){

		// var_dump($curriculum); exit;
		
		$sessions = json_decode( $curriculum['sessions']);
		$students = $curriculum['students'];
		$msgLogs = $curriculum['msgLogs'];
		$isElearningClass = stripos($curriculum['title'],'e-learning') === false ? false : true;
		
		
		
		echo '<div class="curriculumDiv">';
		
		$dateS = date("m/d/Y",$curriculum['start']);
		$dateE = date("m/d/Y",$curriculum['end']);
		
		echo '<div class="curriculumInfo"><h3><a data-lesson="'.$curriculum['sessions'].'" id="title'.$curriculum['id'].'" href="'.(!$isElearningClass?base_url('xAdmin/edit_curriculum/'.$curriculum['id']):'javascript: void(0);').'">'.$curriculum['title'].' | Edit Class</a><span>(參加人數: '.count($students).')</span></h3>';
		
		if(!$isElearningClass) echo '<p class="curriculumDate">Date: '.(implode(', ',array_map(function($v){return date("m/d/Y g:i a",$v);},$sessions))).'</p>';
		
		//echo 'Note:<span id="noteMsg'.$curriculum['id'].'"></span><br /> <textarea id="note'.$curriculum['id'].'" class="note" name="note" rows="4" cols="50">'.$curriculum['note'].'</textarea><br /><button data-note="'.$curriculum['id'].'" class="noteSAVE">Save</button>';
		
		echo '</div>';
		
		
		
		
		
		echo 'Student List: <a data-cid="'.$curriculum['id'].'" class="addStudent" href="javascript: void(0);">+ Add</a>';
		
		if(!$students){
			echo '<p>No Students were found</p>';

		}			
			
		displayStudents($curriculum['id'],$students,$sessions,$isElearningClass);	
		
		echo '<h3 data-cid="'.$curriculum['id'].'" class="msgTitle">Message <span>[ + ]</span></h3>';
		
		//if(($curriculum['end']-3600*24*2)>time()) 
		displayFunction($curriculum['id'],$students,$curriculum['title']);	
		
		displayMsgLogs($curriculum['id'],$msgLogs);		
		
		echo '</div>';
		
	}

?>














<script type="text/javascript">

if(window.location.hash){
	$(window.location.hash).addClass('highlight');
}

// complete:function(data){ console.log(data);}

var timer  = null; 
var ajaxer  = null; 

$( document ).on( "click", ".sbt", function() {
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 		
	
	var cid = $(this).parents('table')[0].dataset.cid;
	var bid = $(this).parents('tr')[0].dataset.bid;
	var date = $(this).data('date');
	var eml = $(this);
	var ccid = '#c'+cid+'c'+bid;

		
	if($(this).hasClass('presence')){
	
			lable = 'absence';	
		
	}else{
		
			lable = 'presence';			
	
	}
	
	params = {
		date: date,
		bid: bid,
		cid: cid,
		action: lable
	};		
	
	
	timer = setTimeout(function() {
		
		
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: params,      
			success:function(data){  
				
				var n = Number(data);
				if (!isNaN(n))
				{
					
					$(eml).removeClass('absence presence');
					$(eml).addClass(lable);
					$(ccid).html(n+'%');
					
				}else{
					$(eml).html('Error');
				}	

				
			}
		});
		
	 },300);		
	
	
	
});


$( document ).on( "click", ".msgTitle", function() {
	var cid = $( this ).data('cid');
	
		
	if($( this ).html().indexOf('+') != -1){
		
			$( '.msgTitle' ).html('Message <span>[ + ]</span>');	
			$( '.classFunction' ).hide();
			$( '.classMsgLogs' ).hide();
		
			$( this ).html('Message <span>[ - ]</span>');
			$( '#cf'+cid ).show();
			$( '#msgLogs'+cid ).show();		
		
	}else{
		
			$( '.msgTitle' ).html('Message <span>[ + ]</span>');	
			$( '.classFunction' ).hide();
			$( '.classMsgLogs' ).hide();
				
		
	}
	
	

	
	
});	
	
	
$( document ).on( "click", ".removeStu", function() {
	
	var r = confirm("Please press 'OK' to continue");
	var url = '<?php echo $canonical; ?>';	
	
	var cid = $(this).parents('table')[0].dataset.cid;	
	var theTr = $(this).parents('tr')[0];
	var bid = theTr.dataset.bid;
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: {	cid: cid, bid: bid, action:'remove'},      
				success:function(data){		
					if(data=='OK'){ 
						$(theTr).remove();
					}											
				}
			});
	}	
});



$( document ).on( "click", ".noteSAVE", function() {
	
	if(timer) clearTimeout(timer); 
	if(ajaxer) ajaxer.abort(); 		
	
	var noteId = $(this).data('note');
	var content = $('#note'+noteId).val();
	 
	timer = setTimeout(function() {
		$('#noteMsg'+noteId).html('processing...');
		
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: {	cid: noteId, content: content, action:'addnote'},     
			success:function(data){  
				if(data=='OK'){ 
					$('#noteMsg'+noteId).html('Saved');
				}				
			}
		});
		
	 },300); 
	 
	 
	 
});


$( document ).on( "click", ".join", function() {
	

	
	var cid = $(this).data('cid');
	var bid = $(this).data('bid');
	var lesson = $('#title'+cid).data('lesson');	
	var me = 	$(this); 
	var name =  $(this).data('name');
	var sWidth =  1/lesson.length*100;
	
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: {	cid: cid, bid: bid, action:'join'},     
			success:function(data){   
				if(data=='OK'){ 
					$(me).html('Joined');
					$(me).removeClass('join');
					
					var url = '<?php echo base_url("xAdmin/baptist/"); ?>'+bid;
					var inhtml='';
					
					lesson.forEach(function(item){
						inhtml += '<div style="width:'+sWidth+'%;"><span data-date="'+item+'" class="absence sbt"></span></div>';
					});
					
					
					var html = '<tr data-bid="'+bid+'"><td><a target="_blank" href="'+url+'">'+name+'</a></td><td class="tdLogs"><div class="logsWrap clearfix">'+inhtml+'</div></td><td id="c'+cid+'c'+bid+'">0%</td><td class="tdFunction"><a href="javascript:void(0);" class="removeStu">remove</a></td></tr>';
					
					$('#trTitle'+cid).after(html);
					
					
					
					var htmlforsendms = '<div><input type="checkbox" class="to_'+cid+'" id="sendItem'+bid+'" value="'+bid+'"> <label for="sendItem'+bid+'">'+name+'</label></div>';
					$('.stlists_'+cid+' .stuhtml').prepend(htmlforsendms);
					
					
					
				}				
			}
		});
		

	 
	 
	 
});


var searchWrap;
$( document ).on( "click", ".addStudent", function() {
	
	if(typeof(searchWrap) != "undefined" && searchWrap !== null) {
			return;
	}
	
	var cid = $(this).data('cid'); 
	
	var input = document.createElement('input');
	
	var msg = document.createElement('p');
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Search';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	
	var title = document.createElement('h3');	
	title.innerHTML = 'Add students to: '+$('#title'+cid).html();
	
	searchWrap = document.createElement('div');
	searchWrap.id = 'addStudentDiv';
	searchWrap.classList.add("ajaxDiv");
	
	
	searchWrap.appendChild(btC);
	searchWrap.appendChild(title);
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
					data: {	cid: cid,query:query, action:'searchStu'},      
					success:function(data){ 
						
						msg.innerHTML = '';
						lists.innerHTML = '';
						
						if(data.length==0){
							msg.innerHTML = 'Did not match any documents.';
						}else{
							
							
							data.forEach(function(item){
								var p2 = item[2]==1?'<span class="function">Joined</span>':'<a href="javascript:void(0);" data-name="'+item[1]+'" data-cid="'+cid+'" data-bid="'+item[0]+'" class="join function">+Add</a>';
								var li = document.createElement('li'); 
								li.innerHTML = item[1]+' '+p2;
								
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


$( document ).on( "click", ".selectAll", function() {
	
	var cid = $(this).data('cid');
	
	$('.to_'+cid).each(function() { 
		
			$(this).prop( "checked", true );
		
		});
	
});

$( document ).on( "click", ".selectInverse", function() {
	
	var cid = $(this).data('cid');
	
	$('.to_'+cid).each(function() { 
		
			$(this).prop( "checked", !$(this).is(':checked'));
		
		});
	
});

$( document ).on( "click", ".messageSend", function() {
		var cid = $(this).data('cid');
		var cTitle = $(this).data('ctitle');
	
		var msg = $('.message_'+cid).val();
		
		var files=[];
		var to=[];
		
		$('.files_'+cid).each(function() { 			
			files.push($(this).html());		
		});

		$('.to_'+cid).each(function() { 			
			if($(this).is(':checked')){
				to.push($(this).val());	
			}		
		});		

	var error='';
	$('.smsError_'+cid).html('Loading...');
	
	if(!msg){
		error += 'Message field is required.<br />';
	}
	
	if(to.length==0){
		error += 'Please select at least one student.<br />';
	}
	
	if(error){
		$('.smsError_'+cid).html(error);
	}else{
		

			params = {msg:msg,files:files,to:to,action:'sendMs',cid:cid,cTitle:cTitle}
		

			
			$.ajax({
				dataType:'html',
				method: "POST",
				url:  '<?php echo $canonical; ?>',
				data: params,      
				success:function(data){ 
						$('.smsError_'+cid).html('Submit successfully!');
						$('.filesPreview_'+cid).html('');
						$('#msgLogs'+cid+' .nologs').remove();
						$('.message_'+cid).val('');
						$('#logsWrap'+cid).prepend(data);
					


					
				}
			});
			
	
		
	}
		
		

	
});




var sendWrap;
$( document ).on( "click", ".messageSendcancel", function() {
	
	if(typeof(sendWrap) != "undefined" && sendWrap !== null) {
			return;
	}
	
	var cid = $(this).data('cid'); 
	
	var msg = document.createElement('p');
	
	var selectInverse = document.createElement('button');
	selectInverse.innerHTML = '反選';	
	
	var selectAll = document.createElement('button');
	selectAll.innerHTML = '全選';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	
	var title = document.createElement('h3');	
	title.innerHTML = 'Send message to: ';
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Send';	
	btS.id = 'sendMsBt';
	
	sendWrap = document.createElement('div');
	sendWrap.id = 'messageSendToDiv';
	sendWrap.classList.add("ajaxDiv");
	
	sendWrap.appendChild(btC);
	sendWrap.appendChild(title);
	
	sendWrap.appendChild(selectAll);
	sendWrap.appendChild(selectInverse);
	
	sendWrap.appendChild(msg);
	sendWrap.appendChild(lists);
	
	sendWrap.appendChild(btS);
	
	$('.people'+cid).each(function() { 
		
		var name = $(this).text();
		var bid = $(this).data('bid');
		var li = document.createElement('li'); 
		var liHtml = '<input type="checkbox" class="to_'+cid+'" id="sendItem'+bid+'" value="'+bid+'"> <label for="sendItem'+bid+'">'+name+'</label> ';
		
		li.innerHTML = liHtml;	
		lists.appendChild(li);
	});
	
	
	document.getElementsByTagName("BODY")[0].appendChild(sendWrap);
	
	
	
	btC.addEventListener('click', function(){
		 close();
	});
	

	selectAll.addEventListener('click', function(){
		
		$('input[name="sendto"]').each(function() { 
		
			$(this).prop( "checked", true );
		
		});		
		
	});	
	
	
	selectInverse.addEventListener('click', function(){
		
		$('input[name="sendto"]').each(function() { 
		
			$(this).prop( "checked", !$(this).is(':checked'));
		
		});		
		
	});	
	
	
	btS.addEventListener('click', function(){
		
		var msg = $('.message_9').val();
		
		var files=[];
		var to=[];
		
		$('.files_'+cid).each(function() { 			
			files.push($(this).html());		
		});

		$('.to_'+cid).each(function() { 			
			if($(this).is(':checked')){
				to.push($(this).val());	
			}		
		});		

		if(!msg){
			
		}
		
		
		console.log(msg);	
		console.log(files);	
		console.log(to);	
		
	});	
	
	
	function close(){
		sendWrap.remove();
		sendWrap = undefined;
	}	
});






function showProgressbar(cid,n,m){
	
	w = n/m*100 + '%';
	$('.progressbar_'+cid+' span').html(n+'/'+m);
	$('.progressbar_'+cid+' span').css('width',w);
	
	if(n==m){
		setTimeout(function() {
			$('.progressbar_'+cid).html('<span></span>');
		},2000);
	}
	
}


$( document ).on( "click", ".removeFile", function() {
	
	$(this).parent().remove();
	
});

</script>

</body>
</html>



<?php


function displayMsgLogs($cid,$logs){
	
	
	$logsHtml=''; 
	
	foreach($logs as $log){
		
		$message = nl2br($log['message']);
		
		if(json_decode($log['files'])){
		
			
			$message .= '<br />附件: <br />';
			
			foreach(json_decode($log['files']) as $file){
				$message .= '<a href="'.base_url().'files/'.$cid.'/'.$file.'">'.base_url().'files/'.$cid.'/'.$file.'</a><br />';
			}
			
		}
		
		$msgto = [];
		$send_to_arr = json_decode($log['sendto']);
		foreach($send_to_arr[0] as $key => $stu){
			$msgto[] = $stu.'&lt;'.$send_to_arr[1][$key].'&gt;';
		}		
		
		$logsHtml .= '<div class="slog">
		<h4>'.ucwords($log['sender']).' 於 '.date("m/d/Y g:i a",$log['date']).' 提交</h4>
		<h4>信息內容:</h4><div class="msgcon">'.$message.'</div>
		<h4>目標:</h4><div class="msgto">'.implode(', ',$send_to_arr[0]).'</div>	
		</div>';
	}
	
	if($logsHtml==''){
		$logsHtml = '<div class="nologs">暫無記錄</div>';
	}
	
	echo '
	<div class="classMsgLogs" id="msgLogs'.$cid.'">
	<h3>Logs</h3>
	<div id="logsWrap'.$cid.'">'.$logsHtml.'</div></di>';	
};

function displayFunction($cid,$students,$cTitle){
	
	

	
	$stuhtml ='';
	foreach($students  as $stu){
		$stuhtml .='<div><input type="checkbox" class="to_'.$cid.'" id="sendItem'.$stu['baptism_id'].'" value="'.$stu['baptism_id'].'"> <label for="sendItem'.$stu['baptism_id'].'">'.$stu['name'].'</label></div>';
	}
	

	
	
	echo '
	<div class="classFunction" id="cf'.$cid.'">
	
			
				<div class="addDescription"><textarea class="message message_'.$cid.'" rows="6" cols="50" placeholder="Message" ></textarea></div>
            

            
                <div class="progressbar progressbar_'.$cid.'"><span></span></div>
        
				<div class="preUploadError preUploadError_'.$cid.'"></div>
		
		
          
			<div class="filesPreview filesPreview_'.$cid.'"></div>
			
			
			<div class="stlists stlists_'.$cid.'"><h4>Send to:</h4><button class="selectAll" data-cid="'.$cid.'">全選</button> <button class="selectInverse" data-cid="'.$cid.'">反選</button><div class="stuhtml">'.$stuhtml.'</div></div>
        
			<p class="bts"><input  data-cid="'.$cid.'"  data-ctitle="'.$cTitle.'"  type="button" class="messageSend"  value="Send"/></p>
			
			<p class="smsError smsError_'.$cid.'"></p>
			
			
	</div>';
};

function displayStudents($cid,$students,$sessions,$isElearningClass){


	$tdNum =  $isElearningClass ? 1 : count($sessions);
	$cWidth = floor(1/(($tdNum+3)*2)*100);
	$sWidth = floor(1/$tdNum*100);
	
	
	
	
	echo '<table data-cid="'.$cid.'" class="curriculumTb"><tr id="trTitle'.$cid.'" class="curriculumTbTitle"><td style="width:'.($cWidth*2).'%;" class="tdName"></td><td class="tdLogs"><div class="logsWrap clearfix">';
	
	if(!$isElearningClass){
		foreach($sessions as $item){
			
			echo '<div style="width:'.$sWidth.'%;">'.date("m/d/Y",$item).'</div>';
			
		}
	}else{
		
		echo '<div style="width:'.$sWidth.'%;">Start Date</div>';
		 
	}
	
	echo '</div></td><td style="width:'.$cWidth.'%;" class="tdCompletion">Completion</td><td style="width:'.$cWidth.'%;"  class="tdFunction"></td></tr>';
	
	foreach($students as $stu){
		
		
		
		$logsInfo = [];
		$logsInfoTmp = explode('|||',$stu['logs']);
		
		foreach($logsInfoTmp as $item){
			
			if(strpos($item, 'signin')!==false) $logsInfo['signin'][] = strstr($item, 'signin',true);
			
			if(strpos($item, 'finish')!==false) $logsInfo['finish'] = strstr($item, 'finish',true);
			
			if(strpos($item, 'join')!==false) $logsInfo['join'] = strstr($item, 'join',true);
			
		}
		
		
		
		
		echo '<tr id="cid'.$cid.'bid'.$stu['baptism_id'].'" data-bid="'.$stu['baptism_id'].'"><td><a target="_blank" class="people'.$cid.'" data-bid="'.$stu['baptism_id'].'" href="'.base_url("xAdmin/baptist/".$stu['baptism_id']).'">'.$stu['name'].'</a></td><td class="tdLogs"><div class="logsWrap clearfix">';
		
		
		$completion = 	isset($logsInfo['finish'])?$logsInfo['finish'].'%':'0%';	
		
		if(!$isElearningClass){
			
			foreach($sessions as $item){
				
					
				
				if(isset($logsInfo['signin'])&&in_array(strval($item),$logsInfo['signin'])){
					
					$button = '<span data-date="'.$item.'" class="presence sbt"></span>';
				}else{
					
					$button = '<span data-date="'.$item.'" class="absence sbt"></span>';
				}
				
				echo '<div style="width:'.$sWidth.'%;">'.$button.'</div>';
				
			}
			

		
		}else{
			
			echo '<div style="width:'.$sWidth.'%;">'.date("m/d/Y",$logsInfo['join']).'</div>';			
			
		}
		
		
		
			echo '</div></td><td id="c'.$cid.'c'.$stu['baptism_id'].'">'.$completion.'</td><td class="tdFunction"><a href="javascript:void(0);" class="removeStu">remove</a></td></tr>';		
		
	
	}
	
	
	
	echo '</table>';
	

	
	
	
	

	
	
	
}

?>




