<?php
/**
 * Wsspg Inline Credit Card Form
 *
 * The frontend markup for the credit card form.
 *
 * @since       1.0.0
 * @package     Wsspg
 * @subpackage  Wsspg/public/checkout
 * @author      wsspg <wsspg@mail.com>
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright   (c) 2016 https://github.com/wsspg
 */

if( ! defined( 'ABSPATH' ) ) exit; // exit if accessed directly.

/**
 * Wsspg Inline Credit Card Form HTML
 * 
 * @since  1.0.0
 */
?>

<fieldset id="wsspg-cc-fieldset" class="wc-credit-card-form wc-payment-form" data-pkey="<?php echo esc_attr( Wsspg::get_api_key( 'publishable' ) ); ?>">
	<p class="form-row form-row-wide validate-required">
		<label for="wsspg-cc-number">
			<?php echo esc_html__( 'Card Number', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?>
			<span class="required">*</span>
		</label>
		<input id="wsspg-cc-number" value="" class="input-text wc-credit-card-form-card-number" type="text" maxlength="20" autocomplete="off" placeholder="&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;" data-stripe="number" />
	</p>
	<p class="form-row form-row-first validate-required">
		<label for="wsspg-cc-exp-month">
			<?php echo esc_html__( 'Expiry (MM)', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?>
			<span class="required">*</span>
		</label>
		<input id="wsspg-cc-exp-month" value="" class="input-text" type="text" autocomplete="off" placeholder="<?php echo esc_attr( esc_html__( 'MM', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ); ?>" data-stripe="exp-month" />
	</p>
	<p class="form-row form-row-last validate-required">
		<label for="wsspg-cc-exp-year">
			<?php echo esc_html__( 'Expiry (YY)', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?>
			<span class="required">*</span>
		</label>
		<input id="wsspg-cc-exp-year" value="" class="input-text" type="text" autocomplete="off" placeholder="<?php echo esc_attr( esc_html__( 'YY', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ); ?>" data-stripe="exp-year" />
	</p>
	<p class="form-row form-row-wide validate-required">
		<label for="wsspg-cc-cvc">
			<?php echo esc_html__( 'CVC', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?>
			<span class="required">*</span>
		</label>
		<input id="wsspg-cc-cvc" value="" class="input-text wc-credit-card-form-card-cvc" type="text" autocomplete="off" placeholder="<?php echo esc_attr( esc_html__( 'CVC', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ); ?>" data-stripe="cvc" />
	</p>
<div class="clear"></div>
</fieldset>

