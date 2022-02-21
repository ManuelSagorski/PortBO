<?php
namespace bo\views\settings;
use bo\components\classes\User;
use bo\components\types\Languages;
use bo\components\classes\Port;
use bo\components\classes\helper\Security;
use bo\components\classes\Projects;

include '../../components/config.php';
Security::grantAccess(8);

$project = null;
if(isset($_GET['projectID']))
    $project = $_GET['projectID'];


if(isset($_GET['id'])) 
    $userToEdit = User::getSingleObjectByID($_GET['id'], $project);
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
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getFirstName():''; ?>"
        	>
        </div>

        <div id="input_userSurname" class="required field">
        	<label>Nachname</label>
        	<input 
        		type="text" 
        		id="userSurname" 
        		name="userSurname" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getSurname():''; ?>"
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
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getUsername():''; ?>"
        	>
        </div>

        <div id="input_userPhone" class="required field">
        	<label>Handynummer</label>
        	<input 
        		type="text" 
        		id="userPhone" 
        		name="userPhone" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getPhone():''; ?>"
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
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getEmail():''; ?>"
        	>
        </div>
    
        <div id="input_userLevel" class="field">
        	<label>Benutzerlevel</label>
        	<select id="userLevel" name="userLevel" class="ui fluid dropdown"<?php echo (!empty($userToEdit) && $userToEdit->getLevel() > $user->getLevel())?" disabled":""; ?>>
        	<?php foreach (User::returnAllowedUserLevels($user, $userToEdit, $_GET['projectID']) as $levelID=>$level) {?>
    			<option 
    				value="<?php echo $levelID; ?>"
    				<?php if(!empty($userToEdit)){echo ($userToEdit->getLevel() == $levelID)?' selected':'';} ?>
    			><?php echo $level; ?></option>
    		<?php } ?>
        	</select>
        </div>
    </div>
    
    <div id="input_userLanguage" class="field">
    	<label>Sprachen</label>
    	<select id="userLanguage" name="userLanguage" multiple="multiple" class="ui fluid dropdown"<?php echo (!empty($_GET['projectID']))?" disabled":""; ?>>
    	<?php foreach (languages::$languages as $id=>$language) { ?>
			<option value="<?php echo $id; ?>"><?php echo $language; ?></option>
		<?php } ?>
    	</select>
    </div>

    <div id="input_userPort" class="field">
    	<label>Zugewiesene Häfen</label>
    	<select id="userPort" name="userPort" multiple="multiple" class="ui fluid dropdown"<?php echo (!empty($_GET['projectID']))?" disabled":""; ?>>
    	<?php foreach (Port::getMultipleObjects() as $port) { ?>
			<option value="<?php echo $port->getID(); ?>"><?php echo $port->getName(); ?></option>
		<?php } ?>
    	</select>
    </div>

    <?php if(empty($userToEdit)) { ?>
    <div id="input_userSendInfo" class="field">
    	<label>Email mit Benutzerdaten an den Verkündiger schicken</label>
		<input 
			type="checkbox" 
			id="userSendInfo" 
			name="userSendInfo" 
		>
    </div>
    <?php } ?>

	<?php if(!empty($_GET['projectID'])) { ?>
	<input type="hidden" name="projectID" value="<?php echo $_GET['projectID']; ?>">
	<?php } ?>

	<button class="ui button" type="submit">Speichern</button>
	<?php if(!empty($userToEdit) && $userToEdit->getLevel() > 1) { ?>
		<button class="ui button" onClick="settings.sendInvitationMail(<?php echo $userToEdit->getID(); ?>)">Einladungsmail</button>
	<?php } ?>
	<?php if(!empty($userToEdit) && empty($userToEdit->getPlanningID()) && $userToEdit->getLevel() > 3) { ?>
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
$("#addUser").submit(function(event){ settings.addUser(<?php echo (!empty($userToEdit))?$userToEdit->getID():'null'; ?>); });
$("#addKalenderForm").submit(function(event){ settings.addUserKalender(<?php echo (!empty($userToEdit))?$userToEdit->getID():''; ?>); });
$('.ui.radio.checkbox').checkbox();

<?php if(!empty($userToEdit)) { foreach ($userToEdit->getUserPorts() as $port) {?>
$('#userPort').dropdown('set selected', <?php echo $port->getID(); ?>);
<?php }} ?>

<?php if(!empty($userToEdit)) { foreach($userToEdit->getUserLanguages() as $language) { ?>
$('#userLanguage').dropdown('set selected', <?php echo $language->getLanguageID(); ?>);
<?php }} ?>
</script>