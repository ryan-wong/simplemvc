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

    public function __construct() {
        $this->_templateManagement = new Core_TemplateManagement();
    }

    public function getTemplateManagement() {
        return $this->_templateManagement;
    }

}

?>
