<?php
$mustacheOptions = array(
    'cache' => ROOT . DS . "tmp" . DS . "cache",
    'loader' => new Mustache_Loader_FilesystemLoader(ROOT . DS . "module" . DS . "view" . DS . "index"),
    'partials_loader' => new Mustache_Loader_FilesystemLoader(ROOT . DS . "module" . DS . "template"),
    'helper' => $view,
    'escape' => function($value) {
        return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
    },
    'charset' => 'ISO-8859-1',
    'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
    'strict_callables' => true,
);
$m = new Mustache_Engine($mustacheOptions);
//echo $m->render('Hello {{planet}}', array('planet' => 'World!'));
$tpl = $m->loadTemplate('foo'); // loads __DIR__.'/views/foo.mustache';
echo $tpl->render(array('planet' => 'baz',
    'person' => 'asis',
    'company' => '<b>GitHub</b>',
    'nothin' => false,
    'repo' => array(// repeat if repos is list
        array('name' => "resque"),
        array('name' => "hub"),
        array('name' => "rip"),
    ),
    'name' => "Willy",
    'wrapped' => function($text) {// anonymous function
        return "<b>" . $text . "</b>";
    },
    'embiggened' => function($text, Mustache_LambdaHelper $helper) {
        return strtoupper($helper->render($text));
    },
    'person?' => array(
        'name' => 'Jon',
    ),
    array('colors' => array('red', 'blue', 'green')),
    'foo' => array(
        'bar' => array(
            'baz' => 'qux',
        ),
    ),
));
?>
This file shows you all the functionality of mustashe
To use mustache go to https://github.com/bobthecow/mustache.php
Download src folder and put it under gear 
so  for example:
simplemvc/gear/Mustache/Loader