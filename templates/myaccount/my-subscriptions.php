<?php
/**
 * Wsspg My Account Subscriptions
 *
 * @since       1.0.0
 * @package     Wsspg
 * @subpackage  Wsspg/templates/myaccount
 * @author      wsspg <wsspg@mail.com>
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright   (c) 2016 https://github.com/wsspg
 */

if( ! defined( 'ABSPATH' ) ) exit; // exit if accessed directly.

/**
 * Wsspg My Account Subscriptions HTML
 *
 * @since  1.0.0
 */
?>
<?php if( ! isset( $subscriptions->data ) || count( $subscriptions->data ) === 0 ): ?>
<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
<?php esc_html_e( 'No active subscriptions.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?>
</div>
<?php else: ?>
<form class="" action="" method="post">
<table class="shop_table shop_table_responsive">
<thead>
<tr>

	<th class="plan-name"><span class="nobr"><?php echo esc_html__( 'Plan', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?></span></th>
	<th class="plan-amount"><span class="nobr"><?php echo esc_html__( 'Amount', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?></span></th>
	<th class="plan-status"><span class="nobr"><?php echo esc_html__( 'Status', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?></span></th>
	<?php if( $this->mode === 'enabled' ): ?>
	<th class="plan-cancel"><span class="nobr"><?php echo esc_html__( 'Cancel', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?></span></th>
	<?php endif; ?>

</tr>
</thead>
<tbody>
<?php $i = 0; ?>
<?php foreach( $subscriptions->data as $subscription ): ?>
<tr class="subscription">

	<td class="plan-name" data-title="<?php echo esc_attr( esc_html__( 'Plan', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ); ?>">
	<?php $quantity = $subscription->quantity > 1 ? ' x <strong>' . $subscription->quantity . '</strong>' : ''; ?>
	<?php if( isset( $subscription->metadata->product_id ) ): ?>
		<?php $product = wc_get_product( $subscription->metadata->product_id ); ?>
		<a href="<?php echo get_permalink( $subscription->metadata->product_id ); ?>">
		<?php if( version_compare( WC_VERSION, '3.0.0', '<' ) ) : ?>
			<?php echo $product->post->post_title; ?>
		<?php else: ?>
			<?php echo $product->get_name(); ?>
		<?php endif; ?>
		</a>
		<?php echo $quantity; ?>
	<?php else: ?>
		<?php echo $subscription->plan->name . $quantity; ?>
	<?php endif; ?>
	</td>
	<td class="plan-amount" data-title="<?php echo esc_attr( esc_html__( 'Amount', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ); ?>">
	<?php if( Wsspg::is_zero_decimal( $subscription->plan->currency ) ): ?>
		<?php echo '<strong>'.get_woocommerce_currency_symbol( strtoupper( $subscription->plan->currency ) ) . sprintf( '%0.0f', ( ( $subscription->plan->amount * $subscription->quantity )  * ( 100 + $subscription->tax_percent  ) / 100 ) ) . '</strong>'; ?>
	<?php else : ?>
		<?php echo '<strong>'.get_woocommerce_currency_symbol( strtoupper( $subscription->plan->currency ) ) . preg_replace( '/.00/', '', sprintf( '%0.2f', ( ( ( $subscription->plan->amount * $subscription->quantity ) / 100 ) * ( 100 + $subscription->tax_percent ) ) / 100 ) ) . '</strong>'; ?>
	<?php endif; ?>
	<?php if( $subscription->plan->interval_count > 1 ): ?>
	<?php echo ' '.esc_html__( 'every', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ).' <strong>'.$subscription->plan->interval_count.' '.$subscription->plan->interval.'s</strong>'; ?>
	<?php else : ?>
	<?php echo ' '.esc_html__( 'per', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ).' <strong>'.$subscription->plan->interval.'</strong>'; ?>
	<?php endif; ?>
	</td>
	<td class="plan-status" data-title="<?php echo esc_attr( esc_html__( 'Status', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ); ?>"><?php echo $subscription->status; ?></td>
	<?php if( $this->mode === 'enabled' ): ?>
	<td class="plan-cancel" data-title="<?php echo esc_attr( esc_html__( 'Cancel', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ); ?>"><a href="#"><input type="submit" class="button" name="wsspg_subscription_id_<?php echo $subscription->id; ?>" value="<?php esc_attr_e( 'CANCEL', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ); ?>" /></a></td>
	<?php endif; ?>

</tr>
<?php $i++; ?>
<?php endforeach; ?>
</tbody>
</table>
<?php if( $this->mode === 'enabled' ) wp_nonce_field( 'wsspg_nonce' ); ?>
<input type="hidden" name="action" value="wsspg_nonce" />
</form>
<?php endif; ?>
