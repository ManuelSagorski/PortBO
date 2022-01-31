<?php
namespace bo\views\settings;
use bo\components\classes\SettingsForecastLists;
include '../../components/config.php';

if(isset($_GET['id']))
    $link = SettingsForecastLists::getSingleObjectByID($_GET['id']);
?>

<form id="addLink" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="input_linkName" class="required field">
    	<label>Name</label>
    	<input 
    		type="text" 
    		id="linkName" 
    		name="linkName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($link))?$link->getName():''; ?>"
    	>
    </div>

    <div id="input_linkUrl" class="required field">
    	<label>Link</label>
    	<textarea rows="10" id="linkUrl" name="linkUrl" onkeyup="formValidate.clearAllError();"><?php echo (!empty($link))?$link->getLink():''; ?></textarea>
    </div>
   
    <input type="hidden" name="vesselID" value="<?php echo $_GET['vesselID']; ?>">
    
    <button class="ui button" type="submit">Speichern</button>
</form>
<script>
$("#addLink").submit(function(event){ settings.addLink(<?php echo (!empty($link))?$link->getID():'null'; ?>);});
</script>