<?php 
use bo\components\classes\forecast\POA;

$independent = true;
include '../bo/components/config.php';
include '../bo/components/libs/simple_html_dom.php';

$poa = new POA();

$poa->getForecast();
?>