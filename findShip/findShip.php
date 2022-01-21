<?php
namespace findShip;

use bo\components\classes\forecast\eurogate;
use bo\components\classes\forecast\portTicker;
use bo\components\classes\forecast\hhla;
use bo\components\classes\forecast\unikai;
use bo\components\classes\forecast\fleetmon;
use bo\components\classes\forecast\shipnext;
use bo\components\classes\port;

$independent = true;
include '../bo/components/config.php';
include '../bo/components/libs/simple_html_dom.php';

$eurogate = new eurogate();
if($eurogate->getForecast()) {
    echo "<table>";
    foreach ($eurogate->expectedVessels as $vessel) {
        echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['arrivalTime'] . "</td><td>" . $vessel['leavingDateTime'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td></tr>";
    }
    echo "</table>";
}

$portTicker = new portTicker();
$portTicker->getForecast();

echo "<table>";
foreach ($portTicker->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

$hhla = new hhla();
$hhla->getForecast();

echo "<table>";
foreach ($hhla->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";


$unikai = new unikai();
$unikai->getForecast();

echo "<table>";
foreach ($unikai->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";


$fleetmon = new fleetmon();
$fleetmon->getForecast();

echo "<table>";
foreach ($fleetmon->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

$shipnext = new shipnext();
$shipnext->getForecast();

echo "<table>";
foreach ($shipnext->expectedVessels as $vessel) {
    echo "<tr><td>" . port::getPortName($vessel['port']) . "</td><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td></tr>";
}
echo "</table>";

?>