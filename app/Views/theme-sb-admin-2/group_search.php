









<?php if(isset($capabilities['edit_class'])&&$capabilities['edit_class']): ?>

<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow ">
		<div class="card-body">
			<h1 class="card-title">Search groups</h1>  
		 

<div class="sesstion" id="s-searchBar">




<a href="<?= (base_url('xAdmin/group/new')); ?>">+New Group</a> <br /><br />

<form id="searchBox">

	<p>Keyword: <input class="form-control" type="text" value=""  name="query" id="query" /><small>*Search by Group Name, leader’s name, Group tags...</small></p><br />
	
	<p>Published or not: 	
		<select class="form-control" name="pub" id="pub">
			<option value="">---</option>
			<option value="1">True</option>
			<option value="0">False</option>
		</select> 	
	</p>	
	
	
	
	<p>Location:	
		<select class="form-control" name="site" id="site">
		<option value="">---</option>
		<?php
			foreach($sites as $siteCf){
				echo '<option   value ="'.$siteCf.'">'.$siteCf.'</option>';		
			}	
		?>
		</select> 
	</p>	
	
	<p>Pastor:	
		<select class="form-control" name="pastor" id="pastor">
		<option value="">---</option>
		<?php
			foreach($admins as $pastor){
				
				if($pastor['admin'] < 3) continue;
				
				 
				
				echo '<option   value="'.$pastor['bid'].'">'.$pastor['name'].'</option>';
			}
		?>
		</select> 
	</p>
	

<br />
	
	<p class="bts">
		<button type="reset" class="btn btn-secondary" id="btReset" value="Reset">Reset</button> | 
		<button type="button" class="btn btn-primary " onclick="search_submit()" id="btFilter">Search</button> | 
		<button type="button" class="btn btn-info " onclick="search_submit(1)" id="btAll">Display all</button>
	</p>


</form>









</div>

 

<div class="sesstion my-3" id="s-searchResult">
<?php

if(isset($returnHtml)&&$returnHtml) echo $returnHtml;

?>
</div>


</div>
</div>
</div>
</div>

<?php endif; //<?php if(isset($capabilities['edit_class']&&$capabilities['edit_class')):  ?>








 
			
<?php if(isset($usersGroups)&&$usersGroups): 
echo '<div class="row my-3">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4 ">
		<div class="card-body">'; 
echo '	<h1 class="card-title">Your Groups:</h1> ';
	echo '<div class="sesstion">';
	

					echo '<ul id="yourGroups">';
				
			
				foreach($usersGroups as $row){
					
					echo '<li>';
					
					echo '<h3><a href="'.base_url('xAdmin/group/'.$row['id']).'">'.$row['name'].'</a></h3>';
					echo '<p>Description: '.$row['description'].'</p>';
					echo '<p>Tags: '.$row['tag_value'].'</p>';
					echo '<p>Campus:'.$row['campus'].' | 	Leader:'.$row['leader_name'].' | Pastor:'.$row['pastor_name'].'</p>';
					echo '<p>Publish: '.($row['publish']?'Yes':'No').'</p>';
	
					echo '</li>';
					
				}
			
				echo '</ul>';
				
				echo '</div>  </div>
</div>
</div>
</div>';

  endif;  // isset($usersGroups)&&$usersGroups?>
  
  



 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>



<style>



#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}

#results div div{ padding:10px; border-left: 1px solid #ccc;}
#results p { font-size:14px;line-height:18px;margin: 10px 0;}

.sortable th {     cursor: pointer;}

#s-searchBar,#s-searchResult{    margin: 20px 10px;}


#searchBox .bts{margin-top:10px;}
#searchBox p{margin:5px 0;}

#searchBox .hide{ display:none;}

tr.signin a.profile{ background:url(<?= base_url(); ?>/assets/images/member.png) no-repeat 0 2px;     padding-left: 20px;}

#btFilter{ padding-right:40px;padding-left:40px;}

.x-results{margin-bottom:10px;}

#wrapNode{background: #fff; position: fixed; width: 300px; left: 50%; margin-left: -150px; margin-top: 15%; padding: 25px; box-shadow: 0 0 20px 2px #000; }
#wrapNode input,#wrapNode select{ width:100%; margin-bottom:15px; }
#wrapNode textarea{ width:100%; height:150px; margin-bottom:15px; }

#wrapNode span{  display: block; color: red; }

.list td.selected{    background: #fff38a;}



.x-results a{padding: 0 5px; }
.x-results a.current{background: #ccc; }
.x-results a.current:after{ content:' ▼'; }


.hidenBox{ display:none;}

.hidenBox ul,.hidenBox p{ margin: 0 0 10px 0;
    padding: 0;}
.hidenBox li{        padding-bottom: 10px;
    padding-right: 20px;}
.hidenBox h3{    margin: 10px 0;}	

  #returnMinistries li{   text-decoration: underline;
    color: blue;
    cursor: pointer;
  }
  
#returnMinistries li:hover{ text-decoration: none;}  

#returnMinistries li:before{ content:'└ '; }

#mySDiv li, #myHDiv li, #siteDiv li{
	
    display: inline-block;
}

 
#searchGroupResults, #yourGroups{ padding:0;}
#searchGroupResults li, #yourGroups li{     margin-top: 30px; list-style: none; border-top: 1px solid #eee;     padding-top: 30px;}

@media screen and (max-width:575px ) {
	
	#mySDiv li, #myHDiv li{
	    width: 90%;
    display: inline-block;
}

}
</style>

<script type="text/javascript">


var timer  = null; 
var ajaxer  = null; 




	
  function search_submit(displayAll=0) {

			if(displayAll){frest();}
				
			query = $('#query').val().trim();	
			
			site = $('#site').val();		
			pub = $('#pub').val();			
			pastor = $('#pastor').val();			
			
			if(!query && !displayAll && !site && !pub && !pastor) return;
			
			
			
			if(timer) clearTimeout(timer); 
			if(ajaxer) ajaxer.abort(); 
		  
			params = {
				query: query,  
				site: site,  
				pub: pub,  
				pastor: pastor,  
				displayAll: displayAll,
				action: 'search'
			};
			 
			timer = setTimeout(function() {
				$('#s-searchResult').html('Loading...');
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?php echo $canonical; ?>',
					data: params,      
					success:function(data){
						
							$('#s-searchResult').html(data);

						
					}
				});
				
			 },600);	
			 

    }
	
  






$( document ).on( "click", "#btReset", function() {
	event.preventDefault();
		frest();	
});







function frest(){
		$( '#query' ).val('');	
		$( '#pub' ).val('');
		$( '#site' ).val('');	
		$( '#pastor' ).val('');	
}


</script>
