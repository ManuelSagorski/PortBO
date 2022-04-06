<?php 
namespace bo\views\vessel;

use bo\components\classes\Agency;
use bo\components\classes\Port;
use bo\components\classes\Vessel;
use bo\components\classes\User;
use bo\components\classes\Projects;
use bo\components\classes\VesselContact;
use bo\components\classes\Language;

include '../../components/config.php';

if(!empty($_GET["id"])) {
    $vessel = Vessel::getSingleObjectByID($_GET["id"]);
    $_SESSION['vessID'] = $vessel->getID();
?>
<div class="elementDetailWrapper ui segment">
	<div class="elemDetail vessel">
    	<div class="elemDetailIcon">
    		<img src="../resources/img/iconVessel.png" />
    	</div>

    	<div class="label"><?php $t->_('name'); ?>:</div>
    	<div class="elemDetailName"><div><?php echo $vessel->getName(); ?></div></div>
    
    	<div class="label">IMO:</div>
    	<div><?php echo $vessel->getIMO(); ?></div>
    	
    	<div class="label">MMSI:</div>
    	<div><?php echo $vessel->getMMSI(); ?></div>
    	
    	<div class="label">ENI:</div>
    	<div><?php echo $vessel->getENI(); ?></div>    		
    
    	<div class="label"><?php $t->_('typ'); ?>:</div>
    	<div><?php echo $vessel->getTyp(); ?></div>
    	
    	<div class="label"><?php $t->_('nationalitis'); ?>:</div>
    	<div class="elemDetailLanguages"><?php echo $vessel->getLanguage(); ?></div>
    	
    	<?php if(!empty($vessel->getVesselLanguagesMaster())) { ?>
    	<div class="label languagesIndiv"><?php $t->_('language-master'); ?>:</div>
    	<div class="elemDetailLanguagesIndiv">
    	<?php foreach ($vessel->getVesselLanguagesMaster() as $key => $language) { 
    	    echo Language::getLanguageByID($language->getLanguageID());
    	    if($key != array_key_last($vessel->getVesselLanguagesMaster())) {
    	        echo ", ";
    	    }
    	} ?>
    	</div>
    	<?php }?>

    	<?php if(!empty($vessel->getVesselLanguagesCrew())) { ?>
    	<div class="label languagesIndiv"><?php $t->_('language-crew'); ?>:</div>
    	<div class="elemDetailLanguagesIndiv">
    	<?php foreach ($vessel->getVesselLanguagesCrew() as $key => $language) { 
    	    echo Language::getLanguageByID($language->getLanguageID());
    	    if($key != array_key_last($vessel->getVesselLanguagesCrew())) {
    	        echo ", ";
    	    }
    	} ?>
    	</div>
    	<?php }?>    	
    </div>
</div>
<div class="detailActions ui icon menu">
	<a class="item" onclick="vessel.newVessel(<?php echo $vessel->getID(); ?>);">
		<i class="edit icon"></i>
	</a>
	<a class="item" onClick="alert('<?php $t->_('delete-ships-only-admin'); ?>');">
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

<?php /*

###
Funktion VesselInfo nicht mehr gewünscht - wird zurückgebaut.
###

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
			<td data-label="userFullName"><?php echo User::getUserFullName($info->getUser()); ?></td>			
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
*/ ?>
<table class="detailTable ui very compact celled striped table">
	<thead>
		<tr>
			<th colspan="5"><?php $t->_('contact-details'); ?></th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($vessel->getVesselContactDetails() as $contactDetail) { ?>
		<tr<?php echo ($contactDetail->getInvalid())?" class='contactDetailInvalid'":""; ?>>
			<td data-label="select"><input type="radio" name="selectContactDetail" value="<?php echo $contactDetail->getID();?>"></td>
			<td data-label="contactDetailType"><?php echo $contactDetail->getType(); ?></td>			
			<td data-label="contactDetail"><?php echo $contactDetail->getDetail(); ?></td>
			<td data-label="supposed" class="center aligned collapsing contactIcon<?php echo ($contactDetail->getSupposed())?"":" iconDisabled"; ?><?php echo ($contactDetail->getInvalid())?" disabled":""; ?>">
				<i onClick="vessel.contactDetailSupposed(<?php echo $vessel->getID(); ?>, <?php echo $contactDetail->getID();?>);" class="question icon"></i>
			</td>
			<td data-label="invalid" class="center aligned collapsing contactIcon<?php echo ($contactDetail->getInvalid())?"":" iconDisabled"; ?>">
				<i onClick="vessel.contactDetailInvalid(<?php echo $vessel->getID(); ?>, <?php echo $contactDetail->getID();?>);" class="thumbs down outline icon"></i>
			</td>
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
			<th colspan="7"><?php $t->_('contacts'); ?></th>
		</tr>
		<tr>
			<th></th>
			<th><?php $t->_('date'); ?></th>
			<th><?php $t->_('port'); ?></th>
			<th><?php $t->_('contact-by'); ?></th>
			<th><?php $t->_('typ'); ?></th>
			<th><?php $t->_('agent'); ?></th>
			<th><?php $t->_('next-contact'); ?></th>
		</tr>
	</thead>
    <tbody>
    <?php foreach ($vessel->getVesselContact() as $contact) { ?>
		<tr<?php echo ($contact->getPlanned() == 1)?' class="planned"':''; ?>>
			<td data-label="select">
			<?php if ($contact->getProjectId() == $user->getProjectId()) { ?>
				<input type="radio" name="selectContact" value="<?php echo $contact->getID(); ?>">
			<?php } ?>	
			</td>
			<td data-label="timestamp"><?php echo date("d.m.Y", strtotime($contact->getDate())); ?></td>
			<td data-label="portName">
			<?php 
                echo Port::getPortName($contact->getPortID(), 0);
                echo ($contact->getProjectId() != $user->getProjectId())?" (" . Projects::getProjectShort($contact->getProjectId()) . ")":'';
            ?>
            </td>			
			<td 
				data-label="userName" 
				title="<?php if(!empty($contact->getContactUser())) { echo $contact->getContactUser()->getPhone(); }?>"
				<?php echo (!empty($contact->getContactUserID()))?' class="three wide"':''; ?>
			>
				<?php if(!empty($contact->getContactUser())) { echo "<a href='tel:" . $contact->getContactUser()->getPhone() . "'>"; }?>
					<?php if(!empty($contact->getContactUser())) { echo $contact->getContactUser()->getFirstName() . " " . $contact->getContactUser()->getSurname(); }?>
				<?php if(!empty($contact->getContactUser())) { echo "</a>"; }?>
			</td>
			<td data-label="contactType"><?php echo $contact->getContactType(); ?></td>
			<td data-label="agency"><?php echo ($contact->getProjectId() == $user->getProjectId())?Agency::getAgentShort($contact->getAgentID()):''; ?></td>
			<td data-label="next"><?php echo VesselContact::$monthNext[$contact->getMonthNext()]; ?></td>
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
	<div><?php $t->_('no-ship-selected'); ?></div>
</div>

<div id="vesselForecast">
	<div id="forecastLoader"><img src="../resources/img/loader.gif" /></div>
</div>
<div id="externLinks"></div>
<?php }?>