<?php 
namespace views\port;

use components\classes\company;
use components\classes\dbConnect;
use components\classes\port;
use components\classes\user;
use components\types\languages;

include '../../components/config.php';

if(!empty($_GET["id"])) {
    $port = dbConnect::fetchSingle("select * from port_bo_port where id = ?", port::class, array($_GET["id"]));
    $users = dbConnect::fetchAll("select u.* from port_bo_user u join port_bo_userToPort up on u.id = up.user_id where up.port_id = ?", user::class, array($_GET["id"]));
    $companys = dbConnect::fetchAll("select * from port_bo_company where port_id = ? order by name", company::class, array($_GET["id"]));
    $_SESSION['portID'] = $port->getID();

?>
<div class="elementDetailWrapper ui segment">
	<div class="elemDetail agency">
    	<div class="elemDetailIcon">
    		<img src="../resources/img/iconPort.png" />
    	</div>

    	<div class="label">Name:</div>
    	<div class="elemDetailName"><div><?php echo $port->getName(); ?></div></div>
    
    	<div class="label">Kürzel:</div>
    	<div><?php echo $port->getShort(); ?></div>
    </div>
</div>
<div class="detailActions ui icon menu">
	<a class="item" onclick="portC.newPort(1);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="alert('Häfen können derzeit nur durch einen Administrator gelöscht werden.');">
		<i class="trash alternate icon"></i>
	</a>
	<a class="item" href="<?php echo $port->getMtLink(); ?>" target="_blank">
		<img class="iconRowElement" src="../resources/img/marineTrafficLogo.png" />
	</a>
</div>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="5">Liegeplätze</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($companys as $company) { ?>
		<tr>
			<td data-label="select"><input type="radio" name="selectCompany" value="<?php echo $company->getID(); ?>"></td>
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

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="5">In diesem Hafen tätige Verkündiger:</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($users as $user) { ?>
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
	<div>Kein Hafen ausgewählt</div>
</div>
<?php }?>