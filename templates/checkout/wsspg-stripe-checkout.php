<?php
/**
 * Wsspg Stripe Checkout
 *
 * Custom Checkout.js integration.
 *
 * @since       1.0.0
 * @package     Wsspg
 * @subpackage  Wsspg/public/checkout
 * @author      wsspg <wsspg@mail.com>
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright   (c) 2016 https://github.com/wsspg
 */

if( ! defined( 'ABSPATH' ) ) exit; // exit if accessed directly.

//	we'll need some of the user's info.
$current_user = wp_get_current_user();
$user_email = $current_user->user_email;
$cart_total = WC()->cart->total;

/**
 * Wsspg Stripe Checkout HTML
 * 
 * @since  1.0.0
 */
?>

<div id="wsspg-data"
data-key="<?php echo esc_attr( Wsspg::get_api_key( 'publishable' ) ); ?>"
data-label="<?php echo esc_attr( $this->stripe_checkout_button ); ?>"
data-email="<?php echo esc_attr( $user_email ); ?>"
data-amount="<?php echo esc_attr( $this->wsspg_get_zero_decimal( $cart_total ) ); ?>"
data-name="<?php echo esc_attr( $this->wsspg_get_store_name() ); ?>"
data-currency="<?php echo esc_attr( strtolower( $this->currency ) ); ?>"
data-image="<?php echo esc_attr( $this->stripe_checkout_thumbnail ); ?>"
data-bitcoin="<?php echo $this->wsspg_supports('bitcoin') ? 'true' : 'false'; ?>"
data-locale="<?php echo esc_attr( $this->stripe_checkout_locale ); ?>"
data-remember-me="<?php echo $this->stripe_checkout_remember_me ? 'true' : 'false'; ?>"
data-refund-mispayments="<?php echo $this->bitcoin_refund_mispayments ? 'true' : 'false'; ?>"
data-alipay="<?php echo $this->wsspg_supports('alipay') ? 'true' : 'false'; ?>"
></div>
