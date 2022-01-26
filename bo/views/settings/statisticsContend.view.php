<?php
namespace bo\views\settings;

use bo\components\classes\helper\statistics;

include '../../components/config.php';

$statistics = new statistics($_POST['data']);
?>
<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="2">Global</th>
		</tr>
	</thead>
    <tbody>
		<tr>
			<td>Shiffe insg. in der Datenbank:</td>
			<td><?php echo $statistics->getGlobal()['shipCount']; ?></td>			
		</tr>
		<tr>
			<td>Schiffe mit bekannter Email Adresse:</td>
			<td><?php echo $statistics->getGlobal()['shipMailCount']; ?></td>			
		</tr>
		<tr>
			<td>Schiffe mit bekannter Telefonnummer:</td>
			<td><?php echo $statistics->getGlobal()['shipPhoneCount']; ?></td>			
		</tr>
    </tbody>
</table>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="2">Zeitraum</th>
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