<?php
namespace bo\views\port;

use bo\components\classes\dbConnect;
use bo\components\classes\port;

include '../../components/config.php';

if(isset($_GET['id'])) {
    $port = dbConnect::fetchSingle("select * from port_bo_port where id= ?", port::class, array($_GET['id']));
}
$editMode = !empty($port);
?>

<form id="addPort" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="input_portName" class="required field">
    	<label>Name</label>
    	<input 
    		type="text" 
    		id="portName" 
    		name="portName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo($editMode)?$port->getName():''; ?>"
    	>
    </div>

    <div id="input_portShort" class="required field">
    	<label>Kürzel</label>
    	<input 
    		type="text" 
    		id="portShort" 
    		name="portShort" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo($editMode)?$port->getShort():''; ?>"
    	>
    </div>
    
    <div id="input_portMTLink" class="field">
    	<label>MarineTraffic Link</label>
    	<textarea rows="2" id="portMTLink" name="portMTLink"><?php echo($editMode)?$port->getMTLink():''; ?></textarea>
    </div>
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addPort").submit(function(event){ portC.addPort(<?php echo ($editMode)?$port->getID():'null'; ?>);});
</script>