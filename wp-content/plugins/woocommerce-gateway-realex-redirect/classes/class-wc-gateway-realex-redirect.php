<?php
/**
 * WooCommerce Realex Redirect
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Realex Redirect to newer
 * versions in the future. If you wish to customize WooCommerce Realex Redirect for your
 * needs please refer to http://docs.woothemes.com/document/realex-redirec-payment-gateway/ for more information.
 *
 * @package     WC-Gateway-Realex-Redirect
 * @author      SkyVerge
 * @copyright   Copyright (c) 2012-2015, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Realex Redirect Gateway Class
 */
class WC_Gateway_Realex_Redirect extends WC_Payment_Gateway {

	/** @var string the endpoint hosted payment page url */
	private $endpoint_url = "https://epage.payandshop.com/epage.cgi";

	/** @var string the generated response URL, based on the host name */
	private $response_url;

	/** @var string the generated referring URL from the checkout page, based on the host name */
	private $referring_url_checkout;

	/** @var string the generated referring URL from the pay page, based on the host name */
	private $referring_url_checkout_pay;

	/** @var string the wc-api return URL */
	private $return_url;

	/** @var string "yes" or "no", indicates whether the gateway is in test mode */
	private $testmode;

	/** @var string 2 options for debug mode - off or log */
	public $debug_mode;

	/** @var string "yes" or "no" where "yes" indicates the payment should be authorized and settled */
	private $settlement;

	/** @var array realex card types to display images for, from the $card_type_options member*/
	private $cardtypes;

	/** @var string the account merchant id */
	private $merchantid;

	/** @var string the account shard secret */
	private $sharedsecret;

	/** @var string optional test account name */
	private $accounttest;

	/** @var string optional live account name */
	private $accountlive;

	/** @var string Indicates whether the Realex hosted payment page should be posted to directly from the pay page, one of 'yes' or 'no' */
	private $form_submission_method;

	/** @var string Indicates whether Address Verification Service should be enabled, one of 'yes' or 'no' */
	private $enable_avs;

	/**
	 * Associative array of realex card types to card name
	 * @var array
	 */
	private $card_type_options;

