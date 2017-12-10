<?php
/**
 * Wsspg Customer
 *
 * Handles customer data.
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
 * Wsspg Customer Class
 *
 * @since  1.0.0
 * @class  Wsspg_Customer
 */
class Wsspg_Customer {

	/**
	 * WP User ID
	 *
	 * @since  1.0.0
	 * @var    int
	 */
	private $uid;

	/**
	 * WP User object.
	 *
	 * @since  1.0.0
	 * @var    object
	 */
	public $data;

	/**
	 * The customer's Stripe id string.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $stripe;

	/**
	 * Customer params.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	public $params;

	/**
	 * True if the customer is not a registered user.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	public $guest;

	/**
	 * The Stripe customer object.
	 *
	 * @since  1.0.0
	 * @var    object
	 */
	public $_object;

	/**
	 * The customer's payment source identification string.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $source;

	/**
	 * The customer's payment source token.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $token;

	/**
	 * Initialize the class and set its properties.
	 *
	 * Private access prevents creating an instance of the
	 * class using the 'new' syntax. Instead, using the static
	 * method Wsspg_Customer::load(), the object can return
	 * null if the constructor throws an error.
	 *
	 * @since  1.0.0
	 * @param  int
	 */
	private function __construct( $uid = null ) {

		if( ! isset( $uid ) ) {
			throw new Exception();
		} else {
			$this->uid      = $uid;
			$this->guest    = $this->uid === 0 ? true : false;
			$this->stripe   = $this->get_stripe();
			if( $uid !== 0 )
				$this->data = get_userdata( $uid );
		}
	}

	/**
	 * Load an instance of the customer class. Returns null if
	 * the constructor throws an error.
	 *
	 * @since   1.0.0
	 * @param   int
	 * @return  object | null
	 */
	public static function load( $uid = null ) {

		try {
			return new Wsspg_Customer( $uid );
		} catch( Exception $e ) {
			return null;
		}
	}

	/**
	 * Retrieve the customer's Stripe ID from usermeta.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	private function get_stripe() {

		if( $this->guest ) return null;
		$meta = get_user_meta( $this->uid, WSSPG_PLUGIN_MODE.'_stripe_id', true );
		if( empty( $meta ) ) return null;
		if( is_serialized( $meta ) ) {
			$meta = maybe_unserialize( $meta );
		}
		return $meta;
	}

	/**
	 * Save a new customer's Stripe ID.
	 *
	 * @since  1.0.0
	 */
	public function save() {

		if( ! isset( $this->stripe ) || ! isset( $this->uid ) ) return null;
		return update_user_meta( $this->uid, WSSPG_PLUGIN_MODE.'_stripe_id', $this->stripe );
	}

	/**
	 * Returns an array with a comma-delimited string of roles to add to a user.
	 *
	 * @since   1.0.0
	 * @return  array | null
	 */
	public function roles( $item ) {

		if( ! $this->guest ) {
			if( isset( $item->product_custom_fields['_wsspg_subscription_product_user_roles'][0] ) ) {
				return array(
					'roles' => implode(
						',',
						maybe_unserialize(
							$item->product_custom_fields['_wsspg_subscription_product_user_roles'][0]
						)
					)
				);
			}
		}
		return null;
	}

	/**
	 * Adds user roles for successful subscriptions.
	 *
	 * @since   1.0.0
	 * @return  array | null
	 */
	public function add_roles( $subscription ) {

		if( ! $this->guest ) {
			if( isset( $subscription->metadata->roles ) ) {
				$user = new WP_User( $this->uid );
				$roles = explode( ',', $subscription->metadata->roles );
				foreach( $roles as $key => $value ) {
					$user->add_role( $value );
				}
				return $roles;
			}
		}
		return null;
	}

	/**
	 * Customer has successfully subscribed to a plan.
	 * - update user meta.
	 * - maybe add roles.
	 *
	 * @since  1.0.0
	 */
	public function has_subscribed_to( $subscription ) {

		if( ! $this->guest ) {
			$plan_id = array( $subscription->plan->id );
			$user_subs = maybe_unserialize( get_user_meta( $this->uid, WSSPG_PLUGIN_MODE.'_subscriptions', true ) );
			if( isset( $user_subs ) && is_array( $user_subs ) ) {
				update_user_meta( $this->uid, WSSPG_PLUGIN_MODE.'_subscriptions', array_unique( array_merge( $user_subs, $plan_id ) ) );
			} else {
				update_user_meta( $this->uid, WSSPG_PLUGIN_MODE.'_subscriptions', $plan_id );
			}
			if( isset( $subscription->metadata->roles ) ) {
				$user = new WP_User( $this->uid );
				$roles = explode( ',', $subscription->metadata->roles );
				foreach( $roles as $key => $value ) {
					$user->add_role( $value );
				}
				return $roles;
			}
		}
		return null;
	}

	/**
	 * Save a customer's payment method for future use.
	 *
	 * @since  1.0.0
	 */
	public function save_source() {

		$token = new WC_Payment_Token_CC();
		$token->add_meta_data( 'mode', WSSPG_PLUGIN_MODE, true);
		$token->add_meta_data( 'customer', $this->stripe, true);
		$token->set_token( $this->source->id );
		$token->set_gateway_id( WSSPG_PLUGIN_ID );
		$token->set_last4( $this->source->last4 );
		$token->set_expiry_year( $this->source->exp_year );
		$token->set_expiry_month( $this->source->exp_month );
		$token->set_card_type( $this->source->brand );
		$token->set_user_id( $this->uid );
		$token->save();
	}

	/**
	 * The customer has a saved method they wish to use.
	 *
	 * @since   1.0.0
	 * @param   int
	 * @return  null | object
	 */
	public function get_saved_source( $id = null ) {

		$token = WC_Payment_Tokens::get( $id );
		//	return null if the token's user ID does not match our customer's user ID.
		if( (int) $token->get_user_id() !== (int) $this->uid || $token->get_meta( 'mode') !== WSSPG_PLUGIN_MODE ) {
			return null;
		} else {
			//	the token belongs to this customer.
			return $token;
		}
	}
}
