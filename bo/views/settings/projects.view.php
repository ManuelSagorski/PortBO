<?php
namespace bo\views\settings;
use bo\components\classes\helper\Security;
use bo\components\classes\Projects;
use bo\components\classes\User;

include '../../components/config.php';
Security::grantAccess(9);

$projects = Projects::getMultipleObjects();
?>

<div class="ui basic segment ">
	<button class="ui button" onClick="settings.newProject();">Projekt anlegen</button>
</div>

<?php
foreach ($projects as $key => $project) { 
    if($project->getModForeignPort())
        $projectUsers = $project->getProjectForeignPortUser();
    else
        $projectUsers = $project->getUserForProjectAdministration();
?>
<div class="ui styled fluid accordion">
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>title">
        <i class="dropdown icon"></i>
        <?php echo $project->getName(); ?>
    </div>
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>content">
    
    	<?php if(!$project->getModForeignPort()) { ?>
        <div class="ui three column grid">
        	<div class="column">
        		<div class="ui segment">
                    <div class="inline field">
                   		<div class="ui toggle checkbox">
                    		<label>Modul Forecast</label>
                    		<input 
                    			type="checkbox" 
                    			tabindex="0" 
                    			class="hidden" 
                    			name="mod_forecast"
                    			onChange="settings.safeModuleSetting($(this), <?php echo $project->getID(); ?>)"
                    			<?php echo ($project->getModForecast())?" checked='checked'":"";?>
                    		>
                    	</div>
                    </div>
        		</div>
        	</div>
        	
        	<div class="column">
        		<div class="ui segment">
                    <div class="inline field">
                   		<div class="ui toggle checkbox">
                    		<label>Modul Planning</label>
                    		<input 
                    			type="checkbox" 
                    			tabindex="0" 
                    			class="hidden"
                    			name="mod_planning"
                    			onChange="settings.safeModuleSetting($(this), <?php echo $project->getID(); ?>)"
                    			<?php echo ($project->getModPlanning())?" checked='checked'":"";?>
                    		>
                    	</div>
                    </div>
        		</div>
        	</div>
        	
        	<div class="column">
        		<div class="ui segment">
                    <div class="inline field">
                   		<div class="ui toggle checkbox">
                    		<label>Modul Externe Links</label>
                    		<input 
                    			type="checkbox" 
                    			tabindex="0" 
                    			class="hidden"
                    			name="mod_externLinks"
                    			onChange="settings.safeModuleSetting($(this), <?php echo $project->getID(); ?>)"
                    			<?php echo ($project->getModExternLinks())?" checked='checked'":"";?>
                    		>
                    	</div>
                    </div>
        		</div>
        	</div>
        	
        	<div class="column">
        		<div class="ui segment">
                    <div class="inline field">
                   		<div class="ui toggle checkbox">
                    		<label>Modul Kontaktdetails Schiff</label>
                    		<input 
                    			type="checkbox" 
                    			tabindex="0" 
                    			class="hidden"
                    			name="mod_contactDetails"
                    			onChange="settings.safeModuleSetting($(this), <?php echo $project->getID(); ?>)"
                    			<?php echo ($project->getModContactDetails())?" checked='checked'":"";?>
                    		>
                    	</div>
                    </div>
        		</div>
        	</div>
        	
        	<div class="column">
        		<div class="ui red segment">
                    <div class="inline field">
                   		<div class="ui toggle checkbox">
                    		<label>Separate Kontaktdetails</label>
                    		<input 
                    			type="checkbox" 
                    			tabindex="0" 
                    			class="hidden"
                    			name="contact_details_separated"
                    			onChange="settings.safeModuleSetting($(this), <?php echo $project->getID(); ?>)"
                    			<?php echo ($project->getContactDetailsSeparated())?" checked='checked'":"";?>
                    		>
                    	</div>
                    </div>
        		</div>
        	</div>
        </div>
        <?php } ?>
        
        <table class="detailTable ui very compact celled striped table">
            <thead>
                <tr>
                    <th colspan="6"><?php echo (!$project->getModForeignPort())?"Projekt Administratoren":"Mitarbeiter Foreign Port"; ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($projectUsers as $projectUser) { ?>
                <tr<?php echo ($projectUser->getLevel() == 0)?" class='negative'":""; ?>>
                	<td><input type="radio" name="selectUser" value="<?php echo $projectUser->getID(); ?>"></td>
                    <td><?php echo $projectUser->getFirstName() . " " . $projectUser->getSurname(); ?></td>
                    <td><?php echo $projectUser->getPhone(); ?></td>
                    <td><?php echo $projectUser->getEmail(); ?></td>
                    <td><?php echo $projectUser->getLevelDescription(); ?></td>
                    <td><?php echo $projectUser->getForeignPort(); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="detailActions ui icon menu">
        	<a class="item" onclick="settings.newUser($('input[name=selectUser]:checked').val(), true, <?php echo $project->getID(); ?>);">
        		<i class="edit icon"></i>
        	</a>
        	<a class="item" onClick="alert('<?php $t->_('delete-user-only-admin'); ?>');">
        		<i class="trash alternate icon"></i>
        	</a>
        	<a class="item" onclick="settings.transferUser($('input[name=selectUser]:checked').val());">
        		<i class="sign-out alternate icon"></i>
        	</a>
        </div>
    </div>
</div>
<?php } ?>

<script>
$('.ui.accordion').accordion({exclusive: true});
$('.ui.checkbox').checkbox();
</script>