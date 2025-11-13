<ul class="navbar-nav ml-auto">



                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
						
						
						<?php  if($isLogin): ?>
						
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $userName; ?></span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                            </a>
							
						<?php  else: ?>	
						
	                        <a class="nav-link " href="<?= (base_url('?redirect='.rawurldecode($canonical))); ?>">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Login</span>
                                <img class="img-profile rounded-circle" src="<?= $userPicture; ?>">
                            </a>					
						
						<?php  endif; ?>	
						
						
						
						<?php  if($isLogin): ?>
						
						
						
					
						
						
						
						
						
						
						
						
						
							
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                               
							   <a class="dropdown-item" target="_blank" href="<?= base_url('member/profile'); ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    My Profile
                                </a>
								
                              

								
							   <a class="dropdown-item" target="_blank" href="<?= base_url('member/classes'); ?>">
                                    <i class="bi bi-journal-bookmark mr-2 text-gray-400"></i>
                                    My Classes
                                </a>
								
                    

								

								
                                <div class="dropdown-divider"></div> 

								
							   <a class="dropdown-item"  href="<?= base_url('?logout=1'); ?>">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw  mr-2 text-gray-400"></i>
                                    Logout
                                </a>
								
         
								
								
                            </div>
							
						<?php  endif; ?>		
							
							
							
							
							
							
                        </li>

                    </ul>