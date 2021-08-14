<?php
/**
 * Plugin Name: WooCommerce Realex Redirect Gateway
 * Plugin URI: http://www.woothemes.com/products/realex-redirect-payment-gateway/
 * Description: Adds the Realex Redirect Gateway to your WooCommerce website.
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com
 * Version: 1.3.2
 * Text Domain: woocommerce-gateway-realex-redirect
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2012-2014 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Gateway-Realex-Redirect
 * @author    SkyVerge
 * @category  Gateway
 * @copyright Copyright (c) 2012-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

// Plugin updates
woothemes_queue_update( plugin_basename( __FILE__ ), 'a57273a18ab6932aa661e21117f29874', '18736' );

// WC active check
if ( ! is_woocommerce_active() ) {
	return;
}

// Required library class
if ( ! class_exists( 'SV_WC_Framework_Bootstrap' ) ) {
	require_once( 'lib/skyverge/woocommerce/class-sv-wc-framework-bootstrap.php' );
}

SV_WC_Framework_Bootstrap::instance()->register_plugin( '3.1.0', __( 'WooCommerce Realex Redirect Gateway', 'woocommerce-gateway-realex-redirect' ), __FILE__, 'init_woocommerce_gateway_realex_redirect', array( 'minimum_wc_version' => '2.1', 'backwards_compatible' => '3.1.0' ) );

function init_woocommerce_gateway_realex_redirect() {


/**
 * # WooCommerce Gateway Realex Redirect Main Plugin Class
 *
 * ## Plugin Overview
 *
 * The main class for the Realex Redirect gateway.  This class handles all the
 * non-gateway tasks such as verifying dependencies are met, loading the text
 * domain, etc.  It also loads the Realex Gateway when needed now that the
 * gateway is only created on the checkout and settings page.  The gateway is
 * also loaded in the following instances:
 *
 * * From the admin_notices hook, to verify proper configuration
 *
 * ## Gateway Details
 *
 * Although this Gateway is named Redirect, it's actually a hosted payment
 * gateway, which the client is redirected to.  Unlike the typical redirect
 * gateway, no credit card information is supplied in the pay page, instead
 * the client is immediately redirected to a payment page hosted on the
 * Realex servers.  The basic flow of this payment gateway is as follows:
 *
 * 1. Customer checks out choosing Realex Redirect as the payment method
 * 2. Browser is directed to the WooCommerce Payment page (as with typical
 *    redirect payment methods)
 * 3. The browser is automatically redirected to the Realex payment site, and
 *    presented with a checkout form to supply their credit card info.
 * 4. Customer pays on the Realex site
 * 5. Realex server performs an asynchronous server-to-server request which
 *    is accepted by this plugin and used to complete the order
 * 6. The result of this response request is displayed to the customer, along
 *    with a link to "Continue Shopping" back on the site
 */
class WC_Realex_Redirect extends SV_WC_Plugin {


	/** version number */
	const VERSION = '1.3.2';

	/** @var WC_Realex_Redirect single instance of this plugin */
	protected static $instance;

	/** gateway id */
	const PLUGIN_ID = 'realex_redirect';

	/** plugin text domain */
	const TEXT_DOMAIN = 'woocommerce-gateway-realex-redirect';

	/** class name to load as gateway, can be base or subscriptions class */
	const GATEWAY_CLASS_NAME = 'WC_Gateway_Realex_Redirect';


	/**
	 * Initialize the plugin
	 *
	 * @see SV_WC_Plugin::__construct()
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			self::TEXT_DOMAIN
		);

		// Load the gateway
		add_action( 'sv_wc_framework_plugins_loaded', array( $this, 'includes' ) );
	}


	/**
	 * Loads Gateway class once parent class is available
	 *
	 * @since 1.2
	 */
	public function includes() {

		// load gateway class
		require_once( 'classes/class-wc-gateway-realex-redirect.php' );

		// Add class to WC Payment Methods
		add_filter( 'woocommerce_payment_gateways', array( $this, 'load_gateway' ) );
	}


	/**
	 * Adds gateway to the list of available payment gateways
	 *
	 * @param array $gateways array of gateway names or objects
	 * @return array $gateways array of gateway names or objects
	 */
	public function load_gateway( $gateways ) {

		$gateways[] = self::GATEWAY_CLASS_NAME;

		return $gateways;
	}


	/**
	 * Load the translation so that WPML is supported
	 *
	 * @see SV_WC_Plugin::load_translation()
	 */
	public function load_translation() {
		// localization in the init action for WPML support
		load_plugin_textdomain( 'woocommerce-gateway-realex-redirect', false, dirname( plugin_basename( $this->get_file() ) ) . '/i18n/languages' );
	}


