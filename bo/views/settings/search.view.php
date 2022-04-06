<?php 
namespace bo\views\settings;
use bo\components\classes\helper\Security;

include '../../components/config.php';
Security::grantAccess(8);
?>

<div class="listingHeadline"><?php $t->_('settings'); ?>:</div>

<div id="searchResult">
	<div class="searchResultRow active"><a onclick="settings.openDetails('users', this);">Benutzerverwaltung</a></div>
	<div class="searchResultRow"><a id="settingsEinstellungen" onclick="settings.openDetails('settings', this);">Einstellungen</a></div>
	<div class="searchResultRow"><a onclick="settings.openDetails('statistics', this);">Statistik</a></div>
</div>

<?php if ($user->getLevel() == 9) { ?>
<div class="listingHeadline"><?php $t->_('project-administration'); ?>:</div>

<div id="searchResult">
	<div class="searchResultRow"><a onclick="settings.openDetails('logging', this);"><?php $t->_('menu-logging'); ?></a></div>
	<div class="searchResultRow"><a id="settingsProjekte" onclick="settings.openDetails('projects', this);"><?php $t->_('menu-projects'); ?></a></div>
</div>	
<?php } ?>