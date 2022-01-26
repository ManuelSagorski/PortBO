<?php 
namespace bo\views\vessel;

use bo\components\classes\agency;
use bo\components\classes\helper\dbConnect;
use bo\components\classes\port;
use bo\components\classes\vessel;
use bo\components\classes\user;

include '../../components/config.php';

if(!empty($_GET["id"])) {
    $vessel = dbConnect::fetchSingle("select * from port_bo_vessel where id = ?", vessel::class, array($_GET["id"]));
    $_SESSION['vessID'] = $vessel->getID();
?>
<div class="elementDetailWrapper ui segment">
	<div class="elemDetail vessel">
    	<div class="elemDetailIcon">
    		<img src="../resources/img/iconVessel.png" />
    	</div>

    	<div class="label">Name:</div>
    	<div class="elemDetailName"><div><?php echo $vessel->getName(); ?></div></div>
    
    	<div class="label">IMO:</div>
    	<div><?php echo $vessel->getIMO(); ?></div>
    	
    	<div class="label">MMSI:</div>
    	<div><?php echo $vessel->getMMSI(); ?></div>
    	
    	<div class="label">ENI:</div>
    	<div><?php echo $vessel->getENI(); ?></div>    		
    
    	<div class="label">Typ:</div>
    	<div><?php echo $vessel->getTyp(); ?></div>
    	
    	<div class="label">Sprachen:</div>
    	<div class="elemDetailLanguages"><?php echo $vessel->getLanguage(); ?></div>
    </div>
</div>
<div class="detailActions ui icon menu">
	<a class="item" onclick="vessel.newVessel(<?php echo $vessel->getID(); ?>);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="alert('Schiffe können derzeit nur durch einen Administrator gelöscht werden.');">
		<i class="trash alternate icon"></i>
	</a>
	<?php if(!empty($vessel->getIMO())) { ?>
	<a class="item" href="https://www.vesselfinder.com/de/?imo=<?php echo $vessel->getIMO(); ?>" target="_blank">
		<img class="iconRowElement" src="../resources/img/vesselFinderLogo.png" />
	</a>
	<?php } ?>
	<?php if(!empty($vessel->getMMSI())) { ?>
	<a class="item" href="https://www.myshiptracking.com/de/?mmsi=<?php echo $vessel->getMMSI(); ?>" target="_blank">
		<img class="iconRowElement" src="../resources/img/myShipTrackingLogo.png" />
	</a>
	<?php } ?>
</div>	

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="4">Allgemeine Informationen</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($vessel->getVesselInfo() as $info) { ?>
		<tr>
			<td data-label="select"><input type="radio" name="selectInfo" value="<?php echo $info->getID();?>"></td>
			<td data-label="userFullName"><?php echo user::getUserFullName($info->getUser()); ?></td>			
			<td data-label="timestamp"><?php echo date("d.m.Y", strtotime($info->getTs_erf())); ?></td>
			<td data-label="info"><?php echo $info->getInfo(); ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>
<div class="detailActions ui icon menu">
	<a class="item" onclick="vessel.newVesselInfo(<?php echo $vessel->getID(); ?>);">
		<i class="plus icon"></i>
	</a>
	<a class="item" onclick="vessel.newVesselInfo(<?php echo $vessel->getID(); ?>, $('input[name=selectInfo]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="vessel.deleteVesselInfo(<?php echo $vessel->getID(); ?>, $('input[name=selectInfo]:checked').val());">
		<i class="trash alternate icon"></i>
	</a>
</div>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="4">Kontaktinformationen</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($vessel->getVesselContactDetails() as $contactDetail) { ?>
		<tr>
			<td data-label="select"><input type="radio" name="selectContactDetail" value="<?php echo $contactDetail->getID();?>"></td>
			<td data-label="contactDetailType"><?php echo $contactDetail->getType(); ?></td>			
			<td data-label="contactDetail"><?php echo $contactDetail->getDetail(); ?></td>
			<td data-label="info"><?php echo $contactDetail->getInfo(); ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>
<div class="detailActions ui icon menu">
	<a class="item" onclick="vessel.newVesselContactDetail(<?php echo $vessel->getID(); ?>);">
		<i class="plus icon"></i>
	</a>
	<a class="item" onclick="vessel.newVesselContactDetail(<?php echo $vessel->getID(); ?>, $('input[name=selectContactDetail]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="vessel.deleteVesselContactDetail(<?php echo $vessel->getID(); ?>, $('input[name=selectContactDetail]:checked').val());">
		<i class="trash alternate icon"></i>
	</a>
</div>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="7">Kontakte</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($vessel->getVesselContact() as $contact) { ?>
		<tr<?php echo ($contact->getPlanned() == 1)?' class="planned"':''; ?>>
			<td data-label="select"><input type="radio" name="selectContact" value="<?php echo $contact->getID(); ?>"></td>
			<td data-label="timestamp"><?php echo date("d.m.Y", strtotime($contact->getDate())); ?></td>
			<td data-label="portName"><?php echo port::getPortName($contact->getPortID()); ?></td>			
			<td data-label="userName"<?php echo (!empty($contact->getContactName()))?' class="three wide"':''; ?>><?php echo $contact->getContactName(); ?></td>
			<td data-label="contactType">
			<?php echo $contact->getContactType(); if(!empty($contact->getVesselContactMail())) { ?>
                <a class="item" onClick="vessel.getVesselContactMail(<?php echo $contact->getID(); ?>);"><i class="envelope outline icon"></i></a>
            <?php } ?>
			</td>
			<td data-label="agency"><?php echo agency::getAgentShort($contact->getAgentID()); ?></td>
			<td data-label="agency"><?php echo $contact->getInfo(); ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>
<div class="detailActions ui icon menu">
	<a class="item" onclick="vessel.newVesselContact(<?php echo $vessel->getID(); ?>);">
		<i class="plus icon"></i>
	</a>
	<a class="item" onclick="vessel.newVesselContact(<?php echo $vessel->getID(); ?>, $('input[name=selectContact]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="vessel.deleteVesselContact(<?php echo $vessel->getID(); ?>, $('input[name=selectContact]:checked').val());">
		<i class="trash alternate icon"></i>
	</a>
</div>

<?php } else { ?>
<div id="detailEmpty">
	<div><img src="../resources/img/iconVessel.png" /></div>
	<div>Kein Schiff ausgewählt</div>
</div>

<div id="vesselForecast"></div>
<?php }?>