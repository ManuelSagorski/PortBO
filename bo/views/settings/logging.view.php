<?php
namespace bo\views\settings;
use bo\components\classes\helper\DBConnect;
use bo\components\classes\User;
use bo\components\classes\helper\Security;
use bo\components\classes\Projects;

include '../../components/config.php';
Security::grantAccess(8);

$sqlstrg = "select * from port_bo_log";
if($user->getLevel() != 9)
    $sqlstrg .= " where project_id = " . $user->getProjectId();
$sqlstrg .= " order by ts_erf desc limit 10000";

$logTable = DBConnect::execute($sqlstrg, array());
?>

<table class="detailTable ui very compact celled striped log table pagination" data-pagecount="500">
	<thead>
		<tr>
			<th colspan="6"><?php $t->_('logging'); ?></th>
		</tr>
	</thead>
    <tbody>
    <?php while ($row = $logTable->fetch()) { ?>
		<tr class="<?php echo $row['logLevel']; ?>">
			<td data-label="timestamp" class="three wide"><?php echo $row['ts_erf']; ?></td>
			<td data-label="project" class="two wide"><?php echo Projects::getProjectShort($row['project_id']); ?></td>
			<td data-label="userFullName" class="two wide"><?php echo (!empty($row['user_id']))?User::getUserFullName($row['user_id'], 0):"&nbsp;"; ?></td>			
			<td data-label="loglevel"><?php echo $row['logLevel']; ?></td>
			<td data-label="component"><?php echo $row['component']; ?></td>
			<td data-label="message"><?php echo $row['message']; ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>

<script>
helper.generatePaginationForTable();
</script>