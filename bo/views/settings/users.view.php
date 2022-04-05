<?php
namespace bo\views\settings;
use bo\components\classes\User;
use bo\components\types\Languages;
use bo\components\classes\helper\Security;

include '../../components/config.php';
Security::grantAccess(8);

$users = User::getMultipleObjects(["inactive" => 0]);
?>
<div class="ui segment">
	<h4 class="ui header">Neuen Verkündiger einladen</h4>

	<form id="inviteUser" class="ui form" autocomplete="off">
		<div class="ui two column grid">
			<div class="column">
                <div class="required field">
                	<input type="text" id="email" name="email" placeholder="Email Adresse...">
                </div>
            </div>
            <div class="column">
    			<button>Einladungsmail senden</button>
    		</div>
		</div>
		<div class="ui error message"></div>
		<div id="message"></div>
	</form>
</div>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="7">Angemeldete Verkündiger</th>
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
  .form({ fields: {email: {identifier: 'email', rules: [{type   : 'email',prompt : 'Bitte gebe eine gültige Email Adresse ein.'}]}}, 
  	onSuccess: function(){
  		settings.inviteUser(<?php echo $project->getID(); ?>);
  		return false;
  	}}
  	);
</script>