<?php
namespace components\controller;

use components\classes\dbConnect;

include '../config.php';

$searchExpression1 = '%' . $_GET['expression'] . '%';
$searchExpression2 = $_GET['expression'] . '%';

switch ($_GET['type']) {
    case 'vessel':
        $sqlstrg = "select * 
                      from port_bo_vessel 
                     where name like ? 
                        or IMO like ? 
                        or ENI like ? 
                     order by ts_erf desc limit 20";
        $parameter = array($searchExpression1, $searchExpression2, $searchExpression2);
        break;
    case 'userForContact':
        $sqlstrg = "select * from port_bo_user where first_name like ? or surname like ? limit 10";
        $parameter = array($searchExpression1, $searchExpression1);
        break;
    case 'agentForContact':
        $sqlstrg = "select * from port_bo_agency where name like ? or short like ? order by ts_erf desc limit 10";
        $parameter = array($searchExpression1, $searchExpression1);
        break;
    case 'agency':
        $sqlstrg = "select * from port_bo_agency where name like ? or short like ? order by ts_erf desc limit 20";
        $parameter = array($searchExpression1, $searchExpression1);
        break;
}

$result = dbConnect::execute($sqlstrg, $parameter);

switch ($_GET['type']) {
    case 'vessel':
        while($row = $result->fetch()) {?>
	    <div class="searchResultRow"><a onClick="vessel.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
		<?php }
        break;
    case 'userForContact':
        while($row = $result->fetch()) {?>
        	<div onclick="selectSuggested('user', this.textContent);"><?php echo $row['first_name'] . " " . $row['surname']; ?></div>
        <?php }
        break;
    case 'agentForContact':
        while($row = $result->fetch()) {?>
    	    <div onclick="selectSuggested('agent', this.textContent);"><?php echo $row['name']; ?></div>
		<?php }
        break;
    case 'agency':
        while($row = $result->fetch()) {?>
    	    <div class="searchResultRow"><a onClick="agency.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
    	<?php }
        break;
}

?>