<?php 
namespace bo\views\port;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\port;

include '../../components/config.php';
?>

<div class="listingHeadline">Meine Häfen:</div>

<div id="searchResult">
<?php foreach($user->getUserPorts() as $port) { ?>
	<div class="searchResultRow"><a onclick="portC.openDetails(<?php echo $port->getID(); ?>);"><?php echo $port->getName(); ?></a></div>
<?php } ?>
</div>
<?php if($user->getLevel() > 8 ) { ?>
<button onClick="portC.newPort();">Hafen hinzufügen</button>
<?php } ?>