<?php
namespace views\vessel;

use components\classes\dbConnect;
use components\classes\forecast;
use components\classes\port;

include '../../components/config.php';
?>

<div class="ui accordion">

<?php 
foreach($user->getUserPorts() as $userPorts) { 
    $forecast = dbConnect::fetchAll("select * from port_bo_scedule where port_id = ? order by arriving", forecast::class, Array($userPorts->getPortID()));
    $arrivingDay = "";
?>

	<div class="title">
    	<i class="dropdown icon"></i>
    	Forecast für <?php echo port::getPortName($userPorts->getPortID()); ?>
	</div>
	<div class="content">
        <table class="detailTable ui very compact celled striped table">
        	<thead>
        		<tr>
        			<th colspan="8">Erwartete Schiffe für <?php echo port::getPortName($userPorts->getPortID()); ?></th>
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
					<td colspan="8"><?php echo $tmpDay->format('Y-m-d'); ?></td>
				</tr>
    <?php    
    }
?>
        		<tr<?php echo ($expectedVessel->getStatus() == 1)?' class="disabled"':'';?>>
        			<td data-label="arriving"><?php echo $expectedVessel->getArriving(); ?></td>			
        			<td data-label="leaving"><?php echo $expectedVessel->getLeaving(); ?></td>
        			<td data-label="name"><?php echo $expectedVessel->getName(); ?></td>
    				<td data-label="inSystem" class="center aligned"><?php echo (!empty($expectedVessel->vessel))?'<a onClick="vessel.openDetails(' . $expectedVessel->vessel->getID() . ');"><i class="address card outline icon"></i></a>':''; ?></td>
        			<td data-label="email" class="center aligned"><?php echo ($expectedVessel->hasMail)?'<i class="envelope outline icon"></i>':''; ?></td>
        			<td data-label="company"><?php echo $expectedVessel->getCompany(); ?></td>
        			<td data-label="agency"><?php echo $expectedVessel->getAgency(); ?></td>
        			<td data-label="done" class="center aligned">
        			<?php if($expectedVessel->getStatus() == 0) { ?>
        				<a onClick="vessel.forecastItemDone(<?php echo $expectedVessel->getID(); ?>, this);"><i class="check icon"></i></a>
        			<?php } ?>
        			</td>
        		</tr>
<?php } ?>
            </tbody>
        </table>
	</div>
<?php } ?>	
</div>

<script>
$('.ui.accordion')
  .accordion()
;
</script>