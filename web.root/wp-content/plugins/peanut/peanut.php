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

use Peanut\PeanutPermissions\TAuthorizationPaths;
use Peanut\sys\ViewModelManager;
use Tops\cms\TRouteFinder;
use Tops\cms\TRouter;
use Tops\cms\wordpress\WordpressRouter;
use Tops\sys\TUser;

function initializePeanut() {
    if (class_exists('Peanut\Bootstrap',false)) {
        return true; // already initialized
    }
    // error_log("initializePeanut");
/*    if (!empty($_SERVER['REQUEST_URI'])) {
        $reqExtension = strtolower( pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION));
        $p = strpos($reqExtension, '?');
        if ($p !== false) {
            $reqExtension = substr($reqExtension, 0, $p);
        }
        if (!(empty($reqExtension) || $reqExtension == 'js' || $reqExtension == 'php')) {
            // skip peanut initializations for images etc.
            return true;
        }
    }*/
    if (!empty($_SERVER['REQUEST_URI'])) {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
        $reqExtension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!(empty($reqExtension) || $reqExtension === 'js' || $reqExtension === 'php')) {
            // skip peanut initializations for images etc.
            return true;
        }
    }

    $peanutSystemLocation = 'docroot';// $peanutSystemLocation = 'module';

    $peanutRoot = $peanutSystemLocation == 'docroot' ?
        $_SERVER['DOCUMENT_ROOT'] :
        __DIR__;

    // print "<h1>peanutRoot: $peanutRoot\n</h1>";
    require_once  "$peanutRoot/tq-peanut/bootstrap/definitions.php";
    require_once  DIR_PNUT_BOOTSTRAP. '/peanut-bootstrap.php';

    \Peanut\Bootstrap::initialize();
//    $test = class_exists('Peanut\Bootstrap',false);
    return class_exists('Tops\sys\TSession');
}

add_action( 'init', 'peanut_initialize' );
function peanut_initialize() {
    if (!initializePeanut()) {
        error_log('Peanut initialization failed');
        return;
    }

   \Tops\sys\TSession::Initialize();

    if (!class_exists('Tops\cms\TRouteFinder')) {
        throw new \Exception('Initialization failed');
    };

    $requestUri = $_SERVER['REQUEST_URI'] ?? null;
    if ( (!empty($requestUri)) && ($requestUri !== '/')) { // home page always authorized
        $uri = preg_replace("/(^\/)|(\/$)/", "", $requestUri);

        // $matched = TRouteFinder::matchWithRedirect($uri);
        $matched = TRouteFinder::match($uri);

        \Tops\sys\TTracer::Print( "Matched route: $uri");
        if ($matched) { // \Nutshell\cms\RouteFinder::match($uri)) {
            $m = TRouteFinder::$matched;
            TRouter::Execute();
            exit;
        }
        WordpressRouter::CheckPageAuthorization($requestUri);
    }

    $test = class_exists('Tops\cms\wordpress\WordpressUser');
    register_block_type(__DIR__ . '/blocks/peanut-block');
}

/**
 * On sign in, redirect users to home page instead of profile or dashboard.
 */
add_filter('login_redirect', function($redirect_to, $requested_redirect_to, $user) {
    // WordPress assigns an error object to $user if not signed in
    if ($user && !is_wp_error($user)) {
        if (!empty($requested_redirect_to)) {
            // except sysadmin for times when we need to redirect to admin pages
            if (in_array('sysadmin', $user->roles ?? [])) {
                return $requested_redirect_to;
            }
            // redirect home ony if the redirect argument is not an profile or dashboard
            $path = parse_url($requested_redirect_to, PHP_URL_PATH);
            $admin_path = parse_url(admin_url(), PHP_URL_PATH); // typically /wp-admin/
            if (!str_starts_with($path, rtrim($admin_path, '/'))) {
                return $requested_redirect_to;
            }
        }
        return home_url();
    }
    return $redirect_to;
}, 10, 3);

function peanut_enqueue_styles() {
    if ( is_user_logged_in() && class_exists('Peanut\sys\ViewModelManager')) {
        // authenticated
        $peanutVersion = ViewModelManager::GetPeanutVersion();
        wp_enqueue_style(
            'my-plugin-style',                          // Unique handle
            plugin_dir_url(__FILE__) . 'css/peanut-authenticated.css', // URL to the file
            [],                                          // Dependencies (other handles)
            $peanutVersion,                                     // Version number
            'all'                                        // Media type
        );
    }
}

add_action('wp_enqueue_scripts', 'peanut_enqueue_styles');

