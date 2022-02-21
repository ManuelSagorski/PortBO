<?php
/*
 * import.php - zum manuellen Import von Schiffen in die Datenbank 
 */

namespace bo\cron;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\Vessel;

$independent = true;
include '../components/config.php';

$result = DBConnect::execute("select * from tmp_import where status = 0", Array());

while($row = $result->fetch()) {
    $vessel = Vessel::getSingleObjectByCondition(Array("IMO" => $row['imo']));
    
    if(!empty($vessel)) {
        $emailResult = DBConnect::execute("select * from port_bo_vesselContactDetails where vessel_id = ? and detail = ?", Array($vessel->getID(), $row['email']));
        if($emailResult->rowCount() == 0) {
            echo $row['name'] . "<br>";
            DBConnect::execute("insert into port_bo_vesselContactDetails (vessel_id, type, detail) value (?, 'Email', ?)", Array($vessel->getID(), $row['email']));
            DBConnect::execute("update tmp_import set status = 1 where id = ?", Array($row['id']));
        }
    }
    else {
        DBConnect::execute("insert into port_bo_vessel (name, imo) values (?, ?)", Array($row['name'], $row['imo']));
        
        $vessel = Vessel::getSingleObjectByCondition(Array("IMO" => $row['imo']));
        
        $emailResult = DBConnect::execute("select * from port_bo_vesselContactDetails where vessel_id = ? and detail = ?", Array($vessel->getID(), $row['email']));
        if($emailResult->rowCount() == 0) {
            echo $row['name'] . "<br>";
            DBConnect::execute("insert into port_bo_vesselContactDetails (vessel_id, type, detail) value (?, 'Email', ?)", Array($vessel->getID(), $row['email']));
            DBConnect::execute("update tmp_import set status = 1 where id = ?", Array($row['id']));
        }
    }
}

?>