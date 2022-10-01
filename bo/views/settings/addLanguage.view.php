<?php
namespace bo\views\settings;
use bo\components\classes\helper\Security;
use bo\components\classes\Language;

include '../../components/config.php';
Security::grantAccess(9);

if(isset($_GET['id']))
    $language = Language::getSingleObjectByID($_GET['id']);
?>

<form id="addLanguage" class="ui form" autocomplete="off">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>

    <div id="input_languageName" class="required field">
    	<label><?php $t->_('language'); ?></label>
    	<input 
    		type="text" 
    		id="languageName" 
    		name="languageName" 
    		onkeyup="formValidate.clearAllError();" 
    		value="<?php echo(!empty($language))?$language->getName():''; ?>"
    	>
    </div>
   
    <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>
<script>
$("#addLanguage").submit(function(event){ settings.addLanguage(<?php echo (!empty($language))?$language->getID():'null'; ?>);});
</script>