	/**
	 * Build and initialize the gateway
	 *
	 * @see WC_Payment_Gateway::__construct()
	 */
	public function __construct() {

		$this->id                 = 'realex_redirect';
		$this->method_title       = __( 'Realex', WC_Realex_Redirect::TEXT_DOMAIN );
		$this->method_description = __( 'Realex Redirect Gateway provides a secure checkout process for your customers without the requirement of an SSL certificate', WC_Realex_Redirect::TEXT_DOMAIN );

		// to set up the images icon for your shop, use the included images/cards.png
		//  for the card images you accept, and hook into this filter with a return
		//  value like: plugins_url( '/images/cards.png', __FILE__ );
		$this->icon               = apply_filters( 'woocommerce_realex_icon', '' );

		// actually this gateway does not have any payment fields, but unfortunately
		//  it seems necessary to set this to true so that the payment_fields()
		//  method will guaranteed to be called and the styling for the gateway
		//  credit card icons can be rendered.
		$this->has_fields = true;

		// generate the response and referring urls
		$this->response_url               = add_query_arg( 'wc-api', get_class( $this ), home_url( '/' ) );
		$this->referring_url_checkout     = get_permalink( woocommerce_get_page_id( 'checkout' ) );
		$this->referring_url_checkout_pay = wc_get_endpoint_url( 'order-pay' );
		$this->return_url                 = add_query_arg( 'wc-api', get_class( $this ), home_url( '/' ) );

		// make ssl if needed
		if ( ( is_ssl() && ! is_admin() ) || get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) {
			$this->response_url               = str_replace( 'http:', 'https:', $this->response_url );
			$this->referring_url_checkout     = str_replace( 'http:', 'https:', $this->referring_url_checkout );
			$this->referring_url_checkout_pay = str_replace( 'http:', 'https:', $this->referring_url_checkout_pay );
			$this->return_url                 = str_replace( 'http:', 'https:', $this->return_url );
		}

		// define the default card type options, and allow plugins to add in additional ones.
		//  Additional display names can be associated with a single card type by using the
		//  following convention: VISA: Visa, VISA-1: Visa Debit, etc
		$default_card_type_options = array(
			'VISA'   => 'Visa',
			'MC'     => 'MasterCard',
			'AMEX'   => 'American Express',
			'LASER'  => 'Laser',
			'SWITCH' => 'Switch',
			'DINERS' => 'Diners'
		);
		$this->card_type_options = apply_filters( 'woocommerce_realex_redirect_card_types', $default_card_type_options );

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Load setting values
		foreach ( $this->settings as $setting_key => $setting ) {
			$this->$setting_key = $setting;
		}

		// add the current environment to the admin-supplied gateway description which is displayed on the checkout page
		if ( $this->is_test_mode() ) {
			$this->description = trim( $this->description . ' ' . __( 'TEST MODE ENABLED', WC_Realex_Redirect::TEXT_DOMAIN ) );
		}

		// Response handler
		add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'handle_ipn_response' ) );

		// Redirect handler
		add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'handle_redirect_back' ) );

		// pay page fallback
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'payment_page' ) );

		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}
	}


	/**
	 * Initialize Settings Form Fields
	 *
	 * Add an array of fields to be displayed
	 * on the gateway's settings screen.
	 *
	 * @see WC_Settings_API::init_form_fields()
	 */
	public function init_form_fields() {

		$this->form_fields = array(

			'enabled' => array(
				'title'       => __( 'Enable', WC_Realex_Redirect::TEXT_DOMAIN ),
				'label'       => __( 'Enable Realex', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),

			'title' => array(
				'title'       => __( 'Title', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'text',
				'desc_tip'    => __( 'Payment method title that the customer will see on your website.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => __( 'Credit Card', WC_Realex_Redirect::TEXT_DOMAIN )
			),

			'description' => array(
				'title'       => __( 'Description', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'textarea',
				'desc_tip'    => __( 'Payment method description that the customer will see on your website.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => __( 'Pay securely using your credit card.', WC_Realex_Redirect::TEXT_DOMAIN )
			),

			'testmode' => array(
				'title'       => __( 'Test Mode', WC_Realex_Redirect::TEXT_DOMAIN ),
				'label'       => __( 'Enable Test Mode', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'description' => __( 'Place the payment gateway in test mode to work with your test account.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => 'yes'
			),

			'debug_mode' => array(
				'title'       => __( 'Debug Mode', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'select',
				'desc_tip'    => __( 'Record all Realex gateway communication to the WooCommerce Realex log file.  It is recommended this be "off" unless in test mode or when debugging an error with a live account.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => 'off',
				'options' => array(
					'off' => __( 'Off', WC_Realex_Redirect::TEXT_DOMAIN ),
					'log' => __( 'Save to Log', WC_Realex_Redirect::TEXT_DOMAIN ),
				),
			),

			'settlement' => array(
				'title'       => __( 'Submit for Settlement', WC_Realex_Redirect::TEXT_DOMAIN ),
				'label'       => __( 'Submit all transactions for settlement immediately.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'yes'
			),

			'cardtypes' => array(
				'title'       => __( 'Accepted Card Logos', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'multiselect',
				'class'       => 'wc-enhanced-select chosen_select',
				'css'         => 'width: 350px;',
				'desc_tip'    => __( 'Select which card types you accept to display the logos for on your checkout page.  This is purely cosmetic and optional, and will have no impact on the cards actually accepted by your account on the hosted payment page.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => '',
				'options'     => $this->card_type_options,
			),

			'merchantid' => array(
				'title'       => __( 'Merchant ID', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'text',
				'desc_tip'    => __( 'Your Realex merchant id.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => ''
			),

			'sharedsecret' => array(
				'title'       => __( 'Shared Secret', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'password',
				'desc_tip'    => __( 'The shared secret for your account, provided by Realex.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => ''
			),

			'accounttest' => array(
				'title'       => __( 'Test Account', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'text',
				'desc_tip'    => __( 'Optional test account (if not supplied the default account will be used)', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => ''
			),

			'accountlive' => array(
				'title'       => __( 'Live Account', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'text',
				'desc_tip'    => __( 'Optional live account (if not supplied the default account will be used)', WC_Realex_Redirect::TEXT_DOMAIN ),
				'default'     => ''
			),

			'form_submission_method' => array(
				'title'       => __( 'Submission method', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'label'       => __( 'Use form submission method.', WC_Realex_Redirect::TEXT_DOMAIN ),
				'description' => sprintf( __( 'Enable this to post order data to Realex via a form instead of using a redirect/querystring.  For existing customers: to disable this setting you are required to provide Realex with the Referring URL %s for whitelisting.', WC_Realex_Redirect::TEXT_DOMAIN ),
					'<strong class="nobr">' . $this->referring_url_checkout . '</strong>' ),
				'default'     => 'no',
			),

			'enable_avs' => array(
				'title'   => __( 'Address Verification Service (AVS)', WC_Realex_Redirect::TEXT_DOMAIN ),
				'label'   => __( 'Perform an AVS check on customers billing addresses', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'    => 'checkbox',
				'default' => 'yes',
			),

			'urls_configured' => array(
				'title'       => __( 'Response/Referring URLs Configured', WC_Realex_Redirect::TEXT_DOMAIN ),
				'label'       => __( 'I certify that I have provided Realex with the proper Response/Referring URLs for this gateway', WC_Realex_Redirect::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'description' => sprintf( __( 'To properly process transactions in live mode you must provide Realex (%s) with your Referring URLs: %s and %s and Response URL: %s', WC_Realex_Redirect::TEXT_DOMAIN ),
					'<a href="mailto:support@realexpayments.com">support@realexpayments.com</a>',
					'<strong class="nobr">' . $this->referring_url_checkout . '</strong>',
					'<strong class="nobr">' . $this->referring_url_checkout_pay . '</strong>',
					'<strong class="nobr">' . $this->response_url . '</strong>' ),
				'default'     => 'no'
			)
		);
	}


	/**
	 * Return the checkout icon
	 *
	 * @see WC_Payment_Gateway::get_icon()
	 * @return string accepted payment icons
	 */
	public function get_icon() {

		$icon = '';
		if ( $this->icon ) {
			// default behavior
			$icon = '<img src="' . esc_url( WC_HTTPS::force_https_url( $this->icon ) ) . '" alt="' . esc_attr( $this->title ) . '" />';
		} elseif ( $this->cardtypes ) {
			// display icons for the selected card types
			$icon = '';
			foreach ( $this->cardtypes as $cardtype ) {
				if ( file_exists( wc_realex_redirect()->get_plugin_path() . '/assets/images/card-' . strtolower( $cardtype ) . '.png' ) ) {
					$icon .= '<img src="' . esc_url( WC_HTTPS::force_https_url( wc_realex_redirect()->get_plugin_url() . '/assets/images/card-' . strtolower( $cardtype ) . '.png' ) ) . '" alt="' . esc_attr( strtolower( $cardtype ) ) . '" />';
				}
			}
		}

		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}


	/**
	 * Being a redirect method, there are no payment fields on the
	 * main checkout/pay pages, rather the payment type is selected, and
	 * the customer is taken to the hosted realex payment page to complete
	 * the transaction.
	 *
	 * @see WC_Payment_Gateway::payment_fields()
	 */
	public function payment_fields() {

		parent::payment_fields();

		?>
		<style type="text/css">#payment ul.payment_methods li label[for='payment_method_realex_redirect'] img:nth-child(n+2) { margin-left:1px; }</style>
		<?php
	}


	/**
	 * Process the payment and return the result, which for a non-direct
	 * payment gateway like is to return success and redirect to payment
	 * page hosted Realex's servers
	 *
	 * @see WC_Payment_Gateway::process_payment()
	 * @param int $order_id order identifier
	 */
	public function process_payment( $order_id ) {

		$order = SV_WC_Plugin_Compatibility::wc_get_order( $order_id );

		if ( $this->form_submission_method() ) {

			// redirect to pay page followed by automatic form post to realex
			return array(
				'result'   => 'success',
				'redirect' => $order->get_checkout_payment_url( true ),
			);

		} else {

			// redirect directly to hosted payment page, with transaction parameters in the query string
			$realex_args = http_build_query( $this->get_realex_redirect_params( $order ), '', '&' );

			return array(
				'result'   => 'success',
				'redirect' => $this->get_endpoint_url() . '?' . $realex_args
			);

		}
	}


	/**
	 * Displays the payment page, which for a hosted payment gateway like
	 * Realex just contains a 'checkout' button which brings the customer
	 * to the Realex website/payment page (we do try to automatically submit
	 * the checkout form and bring them straight to the realex payment site)
	 *
	 * @param int $order_id identifies the order
	 */
	public function payment_page( $order_id ) {

		echo '<p>' . __( 'Thank you for your order, please click the button below to pay with Realex.', WC_Realex_Redirect::TEXT_DOMAIN ) . '</p>';
		$this->generate_realex_form( $order_id );
	}


	/**
	 * Returns the parameters required to request the hosted Realex payment page
	 *
	 * @since 1.1.1
	 * @param WC_Order $order the order object
	 * @return array associative array of name-value parameters
	 */
	private function get_realex_redirect_params( $order ) {

		// Realex will not allow the reuse of order numbers, so it's important
		//  to send a new order number every time a post is made to their servers.
		//  This covers both the case of a failed order, as well as someone just
		//  hitting 'back' on their browser
		$realex_order_number_suffix = '';
		$realex_retry_count = 0;

		if ( is_numeric( $order->realex_retry_count ) ) {
			$realex_retry_count = $order->realex_retry_count;

			// increment the retry count so we don't get order number clashes
			//  Granted, this will increment the count on every page load, but it's not like that
			//  will hurt anything
			$realex_retry_count++;
		}

		// keep track of the retry count
		update_post_meta( $order->id, '_realex_retry_count', $realex_retry_count );

		if ( $realex_retry_count ) {
			$realex_order_number_suffix = apply_filters( 'wc_realex_redirect_order_number_suffix', '-' . $realex_retry_count, $order->id );
		}

		$realex_args = array(
			'MERCHANT_ID'      => $this->get_merchant_id(),
			'ORDER_ID'         => preg_replace( '/[^\w\d_\-]/', '', $order->get_order_number() ) . $realex_order_number_suffix,
			'ACCOUNT'          => $this->get_account(),
			'AMOUNT'           => number_format( $order->get_total(), 2, '', '' ),  // in pennies
			'CURRENCY'         => $order->get_order_currency(),
			'TIMESTAMP'        => date( 'YmdHis' ),
			'AUTO_SETTLE_FLAG' => $this->get_settlement(),
			'X_ORDER_ID'       => $order->id,
		);

		if ( $this->enable_avs() ) {
			$realex_args['BILLING_CODE']  = substr( preg_replace( '/[^0-9]/', '', $order->billing_postcode ), 0, 5 ) . '|' . substr( preg_replace( '/[^0-9]/', '', $order->billing_address_1 ), 0, 5 );
			$realex_args['BILLING_CO']    = $order->billing_country;
			$realex_args['SHIPPING_CODE'] = substr( preg_replace( '/[^0-9]/', '', $order->shipping_postcode ), 0, 5 ) . '|' . substr( preg_replace( '/[^0-9]/', '', $order->shipping_address_1 ), 0, 5 );
			$realex_args['SHIPPING_CO']   = $order->shipping_country;
		}

		$realex_args['X_SHA1HASH'] = $this->get_sha1_hash( $realex_args['TIMESTAMP'], $realex_args['MERCHANT_ID'], $order->id, $realex_args['AMOUNT'] );

		$realex_args['SHA1HASH']   = $this->get_sha1_hash( $realex_args['TIMESTAMP'], $realex_args['MERCHANT_ID'], $realex_args['ORDER_ID'], $realex_args['AMOUNT'], $realex_args['CURRENCY'] );

		return $realex_args;

	}


	/**
	 * Generate the realex button link, automatically attempting to submit
	 * the form and bring the user straight to the realex hosted payment site
	 *
	 * @param int $order_id order identifier
	 */
	private function generate_realex_form( $order_id ) {

		$order = SV_WC_Plugin_Compatibility::wc_get_order( $order_id );

		$realex_args = $this->get_realex_redirect_params( $order );

		// log request
		if ( $this->log_enabled() ) {
			wc_realex_redirect()->log( "Redirect Parameters:\n" . print_r( $realex_args, true ) );
		}

		// attempt to automatically submit the form and bring them to the realex payment site
		wc_enqueue_js('
			jQuery("body").block({
					message: "<img src=\"' . esc_url( wc_realex_redirect()->get_plugin_url() ) . '/assets/images/ajax-loader.gif\" alt=\"Redirecting&hellip;\" style=\"float:left; margin-right: 10px;\" />' . __( 'Thank you for your order. We are now redirecting you to Realex to make payment.', WC_Realex_Redirect::TEXT_DOMAIN ) . '",
					overlayCSS: {
						background: "#fff",
						opacity: 0.6
					},
					css: {
						padding:         20,
						textAlign:       "center",
						color:           "#555",
						border:          "3px solid #aaa",
						backgroundColor: "#fff",
						cursor:          "wait",
						lineHeight:      "32px"
					}
				});

			jQuery("#realex_payment_form").submit();
		');

		$payment_fields = array();

		foreach ( $realex_args as $key => $value ) {
			$payment_fields[] = '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
		}

		echo '<form action="' . esc_url( $this->get_endpoint_url() ) . '" method="post" id="realex_payment_form">' .
				 implode( '', $payment_fields ) .
				'<input type="submit" class="button-alt" value="' . __( 'Pay via Realex', WC_Realex_Redirect::TEXT_DOMAIN ) . '" />' .
				'<a class="button cancel" href="' . esc_url( $order->get_cancel_order_url() ) . '">' . __( 'Cancel order &amp; restore cart', WC_Realex_Redirect::TEXT_DOMAIN ) . '</a>' .
			'</form>';
	}


	/**
	 * Handle the asynchronous server-to-server payment response request from
	 * Realex.
	 *
	 * The customer is not automtically redirected back to our server, instead
	 * the output of this page is displayed to them.  Per the realex documentation,
	 * a simple message informing the customer of the transaction result, along
	 * with a link to continue shopping, should be returned.  No images or other
	 * external page elements are allowed.
	 *
	 * Note that per the Realex documentation: If Realex Payments are unable
	 * to connect to your response URL to deliver the transaction response,
	 * a generic message will be displayed to the customer. An alert will be
	 * generated on the Realex Payments systems and will be forwarded to you
	 * by a member of the support team.
	 */
	public function handle_ipn_response() {

		if ( ! $order_id = $this->get_post( 'X_ORDER_ID' ) ) {
			return;
		}

		// look up the order
		$order = SV_WC_Plugin_Compatibility::wc_get_order( $this->get_post( 'X_ORDER_ID' ) );

		// log the request as needed
		if ( $this->log_enabled() ) {
			$this->log_request();
		}

		// verify the response signature
		if ( $this->verify_response_signature( $_POST ) ) {

			// verify that we found the order
			if ( ! $order->id ) {
				if ( $this->log_enabled() ) wc_realex_redirect()->log( sprintf( "Error - order number %s not found", $this->get_post( 'X_ORDER_ID' ) ) );

				// Return response text
				$this->general_payment_error_reply( $order_id );
			}

			// verify our custom signed X_ORDER_ID parameter
			if ( ! $this->verify_x_order_id_response_signature( $_POST ) ) {

				$message = sprintf( __( "Error - invalid signature for response order number '%s', possible fradulent payment attempt.", WC_Realex_Redirect::TEXT_DOMAIN ), $this->get_post( 'X_ORDER_ID' ) );
				if ( $this->log_enabled() ) {
					wc_realex_redirect()->log( $message );
				}

				$this->order_failed( $order, $message );

				// Return response text
				$this->general_payment_error_reply( $order_id );
			}

			// verify the AMOUNT parameter
			if ( number_format( $order->get_total(), 2, '', '' ) != $this->get_post( 'AMOUNT' ) ) {

				$message = sprintf( __( "Error - amount returned by Realex '%s' does not match expected amount of '%s', possible fradulent payment attempt.", WC_Realex_Redirect::TEXT_DOMAIN ), $this->get_post( 'AMOUNT' ), $order->get_total() * 100 );
				if ( $this->log_enabled() ) {
					wc_realex_redirect()->log( $message );
				}

				$this->order_failed( $order, $message );

				// Return response text
				$this->general_payment_error_reply( $order_id );
			}

			if ( '00' == $this->get_post( 'RESULT' ) ) {
				// Successful payment: update the order record with success

				// this is most likely to come up during testing of gateway issues
				if ( ! in_array( SV_WC_Plugin_Compatibility::get_order_status( $order ), array( 'on-hold', 'pending', 'failed' ) ) ) {
					header( 'HTTP/1.1 200 OK' );
					echo __( "Error - payment has already been received for order '%s'", $order->get_order_number() );
					exit;
				}

				$order_note = __( 'Credit Card Transaction Approved.', WC_Realex_Redirect::TEXT_DOMAIN );
				$order_note .= ' ' . sprintf( __( "Realex order number: %s", WC_Realex_Redirect::TEXT_DOMAIN ), $this->get_post( 'ORDER_ID' ) );

				$order->add_order_note( $order_note );

				$order->payment_complete();

				// store the payment reference in the order
				add_post_meta( $order->id, '_realex_payment_reference', $this->get_post( 'PASREF' ) );

				// Redirect to thank you page
				header( 'HTTP/1.1 200 OK' );
				echo '<script type="text/javascript">window.location = "' . esc_url_raw( add_query_arg( array( 'order_id' => $order->id, 'result' => 'success' ), $this->return_url ) ) . '"</script>';
				exit;

			} elseif ( '501' == $this->get_post( 'RESULT' ) ) {
				// This transaction has already been processed, not truly a failure that requires the order status to be updated
				$order_note = sprintf( __( 'Realex Credit Card payment retry attempt (Result: %s - "%s").', WC_Realex_Redirect::TEXT_DOMAIN ), $this->get_post( 'RESULT' ), $this->get_post( 'MESSAGE' ) );

				if ( $this->log_enabled() ) {
					wc_realex_redirect()->log( $order_note );
				}

				$order->add_order_note( $order_note );

				// Return response text
				header( 'HTTP/1.1 200 OK' );
				echo '<script type="text/javascript">window.location = "' . esc_url_raw( add_query_arg( array( 'order_id' => $order->id, 'result' => 'retry_attempt' ), $this->return_url ) ) . '"</script>';
				exit;

			} else {
				// Failure: it's important that the order status be set to 'failed' so that a new order number can be generated,
				//  because Realex does not allow the same order number to be used once a payment attempt has failed
				$error_note = sprintf( __( 'Realex Credit Card payment failed (Result: %s - "%s").', WC_Realex_Redirect::TEXT_DOMAIN ), $this->get_post( 'RESULT' ), $this->get_post( 'MESSAGE' ) );

				if ( $this->log_enabled() ) {
					wc_realex_redirect()->log( $error_note );
				}

				$this->order_failed( $order, $error_note );

				// Return response text
				header( 'HTTP/1.1 200 OK' );
				echo '<script type="text/javascript">window.location = "' . esc_url_raw( add_query_arg( array( 'order_id' => $order->id, 'result' => 'failure' ), $this->return_url ) ) . '"</script>';
				exit;
			}

		} else {
			// error: response was not properly signed by realex
			$message = "Error - invalid transaction signature, check your Realex settings";
			if ( $this->log_enabled() ) {
				wc_realex_redirect()->log( $message );
			}

			if ( $order->id ) {
				$this->order_failed( $order, $message );
			}

			// Return response text
			$this->general_payment_error_reply( $order_id );
		}
	}


	/**
	 * Handle the redirect back to the shop
	 *
	 * @since 1.3.0
	 */
	public function handle_redirect_back() {

		if ( isset( $_GET['order_id'] ) && isset( $_GET['result'] ) ) {

			if ( $order = SV_WC_Plugin_Compatibility::wc_get_order( $_GET['order_id'] ) ) {

				switch ( $_GET['result'] ) {

					case 'success':
						wp_redirect( $this->get_return_url( $order ) );
						exit;

					case 'retry_attempt':
						wc_add_notice( __( 'This order has already been processed, if you feel this is incorrect please contact the merchant.', WC_Realex_Redirect::TEXT_DOMAIN ), 'error' );
						wp_redirect( $this->get_return_url( $order ) );
						exit;

					// general error or payment failure
					default:
						wc_add_notice( __( 'An error occurred, please try again or try an alternate form of payment.', WC_Realex_Redirect::TEXT_DOMAIN ), 'error' );
						wp_redirect( $order->get_checkout_payment_url() );
						exit;

				}

			} else {

				// not much we can do without the order
				echo sprintf( __( 'An error occurred with your payment, please <a href="%s">contact the merchant</a> to provide an alternative payment method.', WC_Realex_Redirect::TEXT_DOMAIN ), home_url() );
			}

		}
	}


	/**
	 * Check if this gateway is enabled and configured.
	 *
	 * @see WC_Payment_Gateway::is_available()
	 */
	public function is_available() {

		// proper configuration
		if ( ! $this->get_merchant_id() || ! $this->sharedsecret ) {
			return false;
		}

		// make sure they provided Realex with their referring/response URLs
		if ( ! $this->are_urls_configured() ) {
			return false;
		}

		return parent::is_available();
	}


	/**
	 * Check if Referring/Response URLs have been provided to Realex and
	 * display an annoying message at the top of every admin page if not
	 */
	public function check_urls_configuration() {
		if ( "yes" == $this->enabled && ! $this->are_urls_configured() ) {
			return
				sprintf( __( 'To properly process transactions with Realex you must provide %s with your Referring URLs: %s and %s and Response URL: %s.  Then certify this has been done in the WooCommerce Realex plugin configuration.', WC_Realex_Redirect::TEXT_DOMAIN ),
					'<a href="mailto:support@realexpayments.com">support@realexpayments.com</a>',
					'<strong class="nobr">' . $this->referring_url_checkout . '</strong>',
					'<strong class="nobr">' . $this->referring_url_checkout_pay . '</strong>',
					'<strong class="nobr">' . $this->response_url . '</strong>'
				);
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Mark the given order as failed, and set the order note
	 *
	 * @param WC_Order $order the order
	 * @param string $order_note the order note to set
	 */
	private function order_failed( $order, $order_note ) {
		if ( ! SV_WC_Plugin_Compatibility::order_has_status( $order, 'failed' ) ) {
			$order->update_status( 'failed', $order_note );
		} else {
			// otherwise, make sure we add the order note so we can detect when someone fails to check out multiple times
			$order->add_order_note( $order_note );
		}
	}


	/**
	 * Render a generic payment error message with a link to this site, and
	 * halt execution
	 *
	 * @param int $order_id Optional. The order ID. Default 0.
	 */
	private function general_payment_error_reply( $order_id = 0 ) {
		header( 'HTTP/1.1 200 OK' );
		echo '<script type="text/javascript">window.location = "' . esc_url_raw( add_query_arg( array( 'order_id' => $order_id, 'result' => 'general_error' ), $this->return_url ) ). '"</script>';
		exit;
	}


	/**
	 * Log the Realex request to woocommerce/logs/realex_redirect.txt
	 */
	private function log_request() {

		$response = $_POST;
		unset( $response['wc-api'] );
		wc_realex_redirect()->log( "Redirect Response:\n" . print_r( $response, true ) );
	}


	/**
	 * Verify the response signature returned by the Realex gateway
	 *
	 * @param array $response the response from Realex
	 *
	 * @return boolean true if the signature is valid, false otherwise
	 */
	private function verify_response_signature( $response ) {
		return $response['SHA1HASH'] == $this->get_sha1_hash( $response['TIMESTAMP'], $response['MERCHANT_ID'], $response['ORDER_ID'], $response['RESULT'], $response['MESSAGE'], $response['PASREF'], $response['AUTHCODE'] );
	}


	/**
	 * Verify the custom X_ORDER_ID response signature returned by the Realex gateway
	 *
	 * @since 1.2.3
	 * @param array $response the response from Realex
	 *
	 * @return boolean true if the signature is valid, false otherwise
	 */
	private function verify_x_order_id_response_signature( $response ) {
		return isset( $response['X_SHA1HASH'] ) && $response['X_SHA1HASH'] == $this->get_sha1_hash( $response['TIMESTAMP'], $response['MERCHANT_ID'], $response['X_ORDER_ID'], $response['AMOUNT'] );
	}


	/**
	 * Returns the Realex sha1 hash for the provided arguments.  This function takes
	 * a variable list of arguments and returns the hash of them
	 *
	 * @param mixed ...
	 *
	 * @return string realex sha1 hash
	 */
	private function get_sha1_hash() {
		$args = func_get_args();  // assign func_get_args() to avoid a Fatal error in some instances
		return sha1( sha1( implode( '.', $args ) ) . '.' . $this->sharedsecret );
	}


	/**
	 * Safely get post data if set
	 *
	 * @return string post value for $name, or null
	 */
	private function get_post( $name ) {
		if ( isset( $_POST[ $name ] ) ) {
			return $_POST[ $name ];
		}
		return null;
	}


	/** Getter methods ******************************************************/


	/**
	 * Returns whether the transactions should be settled or authorized only
	 *
	 * @return int 1 to authorize and settle, 0 to authorize only
	 */
	public function get_settlement() {
		return "yes" === $this->settlement ? 1 : 0;
	}


	/**
	 * Return the merchant id
	 *
	 * @return string merchant id
	 */
	public function get_merchant_id() {
		return $this->merchantid;
	}


	/**
	 * Returns the endpoint url
	 *
	 * @return string endpoint URL
	 */
	private function get_endpoint_url() {
		return $this->endpoint_url;
	}


	/**
	 * Returns the account for the current mode (test/live)
	 *
	 * @return string account
	 */
	private function get_account() {

		if ( $this->is_test_mode() ) {
			$account = $this->accounttest;
		} else {
			$account = $this->accountlive;
		}

		return apply_filters( 'woocommerce_realex_account', $account, $this );
	}


	/**
	 * Is test mode enabled?
	 *
	 * @return boolean true if test mode is enabled
	 */
	public function is_test_mode() {
		return "yes" == $this->testmode;
	}


	/**
	 * Should Realex communication be logged?
	 *
	 * @return boolean true if log mode is enabled
	 */
	private function log_enabled() {
		return "log" == $this->debug_mode;
	}


	/**
	 * Did the merchant certify that they properly configured their referring/
	 * response URLs with Realex?
	 *
	 * @return boolean true if the referring/respons URLS have been configured
	 */
	public function are_urls_configured() {
		return "yes" == $this->urls_configured;
	}


	/**
	 * Returns true if the Realex hosted payment page should be posted to directly
	 * from the woocommerce /checkout/pay/ page, or redirected to with checkout
	 * parameters in the query
	 *
	 * @since 1.1.1
	 * @return boolean true if realex should be posted to from the pay page
	 */
	public function form_submission_method() {
		return "yes" == $this->form_submission_method;
	}


	/**
	 * Returns true if the Address Verification Service (AVS) is enabled
	 *
	 * @since 1.3.0
	 * @return boolean true if AVS is enabled
	 */
	public function enable_avs() {
		return 'yes' == $this->enable_avs;
	}


}