	/**
	 * Checks if the configure-complus message needs to be rendered
	 *
	 * @since 1.2.5
	 * @see SV_WC_Plugin::add_delayed_admin_notices()
	 */
	public function add_delayed_admin_notices() {

		parent::add_delayed_admin_notices();

		// on the plugin settings page render a notice if 3DSecure is enabled and mcrypt is not installed
		if ( $this->is_plugin_settings() ) {
			$wc_gateway_realex_redirect = new WC_Gateway_Realex_Redirect();
			$message = $wc_gateway_realex_redirect->check_urls_configuration();

			if ( $message ) {
				$this->get_admin_notice_handler()->add_admin_notice( $message, 'check-urls-configuration' );
			}
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Main Realex Redirect Instance, ensures only one instance is/can be loaded
	 *
	 * @since 1.3.0
	 * @see wc_realex_redirect()
	 * @return WC_Realex_Redirect
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Gets the plugin documentation url
	 *
	 * @since 1.4-1
	 * @see SV_WC_Plugin::get_documentation_url()
	 * @return string documentation URL
	 */
	public function get_documentation_url() {
		return 'http://docs.woothemes.com/document/realex-redirec-payment-gateway/';
	}


	/**
	 * Returns the review page url
	 *
	 * @since 1.4-1
	 * @see SV_WC_Plugin::get_review_url()
	 * @return string review URL, or ''
	 */
	public function get_review_url() {
		return 'http://www.skyverge.com/product/woocommerce-realex-redirect-payment-gateway/#tab-reviews';
	}


	/**
	 * Gets the gateway configuration URL
	 *
	 * @since 1.2
	 * @see SV_WC_Plugin::get_settings_url()
	 * @param string $plugin_id the plugin identifier.  Note that this can be a
	 *        sub-identifier for plugins with multiple parallel settings pages
	 *        (ie a gateway that supports both credit cards and echecks)
	 * @return string plugin settings URL
	 */
	public function get_settings_url( $plugin_id = null ) {
		return $this->get_payment_gateway_configuration_url( self::GATEWAY_CLASS_NAME );
	}


	/**
	 * Returns true if on the gateway settings page
	 *
	 * @since 1.2
	 * @see SV_WC_Plugin::is_plugin_settings()
	 * @return boolean true if on the admin gateway settings page
	 */
	public function is_plugin_settings() {
		return $this->is_payment_gateway_configuration_page( self::GATEWAY_CLASS_NAME );
	}


	/**
	 * Returns the admin configuration url for the gateway with class name
	 * $gateway_class_name
	 *
	 * @since 2.2.0-1
	 * @param string $gateway_class_name the gateway class name
	 * @return string admin configuration url for the gateway
	 */
	public function get_payment_gateway_configuration_url( $gateway_class_name ) {

		return admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( $gateway_class_name ) );
	}


	/**
	 * Returns true if the current page is the admin configuration page for the
	 * gateway with class name $gateway_class_name
	 *
	 * @since 2.2.0-1
	 * @param string $gateway_class_name the gateway class name
	 * @return boolean true if the current page is the admin configuration page for the gateway
	 */
	public function is_payment_gateway_configuration_page( $gateway_class_name ) {

		return isset( $_GET['page'] ) && 'wc-settings' == $_GET['page'] &&
		isset( $_GET['tab'] ) && 'checkout' == $_GET['tab'] &&
		isset( $_GET['section'] ) && strtolower( $gateway_class_name ) == $_GET['section'];
	}


	/**
	 * Returns the plugin name, localized
	 *
	 * @since 1.2
	 * @see SV_WC_Plugin::get_plugin_name()
	 * @return string the plugin name
	 */
	public function get_plugin_name() {
		return __( 'WooCommerce Realex Redirect Gateway', self::TEXT_DOMAIN );
	}


	/**
	 * Returns __FILE__
	 *
	 * @since 1.2
	 * @see SV_WC_Plugin::get_file()
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {
		return __FILE__;
	}


	/**
	 * Returns the request variable named $name, if it exists
	 *
	 * @param string $name the request variable name
	 * @return mixed the value of $name or null
	 */
	private function get_request_var( $name ) {
		if ( isset( $_REQUEST[ $name ] ) ) return $_REQUEST[ $name ];
		return null;
	}


	/**
	 * Run every time.  Used since the activation hook is not executed when updating a plugin
	 *
	 * @see SV_WC_Plugin::install()
	 * @since 1.1.1
	 */
	protected function install() {

		// check for a pre 1.1.1 version
		$legacy_settings = get_option( 'woocommerce_realex_redirect_settings' );

		if ( $legacy_settings ) {

			// upgrading from the pre-versioned version, need to adjust the settings array

			// form_submission_method => 'yes'  In version 1.1.1 of the plugin we added the option to redirect
			//  from the checkout page to the hosted payment page, and made it the default behavior.  Unfortunately
			//  all the existing customers will have whitelisted the pay page url /checkout/pay/ with Realex so
			//  we can't go willy-nilly changing this on them so we'll default them to keeping their current
			//  behavior
			if ( ! isset( $legacy_settings['form_submission_method'] ) ) {
				$legacy_settings['form_submission_method'] = 'yes';
			}

			// log -> debug_mode
			if ( ! isset( $legacy_settings['log'] ) || 'no' == $legacy_settings['log'] ) {
				$legacy_settings['debug_mode'] = 'off';
			} elseif ( isset( $legacy_settings['log'] ) && 'yes' == $legacy_settings['log'] ) {
				$legacy_settings['debug_mode'] = 'log';
			}
			unset( $legacy_settings['log'] );

			// set the updated options array
			update_option( 'woocommerce_realex_redirect_settings', $legacy_settings );

			// upgrade path
			$this->upgrade( $legacy_version );

			// and we're done
			return;
		}
	}


} // end WC_Realex_Redirect


/**
 * Returns the One True Instance of Realex Redirect
 *
 * @since 1.3.0
 * @return WC_Realex_Redirect
 */
function wc_realex_redirect() {
	return WC_Realex_Redirect::instance();
}


/**
 * The WC_Realex_Redirect global object, exists only for backwards compat
 *
 * @deprecated 1.3.0
 * @name $wc_realex_redirect
 * @global WC_Realex_Redirect $GLOBALS['wc_realex_redirect']
 */
$GLOBALS['wc_realex_redirect'] = wc_realex_redirect();


} // init_woocommerce_gateway_realex_redirect()
