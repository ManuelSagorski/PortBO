<?php
namespace views\vessel;

use components\classes\vesselContact;
use components\classes\vessel;

include '../../components/config.php';
$openContacts = vesselContact::getOpenContactsForUser($_SESSION['user']);
?>

<div class="listingHeadline">Offene Kontakte:</div>
<?php foreach ($openContacts as $openContact) { ?>
<div class="openContactRow">
	<a onClick="vessel.openDetails(<?php echo $openContact->getVesselID(); ?>)">
		<div><?php echo vessel::getVesselName($openContact->getVesselID())?></div>
		<div><?php echo $openContact->getDate(); ?></div>
	</a>
</div>
<?php } ?>