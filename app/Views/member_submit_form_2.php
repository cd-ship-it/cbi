	
	<p>Date of your baptism 接受浸禮日期 (*): <input value="<?= $user['baptizedDate']; ?>"  name="baptizedDate" id="baptizedDate" class="dateInput" value="" /></p>	
	
	
	


<div style=" background: #eee; padding: 5px; margin-bottom:20px;" id="baptismCertificateFile">

	
		<p>Please upload your Baptism Certificate here if you are from another church 上傳您的浸禮證書: <input <?= ($spBid?'disabled':''); ?> data-bid="<?= $bid; ?>" type="file" id="baptismCertificate" /> <br>

		<small style="color:gray;font-size:11px">supported file format(doc, ppt, pdf, docx, txt, pptx, jpg, jpeg, png)</small></p>	
	
		<div id="baptismCertificateArea">
		<?php 
		
		if($user['baptismCertificate']){
				
				
					
					echo '<p><a target="_blank" href="'. $user['baptismCertificate'] .'">'.basename($user['baptismCertificate']).'</a>';
					
					if(!$spBid) echo ' <span class="rmBaptismCertificate">x Remove</span>'; 
					
					echo '</p>';
					
				
			
		}elseif($spBid && $webConfig->checkPermissionByDes(['is_pastor','is_admin'])){
			
			echo '<p><a target="_blank" href="'.(base_url('member/baptismCertificate?bid='.$bid)).'" class="invite">(No Baptism Certificate on file)</a></p>';
			
		}
		
		?>
		</div>
		
		<p class="baptismCertificateMsg" style=" color: red; "></p>
		</div>

		
	
	<p>Name of your previous church you attended regularly before coming to Crosspoint 來匯點之前定期參加的教會名稱:<br />
	<input value="<?= $user['nopc'] ; ?>" name="nopc" id="nopc" /></p>
	

	
	<p>Site 聚會地點 (*):	
		<select name="site" class="required" title="Site 聚會地點"  id="site">
		<option value="">---</option>
		<?php
			foreach($webConfig->sites as $siteCf){
				echo '<option '.($siteCf==$user['site']?'selected':'').' value ="'.$siteCf.'">'.$siteCf.'</option>';		
			}	
		?>
		</select> 
	</p>
	
	

		
		
	<div class="freebirdFormviewerViewNumberedItemContainer"><div role="listitem" class="freebirdFormviewerViewItemsItemItem" jsname="ibnC6b" jscontroller="RGrRJf" data-item-id="728492983"><div class="freebirdFormviewerViewItemsSectionheaderTitle freebirdCustomFont" role="heading" aria-level="2">Membership Covenant 會員約章</div><div class="freebirdFormviewerViewItemsSectionheaderDescriptionText">     Having received Christ as my Lord and Savior and been baptized or will soon be baptized by immersion, and being in agreement with Crosspoint’s consitution and bylaws, statement of faith, strategy, and governing structure, I now feel led by the Holy Spirit to unite with the Crosspoint church family. In doing so, I commit myself to God and to the other members to do the following:<br><br>
	
	
	1. I will strengthen the unity of my church<br>
	- By loving one another through Life group participation<br>
	- By refusing to gossip<br>
	- By following the leaders<br><br>
	
	
	
	2.	I will share the responsibility of my church (1 Thessalonians 1:2; Luke 14:23; Romans 15:7)<br>
	- By praying for its growth<br>
	- By inviting the unchurched to attend<br>
	- By warmly welcoming those who visit.<br><br>
	
	
	3.	I will serve the ministry of my church (1 Peter 4:10; Ephesians 4:11-12; Philippians 2:3-4, 7)<br>
	- By discovering my gifts and talents<br>
	- By being equipped to serve by my pastor(s)/leaders<br>
	- By developing a servant’s heart<br><br>
	
	
	4.	I will support the testimony of my church (Hebrews 10:25; Philippians 1:27; 1 Corinthians 16:2; Leviticus 27:30)<br>
	- By attending faithfully<br>
	- By living a godly life<br>
	- By giving regularly<br><br>
	
	
	5. I will grow into spiritual maturity for building up my church by (Ephesians 4:11-17, Romans 12:2, Matthew 28:18-20):<br> 
	- Joining discipleship groups.<br> 
	- Learning more about the Bible and our faith.<br> 
	- Discipling others as Jesus commanded.<br><br>
	
	
	
	
	
	
	
	
	
	Constitution and Bylaws: <a href="https://www.google.com/url?q=http://www.crosspointchurchsv.org/Bylaws&amp;sa=D&amp;ust=1601090572765000&amp;usg=AFQjCNEmPW_pZcoY1ItPc3KOcek7FjSIQg">www.crosspointchurchsv.org/Bylaws</a><br><br>
	
	

	
	
	
	
	
	
	
	
	
	
	</div><div jsname="XbIQze" class="freebirdFormviewerViewItemsItemErrorMessage" id="i.err.728492983" role="alert"></div></div></div>	
	
	
		<p>I HEREBY ACKNOWLEDGE THAT I HAVE READ THIS COVENANT, UNDERSTAND IT AND AGREE WITH CROSSPOINT TO CARRY OUT THE ABOVE COMMITMENT. 我在此承認我已經閱讀了這份契約及理解內容，並同意加入匯點教會履行上述承諾。 (*):<br />
		
		
		
	  <input type="checkbox" id="iAgree" <?= ($spBid?'checked':''); ?>  name="iAgree" value="">
  <label for="iAgree"> I Agree 我同意</label></p>	
	
		<?php if(!$spBid): ?>

			
			<p class="bts">
			
			<input type="hidden" name="rmBaptismCertificate" value="0" />
			<input type="hidden" name="action" value="profileUpdate" />
			<input type="hidden" class="required" title="會員約章知情並同意" id="iAgreer" name="iAgreer" value="" />
			<input type="submit"  id="btSubmit" value="<?= $fsubmitLable; ?>" />
			
			</p>
			<p class="fmsg"></p>

		<?php endif; ?>	
		
		</form>
		