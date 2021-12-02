<?php 
namespace views\port;

use components\classes\dbConnect;
use components\classes\port;

include '../../components/config.php';

$ports = dbConnect::fetchAll("select p.* from port_bo_port p right join port_bo_userToPort u on p.id = u.port_id where u.user_id = ?", port::class, array($_SESSION['user']));
?>

<div class="listingHeadline">Meine Häfen:</div>

<div id="searchResult">
<?php foreach($ports as $port) { ?>
	<div class="searchResultRow"><a onclick="portC.openDetails(<?php echo $port->getID(); ?>);"><?php echo $port->getName(); ?></a></div>
<?php } ?>
</div>
<?php if($user->getLevel() > 8 ) { ?>
<button onClick="portC.newPort();">Hafen hinzufügen</button>
<?php } ?>