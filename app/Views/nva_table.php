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
							<h6 class="m-0 font-weight-bold text-primary">New Visitors
								<?php if(isset($campus) && $campus): ?>
									<span class="text-muted">- <?= ucwords($campus); ?></span>
								<?php endif; ?>
							</h6>
							<?php if(isset($dateRange) && $dateRange): ?>
								<div class="d-flex align-items-center mt-2">
									<label class="mb-0 text-muted small mr-2">Date Range:</label>
									<input type="text" id="date_range_start" class="form-control form-control-sm mr-1" style="width: 110px;" value="<?= $dateRange['startInput']; ?>" placeholder="Start Date">
									<span class="text-muted mr-1">-</span>
									<input type="text" id="date_range_end" class="form-control form-control-sm" style="width: 110px;" value="<?= $dateRange['endInput']; ?>" placeholder="End Date">
								</div>
							<?php else: ?>
								<div class="d-flex align-items-center mt-2">
									<label class="mb-0 text-muted small mr-2">Date Range:</label>
									<input type="text" id="date_range_start" class="form-control form-control-sm mr-1" style="width: 110px;" placeholder="Start Date">
									<span class="text-muted mr-1">-</span>
									<input type="text" id="date_range_end" class="form-control form-control-sm" style="width: 110px;" placeholder="End Date">
								</div>
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

        
        
        
        
        
        
           <!-- Desktop Table View -->
           <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Visited</th>
                            <td>Name</td>
                            <td>Phone</td>
                            <td>Email</td>
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
                            <td><?= isset($row['phone']) && $row['phone'] ? htmlspecialchars($row['phone']) : '-'; ?></td>
                            <td><?= isset($row['email']) && $row['email'] ? htmlspecialchars($row['email']) : '-'; ?></td>
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
            
            <!-- Mobile Card View -->
            <style>
                @media (max-width: 767px) {
                    .d-md-none {
                        margin-left: -15px;
                        margin-right: -15px;
                    }
                    .d-md-none .card {
                        margin-left: 0;
                        margin-right: 0;
                    }
                    .d-md-none .card-body {
                        padding-left: 0.75rem;
                        padding-right: 0.75rem;
                    }
                    .container,.container-fluid,.container-lg,.container-md,.container-sm,.container-xl {
                           padding-left: 0.5rem !important;
                        padding-right: 0.5rem !important;
                    }
                }
            </style>
            <div class="d-md-none">
                <?php foreach($entries as $row): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 font-weight-bold"><?= htmlspecialchars($row['name']); ?></h6>
                                <a href="<?= (base_url('nva/detail/'.$row['id'])); ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Visited</small>
                                    <strong><?= ($row['visited']); ?></strong>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Status</small>
                                    <strong><?= ($row['stage']); ?></strong>
                                </div>
                                <?php if(isset($row['phone']) && $row['phone']): 
                                    // Format phone number to US format (XXX) XXX-XXXX
                                    $phoneDigits = preg_replace('/[^0-9]/', '', $row['phone']);
                                    $formattedPhone = '';
                                    if(strlen($phoneDigits) == 10) {
                                        $formattedPhone = '(' . substr($phoneDigits, 0, 3) . ') ' . substr($phoneDigits, 3, 3) . '-' . substr($phoneDigits, 6, 4);
                                    } elseif(strlen($phoneDigits) == 11 && substr($phoneDigits, 0, 1) == '1') {
                                        $formattedPhone = '(' . substr($phoneDigits, 1, 3) . ') ' . substr($phoneDigits, 4, 3) . '-' . substr($phoneDigits, 7, 4);
                                    } else {
                                        $formattedPhone = $row['phone']; // Keep original if can't format
                                    }
                                ?>
                                <div class="col-12 mb-2">
                                    <small class="text-muted d-block">Phone</small>
                                    <div class="d-flex align-items-center">
                                        <strong class="mr-2"><?= htmlspecialchars($formattedPhone); ?></strong>
                                        <button type="button" class="btn btn-sm btn-outline-secondary p-1 copy-phone-btn" data-phone="<?= htmlspecialchars($phoneDigits); ?>" title="Copy phone number" style="line-height: 1; min-width: 28px; height: 28px;">
                                            <i class="fas fa-copy" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if(isset($row['email']) && $row['email']): ?>
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Email</small>
                                    <strong><?= htmlspecialchars($row['email']); ?></strong>
                                </div>
                                <?php endif; ?>
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Case Owner</small>
                                    <strong><?= ($row['case_owner_name']); ?></strong>
                                </div>
                                <?php if($row['preferred_language']): ?>
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Language</small>
                                    <strong><?= ($row['preferred_language']); ?></strong>
                                </div>
                                <?php endif; ?>
                                <?php if($row['campus']): ?>
                                <div class="col-6 mb-2">
                                    <small class="text-muted d-block">Campus</small>
                                    <strong><?= ($row['campus']); ?></strong>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if(empty($entries)): ?>
                    <div class="alert alert-info">No visitors found.</div>
                <?php endif; ?>
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

<script>
// Copy phone number to clipboard
$(document).on('click', '.copy-phone-btn', function(e) {
e.preventDefault();
var phone = $(this).data('phone');
var button = $(this);
var icon = button.find('i');

// Format phone for clipboard (digits only or formatted)
var phoneToCopy = phone;
if(phone.length == 10) {
phoneToCopy = '(' + phone.substring(0, 3) + ') ' + phone.substring(3, 6) + '-' + phone.substring(6, 10);
} else if(phone.length == 11 && phone.charAt(0) == '1') {
phoneToCopy = '(' + phone.substring(1, 4) + ') ' + phone.substring(4, 7) + '-' + phone.substring(7, 11);
}

// Copy to clipboard
if(navigator.clipboard && navigator.clipboard.writeText) {
navigator.clipboard.writeText(phoneToCopy).then(function() {
// Show success feedback
icon.removeClass('fa-copy').addClass('fa-check text-success');
setTimeout(function() {
icon.removeClass('fa-check text-success').addClass('fa-copy');
}, 2000);
}).catch(function(err) {
// Fallback for older browsers
copyToClipboardFallback(phoneToCopy, button, icon);
});
} else {
// Fallback for older browsers
copyToClipboardFallback(phoneToCopy, button, icon);
}
});

function copyToClipboardFallback(text, button, icon) {
var textArea = document.createElement('textarea');
textArea.value = text;
textArea.style.position = 'fixed';
textArea.style.left = '-999999px';
textArea.style.top = '-999999px';
document.body.appendChild(textArea);
textArea.focus();
textArea.select();

try {
document.execCommand('copy');
icon.removeClass('fa-copy').addClass('fa-check text-success');
setTimeout(function() {
icon.removeClass('fa-check text-success').addClass('fa-copy');
}, 2000);
} catch(err) {
alert('Failed to copy phone number');
}

document.body.removeChild(textArea);
}
</script>








