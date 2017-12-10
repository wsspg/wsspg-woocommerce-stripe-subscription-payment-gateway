<?php
/**
 * The core plugin class.
 *
 * Loads the plugin's dependencies and adds action and filter hooks.
 *
 * Also contains some public static methods for event logging and access
 * to stored settings, such as the checkout method, or the API keys.
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
 * Wsspg Class
 *
 * @since  1.0.0
 * @class  Wsspg
 */
class Wsspg {

	/**
	 * The loader that's responsible for maintaining and registering the hooks that power the plugin.
	 *
	 * @since  1.0.0
	 * @var    Wsspg_Loader
	 */
	private $loader;

	/**
	 * Static reference to the gateway settings.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	private static $settings;

	/**
	 * Static reference to the log class.
	 *
	 * @since  1.0.0
	 * @var    WC_Logger
	 */
	private static $log;

	/**
	 * Load the dependencies and set the hooks.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_gateway_hooks();
		$this->define_endpoint_hooks();
		$this->define_subscription_hooks();
	}

	/**
	 * Load the required dependencies for this plugin:
	 *
	 * - Wsspg_Loader                   Action and filter hook organiser.
	 * - Wsspg_i18n                     Internationalization.
	 * - Wsspg_API                      Handles Stripe API transactions.
	 * - Wsspg_Endpoints                Registers endpoints.
	 * - Wsspg_Customer                 Handles all our customer data.
	 * - Wsspg_Gateway                  Defines the Wsspg WooCommerce payment gateway.
	 * - Wsspg_Admin                    Admin-specific functions.
	 * - Wsspg_Public                   Public-specific functions.
	 * - WC_Product_Wsspg_Subscription  Defines a new product type for subscriptions.
	 * - Wsspg_Subscriptions            Handles the subscription process.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-api.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-endpoints.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-customer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-gateway.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-product-wsspg-subscription.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wsspg-subscriptions.php';
		$this->loader = new Wsspg_Loader();
	}

	/**
	 * Register endpoint hooks.
	 *
	 * @since  1.0.0
	 */
	private function define_endpoint_hooks() {

		$plugin_endpoints = new Wsspg_Endpoints();
		$this->loader->add_filter(
			'query_vars',
			$plugin_endpoints,
			'wsspg_endpoints_query_vars',
			0
		);
		$this->loader->add_filter(
			'woocommerce_account_menu_items',
			$plugin_endpoints,
			'wsspg_endpoints_woocommerce_account_menu_items'
		);
		$this->loader->add_filter(
			'woocommerce_account_' . $plugin_endpoints->get_endpoint() . '_endpoint',
			$plugin_endpoints,
			'wsspg_endpoints_woocommerce_account_wsspg_custom_endpoint_endpoint'
		);
		$this->loader->add_filter(
			'the_title',
			$plugin_endpoints,
			'wsspg_endpoints_wsspg_custom_endpoint_title'
		);
		$this->loader->add_filter(
			'woocommerce_saved_payment_methods_list',
			$plugin_endpoints,
			'wsspg_endpoints_woocommerce_saved_payment_methods_list',
			10,
			2
		);
		$this->loader->add_action(
			'woocommerce_payment_token_updated',
			$plugin_endpoints,
			'wsspg_endpoints_woocommerce_payment_token_updated',
			10,
			1
		);
	}

