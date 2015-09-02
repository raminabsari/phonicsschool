<?php
/**
 * Plugin Name: YITH WooCommerce Added to Cart Popup
 * Plugin URI: http://yithemes.com/
 * Description: YITH WooCommerce Added to Cart Popup plugin allow to displays popup cart for add to cart action
 * Version: 1.0.2
 * Author: Yithemes
 * Author URI: http://yithemes.com/
 * Text Domain: yith-wacp
 * Domain Path: /languages/
 *
 * @author Yithemes
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.2
 */
/*  Copyright 2015  Your Inspiration Themes  (email : plugins@yithemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_wacp_free_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'YITH WooCommerce Added to Cart Popup is enabled but not effective. It requires WooCommerce in order to work.', 'yith-wacp' ); ?></p>
	</div>
<?php
}


function yith_wacp_install_free_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Added to Cart Popup while you are using the premium one.', 'yith-wacp' ); ?></p>
	</div>
	<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


if ( ! defined( 'YITH_WACP_VERSION' ) ){
	define( 'YITH_WACP_VERSION', '1.0.2' );
}

if ( ! defined( 'YITH_WACP_FREE_INIT' ) ) {
	define( 'YITH_WACP_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WACP_INIT' ) ) {
	define( 'YITH_WACP_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WACP' ) ) {
	define( 'YITH_WACP', true );
}

if ( ! defined( 'YITH_WACP_FILE' ) ) {
	define( 'YITH_WACP_FILE', __FILE__ );
}

if ( ! defined( 'YITH_WACP_URL' ) ) {
	define( 'YITH_WACP_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_WACP_DIR' ) ) {
	define( 'YITH_WACP_DIR', plugin_dir_path( __FILE__ )  );
}

if ( ! defined( 'YITH_WACP_TEMPLATE_PATH' ) ) {
	define( 'YITH_WACP_TEMPLATE_PATH', YITH_WACP_DIR . 'templates' );
}

if ( ! defined( 'YITH_WACP_ASSETS_URL' ) ) {
	define( 'YITH_WACP_ASSETS_URL', YITH_WACP_URL . 'assets' );
}


function yith_wacp_free_init() {

	load_plugin_textdomain( 'yith-wacp', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

	// Load required classes and functions
	require_once('includes/class.yith-wacp.php');
	require_once('includes/class.yith-wacp-admin.php');
	require_once('includes/class.yith-wacp-frontend.php');

	// Let's start the game!
	YITH_WACP();
}
add_action( 'yith_wacp_free_init', 'yith_wacp_free_init' );


function yith_wacp_free_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'yith_wacp_free_install_woocommerce_admin_notice' );
	}
	elseif ( defined( 'YITH_WACP_PREMIUM' ) ) {
		add_action( 'admin_notices', 'yith_wacp_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	else {
		do_action( 'yith_wacp_free_init' );
	}
}
add_action( 'plugins_loaded', 'yith_wacp_free_install', 11 );