
 
                        <h1 class="h3 mb-1 text-gray-800">Welcome back, <?= ($logged_name); ?></h1>
						<p class="mb-4">Today is <?= (date("l, M d, Y")); // g:i a ?></p> 
       
	   
	   
	   
	   					
					<!-- Search Box Card -->
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Search People</h6>
						</div>
						<div class="card-body">
							<div class="form-group">
								<input type="text" class="form-control" id="baptismSearchInput" placeholder="Search by name, email, or phone number..." autocomplete="off">
							</div>
							<div id="baptismSearchLoading" style="display: none; text-align: center; padding: 10px;">
								<i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
								<span class="ml-2">Searching...</span>
							</div>
							<div id="baptismSearchResults" style="display: none;">
								<ul class="list-group" id="baptismResultsList"></ul>
							</div>
							<div id="baptismSearchNoResults" style="display: none; text-align: center; padding: 10px; color: #6c757d;">
								<i class="fas fa-search"></i>
								<span class="ml-2">Nothing found</span>
							</div>
						</div>
					</div>
					
					<!-- New Visitors Per Campus Card -->
					<?php if(!empty($newVisitorsPerCampus)): ?>
					<div id="newVisitorsCard" class="card shadow mb-4">
						<div class="card-header py-3">
							<div class="d-flex justify-content-between align-items-center flex-wrap">
								<div class="mb-2 mb-md-0">
									<h6 class="m-0 font-weight-bold text-primary">New Visitors by Campus</h6>
									<small class="text-muted">Date Range: <?= $newVisitorsDateRange['startFormatted']; ?> - <?= $newVisitorsDateRange['endFormatted']; ?></small>
								</div>
								<div class="d-flex align-items-center" style="gap: 8px;">
									<label class="mb-0 small mr-1">Start:</label>
									<input type="text" id="visitor_start_date" class="form-control form-control-sm dateInput" style="width: 120px;" value="<?= $newVisitorsDateRange['startInput']; ?>" />
									<label class="mb-0 small mr-1 ml-2">End:</label>
									<input type="text" id="visitor_end_date" class="form-control form-control-sm dateInput" style="width: 120px;" value="<?= $newVisitorsDateRange['endInput']; ?>" />
									<button type="button" id="updateVisitorDates" class="btn btn-sm btn-primary ml-2">
										<i class="fas fa-sync-alt"></i> Update
									</button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<style>
								@media (min-width: 1200px) {
									.visitor-campus-card {
										flex: 0 0 20% !important;
										max-width: 20% !important;
									}
								}
								.visitor-count-link {
									text-decoration: none !important;
								}
								.visitor-count-link:hover {
									text-decoration: underline !important;
								}
							</style>
							<div class="row">
								<?php 
								$colorClasses = ['primary', 'success', 'info', 'warning', 'danger', 'secondary', 'dark'];
								$colorIndex = 0;
								foreach($newVisitorsPerCampus as $row): 
											$campusName = $row['campus'] ?: 'N/A';
											$campusUrlEncoded = $campusName !== 'N/A' ? str_replace(' ', '-', strtolower($campusName)) : '0';
											// Use session dates for drill-down (0 means use session)
											$drillDownUrl = base_url('nva/table/0/1/0/0/'.$campusUrlEncoded.'/0');
									$colorClass = $colorClasses[$colorIndex % count($colorClasses)];
									$colorIndex++;
									$count = (int)$row['count'];
								?>
								<div class="col-xl col-lg-4 col-md-6 col-sm-6 mb-4 visitor-campus-card">
									<div class="card border-left-<?= $colorClass; ?> shadow h-100 py-2">
										<div class="card-body">
											<div class="row no-gutters align-items-center">
												<div class="col mr-2">
													<div class="text-xs font-weight-bold text-<?= $colorClass; ?> text-uppercase mb-1">
														<?= htmlspecialchars($campusName); ?>
													</div>
													<div class="h5 mb-0 font-weight-bold">
														<?php if($count > 0): ?>
															<a href="<?= $drillDownUrl; ?>" class="text-primary visitor-count-link" title="Click to view details">
																<?= $count; ?>
															</a>
														<?php else: ?>
															<span class="text-muted"><?= $count; ?></span>
														<?php endif; ?>
													</div>
												</div>
												<div class="col-auto">
													<i class="fas fa-users fa-lg text-gray-300"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
							<div class="mt-3">
								<a href="<?= base_url('nva/table/0/1/0/0'); ?>" class="btn btn-primary btn-sm">
									<i class="fas fa-external-link-alt"></i> View All New Visitors
								</a>
							</div>
						</div>
					</div>
					<?php endif; ?>
						
	<div class="row">

				  <?php if( $webConfig->checkPermissionByDes('pto_apply') ): ?>
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="mb-3 h5"><a class="text-primary" target="_blank" href="<?= base_url('pto?v='.rand()); ?>">PTO Application</a></div>
                                        </div>
										
										<div class="col-auto">
                                            <i class="fas fa-calendar fa-3x rotate-0 text-primary"></i>
                                        </div>
                                    </div>
									
									                                       
                                </div>
                            </div>
                        </div>  
					<?php endif; ?>		
					
					
					
	 		 
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-secondary" target="_blank" href="<?= base_url('nva/table/'.$logged_id.'/0'); ?>">New Visitors Assimilation</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-3x  text-secondary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
			 
					
					

	
					
					
					<?php /* Hidden: Prayer items card
					if( $webConfig->checkPermissionByDes(['is_pastor','is_admin']) ): ?>
				 
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-primary"  href="<?= base_url('xAdmin/edit_prayer_items?v='.rand()); ?>">Prayer items</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-praying-hands fa-3x  text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php endif; */ ?>	
							
						
						<?php /* Hidden: CBI Class Data card
						if( $webConfig->checkPermissionByDes(['is_pastor','is_admin']) ): ?>
							<div class="col-xl-3 col-sm-6 mb-4">
								<div class="card border-left-info shadow h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
										
											<div class="col mr-2">                                         
												<div class="h5 mb-3"><a class="text-info" target="_blank" href="<?= base_url('classdata'); ?>">CBI Class Data</a></div>
											</div>
											
											<div class="col-auto">
												<i class="fas fa-database fa-3x  text-info"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endif; */ ?>		
						
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="mb-2 h5">Room Approval</div>
											
											<div class="form-group">                                            
                                            <select onchange="selectOnchangeLink(this,this.value);" class="form-control">
                                                <option>Choose an option</option>
                                                <option value="https://docs.google.com/spreadsheet/viewform?formkey=dFV4UTkxci1fbV9BaHU1bDZ2N2hLb3c6MA">Reserve a room (Milpitas)</option>
                                                <option value="https://docs.google.com/spreadsheet/embeddedform?formkey=dFVFUUlpbkRLZ2cxUk00aEhsSTFYdEE6MA">Reserve Gym (Milpitas) </option>
                                                <option value="https://crosspointchurchsv.org/churchoffice/pleasanton-room-calendar/">Reserve Room/Gym (Pleasanton)</option>
                                            </select>
                                        </div>
											
											
                                        </div>
										
                                  
                                    </div>
                                </div>
                            </div>
                        </div>
						
                        <?php /* Hidden: Speaking Engagement card
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-dark" target="_blank" href="https://drive.google.com/file/d/1Gb2T1xEiy7YFYwORmK_oQOgx9aKAjMgK/view?usp=sharing">Speaking Engagement</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-bullhorn fa-3x  text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        */ ?>
						
                        <?php /* Hidden: Announcement Video Application card
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-danger" target="_blank" href="https://docs.google.com/document/d/1n4Bd-7E-alT3B92E3s1VOl7DQCoBJHF7D2ji72RSgzg/edit?usp=drive_link">Announcement Video Application</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-video fa-3x  text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        */ ?>						
                      
					  <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-dark" target="_blank" href="https://drive.google.com/drive/u/0/folders/1TxKYg5PvRSL9Y96xuQJd-CGLzarROmw1">Pulpit Plan</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-flag-checkered fa-3x  text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>						
                       
					   <?php /* Hidden: Pastor Training card
					   <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-primary" target="_blank" href="https://drive.google.com/drive/folders/1D255E5sFwlbt5wSNgnSX9DRqrZPy3eJN?usp=share_link">Pastor Training</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-signal fa-3x  text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        */ ?>	
						
 






                        <?php /* Hidden: Zone Materials card
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="mb-2 h5">Zone Materials </div>
											<div class="form-group">                                            
                                            <select onchange="selectOnchangeLink(this,this.value);" class="form-control" >
                                                <option >Choose an option</option>
                                                <option value="https://drive.google.com/drive/folders/0B7C767B9KmqNTGp0d3BqM0wzcXc?resourcekey=0-_nLNg1t1xGIdQmbF73SGcw&usp=share_link">Discipleship</option>
                                                <option value="https://crosspointchurchsv.org/LGadmin/docs/">Life Groups Information</option>
                                            </select>
                                        </div>
											
											
                                        </div>
										
                                  
                                    </div>
                                </div>
                            </div>
                        </div>
                        */ ?>

				
						
                        <div class="col-xl-3 col-sm-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
									
                                        <div class="col mr-2">                                         
                                            <div class="h5 mb-3"><a class="text-info" target="_blank" href="https://sites.google.com/crosspointchurchsv.org/myxp/staff-portal">Staff Portal</a></div>
                                        </div>
										
                                        <div class="col-auto">
                                            <i class="fas fa-desktop fa-3x  text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                   

                      
           
                    </div>

                    <!-- Content Row -->
	   
	   
	   
	   
	   <?php if($toDoList||$students||$pastoralApproval): ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Your To-do list</h6>
                        </div>
						
						
						
						
						
	
					
						
						
						
						
						
						
						
						
						
                        <div class="card-body">
						

