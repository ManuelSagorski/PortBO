<?php
namespace bo\views\contacts;

use bo\components\classes\Projects;

include '../../components/config.php';

$projects = Projects::getMultipleObjects();
?>
<div class="ui basic segment">
	Folgende Hafengruppen verwenden derzeit Backoffice:
</div>
<?php 
foreach ($projects as $key => $project) {
    if(!$project->getModForeignPort()) {
        $projectUsers = $project->getUserForProjectAdministration();
?>
<div class="ui styled fluid accordion">
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>title">
        <i class="dropdown icon"></i>
        <?php echo $project->getName(); ?>
    </div>
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>content">        
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
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php }} ?>

<script>
$('.ui.accordion').accordion({exclusive: true});
$('.ui.checkbox').checkbox();
</script>