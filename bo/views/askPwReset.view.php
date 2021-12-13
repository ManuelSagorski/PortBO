<div id="loginBody">
	<h1>Backoffice Hafengruppe Nord</h1>
	<div id="pwResetBody">
    	<div>
    		Du hast dein Passwort vergessen? Bitte gebe deinen Benutzernamen ein und klicke auf senden. 
    	</div>
    	<form id="sendLink">
    		<div class="loginRow">
    			<input 
    				type="text" 
    				name="usernameReset" 
    				value="Benutzername" 
    				onblur="if (this.value=='') this.value='Benutzername'" 
    				onfocus="if (this.value=='Benutzername') this.value='';" 
    			/>
    			<div id="msgUsernameReset"></div>
    		</div>
    		<?php echo (isset($msg['error']))?'<div id="errorMsg">' . $msg['error'] . '</div>':''; ?>
    		<div class="loginRow">
    			<input class="inputButton" type="submit" value="senden" />
    		</div>
    	</form>
    	<script>
        	$("#sendLink").submit(function(event){ event.preventDefault(); askPwResetLink(); });
        </script>
    </div>
</div>