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
.clearfix:after{content:" ";display:block;height:0;clear:both;visibility:hidden}
.clearfix{display:inline-block}

#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}

#results div div{ padding:10px; border-left: 1px solid #ccc;}
#results p { font-size:14px;line-height:18px;margin: 10px 0;}

.sortable th {     cursor: pointer;}

#s-searchBar,#s-searchResult{    margin: 20px 10px;}


#searchBox .bts{margin-top:10px;}
#searchBox p{margin:5px 0;}

#searchBox .hide{ display:none;}

tr.signin a.profile{ background:url(<?= base_url(); ?>/assets/images/member.png) no-repeat 0 2px;     padding-left: 20px;}

#btFilter{ padding-right:40px;padding-left:40px;}

.x-results{margin-bottom:10px;}

#wrapNode{background: #fff; position: fixed; width: 300px; left: 50%; margin-left: -150px; margin-top: 15%; padding: 25px; box-shadow: 0 0 20px 2px #000; }
#wrapNode input,#wrapNode select{ width:100%; margin-bottom:15px; }
#wrapNode textarea{ width:100%; height:150px; margin-bottom:15px; }

#wrapNode span{  display: block; color: red; }

.list td.selected{    background: #fff38a;}



.x-results a{padding: 0 5px; }
.x-results a.current{background: #ccc; }
.x-results a.current:after{ content:' ▼'; }


.hidenBox{ display:none;}

.hidenBox ul,.hidenBox p{ margin: 0 0 10px 0;
    padding: 0;}
.hidenBox li{        padding-bottom: 10px;
    padding-right: 20px;}
.hidenBox h3{    margin: 10px 0;}	

  #returnMinistries li{   text-decoration: underline;
    color: blue;
    cursor: pointer;
  }
  
#returnMinistries li:hover{ text-decoration: none;}  

#returnMinistries li:before{ content:'└ '; }

#mySDiv li, #myHDiv li, #siteDiv li{
	
    display: inline-block;
}

@media screen and (max-width:575px ) {
	
	#mySDiv li, #myHDiv li{
	    width: 90%;
    display: inline-block;
}

}
</style>

</head>

<body>

<?= $header; ?>


<div class="sesstion" id="s-searchBar">

<form id="searchBox">

	<p>Name: <input title="Name" type="text" value="<?= (isset($inputName)?$inputName:''); ?>"  name="query" id="query" /></p>
	
	<p>Class: 
	
		<select name="theClass" id="theClass">
			<option value="">---</option>
			<?php
				foreach($curriculumCodes as $cc){
					echo '<option '.(isset($inputtheClass)&&$inputtheClass==$cc[0]?'selected':'').' value ="'.$cc[0].'">'.$cc[1].'</option>';		
				}	
			?>
		</select> 
		
		<select  <?= ($inputcStatus?'':'class="hide"'); ?> name="cStatus" id="cStatus">
			<option <?= (isset($inputcStatus)&&$inputcStatus=='completed'?'selected':''); ?> value="completed">已完成</option>
			<option <?= (isset($inputcStatus)&&$inputcStatus=='unjoined'?'selected':''); ?> value="unjoined">未完成</option>
			<option <?= (isset($inputcStatus)&&$inputcStatus=='incompleted'?'selected':''); ?> value="incompleted">進行中</option>	
		</select> 		
	</p>
	
	<p>Member Status: 
		<select name="mStatus" id="mStatus">
			<option value="">---</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='member'?'selected':''); ?> value="member">Member</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='pending'?'selected':''); ?> value="pending">Awaiting approval</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='premember'?'selected':''); ?> value="premember">Pre-member</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='exmember'?'selected':''); ?> value="exmember">Ex-member</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='inactive'?'selected':''); ?> value="inactive">Inactive</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='guest'?'selected':''); ?> value="guest">Guest</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='registered'?'selected':''); ?> value="registered">Registered</option>
			<option <?= (isset($inputmStatus)&&$inputmStatus=='unregistered'?'selected':''); ?> value="unregistered">Unregistered</option>
		</select> 
	</p>
	
	<input  input="text" style="    display: none;" />

	
	<p class="bts"><button type="reset" id="btReset" value="Reset">Reset</button> | <button type="button" onclick="search_submit()" id="btFilter">Search</button> | <button type="button" onclick="search_submit(1)" id="btAll">Display all</button></p>
