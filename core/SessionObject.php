<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @package Core
 * @category Main
 * @copyright (c) 2013, Ryan Wong
 */
class Core_SessionObject {

    private $_namespace;

    /**
     * 
     * @param string $namespace
     * @throws Exception
     */
    public function __construct($namespace = 'default') {
        if (!$namespace) {
            throw new Exception('Expected Session Name');
        }
        $this->_namespace = $namespace;
    }

    /**
     * Return a value stored in session
     * @param string $name
     * @return null|mixed
     * @throws Exception
     */
    public function & __get($name) {
        if ($name === '') {
            throw new Exception("The '$name' key must be a non-empty string");
        }

        if (isset($_SESSION[$this->_namespace]) &&
                isset($_SESSION[$this->_namespace][$name])) {
            return $_SESSION[$this->_namespace][$name];
        } else {
            return null;
        }
    }

    /**
     * Set a value to session
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set($name, $value) {

        if ($name === '') {
            throw new Exception("The '$name' key must be a non-empty string");
        }
        $name = (string) $name;
        if (isset($_SESSION['lock']) && in_array($name, $_SESSION['lock'])) {
            throw new Exception("$name has been locked");
        } else {
            $_SESSION[$this->_namespace][$name] = $value;
        }
    }

    /**
     * 
     * @param string $name
     */
    public function __unset($name) {
        if (isset($_SESSION[$this->_namespace]) &&
                isset($_SESSION[$this->_namespace][$name])) {
            unset($_SESSION[$this->_namespace][$name]);
        }
    }

    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function __isset($name) {
        if (isset($_SESSION[$this->_namespace]) &&
                array_key_exists($name, $_SESSION[$this->_namespace][$name])) {
            return true;
        }
        return false;
    }

    /**
     * Get all value stored in Session NameSpace
     * @return array
     */
    public function getAll() {
        if (isset($_SESSION[$this->_namespace])) {
            return $_SESSION[$this->_namespace];
        }
        return array();
    }

    /**
     * Get whole Session array.
     * @return array
     */
    public function getSession() {
        return $_SESSION;
    }

    /**
     * Lock a Session value so no one can set it
     * @param string $name
     */
    public function lock($name) {
        $_SESSION['lock'][] = $name;
    }

}

?>
