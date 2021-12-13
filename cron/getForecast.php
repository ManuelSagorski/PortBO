<?php
namespace cron;

use components\classes\forecast\eurogate;
use components\classes\forecast\portTicker;
use components\classes\forecast\hhla;
use components\classes\forecast\unikai;

$independent = true;
include '../components/config.php';
include '../components/libs/simple_html_dom.php';


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

?>