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

spl_autoload_register("autoload");

/**
 * 
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function endsWith($haystack, $needle) {
    return substr($haystack, -strlen($needle)) == $needle;
}

/**
 * Given object and string, make object call each part of string.
 * i.e. $obj->server()->order()->address() given string 'server_order_address'
 * @param mixed $object
 * @param string $path
 * @return mixed
 * @throws Exception
 */
function fieldToPath($object, $path) {
    if (!is_object($object)) {
        throw new Exception('Object not found');
    }
    $parts = explode('_', $path);
    if (count($parts) < 1) {
        throw new Exception('No path is given');
    }
    foreach ($parts as $method) {
        try {
            $object = $object->$method();
        } catch (Exception $ex) {
            throw new Exception("Method $method Doesn't Exist");
        }
    }
    return $object;
}

/**
 * Convert _letter => Capital Letter
 * @param string $name
 * @return string
 */
function field2name($field) {
    return str_replace(
                    array('_a', '_b', '_c', '_d', '_e', '_f', '_g', '_h', '_i', '_j', '_k', '_l', '_m', '_n', '_o', '_p', '_q', '_r', '_s', '_t', '_u', '_v', '_w', '_x', '_y', '_z'), array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'), lcfirst($field)
    );
}

function getConfig() {
    $fileName = ROOT . DS . "public" . DS . "config" . DS . "configuration.ini";
    $ini_array = parse_ini_file($fileName, true);

    if (!isset($ini_array[ENV]['layout_enable'])) {
        $ini_array[ENV]['layout_enable'] = 0;
    }
    if (!isset($ini_array[ENV]['gear_namespace'])) {
        $ini_array[ENV]['gear_namespace'] = array();
    }

    return $ini_array;
}

/**
 * Given url and cache time, cache webpage for cachetime and retrieve new one when
 * time runs out
 * @param string $url
 * @param int $cacheTime seconds
 * @return string
 */
function getCacheFileContent($url, $cacheTime) {
    $fileName = sha1($url);
    $file = Gear_Cache::getFullfile($fileName, $cacheTime);
    if ($file) {
        return $file;
    } else {
        $file = file_get_contents($url);
        Gear_Cache::setFullFile($fileName, $file);
        return $file;
    }
}

function getLanguage() {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    return $lang;
}

/**
 * Get Last Part of Class Name 
 * @param string $name
 * @return string
 */
function getTableName($name) {
    $parts = explode('_', $name);
    return $parts[count($parts) - 1];
}

/**
 * Convert Capital Letter => _letter
 * @param string $name
 * @return string
 */
function name2field($name) {
    return str_replace(
                    array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'), array('_a', '_b', '_c', '_d', '_e', '_f', '_g', '_h', '_i', '_j', '_k', '_l', '_m', '_n', '_o', '_p', '_q', '_r', '_s', '_t', '_u', '_v', '_w', '_x', '_y', '_z'), lcfirst($name)
    );
}

/**
 * Print array as if print_r
 * @param array $array
 * @param boolean $return
 */
function printr($array, $return = false) {
    $str = '<pre>' . print_r($array, true) . '</pre>';
    if (!$return) {
        echo $str;
    } else {
        return $str;
    }
}

/**
 * <pre> [a,a,a,a,a,a,]</pre>
 * @param mixed $array
 * @param boolean $return false  print, true return
 * @return string
 */
function printl($array, $return = false) {
    $str = "<pre>[\n";
    for ($i = 0; $i < count($array); $i++) {
        $newStr = '';
        for ($j = 0; $j < count($array[0]); $j++) {
            $newStr .= $array[$i][$j] . ' ';
        }
        $str .= "[$newStr]\n";
    }
    $str .=']</pre>';
    if (!$return) {
        echo $str;
    } else {
        return $str;
    }
}

function removeMagicQuotes() {
    if (get_magic_quotes_gpc()) {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

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

/**
 * 
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function startsWith($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

?>