	/**
	 * Register all of the hooks related to the admin area.
	 *
	 * @since  1.0.0
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wsspg_Admin();
		$this->loader->add_filter(
			'plugin_action_links_'.WSSPG_PLUGIN_BASENAME,
			$plugin_admin,
			'wsspg_plugin_action_links'
		);
		$this->loader->add_filter(
			'plugin_row_meta',
			$plugin_admin,
			'wsspg_plugin_row_meta',
			10,
			2
		);
		$this->loader->add_filter(
			'woocommerce_payment_gateways',
			$plugin_admin,
			'wsspg_woocommerce_payment_gateways'
		);
		$this->loader->add_action(
			'admin_menu',
			$plugin_admin,
			'wsspg_admin_admin_menu'
		);
		$this->loader->add_action(
			'admin_enqueue_scripts',
			$plugin_admin,
			'wsspg_admin_enqueue_scripts'
		);
		$this->loader->add_filter(
			'woocommerce_get_price_html',
			$plugin_admin,
			'wsspg_woocommerce_get_price_html',
			100,
			2
		);
		$this->loader->add_filter(
			'woocommerce_cart_item_price',
			$plugin_admin,
			'wsspg_woocommerce_cart_item_price',
			20,
			3
		);
		$this->loader->add_filter(
			'woocommerce_cart_item_subtotal',
			$plugin_admin,
			'wsspg_woocommerce_cart_item_subtotal',
			20,
			3
		);
		$this->loader->add_filter(
			'woocommerce_order_formatted_line_subtotal',
			$plugin_admin,
			'wsspg_woocommerce_order_formatted_line_subtotal',
			20,
			3
		);
	}

	/**
	 * Register all of the hooks related to the gateway.
	 *
	 * @since  1.0.0
	 */
	private function define_gateway_hooks() {

		$plugin_gateway = new Wsspg_Payment_Gateway();
		$this->loader->add_action(
			'woocommerce_order_status_processing',
			$plugin_gateway,
			'wsspg_payment_gateway_woocommerce_order_status_processing'
		);
		$this->loader->add_action(
			'woocommerce_order_status_completed',
			$plugin_gateway,
			'wsspg_payment_gateway_woocommerce_order_status_completed'
		);
		$this->loader->add_action(
			'woocommerce_order_status_cancelled',
			$plugin_gateway,
			'wsspg_payment_gateway_woocommerce_order_status_cancelled'
		);
		$this->loader->add_action(
			'woocommerce_order_status_refunded',
			$plugin_gateway,
			'wsspg_payment_gateway_woocommerce_order_status_refunded'
		);
		$this->loader->add_filter(
			'woocommerce_coupon_get_discount_amount',
			$plugin_gateway,
			'wsspg_payment_gateway_woocommerce_coupon_get_discount_amount',
			10,
			4
		);
		$this->loader->add_action(
			'woocommerce_payment_token_set_default',
			$plugin_gateway,
			'wsspg_payment_gateway_woocommerce_payment_token_set_default'
		);
		$this->loader->add_action(
			'woocommerce_payment_token_deleted',
			$plugin_gateway,
			'wsspg_payment_gateway_woocommerce_payment_token_deleted',
			10,
			2
		);
	}

	/**
	 * Register all of the hooks related to the public area.
	 *
	 * @since  1.0.0
	 */
	private function define_public_hooks() {

		$plugin_public = new Wsspg_Public();
		$this->loader->add_action(
			'wp_enqueue_scripts',
			$plugin_public,
			'wsspg_enqueue_scripts'
		);
	}