<ul class="accordion list-arrow" id="accordionTodo">
 



<?php	if($students): ?>
	
  <li id="online101" class="my-4">
 
        <strong class="mx-0 px-0" type="button" data-toggle="collapse" data-target="#collapse-101" aria-expanded="true" aria-controls="collapse-101">
         You have <i class="text-danger"><?= $students;?></i> people waiting for you to approve the completion of Class 101.
        </strong>
  

    <div id="collapse-101" class="collapse show" data-parent="#accordionTodo">
	 
     <div class="alert alert-secondary" role="alert">
        
		<div>
Click the following button to review their scores<br />
<a class="btn btn-primary" href="<?= base_url('elearning/table/1/'.$logged_id); ?>">Review</a>

</div>
		
      </div>
    </div>
  </li>	

	
<?php	endif; //if($students) ?>

<?php	if($pastoralApproval): ?>
	
  <li class="my-4">
 
        <strong class="mx-0 px-0" type="button" data-toggle="collapse" data-target="#collapse-pastoralApproval" aria-expanded="true" aria-controls="collapse-pastoralApproval">
         You have <i class="text-danger"><?= $pastoralApproval;?></i> people waiting for Pastoral Approval
        </strong>
  

    <div id="collapse-pastoralApproval" class="collapse show" data-parent="#accordionTodo">
	 
     <div class="alert alert-secondary" role="alert">
        
		<div>
