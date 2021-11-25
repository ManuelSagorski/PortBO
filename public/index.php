<?php
include '../components/config.php';

ob_start();
include '../views/templates/_homeSite.template.php';
ob_end_flush();
?>