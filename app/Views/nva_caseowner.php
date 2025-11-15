<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1><?= $pageTitle; ?></h1>			  
			<hr />	


<a style=" margin: 0 10px 20px 10px; " href="javascript:void(0);" class="selected tditem">+New Mapping</a>


<div class="sesstion" id="s-searchResult">

<table id="results"> 
	<thead>
		<tr class="title"> 
			<th>Campus</th>  
			<th>Life Status</th>
			<th>Pastor</th> 
		</tr>
	</thead>
	<tbody>
<?php 

if(isset($mappings) && $mappings){
	foreach($mappings as $key => $item){
		
		$class = "tditem";
		if($key%2 != 0) $class .= " bg";
		
		echo '<tr data-map-id="'.$item['id'].'" class="'.$class.'">';
		echo '<td class="campus-name" data-campus-id="'.($item['campus_id'] ?? '').'">'.($item['campus'] ?? '').'</td>';
		echo '<td class="life-status" data-lifestatus-id="'.($item['lifestatus_id'] ?? '').'">'.($item['life_status'] ?? '').'</td>';
		echo '<td class="pastor-name" data-pastor-id="'.($item['pastor_id'] ?? '').'">'.($item['pastor_name'] ?? '').'</td>';
		echo '</tr>';
	}
}

?>
	</tbody> 
</table>

</div>



</div></div></div></div>

 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/jquery-3.3.1.min.js'></script>
	
	

<style>
.clearfix:after{content:" ";display:block;height:0;clear:both;visibility:hidden}
.clearfix{display:inline-block}