add_action( 'wp_enqueue_scripts', 'peanut_scripts' );
function peanut_scripts() {
    if (!class_exists('Peanut\sys\ViewModelManager')) {
        return;
    }
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
    if (!class_exists('Peanut\sys\ViewModelManager')) {
        return;
    }
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

add_action('save_post', 'peanut_save_block', 10, 2);
function peanut_save_block($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $status = $post->post_status ?? null;
    if (in_array($status, ['inherit', 'auto-draft'])) return;

    $repository = new \Tops\cms\wordpress\db\repository\PeanutBlocksRepository();
    $peanutBlocks = [];

    if ($status !== 'trash') {
        $blocks = parse_blocks($post->post_content);
        foreach ($blocks as $block) {
            $name = $block['blockName'] ?? null;
            if ($name === 'peanut-block/peanut-block') {
                $attrs = $block['attrs']  ?? null;
                $blockId = $attrs['vmcontext'] ?? null;
                if (!empty($blockId)) {
                    $peanutBlocks[] = $blockId;
                    $viewModel = $attrs['viewmodel'] ?? '';
                    $input =  $attrs['vminput'] ?? '';
                    $repository->updateBlock($post_id,$blockId, $viewModel, $input);
                }
            }
        }
    }

    $repository->removeOrphanBlocks($post_id, $peanutBlocks);

}

add_action( 'publish_post', function( $post_id, $post ) {
    // Fires whether it came from pending review or admin publishing directly
    \Peanut\WordpressTools\WpNotificationManager::AddPost($post_id);
}, 10, 2 );

add_action( 'comment_post', function( $comment_id, $comment_approved, $comment_data ) {
    if ( $comment_approved === 1 ) {
        // Immediately visible — no moderation needed (e.g. trusted/logged-in commenter)
        \Peanut\WordpressTools\WpNotificationManager::AddComment($comment_id);
    }
}, 10, 3 );

add_action( 'comment_unapproved_to_approved', function( $comment ) {
    \Peanut\WordpressTools\WpNotificationManager::AddComment($comment->comment_ID);
} );


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
    if (!class_exists('Tops\cms\TRouteFinder')) {
        // sometimes (e.g. customizer page) this tries to run before peanut is initialized
        return [];
    }
    $authorizations = TAuthorizationPaths::GetInstance();
    if ($authorizations->isAdmin) {
        return $items;
    }
    foreach ( $items as $key => $item ) {
        if ( $item->object === 'page' ) {
            $page = get_post( $item->object_id );
            if ( $page && $page->post_status === 'private' ) {
                unset( $items[$key] );
            }
        }
        if (!$authorizations->isAuthorized($item->url)) {
            unset($items[$key]);
        }
    }
    return $items;
});

add_action('wp_update_nav_menu', function($menu_id) {
    if (initializePeanut()) {
        static $done = false;
        if ($done) {
            return;
        }
        if (class_exists('\Tops\cms\wordpress\WordPressSiteMapBuilder')) {
            $menu = wp_get_nav_menu_object($menu_id);
            $name = \Tops\sys\TConfiguration::getValue('menu-name','wordpress','main-menu');
            if ($menu->name === $name) {
                $done = true;
                $builder= new \Tops\cms\wordpress\WordPressSiteMapBuilder();
                $result = $builder->build();
                $ok = $result->success ?? false;
                if ($ok) {
                    error_log('Sitemap builder succeeded. File: '.$result->outputFile);
                }
                else {
                    $errors = $result->errors ?? [];
                    error_log('Sitemap builder failed: '.implode(', ', $errors));
                }
            }

        }
    }
});

add_action( 'user_register', function( $user_id ) {
    if (class_exists('Tops\sys\TCmsEvents')) {    // $user_id is the newly created user's ID
        $user = get_userdata( $user_id );
        \Tops\sys\TCmsEvents::Handle('user', \Tops\sys\TSystemEvents::ON_USER_UPDATE,$user);
        // error_log( 'New user registered: ' . $user->user_login );
    }
}, 10, 1 );

add_action( 'profile_update', function( $user_id, $old_user_data ) {
    if (class_exists('Tops\sys\TCmsEvents')) {
        $new_user = get_userdata($user_id);
        \Tops\sys\TCmsEvents::Handle('user', \Tops\sys\TSystemEvents::ON_USER_UPDATE,$new_user);
        // Compare $old_user_data (WP_User object, pre-update) to $new_user
        // error_log('User updated: ' . $new_user->user_login);
    }
}, 10, 2 );

add_action( 'deleted_user', function( $user_id, $reassign, $user ) {
    if (class_exists('Tops\sys\TCmsEvents')) {
        \Tops\sys\TCmsEvents::Handle('user', \Tops\sys\TSystemEvents::ON_USER_DELETE,$user_id);
        // error_log( 'User deleted: ID ' . $user_id );
    }
}, 10, 3 );

