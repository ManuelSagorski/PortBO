<?php
namespace views\settings;

use components\classes\dbConnect;
use components\classes\user;

include '../../components/config.php';

if($user->getLevel() != 9) {
    header('Location: http://'.$hostname.'/'.FOLDER.'/index.php');
}
$logTable = dbConnect::execute("select * from port_bo_log order by ts_erf desc limit 150", array());
?>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="5">Logeinträge</th>
		</tr>
	</thead>
    <tbody>
    <?php while ($row = $logTable->fetch()) { ?>
		<tr<?php echo($row['logLevel'] == 'error')?' class="error"':''; ?>>
			<td data-label="timestamp" class="three wide"><?php echo $row['ts_erf']; ?></td>
			<td data-label="userFullName" class="three wide"><?php echo (!empty($row['user_id']))?user::getUserFullName($row['user_id']):"&nbsp;"; ?></td>			
			<td data-label="loglevel"><?php echo $row['logLevel']; ?></td>
			<td data-label="component"><?php echo $row['component']; ?></td>
			<td data-label="message"><?php echo $row['message']; ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>