<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/31/2017
 * Time: 8:02 AM
 */
$projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/../../web.root')).'/';
require $projectFileRoot.'tq-peanut/bootstrap/definitions.php';
require $projectFileRoot.'tq-peanut/bootstrap/peanut-bootstrap.php';
\Peanut\Bootstrap::initialize();
$inifile = __DIR__."/modelbuilder-cms.ini";
$config = parse_ini_file(__DIR__."/modelbuilder-cms.ini",true);
\Tops\db\TModelBuilder::Build($config,__DIR__);
