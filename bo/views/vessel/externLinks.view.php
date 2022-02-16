<?php
namespace bo\views\vessel;
use bo\components\classes\SettingsExternLinks;

include '../../components/config.php';
?>

<h4 class="ui horizontal divider header">
  <i class="linkify icon"></i>
  Externe Links
</h4>

<?php foreach(SettingsExternLinks::getMultipleObjects() as $externForecastList) {?>
<div class="externLink"><a href="<?php echo $externForecastList->getLink(); ?>" target="_blank"><?php echo $externForecastList->getName(); ?></a></div>
<?php }?>