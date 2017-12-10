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

$current_user = wp_get_current_user();
$user_email = $current_user->user_email;
$cart_total = WC()->cart->total;
$attributes = array(
	'key' => esc_attr( Wsspg::get_api_key( 'publishable' ) ),
	'label' => esc_attr( $this->stripe_checkout_button ),
	'email' => esc_attr( $user_email ),
	'amount' => esc_attr( $this->wsspg_get_zero_decimal( $cart_total ) ),
	'name' => esc_attr( $this->wsspg_get_store_name() ),
	'currency' => esc_attr( strtolower( $this->currency ) ),
	'image' => esc_attr( $this->stripe_checkout_thumbnail ),
	'bitcoin' => $this->wsspg_supports('bitcoin') ? 'true' : 'false',
	'locale' => esc_attr( $this->stripe_checkout_locale ),
	'remember-me' => $this->stripe_checkout_remember_me ? 'true' : 'false',
	'refund-mispayments' => $this->bitcoin_refund_mispayments ? 'true' : 'false',
	'alipay' => $this->wsspg_supports('alipay') ? 'true' : 'false',
);

/**
 * Wsspg Stripe Checkout HTML
 *
 * @since  1.0.0
 */
?>

<div id="wsspg-data" <?php foreach( $attributes as $key => $attr ): ?>
<?php echo 'data-'.$key.'="'.$attr.'" '; ?>
<?php endforeach; ?>
></div>
