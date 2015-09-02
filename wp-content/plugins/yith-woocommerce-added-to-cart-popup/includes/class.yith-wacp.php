<?php
/**
 * Main class
 *
 * @author Yithemes
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */


if ( ! defined( 'YITH_WACP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WACP' ) ) {
	/**
	 * YITH WooCommerce Added to Cart Popup
	 *
	 * @since 1.0.0
	 */
	class YITH_WACP {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WACP
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WACP_VERSION;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WACP
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @return mixed YITH_WACP_Admin | YITH_WACP_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			// Load Plugin Framework
			add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );

			// Class admin
			if ( is_admin() ) {
				YITH_WACP_Admin();
			}

			YITH_WACP_Frontend();
		}

		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT' ) || ! defined( 'YIT_CORE_PLUGIN' ) ) {
				require_once( YITH_WACP_DIR . '/plugin-fw/yit-plugin.php' );
			}

		}
	}
}

/**
 * Unique access to instance of YITH_WACP class
 *
 * @return \YITH_WACP
 * @since 1.0.0
 */
function YITH_WACP(){
	return YITH_WACP::get_instance();
}