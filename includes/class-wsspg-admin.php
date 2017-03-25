<?php
/**
 * Wsspg Admin
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, enqueues the
 * admin-specific styles and scripts, adds plugin action links,
 * plugin meta row links, admin notices, settings pages, tabs.
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
 * Wsspg Admin Class
 *
 * @since  1.0.0
 * @class  Wsspg_Admin
 */
class Wsspg_Admin {
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {}
	
	/**
	 * Plugin action links.
	 *
	 * @since   1.0.0
	 * @param   array
	 * @return  array
	 */
	public function wsspg_plugin_action_links( $links ) {
		
		$wsspg_action_links_before = array(
			'settings' => array(
				'url' => esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section='.WSSPG_PLUGIN_ID ) ),
				'title' => esc_attr( __( 'View Wsspg Settings', 'wsspg' ) ),
				'text' => __( 'Settings', 'wsspg' ),
			),
		);
		$wsspg_action_links_after = array(
			'edit' => array(
				'url' => esc_url( admin_url( 'plugin-editor.php?file='.WSSPG_PLUGIN_BASENAME.'&plugin='.WSSPG_PLUGIN_BASENAME.'' ) ),
				'title' => esc_attr( __( 'Edit Wsspg', 'wsspg' ) ),
				'text' => __( 'Edit', 'wsspg' ),
			),
		);
		$before_links = array();
		foreach( $wsspg_action_links_before as $key => $value ) {
			$before_links[ $key ] = "<a href='".$value['url']."' title='".$value['title']."'>".$value['text']."</a>";
		}
		$after_links = array();
		foreach( $wsspg_action_links_after as $key => $value ) {
			$after_links[ $key ] = "<a href='".$value['url']."' title='".$value['title']."'>".$value['text']."</a>";
		}
		return array_merge( $before_links, $links, $after_links );
	}
	
