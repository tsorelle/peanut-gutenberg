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
    $peanutSystemLocation = 'docroot';// $peanutSystemLocation = 'module';

    $peanutRoot = $peanutSystemLocation == 'docroot' ?
        $_SERVER['DOCUMENT_ROOT'] :
        __DIR__;

    require_once  "$peanutRoot/tq-peanut/bootstrap/definitions.php";
    require_once DIR_APPLICATION . '/config/peanut-bootstrap.php';

    \Peanut\Bootstrap::initialize();
    if (!class_exists('Tops\sys\TTracer')) {
        exit ("Tracer not loaded");
    }
    $tracerOn = false;
   // $tracerOn = true;
    if ($tracerOn) {
        \Tops\sys\TTracer::Start();
    }

    // don't start session here, WP doesn't like it.
    /**
    $status = session_status();
    if ($status == PHP_SESSION_NONE) {
         session_start();
    }
     */

    \Tops\sys\TSession::Initialize();

    \Tops\sys\TTracer::Print(
        class_exists('PeanutTest\services\HelloWorldCommand') ?
        'Found test service' : 'NO TEST SERVICE');

    if (!class_exists('Tops\cms\TRouteFinder')) {
        throw new \Exception('Initialization failed');
    };

    if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ) {
        $uri = preg_replace("/(^\/)|(\/$)/", "", $_SERVER['REQUEST_URI']);
        // $matched = TRouteFinder::matchWithRedirect($uri);
        $matched = TRouteFinder::match($uri);
        \Tops\sys\TTracer::Print( "Matched route: $uri");
        if ($matched) { // \Nutshell\cms\RouteFinder::match($uri)) {
            TRouter::Execute();
            exit;
        }
    }

    $test = class_exists('Tops\cms\wordpress\WordpressUser');
    register_block_type(__DIR__ . '/blocks/peanut-block');


}


// todo: move to scriptloading render
add_action( 'wp_enqueue_scripts', 'peanut_scripts' );
function peanut_scripts() {
    $peanutVersion = ViewModelManager::GetPeanutVersion();
    $optimized = \Tops\sys\TConfiguration::getBoolean('optimize','peanut',true);
    $loaderScript = $optimized ? 'peanut-loader.min.js' : 'PeanutLoader.js';

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

add_action('wp_footer', 'render_peanut_start_script', 999);

function render_peanut_start_script() {
    /*
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
        ['peanut-head-load-js'], $peanutVersion, true);*/

    \Tops\sys\TTracer::Print("Rendering peanut start script");
    if (\Peanut\sys\ViewModelManager::hasVm()) {
        // todo: render script tags here for peanut loader/head.js?
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

add_filter( 'wp_get_nav_menu_items', function( $items ) {
    if ( is_user_logged_in() ) return $items;

    foreach ( $items as $key => $item ) {
        if ( $item->object === 'page' ) {
            $page = get_post( $item->object_id );
            if ( $page && $page->post_status === 'private' ) {
                unset( $items[$key] );
            }
        }
    }
    return $items;
});

