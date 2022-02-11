<?php
namespace findShip;

use bo\components\classes\forecast\Eurogate;
use bo\components\classes\forecast\PortTicker;
use bo\components\classes\forecast\HHLA;
use bo\components\classes\forecast\Unikai;
use bo\components\classes\forecast\Fleetmon;
use bo\components\classes\forecast\Shipnext;
use bo\components\classes\Port;

$independent = true;
include '../bo/components/config.php';
include '../bo/components/libs/simple_html_dom.php';

$eurogate = new Eurogate();
if($eurogate->getForecast()) {
    echo "<table>";
    foreach ($eurogate->expectedVessels as $vessel) {
        echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['arrivalTime'] . "</td><td>" . $vessel['leavingDateTime'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td></tr>";
    }
    echo "</table>";
}

$portTicker = new PortTicker();
$portTicker->getForecast();

echo "<table>";
foreach ($portTicker->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

$hhla = new HHLA();
$hhla->getForecast();

echo "<table>";
foreach ($hhla->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";


$unikai = new Unikai();
$unikai->getForecast();

echo "<table>";
foreach ($unikai->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";


$fleetmon = new Fleetmon();
$fleetmon->getForecast();

echo "<table>";
foreach ($fleetmon->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

$shipnext = new Shipnext();
$shipnext->getForecast();

echo "<table>";
foreach ($shipnext->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['quelle'] . "</td><td>" . Port::getPortName($vessel['port']) . "</td><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td></tr>";
}
echo "</table> ";

?>
