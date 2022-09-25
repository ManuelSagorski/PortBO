<?php 
namespace bo\views\settings;
use bo\components\classes\Language;
use bo\components\classes\helper\Security;

include '../../components/config.php';
Security::grantAccess(9);

$languages = Language::getMultipleObjects([], 'name');
?>
<table class="ui very compact unstackable celled striped table">
    <thead>
    	<tr>
    		<th colspan="2"><?php $t->_('menu-languages'); ?></th>
    	</tr>
    </thead>
    <tbody>
    	<?php foreach ($languages as $language) { ?>
    		<tr>
    			<td data-label="select"><input type="radio" name="selectLanguage" value="<?php echo $language->getID(); ?>"></td>
    			<td><?php echo $language->getName(); ?></td>
    		</tr>
    	<?php } ?>
    </tbody>
</table>