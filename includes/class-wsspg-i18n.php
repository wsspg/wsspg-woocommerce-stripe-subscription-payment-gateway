<?php
/**
 * Wsspg Internationalization
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
 * Wsspg i18n Class
 *
 * @since  1.0.0
 * @class  Wsspg_i18n
 */
class Wsspg_i18n {
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since  1.0.0
	 */
	public static function load_textdomain() {
		
		load_textdomain( 'wsspg-woocommerce-stripe-subscription-payment-gateway', WP_LANG_DIR . '/wsspg/wsspg-' . get_locale() . '.mo' );
		load_plugin_textdomain(
			'wsspg-woocommerce-stripe-subscription-payment-gateway',
			false,
			'wsspg-woocommerce-stripe-subscription-payment-gateway/i18n/languages/'
		);
	}
}