#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}
input,input:focus{ border: 1px #444 solid;}
tr.bg{ background: #fafafa; }
tr.selected{background: #fff38a; }

#wrapNode{background: #fff; position: fixed; width: 450px; left: 50%; margin-left: -225px; margin-top: 15%; padding: 25px; box-shadow: 0 0 20px 2px #000; z-index:999; }
#wrapNode input,#wrapNode select{ width: 100%; margin-bottom:15px; }
#wrapNode textarea{ width:90%; height:150px; margin-bottom:15px; }

#wrapNode span{  display: block; color: red; margin-top: 15px; }
#wrapNode h5{ margin-bottom:15px; font-size:18px;}

#wrapNode button{ margin:5px 5px 0 0;}
#wrapNode lable{display: block;}
#wrapNode #del{ float:right;}

td.selected{    background: #fff38a;}

.pastor-search-container {
	position: relative;
}

.pastor-search-input {
	width: 100%;
	padding: 8px;
	border: 1px solid #ccc;
}

.pastor-dropdown-list {
	position: absolute;
	top: 100%;
	left: 0;
	right: 0;
	background: white;
	border: 1px solid #ccc;
	border-top: none;
	max-height: 200px;
	overflow-y: auto;
	z-index: 1000;
	box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.pastor-dropdown-item {
	padding: 8px;
	cursor: pointer;
	border-bottom: 1px solid #eee;
}

.pastor-dropdown-item:hover {
	background: #f0f0f0;
}

.pastor-dropdown-item:last-child {
	border-bottom: none;
}

@media screen and (max-width:575px ) {
	#wrapNode {
		width: 90%;
		margin-left: -45%;
	}
}
</style>	
	

<script type="text/javascript">

var wrapNode;
var timer = null;
var ajaxer = null;

$( document ).on( "click", ".tditem", function() {
	
	if(typeof(wrapNode) != "undefined" && wrapNode !== null) {
		return;
	}
	
	var theTr = $(this);
	var map_id = $(this).data('map-id');
	
	if($(this).hasClass('selected')){		
		
		var campusId = $(this).children('.campus-name').data('campus-id') || '';
		var campusName = $(this).children('.campus-name').html() || '';
		
		var lifestatusId = $(this).children('.life-status').data('lifestatus-id') || '';
		var lifestatusName = $(this).children('.life-status').html() || '';
		
		var pastorId = $(this).children('.pastor-name').data('pastor-id') || '';
		var pastorName = $(this).children('.pastor-name').html() || '';
		
		var innerNode = document.createElement('div');	
		innerNode.id = 'innerNode';		
		
		var sbNode = document.createElement('button');
		sbNode.innerHTML = map_id ? 'Update' : 'Submit';
		
		var cbNode = document.createElement('button');	
		cbNode.innerHTML = 'Cancel';
		
		var dbNode = document.createElement('button');	
		dbNode.innerHTML = 'Delete';
		dbNode.id = 'del';
		
		var title = document.createElement('h5');
		title.innerHTML = map_id ? 'Edit Mapping' : 'Add Mapping';	
		
		var smMsg = document.createElement('span');
		
		innerNode.appendChild(title);
		
		// Campus dropdown
		var label = document.createElement('label');
		label.innerHTML = 'Campus: ';			
		innerNode.appendChild(label);
		
		var selectCampus = document.createElement('select');
		selectCampus.id = 'campusSelect';
		selectCampus.dataset.name = 'campus_id';
		
		var emptyCampusOption = document.createElement('option');
		emptyCampusOption.value = '';
		emptyCampusOption.textContent = '-- Select Campus --';
		selectCampus.appendChild(emptyCampusOption);
		
		var campuses = <?= json_encode($campusList ?? []); ?>;
		campuses.forEach(function(campus){
			var option = document.createElement('option');
			option.value = campus.id;
			option.textContent = campus.campus;
			if(String(campus.id) === String(campusId)){
				option.selected = true;
			}
			selectCampus.appendChild(option);
		});
		innerNode.appendChild(selectCampus);
		
		// Life Status dropdown
		label = document.createElement('label');
		label.innerHTML = 'Life Status: ';			
		innerNode.appendChild(label);
		
		var selectLifeStatus = document.createElement('select');
		selectLifeStatus.id = 'lifeStatusSelect';
		selectLifeStatus.dataset.name = 'lifestatus_id';
		
		var emptyLifeStatusOption = document.createElement('option');
		emptyLifeStatusOption.value = '';
		emptyLifeStatusOption.textContent = '-- Select Life Status --';
		selectLifeStatus.appendChild(emptyLifeStatusOption);
		
		var lifeStatuses = <?= json_encode($lifeStatuses ?? []); ?>;
		lifeStatuses.forEach(function(lifeStatus){
			var option = document.createElement('option');
			option.value = lifeStatus.id;
			option.textContent = lifeStatus.life_status;
			if(String(lifeStatus.id) === String(lifestatusId)){
				option.selected = true;
			}
			selectLifeStatus.appendChild(option);
		});
		innerNode.appendChild(selectLifeStatus);
		
		// Pastor smart dropdown
		label = document.createElement('label');
		label.innerHTML = 'Pastor: ';			
		innerNode.appendChild(label);
		
		var pastorSearchContainer = document.createElement('div');
		pastorSearchContainer.className = 'pastor-search-container';
		
		var searchInput = document.createElement('input');
		searchInput.type = 'text';
		searchInput.placeholder = 'Type to search for Pastor...';
		searchInput.id = 'pastorSearchInput';
		searchInput.className = 'pastor-search-input';
		
		var selectNodePastor = document.createElement('select');
		selectNodePastor.id = 'pastorSelect';
		selectNodePastor.dataset.name = 'pastor_id';
		selectNodePastor.style.display = 'none';
		
		var emptyOption = document.createElement('option');
		emptyOption.value = '';
		emptyOption.textContent = '-- Select Pastor --';
		selectNodePastor.appendChild(emptyOption);
		
		var pastors = <?= json_encode($pastors ?? []); ?>;
		var selectedPastorName = '';
		pastors.forEach(function(pastor){
			var option = document.createElement('option');
			option.value = pastor.id;
			option.textContent = pastor.name;
			if(String(pastor.id) === String(pastorId)){
				option.selected = true;
				selectedPastorName = pastor.name;
			}
			selectNodePastor.appendChild(option);
		});
		if(selectedPastorName){
			searchInput.value = selectedPastorName;
		}
		
		pastorSearchContainer.appendChild(searchInput);
		pastorSearchContainer.appendChild(selectNodePastor);
		
		// Create dropdown list container
		var dropdownList = document.createElement('div');
		dropdownList.className = 'pastor-dropdown-list';
		dropdownList.style.display = 'none';
		pastorSearchContainer.appendChild(dropdownList);
		
		innerNode.appendChild(pastorSearchContainer);
		
		innerNode.appendChild(sbNode);
		innerNode.appendChild(cbNode);
		if(map_id){
			innerNode.appendChild(dbNode);
		}
		innerNode.appendChild(smMsg);
		
		wrapNode = document.createElement('div');	
		wrapNode.id = 'wrapNode';
		
		wrapNode.appendChild(innerNode);
		
		cbNode.addEventListener('click', function(){
			close();
		});	
		
		// Pastor search functionality - filter dropdown options
		searchInput.addEventListener('input', function(){
			var query = this.value.toLowerCase().trim();
			dropdownList.innerHTML = '';
			
			if(query.length === 0){
				dropdownList.style.display = 'none';
				Array.from(selectNodePastor.options).forEach(function(option){
					if(option.value !== ''){
						option.style.display = '';
					}
				});
				return;
			}
			
			var hasMatches = false;
			Array.from(selectNodePastor.options).forEach(function(option){
				if(option.value === ''){
					option.style.display = 'none';
					return;
				}
				
				var optionText = option.textContent.toLowerCase();
				if(optionText.indexOf(query) !== -1){
					option.style.display = '';
					hasMatches = true;
					
					var listItem = document.createElement('div');
					listItem.className = 'pastor-dropdown-item';
					listItem.textContent = option.textContent;
					listItem.dataset.value = option.value;
					listItem.addEventListener('click', function(){
						selectNodePastor.value = this.dataset.value;
						searchInput.value = this.textContent;
						dropdownList.style.display = 'none';
					});
					dropdownList.appendChild(listItem);
				} else {
					option.style.display = 'none';
				}
			});
			
			if(hasMatches){
				dropdownList.style.display = 'block';
			} else {
				dropdownList.style.display = 'none';
			}
		});
		
		// Show dropdown when input is focused
		searchInput.addEventListener('focus', function(){
			if(this.value.trim().length > 0){
				this.dispatchEvent(new Event('input'));
			} else {
				dropdownList.innerHTML = '';
				Array.from(selectNodePastor.options).forEach(function(option){
					if(option.value !== ''){
						var listItem = document.createElement('div');
						listItem.className = 'pastor-dropdown-item';
						listItem.textContent = option.textContent;
						listItem.dataset.value = option.value;
						listItem.addEventListener('click', function(){
							selectNodePastor.value = this.dataset.value;
							searchInput.value = this.textContent;
							dropdownList.style.display = 'none';
						});
						dropdownList.appendChild(listItem);
					}
				});
				dropdownList.style.display = 'block';
			}
		});
		
		// Hide dropdown when clicking outside
		document.addEventListener('click', function(e){
			if(!pastorSearchContainer.contains(e.target)){
				dropdownList.style.display = 'none';
			}
		});
		
		// Update search input when select changes
		selectNodePastor.addEventListener('change', function(){
			var selectedOption = this.options[this.selectedIndex];
			if(selectedOption && selectedOption.value !== ''){
				searchInput.value = selectedOption.textContent;
			} else {
				searchInput.value = '';
			}
		});

		sbNode.addEventListener('click', function(){
			
			if(!selectCampus.value || !selectLifeStatus.value || !selectNodePastor.value){ 
				smMsg.innerHTML = 'Please fill in all fields';
				return;
			}
			
			var data = {
				map_id: map_id,  
				campus_id: selectCampus.value, 
				lifestatus_id: selectLifeStatus.value,
				pastor_id: selectNodePastor.value || '', 
				action: 'update'
			};
			 
			smMsg.innerHTML = 'Loading...';
			
			ajaxer = $.ajax({
				dataType: 'html',
				method: 'POST',
				url: '<?= base_url("cbi/nva/caseowner"); ?>',
				data: data,      
				success: function(data){
					smMsg.innerHTML = data;
					if(data == 'ok'){								
						close(); 
						location.reload();
					} else {
						smMsg.innerHTML = data;
					}
				}
			});		
		});			

		dbNode.addEventListener('click', function(){
			
			var data = {
				map_id: map_id,  
				action: 'remove'
			};
			 
			smMsg.innerHTML = 'Loading...';
			
			ajaxer = $.ajax({
				dataType: 'html',
				method: 'POST',
				url: '<?= base_url("cbi/nva/caseowner"); ?>',
				data: data,      
				success: function(data){
					smMsg.innerHTML = data;
					if(data == 'ok'){								
						close();
						$(theTr).remove();
					} else {
						smMsg.innerHTML = data;
					}
				}
			});		
		});

		$('body').before(wrapNode);
	} else {
		$('table .selected').removeClass('selected');
		$(this).addClass('selected');
	}
	
	function close(){
		if(wrapNode){
			wrapNode.remove();
			wrapNode = undefined;
		}
	}
	
});

</script>

