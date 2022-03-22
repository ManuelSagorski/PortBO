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
			<th colspan="2">Für den gewählten Zeitraum</th>
		</tr>
	</thead>
    <tbody>
    	<tr>
			<td>Besuchte Schiffe:</td>
			<td><?php echo $statistics->getPeriod()['visitCount']; ?></td>			
		</tr>
		<tr>
			<td>Briefe an Schiffe:</td>
			<td><?php echo $statistics->getPeriod()['letterCount']; ?></td>			
		</tr>
		<tr>
			<td>Emails direkt an Schiffe:</td>
			<td><?php echo $statistics->getPeriod()['emailDirectCount']; ?></td>			
		</tr>
		<tr>
			<td>Emails an Schiff über Agenten:</td>
			<td><?php echo $statistics->getPeriod()['emailAgentCount']; ?></td>			
		</tr>
		<tr>
			<td>Kontakte per Telefon (Anruf / Messenger):</td>
			<td><?php echo $statistics->getPeriod()['phoneCount']; ?></td>			
		</tr>
    </tbody>
</table>