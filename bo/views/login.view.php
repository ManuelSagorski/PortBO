<div id="loginBody">
	<h1>Backoffice Hafengruppe Nord</h1>
	<form id="loginForm" action="index.php" method="post">
		<div class="loginRow">
			<input 
				type="text" 
				name="username" 
				value="Benutzername" 
				onblur="if (this.value=='') this.value='Benutzername'" 
				onfocus="if (this.value=='Benutzername') this.value='';"
			/>
			<div id="msgUsername"></div>
		</div>
		<div class="loginRow">
			<input 
				type="password" 
				value="Passwort" 
				name="secret" 
				onblur="if (this.value=='') this.value='Passwort'" 
				onfocus="if (this.value=='Passwort') this.value='';" 
			/>
			<div id="msgSecret"></div>
		</div>
		<?php echo (isset($result['message']['error']))?'<div class="errorMsgForm">' . $result['message']['error'] . '</div>':''; ?>
		<?php echo (isset($result['message']['info']))?'<div class="msgForm">' . $result['message']['info'] . '</div>':''; ?>
		<div class="loginRow">
			<input class="inputButton" type="submit" onClick="validateLogin()" value="Anmelden" />
		</div>
	</form>
</div>
<div id="newPW">
	<a onclick="loadPwReset();">Passwort vergessen</a>
</div>