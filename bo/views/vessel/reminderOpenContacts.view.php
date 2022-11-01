<?php 
namespace bo\views\vessel;

use bo\components\classes\VesselContact;
use bo\components\classes\Vessel;
include '../../components/config.php';
?>

<div class="ui warning message">
	In eurem Hafendienst Projekt gibt es noch Schiffskontakte, die mehr als 3 Tage in der Vergangenheit liegen und noch nicht abgeschlossen sind.
	Schließe diese Kontakte bitte ab oder veranlasse, dass der jeweilige Verkündiger dies tut. Vielen Dank.
</div>

<table class="ui very compact unstackable celled striped table">
	<?php foreach (VesselContact::checkOpenContacts() as $contact) { ?>
	    <tr>
	    	<td><?php echo Vessel::getVesselName($contact->getVesselID())?></td>
	    	<td><?php echo date("d.m.Y", strtotime($contact->getDate())); ?></td>
	    </tr>
	<?php }	?>
</table>