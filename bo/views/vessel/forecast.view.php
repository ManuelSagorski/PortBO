<?php
namespace bo\views\vessel;

use bo\components\classes\dbConnect;
use bo\components\classes\forecast;
use bo\components\classes\port;

include '../../components/config.php';
?>

<div class="ui accordion">

<?php 
foreach($user->getUserPorts() as $key => $userPorts) { 
    $forecast = dbConnect::fetchAll("select * from port_bo_scedule where port_id = ? order by arriving", forecast::class, Array($userPorts->getPortID()));
    $arrivingDay = "";
?>

	<div class="title">
    	<i class="dropdown icon"></i>
    	Forecast für <?php echo port::getPortName($userPorts->getPortID()); ?>
	</div>
	<div class="content">
		<form id="addForecast<?php echo $userPorts->getPortID(); ?>" class="addForecast">
            <table class="detailTable ui very compact celled striped table">
            	<thead>
            		<tr>
            			<th>ETA</th>
            			<th>ETD</th>
            			<th>Name</th>
            			<th></th>
            			<th></th>
            			<th>Terminal</th>
            			<th>Makler?</th>
            			<th></th>
            			<th></th>
            		</tr>
            	</thead>
                <tbody>
<?php 
foreach($forecast as $expectedVessel) {
    $tmpDay = new \DateTime($expectedVessel->getArriving());
    if($tmpDay->format('Y-m-d') != $arrivingDay) {
        $arrivingDay = $tmpDay->format('Y-m-d');
    ?>
    				<tr class="positive">
    					<td colspan="9"><?php echo $tmpDay->format('Y-m-d'); ?></td>
    				</tr>
    <?php    
    }
?>
            		<tr<?php echo ($expectedVessel->getStatus() == 1)?' class="forecastDisabled"':'';?>>
            			<td data-label="arriving"><?php echo $expectedVessel->getArriving(); ?></td>			
            			<td data-label="leaving"><?php echo $expectedVessel->getLeaving(); ?></td>
            			<td data-label="name" title="<?php echo $expectedVessel->getName(); ?>"><?php echo $expectedVessel->getName(); ?></td>
        				<td data-label="inSystem" class="center aligned"><?php echo (!empty($expectedVessel->vessel))?'<a onClick="vessel.openDetails(' . $expectedVessel->vessel->getID() . ');"><i class="address card outline icon"></i></a>':''; ?></td>
            			<td data-label="email" class="center aligned">
            				<?php echo ($expectedVessel->hasMail)?'<i class="envelope outline icon"></i>':''; ?>
            				<?php echo ($expectedVessel->inDry)?'<i class="gb uk flag"></i>':''; ?>
            				<?php echo ($expectedVessel->expectMail)?'<i class="question circle outline icon"></i>':''; ?>
            			</td>
            			<td data-label="company"><?php echo $expectedVessel->getCompany(); ?></td>
            			<td data-label="agency" title="<?php echo $expectedVessel->getAgency(); ?>"><?php echo $expectedVessel->getAgency(); ?></td>
            			<td data-label="done" class="center aligned">
            			<?php if($expectedVessel->getStatus() == 0) { ?>
            				<a onClick="vessel.forecastItemDone(<?php echo $expectedVessel->getID(); ?>, this);"><i class="check icon"></i></a>
            			<?php } else { ?>
            				<a onClick="vessel.forecastItemReopen(<?php echo $expectedVessel->getID(); ?>, this);"><i class="undo icon"></i></a>
            			<?php } ?>
            			</td>
            			<td data-label="remove" class="center aligned">
            				<a onClick="vessel.forecastItemRemove(<?php echo $expectedVessel->getID(); ?>, this);"><i class="trash icon"></i></a>
            			</td>
            		</tr>
<?php } ?>
    				<tr>
						<td><div id="input_eta" class="field"><input type="date" name="eta" id="eta"></div></td>
						<td><input type="date" name="etd" id="etd"></td>
						<td><div id="input_name" class="field"><input type="text" name="name" id="name"></div></td>
						<td colspan="2">
							<input type="hidden" name="portID" value="<?php echo $userPorts->getPortID(); ?>">
							<input type="hidden" name="accordionID" value="<?php echo $key; ?>">
						</td>
						<td><input type="text" name="terminal" id="terminal"></td>
						<td><input type="text" name="agency" id="agency"></td>
						<td class="right aligned" colspan="2"><button class="ui icon button" type="submit"><i class="save outline icon"></i></button></td>
    				</tr>
                </tbody>
            </table>
    	</form>
	</div>
<?php } ?>	
</div>

<script>
$('.ui.accordion').accordion();
$(".addForecast").submit(function(event){ vessel.addForecast(this.id); });
</script>