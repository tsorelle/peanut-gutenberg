<?php
/**
 * Bootstrap file for running peanut scripts with WordPress
 */

define('WP_USE_THEMES', false);
define('DIR_ROOT', str_replace('\\', '/', realpath(__DIR__ . '/../web.root')));

$_SERVER['DOCUMENT_ROOT'] = DIR_ROOT;
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';

require_once DIR_ROOT . '/wp-load.php';

// definitions.php uses DIR_ROOT if defined
require_once DIR_ROOT . '/tq-peanut/bootstrap/definitions.php';

$peanutBootstrap = DIR_ROOT . '/tq-peanut/config/peanut-bootstrap.php';
if (file_exists($peanutBootstrap)) {
    require_once $peanutBootstrap;
    $response = \Peanut\Bootstrap::initialize(DIR_PEANUT_ROOT);
    $loader = $response->loader;
    $loader->addPsr4('PeanutTest\\', DIR_PEANUT_ROOT . '/src/test');
    $absPeanutRoot = realpath(__DIR__ . '/../web.root/tq-peanut');
    if (file_exists($absPeanutRoot . "/src/test/scripts/BuildmenuTest.php")) {
        include_once $absPeanutRoot . "/src/test/scripts/TestScript.php";
        include_once $absPeanutRoot . "/src/test/scripts/BuildmenuTest.php";
    }
}

if ($argc < 2) {
    exit("Usage: php pnutstart.inc [ClassName]\n");
}

$className = $argv[1];
if (class_exists($className)) {
    $script = new $className();
    if (method_exists($script, 'run')) {
        $script->run();
    } else {
        exit("Class $className does not have a run() method.\n");
    }
} else {
    exit("Class $className not found.\n");
}
