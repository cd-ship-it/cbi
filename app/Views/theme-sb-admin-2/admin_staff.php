<?php








// --- Step 3: Recursive function to render the tree in HTML ---
function renderStaffTree($staffMembers, $level = 0) {
    if (empty($staffMembers)) {
        return;
    }

    echo '<ul>';
    foreach ($staffMembers as $staff) {
        $fullName = htmlspecialchars($staff['fName'] . ' ' . $staff['lName']);
        $id = htmlspecialchars($staff['id']);
        $supervisor = ($staff['supervisor'] !== null) ? htmlspecialchars($staff['supervisor']) : 'N/A';
        $hasChildren = !empty($staff['children']);
		$site = $staff['site'];

        echo '<li>';
        echo '<div class="staff-node ' . ($hasChildren ? 'has-children' : 'no-children') . '">';
        echo '<strong>' . $fullName . '</strong> (ID: ' . $id . ' | Site: '. $site .')';
        echo '<span class="details">';
        echo ' | <a taget="_blank" href="'.base_url('xAdmin/baptist/'.$id).'#pto_options">Edit</a>';
        echo '</span>';
        echo '</div>';

        // Recursively render children if they exist
        if ($hasChildren) {
            // Sort children before rendering for consistent order
            usort($staff['children'], function($a, $b) {
                $cmp = strcmp($a['lName'], $b['lName']);
                if ($cmp === 0) {
                    return strcmp($a['fName'], $b['fName']);
                }
                return $cmp;
            });
            renderStaffTree($staff['children'], $level + 1);
        }
        echo '</li>';
    }
    echo '</ul>';
}	







?>









<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 ><?= $pageTitle; ?></h1>			  
			<hr />	




<div id="adminList" style=" margin: 0 10px 20px 10px; ">

 
	
    <div id="staff-hierarchy-tree">
        <?php
        // Start rendering from the top-level (root) staff members
        renderStaffTree($finalRootNodes);
        ?>
    </div>



</div>




</div>
</div>
</div>
</div>








	
	
<style>


#pageDescription{margin: 0 10px 20px 10px;}


#addAdminDiv{position: fixed; width: 300px; background: #fff; height: 100%; top: 0; right: 0; padding: 20px; box-shadow: 11px -4px 12px 20px #000;z-index:9999;}

#closeTw{  cursor: pointer;     display: block;    margin-bottom: 10px;}
#closeTw:hover{text-decoration: underline;}

#addAdminDiv h3{ margin-bottom:10px;}
#addAdminDiv ul{     list-style: none;    padding: 20px 0 0 0;}
#addAdminDiv li{  padding: 5px 0;     text-transform: capitalize;}
#addAdminDiv li .function{ float:right;}

#adminList p { margin: 5px 0;}

tr.highlight{    background: #FFEB3B;}

.stuhtml{    display: flex;
    flex-wrap: wrap;     margin: 15px 0;}

.stuhtml div{min-width:150px; margin-right:20px;} 

@media screen and (max-width:375px ) {
	
	
}


        #staff-hierarchy-tree ul {
             
            padding-left: 25px; /* Indentation for levels */
            background-color: #fff;
             
            margin-bottom: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        #staff-hierarchy-tree ul ul {
            background-color: #f9f9f9;
			
            margin-top: 5px;
            margin-bottom: 5px;
            border-radius: 0 5px 5px 0;
            box-shadow: none;
        }
        #staff-hierarchy-tree li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        #staff-hierarchy-tree li:last-child {
            border-bottom: none;
        }
        .staff-node {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }
        .staff-node strong {
            color: #333;
        }
        .has-children strong {
            color: #00bcd4; /* Differentiate staff who are also supervisors */
        }
        .details {
            font-size: 0.85em;
            color: #666;
        }

</style>	
	




<script type="text/javascript">

var searchWrap;
$( document ).on( "click", "#addAdmin", function() {
	
	if(typeof(searchWrap) != "undefined" && searchWrap !== null) {
			return;
	}
	
		
	var input = document.createElement('input');
	
	var msg = document.createElement('p');
	
	var btS = document.createElement('button');
	btS.innerHTML = 'Search User';

	var btC = document.createElement('span');
	btC.id = 'closeTw';
	btC.innerHTML = 'x Close';
	
	var lists = document.createElement('ul');
	
	
	searchWrap = document.createElement('div');
	searchWrap.id = 'addAdminDiv';
	
	
	searchWrap.appendChild(btC);
	searchWrap.appendChild(input);
	searchWrap.appendChild(btS);
	searchWrap.appendChild(msg);
	searchWrap.appendChild(lists);
	
	
	document.getElementsByTagName("BODY")[0].appendChild(searchWrap);
	
	
	
	btC.addEventListener('click', function(){
		 close();
	});

	btS.addEventListener('click', function(){
		var query = input.value;
		
				$.ajax({
					dataType:'json',
					method: "POST",
					url: '<?php echo  $canonical; ?>',
					data: {	query:query, action:'searchUser'},      
					success:function(data){ 
						
						msg.innerHTML = '';
						lists.innerHTML = '';
						
						if(data.length==0){
							msg.innerHTML = 'Did not match any documents.';
						}else{
							
							
							data.forEach(function(item){
								 
								var li = document.createElement('li'); 
								var theUrl =  '<?= base_url("xAdmin/management/capabilities"); ?>/'+item.bid;
								li.innerHTML = item.name+' <a href="'+theUrl+'" data-name="'+item.name+'" data-uid="'+item.id+'" class="">Set capabilities</a>';
								
								lists.appendChild(li);
							});
							
							
						}	

						
					}
				});			
		
	});		
	
	function close(){
		searchWrap.remove();
		searchWrap = undefined;
	}	
});


 



$( document ).on( "change", "input.capability", function() {
	

	

	var uid = $(this).data('uid');
	var capability =  $(this).data('capability');
	var val =  $(this).prop("checked")?1:0;
	
	
	$('.msg').html('Loading...');
				
		ajaxer = $.ajax({
			dataType:'html',
			method: "POST",
			url: '<?php echo $canonical; ?>',
			data: { uid: uid, val: val, capability: capability, action:'capabilityChange'},     
			success:function(data){   
				$('.msg').html('');	console.log(data);				
			}
		});
		

	 
	 
	 
});

$( document ).on( "change", "select.sp_user", function() {
	

	
	var bid =  $(this).val();
	var url =  '<?= base_url("xAdmin/management/capabilities"); ?>/'+bid;
	
	location.href = url;
		

	 
	 
	 
});

$( document ).on( "click", ".removeAdmin", function() {
	
	var r = confirm("Please press 'OK' to continue");
	var url = '<?php echo  $canonical; ?>';	
	
	var uid = $(this).data('uid');	
	var me = $(this).parents('p')[0];
	
	if(r){
			$.ajax({
				dataType:'html',
				method: "POST",
				url: url,
				data: { uid: uid, action:'remove'},      
				success:function(data){		
					if(data=='OK'){ 
						$(me).remove();
					}											
				}
			});
	}	
});





</script>


