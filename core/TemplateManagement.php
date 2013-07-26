<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @category Main
 * @package Core
 * @copyright (c) 2013, Ryan Wong
 */
class Core_TemplateManagement {

    protected $_templateEngine = null;
    protected $_type;
    protected $_templateFile;
    protected $_cache;

    public function __construct() {
        
    }

    public function templateSelection($type, $module, $controller, $action, $cache) {
        switch ($type) {
            case 'mustache':
                $this->_type = $type;
                $this->_templateFile = $action;
                $this->_templateEngine = $this->mustacheTemplateSettings($module, $controller);
                break;
            case 'raintpl':
                $this->_type = $type;
                $this->_templateFile = $action;
                $this->_cache = $cache;
                $this->_templateEngine = $this->rainTplTemplateSettings($module, $controller);
                break;
            case 'php':
                $this->_type = "php";
                $this->_templateFile = ROOT . DS . $module . DS . "view" . DS . $controller . DS . "{$action}.php";
                break;
            default:
                break;
        }
    }

    public function render($data) {
        switch ($this->_type) {
            case 'mustache':
                echo $this->_templateEngine->render($this->_templateFile, $data);
                break;
            case 'raintpl':
                foreach ($data as $key => $value) {
                    $this->_templateEngine->assign($key, $value);
                }
                if ($this->_cache) {
                    $cache = $this->_templateEngine->cache($this->_templateFile, $this->_cache);
                    echo $cache;
                }
                $this->_templateEngine->draw($this->_templateFile);
                break;
            case 'php':
                extract($data);
                include $this->_templateFile;
                break;
            default:
                break;
        }
    }

    private function mustacheTemplateSettings($module, $controller) {
        $mustacheOptions = array(
            'cache' => ROOT . DS . "tmp" . DS . "cache",
            'loader' => new Mustache_Loader_FilesystemLoader(ROOT . DS . $module . DS . "view" . DS . $controller),
            'partials_loader' => new Mustache_Loader_FilesystemLoader(ROOT . DS . $module . DS . "template"),
            'escape' => function($value) {
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            },
            'charset' => 'ISO-8859-1',
            'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
            'strict_callables' => true,
        );

        $m = new Mustache_Engine($mustacheOptions);
        return $m;
    }

    private function rainTplTemplateSettings($module, $controller) {
        $raintpl = new Raintpl_Adapter($module, $controller);
        return $raintpl;
    }

}

?>
