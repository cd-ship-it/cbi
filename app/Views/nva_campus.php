<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1><?= $pageTitle; ?></h1>			  
			<hr />	


<a style=" margin: 0 10px 20px 10px; " href="javascript:void(0);" class="selected tditem">+New Campus</a>


<div class="sesstion" id="s-searchResult">

<table id="results"> 
	<thead>
		<tr class="title"> 
			<th width="50">ID</th>  
			<th>Campus Name</th>  
			<th>Campus Pastor</th> 
		</tr>
	</thead>
	<tbody>
<?php 

if(isset($campusList) && $campusList){
	foreach($campusList as $key => $item){
		
		$class = "tditem";
		if($key%2 != 0) $class .= " bg";
		
		// Get pastor name if campus_pastor ID exists
		$pastor_name = '';
		if(!empty($item['campus_pastor'])){
			$modelProfiles = new \App\Models\ProfilesModel();
			$pastor_info = $modelProfiles->find($item['campus_pastor']);
			if($pastor_info){
				$pastor_name = trim($pastor_info['fName'] . ' ' . $pastor_info['lName']);
			}
		}
		
		echo '<tr data-campus-id="'.$item['id'].'" class="'.$class.'">';
		echo '<td>'.$item['id'].'</td>';
		echo '<td class="campus-name">'.($item['campus'] ?? '').'</td>';
		echo '<td class="campus-pastor" data-pastor-id="'.($item['campus_pastor'] ?? '').'">'.$pastor_name.'</td>';
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

#wrapNode{background: #fff; position: fixed; width: 400px; left: 50%; margin-left: -200px; margin-top: 15%; padding: 25px; box-shadow: 0 0 20px 2px #000; z-index:999; }
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
	var campus_id = $(this).data('campus-id');
	
	if($(this).hasClass('selected')){		
		
		var inputNodeCampus = document.createElement('input');		
		inputNodeCampus.value = $(this).children('.campus-name').html() ? $(this).children('.campus-name').html() : '';
		inputNodeCampus.dataset.name = 'campus_name';
		inputNodeCampus.placeholder = 'Campus Name';

		var pastorId = $(this).children('.campus-pastor').data('pastor-id') || '';
		
		var pastorSearchContainer = document.createElement('div');
		pastorSearchContainer.className = 'pastor-search-container';
		
		// Create search input
		var searchInput = document.createElement('input');
		searchInput.type = 'text';
		searchInput.placeholder = 'Type to search for Campus Pastor...';
		searchInput.id = 'pastorSearchInput';
		searchInput.className = 'pastor-search-input';
		
		// Create select dropdown
		var selectNodePastor = document.createElement('select');
		selectNodePastor.id = 'pastorSelect';
		selectNodePastor.dataset.name = 'campus_pastor';
		selectNodePastor.style.display = 'none';
		
		// Add empty option
		var emptyOption = document.createElement('option');
		emptyOption.value = '';
		emptyOption.textContent = '-- Select Campus Pastor --';
		selectNodePastor.appendChild(emptyOption);
		
		// Add all pastors from PHP data
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
		
		var innerNode = document.createElement('div');	
		innerNode.id = 'innerNode';		
		
		var sbNode = document.createElement('button');
		sbNode.innerHTML = campus_id ? 'Update' : 'Submit';
		
		var cbNode = document.createElement('button');	
		cbNode.innerHTML = 'Cancel';
		
		var dbNode = document.createElement('button');	
		dbNode.innerHTML = 'Delete';
		dbNode.id = 'del';
		
		var title = document.createElement('h5');
		title.innerHTML = campus_id ? 'Edit Campus' : 'Add Campus';	
		
		var smMsg = document.createElement('span');
		
		innerNode.appendChild(title);
		
		var label = document.createElement('label');
		label.innerHTML = 'Campus Name: ';			
		innerNode.appendChild(label);
		innerNode.appendChild(inputNodeCampus);

		label = document.createElement('label');
		label.innerHTML = 'Campus Pastor: ';			
		innerNode.appendChild(label);
		innerNode.appendChild(pastorSearchContainer);
		
		innerNode.appendChild(sbNode);
		innerNode.appendChild(cbNode);
		if(campus_id){
			innerNode.appendChild(dbNode);
		}
		innerNode.appendChild(smMsg);
		
		wrapNode = document.createElement('div');	
		wrapNode.id = 'wrapNode';
		
		wrapNode.appendChild(innerNode);
		
		cbNode.addEventListener('click', function(){
			close();
		});	
		
		// Create dropdown list container
		var dropdownList = document.createElement('div');
		dropdownList.className = 'pastor-dropdown-list';
		dropdownList.style.display = 'none';
		pastorSearchContainer.appendChild(dropdownList);
		
		// Pastor search functionality - filter dropdown options
		searchInput.addEventListener('input', function(){
			var query = this.value.toLowerCase().trim();
			dropdownList.innerHTML = '';
			
			if(query.length === 0){
				dropdownList.style.display = 'none';
				// Show all options
				Array.from(selectNodePastor.options).forEach(function(option){
					if(option.value !== ''){
						option.style.display = '';
					}
				});
				return;
			}
			
			// Filter and show matching options
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
					
					// Add to dropdown list for display
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
				// Show all options when empty
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
			
			if(!inputNodeCampus.value){ 
				smMsg.innerHTML = 'Please enter campus name';
				return;
			}
			
			var data = {
				campus_id: campus_id,  
				campus_name: inputNodeCampus.value, 
				campus_pastor: selectNodePastor.value || '', 
				action: 'update'
			};
			 
			smMsg.innerHTML = 'Loading...';
			
			ajaxer = $.ajax({
				dataType: 'html',
				method: 'POST',
				url: '<?= base_url("cbi/nva"); ?>',
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
				campus_id: campus_id,  
				action: 'remove'
			};
			 
			smMsg.innerHTML = 'Loading...';
			
			ajaxer = $.ajax({
				dataType: 'html',
				method: 'POST',
				url: '<?= base_url("cbi/nva"); ?>',
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

