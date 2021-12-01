<?php 
namespace views\agency;

use components\classes\agency;
use components\classes\port;
use components\classes\vessel;
use components\classes\dbConnect;
use components\classes\vesselContact;

include '../../components/config.php';

if(!empty($_GET["id"])) {
    $agency = dbConnect::fetchSingle("select * from port_bo_agency where id = ?", agency::class, array($_GET["id"]));
    $_SESSION['agencyID'] = $agency->getID();
    $vesselContacts = dbConnect::fetchAll("select * from port_bo_vesselContact where agent_id = ? order by date desc", vesselContact::class, array($agency->getID()));
?>
<div class="elementDetailWrapper ui segment">
	<div class="elemDetail agency">
    	<div class="elemDetailIcon">
    		<img src="../resources/img/iconAgent.png" />
    	</div>

    	<div class="label">Name:</div>
    	<div class="elemDetailName"><div><?php echo $agency->getName(); ?></div></div>
    
    	<div class="label">Kürzel:</div>
    	<div><?php echo $agency->getShort(); ?></div>
    </div>
</div>
<div class="detailActions ui icon menu">
	<a class="item" onclick="agency.newAgency(<?php echo $agency->getID(); ?>)">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="alert('Agenten können derzeit nur durch einen Administrator gelöscht werden.');">
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
    <?php foreach ($agency->getAgencyPortInfo() as $info) { ?>
		<tr>
			<td data-label="select"><input type="radio" name="selectAgencyDetail" value="<?php echo $info->getID();?>"></td>
			<td data-label="portName"><?php echo port::getPortName($info->getPortID()); ?></td>			
			<td data-label="email"><?php echo $info->getEmail(); ?></td>
			<td data-label="info"><?php echo $info->getInfo(); ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>
<div class="detailActions ui icon menu">
	<a class="item" onclick="agency.newAgencyPortInfo(<?php echo $agency->getID(); ?>);">
		<i class="plus icon"></i>
	</a>
	<a class="item" onclick="agency.newAgencyPortInfo(<?php echo $agency->getID(); ?>, $('input[name=selectAgencyDetail]:checked').val(), true);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="agency.deleteAgencyPortInfo(<?php echo $agency->getID(); ?>, $('input[name=selectAgencyDetail]:checked').val());">
		<i class="trash alternate icon"></i>
	</a>
</div>

<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="7">Kontakte zu diesem Agenten:</th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($vesselContacts as $vesselContact) { ?>
		<tr>
			<td data-label="timestamp"><?php echo $vesselContact->getDate(); ?></td>
			<td data-label="contactType"><?php echo $vesselContact->getContactType(); ?></td>			
			<td data-label="vesselName"><?php echo vessel::getVesselName($vesselContact->getVesselID()); ?></td>
			<td data-label="contactName"><?php echo $vesselContact->getContactName(); ?></td>
		</tr>
	<?php } ?>
    </tbody>
</table>

<?php } else { ?>
<div id="detailEmpty">
	<div><img src="../resources/img/iconAgent.png" /></div>
	<div>Keine Agentur ausgewählt</div>
</div>
<?php }?>
