<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//global $woocommerce;
/*echo "<pre>";
print_r($_SERVER);
echo "</pre>";*/

add_action('wp_head', 'your_function');
function your_function(){

    if( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] == '/new-phonicsschool/checkout/' ) {

        //echo "Thanks";
        echo "<style>";
        echo ".woocommerce ul.woocommerce-error {display: block !important;}";
        echo "</style>";
    }   
}

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
    return '/new-phonicsschool/store/';
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

add_filter( 'woocommerce_billing_fields', 'wc_npr_filter_phone', 10, 1 );
function wc_npr_filter_phone( $address_fields ) {
$address_fields['billing_phone']['required'] = false;
$address_fields['billing_phone']['placeholder'] = 'Enter Phone (optional)';

return $address_fields;
}

function custom_add_to_cart_message( $message ) {

    function cart_json(){
        global $woocommerce;
        global $current_currency_symbol;
        global $current_currency;
        global $json;

        $current_currency = get_woocommerce_currency();
        $current_currency_symbol = get_woocommerce_currency_symbol( $current_currency );
        //echo wp_specialchars( $current_currency_symbol );

/*if( $_SERVER['REMOTE_ADDR'] == '115.112.129.194' ) {
    echo "<pre>";
        print_r($woocommerce);
    echo "</pre>";
}*/

        

        $cart_items = $woocommerce->cart->cart_contents;
        $latest_added_item = end($cart_items);
        $p_id = $latest_added_item['data']->post->ID;
        $product = wc_get_product( $p_id );
        $product_image = $product->get_image();
        $product_link = get_permalink($p_id);
        $details = array();
        $json = 0;

        if( isset($latest_added_item['quantity']) && !empty($latest_added_item['quantity']) && $latest_added_item['quantity'] != 0 ){

            $details['product_id'] = $p_id; 
            $details['product_link'] = $product_link; 
            $details['product_image'] = $product_image; 
            $details['quantity'] = $latest_added_item['quantity']; 
            $details['price'] = $latest_added_item['data']->price; 
            $details['product_title'] = $latest_added_item['data']->post->post_title; 
            $details['total_quantity'] = $woocommerce->cart->cart_contents_count; 
            $details['subtotal'] = $woocommerce->cart->subtotal;   
            $details['cart_url'] = $woocommerce->cart->get_checkout_url();  
            $details['currency'] = $current_currency; 
            $details['currency_symbol'] = $current_currency_symbol; 
            $json = json_encode($details);     
        }else{
            $json = 0;       
        }        
            echo "<script>";
            echo "var cart_json = " . $json.";";
            echo "</script>";    
    }
    add_action('wp_head', 'cart_json');
    return $message;
}
add_filter( 'wc_add_to_cart_message', 'custom_add_to_cart_message' );

function checkOutURL() {

    global $woocommerce;
    $checkOutURL = $woocommerce->cart->get_checkout_url();  ;
    echo "<script>";
    echo "var checkOutURL = '".$checkOutURL."';";
    echo "</script>";

}
add_action('wp_head', 'checkOutURL');