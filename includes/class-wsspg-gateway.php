<?php
/**
 * Wsspg Payment Gateway
 *
 * Extends WC_Payment_Gateway_CC.
 *
 * It’s important to note that adding hooks inside gateway classes
 * may not trigger. Gateways are only loaded when needed, such as
 * during checkout and on the settings page in the admin.
 *
 * You should keep hooks outside of the gateway class or use WC-API
 * if you need to hook into WordPress events from your class.
 *
 * @since       1.0.0
 * @package     Wsspg
 * @subpackage  Wsspg/includes
 * @author      wsspg <wsspg@mail.com>
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright   (c) 2016 https://github.com/wsspg
 */

if( ! defined( 'ABSPATH' ) ) exit; // exit if accessed directly.

/**
 * Wsspg Payment Gateway Class
 *
 * @since    1.0.0
 * @class    Wsspg_Payment_Gateway
 * @extends  WC_Payment_Gateway_CC
 */
class Wsspg_Payment_Gateway extends WC_Payment_Gateway_CC {

	/**
	 * Capture or Authorize.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $payment_action;

	/**
	 * True if Wsspg should save tokenized payment methods for future use.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	private $save_payment_method;

	/**
	 * Stores the appropriate API key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $key;

	/**
	 * Enable or disable Stripe Checkout.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	protected $stripe_checkout_enabled;

	/**
	 * Specify whether to include the option to "Remember Me" for future purchases.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	private $stripe_checkout_remember_me;

	/**
	 * Display text on Stripe Checkout modal button.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $stripe_checkout_button;

	/**
	 * Stripe Checkout language.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $stripe_checkout_locale;

	/**
	 * URL to brand/logo image.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $stripe_checkout_thumbnail;

	/**
	 * True if Wsspg should support Bitcoin transactions.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	private $bitcoin;

	/**
	 * True if Stripe should automatically handle refunded Bitcoin mispayments after one hour.
	 *
	 * @since  1.0.0
	 * @var    bool
	 */
	private $bitcoin_refund_mispayments;

	/**
	 * True if Wsspg should support Alipay transactions.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	private $alipay;

	/**
	 * 3 letter ISO currency code (uppercase).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $currency;

	/**
	 * True if Wsspg should log events.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	private $debug;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		$this->id = WSSPG_PLUGIN_ID;
		$this->method_description = WSSPG_PLUGIN_DESC . '<hr>';
		$this->method_title = WSSPG_PLUGIN_TITLE;
		$this->supports = array( 'subscriptions', 'products', 'refunds', 'pre-orders', 'tokenization' );
		$this->has_fields = true;
		$this->view_transaction_url = 'https://dashboard.stripe.com/payments/%s';
		$this->init_form_fields();
		$this->init_settings();
		$this->payment_action = $this->get_option( 'payment_action', 'capture' );
		$this->save_payment_method = $this->get_option( 'save_payment_method', 'enabled' ) === 'enabled' ? true : false;
		$this->title = $this->get_option( 'title', WSSPG_PLUGIN_TITLE );
		$this->description = $this->get_option( 'description', NULL );
		$this->order_button_text = $this->get_option( 'order_button_text', esc_html__( 'Place Order', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) );
		$this->stripe_checkout_enabled = $this->get_option( 'stripe_checkout_enabled', 'disabled' ) === 'enabled' ? true : false;
		$this->stripe_checkout_remember_me = $this->get_option( 'stripe_checkout_remember_me', 'enabled' ) === 'enabled' ? true : false;
		$this->stripe_checkout_button = $this->get_option( 'stripe_checkout_button', esc_html__( 'Pay {{amount}}', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) );
		$this->stripe_checkout_locale = $this->get_option( 'stripe_checkout_locale', 'en' );
		$this->stripe_checkout_thumbnail = $this->get_option( 'stripe_checkout_thumbnail', '' );
		$this->bitcoin = $this->get_option( 'bitcoin', 'disabled' ) === 'enabled' ? true : false;
		$this->bitcoin_refund_mispayments = $this->get_option( 'bitcoin', 'disabled' ) === 'enabled' ? true : false;
		$this->alipay = $this->get_option( 'alipay', 'disabled' ) === 'enabled' ? true : false;
		$this->debug = $this->get_option( 'debug', 'disabled' );
		$this->icon = $this->get_icon();
		$this->key = Wsspg::get_api_key( 'secret' );
		$this->currency = strtoupper( get_woocommerce_currency() );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'admin_notices', array( $this, 'wsspg_gateway_admin_notices' ) );
	}

	/**
	 * Define payment gateway settings fields.
	 *
	 * @since  1.0.0
	 */
	public function init_form_fields() {

		$this->form_fields = include( 'settings-wsspg.php' );
	}

