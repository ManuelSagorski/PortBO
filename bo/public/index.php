<?php
include '../components/config.php';

if($_SESSION['userLevel'] < 4) {
    header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/lookup.php');
}

ob_start();
include '../views/templates/_homeSite.template.php';
ob_end_flush();
?>