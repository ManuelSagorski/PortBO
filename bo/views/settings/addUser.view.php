<?php
namespace bo\views\settings;

use bo\components\classes\user;
use bo\components\types\languages;
use bo\components\classes\port;

include '../../components/config.php';

if(isset($_GET['id'])) 
    $userToEdit = user::getSingleObjectByID($_GET['id']);
$editMode = !empty($userToEdit);
?>

<form id="addUser" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>
    
    <div class="two fields">
        <div id="input_userFirstName" class="required field">
        	<label>Vorname</label>
        	<input 
        		type="text" 
        		id="userFirstName" 
        		name="userFirstName" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo($editMode)?$userToEdit->getFirstName():''; ?>"
        	>
        </div>

        <div id="input_userSurname" class="required field">
        	<label>Nachname</label>
        	<input 
        		type="text" 
        		id="userSurname" 
        		name="userSurname" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo($editMode)?$userToEdit->getSurname():''; ?>"
        	>
        </div>    
    </div>

    <div class="two fields">
        <div id="input_userUsername" class="field">
        	<label>Benutzername</label>
        	<input 
        		type="text" 
        		id="userUsername" 
        		name="userUsername" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo($editMode)?$userToEdit->getUsername():''; ?>"
        	>
        </div>

        <div id="input_userPhone" class="required field">
        	<label>Handynummer</label>
        	<input 
        		type="text" 
        		id="userPhone" 
        		name="userPhone" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo($editMode)?$userToEdit->getPhone():''; ?>"
        	>
        </div>    
    </div>

    <div class="two fields">
        <div id="input_userEmail" class="required field">
        	<label>Email Adresse</label>
        	<input 
        		type="text" 
        		id="userEmail" 
        		name="userEmail" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo($editMode)?$userToEdit->getEmail():''; ?>"
        	>
        </div>
    
        <div id="input_userLevel" class="field">
        	<label>Benutzerlevel</label>
        	<select id="userLevel" name="userLevel" class="ui fluid dropdown">
        	<?php foreach (user::$userLevel as $levelID=>$level) { ?>
    			<option 
    				value="<?php echo $levelID; ?>"
    				<?php if($editMode){echo ($userToEdit->getLevel() == $levelID)?' selected':'';} ?>
    			><?php echo $level; ?></option>
    		<?php } ?>
        	</select>
        </div>
    </div>
    
    <div id="input_userLanguage" class="field">
    	<label>Sprachen</label>
    	<select id="userLanguage" name="userLanguage" multiple="" class="ui fluid dropdown">
    	<?php foreach (languages::$languages as $id=>$language) { ?>
			<option value="<?php echo $id; ?>"><?php echo $language; ?></option>
		<?php } ?>
    	</select>
    </div>

    <div id="input_userPort" class="field">
    	<label>Zugewiesene Häfen</label>
    	<select id="userPort" name="userPort" multiple="" class="ui fluid dropdown">
    	<?php foreach (port::getMultipleObjects() as $port) { ?>
			<option value="<?php echo $port->getID(); ?>"><?php echo $port->getName(); ?></option>
		<?php } ?>
    	</select>
    </div>

    <?php if(!$editMode) { ?>
    <div id="input_userSendInfo" class="field">
    	<label>Email mit Benutzerdaten an den Verkündiger schicken</label>
		<input 
			type="checkbox" 
			id="userSendInfo" 
			name="userSendInfo" 
		>
    </div>
    <?php } ?>

	<button class="ui button" type="submit">Speichern</button>
	<?php if($editMode && $userToEdit->getLevel() > 1) { ?>
		<button class="ui button" onClick="settings.sendInvitationMail(<?php echo $userToEdit->getID(); ?>)">Einladungsmail</button>
	<?php } ?>
	<?php if($editMode && empty($userToEdit->getPlanningID()) && $userToEdit->getLevel() > 3) { ?>
		<button class="ui icon button" onClick="settings.showAddKalender()"><i class="calendar alternate outline icon"></i></button>
	<?php } ?>
</form>

<div id="addKalender" class="ui raised segment">
	<form id="addKalenderForm" class="ui form">
        <div id="input_kalender" class="inline fields">
        	<label for="kalender">Kalender hinzufügen für:</label>
            <div class="field">
              <div class="ui radio checkbox">
                <input type="radio" name="kalender" checked="checked" tabindex="0" class="hidden" value="1">
                <label>Hamburg</label>
              </div>
            </div>
            <div class="field">
              <div class="ui radio checkbox">
                <input type="radio" name="kalender" tabindex="0" class="hidden" value="2">
                <label>Den echten Norden</label>
              </div>
            </div>
        </div>
        
        <div class="buttonLine"><button class="ui button" type="submit">Kalender anlegen</button></div>
	</form>
</div>

<script>
$('#userLanguage').dropdown();
$('#userPort').dropdown();
$("#addUser").submit(function(event){ settings.addUser(<?php echo ($editMode)?$userToEdit->getID():'null'; ?>); });
$("#addKalenderForm").submit(function(event){ settings.addUserKalender(<?php echo ($editMode)?$userToEdit->getID():''; ?>); });
$('.ui.radio.checkbox').checkbox();

<?php if($editMode) { foreach ($userToEdit->getUserPorts() as $port) {?>
$('#userPort').dropdown('set selected', <?php echo $port->getID(); ?>);
<?php }} ?>

<?php if($editMode) { foreach($userToEdit->getUserLanguages() as $language) { ?>
$('#userLanguage').dropdown('set selected', <?php echo $language->getLanguageID(); ?>);
<?php }} ?>
</script>