<?php 
namespace bo\views\lookup;

use bo\components\classes\helper\dbConnect;
use bo\components\classes\vessel;
use bo\components\types\contactTypes;

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
    	<div class="elemDetailName"><?php echo $vessel->getIMO(); ?></div>
    	
    	<div class="label">MMSI:</div>
    	<div class="elemDetailName"><?php echo $vessel->getMMSI(); ?></div>
    </div>
</div>

<table class="detailTable unstackable ui very compact celled striped table lookupTable">
	<thead>
		<tr>
			<th colspan="2">Available Information</th>
		</tr>
	</thead>
    <tbody>
   		<tr>
			<td data-label="type">Email</td>
			<td data-label="status" class="center aligned"><?php echo ($vessel->hasMail)?'<i class="check icon"></i>':'<i class="thumbs down outline icon"></i>'; ?></td>			
		</tr>
		<tr>
			<td data-label="type">Phone</td>
			<td data-label="status" class="center aligned"><?php echo ($vessel->hasPhone)?'<i class="check icon"></i>':'<i class="thumbs down outline icon"></i>'; ?></td>			
		</tr>
		<tr>
			<td data-label="type">Last contact</td>
			<td data-label="status" class="center aligned"><?php 
			$lastDate = '<i class="thumbs down outline icon"></i>';
			$lastContactKey = 'false';
			foreach($vessel->getVesselContact() as $key => $vesselContact) {
			    if($vesselContact->getPlanned() == 0) {
			        $lastDate = $vesselContact->getDate();
			        $lastContactKey = $key;
			        break;
			    }
			}
			echo $lastDate;
			?></td>			
		</tr>
		<tr>
			<td data-label="type">Last contact type</td>
			<td data-label="status" class="center aligned"><?php 
			if(isset($vessel->getVesselContact()[$lastContactKey])) {
                echo contactTypes::$translateContactTypes[$vessel->getVesselContact()[$lastContactKey]->getContactType()];
                echo ($vessel->getVesselContact()[$lastContactKey]->getContactType() == 'Email' && !$vessel->hasMail)?' by agency':'';
			}
			?></td>			
		</tr>
    </tbody>
</table>
<div id="requestMsg" class="msgForm"></div>
<div id="requestInfo"><button onClick="lookup.requestInformation(<?php echo $vessel->getID(); ?>);">Request more information</button></div>

<?php } else { ?>
<div id="detailEmpty">
	<div><img src="../resources/img/iconVessel.png" /></div>
	<div>No vessel selected</div>
	<div>Please use the search on the left</div>
</div>
<?php }?>