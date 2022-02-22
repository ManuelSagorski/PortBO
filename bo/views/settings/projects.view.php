<?php
namespace bo\views\settings;
use bo\components\classes\helper\Security;
use bo\components\classes\Projects;
use bo\components\classes\User;

include '../../components/config.php';
Security::grantAccess(9);

$projects = Projects::getMultipleObjects();
?>

<?php foreach ($projects as $key => $project) { ?>
<div class="ui styled fluid accordion">
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>title">
        <i class="dropdown icon"></i>
        <?php echo $project->getName(); ?>
    </div>
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>content">
    
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
        </div>
        
        <table class="detailTable ui very compact celled striped table">
            <thead>
                <tr>
                    <th colspan="5">Projekt Administratoren</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($project->getProjectAdmins() as $projectAdmin) { ?>
                <tr>
                	<td><input type="radio" name="selectUser" value="<?php echo $projectAdmin->getID(); ?>"></td>
                    <td><?php echo $projectAdmin->getFirstName() . " " . $projectAdmin->getSurname(); ?></td>
                    <td><?php echo $projectAdmin->getPhone(); ?></td>
                    <td><?php echo $projectAdmin->getEmail(); ?></td>
                    <td><?php echo $projectAdmin->getLevelDescription(); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="detailActions ui icon menu">
        	<a class="item" onclick="settings.newUser(null, false, <?php echo $project->getID(); ?>);">
        		<i class="plus icon"></i>
        	</a>
        	<a class="item" onclick="settings.newUser($('input[name=selectUser]:checked').val(), true, <?php echo $project->getID(); ?>);">
        		<i class="edit icon"></i>
        	</a>
        	<a class="item" onClick="alert('Benutzer können derzeit nur durch einen Datenbankadministrator gelöscht werden.');">
        		<i class="trash alternate icon"></i>
        	</a>
        </div>
    </div>
</div>
<?php } ?>

<script>
$('.ui.accordion').accordion({exclusive: true});
$('.ui.checkbox').checkbox();
</script>