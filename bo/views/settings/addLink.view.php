<?php
namespace bo\views\settings;
use bo\components\classes\helper\Security;
use bo\components\classes\SettingsExternLinks;

include '../../components/config.php';
Security::grantAccess(8);

if(isset($_GET['id']))
    $link = SettingsExternLinks::getSingleObjectByID($_GET['id']);
?>

<form id="addLink" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="input_linkName" class="required field">
    	<label><?php $t->_('name'); ?></label>
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
   
    <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>
<script>
$("#addLink").submit(function(event){ settings.addLink(<?php echo (!empty($link))?$link->getID():'null'; ?>);});
</script>