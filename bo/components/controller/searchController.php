<?php
namespace bo\components\controller;

use bo\components\classes\helper\Query;
use bo\components\classes\Vessel;
use bo\components\classes\Dry;
use bo\components\classes\User;
use bo\components\classes\Agency;

include '../config.php';

$searchExpression1 = '%' . trim($_GET['expression']) . '%';
$searchExpression2 = trim($_GET['expression']) . '%';

$vesselQuery = (new Query("select"))
    ->table(Vessel::TABLE_NAME)
    ->or()
    ->conditionLike(["name" => $searchExpression1, "IMO" => $searchExpression2, "ENI" => $searchExpression2, "MMSI" => $searchExpression2])
    ->order("ts_erf desc");

switch ($_GET['type']) {
    case 'vessel':
        $result = $vesselQuery
            ->limit("20")
            ->execute();
            
        while($row = $result->fetch()) {?>
	    	<div class="searchResultRow"><a onClick="vessel.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
		<?php }
        break;
        
    case 'vesselDrySearch':
        $result = (new Query("select"))
            ->table(Dry::TABLE_NAME)
            ->or()
            ->conditionLike(["name" => $searchExpression1, "imo" => $searchExpression2])
            ->limit("5")
            ->execute();
            
        while($row = $result->fetch()) {?>
    	    <div class="searchResultRow"><i class="gb uk flag"></i> <?php echo $row['name']; ?></div>
   		<?php }
        break;
        
    case 'vesselLookup':
        $result = $vesselQuery
            ->limit("10")
            ->execute();
            
        while($row = $result->fetch()) {?>
	    	<div class="searchResultRow"><a onClick="lookup.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
		<?php }
        break;
        
    case 'userForContact':
        $result = (new Query("select"))
            ->table(User::TABLE_NAME)
            ->or()
            ->conditionLike(["first_name" => $searchExpression1, "surname" => $searchExpression1])
            ->limit("10")
            ->execute();
        
        while($row = $result->fetch()) {?>
        	<div onclick="selectSuggested('user', this.textContent);"><?php echo $row['first_name'] . " " . $row['surname']; ?></div>
        <?php }            
        break;
        
    case 'agentForContact':
        $result = (new Query("select"))
            ->table(Agency::TABLE_NAME)
            ->or()
            ->conditionLike(["name" => $searchExpression1, "short" => $searchExpression1])
            ->order("ts_erf desc")
            ->limit("10")
            ->execute();
            
        while($row = $result->fetch()) {?>
    	    <div onclick="selectSuggested('agent', this.textContent);"><?php echo $row['name']; ?></div>
		<?php }
        break;
        
    case 'agency':
        $result = (new Query("select"))
            ->table(Agency::TABLE_NAME)
            ->or()
            ->conditionLike(["name" => $searchExpression1, "short" => $searchExpression1])
            ->order("ts_erf desc")
            ->limit("20")
            ->execute();
            
        while($row = $result->fetch()) {?>
    	    <div class="searchResultRow"><a onClick="agency.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
    	<?php }
        break;
}
?>