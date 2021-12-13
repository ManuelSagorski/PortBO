<?php
namespace bo\views\settings;

use bo\components\classes\dbConnect;
use bo\components\classes\user;
use bo\components\types\languages;

include '../../components/config.php';

if(isset($_GET['id'])) {
    $userToEdit = dbConnect::fetchSingle("select * from port_bo_user where id= ?", user::class, array($_GET['id']));
}
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
    	<?php foreach ($ports as $port) { ?>
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
</form>

<script>
$('#userLanguage').dropdown();
$('#userPort').dropdown();
$("#addUser").submit(function(event){ settings.addUser(<?php echo ($editMode)?$userToEdit->getID():'null'; ?>);});

<?php 
if($editMode) {
    foreach ($ports as $port) {
        if($userToEdit->userHasPort($port->getID())) {
?>
$('#userPort').dropdown('set selected', <?php echo $port->getID(); ?>);
<?php }}} ?>

<?php if($editMode) { foreach($userToEdit->getUserLanguages() as $language) { ?>
$('#userLanguage').dropdown('set selected', <?php echo $language->getLanguageID(); ?>);
<?php }} ?>
</script>