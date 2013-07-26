<?php

function autoload($className) {
    $classPath = explode('_', $className);
    if (count($classPath) < 2) {
        throw new Exception("Unable to load $className.");
    }
    $path = ROOT;
    for ($i = 0; $i < count($classPath) - 1; $i++) {
        $path .= DS . strtolower($classPath[$i]);
    }
    $path .= DS . $classPath[$i];
    $path .= '.php';
    if (file_exists($path)) {
        require_once($path);
    } else {
        autoLoadExceptions($classPath);
    }
}

spl_autoload_register("autoload");

function setReporting() {
    error_reporting(E_ALL);
    if (ENV == 'development' || ENV == 'test') {
        ini_set('display_errors', 'On');
    } else {
        ini_set('display_errors', 'Off');
    }
    ini_set('log_errors', 'On');
    ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error_log');
}

function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

function removeMagicQuotes() {
    if (get_magic_quotes_gpc()) {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

function autoLoadExceptions($classPath) {
    $config = getConfig();
    $exception = $config[ENV]['gear_namespace'];
    $className = implode("_", $classPath);
    $first = $classPath[0];
    if (in_array($first, $exception)) {
        $path = ROOT . DS . 'gear';
        for ($i = 0; $i < count($classPath) - 1; $i++) {
            $path .= DS . $classPath[$i];
        }
        $path .= DS . $classPath[$i];
        $path .= '.php';
        if (file_exists($path)) {
            require_once($path);
        } else {
            throw new Exception("Unable to load $className.");
        }
    } else {
        throw new Exception("Unable to load $className.");
    }
}

function getConfig() {
    $fileName = ROOT . DS . "public" . DS . "config" . DS . "configuration.ini";
    $ini_array = parse_ini_file($fileName, true);
    return $ini_array;
}

?>