	/**
	 * Process payment.
	 *
	 * @since   1.0.0
	 * @param   int
	 * @return  array
	 */
	public function process_payment( $order_id ) {

		if( ! isset( $_POST['wsspg-data'] ) ) {
			throw new Exception();
		} else {
			global $woocommerce;
			//	decode the form.
			//	throw an error if the data has an errors flag.
			$raw_data = stripslashes( $_POST['wsspg-data'] );
			$data = json_decode( $raw_data, true );
			if( isset( $data['error'] ) ) throw new Exception();
			//	grab an order and a customer.
			//	throw an error if either is not set.
			$order = wc_get_order( $order_id );
			$customer = Wsspg_Customer::load( $order->get_customer_id() );
			if( ! isset( $order ) || ! isset( $customer ) ) throw new Exception();
			//	give everyone a stripe id, regardless.
			//	throw an error if one is not set after the api request.
			if( ! isset( $customer->stripe ) ) {
				$customer->params = array(
					"email"    => $order->get_billing_email(),
					"metadata" => array(
						"first_name" => $order->get_billing_first_name(),
						"last_name"  => $order->get_billing_last_name(),
						"telephone"  => $order->get_billing_phone(),
					),
				);
				$customer->_object = Wsspg_API::request( 'customers', $this->key, $customer->params );
				if( ! isset( $customer->_object->id ) ) throw new Exception();
				$customer->stripe = $customer->_object->id;
				if( ! $customer->guest ) $customer->save();
			}
			//	retrieve a saved payment source token, or create a new one.
			//	throw an error if the api request returns null.
			//	maybe save the source token.
			if( $data['method'] === 'saved' ) {
				$customer->source = $customer->get_saved_source( $data['token'] );
			} else {
				$customer->source = Wsspg_API::request(
					"customers/{$customer->stripe}/sources",
					$this->key,
					array(
						"source" => $data['token']['id']
					)
				);
			}
			if( ! isset( $customer->source ) ) throw new Exception();
			if( $data['save'] ) $customer->save_source();
			//	Bitcoin payments are captured immediately.
			if( isset( $data['token']['object'] ) ) {
				if( $data['token']['object'] === 'bitcoin_receiver' ) $this->payment_action = 'capture';
			}
			/* ------------- SET SOURCE ------------------------ */
			$source = ( $customer->source instanceof WC_Payment_Token_CC ) ? $customer->source->get_token() : $customer->source->id;
			/* ------------- AUTH CHECK LOGIC ------------------ */
			//	ensure that the customer's source will succeed
			//	for the total amount before proceeding.
			//	throw an exception or refund the uncaptured charge.
			$params = array(
				"amount" => $this->wsspg_get_zero_decimal( $order->get_total() ),
				"currency" => $order->get_currency(),
				"customer" => $customer->stripe,
				"source" => $source,
				"description" => esc_html__(
					'checkout_preprocessor',
					'wsspg-woocommerce-stripe-subscription-payment-gateway'
				),
				"capture" => 'false',
				"metadata" => array(
					'order_id' => $order->get_id()
				),
			);
			$charge = Wsspg_API::request( 'charges', $this->key, $params );
			if( ! isset( $charge ) ) throw new Exception();
			$params = array(
				'charge'    => $charge->id,
				'amount'    => $this->wsspg_get_zero_decimal( $order->get_total() ),
				'metadata'  => array(
					'refunded_by'  => esc_html__(
						'checkout_preprocessor',
						'wsspg-woocommerce-stripe-subscription-payment-gateway'
					),
					'reason_given' => esc_html__(
						'Releasing checkout_preprocessor authorization.',
						'wsspg-woocommerce-stripe-subscription-payment-gateway'
					),
				)
			);
			$refund = Wsspg_API::request( 'refunds', $this->key, $params );
			if( ! isset( $refund ) ) throw new Exception();
			/* ------------- SUBSCRIPTIONS LOGIC --------------- */
			//	grab the cart, declare some flags.
			$cart = $woocommerce->cart->get_cart();
			$to_pay = array(
				"total" => $order->get_total(),
				"subscriptions" => 0,
				"other" => 0
			);
			//	loop through the cart.
			//	if the product is a subscription, flag the subscription amount.
			foreach( $cart as $cart_item_key => $cart_item ) {
				$item = $cart_item['data'];
				if( $item->get_type() === 'wsspg_subscription' ) {
					$to_pay['subscriptions'] += $cart_item['line_total'] + $cart_item['line_subtotal_tax'];
				}
			}
			//	if the total amount to pay (excluding subscriptions) is more than zero,
			//	but less than the Stripe minimum,
			//	throw an exception.
			$amount = $this->wsspg_get_zero_decimal( $to_pay['total'] - $to_pay['subscriptions'] );
			if( $amount > 0 ) {
				if( ! $this->wsspg_minimum_order( $amount ) ) {
					throw new Exception( esc_html__(
						'The cart total does not meet Stripe\'s minimum order requirement.',
						'wsspg-woocommerce-stripe-subscription-payment-gateway'
					) );
				}
			}
			//	loop through the cart.
			//	if the product is a subscription, sign the customer up to it.
			//	throw an exception if the subscription comes back null.
			//	flag the subscription amount.
			foreach( $cart as $cart_item_key => $cart_item ) {

				$item = $cart_item['data'];
				if( $item->get_type() === 'wsspg_subscription' ) {
					//	support for subscription tax percent
					$tax_percent = 0;
					$tax = 0;
					$_product = wc_get_product( $cart_item['product_id'] );
					if( $_product->is_taxable() ) {
						$_tax = new WC_Tax();
						$product_tax_class = $_product->get_tax_class();
						$rates = $_tax->get_rates( $product_tax_class );
        				$rate = array_shift( $rates );
						$tax_percent = $rate['rate'];
					}
					//	subscription payment source must be reusable.
					if( isset( $data['token']['object'] ) ) {
						if( $data['token']['object'] === 'bitcoin_receiver' ) {
							throw new Exception( esc_html__(
								'Payment sources for subscription products must be reusable.',
								'wsspg-woocommerce-stripe-subscription-payment-gateway'
							) );
						}
					}
					//	mixed carts are capture only.
					$this->payment_action = 'capture';
					$roles = $customer->roles( $item );
					$metadata = array(
						'product_id' => $item->get_id(),
						'order_id' => $order->get_id(),
					);
					if( isset( $roles ) ) $metadata = array_merge( $roles, $metadata );
					$params = array(
						"customer" => $customer->stripe,
						"source" => $source,
						"plan" => $item->get_plan_id(),
						"quantity" => $cart_item['quantity'],
						"tax_percent" => $tax_percent,
						"metadata" => $metadata,
					);
					$subscription = Wsspg_API::request( 'subscriptions', $this->key, $params );
					if( ! isset( $subscription ) ) throw new Exception();
					$view_sub_url = sprintf(
						'<a href="https://dashboard.stripe.com/subscriptions/%s" target="_blank">%s</a>',
						$subscription->id,
						$subscription->id
					);
					$order->add_order_note( sprintf(
						esc_html__( 'Subscription authorized and captured: %s', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
						$view_sub_url
					) );
					$customer->has_subscribed_to( $subscription );
				}
			}
			/* ------------------------------------------------- */
			if( $amount > 0 ) {
				$params = array(
					"amount" => $amount,
					"currency" => $order->get_currency(),
					"customer" => $customer->stripe,
					"source" => $source,
					"description" => get_post_meta( $order->get_id(), '_wsspg_order_description', true ),
					"capture" => $this->payment_action === 'capture' ? 'true' : 'false',
					"metadata" => array(
						'order_id' => $order->get_id()
					),
				);
				$charge = Wsspg_API::request( 'charges', $this->key, $params );
				if( isset( $charge ) ) {
					$this->wsspg_set_order_charge_id( $order_id, $charge->id );
					$view_charge_url = sprintf(
						'<a href="https://dashboard.stripe.com/payments/%s" target="_blank">%s</a>',
						$charge->id,
						$charge->id
					);
					if( $this->payment_action === 'capture' ) {
						update_post_meta(
							$order_id,
							'_wsspg_order_funds_captured',
							1
						);
						$order->add_order_note( sprintf(
							esc_html__( 'Payment authorized and captured: %s', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
							$view_charge_url
						) );
						$order->payment_complete( $charge->id );
					} else {
						update_post_meta(
							$order_id,
							'_wsspg_order_uncaptured_charge',
							$charge->id
						);
						$order->add_order_note( sprintf(
							esc_html__( 'Payment authorized: %s', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
							$view_charge_url
						) );
					}
					//	dump the cart contents and return success.
					WC()->cart->empty_cart();
					return array( 'result' => 'success', 'redirect' => $this->get_return_url( $order ) );
				}
			} else {
				update_post_meta(
					$order_id,
					'_wsspg_order_funds_captured',
					1
				);
				$order->payment_complete();
				//	dump the cart contents and return success.
				WC()->cart->empty_cart();
				return array( 'result' => 'success', 'redirect' => $this->get_return_url( $order ) );
			}
			//	the payment couldn't be processed.
			return array( 'result' => 'failure' );
		}
	}

	/**
	 * Process refunds.
	 *
	 * @since   1.0.0
	 * @param   int
	 * @param   float
	 * @param   string
	 * @return  bool
	 */
	public function process_refund( $order_id, $amount = null, $reason = null ) {

		$charge_id = get_post_meta( $order_id, '_wsspg_order_charge_id', true );
		$charge = Wsspg_Api::request( "charges/{$charge_id}", $this->key );
		if( isset( $charge  ) ) {
			if( $charge->source->object === 'bitcoin_receiver' ) {
				throw new Exception( esc_html__(
					'It is not possible to refund Bitcoin transactions through this interface.',
					'wsspg-woocommerce-stripe-subscription-payment-gateway'
				) );
			} else {
				$params = array(
					'charge'    => $charge->id,
					'amount'    => $this->wsspg_get_zero_decimal( $amount ),
					'metadata'  => array(
						'refunded_by'  => wp_get_current_user()->user_login,
						'reason_given' => $reason
					)
				);
				$refund = Wsspg_API::request( 'refunds', $this->key, $params );
				if( isset( $refund  ) ) {
					$order = wc_get_order( $order_id );
					$order->add_order_note( sprintf(
						esc_html__( 'Refunded %s%s%s%s: %s', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
						'<strong>',
						(string) get_woocommerce_currency_symbol(),
						(string) $this->wsspg_format_currency_unit( $refund->amount, $refund->currency ),
						'</strong>',
						sprintf(
							'<a href="https://dashboard.stripe.com/logs/%s" target="_blank">%s</a>',
							$refund->request,
							$refund->id
						)
					) );
					return true;
				}
			}
		}
		//	the refund couldn't be processed.
		return false;
	}

	/**
	 * Add a captured flag to an order once funds have been processed.
	 *
	 * @since  1.0.0
	 * @param  int
	 */
	private function wsspg_set_order_funds_captured( $order_id ) {

		update_post_meta( $order_id, '_wsspg_order_funds_captured', true );
	}

	/**
	 * Add the charge id to an order.
	 *
	 * @since  1.0.0
	 * @param  int
	 * @param  int
	 */
	private function wsspg_set_order_charge_id( $order_id, $charge_id ) {

		update_post_meta( $order_id, '_wsspg_order_charge_id', $charge_id );
	}

	/**
	 * Add a payment method token.
	 *
	 * @since  1.0.0
	 */
	public function add_payment_method() {

		$customer = Wsspg_Customer::load( get_current_user_id() );
		if( isset( $customer ) ) {
			if( !isset( $_POST['wsspg-data'] ) ) {
				//	data is missing or null.
				wc_add_notice( esc_html__( 'There was an error processing your request.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), 'error' );
				return array(
					'result'   => 'failure'
				);
			} else {
				$data = json_decode( stripslashes( $_POST['wsspg-data'] ), true );
				if( isset( $data['error'] ) ) {
					//	Stripe returned an error.
					wc_add_notice( print_r( $data['error'], true ), 'error' );
					return array(
						'result'   => 'failure'
					);
				} elseif( isset( $data['token']['id'] ) ) {
					if( ! isset( $customer->stripe ) ) {
						$customer->params = array(
							"email"    => $customer->data->email,
							"metadata" => array(
								"first_name" => $customer->data->first_name,
								"last_name"  => $customer->data->last_name,
							),
						);
						$customer->_object = Wsspg_API::request( 'customers', $this->key, $customer->params );
						if( ! isset( $customer->_object->id ) ) throw new Exception();
						$customer->stripe = $customer->_object->id;
						$customer->save();
					}
					$customer->source = Wsspg_API::request(
						"customers/{$customer->stripe}/sources",
						$this->key,
						array(
							"source" => $data['token']['id']
						)
					);
					if( ! isset( $customer->source ) ) throw new Exception();
					$customer->save_source();
					return array(
						'result'   => 'success',
						'redirect' => wc_get_endpoint_url( 'payment-methods' ),
					);
				}
			}
		}
		//	something went wrong.
		return array(
			'result'   => 'failure'
		);
	}

	/**
	 * Output the admin gateway settings form.
	 *
	 * @since  1.0.0
	 */
	public function admin_options() {

		// echo '<div class="inline error"><p><strong>Put admin notices here...</strong></p></div>';
		parent::admin_options();
	}

	/**
	 * Adds admin notices to the plugin section only.
	 *
	 * @since  1.0.0
	 * @hook   admin_notices
	 */
	public function wsspg_gateway_admin_notices() {

		/*
		$screen = get_current_screen();
		$section = isset( $_GET['section'] ) && $_GET['section'] === WSSPG_PLUGIN_ID ? true : false ;
		if( $section && $screen->id === 'woocommerce_page_wc-settings' ) {
			echo '<div class="notice notice-warning"><p>';
			echo esc_html__( 'Put admin notices here...', 'wsspg-woocommerce-stripe-subscription-payment-gateway' );
			echo '</p></div>';
		}
		*/
	}

	/**
	 * Outputs the checkout page credit card form.
	 *
	 * @since  1.0.0
	 */
	public function form() {

		global $woocommerce;
		$cart = $woocommerce->cart->get_cart();
		foreach( $cart as $cart_item_key => $cart_item ) {
			$item = $cart_item['data'];
			if( $item->get_type() === 'wsspg_subscription' ) {
				$this->bitcoin = false;
				$this->alipay = false;
			}
		}
		if( $this->stripe_checkout_enabled && !is_add_payment_method_page() ) {
			//	output Stripe Checkout
			include( WSSPG_PLUGIN_DIR_PATH.'templates/checkout/wsspg-stripe-checkout.php' );
		} else {
			//	inline credit card form.
			include( WSSPG_PLUGIN_DIR_PATH.'templates/checkout/wsspg-inline-cc-form.php' );
		}
	}

	/**
	 * Builds our payment fields area - including a description, tokenization
	 * fields for logged in users, and the actual payment fields.
	 *
	 * @since  1.0.0
	 */
	public function payment_fields() {

		if( null !== Wsspg::get_api_key( 'publishable' ) ) {
			if( is_checkout() ) {
				$this->tokenization_script();
				if( !empty( $this->description ) ) {
					echo wpautop( wptexturize( $this->description ) );
				}
				$this->saved_payment_methods();
				$this->form();
				if( $this->save_payment_method ) {
					$this->save_payment_method_checkbox();
				}
			} else {
				$this->form();
			}
		} else {
			//	there is no publishable key. we don't want to divulge this on the frontend,
			//	so just continue silently and don't output the payment form.
			//	the form will be submitted blank and the server will pick it up and spit out
			//	some generic error message(s).
		}
	}

	/**
	 * Returns the order total in the smallest currency unit.
	 *
	 * @since   1.0.0
	 * @param   float
	 * @return  int
	 */
	public function wsspg_get_zero_decimal( $total = null ) {

		return Wsspg::get_zero_decimal( $total, $this->currency );
	}

	/**
	 * Formats an amount from the smallest currency unit to the largest.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @param   mixed
	 */
	private function wsspg_format_currency_unit( $amount, $currency ) {

		return Wsspg::format_currency_unit( $amount, $currency );
	}

	/**
	 * Returns true if the order meets Stripe's minimum amount requirement.
	 *
	 * @since   1.0.0
	 * @return  boolean
	 */
	private function wsspg_minimum_order( $amount ) {

		$minimum = 0;
		switch( $this->currency ) {
			case 'USD':
				$minimum = 50;
				break;
			case 'CAD':
				$minimum = 50;
				break;
			case 'GBP':
				$minimum = 30;
				break;
			case 'EUR':
				$minimum = 50;
				break;
			case 'DKK':
				$minimum = 250;
				break;
			case 'NOK':
				$minimum = 300;
				break;
			case 'SEK':
				$minimum = 300;
				break;
			case 'CHF':
				$minimum = 50;
				break;
			case 'AUD':
				$minimum = 50;
				break;
			case 'JPY':
				$minimum = 50;
				break;
			case 'MXN':
				$minimum = 1000;
				break;
			case 'SGD':
				$minimum = 50;
				break;
			case 'HKD':
				$minimum = 400;
				break;
			default:
				break;
		}
		return $amount > $minimum ? true : false;
	}

	/**
	 * Returns the site title.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function wsspg_get_store_name() {

		if( is_multisite() ) {
			global $blog_id;
			$current_blog_details = get_blog_details( $blog_id );
			return $current_blog_details->blogname;
		} else {
			return get_bloginfo( 'name' );
		}
	}

	/**
	 * Return a boolean for capability support.
	 *
	 * @since   1.0.0
	 * @param   string
	 * @return  boolean
	 */
	public function wsspg_supports( $capability = null ) {

		$bool = false;
		switch( $capability ) {
			case 'bitcoin':
				//	only accept bitcoin if the store currency is in USD.
				if( $this->bitcoin && $this->currency === 'USD' ) {
					$bool = true;
				}
				break;
			case 'alipay':
				//	only accept alipay if the store currency is in USD.
				if( $this->alipay && $this->currency === 'USD' ) {
					$bool = true;
				}
				break;
			default:
				break;
		}
		return $bool;
	}

	/**
	 * Process uncaptured funds on order change status: processing.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_payment_gateway_woocommerce_order_status_processing( $order_id ) {

		if( ! get_post_meta( $order_id, '_wsspg_order_funds_captured', true ) ) {
			$uncaptured = get_post_meta( $order_id, '_wsspg_order_uncaptured_charge', true );
			if( isset( $uncaptured ) && ! empty( $uncaptured ) ) {
				$charge = Wsspg_API::request(
					"charges/{$uncaptured}/capture",
					$this->key
				);
				if( isset( $charge ) ) {
					$order = wc_get_order( $order_id );
					$view_charge_url = sprintf(
						'<a href="https://dashboard.stripe.com/payments/%s" target="_blank">%s</a>',
						$charge->id,
						$charge->id
					);
					update_post_meta(
						$order_id,
						'_wsspg_order_funds_captured',
						1
					);
					delete_post_meta(
						$order_id,
						'_wsspg_order_uncaptured_charge'
					);
					$order->add_order_note( sprintf(
						esc_html__( 'Payment captured: %s', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
						$view_charge_url
					) );
					$order->payment_complete( $charge->id );
				}
			}
		}
	}

	/**
	 * Process uncaptured funds on order change status: completed.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_payment_gateway_woocommerce_order_status_completed( $order_id ) {

		if( ! get_post_meta( $order_id, '_wsspg_order_funds_captured', true ) ) {
			$uncaptured = get_post_meta( $order_id, '_wsspg_order_uncaptured_charge', true );
			if( isset( $uncaptured ) && ! empty( $uncaptured ) ) {
				$charge = Wsspg_API::request(
					"charges/{$uncaptured}/capture",
					$this->key
				);
				if( isset( $charge ) ) {
					$order = wc_get_order( $order_id );
					$view_charge_url = sprintf(
						'<a href="https://dashboard.stripe.com/payments/%s" target="_blank">%s</a>',
						$charge->id,
						$charge->id
					);
					update_post_meta(
						$order_id,
						'_wsspg_order_funds_captured',
						1
					);
					delete_post_meta(
						$order_id,
						'_wsspg_order_uncaptured_charge'
					);
					$order->add_order_note( sprintf(
						esc_html__( 'Payment captured: %s', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
						$view_charge_url
					) );
					$order->payment_complete( $charge->id );
				}
			}
		}
	}

	/**
	 * Process order change status: cancelled.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_payment_gateway_woocommerce_order_status_cancelled( $order_id ) {

		//	not currently implemented.
	}

	/**
	 * Process order change status: refunded.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_payment_gateway_woocommerce_order_status_refunded( $order_id ) {

		//	not currently implemented.
	}

	/**
	 * Excludes subscriptions from coupon discounts.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_payment_gateway_woocommerce_coupon_get_discount_amount( $discount, $discounting_amount, $cart_item, $single ) {

		if( $cart_item['data']->get_type === 'wsspg_subscription' ) {
			$discount = 0;
		}
		return $discount;
	}

	/**
	 * Set as default in Stripe.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_payment_gateway_woocommerce_payment_token_set_default( $token_id ) {

		$customer = Wsspg_Customer::load( get_current_user_id() );
		$token = WC_Payment_Tokens::get( $token_id );
		if( WSSPG_PLUGIN_ID === $token->get_gateway_id() ) {
			$params = array(
				"default_source" => $token->get_token(),
			);
			$update = Wsspg_API::request( "customers/{$customer->stripe}", $this->key, $params );
			if( ! isset( $update ) ) throw new Exception();
		}
	}

	/**
	 * Delete a payment source from Stripe.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_payment_gateway_woocommerce_payment_token_deleted( $card_id ) {

		//	not currently implemented.
	}

	/**
	 * Return a user's saved tokens.
	 *
	 * @since  1.0.0
	 */
	public function get_tokens() {

		$this->tokens = array();
		if( is_user_logged_in() && $this->supports( 'tokenization' ) ) {
			$this->tokens = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), $this->id );
		}
		return $this->tokens;
	}

	/**
	 * Return the gateway's checkout icon.
	 *
	 * @since  1.0.0
	 */
	public function get_icon() {

		$ext = '.svg';
		$icon_array = array(
			'visa' => array(
				'url' => WC()->plugin_url().'/assets/images/icons/credit-cards/visa'.$ext,
				'alt' => 'Visa',
				'width' => 24,
				'enabled' => true,
			),
			'mastercard' => array(
				'url' => WC()->plugin_url().'/assets/images/icons/credit-cards/mastercard'.$ext,
				'alt' => 'Mastercard',
				'width' => 24,
				'enabled' => true,
			),
			'amex' => array(
				'url' => WC()->plugin_url().'/assets/images/icons/credit-cards/amex'.$ext,
				'alt' => 'Amex',
				'width' => 24,
				'enabled' => true,
			),
			'discover' => array(
				'url' => WC()->plugin_url().'/assets/images/icons/credit-cards/discover'.$ext,
				'alt' => 'Discover',
				'width' => 24,
				'enabled' => $this->currency === 'USD' ? true : false,
			),
			'diners' => array(
				'url' => WC()->plugin_url().'/assets/images/icons/credit-cards/diners'.$ext,
				'alt' => 'Diners',
				'width' => 24,
				'enabled' => $this->currency === 'USD' ? true : false,
			),
			'bitcoin' => array(
				'url' => WSSPG_PLUGIN_DIR_URL.'/assets/images/icons/bitcoin'.$ext,
				'alt' => 'Bitcoin',
				'width' => 16,
				'enabled' => $this->bitcoin && $this->stripe_checkout_enabled ? true : false,
			),
		);
		$icon = '';
		$icon_count = count( $icon_array );
		foreach( $icon_array as $icon_item ) {
			if( $icon_item['enabled'] ) {
				$icon_src = WC_HTTPS::force_https_url( $icon_item['url'] );
				$icon_alt = $icon_item['alt'];
				$icon_width = $icon_item['width'];
				$icon_style = 'margin:6px 1px 0 0;';
				$icon .= "<img src='{$icon_src}' alt='{$icon_alt}' width='{$icon_width}' style='{$icon_style}' />";
			}
		}
		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}
}
