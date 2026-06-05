<?php

/**
 * @package Bootscore Child
 *
 * @version 6.0.0
 */


// Exit if accessed directly
defined('ABSPATH') || exit;


/**
 * Enqueue scripts and styles
 */
add_action('wp_enqueue_scripts', 'bootscore_child_enqueue_styles');
function bootscore_child_enqueue_styles() {

  // Compiled main.css
  $modified_bootscoreChildCss = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/css/main.css'));
  wp_enqueue_style('main', get_stylesheet_directory_uri() . '/assets/css/main.css', array('parent-style'), $modified_bootscoreChildCss);

  // style.css
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  
  // custom.js
  // Get modification time. Enqueue file with modification date to prevent browser from loading cached scripts when file content changes. 
  $modificated_CustomJS = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/custom.js'));
  wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), $modificated_CustomJS, false, true);
}

function bootscore_footer_login_link() {
    if ( !is_user_logged_in() ) {
        return '<a href="' . wp_login_url() . '">Sign in</a>';
    }
    return '';
}
add_shortcode('bootscore_signin', 'bootscore_footer_login_link');


/**
 * Navbar classes
 * Adds navbar-light bg-light to the navbar
 */
add_filter('bootscore/class/header/navbar', 'bootscore_child_navbar_classes');
function bootscore_child_navbar_classes($class) {
  return trim($class . ' navbar-light bg-light');
}

function change_logo_path($logo, $color) {
    $d = get_stylesheet_directory_uri();
    if ($color === 'theme-dark') {
        return get_stylesheet_directory_uri() . '/assets/img/logo/peanut-logo-small.jpg';
    }
    return get_stylesheet_directory_uri() .  '/assets/img/logo/peanut-logo-small.jpg';
}
add_filter('bootscore/logo', 'change_logo_path', 10, 2);
