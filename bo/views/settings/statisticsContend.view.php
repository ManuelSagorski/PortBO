<?php
namespace bo\views\settings;
use bo\components\classes\helper\Security;
use bo\components\classes\helper\Statistics;

include '../../components/config.php';
Security::grantAccess(8);

$statistics = new Statistics($_POST['data']);
?>
<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="2"><?php $t->_('for-selected-period'); ?></th>
		</tr>
	</thead>
    <tbody>
    	<tr>
			<td><?php $t->_('visited-ships'); ?>:</td>
			<td><?php echo $statistics->getPeriod()['visitCount']; ?></td>			
		</tr>
		<tr>
			<td><?php $t->_('letters-ships'); ?>:</td>
			<td><?php echo $statistics->getPeriod()['letterCount']; ?></td>			
		</tr>
		<tr>
			<td><?php $t->_('emails-ships'); ?>:</td>
			<td><?php echo $statistics->getPeriod()['emailDirectCount']; ?></td>			
		</tr>
		<tr>
			<td><?php $t->_('emails-ships-agencys'); ?>:</td>
			<td><?php echo $statistics->getPeriod()['emailAgentCount']; ?></td>			
		</tr>
		<tr>
			<td><?php $t->_('calls-ships'); ?>:</td>
			<td><?php echo $statistics->getPeriod()['phoneCount']; ?></td>			
		</tr>
    </tbody>
</table>