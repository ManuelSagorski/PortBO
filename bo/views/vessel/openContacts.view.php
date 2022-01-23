<?php
namespace bo\views\vessel;

use bo\components\classes\vesselContact;
use bo\components\classes\vessel;
use bo\components\classes\port;

include '../../components/config.php';
$openContacts = vesselContact::getOpenContactsForUser($_SESSION['user']);
$tmpDatum = '';
$tmpPort = 0;
?>

<div class="listingHeadline">
	Geplante Kontakte:
<?php 
foreach ($openContacts as $openContact) { 
    if($openContact->getPortID() != $tmpPort) {
        $tmpPort = $openContact->getPortID();
?>
</div>
<div>
	<div class="openContactRowHeader"><?php echo port::getPortName($openContact->getPortID()); ?></div>
<?php } ?>

<div class="openContactRow">
	<a onClick="vessel.openDetails(<?php echo $openContact->getVesselID(); ?>)">
		<div>
			<?php echo (vessel::getVesselType($openContact->getVesselID()) == 'Cruise')?'<i class="ship icon"></i>':''; ?>
			<?php echo $openContact->getDate(); ?> | <?php echo vessel::getVesselName($openContact->getVesselID())?>
		</div>
		<div><?php echo $openContact->getContactName(); ?></div>
	</a>
</div>
<?php } ?>

</div>