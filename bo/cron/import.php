<?php
namespace bo\cron;

use bo\components\classes\dbConnect;
use bo\components\classes\vessel;

$independent = true;
include '../components/config.php';

$result = dbConnect::execute("select * from tmp_import where status = 0", Array());

while($row = $result->fetch()) {
    $vessel = dbConnect::fetchSingle("select * from port_bo_vessel where IMO = ?", vessel::class, Array($row['imo']));
    
    if(!empty($vessel)) {
        $emailResult = dbConnect::execute("select * from port_bo_vesselContactDetails where vessel_id = ? and detail = ?", Array($vessel->getID(), $row['email']));
        if($emailResult->rowCount() == 0) {
            echo $row['name'] . "<br>";
            dbConnect::execute("insert into port_bo_vesselContactDetails (vessel_id, type, detail) value (?, 'Email', ?)", Array($vessel->getID(), $row['email']));
            dbConnect::execute("update tmp_import set status = 1 where id = ?", Array($row['id']));
        }
    }
    else {
        dbConnect::execute("insert into port_bo_vessel (name, imo) values (?, ?)", Array($row['name'], $row['imo']));
        
        $vessel = dbConnect::fetchSingle("select * from port_bo_vessel where IMO = ?", vessel::class, Array($row['imo']));
        
        $emailResult = dbConnect::execute("select * from port_bo_vesselContactDetails where vessel_id = ? and detail = ?", Array($vessel->getID(), $row['email']));
        if($emailResult->rowCount() == 0) {
            echo $row['name'] . "<br>";
            dbConnect::execute("insert into port_bo_vesselContactDetails (vessel_id, type, detail) value (?, 'Email', ?)", Array($vessel->getID(), $row['email']));
            dbConnect::execute("update tmp_import set status = 1 where id = ?", Array($row['id']));
        }
    }
}

?>