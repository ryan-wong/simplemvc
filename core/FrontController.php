<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @category Main
 * @package Core
 * @copyright (c) 2013, Ryan Wong
 */
class Core_FrontController {

    private static $_instance;

    /**
     * 
     * @return Core_FrontController
     */
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            $className = __CLASS__;
            self::$_instance = new $className();
        }
        return self::$_instance;
    }

    public function handle($url) {
        $urlArray = parse_url($url);
        $urlParts = array_values(array_filter(explode('/', $urlArray['path'])));
        $pathNum = count($urlParts);
        switch ($pathNum) {
            case 0:
            case 1://module                
                $this->homePage();
                break;
            case 2: //controller                
                $module = strtolower($urlParts[0]);
                $controller = strtolower($urlParts[1]);
                $action = 'index';
                $query = (!empty($urlArray['query'])) ? $urlArray['query'] : '';
                $this->delegateToController($module, $controller, $action, $query);
                break;
            case 3:// action
                $module = strtolower($urlParts[0]);
                $controller = strtolower($urlParts[1]);
                $action = strtolower($urlParts[2]);
                $query = (!empty($urlArray['query'])) ? $urlArray['query'] : '';
                $this->delegateToController($module, $controller, $action, $query);
                break;
            default:
                break;
        }
    }

    /**
     * Include homepage
     */
    public function homePage() {
        include_once ROOT . DS . "public" . DS . "defaultpage" . DS . "home.php";
        exit;
    }

    /**
     * redirect to 404 error
     */
    public function invalidUrl() {
        include_once ROOT . DS . "public" . DS . "defaultpage" . DS . "404error.php";
        exit;
    }

    private function parseQuery($query) {
        if (!$query) {
            return '';
        }
        $parts = explode('&', $query);
        $param = array();
        foreach ($parts as $keyValuePair) {
            $pair = explode('=', $keyValuePair);
            $param[$pair[0]] = $pair[1];
        }
        return $param;
    }

    public function delegateToController($module, $controller, $action, $query) {
        try {
            $controllerName = ucfirst($module) . '_' . ucfirst($controller);
            $dispatch = new $controllerName($module, $controller, $action, $this->parseQuery($query));
        } catch (Exception $ex) {
            error_log($ex->getMessage() . "\n");
            error_log($ex->getTraceAsString() . "\n");
            $this->invalidUrl();
            exit;
        }
        $dispatch->render();
    }

}

?>
