<?php
namespace bo\views\contacts;

use bo\components\classes\Projects;

include '../../components/config.php';

$projects = Projects::getMultipleObjects([], "name");
?>
<div class="ui basic grey inverted segment"><h3><?php $t->_('coordinators-hgs'); ?></h3></div>
<?php 
foreach ($projects as $key => $project) {
    if(!$project->getModForeignPort()) {
        $projectUsers = $project->getCoordinationTeam();
?>
<div class="ui styled fluid accordion">
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>title">
        <i class="dropdown icon"></i>
        <?php echo $project->getName(); ?>
    </div>
    <div class="<?php echo ($key === array_key_first($projects))?"active ":""; ?>content">        
        <table class="detailTable ui very compact celled striped table">
            <tbody>
            <?php foreach ($projectUsers as $projectUser) { ?>
                <tr<?php echo ($projectUser->getLevel() == 0)?" class='negative'":""; ?>>
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