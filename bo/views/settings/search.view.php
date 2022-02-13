<?php 
namespace bo\views\settings;
use bo\components\classes\helper\Security;

include '../../components/config.php';
Security::grantAccess(8);
?>

<div class="listingHeadline">Einstellungen:</div>

<div id="searchResult">
	<div class="searchResultRow active"><a onclick="settings.openDetails('users', this);">Benutzerverwaltung</a></div>
	<div class="searchResultRow"><a onclick="settings.openDetails('settings', this);">Einstellungen</a></div>
	<div class="searchResultRow"><a onclick="settings.openDetails('logging', this);">Logging</a></div>
	<div class="searchResultRow"><a onclick="settings.openDetails('statistics', this);">Statistik</a></div>
</div>

<?php if ($user->getLevel() == 9) { ?>
<div class="listingHeadline">Projekt Administration:</div>

<div id="searchResult">
	<div class="searchResultRow"><a onclick="settings.openDetails('projects', this);">Projekte</a></div>
</div>	
<?php } ?>