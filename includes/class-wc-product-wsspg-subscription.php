<?php
/**
 * Wsspg Subscription Product
 *
 * Extends the WooCommerce simple product class.
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
 * Wsspg Subscription Product Class
 *
 * @since   1.0.0
 * @class   Wsspg_Subscription_Product
 * @extend  WC_Product_Simple
 */
class WC_Product_Wsspg_Subscription extends WC_Product_Simple {
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 */
	public function __construct( $product ) {
		
		parent::__construct( $product );
		if( version_compare( WC_VERSION, '3.0.0', '<' ) ) $this->product_type = 'wsspg_subscription';
		$this->product_custom_fields = get_post_meta( $this->id );
	}
	
	/**
	 * Declare the product type.
	 *
	 * @since   1.0.4
	 * @return  string
	 */
	public function get_type() {
	
		return 'wsspg_subscription';
	}
	
	/**
	 * Return the Stripe Plan ID string.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_plan_id() {
		
		return $this->product_custom_fields['_wsspg_stripe_plan_id'][0];
	}
}
