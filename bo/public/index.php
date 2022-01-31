<?php
include '../components/config.php';

if($_SESSION['userLevel'] < 4) {
    header('Location: ' . PUBLIC_PATH . 'lookup.php');
}

ob_start();
include '../views/templates/_homeSite.template.php';
ob_end_flush();
?>