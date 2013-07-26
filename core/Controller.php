<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @category Main
 * @package Core
 * @copyright (c) 2013, Ryan Wong
 */
class Core_Controller {

    protected $_action;
    protected $_cache;
    protected $_controller;
    protected $_data;
    protected $_dontRender;
    protected $_extension;
    protected $_layoutEnable;
    protected $_layout;
    protected $_module;
    protected $_query;
    protected $_templateFile;
    protected $_templateType;
    protected $_view;

    public function __construct($module, $controller, $action, $query) {
        $config = getConfig();
        $query = ($query) ? $query : array();
        $this->_cache = false;        
        $this->_query = $query;
        $this->_module = $module;
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_extension = ".php";
        $this->_templateFile = '';
        $this->_dontRender = false;
        $this->_templateType = 'php';
        $this->_view = new Core_View();
        $this->_data = array('view' => $this->_view);
        $this->_layoutEnable = $config[ENV]['layout_enable'] == '1' ? true : false;
        $this->_layout = "{$module}.php";
        if (!method_exists($this, $action . "Page")) {
            throw new Exception('Page Does not Exist.');
        }
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data;
        }
        return '';
    }

    public function getParam($field, $default = '') {
        if (array_key_exists($field, $this->_query)) {
            return $this->_query[$field];
        }
        return $default;
    }

    /**
     * Load page by url but don't change url
     * @param string $url
     */
    public function forward($url) {
        $front = Core_Frontcontroller::getInstance();
        $front->handle($url);
        exit;
    }

    public function init() {
        
    }

    /**
     * redirect to url
     * @param string $url
     */
    public function redirect($url) {
        $isUrl = strpos($url, 'http://') === 0;
        $slash = strpos($url, '/') === 0;
        if ($isUrl) {
            header("Location: $url");
            exit;
        } else {
            if ($slash) {
                $host = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
                $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off") ? 'https' : 'http';
                $port = (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80);
                $uri = $proto . '://' . $host;
                if ((('http' == $proto) && (80 != $port)) || (('https' == $proto) && (443 != $port))) {
                    if (strrchr($host, ':') === false) {
                        $uri .= ':' . $port;
                    }
                }
                $url = $uri . '/' . ltrim($url, '/');
                header("Location: $url");
                exit;
            } else {
                $this->invalidUrl();
            }
        }
    }

    public function render() {
        $this->init();
        $action = $this->_action . "Page";
        $this->$action();
        $view = $this->_view;
        if (!$this->_dontRender) {
            if ($this->_layoutEnable) {
                $view->getTemplateManagement()->templateSelection($this->_templateType, $this->_module, $this->_controller, $this->_action, $this->_cache);
                $view->assignDataForTemplate($this->_data);
                $layoutFile = ROOT . DS . $this->_module . DS . "layout" . DS . $this->_layout;
                include $layoutFile;
            } else {
                $this->_view->getTemplateManagement()->templateSelection($this->_templateType, $this->_module, $this->_controller, $this->_action, $this->_cache);
                $this->_view->getTemplateManagement()->render($this->_data);
            }
        }
    }

    public function setCache($seconds) {
        $this->_cache = $seconds;
    }

    public function setDontRender() {
        $this->_dontRender = true;
    }

    public function setExtension($ext) {
        $this->_extension = $ext;
    }

    public function setTemplateType($type) {
        $this->_templateType = $type;
    }

}

?>
