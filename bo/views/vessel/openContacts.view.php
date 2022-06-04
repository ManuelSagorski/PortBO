<?php
namespace bo\views\vessel;

use bo\components\classes\VesselContact;
use bo\components\classes\Vessel;
use bo\components\classes\Port;
use bo\components\classes\User;

include '../../components/config.php';
$openContacts = VesselContact::getOpenContactsForUser($_SESSION['user']);
$tmpPort = 0;
?>

<div class="listingHeadline">
	<?php $t->_('planned-contacts'); ?>
<?php 
foreach ($openContacts as $openContact) { 
    if($openContact->getPortID() != $tmpPort) {
        $tmpPort = $openContact->getPortID();
?>
</div>
<div>
	<div class="openContactRowHeader"><?php echo Port::getPortName($openContact->getPortID()); ?></div>
<?php } ?>

<div class="openContactRow<?php echo ($openContact->getContactUserID() == $user->getID())?' ownContact':''; ?>">
	<a onClick="vessel.openDetails(<?php echo $openContact->getVesselID(); ?>)">
		<div>
			<?php echo (Vessel::getVesselType($openContact->getVesselID()) == 'Cruise' or Vessel::getVesselType($openContact->getVesselID()) == 'River-Cruise')?'<i class="ship icon"></i>':''; ?>
			<?php echo $openContact->getDate(); ?> | <?php echo Vessel::getVesselName($openContact->getVesselID())?>
		</div>
		<div><?php echo User::getUserFullName($openContact->getContactUserID()); ?></div>
	</a>
</div>
<?php } ?>

</div>