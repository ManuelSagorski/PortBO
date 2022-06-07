<?php
namespace bo\views\vessel;
use bo\components\classes\Forecast;
use bo\components\classes\Port;
use bo\components\classes\Vessel;
use bo\components\classes\helper\Query;

include '../../components/config.php';
if(!$project->getModForecast())
    exit;
?>

<div class="ui accordion">

<?php 
foreach($user->getUserPorts() as $key => $userPorts) { 
    $forecast = Forecast::getForecastForPort($userPorts->getID());
    $arrivingDay = "";
?>

	<div class="title">
    	<i class="dropdown icon"></i>
    	Forecast - <?php echo Port::getPortName($userPorts->getID()); ?> (<?php echo Forecast::getCountOpenForecastPort($userPorts->getID()); ?>)
	</div>
	<div class="content">
		<form id="addForecast<?php echo $userPorts->getID(); ?>" class="addForecast">
            <table class="detailTable ui very compact celled striped table">
            	<thead>
            		<tr>
            			<th>ETA / ETD</th>
            			<th>Name / IMO</th>
            			<th></th>
            			<th></th>
            			<th>Last cont.</th>
            			<th>Terminal / Agent</th>
            			<th></th>
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
            			<td data-label="etad">
            				<div><?php echo $expectedVessel->getArriving(); ?></div>
            				<div><?php echo $expectedVessel->getLeaving(); ?></div>
            			</td>			
            			<td data-label="name_imo" title="<?php echo $expectedVessel->getName(); ?>">
            				<div><?php echo $expectedVessel->getName(); ?><?php echo ($expectedVessel->getType() == 'tanker')?' <i class="tint icon"></i>':''; ?></div>
            				<div><?php echo $expectedVessel->getIMO(); ?></div>
            			</td>
        				<td data-label="inSystem" class="center aligned collapsing">
        				<?php echo (!empty($expectedVessel->vessel))?'
                            <a onClick="vessel.openDetails(' . $expectedVessel->vessel->getID() . '); safeForecastPosition();">
                                <i class="address card outline icon"></i>
                            </a>':''; ?>
        				</td>
            			<td data-label="email" class="center aligned collapsing">
            				<?php echo ($expectedVessel->hasMail)?'<i class="envelope outline icon"></i>':''; ?>
            				<?php echo ($expectedVessel->hasPhone)?'<i class="phone icon"></i>':''; ?>
            				<?php echo ($expectedVessel->inDry)?'<i class="gb uk flag"></i>':''; ?>
            				<?php echo ($expectedVessel->expectMail)?'<i class="question circle outline icon"></i>':''; ?>
            			</td>
            			<td data-label="last_contact" class="collapsing">
            			<?php if (!empty($expectedVessel->vessel)) { ?>
                			<div<?php echo (strtotime(Vessel::getLastContactVessel($expectedVessel->vessel->getID())) >= strtotime(date("Y-m-d")))?" class='contactFuture'":''; ?>>
                				<?php echo Vessel::getLastContactVessel($expectedVessel->vessel->getID()); ?>
                			</div>                			    
                		<?php } ?>
            			</td>
            			<td data-label="agency_company">
            				<div><?php echo $expectedVessel->getCompany(); ?></div>
            				<div><?php echo $expectedVessel->getAgency(); ?></div>
            			</td>
            			<td data-label="createSchedule" class="center aligned collapsing">
            				<?php if (!empty($expectedVessel->vessel)) { ?>
            				<a onClick="vessel.createScheduledContact(<?php echo $expectedVessel->vessel->getID(); ?>, <?php echo $userPorts->getID(); ?>, '<?php echo $tmpDay->format('Y-m-d'); ?>'); safeForecastPosition();"><i class="calendar plus outline icon"></i></a>
            				<?php } ?>
            			</td>
            			<td data-label="done" class="center aligned collapsing">
            			<?php if($expectedVessel->getStatus() == 0) { ?>
            				<a onClick="vessel.forecastItemDone(<?php echo $expectedVessel->getID(); ?>, this);"><i class="check icon"></i></a>
            			<?php } else { ?>
            				<a onClick="vessel.forecastItemReopen(<?php echo $expectedVessel->getID(); ?>, this);"><i class="undo icon"></i></a>
            			<?php } ?>
            			</td>
            			<td data-label="remove" class="center aligned collapsing">
            				<a onClick="vessel.forecastItemRemove(<?php echo $expectedVessel->getID(); ?>, this);"><i class="trash icon"></i></a>
            			</td>
            		</tr>
<?php } ?>
    				<tr>
						<td>
							<div id="input_eta" class="field"><input type="date" name="eta" id="eta"></div>
							<div id="input_etd" class="field"><input type="date" name="etd" id="etd"></div>
						</td>
						<td><div id="input_name" class="field"><input type="text" name="name" id="name"></div></td>
						<td colspan="2">
							<input type="hidden" name="portID" value="<?php echo $userPorts->getID(); ?>">
							<input type="hidden" name="accordionID" value="<?php echo $key; ?>">
						</td>
						<td><input type="text" name="terminal" id="terminal"></td>
						<td><input type="text" name="agency" id="agency"></td>
						<td class="right aligned" colspan="3"><button class="ui icon button" type="submit"><i class="save outline icon"></i></button></td>
    				</tr>
                </tbody>
            </table>
    	</form>
	</div>
<?php } ?>	
</div>

<script>
$('.ui.accordion').accordion({
	onOpening: function() {
		forecastAccordionOpen = this.index(".content");
	}
});
$(".addForecast").submit(function(event){ vessel.addForecast(this.id); });
</script>