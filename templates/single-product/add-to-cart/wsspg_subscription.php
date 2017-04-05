<?php
/**
 * Wsspg Subscription Add To Cart
 *
 * @since       1.0.0
 * @package     Wsspg
 * @subpackage  Wsspg/templates
 * @author      wsspg <wsspg@mail.com>
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright   (c) 2016 https://github.com/wsspg
 */

?>
<?php if( ! defined( 'ABSPATH' ) ) exit; // exit if accessed directly. ?>
<?php global $product; ?>
<?php if( !$product->is_purchasable() ) return; ?>
<?php $availability = $product->get_availability(); ?>
<?php $availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>'; ?>
<?php echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product ); ?>
<?php if( $product->is_in_stock() ) : ?>
<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<form class="cart" method="post" enctype='multipart/form-data'>
<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
<?php if( !$product->is_sold_individually() ) : ?>
<?php woocommerce_quantity_input( array( 'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ), 'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ), 'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ) ); ?>
<?php endif; ?>
<?php if( version_compare( WC_VERSION, '3.0.0', '<' ) ) : ?>
	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
<?php else: ?>
	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />
<?php endif; ?>
<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
<?php endif; ?>
