<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @category Main
 * @package Core
 * @copyright (c) 2013, Ryan Wong
 */
class Core_View {

    protected $_templateManagement;
    private $_data;
    protected $_helper;

    public function __construct() {
        $this->_templateManagement = new Core_TemplateManagement();
        $this->_helper = new Gear_ViewHelper();
        $this->_data = array();
    }

    public function __call($name, $arguments) {
        if (method_exists($this->_helper, $name)) {
            if (!is_array($arguments)) {
                $arguments = array($arguments);
            }
            return call_user_func_array(array($this->_helper, $name), $arguments);
        }
    }

    public function assignDataForTemplate($data) {
        $this->_data = $data;
    }

    /**
     * 
     * @return Core_TemplateManagement
     */
    public function getTemplateManagement() {
        return $this->_templateManagement;
    }

    /**
     * 
     * @return Gear_ViewHelper
     */
    public function getHelper() {
        return $this->_helper;
    }

    public function renderLayout() {
        $this->_templateManagement->render($this->_data);
    }

}

?>
