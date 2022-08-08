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
    	<label><?php $t->_('name'); ?></label>
    	<input 
    		type="text" 
    		id="portName" 
    		name="portName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($port))?$port->getName():''; ?>"
    	>
    </div>

    <div id="input_portShort" class="required field">
    	<label><?php $t->_('short'); ?></label>
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
    
    <h4 class="ui horizontal divider header">
        <i class="globe icon"></i>
        <?php $t->_('settings-vf-map'); ?>
    </h4>
    
    <div class="two fields">
        <div id="input_portLat" class="field">
        	<label>Latitude</label>
        	<input 
        		type="text" 
        		id="portLat" 
        		name="portLat" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($port))?$port->getLat():''; ?>"
        	>
        </div>
    
        <div id="input_portLon" class="field">
        	<label>Longitude</label>
        	<input 
        		type="text" 
        		id="portLon" 
        		name="portLon" 
        		onkeyup="formValidate.clearAllError();" 
        		value="<?php echo(!empty($port))?$port->getLon():''; ?>"
        	>
        </div>
    </div>
    
    <div class="two fields">
        <div class="field">
            <label>Zoom</label>
            <select class="ui fluid dropdown" name="portMapZoom">
            	<?php for($i = 3;$i<=18;$i++) { ?>
                <option value="<?php echo $i; ?>"<?php echo ($port->getVfZoom() == $i)?' selected="selected"':''; ?>><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    
    <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>

<script>
$("#addPort").submit(function(event){ portC.addPort(<?php echo (!empty($port))?$port->getID():'null'; ?>);});
</script>