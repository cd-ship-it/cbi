
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-users-tab" data-toggle="tab" data-target="#nav-users" type="button" role="tab" aria-controls="nav-users" aria-selected="true">Search Users</button>
    <button class="nav-link" id="nav-shape-tab" data-toggle="tab" data-target="#nav-shape" type="button" role="tab" aria-controls="nav-shape" aria-selected="false">SHAPE Profile</button>
    <button class="nav-link" id="nav-serving-tab" data-toggle="tab" data-target="#nav-serving" type="button" role="tab" aria-controls="nav-serving" aria-selected="false">Serving position</button>
  </div>
</nav>

<div class="tab-content" id="nav-tabContent">
	<div class="tab-pane fade show active p-3" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">
		<form id="searchBox">

							<p>Name/Email: <input class="form-control" title="Name" type="text" value="<?= (isset($inputName)?$inputName:''); ?>"  name="query" id="query" /></p>
							
							<p class="my-3">							
								<input <?= (isset($is_over18)&&$is_over18? 'checked' : ''); ?> type="checkbox" id="over18" name="over18" value="1"> <label for="over18">Show members over the Age of 18 as of today</label> 
							</p>

							
						  <div class="form-row">
							<div class="col">
							 <select class="form-control" name="theClass" id="theClass">
									<option value="">Choose an option</option>
									<?php
										foreach($curriculumCodes as $cc){
											echo '<option '.(isset($inputtheClass)&&$inputtheClass==$cc[0]?'selected':'').' value ="'.$cc[0].'">'.$cc[1].'</option>';		
										}	
									?>
								</select> 
							</div>
							<div class="col">
							 	<select  class="form-control <?= ($inputcStatus?'':'hide'); ?>"  name="cStatus" id="cStatus">
									<option <?= (isset($inputcStatus)&&$inputcStatus=='completed'?'selected':''); ?> value="completed">Completed</option>
									<option <?= (isset($inputcStatus)&&$inputcStatus=='unjoined'?'selected':''); ?> value="unjoined">Incomplete</option>
									<option <?= (isset($inputcStatus)&&$inputcStatus=='incompleted'?'selected':''); ?> value="incompleted">In Progress</option>	
								</select> 
							</div>
						  </div>
  
		 
		 
		 
							
							<p> 
								<select class="form-control" name="mStatus" id="mStatus">
									<option value="">Member Status</option>
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
							
							<input class="form-control"  input="text" style="    display: none;" />

							
							<p class="bts">
							<button  class="btn btn-secondary " type="reset" id="btReset" value="Reset">Reset</button> | 
							<button class="btn btn-primary " type="button" onclick="search_submit()" id="btFilter">Search</button> | 
							<button  class="btn btn-info " type="button" onclick="search_submit(1)" id="btAll">Display all</button>
							</p>
						

						</form>
	</div>
	
  <div class="tab-pane fade" id="nav-shape" role="tabpanel" aria-labelledby="nav-shape-tab">
 <form class="hidenBox p-3" id="shapeSearchBox">
					 
									<div id="siteDiv">
										<h3>Site:</h3>
									
										<ul   id="siteUl">
											<?php 					
												foreach($sites as $key => $item){
													echo '
													<li>
														<div class="form-check">  <input class="form-check-input" type="checkbox" id="site'.$key.'" name="site[]" value="'.$item.'">  <label class="form-check-label" for="site'.$key.'">'.$item.'</label> </div>
 
											
																										
													</li>';
												}

												
											?>				
										</ul>	
									</div>
							
									<div id="myCDiv">
										<h3>Which Church Ministries Would You Like to Participate In?</h3>
									
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
							
							<p class="bts"><button class="btn btn-secondary " type="reset" value="Reset">Reset</button> | <button  type="submit" class="btn btn-primary " id="btFilter">Search</button></p>
							
							
		 					
							

						</form> 
  </div>
  
  <div class="tab-pane fade" id="nav-serving" role="tabpanel" aria-labelledby="nav-serving-tab">
 							<form class="hidenBox p-3" id="servingSearchBox">


								<h1></h1> <?php /*?>Search Serving position<?php */?>


								
								<div id="myCDiv">
								
									<p>Serving position: <input class="form-control" title="Serving position" type="text" value="" name="query" id="sPquery"></p>	
								
								</div>
								
								


										

							<input  type="hidden" name="action" value="servingSearch" />
								
										<p id="resultsMsg"></p>
									
									<ul id="returnMinistries">
									
									</ul>
								

								
								<p class="bts"><button   class="btn btn-primary " type="submit" id="spFilter">Search</button></p>

							</form>  
  </div>
</div>
		  
		  
	    
		  
		
		

			<div class="row">
				<div class="col-md-12 stretch-card">
				  <div class="card">
					<div class="card-body m-0">
					
					
					
 <div class="table-responsive sesstion" id="s-searchResult">
                    <?php if($returnHtml) echo $returnHtml; ?>	
                  </div>					
					
					
					
					
				 
					</div>
				  </div>
				</div>
			</div>





 

	 

	<style>
	.clearfix:after{content:" ";display:block;height:0;clear:both;visibility:hidden}
	.clearfix{display:inline-block}
	input,input:focus{ border: 1px #444 solid;}
	 
	#results .title{   text-transform: capitalize;} 
	#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}

	#results div div{ padding:10px; border-left: 1px solid #ccc;}
	#results p { font-size:14px;line-height:18px;margin: 10px 0;}

	.sortable th {     cursor: pointer;}

	#s-searchBar{    margin: 20px 10px;}


	#searchBox .bts{margin-top:10px;}
	#searchBox p{margin:5px 0;}

	#searchBox .hide{ display:none;}

	tr.signin a.profile{ background:url(<?= base_url(); ?>/assets/images/member.png) no-repeat 0 2px;     padding-left: 20px;}

	#btFilter{ padding-right:40px;padding-left:40px;}

	.x-results{margin-bottom:10px;}

	#wrapNode{background: #fff; position: fixed; width: 300px; left: 50%; margin-left: -150px; margin-top: 15%; padding: 25px; box-shadow: 0 0 20px 2px #000; z-index:999; }
	#wrapNode input,#wrapNode select{ width:100%; margin-bottom:15px; }
	#wrapNode textarea{ width:100%; height:150px; margin-bottom:15px; }

	#wrapNode span{  display: block; color: red; }

	.list td.selected{    background: #fff38a;}



	.x-results a{padding: 0 5px; }
	.x-results a.current{background: #ccc; }
	.x-results a.current:after{ content:' ▼'; }


	 

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
	
	.ageSlider  .slider-selection { 	background: #4e73df;}

	.ageSlider  .slider-handle { 	background: #c7c7c7;}

	@media screen and (max-width:575px ) {
		
		#mySDiv li, #myHDiv li{
			width: 90%;
		display: inline-block;
	}

	}
	</style>
	
	
<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'>	
	



	<script type="text/javascript">

 


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
						url: '<?php echo $canonical; ?>',
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
						url: '<?php echo $canonical; ?>',
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
				over18 = $('#over18').prop('checked')?1:0;	
				
			
				
				if(timer) clearTimeout(timer); 
				if(ajaxer) ajaxer.abort(); 
			  
				params = {
					query: searchfName,  
					theClass: theClass,  
					over18: over18,  
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
						url: '<?php echo $canonical; ?>',
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
						url: '<?php echo $canonical; ?>',
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
			$( '#over18' ).prop("checked",0);
			
	}


	</script>
