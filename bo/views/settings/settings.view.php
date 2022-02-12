<?php
namespace bo\views\settings;
use bo\components\classes\SettingsForecastLists;
use bo\components\classes\helper\Security;

include '../../components/config.php';
Security::grantAccess(8);
?>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="2">Externe Links</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach (SettingsForecastLists::getMultipleObjects() as $externLink) { ?>
    	<tr>
			<td data-label="select"><input type="radio" name="selectLink" value="<?php echo $externLink->getID(); ?>"></td>
			<td data-label="linkName"><?php echo $externLink->getName(); ?></td>
    	</tr>
    <?php } ?>
    </tbody>
</table>
<div class="detailActions ui icon menu">
	<a class="item" onclick="settings.newLink();">
		<i class="plus icon"></i>
	</a>
	<a class="item" onclick="settings.newLink($('input[name=selectLink]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="settings.deleteLink($('input[name=selectLink]:checked').val())">
		<i class="trash alternate icon"></i>
	</a>
</div>