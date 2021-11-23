<div id="loginBody">
	<h1>Backoffice Hafengruppe Nord</h1>
	<form action="index.php" method="post">
		<div class="loginRow">
			<input 
				type="text" 
				name="username" 
				value="Benutzername" 
				onblur="if (this.value=='') this.value='Benutzername'" 
				onfocus="if (this.value=='Benutzername') this.value='';"
			/>
		</div>
		<div class="loginRow">
			<input 
				type="password" 
				value="Passwort" 
				name="secret" 
				onblur="if (this.value=='') this.value='Passwort'" 
				onfocus="if (this.value=='Passwort') this.value='';" 
			/>
		</div>
		<?php echo (isset($errMessage))?'<div id="errorMsg">' . $errMessage . '</div>':''; ?>
		<?php echo (isset($message))?'<div id="msg">' . $message . '</div>':''; ?>
		<div class="loginRow">
			<input class="inputButton" type="submit" value="Anmelden" />
		</div>
	</form>
</div>
<div id="newPW">
	<a href="#" onclick="loadPwReset();">Passwort vergessen</a>
</div>