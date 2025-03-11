<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'hello-elementor','hello-elementor','hello-elementor-theme-style','hello-elementor-header-footer' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION




// CUSTOM FUNCTIONS
define( 'CHILD_THEME_URI', get_template_directory_uri() . '-child/' );

// import custom CSS files from child theme
define( 'CHILD_THEME_CSS', CHILD_THEME_URI . 'css/' );
wp_enqueue_style( 'edu25', CHILD_THEME_CSS . 'edu25.css', false, '1.1', 'all');

// import custom JS files from child theme
define( 'CHILD_THEME_JS', CHILD_THEME_URI . 'js/' );
wp_enqueue_script( 'edu25', CHILD_THEME_JS . 'edu25.js', false, '1.1', 'all');


/**
 * Add Swiper JS and CSS files
 */
function swiper_theme_enqueue_public() {
    wp_enqueue_script('swiperjs', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js');
    wp_enqueue_style('swiperjs', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
}

add_action('wp_enqueue_scripts', 'swiper_theme_enqueue_public');



function get_page_id() {
    global $post;
    $post_id = $post->ID;
    return $post_id;
}

include_once 'php/pages-with-category-tag.php';
include_once 'php/portfolio-page-tags.php';
include_once 'php/portfolio-page-categories.php';
include_once 'php/portfolio-filtered-list.php';
include_once 'php/portfolio-related-list.php';