<br />
<p><a onclick="toggleForm('#shapeSearchBox');" href="javascript: void(0);">Search SHAPE Profile</a></p>
<p><a onclick="toggleForm('#servingSearchBox');" href="javascript: void(0);">Search Serving position</a></p>

</form>

<form class="hidenBox" id="shapeSearchBox">
<h1>Search SHAPE Profile</h1>
			<div id="siteDiv">
				<h3>Site:</h3>
			
				<ul   id="siteUl">
					<?php 					
						foreach($sites as $key => $item){
							echo '
							<li>
								
									<input type="checkbox" id="site'.$key.'" name="site[]" value="'.$item.'"> <label for="site'.$key.'">'.$item.'</label>
																				
							</li>';
						}

						
					?>				
				</ul>	
			</div>
	
			<div id="myCDiv">
				<h3>愿参与哪些教会事工:</h3>
			
				<ul class="clearfix"  id="myCUl">
					<?php 					
							foreach(lang('shape_lang.cOptions') as $key => $item){
								echo '
								<li>
									
										<input type="checkbox" id="myC'.$key.'" name="myC[]" value="'.$key.'"> <label for="myC'.$key.'">'.$item[0].' '.$item[1].'</label> 
																					
								</li>';
							}

						
						?>					
				</ul>	
			</div>	
			
			
			
			

<input type="hidden" name="action" value="shapeSearch" />
	
	<p class="bts"><button type="reset" value="Reset">Reset</button> | <button  type="submit" id="btFilter">Search</button> | <a onclick="toggleForm();" href="javascript: void(0);">Cancel</a></p>

</form>
	






<form class="hidenBox" id="servingSearchBox">


	<h1></h1> <?php /*?>Search Serving position<?php */?>


	
	<div id="myCDiv">
	
		<p>Serving position: <input title="Serving position" type="text" value="" name="query" id="sPquery"></p>	
	
	</div>
	
	


			

<input type="hidden" name="action" value="servingSearch" />
	
			<p id="resultsMsg"></p>
		
		<ul id="returnMinistries">
		
		</ul>
	

	
	<p class="bts"><button style="display:none;"  type="submit" id="spFilter">Search</button> <a onclick="toggleForm();" href="javascript: void(0);">Cancel</a></p>

</form>










</div>
<hr />

<div class="sesstion" id="s-searchResult">
<?php

if($returnHtml) echo $returnHtml;

?>
</div>


<?php
/* <div id="s-archive">
<ul>
<li><a href="#">2020 (18)<ul>
<li><a href="#">06/05/2020 (5)</a></li>
<li><a href="#">03/05/2020 (2)</a></li>
<li><a href="#">02/05/2020 (7)</a></li>
<li><a href="#">01/05/2020 (4)</a></li>
</ul>
</a></li>
<li><a href="#">2019</a></li>
<li><a href="#">2018</a></li>
<li><a href="#">2017</a></li>
<li><a href="#">2016</a></li>

</ul>
</div> */
?>






<script type="text/javascript">


var timer  = null; 
var ajaxer  = null; 


$('#sPquery').bind('input propertychange',  function() {
	doSearch();
});

function doSearch() {
	$('#returnMinistries').html('');	
	

	var query_str = $('#sPquery').val().trim();	
	
	if(!query_str){ return;}
	
	
	
	if(timer) {clearTimeout(timer); }
	if(ajaxer) {ajaxer.abort(); }
  
	params = {
		query: query_str,  
		action: 'searchMinistry',
		admin: 1,
	};
	 
	timer = setTimeout(function() {
		$('#resultsMsg').html('Loading...');
		
		ajaxer = $.ajax({
			dataType:'html',			
			url: "<?= base_url('member'); ?>", 
			data: params, 
			method: "POST",			
			success:function(data){
				
					$('#returnMinistries').html(data.replace('Suggest this item', '[Management]').replaceAll('+ Add', ''));
					$('#resultsMsg').html('');
				
			}
		});
		
	 },600);	
};
	

