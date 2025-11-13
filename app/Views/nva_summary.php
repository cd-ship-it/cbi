             <!-- Begin Page Content -->
                <div class="container-fluid">

     <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">New Visitors Assimilation Since July 2021</h1>
                    </div>
                    <div><h5>Your action items</h5>
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-4 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                New Visitors</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <a class="nav-link" href="<?= (base_url('nva/table/'.$current_user_id.'/1')); ?>"><?= ($user_summary['new']); ?></a>                                            </div>
                                            </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Following up</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <a class="nav-link" href="<?= (base_url('nva/table/'.$current_user_id.'/2')); ?>"><?= ($user_summary['following']); ?></a>                                         </div>
                                            </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-4 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Attended Welcome Reception</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <a class="nav-link" href="<?= (base_url('nva/table/'.$current_user_id.'/3')); ?>"><?= ($user_summary['attended']); ?></a>                                          </div>
                                            </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Joined LG</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <a class="nav-link" href="<?= (base_url('nva/table/'.$current_user_id.'/4')); ?>"><?= ($user_summary['joined']); ?></a>                                            </div>
                                            </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        


                        
                    </div>
                    <hr>
   
 
 
           <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Assimilation summary per zone</h1>
                </div>

                    <!-- Content Row -->
                   

                        <!-- Content Column -->
						
						
						<?php 
						
						
						$current_campus="xxx";
						
						$zone_summary_array_values = array_values($zone_summary);
						
						$myIndex = 0;
						
						
						foreach($zone_summary as $key => $zone): 
						
						$piechart_option_title = $zone['campus'] . ' - ' . $zone['lifeStatus']; 
						
						$myIndex++;
						
						$next_campus = isset($zone_summary_array_values[$myIndex])?$zone_summary_array_values[$myIndex]['campus']:'xxx';
						
						
						if($current_campus != $zone['campus']){
							echo '<h5 class="mt-4">'.$zone['campus'].'</h5>';
							echo '<div class="row">';
							$current_campus = $zone['campus'];
						}
						
						
						?>
						
							<div class="col-lg-6 my-3">                           
								<div class="card shadow mb-4">
									<div class="card-header py-3">
										<h6 class="m-0 font-weight-bold text-primary"><?= $zone['campus']; ?></h6>
									</div>
									 <div class="card-body">
											<div id="piechart_<?= $key; ?>" style="height: 300px;"></div>
									</div>		
								</div>
							</div>						
						
						<?php 
						
						
						if($next_campus != $current_campus ){
							echo '</div><hr />';	
						}						
						
						
						
						
						
						endforeach; ?>
						
						
			
						
						
						
						
						
				 

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">New Visitors Monthly</h6>
            
                                </div>
								
								
								
										<div class="dropdown no-arrow">
											<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
												data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
												aria-labelledby="dropdownMenuLink">
												
												<a class="dropdown-item" href="<?= base_url('nva/areachart'); ?>">New Visitors Monthly</a>
												<a class="dropdown-item" href="<?= base_url('nva/areachart'); ?>">Joined a Life Group</a>
											</div>
										</div>  
								
								
								
								
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="embed-responsive embed-responsive-16by9">
									  <iframe class="embed-responsive-item" src="<?= base_url('nva/areachart'); ?>"></iframe>
									</div>
                                </div>
                            </div>
                        </div>

            
                    </div>
                    <!-- Content Row -->
     
 


                    <div class="row">
                        <div class="col-lg-12 mb-4">

                            <!-- Approach -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Assimilation Status Explained</h6>
                                </div>
                                <div class="card-body">
                                    <h4>Stage 1: New</h4>
                                    <p>A new visitor who visited Crosspoint pending for a Pastoral follow up</p>
                                    <h4>Stage 2: Following up</h4>
                                    <p>A new visitor has been followed up by either a pastor or a delegate</p>
                                    <h4>Stage 3: Attended Welcome Reception</h4>
                                    <p>The visitor has attended a Welcome Reception</p>
                                    <h4>Stage 4a: Joined a Life Group </h4>
                                    <p>A new visitor committed to a Life Group</p>
                                    <h4>Stage 4b: Closed</h4>
                                    <p>A new visitor decided not to join Crosspoint, moved out of the area, or has not been responeding for a period of time.</p>
                                </div>
                            </div>

                        </div>
                    </div> 
 

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
			
			
    <!-- Page level plugins -->  
    <script src="<?= base_url('assets/elearning/vendor/chart.js/Chart.min.js'); ?>"></script>	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	

	 <script type="text/javascript">
 
 
		
		
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(function(){
		  
		  
		<?php 
		
			foreach($zone_summary as $key => $zone): 
		
			$piechart_data_new = isset($zone[1]) ? $zone[1] : 0 ;
			$piechart_data_following = isset($zone[2]) ? $zone[2] : 0 ;
			$piechart_data_attended = isset($zone[3]) ? $zone[3] : 0 ;
			$piechart_data_joined = isset($zone[4]) ? $zone[4] : 0 ;
		
			$piechart_data = [['Stage','Counts'],['New '.$piechart_data_new,$piechart_data_new], ['Following up '.$piechart_data_following,$piechart_data_following],['Attended WR '.$piechart_data_attended,$piechart_data_attended],['Joined LG '.$piechart_data_joined,$piechart_data_joined]]; 
		
		
			$piechart_option_title = $zone['campus'] . ' - ' . $zone['lifeStatus']; 		
		
		?>  
		  
        var data = google.visualization.arrayToDataTable(<?= (json_encode($piechart_data, JSON_NUMERIC_CHECK)); ?>);
        var options = {
          title: '<?= $piechart_option_title; ?>',
          slices: {
            0: { color: 'red' },
            1: { color: 'blue' },
            2: { color: 'orange' },
            3: { color: 'green' },
            4: { color: 'blueviolet' },
          }
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart_<?= $key; ?>'));
        chart.draw(data, options);
		
		<?php endforeach; ?>		
		

      });		


    </script>
	
	
	
	
	
	
	
	
	
	
	
	
	
	