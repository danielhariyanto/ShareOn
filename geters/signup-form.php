<input id="name-register" type="text" placeholder="First Name" name="fname">
    <input id="surname-register" type="text" placeholder="Last Name" name="lname">
    <!--<input id="email-register" type="text" placeholder="Email" name="email">-->
    <input id="username-register" type="text" placeholder="Username" name="username">
    <input id="password-register" type="password" placeholder="Password" name="password">
    <p id="birthday-register">Birthday:</p>
    <select id="day-register">
      <?php
				for ($i=1; $i <=31 ; $i++) {
					if ($i<10) {
					echo "<option value=".$i.">0".$i."</option>";	
					} else {
					echo "<option value=".$i.">".$i."</option>";
					}
					
				}
				?>
    </select>
    <select id="month-register">
		<option value="01">January</option>
		<option value="02">February</option>
		<option value="03">March</option>
	    <option value="04">April</option>
	    <option value="05">May</option>
	    <option value="06">June</option>
	    <option value="07">July</option>
	    <option value="08">August</option>
	    <option value="09">September</option>
	    <option value="10">October</option>
	    <option value="11">November</option>
	    <option value="12">December</option>    
	</select>
    <select id="year-register">
      <?php for ($i = 2015; $i >= 1880; $i--){
				
				echo "<option value=".$i.">".$i."</option>";
				}
				?>
    </select>
    <div id="register-button"><img style="width: 100%;" src="<?=$web?>icons/sign-up.png" /><p class="text">Sign Up</p></div>