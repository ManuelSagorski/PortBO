<?php
namespace bo\views\settings;
use bo\components\classes\helper\DBConnect;
use bo\components\classes\User;
use bo\components\classes\helper\Security;

include '../../components/config.php';
Security::grantAccess(8);

$logTable = DBConnect::execute("select * from port_bo_log order by ts_erf desc limit 150", array());
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
			<td data-label="userFullName" class="three wide"><?php echo (!empty($row['user_id']))?User::getUserFullName($row['user_id']):"&nbsp;"; ?></td>			
			<td data-label="loglevel"><?php echo $row['logLevel']; ?></td>
			<td data-label="component"><?php echo $row['component']; ?></td>
			<td data-label="message"><?php echo $row['message']; ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>