	/**
	 * Register all of the hooks related to subscriptions.
	 *
	 * @since  1.0.0
	 */
	private function define_subscription_hooks() {

		$plugin_subscriptions = new Wsspg_Subscriptions();
		$this->loader->add_filter(
			'woocommerce_product_is_visible',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_product_is_visible',
			10,
			2
		);
		$this->loader->add_filter(
			'woocommerce_is_sold_individually',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_is_sold_individually',
			10,
			2
		);
		$this->loader->add_filter(
			'woocommerce_add_to_cart_validation',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_add_to_cart_validation',
			10,
			2
		);
		$this->loader->add_filter(
			'woocommerce_is_purchasable',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_is_purchasable',
			10,
			2
		);
		$this->loader->add_filter(
			'product_type_selector',
			$plugin_subscriptions,
			'wsspg_subscriptions_product_type_selector'
		);
		$this->loader->add_filter(
			'woocommerce_product_data_tabs',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_product_data_tabs'
		);
		$this->loader->add_action(
			'woocommerce_product_data_panels',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_product_data_panels'
		);
		$this->loader->add_action(
			'woocommerce_process_product_meta',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_process_product_meta',
			100
		);
		$this->loader->add_action(
			'save_post',
			$plugin_subscriptions,
			'wsspg_subscriptions_save_post'
		);
		$this->loader->add_action(
			'woocommerce_wsspg_subscription_add_to_cart',
			$plugin_subscriptions,
			'wsspg_subscriptions_woocommerce_wsspg_subscription_add_to_cart',
			30
		);
		$this->loader->add_action(
			'pre_get_posts',
			$plugin_subscriptions,
			'wsspg_subscriptions_pre_get_posts',
			10,
			1
		);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since  1.0.0
	 */
	public function run() {

		$this->loader->run();
	}

	/**
	 * Return the checkout method.
	 *
	 * @since   1.0.0
	 * @return  string | null
	 */
	public static function checkout_method() {

		$settings = self::get_settings();
		if( isset( $settings['stripe_checkout_enabled'] ) ) {
			return $settings['stripe_checkout_enabled'] === 'enabled' ? 'stripe' : 'inline' ;
		}
		return null;
	}

	/**
	 * Are subscriptions enabled ?
	 *
	 * @since   1.0.0
	 * @return  boolean
	 */
	public static function subscriptions_enabled() {

		$settings = self::get_settings();
		if( isset( $settings['subscriptions_enabled'] ) ) {
			if( ! is_user_logged_in() && $settings['guest_subscriptions'] === 'disabled' ) {
				return false;
			}
			return $settings['subscriptions_enabled'] === 'yes' ? true : false ;
		}
		return false;
	}

	/**
	 * Return the appropriate API key.
	 *
	 * @since   1.0.0
	 * @param   string
	 * @return  string | null
	 */
	public static function get_api_key( $switch = null ) {

		$settings = self::get_settings();
		$mode = WSSPG_PLUGIN_MODE === 'wsspg_test' || ! is_ssl() ? 'test' : 'live';
		switch( $switch ) {
			case 'secret':
				return $mode === 'test' ? $settings['test_secret_key'] : $settings['live_secret_key'];
				break;
			case 'publishable':
				return $mode === 'test' ? $settings['test_publishable_key'] : $settings['live_publishable_key'];
				break;
			default:
				break;
		}
		return null;
	}

	/**
	 * Return the plugin settings.
	 *
	 * @since   1.0.0
	 * @return  array
	 */
	public static function get_settings() {

		if( empty( self::$settings ) )
			self::$settings = get_option( "woocommerce_". WSSPG_PLUGIN_ID . "_settings" );
		return self::$settings;
	}

	/**
	 * Static error and event logging.
	 *
	 * Usage: Wsspg::log( $message, $type );
	 *
	 * @since  1.0.0
	 * @param  string
	 */
	public static function log( $message, $type = null  ) {

		$datetime = new DateTime();
		$datetime = $datetime->format( 'Y-m-d H:i:s' );
		$timezone = date_default_timezone_get();
		$message = print_r( $message, true );

		if( defined( 'WSSPG_PLUGIN_DEBUG' ) && WSSPG_PLUGIN_DEBUG ) {
			if( empty( self::$log ) ) {
				self::$log = new WC_Logger();
			}
			self::$log->add( 'wsspg-woocommerce-stripe-subscription-payment-gateway', $message );
			if( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				if( defined( 'WSSPG_PLUGIN_DEV' ) && WSSPG_PLUGIN_DEV ) {
					$type = preg_replace('/\s+/','',$type);
					if( ! isset( $type ) || empty( $type ) || is_null( $type ) ) {
						$type = 'error';
					}
					$filepath = WSSPG_PLUGIN_DIR_PATH."/log/$type.log";
					error_log(
						"[$datetime $timezone]\t$message\n",
						3,
						$filepath
					);
				} else {
					error_log( $message );
				}
			}
		}
	}

	/**
	 * Returns true if $currency is zero decimal currency.
	 *
	 * @since   1.0.5
	 * @param   string
	 * @return  boolean
	 */
	public static function is_zero_decimal( $currency ) {

		$zdcs = array(
			'BIF',
			'CLP',
			'DJF',
			'GNF',
			'JPY',
			'KMF',
			'KRW',
			'MGA',
			'PYG',
			'RWF',
			'VND',
			'VUV',
			'XAF',
			'XOF',
			'XPF',
		);
		return in_array( strtoupper( $currency ), $zdcs ) ? true : false;
	}

	/**
	 * Returns the order total in the smallest currency unit.
	 *
	 * @since   1.0.5
	 * @param   float
	 * @param   string
	 * @return  int
	 */
	public static function get_zero_decimal( $total = null, $currency ) {

		if( isset( $total ) ) {
			if( Wsspg::is_zero_decimal( $currency ) ) {
				$total = absint( $total );
			} else {
				$total = absint( round( $total, 2 ) * 100 );
			}
		}
		return $total;
	}

	/**
	 * Formats an amount from the smallest currency unit to the largest.
	 *
	 * With thanks to uganikov (https://github.com/uganikov)
	 *
	 * @since   1.0.5
	 * @param   float
	 * @param   string
	 * @return  float
	 */
	public static function format_currency_unit( $amount, $currency ) {

		if( ! Wsspg::is_zero_decimal( $currency ) ) {
			$amount = sprintf( '%0.2f', $amount / 100 );
		}
		return $amount;
	}
}
