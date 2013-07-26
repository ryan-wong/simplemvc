<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @package Core
 * @category Main
 * @copyright (c) 2013, Ryan Wong
 */
class Core_Session {

    private static $_instance = null;

    public function __construct() {
        ini_set('session.save_path', ROOT . DS . 'tmp' . DS . 'session' . DS);
        ini_set('session.auto_start', 0);
        ini_set('session.use_cookies', 'on');
        ini_set('session.use_only_cookies', true);
        ini_set('session.hash_function', 'sha512');
        ini_set('session.hash_bits_per_character', 5);
        ini_set('session.gc_maxlifetime', 60);
        $this->cleanSessions();
        @session_start();
        if ($this->validSession()) {
            $_SESSION['expire'] = time() + ini_get('session.gc_maxlifetime');
        } else {
            $_SESSION = array();
            session_destroy();
            session_regenerate_id(true);
            $newSession = session_id();
            session_write_close();
            session_id($newSession);
            @session_start();
            $_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['expire'] = time() + ini_get('session.gc_maxlifetime');
        }
    }

    public static function conditionStartSession() {
        $doNotStartSessionTags = array('soap', 'jsonrpc', 'xmlrpc', 'wsdl');
        $url = strtolower($_SERVER['REQUEST_URI']);
        $tagNotExist = true;
        foreach ($doNotStartSessionTags as $tag) {
            if (strpos($url, $tag)) {
                $tagNotExist = false;
                break;
            }
        }
        $userAgentSet = isset($_SERVER['HTTP_USER_AGENT']);
        return $userAgentSet && $tagNotExist;
    }

    public static function getInstance() {
        if (self::conditionStartSession() && !isset(self::$_instance)) {
            self::$_instance = new Core_Session();
        }
        return self::$_instance;
    }

    /**
     * Manual Clean of session at every hour within the first 5 seconds
     */
    private function cleanSessions() {
        $date = new DateTime('Now');
        if ($date->format("i") == 0 && $date->format("s") < 5) {
            $maxlifetime = ini_get('session.gc_maxlifetime');
            $savePath = ROOT . DS . 'tmp' . DS . 'sessions' . DS;
            foreach (glob("$savePath/sess_*") as $file) {
                if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Make sure matching IP, UserAgent and expire Date
     * @return boolean
     */
    private function validSession() {
        if (!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
            return false;

        if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
            return false;

        if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
            return false;
        if (isset($_SESSION['expire']) && $_SESSION['expire'] < time())
            return false;
        return true;
    }

}

?>
