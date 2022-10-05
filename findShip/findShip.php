<?php
namespace findShip;

use bo\components\classes\forecast\Eurogate;
use bo\components\classes\forecast\PortTicker;
use bo\components\classes\forecast\HHLA;
use bo\components\classes\forecast\Unikai;
use bo\components\classes\forecast\Fleetmon;
use bo\components\classes\forecast\Shipnext;
use bo\components\classes\Port;
use bo\components\classes\forecast\POA;
use bo\components\classes\forecast\PWL;
use bo\components\classes\forecast\POR;
use bo\components\classes\helper\Logger;

$independent = true;
include '../bo/components/config.php';
include '../bo/components/libs/simple_html_dom.php';

echo "<h2>Eurogate</h2>";
$eurogate = new Eurogate();
if($eurogate->getForecast()) {
    echo "<table>";
    foreach ($eurogate->expectedVessels as $vessel) {
        echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['arrivalTime'] . "</td><td>" . $vessel['leavingDateTime'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td></tr>";
    }
    echo "</table>";
}

echo "<h2>PortTicker</h2>";
$portTicker = new PortTicker();
$portTicker->getForecast();

echo "<table>";
foreach ($portTicker->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

echo "<h2>HHLA</h2>";
$hhla = new HHLA();
$hhla->getForecast();

echo "<table>";
foreach ($hhla->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

echo "<h2>Unikai</h2>";
$unikai = new Unikai();
$unikai->getForecast();

echo "<table>";
foreach ($unikai->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['agency'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

echo "<h2>Fleetmon</h2>";
$fleetmon = new Fleetmon();
$fleetmon->getForecast();

echo "<table>";
foreach ($fleetmon->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td><td>" . $vessel['company'] . "</td></tr>";
}
echo "</table>";

echo "<h2>Shipnext</h2>";
$shipnext = new Shipnext();
$shipnext->getForecast();

echo "<table>";
foreach ($shipnext->expectedVessels as $vessel) {
    echo "<tr><td>" . $vessel['quelle'] . "</td><td>" . Port::getPortName($vessel['port']) . "</td><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td></tr>";
}
echo "</table>";

echo "<h2>POA</h2>";
$poa = new POA();
$poa->getForecast();

echo "<table>";
foreach ($poa->expectedVessels as $vessel) {
    echo "<tr><td>" . Port::getPortName($vessel['port']) . "</td><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td></tr>";
}
echo "</table>";

echo "<h2>POR</h2>";
$por = new POR();
$por->getForecast();

echo "<table>";
foreach ($por->expectedVessels as $vessel) {
    echo "<tr><td>" . Port::getPortName($vessel['port']) . "</td><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td></tr>";
}
echo "</table>";

echo "<h2>PWL</h2>";
$pwl = new PWL();
$pwl->getForecast();

echo "<table>";
foreach ($pwl->expectedVessels as $vessel) {
    echo "<tr><td>" . Port::getPortName($vessel['port']) . "</td><td>" . $vessel['arrivalDate'] . "</td><td>" . $vessel['name'] . "</td><td>" . $vessel['imo'] . "</td></tr>";
}
echo "</table>";

Logger::writeLogInfo("findShip", "Scraping abgeschlossen");

?>
