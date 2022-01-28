<?php
namespace bo\views\vessel;

use bo\components\classes\VesselContact;
use bo\components\classes\Vessel;
use bo\components\classes\Port;

include '../../components/config.php';
$openContacts = VesselContact::getOpenContactsForUser($_SESSION['user']);
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
	<div class="openContactRowHeader"><?php echo Port::getPortName($openContact->getPortID()); ?></div>
<?php } ?>

<div class="openContactRow">
	<a onClick="vessel.openDetails(<?php echo $openContact->getVesselID(); ?>)">
		<div>
			<?php echo (Vessel::getVesselType($openContact->getVesselID()) == 'Cruise')?'<i class="ship icon"></i>':''; ?>
			<?php echo $openContact->getDate(); ?> | <?php echo Vessel::getVesselName($openContact->getVesselID())?>
		</div>
		<div><?php echo $openContact->getContactName(); ?></div>
	</a>
</div>
<?php } ?>

</div>