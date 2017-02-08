<?php
/**
 * Wsspg Public
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
 * Wsspg Public Class
 *
 * @since  1.0.0
 * @class  Wsspg_Public
 */
class Wsspg_Public {
	
	/**
	 * @since  1.0.0
	 */
	public function __construct() {}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * Enqueues a different set of scripts depending on the checkout method specified.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_enqueue_scripts() {
		
		wp_enqueue_style(
			'wsspg-public-style',
			WSSPG_PLUGIN_DIR_URL . 'assets/css/style.css'
		);
		$settings = Wsspg::get_settings();
		$endpoint = $settings['myaccount_subscriptions_endpoint'];
		$endpoint_css = ".woocommerce-MyAccount-navigation ul li.woocommerce-MyAccount-navigation-link--{$endpoint} a::before {
    content: '\\f01e';
}";
		wp_add_inline_style( 'wsspg-public-style', $endpoint_css );
		//	load scripts on the 'checkout' and 'add payment method' pages.
		if( is_checkout() || is_add_payment_method_page() ) {
			$min = WSSPG_PLUGIN_DEBUG ? '' : '.min';
			//	get checkout method: either 'inline' or 'stripe'
			$checkout_method = Wsspg::checkout_method();
			if( $checkout_method === 'inline' || is_add_payment_method_page() ) {
				//	wc cc form js
				wp_enqueue_script( 'wc-credit-card-form' );
				//	wsspg public js
				wp_enqueue_script(
					'wsspg-inline-cc',
					WSSPG_PLUGIN_DIR_URL . 'assets/js/wsspg-inline-cc' . $min . '.js',
					array( 'jquery', 'stripe', 'wc-credit-card-form' ),
					WSSPG_PLUGIN_VERSION,
					true
				);
				//	stripe js
				wp_enqueue_script(
					'stripe',
					'https://js.stripe.com/v2/',
					array( 'jquery' ),
					WSSPG_PLUGIN_VERSION,
					true
				);
			} elseif( $checkout_method === 'stripe' ) {
				//	wsspg checkout js
				wp_enqueue_script(
					'wsspg-stripe-checkout',
					WSSPG_PLUGIN_DIR_URL . 'assets/js/wsspg-stripe-checkout' . $min . '.js',
					array( 'jquery', 'stripe-checkout' ),
					WSSPG_PLUGIN_VERSION,
					true
				);
				//	stripe checkout js
				wp_enqueue_script(
					'stripe-checkout',
					'https://checkout.stripe.com/checkout.js',
					array( 'jquery' ),
					WSSPG_PLUGIN_VERSION,
					true
				);
			}
		}
	}
}