$( document ).on( "click", "#returnMinistries li", function() {
    
	
	var ministryId = $(this).data('ministryid');
	var ministryVal = $(this).data('ministryval');
	var item =  $(this);
	
	if(!ministryId){
		window.location.href="<?= base_url('xAdmin/ministries'); ?>"; 
	}
	
	
	
	$('#resultsMsg').html('');
	$('#sPquery').val(ministryVal);
	$('#returnMinistries').html('');
			
		
	$('#spFilter').click();	 
		
		

	
});



function toggleForm(id='') {
	
	$('#shapeSearchBox').hide();
	$('#servingSearchBox').hide();
	$('#searchBox').hide();		
	
	
	if(id==''){
		$('#searchBox').show();
	}else{
		$(id).show();
	}


	
	$('#s-searchResult').html('');	
	$('#returnMinistries').html('');	
	$('#resultsMsg').html('');	
	$('#sPquery').val('');	
}


$("#shapeSearchBox").submit(function(event){
    event.preventDefault(); 
	
	var params = $(this).serialize();
	//console.log(params);
	
	
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		
		
			timer = setTimeout(function() {
				$('#s-searchResult').html('Loading...');
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?php echo $adminUrl; ?>',
					data: params,      
					success:function(data){
						
							$('#s-searchResult').html(data);

						
					}
				});
				
			 },600);	
		 
		
		
	
	
});


$("#servingSearchBox").submit(function(event){
    event.preventDefault(); 
	
	var params = $(this).serialize();
	//console.log(params);
	
	
		
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		
		
			timer = setTimeout(function() {
				$('#s-searchResult').html('Loading...');
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?php echo $adminUrl; ?>',
					data: params,      
					success:function(data){
						
							$('#s-searchResult').html(data);

						
					}
				});
				
			 },600);	
		 
		
		
	
	
});



	
  function search_submit(displayAll=0) {

			if(displayAll){frest();}
				
			searchfName = $('#query').val().trim();	
			
			theClass = $('#theClass').val();		
			cStatus = $('#cStatus').val();		
			mStatus = $('#mStatus').val();		
			
			if(!searchfName && !displayAll && !cStatus && !mStatus) return;
			
			
			
			if(timer) clearTimeout(timer); 
			if(ajaxer) ajaxer.abort(); 
		  
			params = {
				query: searchfName,  
				theClass: theClass,  
				cStatus: cStatus,  
				mStatus: mStatus,  
				displayAll: displayAll,
				action: 'search'
			};
			 
			timer = setTimeout(function() {
				$('#s-searchResult').html('Loading...');
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?php echo $adminUrl; ?>',
					data: params,      
					success:function(data){
						
							$('#s-searchResult').html(data);

						
					}
				});
				
			 },600);	
			 

    }
	
  
$( document ).on( "change", "#theClass", function() {
	
	if($(this).val() == ''){
		$( '#cStatus' ).hide();		
	}else{
		$( '#cStatus' ).show();
		$( '#mStatus' ).val('');
	}
	
}); 

 
$( document ).on( "change", "#mStatus", function() {
	
	if($(this).val() != ''){
		$( '#cStatus' ).hide();	
		$( '#theClass' ).val('');		
	}	
}); 


$( document ).on( "click", "#btReset", function() {
	event.preventDefault();
		frest();	
});



var wrapNode;

