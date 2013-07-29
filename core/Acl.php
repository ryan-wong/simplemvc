<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @category Main
 * @package Core
 * @copyright (c) 2013, Ryan Wong
 */
class Core_Acl {

    /**
     * List of restricted IPs
     * @var array 
     */
    protected $_allowIp = array();

    /**
     * IF user has User privilege, IP privilege, time frame privilege, and other 
     * privilege return true.
     * @return boolean
     */
    public function allow() {
        return $this->allowIp() &&
                $this->allowUser() &&
                $this->allowOther() &&
                $this->allowTimeFrame();
    }

    /**
     * IP restriction
     * @return boolean
     */
    public function allowIp() {
        if ($this->_allowIp) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if (!in_array($ip, $this->_allowIp)) {
                return false;
            }
        }
        return true;
    }

    /**
     * This is Where you overload this function and need user in session
     * @return boolean
     */
    public function allowUser() {
        return true;
    }

    /**
     * This is Where you overload this function and need other restrictions
     * @return boolean
     */
    public function allowOther() {
        return true;
    }

    /**
     * This restrict time of day . Has to be overloaded
     * @return boolean
     */
    public function allowTimeFrame() {
        return true;
    }

}

?>
