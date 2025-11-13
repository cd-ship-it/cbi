		
		<h1>Crosspoint Membership Record and Covenant 匯點會員紀錄和約章<?= (isset($disName)?' ('.$disName.')':'');?></h1>
		
		
		<form action="<?= $memberUrl; ?>" method="post" class="rForm" id="baptistForm"onkeydown="if(event.keyCode==13){return false;}">

	<p>First Name (*): <input class="required" title="First Name" type="text" value="<?= $user['fName']; ?>" name="fName" id="fName" /></p>
	
	<p>Middle Name: <input title="Middle Name" type="text" value="<?= $user['mName']; ?>" name="mName" id="mName" /></p>
	
	<p>Last Name (*): <input class="required" title="Last Name" type="text" value="<?= $user['lName']; ?>" name="lName" id="lName" /></p>
	
	<p>Chinese Name: <input  title="Chinese Name" type="text" value="<?= $user['cName']; ?>" name="cName" id="cName" /></p>
	
	<p>
	Gender (*):	
	<select name="gender" class="required" title="Gender" id="gender">
	<option value ="">---</option>
	<option <?= ($user['gender']=='M'?'selected':'') ;?> value ="M">Male</option>
	<option <?= ($user['gender']=='F'?'selected':'') ;?> value ="F">Female</option>
	</select>
	</p>

	<p>
	Marital Status (*):	
	<select name="marital" class="required" title="Marital Status" id="marital">
	<option value ="">---</option>
	<option <?= ($user['marital']=='M'?'selected':'') ;?> value ="M">Married</option>
	<option <?= ($user['marital']=='S'?'selected':'') ;?> value ="S">Single</option>
	</select>
	</p>

	
	
	<p>Birth Date (*): <input value="<?= $user['birthDate']; ?>" name="birthDate" id="birthDate" class="dateInput required" title="Birth Date" /></p>
	
	<p>Home Address (*): <input class="required" title="Home Address" value="<?= $user['homeAddress']; ?>" name="homeAddress" id="homeAddress" /></p>
	
	<p>City (*): <input class="required" title="City" value="<?= $user['city'] ; ?>" name="city" id="city" /></p>
	
	<p>Zip code (*): <input class="required" title="Zip code" value="<?= $user['zCode'] ; ?>" name="zCode" id="zCode" /></p>
	
	<p>Home Phone (*): <input class="required" title="Home phone" value="<?= $user['hPhone'] ; ?>" name="hPhone" id="hPhone" /></p>
	
	<p>Mobile Phone (*): <input class="required" title="mobile phone" value="<?= $user['mPhone'] ; ?>" name="mPhone" id="mPhone" /></p>
	
	<p>Email (*): <input class="required email" value="<?= $user['email'] ; ?>" name="email" title="Email" id="email" /></p>
	
	<p>Occupation: <input value="<?= $user['occupation'] ; ?>" name="occupation" id="occupation" /></p>
	
	<div id="familyDiv">Family member:<br /> <textarea id="family" name="family" rows="4" cols="50"><?= $user['family']; ?></textarea></div>		
	
	<p></p>
	
	<p>Are you sure you are already a born-again Christian? 你是否肯定自己是一個重生得救的基督徒？ (*):<br />	
		<select name="bornagain" id="bornagain" class="required" title="Are you sure you are already a born-again Christian?">
		<option value="">---</option>
		<option <?= ($user['bornagain']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($user['bornagain']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>
	
	<p>Are you attending a Crosspoint life group regularly? 你現今是否固定參加匯點的「生命小組」？ (*):<br />	
		<select name="attendingagroup" id="attendingagroup" class="required" title="Are you attending a Crosspoint life group regularly?">
		<option value="">---</option>
		<option <?= ($user['attendingagroup']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($user['attendingagroup']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>	
	
	
	<p>If yes, what is the name of your life group or your life group leader? 若有，你現今在匯點參加的小組名稱或組長名字是甚麼？:<br />
	<input value="<?= $user['lifegroup'] ; ?>" name="lifegroup" id="lifegroup" /></p>
	
	
	<p>Were you baptized or officially accepted as member of a previous church? 你是否已受浸或在一間教會擁有過會籍？ (*):<br />	
		<select name="baptizedprevious" id="baptizedprevious" class="required" title="Were you baptized or officially accepted as member of a previous church?">
		<option value="">---</option>
		<option <?= ($user['baptizedprevious']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($user['baptizedprevious']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>	
	
	
	<p>If Yes, name of the church you were baptized or accepted as a member. 若有，曾接受浸禮或擁有會籍的教會名稱 (*):<br />
	<input value="<?= $user['nocb'] ; ?>" name="nocb" id="nocb" /></p>	
	
	<p>If you were baptized, were you baptized by immersion? 若曾接受水禮，接受的水禮是否全身入水的浸禮？:<br />	
		<select name="byImmersion" id="byImmersion">
		<option value="">---</option>
		<option <?= ($user['byImmersion']=='1'?'selected':'') ;?> value="1">Yes 是</option>
		<option <?= ($user['byImmersion']=='0'?'selected':'') ;?> value="0">No 不是</option>
		</select>
	</p>	
	
	
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
	
	

		
		
	<div class="freebirdFormviewerViewNumberedItemContainer"><div role="listitem" class="freebirdFormviewerViewItemsItemItem" jsname="ibnC6b" jscontroller="RGrRJf" data-item-id="728492983"><div class="freebirdFormviewerViewItemsSectionheaderTitle freebirdCustomFont" role="heading" aria-level="2">Membership Covenant 會員約章</div><div class="freebirdFormviewerViewItemsSectionheaderDescriptionText">     Having received Christ as my Lord and Savior and been baptized or will soon be baptized by immersion, and being in agreement with Crosspoint’s consitution and bylaws, statement of faith, strategy, and governing structure, I now feel led by the Holy Spirit to unite with the Crosspoint church family. In doing so, I commit myself to God and to the other members to do the following:<br><br>1.	I will protect the unity of my church (Romans 15:5, 19; 1 Peter 1:22; Ephesians 4:29; Hebrews 13:17)<br>       - By acting in love toward other members<br>       - By refusing to gossip<br>       - By following the leaders<br><br>2.	I will share the responsibility of my church (1 Thessalonians 1:2; Luke 14:23; Romans 15:7)<br>       - By praying for its growth<br>       - By inviting the unchurched to attend<br>       - By warmly welcoming those who visit.<br><br>3.	I will serve the ministry of my church (1 Peter 4:10; Ephesians 4:11-12; Philippians 2:3-4, 7)<br>       - By discovering my gifts and talents<br>       - By being equipped to serve by my pastor(s)/leaders<br>       - By developing a servant’s heart<br><br>4.	I will support the testimony of my church (Hebrews 10:25; Philippians 1:27; 1 Corinthians 16:2; Leviticus 27:30)<br>       - By attending faithfully<br>       - By living a godly life<br>       - By giving regularly<br><br>Constitution and Bylaws: <a href="https://www.google.com/url?q=http://www.crosspointchurchsv.org/Bylaws&amp;sa=D&amp;ust=1601090572765000&amp;usg=AFQjCNEmPW_pZcoY1ItPc3KOcek7FjSIQg">www.crosspointchurchsv.org/Bylaws</a><br><br>我已經接受基督為我生命的主和救主，並已或準備接受浸禮，並且同意匯點的會章 和章程、信仰的聲明、事工策略和管理架構，現在我感覺在聖靈的引導下加入匯點教會的大家庭。當成為會友後，我向上帝和其他成員承諾:<br><br>1.	我會保護我教會的合一(羅馬書15:5,19；彼前書1:22；以弗所書4:29；希伯來書13:17)<br>       - 以愛來對待其他會友<br>       - 拒絕造謠<br>       - 跟隨領導<br><br>2.	我要分擔教會的責任(帖撒羅尼迦前書1:2; 路加福音14:23; 羅馬書15:7)<br>       - 為教會的成長祈禱<br>       - 邀請未信者參加匯點聚會<br>       - 熱情歡迎來訪的新朋友。<br><br>3.	我要服侍我的教會(彼得前書4:10; 以弗所書4:11-12; 腓立比書2:3-4,7)<br>       - 通過發現我的恩賜和才能<br>       - 接受我的牧者/領袖提供的裝備<br>       - 培養僕人的服侍態度<br><br>4.	我要支持我教會的見證(希伯來書10:25; 腓立比書 1:27; 哥林多前書16:2; 利未記27:30)<br>       - 投入出席教會的活動<br>       - 過敬虔的信徒生活<br>       - 定期奉獻<br><br>會章和章程: <a href="https://www.google.com/url?q=http://www.crosspointchurchsv.org/Bylaws&amp;sa=D&amp;ust=1601090572766000&amp;usg=AFQjCNFPlMevYM2FUv2dko3fDQACFjhBmA">www.crosspointchurchsv.org/Bylaws</a></div><div jsname="XbIQze" class="freebirdFormviewerViewItemsItemErrorMessage" id="i.err.728492983" role="alert"></div></div></div>	
	
	
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
		
