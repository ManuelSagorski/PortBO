<?php 
use components\classes\dbConnect;
use components\classes\vessel;

include '../../components/config.php';

if(!empty($_GET["id"])) {
    $vessel = dbConnect::fetchSingle("select * from port_bo_vessel where id = ?", vessel::class, array($_GET["id"]));
    $_SESSION['vessID'] = $vessel->getID();
?>
<div class="elementDetailWrapper ui raised segment">

	<div class="elemDetail">
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
    <div class="detailActions ui icon menu">
    	<a class="item" onclick="vessel.newVessel(<?php echo $vessel->getID(); ?>);">
    		<i class="edit icon"></i>
    	</a>
    	<a class="item" onClick="alert('Schiffe können derzeit nur durch einen Administrator gelöscht werden.');">
    		<i class="trash alternate icon"></i>
    	</a>
    	<a class="item" href="https://www.vesselfinder.com/de/?imo=<?php echo $vessel->getIMO(); ?>" target="_blank">
    		<img class="iconRowElement" src="../resources/img/vesselFinderLogo.png" />
    	</a>
    </div>	
</div>

<?php } else { ?>
<div id="detailEmpty">
	<div><img src="../resources/img/iconVessel.png" /></div>
	<div>Kein Schiff ausgewählt</div>
</div>
<?php }?>