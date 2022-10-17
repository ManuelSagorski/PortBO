<?php
namespace bo\views\settings;
use bo\components\classes\User;
use bo\components\types\Languages;
use bo\components\classes\Port;
use bo\components\classes\helper\Security;
use bo\components\controller\SettingsController;
use bo\components\classes\Projects;
use bo\components\classes\Language;

include '../../components/config.php';
Security::grantAccess(8);

$projectID = null;
if(isset($_GET['projectID'])) {
    $projectID = $_GET['projectID'];
    $projectObj = Projects::getSingleObjectByID($projectID);
}
if(isset($_GET['id'])) 
    $userToEdit = User::getSingleObjectByID($_GET['id'], $projectID);
?>

<form id="addUser" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>
    
    <div class="two fields">
        <div id="input_userFirstName" class="required field">
        	<label><?php $t->_('first-name'); ?></label>
        	<input 
        		type="text" 
        		id="userFirstName" 
        		name="userFirstName" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getFirstName():''; ?>"
        		readonly="readonly"
        	>
        </div>

        <div id="input_userSurname" class="required field">
        	<label><?php $t->_('surname'); ?></label>
        	<input 
        		type="text" 
        		id="userSurname" 
        		name="userSurname" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getSurname():''; ?>"
        		readonly="readonly"
        	>
        </div>    
    </div>

    <div class="two fields">
        <div id="input_userUsername" class="field">
        	<label><?php $t->_('username'); ?></label>
        	<input 
        		type="text" 
        		id="userUsername" 
        		name="userUsername" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getUsername():''; ?>"
        		readonly="readonly"
        	>
        </div>

        <div id="input_userPhone" class="required field">
        	<label><?php $t->_('mobile'); ?></label>
        	<input 
        		type="text" 
        		id="userPhone" 
        		name="userPhone" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getPhone():''; ?>"
        		readonly="readonly"
        	>
        </div>    
    </div>

    <div class="two fields">
        <div id="input_userEmail" class="required field">
        	<label><?php $t->_('email-address'); ?></label>
        	<input 
        		type="text" 
        		id="userEmail" 
        		name="userEmail" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($userToEdit))?$userToEdit->getEmail():''; ?>"
        		readonly="readonly"
        	>
        </div>
    
        <div id="input_userLevel" class="field">
        	<label><?php $t->_('userlevel'); ?></label>
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

	<?php if(!empty($projectObj) && $projectObj->getModForeignPort()) { ?>
    <div id="input_foreignPort" class="field">
    	<label>Foreign Port</label>
    	<input 
    		type="text" 
    		id="foreignPort" 
    		name="foreignPort" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($userToEdit))?$userToEdit->getForeignPort():''; ?>"
    	>
    </div>
    <?php } ?>
    
    <div id="input_userLanguage" class="field">
    	<label><?php $t->_('languages'); ?></label>
    	<select id="userLanguage" name="userLanguage" multiple="multiple" class="ui fluid dropdown"<?php echo (!empty($_GET['projectID']))?" disabled":""; ?>>
    	<?php foreach (Language::getLanguages() as $id=>$language) { ?>
			<option value="<?php echo $id; ?>"><?php echo $language; ?></option>
		<?php } ?>
    	</select>
    </div>

    <div id="input_userPort" class="field">
    	<label><?php $t->_('assigned-ports'); ?></label>
    	<select id="userPort" name="userPort" multiple="multiple" class="ui fluid dropdown"<?php echo (!empty($_GET['projectID']))?" disabled":""; ?>>
    	<?php foreach (Port::getMultipleObjects(['inactive' => 0]) as $port) { ?>
			<option value="<?php echo $port->getID(); ?>"><?php echo $port->getName(); ?></option>
		<?php } ?>
    	</select>
    </div>

    <div class="field"<?php echo ($user->getLevel() < 9)?'style="display: none;"':''; ?>>
   		<div class="ui toggle coordination checkbox">
    		<label><?php $t->_('user-coordination'); ?></label>
    		<input 
    			type="checkbox" 
    			class="hidden"
    			name="coordination"
    			<?php echo ($userToEdit->getCoordination() == 1)?" checked='checked'":"";?>
    		>
    	</div>
    </div>

    <?php if(empty($userToEdit)) { ?>
    <div id="input_userSendInfo" class="field">
    	<label><?php $t->_('send-login-email'); ?></label>
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

	<button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
	
	<?php if(!empty($userToEdit) && $userToEdit->getLevel() > 1) { ?>
		<button class="ui button" onClick="settings.sendInvitationMail(<?php echo $userToEdit->getID(); ?>)"><?php $t->_('invitation-mail'); ?></button>
	<?php } ?>
	
	<?php if(!empty($userToEdit) && SettingsController::canGetCalender($userToEdit, $projectID)) { ?>
		<button class="ui icon button" onClick="settings.showAddKalender()"><i class="calendar alternate outline icon"></i></button>
	<?php } ?>
</form>

<div id="addKalender" class="ui raised segment">
	<form id="addKalenderForm" class="ui form">
        <div id="input_kalender" class="inline fields">
        	<label for="kalender"><?php $t->_('add-calender-for'); ?>:</label>
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
        
        <div class="buttonLine"><button class="ui button" type="submit"><?php $t->_('add-calendar'); ?></button></div>
	</form>
</div>

<script>
$('#userLanguage').dropdown();
$('#userPort').dropdown();
$("#addUser").submit(function(event){ settings.addUser(<?php echo (!empty($userToEdit))?$userToEdit->getID():'null'; ?>); });
$("#addKalenderForm").submit(function(event){ settings.addUserKalender(<?php echo (!empty($userToEdit))?$userToEdit->getID():'null'; ?>, <?php echo (!empty($projectID))?$projectID:'null'; ?>); });
$('.ui.radio.checkbox').checkbox();
$('.ui.coordination.checkbox').checkbox();

<?php if(!empty($userToEdit)) { foreach ($userToEdit->getUserPorts() as $port) {?>
$('#userPort').dropdown('set selected', <?php echo $port->getID(); ?>);
<?php }} ?>

<?php if(!empty($userToEdit)) { foreach($userToEdit->getUserLanguages() as $language) { ?>
$('#userLanguage').dropdown('set selected', <?php echo $language->getLanguageID(); ?>);
<?php }} ?>
</script>