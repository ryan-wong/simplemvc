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
    protected $_data;

    public function __construct() {
        $this->_templateManagement = new Core_TemplateManagement();
        $this->_data = array();
    }

    public function getTemplateManagement() {
        return $this->_templateManagement;
    }

    public function assignDataForTemplate($data) {
        $this->_data = $data;
    }

    public function renderLayout() {
        $this->_templateManagement->render($this->_data);
    }

}

?>
