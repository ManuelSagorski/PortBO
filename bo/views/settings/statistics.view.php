<?php
namespace bo\views\settings;
use bo\components\classes\Port;
include '../../components/config.php';

if($user->getLevel() != 9)
    header('Location: ' . MAIN_PATH . 'index.php');
?>

<div>
	<form id="statisticsDate" class="ui form" name="statistics">
		<div class="ui three column grid">
        	<div id="input_dateFrom" class="column">
            	<label>Von:</label>
            	<input 
            		type="date" 
            		id="dateFrom" 
            		name="dateFrom" 
            		value="<?php echo date("Y-m-01"); ?>"
            		onChange="settings.getStatistics();"
            	>
            </div>
            
            <div id="input_dateTo" class="column">
            	<label>Bis:</label>
            	<input 
            		type="date" 
            		id="dateTo" 
            		name="dateTo" 
            		value="<?php echo date("Y-m-d"); ?>"
            		onChange="settings.getStatistics();"
            	>
            </div>
            
            <div id="input_port" class="column">
            	<label>Hafen:</label>
            	<select id="port" name="port" class="ui fluid dropdown" onChange="settings.getStatistics();">
            		<option value="0" selected>Alle Häfen</option>
            	<?php foreach (Port::getMultipleObjects() as $port) { ?>
        			<option value="<?php echo $port->getID(); ?>"><?php echo $port->getName(); ?></option>
        		<?php } ?>
            	</select>
            </div>
    	</div>
	</form>
</div>

<script>
$('#port').dropdown();
$("#statisticsDate").submit(function(event){ settings.getStatistics(); });
settings.getStatistics();
</script>

<div id="statisticsContend">

</div>