	/**
	 * Plugin meta links.
	 *
	 * @since   1.0.0
	 * @param   array
	 * @param   mixed
	 * @return  array
	 */
	public function wsspg_plugin_row_meta( $links, $file ) {
		
		if( $file === WSSPG_PLUGIN_BASENAME ) {
			$wsspg_meta_links = array(
				'docs' => array(
					'url' => esc_url( '/docs' ),
					'title' => esc_attr( __( 'View Wsspg Documentation', 'wsspg' ) ),
					'text' => __( 'Docs', 'wsspg' ),
					'enabled' => false,
				),
				'api' => array(
					'url' => esc_url( 'https://dashboard.stripe.com/account/apikeys' ),
					'title' => esc_attr( __( 'Get Stripe API Keys', 'wsspg' ) ),
					'text' => __( 'Get Stripe API Keys', 'wsspg' ),
					'enabled' => true,
				),
				'support' => array(
					'url' => esc_url( '/support' ),
					'title' => esc_attr( __( 'Get support for Wsspg', 'wsspg' ) ),
					'text' => __( 'Support', 'wsspg' ),
					'enabled' => false,
				),
			);
			$row_meta = array();
			foreach( $wsspg_meta_links as $key => $value ) {
				if( $value['enabled'] ) {
					$row_meta[ $key ] = "<a href='".$value['url']."' title='".$value['title']."' target='_blank'>".$value['text']."</a>";
				}
			}
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	
	/**
	 * Adds the gateway class to array of payment methods.
	 *
	 * @since   1.0.0
	 * @param   array
	 * @return  array
	 */
	public function wsspg_woocommerce_payment_gateways( $methods ) {
		
		$methods[] = 'Wsspg_Payment_Gateway';
		return $methods;
	}
	
	/**
	 * Add submenu page to 'WooCommerce'.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_admin_admin_menu() {
		
		$page_hook = add_submenu_page(
			'woocommerce',
			__( 'Manage Stripe Subscriptions', 'wsspg' ), 
			__( 'Stripe Subscriptions', 'wsspg' ),
			'manage_woocommerce',
			'wsspg',
			__CLASS__ . '::wsspg_admin_manage_stripe_subscriptions'
		);
	}
	
	/**
	 * Add css / js to admin pages.
	 *
	 * @since  1.0.0
	 */
	public function wsspg_admin_enqueue_scripts() {
		
		$min = WSSPG_PLUGIN_DEBUG ? '' : '.min';
		wp_enqueue_style(
			'wsspg-admin-style',
			WSSPG_PLUGIN_DIR_URL . 'assets/css/admin.css'
		);
		wp_enqueue_script(
			'wsspg-admin',
			WSSPG_PLUGIN_DIR_URL . 'assets/js/wsspg-admin' . $min . '.js',
			array( 'jquery'  ),
			WSSPG_PLUGIN_VERSION,
			true
		);
	}
	
	/**
	 * Filter product price html.
	 *
	 * @since   1.0.0
	 * @param   string
	 * @param   object
	 * @return  string
	 */
	public function wsspg_woocommerce_get_price_html( $price, $product ) {
		
		if( !empty( $product ) && $product->is_type( 'wsspg_subscription' ) ) {
			$meta = get_post_meta( $product->post->ID );
			if( ! isset( $meta['_wsspg_stripe_plan_interval'][0] ) ) {
				return $price;
			} elseif( ! isset( $meta['_wsspg_stripe_plan_interval_count'][0] ) ) {
				return sprintf(
					esc_html( __( '%s per %s', 'wsspg' ) ),
					$price,
					$meta['_wsspg_stripe_plan_interval'][0]
				);
			} else {
				if( $meta['_wsspg_stripe_plan_interval_count'][0] > 1 ) {
					return sprintf(
						esc_html( __( '%s per %.0f %ss', 'wsspg' ) ),
						$price,
						$meta['_wsspg_stripe_plan_interval_count'][0],
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				} else {
					return sprintf(
						esc_html( __( '%s per %s', 'wsspg' ) ),
						$price,
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				}
			}
		}
		return $price;
	}
	
	/**
	 * Filter cart item price html.
	 *
	 * @since   1.0.0
	 * @param   string
	 * @param   object
	 * @param   string
	 * @return  string
	 */
	public function wsspg_woocommerce_cart_item_price( $price, $item, $item_key ) {
		
		$item = $item['data'];
		if( ! empty( $item ) && $item->is_type( 'wsspg_subscription' ) ) {
			$meta = get_post_meta( $item->post->ID );
			if( ! isset( $meta['_wsspg_stripe_plan_interval'][0] ) ) {
				return $price;
			} elseif( ! isset( $meta['_wsspg_stripe_plan_interval_count'][0] ) ) {
				return sprintf(
					esc_html( __( '%s per %s', 'wsspg' ) ),
					$price,
					$meta['_wsspg_stripe_plan_interval'][0]
				);
			} else {
				if( $meta['_wsspg_stripe_plan_interval_count'][0] > 1 ) {
					return sprintf(
						esc_html( __( '%s per %.0f %ss', 'wsspg' ) ),
						$price,
						$meta['_wsspg_stripe_plan_interval_count'][0],
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				} else {
					return sprintf(
						esc_html( __( '%s per %s', 'wsspg' ) ),
						$price,
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				}
			}
		}
		return $price;
	}
	
	/**
	 * Filter checkout cart item line subtotal html.
	 *
	 * @since   1.0.1
	 * @param   string
	 * @param   object
	 * @param   string
	 * @return  string
	 */
	public function wsspg_woocommerce_cart_item_subtotal( $price, $item, $item_key ) {
		
		$item = $item['data'];
		if( ! empty( $item ) && $item->is_type( 'wsspg_subscription' ) ) {
			$meta = get_post_meta( $item->post->ID );
			if( ! isset( $meta['_wsspg_stripe_plan_interval'][0] ) ) {
				return $price;
			} elseif( ! isset( $meta['_wsspg_stripe_plan_interval_count'][0] ) ) {
				return sprintf(
					esc_html( __( '%s per %s', 'wsspg' ) ),
					$price,
					$meta['_wsspg_stripe_plan_interval'][0]
				);
			} else {
				if( $meta['_wsspg_stripe_plan_interval_count'][0] > 1 ) {
					return sprintf(
						esc_html( __( '%s per %.0f %ss', 'wsspg' ) ),
						$price,
						$meta['_wsspg_stripe_plan_interval_count'][0],
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				} else {
					return sprintf(
						esc_html( __( '%s per %s', 'wsspg' ) ),
						$price,
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				}
			}
		}
		return $price;
	}
	
	/**
	 * Filter order details line subtotal html.
	 *
	 * @since   1.0.1
	 * @param   string
	 * @param   object
	 * @return  string
	 */
	public function wsspg_woocommerce_order_formatted_line_subtotal( $subtotal, $item ) {
		$_product_id = array_shift( $item['item_meta']['_product_id'] );
		$_product = wc_get_product( $_product_id );
		$item = $_product;
		if( ! empty( $item ) && $item->is_type( 'wsspg_subscription' ) ) {
			$meta = get_post_meta( $item->post->ID );
			if( ! isset( $meta['_wsspg_stripe_plan_interval'][0] ) ) {
				return $subtotal;
			} elseif( ! isset( $meta['_wsspg_stripe_plan_interval_count'][0] ) ) {
				return sprintf(
					esc_html( __( '%s per %s', 'wsspg' ) ),
					$subtotal,
					$meta['_wsspg_stripe_plan_interval'][0]
				);
			} else {
				if( $meta['_wsspg_stripe_plan_interval_count'][0] > 1 ) {
					return sprintf(
						esc_html( __( '%s per %.0f %ss', 'wsspg' ) ),
						$subtotal,
						$meta['_wsspg_stripe_plan_interval_count'][0],
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				} else {
					return sprintf(
						esc_html( __( '%s per %s', 'wsspg' ) ),
						$subtotal,
						$meta['_wsspg_stripe_plan_interval'][0]
					);
				}
			}
		}
		return $subtotal;
	}
	
	/**
	 * Outputs a sortable list table of subscriptions.
	 *
	 * @since  1.0.0
	 */
	public static function wsspg_admin_manage_stripe_subscriptions() {
		
		if( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		if( ! class_exists( 'Wsspg_Subscription_List_Table' ) ) {
			require_once( WSSPG_PLUGIN_DIR_PATH . 'includes/class-wsspg-subscription-list-table.php' );
		}
		$search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : null;
		$search = ( isset( $search ) && ! empty( $search ) ) ? "&nbsp;[ {$search} ]" : '';
		?>
		<div class="wrap">
		<h2><?php echo sprintf(
			__( 'Stripe Subscriptions%s', 'wsspg' ),
			$search
		); ?></h2>
		<?php $sslt = new Wsspg_Subscription_List_Table(); ?>
		<?php $sslt->prepare_items(); ?>
		<form method="post">
		<?php wp_nonce_field( 'my-action' ); ?>
		<?php $sslt->search_box( 'search', 'wsspg-list-table' ); ?>
		<?php $sslt->display(); ?>
		</form>
		</div>
		<?php
	}	
}
