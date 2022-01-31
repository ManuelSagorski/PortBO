<?php
namespace bo\views\port;
use bo\components\classes\Port;
include '../../components/config.php';

if(isset($_GET['id']))
    $port = Port::getSingleObjectByID($_GET['id']);
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
    		value="<?php echo(!empty($port))?$port->getName():''; ?>"
    	>
    </div>

    <div id="input_portShort" class="required field">
    	<label>Kürzel</label>
    	<input 
    		type="text" 
    		id="portShort" 
    		name="portShort" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($port))?$port->getShort():''; ?>"
    	>
    </div>
    
    <div id="input_portMTLink" class="field">
    	<label>MarineTraffic Link</label>
    	<textarea rows="2" id="portMTLink" name="portMTLink"><?php echo(!empty($port))?$port->getMTLink():''; ?></textarea>
    </div>
    
    <button class="ui button" type="submit">Speichern</button>
</form>

<script>
$("#addPort").submit(function(event){ portC.addPort(<?php echo (!empty($port))?$port->getID():'null'; ?>);});
</script>