<?php
namespace bo\views\profile;

include '../../components/config.php';
?>

<div class="ui grid profile">
	<div class="four wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header">Passwort ändern</h4>
			<form id="changePassword" class="ui form" autocomplete="off">
			    <div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                
                <div class="ui success message">
                	<div id="successMessage"> Dein Passwort wurde erfolgreich geändert.</div>
                </div>
                
                <div id="input_secretNew1" class="required field">
                	<label>Neues Passwort</label>
                	<input 
                		type="password" 
                		id="secretNew1" 
                		name="secretNew1" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
                
                <div id="input_secretNew2" class="required field">
                	<label>Wiederholen</label>
                	<input 
                		type="password" 
                		id="secretNew2" 
                		name="secretNew2" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
                
                <button class="ui button" type="submit">Passwort ändern</button>
			</form>
        </div>
	</div>
	
	<div class="four wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header">Email Adresse ändern</h4>
			<form id="changeEmail" class="ui form" autocomplete="off">
			    <div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                                
                <div class="ui success message">
                	<p>Deine Email Adresse wurde geändert.</p>
                </div>
                
    			<div>Aktuelle Email-Adresse:</div>
        		<div id="actualEmail"><?php echo $user->getEmail(); ?></div>
        		<div class="ui divider"></div>
                
                <div id="input_emailNew" class="required field">
                	<label>Neue Email Adresse</label>
                	<input 
                		type="text" 
                		id="emailNew" 
                		name="emailNew" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
               
                <button class="ui button" type="submit">Speichern</button>
			</form>
        </div>
	</div>

	<div class="eight wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header">Kontakt</h4>
			<form id="sendMessage" class="ui form" autocomplete="off">
				<div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                
                <div class="ui success message">
                	<p>Deine Nachricht wurde veschickt. Wir werden dir so bald wie möglich antworten.</p>
                </div>
                
			    <div id="input_message" class="required field">
                	<label>Nachricht an das Koordinations-Team</label>
                	<textarea rows="4" id="message" name="message"></textarea>
                </div>
				<button class="ui button" type="submit">Senden</button>
			</form>
        </div>
	</div>
	
	<div class="four wide column">
    	<div class="ui raised segment">
    		<h4 class="ui header">Handynummer ändern</h4>
			<form id="changePhone" class="ui form" autocomplete="off">
			    <div class="ui error message">
            		<div id="errorMessage"></div>
                </div>
                                                
                <div class="ui success message">
                	<p>Deine Handynummer wurde geändert.</p>
                </div>

        		<div>Aktuelle Handynummer:</div>
        		<div id="actualPhone"><?php echo $user->getPhone(); ?></div>
        		<div class="ui divider"></div>
                
                <div id="input_phoneNew" class="required field">
                	<label>Neue Handynummer</label>
                	<input 
                		type="tel" 
                		id="phoneNew" 
                		name="phoneNew" 
                		onkeyup="formValidate.clearAllError();"
                	>
                </div>
               
                <button class="ui button" type="submit">Speichern</button>
			</form>
        </div>
	</div>
</div>

<script>
$("#changePassword").submit(function(event){ profile.changePassword(this.id); });
$("#changeEmail").submit(function(event){ profile.changeMail(this.id); });
$("#changePhone").submit(function(event){ profile.changePhone(this.id); });
$("#sendMessage").submit(function(event){ profile.sendMessage(this.id); });
</script>