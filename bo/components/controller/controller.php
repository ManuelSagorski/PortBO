<?php
namespace bo\components\controller;

include '../config.php';

$url  = (isset($_GET['_url']) ? $_GET['_url'] : '');
$urlParts = explode('/', $url);

$controllerName = (isset($urlParts[0]) && $urlParts[0] ? $urlParts[0] : 'index');
$controllerClassName = "\\bo\\components\\controller\\".ucfirst($controllerName).'Controller';

$actionName = (isset($urlParts[1]) && $urlParts[1] ? $urlParts[1] : 'index');
$actionMethodName = ucfirst($actionName);

$controller = new $controllerClassName();
$controller->$actionMethodName();
?>