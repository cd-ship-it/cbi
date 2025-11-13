<div class="row">

<div class="col-md-12 stretch-card">
	<div class="card shadow mb-4">
		<div class="card-body">
			<h1 ><?= $pageTitle; ?></h1>			  
			<hr />	








<a style=" margin: 0 10px 20px 10px; " href="javascript:void(0);" class="selected tditem">+New Serving position</a>


<div class="sesstion" id="s-searchResult">

<table id="results"> <thead><tr class="title"> <th width="50">ID</th>  <th>EN</th>  <th>繁體</th>  <th>简体</th> </tr></thead>
 <tbody>
<?php 

foreach($ministries as $key => $item){

	if($item['en']==$item['zh-Hant'] || $item['en']==$item['zh-Hans'] || $item['zh-Hant']==NULL || $item['zh-Hans']==NULL || $item['en']==NULL ){
		$class="highline tditem";
	}else{
		$class=" tditem";
	}
	
	if($key%2 != 0 ) $class .=" bg";
	
	echo '<tr data-mid="'.$item['id'].'" class="'.$class.'">';
	
	echo '<td>'.$item['id'].'</td>';
	echo '<td class="en">'.$item['en'].'</td>';
	echo '<td class="zh-Hant">'.$item['zh-Hant'].'</td>';
	echo '<td class="zh-Hans">'.$item['zh-Hans'].'</td>';
	
	echo '</tr>';
	
}

?>
 </tbody> </table>

</div>



</div></div></div></div>


 
	<script type='text/javascript' src='<?= base_url(); ?>/assets/rome/rome.min.js?v=001'></script>
	<link rel='stylesheet' href='<?= base_url(); ?>/assets/rome/rome.css?v=001' type='text/css'/>
	
	
	
	
	

<style>
.clearfix:after{content:" ";display:block;height:0;clear:both;visibility:hidden}
.clearfix{display:inline-block}

#results{width:100%;}
#results .title{    background: #9e9e9e;    text-transform: capitalize;}
#results td{padding: 2px; border: 1px solid #ddd; min-width: 100px;}
input,input:focus{ border: 1px #444 solid;}
tr.bg{ background: #fafafa; }
tr.selected{background: #fff38a; }

#wrapNode{background: #fff; position: fixed; width: 340px; left: 50%; margin-left: -150px; margin-top: 15%; padding: 25px; box-shadow: 0 0 20px 2px #000; z-index:999; }
#wrapNode input,#wrapNode select{ width: 286px;margin-bottom:15px; }
#wrapNode textarea{ width:90%; height:150px; margin-bottom:15px; }

#wrapNode span{  display: block; color: red; margin-top: 15px; }
#wrapNode h5{ margin-bottom:15px; font-size:18px;}

#wrapNode button{ margin:5px 5px 0 0;}
#wrapNode lable{display: block;}
#wrapNode #del{ float:right;}

td.selected{    background: #fff38a;}



@media screen and (max-width:575px ) {


}

}
</style>	
	
	
	
<script type="text/javascript">




var wrapNode;

$( document ).on( "click", ".tditem", function() {
	
	if(typeof(wrapNode) != "undefined" && wrapNode !== null) {
			return;
	}
	
	var theTr = $(this);
	var mid = $(this).data('mid');
	
	if($(this).hasClass('selected')){		
		
		var inputNodeEn = document.createElement('input');		
		inputNodeEn.value= $(this).children('.en').html()?$(this).children('.en').html():'';
		inputNodeEn.dataset.name =  'en';

		var inputNodeHans = document.createElement('input');		
		inputNodeHans.value= $(this).children('.zh-Hans').html()?$(this).children('.zh-Hans').html():'';
		inputNodeHans.dataset.name =  'zh-Hans';

		var inputNodeHant = document.createElement('input');		
		inputNodeHant.value= $(this).children('.zh-Hant').html()?$(this).children('.zh-Hant').html():'';
		inputNodeHant.dataset.name =  'zh-Hant';
		
		var innerNode =  document.createElement('div');	
		innerNode.id= 'innerNode';		
		
		var sbNode =  document.createElement('button');
		sbNode.innerHTML =   mid ? 'Update' : 'Submit';
		
		var cbNode =  document.createElement('button');	
		cbNode.innerHTML = 'Cancel';
		
		var dbNode =  document.createElement('button');	
		dbNode.innerHTML = 'Delete';
		dbNode.id='del';
		
		
		var title =  document.createElement('h5');
		title.innerHTML = mid ? '修改' : '添加';	
		
		var smMsg =  document.createElement('span');
		
		innerNode.appendChild(title);
		
		var lable =  document.createElement('lable');
		lable.innerHTML = 'En: ';			
		innerNode.appendChild(lable);
		innerNode.appendChild(inputNodeEn);


		
		lable =  document.createElement('lable');
		lable.innerHTML = '繁: ';			
		innerNode.appendChild(lable);
		innerNode.appendChild(inputNodeHant);
		
		
		lable =  document.createElement('lable');
		lable.innerHTML = '简: ';			
		innerNode.appendChild(lable);		
		innerNode.appendChild(inputNodeHans);
		
		innerNode.appendChild(sbNode);
		
		
		innerNode.appendChild(cbNode);
		if(mid){
			innerNode.appendChild(dbNode);
		}
		
		innerNode.appendChild(smMsg);
		
		wrapNode =  document.createElement('div');	
		wrapNode.id= 'wrapNode';
		
		wrapNode.appendChild(innerNode);
		
		
		cbNode.addEventListener('click', function(){
			 close();
		});	
		

		sbNode.addEventListener('click', function(){
			
			if(!inputNodeEn.value || !inputNodeHant.value || !inputNodeHans.value ) { 
				smMsg.innerHTML='Please enter valid data';
				return;
			}
			
			var data = {
				mid: mid,  
				enVal: inputNodeEn.value, 
				hantVal: inputNodeHant.value, 
				hansVal: inputNodeHans.value, 
				action: 'update'
			};
			 
			
				smMsg.innerHTML='Loading...';
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?php echo base_url("xAdmin/ministries"); ?>',
					data: data,      
					success:function(data){console.log(data);
						
							smMsg.innerHTML=data;
							if(data=='ok'){								
								 close(); 
								 location.reload();
							}else{
								smMsg.innerHTML=data;
							}

						
					}
				});		
			
		});			

		dbNode.addEventListener('click', function(){
			
			
			var data = {
				mid: mid,  
				action: 'remove'
			};
			 
			
				smMsg.innerHTML='Loading...';
				
				ajaxer = $.ajax({
					dataType:'html',
					method: "POST",
					url: '<?php echo base_url("xAdmin/ministries"); ?>',
					data: data,      
					success:function(data){
						
							smMsg.innerHTML=data;
							if(data=='ok'){								
								 close();
								 $(theTr).remove();
							}else{
								smMsg.innerHTML=data;
							}

						
					}
				});		
			
		});


		


		
		
		
		$('body').before(wrapNode);
	}else{
		$('table .selected').removeClass('selected');
		$(this).addClass('selected');
	}
	
	
	
	function close(){
		wrapNode.remove();
		wrapNode = undefined;
	}

	
	
	
	
});

	
	
	
	


</script>
