<?php
namespace bo\views\settings;

use bo\components\classes\helper\Security;

include '../../components/config.php';
Security::grantAccess(9);
?>

<form id="addProject" class="ui form">
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>
    
    <div class="required field">
    	<label><?php $t->_('project-name'); ?></label>
    	<input type="text" id="projectName" name="projectName">
    </div>

    <div class="required field">
    	<label><?php $t->_('project-short'); ?></label>
    	<input type="text" id="projectShort" name="projectShort">
    </div>
    
    <button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>

<script>
	$('.ui.form').form({
		fields: {
			projectName: {identifier: 'projectName', rules: [{type : 'empty', prompt : '<?php $t->_('insert-project-name'); ?>'}]},
			projectShort: {identifier: 'projectShort', rules: [{type : 'empty', prompt : '<?php $t->_('insert-project-short'); ?>'}]}
		},
		onSuccess: function (event) {
			event.preventDefault();
			settings.safeProject();
		}
	});
</script>