<?php 
use bo\components\classes\forecast\POA;
use bo\components\classes\forecast\POR;

$independent = true;
include '../bo/components/config.php';
include '../bo/components/libs/simple_html_dom.php';

$por = new POR();

$por->getForecast();
?>