<?php 
namespace bo\views\settings;

use bo\components\classes\helper\Security;
use bo\components\classes\User;
use bo\components\classes\Projects;

include '../../components/config.php';
Security::grantAccess(9);

$actualUser = User::getSingleObjectByID($_GET['id'], 0);
?>

<form id="transferUser" class="ui form">
    <div class="ui message">
        <div class="header">
        	Aktuelles Projekt:
        </div>
    	<p><?php echo Projects::getProjectName($actualUser->getProjectId()); ?></p>
    </div>
    
    <div class="ui error message">
		<div id="errorMessage"></div>
    </div>
    
    <div class="required field">
    	<label>Neues Projekt:</label>
        <div class="ui selection dropdown newProject">
        	<input type="hidden" name="newProject">
        	<i class="dropdown icon"></i>
        	<div class="default text"></div>
          	<div class="menu">
          		<?php foreach (Projects::getAll() as $oneProject) { ?>
            	<div class="item" data-value="<?php echo $oneProject->getID(); ?>"><?php echo $oneProject->getName(); ?></div>
            	<?php } ?>
        	</div>
        </div>  
	</div>
	
	<button class="ui button" type="submit"><?php $t->_('safe'); ?></button>
</form>

<script>
	$('.ui.dropdown.newProject').dropdown();
	
	$('.ui.form')
      .form({ fields: {newProject: {identifier: 'newProject', rules: [{type : 'empty', prompt : '<?php $t->_('insert-new-project'); ?>'}]}}, 
      	onSuccess: function(){
      		settings.safeTransferUser(<?php echo $actualUser->getID(); ?>);
      		return false;
      	}}
      	);
</script>