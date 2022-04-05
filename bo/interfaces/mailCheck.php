<?php

namespace bo\interfaces;

use bo\components\classes\helper\DBConnect;

$independent = true;
include '../components/config.php';

$result = DBConnect::execute("SELECT v.name, vc.* FROM `port_bo_vesselContactDetails` vc left join port_bo_vessel v on v.id = vc.vessel_id where vc.detail like ?", Array('%' . $_GET['name'] . '%'));
?>

<table>
<?php while($row = $result->fetch()) { ?>
    <tr>
    	<td><?php echo $row['name']; ?></td>
    	<td><?php echo $row['detail']; ?></td>
    </tr>
<?php } ?>
</table>
