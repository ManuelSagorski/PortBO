<?php
namespace bo\views\settings;
use bo\components\classes\helper\Security;
use bo\components\classes\Projects;

include '../../components/config.php';
Security::grantAccess(9);

$projects = Projects::getMultipleObjects();
?>

<?php foreach ($projects as $project) { ?>
<div class="ui styled fluid accordion">
    <div class="title">
        <i class="dropdown icon"></i>
        <?php echo $project->getName(); ?>
    </div>
    <div class="content">
    	<?php echo $project->getShort(); ?>
    </div>
</div>
<?php } ?>

<script>
$('.ui.accordion').accordion();
</script>