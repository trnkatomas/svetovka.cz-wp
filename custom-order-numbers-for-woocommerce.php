<?php
/*
Plugin Name: Custom Order Numbers for WooCommerce
Plugin URI: https://wpcodefactory.com/item/custom-order-numbers-woocommerce/
Description: Custom order numbers for WooCommerce.
Version: 1.1.0
Author: Algoritmika Ltd
Author URI: http://www.algoritmika.com
Text Domain: custom-order-numbers-for-woocommerce
Domain Path: /langs
Copyright: � 2017 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	return;
}

if ( 'custom-order-numbers-for-woocommerce.php' === basename( __FILE__ ) ) {
	// Check if Pro is active, if so then return
	$plugin = 'custom-order-numbers-for-woocommerce-pro/custom-order-numbers-for-woocommerce-pro.php';
	if (
		in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
		( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

if ( ! class_exists( 'Alg_WC_Custom_Order_Numbers' ) ) :

/**
 * Main Alg_WC_Custom_Order_Numbers Class
 *
 * @class   Alg_WC_Custom_Order_Numbers
 * @version 1.0.1
 * @since   1.0.0
 */
final class Alg_WC_Custom_Order_Numbers {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.1.0';

	/**
	 * @var   Alg_WC_Custom_Order_Numbers The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Custom_Order_Numbers Instance
	 *
	 * Ensures only one instance of Alg_WC_Custom_Order_Numbers is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_WC_Custom_Order_Numbers - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Custom_Order_Numbers Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'custom-order-numbers-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Settings & Scripts
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @version 1.0.1
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_custom_order_numbers' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'custom-order-numbers-for-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="https://wpcodefactory.com/item/custom-order-numbers-woocommerce/">' . __( 'Unlock All', 'custom-order-numbers-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function includes() {
		// Settings
		require_once( 'includes/admin/class-alg-wc-custom-order-numbers-settings-section.php' );
		$settings = array();
		$settings[] = require_once( 'includes/admin/class-alg-wc-custom-order-numbers-settings-general.php' );
		if ( is_admin() && get_option( 'alg_custom_order_numbers_version', '' ) !== $this->version ) {
			foreach ( $settings as $section ) {
				foreach ( $section->get_settings() as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
						add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
					}
				}
			}
			update_option( 'alg_custom_order_numbers_version', $this->version );
		}
		// Core
		require_once( 'includes/class-alg-wc-custom-order-numbers-core.php' );
	}

	/**
	 * Add Custom Order Numbers settings tab to WooCommerce settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = include( 'includes/admin/class-alg-wc-settings-custom-order-numbers.php' );
		return $settings;
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_wc_custom_order_numbers' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Custom_Order_Numbers to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Custom_Order_Numbers
	 */
	function alg_wc_custom_order_numbers() {
		return Alg_WC_Custom_Order_Numbers::instance();
	}
}

alg_wc_custom_order_numbers();
