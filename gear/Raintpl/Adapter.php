<?php

include 'Raintpl.php';
/**
 * Partials dont work for raintpl framework.
 */
class Raintpl_Adapter extends RainTPL {

    public function __construct($module, $controller) {
        $tplDir = ROOT . DS . $module . DS . "view" . DS . $controller . DS;
        $this->module = $module;
        $this->controller = $controller;
        self::configure('cache_dir', ROOT . DS . 'tmp' . DS . 'cache' . DS);
        self::configure('tpl_dir', $tplDir);
        self::configure('base_url', null);
        self::configure('tpl_ext', 'tpl');
        self::configure('path_replace', false);
        self::configure('path_replace_list', array());
        self::configure('php_enabled', true);
    }

}

?>
