<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('ENV', getenv('APPLICATION_ENV'));
define('BASE_PATH', $_SERVER['HTTP_HOST']);
date_default_timezone_set("America/Toronto");
$url = $_SERVER['REQUEST_URI'];
include 'common.php';
setReporting();
removeMagicQuotes();
//$moduleCreationFile = ROOT . DS . "public" . DS . "makemodule.php";
//if (file_exists($moduleCreationFile)) {
//    include $moduleCreationFile;
//    exit;
//}
Core_Session::getInstance();
$frontController = Core_FrontController::getInstance();
$frontController->handle($url);