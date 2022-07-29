<?php 
namespace bo\views\port;
use bo\components\classes\Company;
use bo\components\classes\Port;
use bo\components\classes\User;
use bo\components\types\Languages;
use bo\components\classes\helper\Query;
use bo\components\classes\UserToPort;
include '../../components/config.php';

if(!empty($_GET["id"])) {
    $port = Port::getSingleObjectByID($_GET["id"]);
  
$_SESSION['portID'] = $port->getID();
?>
<div class="elementDetailWrapper ui segment">
	<div class="elemDetail agency">
    	<div class="elemDetailIcon">
    		<img src="../resources/img/iconPort.png" />
    	</div>

    	<div class="label"><?php $t->_('name'); ?>:</div>
    	<div class="elemDetailName"><div><?php echo $port->getName(); ?></div></div>
    
    	<div class="label"><?php $t->_('short'); ?>:</div>
    	<div><?php echo $port->getShort(); ?></div>
    </div>
</div>
<?php if($user->getLevel() >= 5 ) { ?>
<div class="detailActions ui icon menu">
	<a class="item" onclick="portC.newPort(<?php echo $port->getID(); ?>);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="alert('<?php $t->_('delete-port-only-admin'); ?>');">
		<i class="trash alternate icon"></i>
	</a>
	<a class="item" href="<?php echo $port->getMtLink(); ?>" target="_blank">
		<img class="iconRowElement" src="../resources/img/marineTrafficLogo.png" />
	</a>
</div>
<?php } ?>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="5"><?php $t->_('companys'); ?></th>
		</tr>
	</thead>
    <tbody>
    <?php foreach (Company::getMultipleObjects(["port_id" => $_GET["id"]], "name") as $company) { ?>
		<tr>
			<td data-label="select">
				<?php if($user->getLevel() >= 4) { ?>
				<input type="radio" name="selectCompany" value="<?php echo $company->getID(); ?>">
				<?php } ?>
			</td>
			<td data-label="companyName"><?php echo $company->getName(); ?></td>			
			<td data-label="companyMTLink">
			<?php if(!empty($company->getMTLink())) {?>
				<a href="<?php echo $company->getMTLink();?>" target="_blank"><img class="iconRowElement" src="../resources/img/marineTrafficLogo.png" /></a>
			<?php } ?>
			</td>
			<td data-label="companyPMLink">
			<?php if(!empty($company->getPMLink())) {?>
				<a href="<?php echo $company->getPMLink();?>" target="_blank"><img class="iconRowElement" src="../resources/img/googleMapsLogo.png" /></a>
			<?php } ?>
			</td>
			<td data-label="companyInfo"><?php echo $company->getInfo(); ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>
<?php if($user->getLevel() >= 8 ) { ?>
<div class="detailActions ui icon menu">
	<a class="item" onclick="portC.newCompany(<?php echo $port->getID(); ?>);">
		<i class="plus icon"></i>
	</a>
	<a class="item" onclick="portC.newCompany(<?php echo $port->getID(); ?>, $('input[name=selectCompany]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="portC.deleteCompany(<?php echo $port->getID(); ?>, $('input[name=selectCompany]:checked').val());">
		<i class="trash alternate icon"></i>
	</a>
</div>
<?php } ?>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="5"><?php $t->_('publisher-port'); ?></th>
		</tr>
	</thead>
    <tbody>
    <?php foreach (Port::getUsersForPort($_GET["id"]) as $user) { ?>
		<tr>
			<td data-label="publisherName"><?php echo $user->getFirstName();?> <?php echo $user->getSurname();?></td>			
			<td data-label="publisherEmail"><?php echo $user->getEmail();?></td>
			<td data-label="publisherPhone"><?php echo $user->getPhone();?></td>
			<td data-label="publisherLanguages">
			<?php foreach ($user->getUserLanguages() as $language ) {?>
				<div class="userLanguage"><?php echo languages::$languages[$language->getLanguageID()]; ?></div>
			<?php } ?>		
			</td>
		</tr>
	<?php } ?>
    </tbody>
</table>

<?php } else { ?>
<div id="detailEmpty">
	<div><img src="../resources/img/iconPort.png" /></div>
	<div><?php $t->_('no-port-selected'); ?></div>
</div>
<?php }?>