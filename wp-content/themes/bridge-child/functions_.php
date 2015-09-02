<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// enqueue the child theme stylesheet

Function wp_schools_enqueue_scripts() {
    wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
    wp_enqueue_style( 'childstyle' );

    global $post;

    // enqueue the scripts
    wp_register_script( 'tweenmax', get_stylesheet_directory_uri() . '/js/scrollmagic-lib/greensock/TweenMax.min.js', array(), null, true);
    wp_enqueue_script( 'tweenmax');
    wp_register_script( 'scrollmagic', get_stylesheet_directory_uri() . '/js/scrollmagic-lib/scrollmagic/minified/ScrollMagic.min.js', array('tweenmax', 'jquery'), null, true);
    wp_enqueue_script( 'scrollmagic');
    wp_register_script( 'animations-gsap', get_stylesheet_directory_uri() . '/js/scrollmagic-lib/scrollmagic/minified/plugins/animation.gsap.min.js', array(), null, true);
    wp_enqueue_script( 'animations-gsap');
    wp_register_script( 'intro', get_stylesheet_directory_uri() . '/js/intro.js', array('tweenmax', 'scrollmagic', 'animations-gsap', 'jquery'), null, true);
    wp_enqueue_script( 'intro');
    wp_localize_script('intro', 'WPURLS', array(
            'template_url' => get_bloginfo('template_url'),
            'child_stylesheet_directory_uri' => get_stylesheet_directory_uri(),
            'postID' => $post->ID
        ));

    wp_register_script( 'prefixfree', get_stylesheet_directory_uri() . '/js/prefixfree.min.js', array(), null, false);
    wp_enqueue_script( 'prefixfree');

}
add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);


// Couldn't get shortcodes to work in shortcodes.php for reasons unknown,
// so included a brand new file of shortcodes...
include_once('includes/shortcodes/ps-shortcodes.php');


// Allow shortcodes in widgets
add_filter('widget_text', 'do_shortcode');


// Change target of 'Back to Shop' button
// Tutorial: http://www.skyverge.com/blog/change-woocommerce-return-to-shop-button/
function change_empty_cart_button_url() {
    return '/store/';
}
add_filter( 'woocommerce_return_to_shop_redirect', 'change_empty_cart_button_url' );


// Add woocommerce class to custom shop page
function add_shop_body_class($classes) {
    if (is_page('Shop')) {
        $classes[] = 'woocommerce';
    }
    return $classes;
}
add_filter('body_class', 'add_shop_body_class');