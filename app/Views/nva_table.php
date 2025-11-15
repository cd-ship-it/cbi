                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800"></h1>
					
					
					<?php if(uri_string() == 'nva/table/'.$current_user_id.'/0'): ?>
					
					<h5>Your action items</h5>
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
					
					<?php endif; //($current_user_id) ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
							<?php if(isset($dateRange) && $dateRange): ?>
								<h6 class="m-0 font-weight-bold text-primary">New Visitors</h6>
								<small class="text-muted">Date Range: <?= $dateRange['startFormatted']; ?> - <?= $dateRange['endFormatted']; ?></small>
							<?php else: ?>
								<h6 class="m-0 font-weight-bold text-primary">New Visitors</h6>
							<?php endif; ?>
						</div>
                        <div class="card-body">
						
						
									
						<div class="row g-3 align-items-center pb-4">
						
						  <label class="col-auto"> Filter:  </label>
						  
					  
								 <div class="col-auto">
								<select id="filter_case_owner" class="custom-select">	
									<option value="">Choose case owner</option>
								
									<?php 
                                    
                                    foreach($caseOwnerObs as $item):  

                                        
                                        ?>
										<option <?= ($uid== $item['bid']?'selected':''); ?> value="<?= $item['bid']; ?>"><?= $item['name']; ?></option>
                                        
									<?php endforeach; ?>	

                                    <?php if($webConfig->checkPermissionByDes('is_senior_pastor')): ?>
                                        <option value="0">All</option>
                                    <?php endif; ?>


								</select> 	
								</div>
					 
								
								 <div class="col-auto">
								<select id="filter_status" class="custom-select">	
								
									 <option value="0">Choose assimilation stage</option>
									 
									<?php foreach($statusObs as $key => $item): ?>							
										<option  <?= ($stage_id== $key ?'selected':''); ?> value="<?= $key; ?>"><?= $item; ?></option>
									<?php endforeach; ?>		
								
								</select> 
								</div>
								
								<?php   ?>
								 <div class="col-auto">
								<select id="filter_peferred_lg" class="custom-select">	
								
									
									 
									<?php foreach($peferred_lg_obs as $key => $item): ?>							
										<option  <?= ($peferred_lg == $key ?'selected':''); ?> value="<?= $key; ?>"><?= $item; ?></option>
									<?php endforeach; ?>		
								
								</select> 
								</div>
								<?php    ?>
								
						</div>
				
						
						
						
						
						
						
                           <div class="table-responsive">
						   
						   
						   
						   
						   

                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Visited</th>
                                            <td>Name</td>
                                            <td>Case Owner</td>
                                            <td>Status</td>
                                            <td>Language Preference</td>
                                            <td>Campus</td>
                                            <td>Edit</td>
                                        </tr>
                                    </thead>
                              
                                    <tbody>
                                  
								  
								  
								  <?php foreach($entries as $row): ?>
								  
								  
                                        <tr>
                                            <th><?= ($row['visited']); ?></th>
                                            <td><?= ($row['name']); ?></td>
                                            <td><?= ($row['case_owner_name']); ?></td>
                                            <td><?= ($row['stage']); ?></td>
                                            <td><?= ($row['preferred_language']); ?></td>
                                            <td><?= ($row['campus']); ?></td>
                                            <td><a href="<?= (base_url('nva/detail/'.$row['id'])); ?>" class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a></td>
                                        </tr>
										
									  <?php endforeach; ?>		
										
                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
				
				
				
				
				
				
				
				
				
				
				
	<?php 
				
function strReplace($string,$start,$end)
{	
$strlen = mb_strlen($string, 'UTF-8');
    $firstStr = mb_substr($string, 0, $start,'UTF-8');
    $lastStr = mb_substr($string, -1, $end, 'UTF-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($string, 'utf-8') -1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
		
}


?>








	