Click the following button to review<br />
<a class="btn btn-primary" href="<?= base_url('xAdmin/pending?pid='.$logged_id); ?>">Review</a>

</div>
		
      </div>
    </div>
  </li>	

	
<?php	endif; //if($pastoralApproval) ?>


	
<?php foreach($toDoList as $key => $item): ?>


  <li class="">
 
        <strong class="mx-0 px-0" type="button" data-toggle="collapse" data-target="#collapse<?= $key; ?>" aria-expanded="true" aria-controls="collapse<?= $key; ?>">
         <?= $item['title']; ?> <?= ($item['status']=='1'?'[Archived]':''); ?> 
        </strong>
  

    <div id="collapse<?= $key; ?>" class="collapse " aria-labelledby="heading<?= $key; //show ?>" data-parent="#accordionTodo">
	<a class="remove todoFun btn btn-link" data-action="remove" data-id="<?= $item['id']; ?>" href="javascript: void(0);">x Remove this item</a>
     <div class="alert alert-secondary" role="alert">
        
		<div><?= $item['content']; ?></div>
		
      </div>
    </div>
  </li>




<?php endforeach;  ?>
</ul>


					
						
						</div>
					</div>
 
	<?php endif; //if($toDoList||$students) ?>	  






<!-- Rome Date Picker -->
<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css' type='text/css'/>

