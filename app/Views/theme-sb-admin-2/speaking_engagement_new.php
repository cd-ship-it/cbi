
 


                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Speaking Engagement Application</h1>


<!-- Begin Page Content -->
                <div class="container-fluid">
				
					<?= (isset($ptoInformation)?$ptoInformation:''); ?>
					

                    <!-- Page Heading -->
                     <form  method="POST"  class="needs-validation ajaxSubmit" novalidate>
					 
					 <?php if($post_id=='assign'): ?>
					 
                        <div class="form-group">
                         <label for="assigned_id" class="form-label">Assign to a pastor*</label>
                         
							<select required class="form-control" name="assigned_id" id="assigned_id">
							<option value="">---</option>
							
							<?php 
							
								foreach($pastors as $pastor){
									
									
									echo '<option value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
								}
							
							?>
							
							
							
							
							</select>	

							 <div class="invalid-feedback">      Please select a valid item      </div>
                        </div>
						
						<div id="getPTO" class=""></div>
						
					<?php else: ?>
					
						<input type="hidden" name="assigned_id" value="<?= $logged_id; ?>" >
						
					<?php endif; ?>
						


						
                        <div class="form-group">
                         <label for="speakingDate" class="form-label">Speaking Date*</label>
                          <input required type="text" class="form-control" id="speakingDate" name="speakingDate"  value="">
						  <div class="invalid-feedback">       Please enter a valid value.      </div>
                        </div>
						
                        <div class="form-group">
                         <label for="speakingTimeStart" class="form-label">Speaking Start time*</label>
                          <input required type="text" class="form-control"  id="speakingTimeStart" name="speakingTimeStart"  value="">
						  <div class="invalid-feedback">       Please enter a valid value.      </div>
                        </div>	
						
                        <div class="form-group">
                         <label for="speakingTimeEnd" class="form-label">Speaking End time*</label>
                          <input required type="text" class="form-control"  id="speakingTimeEnd" name="speakingTimeEnd" value="">
						  <div class="invalid-feedback">       Please enter a valid value.      </div>
                        </div>					 
					 	
						
                        <div class="form-group">
                         <label for="venue" class="form-label">Venue*</label>
                          <input required type="text" class="form-control"  id="venue" name="venue" value="">
						  <div class="invalid-feedback">       Please enter a valid value.      </div>
                        </div>					 
					 	
						
                        <div class="form-group">
                         <label for="address" class="form-label">Address</label>
                          <input type="text" class="form-control"  id="address" name="address" value="">
                        </div>						 
					 	
						
                        <div class="form-group">
                         <label for="contact_info" class="form-label">Venue contact person name, phone and/or email</label>
                          <input type="text" class="form-control" id="contact_info" name="contact_info"  value="">
                        </div>					 
					 
					 
                        <div class="form-group">
                          <label for="notes">Notes</label>
                          <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>						

						
						<input type="hidden" name="action" value="new_speaking_engagement" >
											
						
						
					<?php if($post_id=='assign'): ?>
						<button class="btn btn-danger px-5" type="submit">Assign</button>
					<?php else: ?>
						<button class="btn btn-danger px-5 " type="submit">Apply</button>
					<?php endif; ?>	


				
							
						<p style="color:red;font-size: 18px;padding-top: 10px;" class="fmsg"></p>
					
					
						
						
			 				
							
                      </form>
 

					
                 
                </div>
                <!-- /.container-fluid -->


<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.min.css?v=001' type='text/css'/>


 




<script type="text/javascript">

$( document ).on( "change", "select#assigned_id", function() { 

	 
	var uid =  $(this).val();
	var action = 'getPTO';
	var url = '<?php echo base_url("xAdmin/speaking_engagement/details/0"); ?>';
	
	
	if(uid){
		$('#getPTO').html('Loading...');
	}else{
		$('#getPTO').html('');
		return;
	}
	
		if(timer) clearTimeout(timer); 
		if(ajaxer) ajaxer.abort(); 
		
timer = setTimeout(function() {				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: url,
			data: { uid: uid,   action:action},     
			success:function(data){   
				$('#getPTO').html(data);				
			}
		});
 },600);		

	 
	 
	 
});
 

  rome(document.getElementById('speakingDate'), {
	  time:false,
	  inputFormat: "MM/DD/YYYY",
	}); 

  rome(document.getElementById('speakingTimeStart'), {
	  date:false,
	  inputFormat: "h:mm a",
	}); 

  rome(document.getElementById('speakingTimeEnd'), {
	  date:false,
	  inputFormat: "h:mm a",
	});


(function() {
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
		 
		event.preventDefault();
		$('.ajaxSubmit .fmsg').html('').addClass('spinner-border spinner-border-sm');
		
		
        if (form.checkValidity() === false) {          
          event.stopPropagation();
		  $('.ajaxSubmit .fmsg').removeClass('spinner-border spinner-border-sm');

		  $('.ajaxSubmit .fmsg').html('Oops, please check your input and submit the form again.');
        }else{
			
				$('.ajaxSubmit .fmsg').addClass('spinner-border spinner-border-sm');

				
				if(timer) clearTimeout(timer); 
				if(ajaxer) ajaxer.abort(); 
				
				var params = $(this).serialize();
				
				
				
				timer = setTimeout(function() {
					
					ajaxer = $.ajax({
						dataType:'html',
						method: "POST",
						url: '<?= $canonical ?>',
						data: params,      
						success:function(data){
							if(data=='OK'){
								$('.ajaxSubmit .fmsg').html('Updated');
								window.location.href="<?= base_url('xAdmin/speaking_engagement'); ?>"; 
							}else{
								$('.ajaxSubmit .fmsg').html('Error');
							}
							$('.ajaxSubmit .fmsg').removeClass('spinner-border spinner-border-sm');
						}
					});
					
				 },600);			
			
		}
		
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>