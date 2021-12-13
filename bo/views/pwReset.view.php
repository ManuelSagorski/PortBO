<div id="loginBody">
	<h1>Backoffice Hafengruppe Nord</h1>
	<div>Bitte wähle ein neues Passwort:</div>
	<form id="userNewPW">
		<div class="loginRow">
			<input type="password" name="secretNew1" />
			<div id="msgSecretNew1"></div>
		</div>
		<div class="loginRow">
			<input type="password" name="secretNew2" />
			<div id="msgSecretNew2"></div>
		</div>
		<input type="hidden" name="userID" value="<?php echo $_GET['id']; ?>" />
		<input type="hidden" name="userCode" value="<?php echo $_GET['code']; ?>" />
		<div class="loginRow">
			<input class="inputButton" type="submit" value="Passwort ändern" />
		</div>
	</form>
	<script>
    	$("#userNewPW").submit(function(event){ event.preventDefault(); pwReset() });
    </script>
</div>