<?php 
namespace bo\views\port;

include '../../components/config.php';
?>

<div class="listingHeadline"><?php $t->_('my-ports'); ?>:</div>

<div id="searchResult">
<?php foreach($user->getUserPorts() as $port) { ?>
	<div class="searchResultRow"><a onclick="portC.openDetails(<?php echo $port->getID(); ?>);"><?php echo $port->getName(); ?></a></div>
<?php } ?>
</div>
<?php if($user->getLevel() >= 8 ) { ?>
<button onClick="portC.newPort();"><?php $t->_('add-new-port'); ?></button>
<?php } ?>