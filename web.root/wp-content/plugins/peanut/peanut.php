<?php
/**
 * @package peanut
 */
/*
Plugin Name: peanut
Plugin URI: https://github.com/tsorelle/peanut-gutenberg
Description: Peanut framework supports KnockoutViewModels and TOPS Services
Version: 0.2
Author: Terry SoRelle
Author URI: https://github.com/tsorelle
License: GPLv2 or later
Text Domain: peanut
*/

use Tops\cms\TRouteFinder;
use Tops\cms\TRouter;
use Peanut\sys\ViewModelManager;

add_action( 'init', 'peanut_initialize' );
function peanut_initialize() {
    if (!empty($_SERVER['REQUEST_URI'])) {
        $reqExtension = strtolower( pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION));
        $p = strpos($reqExtension, '?');
        if ($p !== false) {
            $reqExtension = substr($reqExtension, 0, $p);
        }
        if (!(empty($reqExtension) || $reqExtension == 'js' || $reqExtension == 'php')) {
            // skip peanut initializations for images etc.
            return;
        }
    }
    $peanutSystemLocation = 'docroot';
    // $peanutSystemLocation = 'module';

    $peanutRoot = $peanutSystemLocation == 'docroot' ?
        $_SERVER['DOCUMENT_ROOT'] :
        __DIR__;

    require_once  "$peanutRoot/tq-peanut/bootstrap/definitions.php";
    require_once DIR_APPLICATION . '/config/peanut-bootstrap.php';
    $bootResponse  = \Peanut\Bootstrap::initialize();

    session_start();
    \Tops\sys\TSession::Initialize();
    if (!class_exists('Tops\cms\TRouteFinder')) {
        throw new \Exception('Initialization failed');
    };

    if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ) {
        $uri = preg_replace("/(^\/)|(\/$)/", "", $_SERVER['REQUEST_URI']);
        // $matched = TRouteFinder::matchWithRedirect($uri);
        $matched = TRouteFinder::match($uri);
        if ($matched) { // \Nutshell\cms\RouteFinder::match($uri)) {
            TRouter::Execute();
            exit;
        }
    }

    $test = class_exists('Tops\cms\wordpress\WordpressUser');
    register_block_type(__DIR__ . '/blocks/peanut-block');


}

add_action( 'wp_enqueue_scripts', 'peanut_scripts' );
function peanut_scripts() {
    if (\Peanut\sys\ViewModelManager::hasVm()) {
/*
        $currentTheme = wp_get_theme();
        $themeSection =  strtolower($currentTheme->name);
        $themeIni = \Tops\sys\TIniSettings::Create('themes.ini');
        $bootstrapLib = $themeIni->getValue('bootstrap.library',$themeSection);
        $dependencies = array ('peanut-head-load-js');
        if ($bootstrapLib !== false) {
            $dependencies[] = $bootstrapLib;
        }
        $dependencies[] = 'jquery';
*/
        $optimized = \Tops\sys\TConfiguration::getBoolean('optimize','peanut',true);
        $loaderScript = $optimized ? 'peanut-loader.min.js' : 'PeanutLoader.js';

        $peanutVersion = ViewModelManager::GetPeanutVersion();
        wp_enqueue_script(
            'peanut-head-load-js',
            'https://cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.js',
            array(),
            '1.0.3',
            true
        );

        wp_enqueue_script('peanut-loader-js', "/tq-peanut/pnut/core/$loaderScript",
            ['peanut-head-load-js'], $peanutVersion, true);
    }
}

add_action('wp_footer', 'render_peanut_start_script', 999);

function render_peanut_start_script() {
    if (\Peanut\sys\ViewModelManager::hasVm()) {
        \Peanut\sys\ViewModelManager::RenderStartScript();
    }
}

// todo: see if this is still needed, if so add from legacy project
// add_filter('the_content','peanut_content');
// function peanut_content($input)

function peanut_install() {
    // todo: not needed?
/*    $installationIni = @parse_ini_file(__DIR__.'/installation/installation.ini');
    if ($installationIni !== false && !empty($installationIni['enabled'])) {
        require_once (__DIR__.'/installation/bootstrap/PeanutPluginInstaller.php');
        \Tops\wordpress\PeanutPluginInstaller::install();
    }*/
}
register_activation_hook( __FILE__, 'peanut_install' );

function peanut_deactivation() {
    // todo: not needed?
/*    $installationIni = @parse_ini_file(__DIR__.'/installation/installation.ini');
    if ($installationIni !== false && (!empty($installationIni['enabled'])) && class_exists('\Peanut\sys\DefaultPeanutInstaller')) {
        $installer = new \Peanut\sys\DefaultPeanutInstaller();
        $installer->uninstallAll();
    }*/
}
// register_deactivation_hook(__FILE__, 'peanut_deactivation' );

