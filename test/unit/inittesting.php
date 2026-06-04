<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/14/2017'
 * Time: 6:01 AM
 */

define('BASE_DIR',str_replace('\\','/', realpath(__DIR__.'/../../web.root')));
$definitionsFile = BASE_DIR.'/tq-peanut/bootstrap/definitions.php';
include_once $definitionsFile;
include_once DIR_CONFIGURATION.'/peanut-bootstrap.php';
\Peanut\Bootstrap::initialize();