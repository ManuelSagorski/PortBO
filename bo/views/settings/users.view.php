<?php
namespace bo\views\settings;

use bo\components\classes\User;
use bo\components\types\Languages;

include '../../components/config.php';

if($user->getLevel() != 9)
    header('Location: ' . MAIN_PATH . 'index.php');
$users = User::getMultipleObjects();
?>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="7">Angemeldete Verkündiger</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($users as $user) { ?>
		<tr>
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
	<a class="item" onclick="settings.newUser();">
		<i class="plus icon"></i>
	</a>
	<a class="item" onclick="settings.newUser($('input[name=selectUser]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="alert('Benutzer können derzeit nur durch einen Datenbankadministrator gelöscht werden.');">
		<i class="trash alternate icon"></i>
	</a>
</div>