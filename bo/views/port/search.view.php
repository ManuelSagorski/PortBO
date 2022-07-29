<?php 
namespace bo\views\port;

use bo\components\classes\Port;

include '../../components/config.php';

if( $user->getLevel() >= 8 ) {
    $portsToView = Port::getPortsForProject();
}
else {
    $portsToView = $user->getUserPorts();
}
?>

<div class="listingHeadline"><?php $t->_('my-ports'); ?>:</div>

<div id="searchResult">
<?php foreach($portsToView as $port) { ?>
	<div class="searchResultRow"><a onclick="portC.openDetails(<?php echo $port->getID(); ?>);"><?php echo $port->getName(); ?></a></div>
<?php } ?>
</div>
<?php if($user->getLevel() >= 5 ) { ?>
<button onClick="portC.newPort();"><?php $t->_('add-new-port'); ?></button>
<?php } ?>