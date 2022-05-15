<div class="ui raised green segment" id="loginBody">
	<h1>Backoffice Hafengruppe Nord</h1>
	<p>
		Registrierung zur Mitarbeit
	</p>
	<form class="ui form register" id="registerForm" action="index.php" method="post">

		<div class="ui error message"></div>
		
    	<div class="field">
       		<input type="text" name="userFirstName" placeholder="Vorname">
    	</div>
    	<div class="field">
        	<input type="text" name="userSurname" placeholder="Nachname">
    	</div>
    	<div class="field">
        	<input type="text" name="userEmail" placeholder="Email Adresse">
    	</div>
    	<div class="field">
        	<input type="text" name="userPhone" placeholder="Telefonnummer mobil">
    	</div>

        <div class="ui horizontal divider">
        	Zugangsdaten
        </div>

    	<div class="field" id="fieldUsername">
       		<input type="text" id="username" name="userUsername" placeholder="Benutzername" onBlur="checkSelectedUsername();">
    	</div>
    	<div class="field">
        	<input type="password" name="password1" placeholder="Passwort">
    	</div>
    	<div class="field">
        	<input type="password" name="password2" placeholder="Passwort Wiederholung">
    	</div>

        <div class="field">
            <div class="ui checkbox">
                <input type="checkbox" name="dataprotection">
                <label>Ich stimme den <a class="markedLink" href="/bo/datenschutz.php" target="_blank">Datenschutzbedingungen</a> zu.</label>
            </div>
        </div>

		<input type="hidden" name="registerKey" value="<?php echo $_GET['code']; ?>" />

		<button class="ui primary button" id="registerButton">Registrieren</button>
	</form>
</div>