$( document ).on( "click", ".tditem", function() {
	
	if(typeof(wrapNode) != "undefined" && wrapNode !== null) {
			return;
	}
	
	var theTd = $(this);
	
	if($(this).hasClass('selected')){		
		
		if($(this).hasClass('dateInput')){
		
			var inputNode = document.createElement('input');
		
		}else if($(this).hasClass('genderInput')){
			
			var inputNode = document.createElement('select');
			
			var op1 = document.createElement("option");
			op1.text = "Male";
			op1.value = "M";
			if($(this).text()==op1.value){
				op1.selected = true;
			};			
			inputNode.add(op1);

			var op2 = document.createElement("option");
			op2.text = "Female";
			op2.value = "F";
			if($(this).text()==op2.value){
				op2.selected = true;
			};			
			inputNode.add(op2);
			
			
		}else if($(this).hasClass('maritalInput')){
			
			var inputNode = document.createElement('select');
			
			var op1 = document.createElement("option");
			op1.text = "Married";
			op1.value = "M";
			if($(this).text()==op1.value){
				op1.selected = true;
			};
			inputNode.add(op1);

			var op2 = document.createElement("option");
			op2.text = "Single";
			op2.value = "S";
			if($(this).text()==op2.value){
				op2.selected = true;
			};			
			inputNode.add(op2);
			
			
		}else if($(this).hasClass('siteInput')){
			
			var inputNode = document.createElement('select');
			
			var siteArr = <?= (json_encode($sites)); ?>;
			
			siteArr.forEach(function(item){
				
				var op1 = document.createElement("option");
				op1.text = item;
				op1.value = item;
				
				if($(this).text()==op1.value){
					op1.selected = true;
				};				
				
				inputNode.add(op1);	
				
			});
			
		}else{
			
			var inputNode = document.createElement('textarea');
		}	
		
		
		inputNode.value= $(this).text();
		inputNode.dataset.mid =  $(this).data('mid');
		inputNode.dataset.name =  $(this).data('lable');
		
		
		if($(this).hasClass('dateInput')){
		
			 rome(inputNode, {
				  time:false,
				  inputFormat: "MM/DD/YYYY",
				});		
		
		
		}
			 
		
		
		
		var innerNode =  document.createElement('div');	
		innerNode.id= 'innerNode';		
		
		var sbNode =  document.createElement('button');
		sbNode.innerHTML = 'Update';
		
		var cbNode =  document.createElement('button');	
		cbNode.innerHTML = 'Cancel';
		
		
		var title =  document.createElement('h5');
		title.innerHTML = $(this).data('lable');	
		
		var smMsg =  document.createElement('span');
		
		//innerNode.appendChild(title);
		innerNode.appendChild(inputNode);
		innerNode.appendChild(sbNode);
		innerNode.appendChild(cbNode);
		
		innerNode.appendChild(smMsg);
		
		wrapNode =  document.createElement('div');	
		wrapNode.id= 'wrapNode';
		
		wrapNode.appendChild(innerNode);
		
		
		cbNode.addEventListener('click', function(){
			 close();
		});	
		

		sbNode.addEventListener('click', function(){
			
			if(!inputNode.value){ 
				smMsg.innerHTML='Please enter valid data';
				return;
			}
			
			var data = {
				mid: inputNode.dataset.mid,  
				name: inputNode.dataset.name, 
				val: inputNode.value, 
				action: 'singelUpdate'
			};
			 
			
				smMsg.innerHTML='Loading...';
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?php echo $adminUrl; ?>',
					data: data,      
					success:function(data){
						
							smMsg.innerHTML=data;
							if(data=='Updated'){
								theTd.text(inputNode.value);
								 close();
							}else{
								smMsg.innerHTML='Error';
							}

						
					}
				});		
			
		});		
		
		
		$('body').before(wrapNode);
	}else{
		$('.selected').removeClass('selected');
		$(this).addClass('selected');
	}
	
	
	
	function close(){
		wrapNode.remove();
		wrapNode = undefined;
	}

	
	
	
	
});





$(document).on('click','.sortable th',function(){
    var table = $(this).parents('table').eq(0);
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
    this.asc = !this.asc;
    if (!this.asc){rows = rows.reverse();}
    table.children('tbody').empty().html(rows);
});




function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index);
        return $.isNumeric(valA) && $.isNumeric(valB) ?
            valA - valB : valA.localeCompare(valB);
    };
}
function getCellValue(row, index){
	
	var tdRval;
	
	tdRval = $(row).children('td').eq(index).data('rval'); 
	
     if(tdRval!==undefined){
		 return tdRval;
	 }else{
		return $(row).children('td').eq(index).text();
	 }
	 
	 
}






function frest(){
		$( '#cStatus' ).hide();	
		$( '#query' ).val('');	
		$( '#theClass' ).val('');
		$( '#mStatus' ).val('');	
}


</script>

</body>
</html>
