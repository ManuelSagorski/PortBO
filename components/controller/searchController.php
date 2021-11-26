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
}

$result = dbConnect::execute($sqlstrg, $parameter);

switch ($_GET['type']) {
    case 'vessel':
        while($row = $result->fetch()) {?>
	    <div class="searchResultRow"><a onClick="vessel.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
		<?php }
        break;
}

?>