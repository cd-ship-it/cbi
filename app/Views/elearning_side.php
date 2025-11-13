        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">E-Learning</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">




 


 
            <li class="nav-item">
                <a class="nav-link collaps" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="bi bi-bookmark"></i>
                    <span>CBIF101</span>
                </a>
				
                <div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
				
							<?php
							
								foreach($class_sessions as $key => $item){									
									
									echo '<a class="collapse-item text-wrap scroll" href="#session_'.$item['id'].'">'.$item['title'].'</a>';
								}
							
							?>	


					
	               </div>
                </div> 
							
				
				</li>


				
 	
		
		





            <!-- Divider -->
            <hr class="sidebar-divider">
			
			
            <li class="nav-item">
                <a class="nav-link" href="<?=  base_url('elearning/table/'.$class_id.'/'.$logged_id);?>" >
                    <i class="fas fa-fw fa-table"></i>
                    <span>Go back</span>
                </a>
				
				
				
				</li>			
			
			
			
		 

            <!-- Heading -->
          

        </ul>
        <!-- End of Sidebar -->