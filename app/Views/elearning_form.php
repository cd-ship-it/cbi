   
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
					<h1>在上第一課前, 請先回答以下問題</h1>
					
							
		<p></p>
		</div>

                

                     <form  method="POST" action="<?= $canonical; ?>"  class="needs-validation ajaxSubmit" novalidate>
					 
                        <div class="form-group">
                          <label for="campus" class="form-label">你在哪個分堂聚會：</label>
                          <select class="form-select form-control " id="campus" name="campus" required>
							<option  value="">Choose</option>
							
							
						<?php foreach($campuses as $item): ?>
							
                         <option value='<?= $item; ?>'><?= $item; ?></option>
						
						<?php endforeach; ?>							
							
		 
                          </select>
						    <div class="invalid-feedback">      Please select a valid item      </div>
                        </div>	

						
                        <div class="form-group">
                          <label for="zPastor" class="form-label">你的Zone Pastor是誰：</label>



                                       <select class="form-select form-control " id="zPastor" name="zPastor" required>
										
													<option  value="">Choose</option>
		
													<?php 
													
														foreach($pastors as $pastor){
															echo '<option    value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
														}
													
													?>
													
													<option value="0">不知道</option>
								 
                                          </select>


						    <div class="invalid-feedback">      Please select a valid item      </div>
                        </div>					 
        
	
								<input   type="hidden" name="action" value="zPastor_update" >
								
                                <button class="btn btn-primary btn-user btn-block" type="submit">開始第一課</button>



						
					 				
							<p style="color:red;font-size: 18px;padding-top: 10px;" class="fmsg"></p>
							

					
							
                      </form>




