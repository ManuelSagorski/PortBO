<?php
namespace views\settings;

use components\classes\dbConnect;
use components\classes\user;
use components\types\languages;

include '../../components/config.php';

if($user->getLevel() != 9) {
    header('Location: http://'.$hostname.'/'.FOLDER.'/index.php');
}
$users = dbConnect::fetchAll('select * from port_bo_user', user::class, array());
?>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="6">Angemeldete Verkündiger</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($users as $user) { ?>
		<tr>
			<td data-label="select"><input type="radio" name="selectUser" value="<?php echo $user->getID(); ?>"></td>
			<td data-label="userFullName"><?php echo $user->getFirstName() . " " . $user->getSurname(); ?></td>			
			<td data-label="userEmail"><?php echo $user->getEmail(); ?></td>
			<td data-label="userPhone">
			<?php echo $user->getPhone(); ?>
			<?php echo (!empty($user->getTelegramID()))?'<img class="iconRowElement" src="../resources/img/telegramLogo.png" />':''; ?>			
			</td>
			<td data-label="userLevel"><?php echo $user->getLevelDescription(); ?></td>
			<td data-label="userLevel">
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