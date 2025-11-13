		
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
	