<script type="text/javascript">

var searchTimer = null;
var searchAjaxer = null;

// Baptism search functionality
$('#baptismSearchInput').on('input', function(){
	var query = $(this).val().trim();
	
	if(searchTimer) clearTimeout(searchTimer);
	if(searchAjaxer) searchAjaxer.abort();
	
	if(query.length < 2){
		$('#baptismSearchResults').hide();
		$('#baptismSearchLoading').hide();
		$('#baptismSearchNoResults').hide();
		$('#baptismResultsList').empty();
		return;
	}
	
		searchTimer = setTimeout(function(){
		// Show loading spinner
		$('#baptismSearchLoading').show();
		$('#baptismSearchResults').hide();
		$('#baptismSearchNoResults').hide();
		
		searchAjaxer = $.ajax({
			dataType: 'json',
			method: 'POST',
			url: '<?= base_url("xAdmin"); ?>',
			data: {
				action: 'searchBaptism',
				query: query
			},
			success: function(data){
				// Hide loading spinner
				$('#baptismSearchLoading').hide();
				$('#baptismResultsList').empty();
				
				if(data && data.length > 0){
					data.forEach(function(item){
						var name = item.name || 'Unknown';
						var email = item.email || '';
						var inactiveStatus = item.inactiveStatus || '';
						
						var li = $('<li class="list-group-item"></li>');
						var link = $('<a></a>')
							.attr('href', '<?= base_url("xAdmin/baptist"); ?>/' + item.id)
							.text(name);
						
						li.append(link);
						
						if(email){
							var emailText = '(' + email + ')';
							if(inactiveStatus){
								emailText += ' - ' + inactiveStatus;
							}
							li.append($('<span class="text-muted ml-2"></span>').text(emailText));
						}
						
						$('#baptismResultsList').append(li);
					});
					$('#baptismSearchResults').show();
					$('#baptismSearchNoResults').hide();
				} else {
					$('#baptismSearchResults').hide();
					$('#baptismSearchNoResults').show();
				}
			},
			error: function(){
				// Hide loading spinner on error
				$('#baptismSearchLoading').hide();
				$('#baptismSearchResults').hide();
				$('#baptismSearchNoResults').hide();
			}
		});
	}, 300);
});

// Hide results when clicking outside
$(document).on('click', function(e){
	if(!$(e.target).closest('#baptismSearchInput, #baptismSearchResults, #baptismSearchLoading, #baptismSearchNoResults').length){
		$('#baptismSearchResults').hide();
		$('#baptismSearchLoading').hide();
		$('#baptismSearchNoResults').hide();
	}
});

$( document ).on( "click", ".todoFun", function() {
	itemId = $(this).data('id');
	action = $(this).data('action');

	var r = confirm("Please press 'OK' to continue");
	var url = '<?= $canonical; ?>';

	
	if(r){

	
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
		var params = {
			action: action,
			itemId: itemId,
		};
		
		
		
		timer = setTimeout(function() {
			
			ajaxer = $.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: params,      
				success:function(data){
					
						 location.reload();
					
				}
			});
			
		 },600);	
	
	
	}
	
});	

