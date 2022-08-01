<?php
namespace bo\views\settings;
use bo\components\classes\User;
use bo\components\types\Languages;
use bo\components\classes\helper\Security;
use bo\components\classes\Projects;

include '../../components/config.php';
Security::grantAccess(8);

$users = User::getMultipleObjects(["inactive" => 0]);
?>
<div class="ui segment">
	<h4 class="ui header"><?php $t->_('invite-publisher'); ?></h4>

	<form id="inviteUser" class="ui form" autocomplete="off">
		<div class="ui stackable grid">
			<div class="<?php echo ($user->getLevel() < 9)?'eight':'four'; ?> wide column">
                <div class="required field">
                	<input type="text" id="email" name="email" placeholder="<?php $t->_('email-address'); ?>...">
                </div>
            </div>
            <div class="four wide column">
            	<div class="ui selection dropdown mailLanguage">
                	<input type="hidden" name="mailLanguage">
                	<i class="dropdown icon"></i>
                	<div class="default text"></div>
                  	<div class="menu">
                  		<?php foreach (Languages::$frontendLanguages as $code => $language) { ?>
                    	<div class="item" data-value="<?php echo $code; ?>"><?php echo $language; ?></div>
                    	<?php } ?>
                	</div>
                </div>
            </div>
            <?php if($user->getLevel() == 9) { ?>
            <div class="four wide column">
            	<div class="ui selection dropdown mailProject">
                	<input type="hidden" name="mailProject">
                	<i class="dropdown icon"></i>
                	<div class="default text"></div>
                  	<div class="menu">
                  		<?php foreach (Projects::getAll() as $oneProject) { ?>
                    	<div class="item" data-value="<?php echo $oneProject->getID(); ?>"><?php echo $oneProject->getName(); ?></div>
                    	<?php } ?>
                	</div>
                </div>            
            </div>
            <?php } ?>
            <div class="four wide column right aligned">
    			<button><?php $t->_('send-invitation-mail'); ?></button>
    		</div>
		</div>
		<div class="ui error message"></div>
		<div id="message"></div>
	</form>
</div>

<div style="width: 100%; overflow: auto;">
    <table class="detailTable ui very compact unstackable celled striped table">
    	<thead>
    		<tr>
    			<th colspan="7"><?php $t->_('registerd-publisher'); ?></th>
    		</tr>
    	</thead>
        <tbody>
        <?php foreach ($users as $user) { ?>
    		<tr<?php echo ($user->getLevel() == 0)?" class='negative'":""; ?>>
    			<td data-label="select"><input type="radio" name="selectUser" value="<?php echo $user->getID(); ?>"></td>
    			<td data-label="userFullName"><?php echo $user->getFirstName() . " " . $user->getSurname(); ?></td>			
    			<td data-label="userEmail"><?php echo $user->getEmail(); ?></td>
    			<td data-label="userPhone"><?php echo $user->getPhone(); ?></td>
    			<td data-label="userParameter">
    				<?php echo (!empty($user->getTelegramID()))?'<i class="telegram plane icon"></i>':''; ?>
    				<?php echo (!empty($user->getPlanningID()))?'<i class="calendar alternate outline icon"></i>':''; ?>		
    			</td>
    			<td data-label="userLevel"><?php echo $user->getLevelDescription(); ?></td>
    			<td data-label="userLanguage">
        		<?php foreach ($user->getUserLanguages() as $language ) {?>
        			<div class="userLanguage"><?php echo languages::$languages[$language->getLanguageID()]; ?></div>
        		<?php } ?>
    			</td>
    		</tr>
    	<?php } ?>
        </tbody>
    </table>
</div>

<div class="detailActions ui icon menu">
	<a class="item" onclick="settings.newUser($('input[name=selectUser]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="settings.deleteUser($('input[name=selectUser]:checked').val());">
		<i class="trash alternate icon"></i>
	</a>
</div>

<script>

$('.ui.form')
  .form({ fields: {email: {identifier: 'email', rules: [{type   : 'email',prompt : '<?php $t->_('insert-email'); ?>'}]}}, 
  	onSuccess: function(){
  		settings.inviteUser(<?php echo $project->getID(); ?>);
  		return false;
  	}}
  	);

	$('.ui.dropdown.mailLanguage').dropdown();
	$('.ui.dropdown.mailLanguage').dropdown('set selected', '<?php echo $_SESSION['language']; ?>');
	
	$('.ui.dropdown.mailProject').dropdown();
	$('.ui.dropdown.mailProject').dropdown('set selected', '<?php echo $project->getName(); ?>');
</script>