// Rome date picker for visitor date range
if(typeof rome !== 'undefined'){
	var startDatePicker = null;
	var endDatePicker = null;
	
	$('#visitor_start_date').each(function(index) {
		var e = document.getElementById($(this).attr('id'));
		startDatePicker = rome(e, {
			time: false,
			inputFormat: "MM/DD/YYYY",
			outputFormat: "MM/DD/YYYY"
		});
		
		startDatePicker.on('data', function(value) {
			// Ensure only date is shown, no time
			if(value) {
				var dateOnly = value.split(' ')[0];
				// Convert YYYY-MM-DD to MM/DD/YYYY if needed
				if(dateOnly.match(/^\d{4}-\d{2}-\d{2}$/)) {
					var parts = dateOnly.split('-');
					dateOnly = parts[1] + '/' + parts[2] + '/' + parts[0];
				}
				if(dateOnly && dateOnly.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
					$(e).val(dateOnly);
				}
			}
		});
		
		startDatePicker.on('ready', function() {
			startDatePicker.dateValidator(function(date) {
				if(endDatePicker && endDatePicker.getDate()){
					return date <= endDatePicker.getDate();
				}
				return true;
			});
		});
	});
	
	$('#visitor_end_date').each(function(index) {
		var e = document.getElementById($(this).attr('id'));
		endDatePicker = rome(e, {
			time: false,
			inputFormat: "MM/DD/YYYY",
			outputFormat: "MM/DD/YYYY"
		});
		
		endDatePicker.on('data', function(value) {
			// Ensure only date is shown, no time
			if(value) {
				var dateOnly = value.split(' ')[0];
				// Convert YYYY-MM-DD to MM/DD/YYYY if needed
				if(dateOnly.match(/^\d{4}-\d{2}-\d{2}$/)) {
					var parts = dateOnly.split('-');
					dateOnly = parts[1] + '/' + parts[2] + '/' + parts[0];
				}
				if(dateOnly && dateOnly.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
					$(e).val(dateOnly);
				}
			}
		});
		
		endDatePicker.on('ready', function() {
			endDatePicker.dateValidator(function(date) {
				if(startDatePicker && startDatePicker.getDate()){
					return date >= startDatePicker.getDate();
				}
				return true;
			});
		});
	});
}

// Update visitor dates when button is clicked
$('#updateVisitorDates').on('click', function(){
	var startDate = $('#visitor_start_date').val();
	var endDate = $('#visitor_end_date').val();
	
	if(!startDate || !endDate){
		alert('Please select both start and end dates');
		return;
	}
	
	// Convert MM/DD/YYYY to YYYY-MM-DD for URL
	var startParts = startDate.split('/');
	var endParts = endDate.split('/');
	
	if(startParts.length === 3 && endParts.length === 3){
		var startFormatted = startParts[2] + '-' + startParts[0].padStart(2, '0') + '-' + startParts[1].padStart(2, '0');
		var endFormatted = endParts[2] + '-' + endParts[0].padStart(2, '0') + '-' + endParts[1].padStart(2, '0');
		
		// Reload page with new date parameters and scroll anchor
		var url = new URL(window.location.href);
		url.searchParams.set('visitor_start_date', startFormatted);
		url.searchParams.set('visitor_end_date', endFormatted);
		url.hash = 'newVisitorsCard';
		window.location.href = url.toString();
	} else {
		alert('Invalid date format');
	}
});

// Scroll to New Visitors card if hash is present or date parameters exist
$(document).ready(function(){
	var urlParams = new URLSearchParams(window.location.search);
	if(urlParams.has('visitor_start_date') && urlParams.has('visitor_end_date')){
		setTimeout(function(){
			var element = document.getElementById('newVisitorsCard');
			if(element){
				element.scrollIntoView({ behavior: 'smooth', block: 'start' });
				// Add a small offset to account for fixed headers
				window.scrollBy(0, -20);
			}
		}, 100);
	}
	
	// Also handle hash navigation
	if(window.location.hash === '#newVisitorsCard'){
		setTimeout(function(){
			var element = document.getElementById('newVisitorsCard');
			if(element){
				element.scrollIntoView({ behavior: 'smooth', block: 'start' });
				window.scrollBy(0, -20);
			}
		}, 100);
	}
});

// Allow Enter key to trigger update
$('#visitor_start_date, #visitor_end_date').on('keypress', function(e){
	if(e.which === 13){
		$('#updateVisitorDates').click();
	}
});


	


</script>