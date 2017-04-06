<?php
/**
 * Wsspg Payment Gateway Settings
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
 * Wsspg Payment Gateway Settings
 *
 * @since   1.0.0
 * @return  array
 */
return array(
	
	/**
	 * Is the gateway enabled.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'enabled' => array(
		'title' => esc_html__( 'Enable/Disable', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'label' => esc_html__( 'Enable&nbsp;', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ).WSSPG_PLUGIN_TITLE,
		'type' => 'checkbox',
		'description' => '',
		'default' => 'no',
	),
	
	/**
	 * Gateway mode: live or test.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'mode' => array(
		'title' => esc_html__( 'Mode', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Run the plugin in test mode or go live.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
		'default' => 'test',
		'options' => array(
			'test' => esc_html__( 'Test', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'live' => esc_html__( 'Live', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Authorize or capture.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'payment_action' => array(
		'title' => esc_html__( 'Payment Action', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Capture funds immediately or authorize payment only.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'capture',
		'desc_tip' => true,
		'options' => array(
			'capture' => esc_html__( 'Capture', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'authorize' => esc_html__( 'Authorize', 'wsspg-woocommerce-stripe-subscription-payment-gateway' )
		),
	),
	
	/**
	 * Enabled saved payment methods for registered users.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'save_payment_method' => array(
		'title' => esc_html__( 'Save Payment Method', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Allow registered customers to save their (tokenized) payment method(s) for future use.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'enabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => esc_html__( 'Enabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Section: API Keys
	 *
	 * @since  1.0.0
	 */
	'api_keys' => array(
		'title' => sprintf( esc_html__( '%sAPI Keys', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<hr><br>' ),
		'type' => 'title',
		'description' => sprintf( esc_html__( 'You can find your API keys on your %sStripe dashboard%s.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<a href="https://dashboard.stripe.com/account/apikeys" target="_blank">', '</a>' ),
	),
	
	/**
	 * Live secret key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'live_secret_key' => array(
		'title' => esc_html__( 'Live Secret Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'Live Secret Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( '', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Live publishable key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'live_publishable_key' => array(
		'title' => esc_html__( 'Live Publishable Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'Live Publishable Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( '', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Test secret key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'test_secret_key' => array(
		'title' => esc_html__( 'Test Secret Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'Test Secret Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( '', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Test publishable key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'test_publishable_key' => array(
		'title' => esc_html__( 'Test Publishable Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'Test Publishable Key', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( '', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Section: Subscriptions
	 *
	 * @since  1.0.0
	 */
	'subscriptions' => array(
		'title' => sprintf( esc_html__( '%sSubscriptions', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<hr><br>' ),
		'type' => 'title',
		'description' => esc_html__( 'Wsspg defines a new product type that connects your store to Stripe\'s Subscriptions API.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
	),
	
	/**
	 * Are subscriptions enabled ?
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'subscriptions_enabled' => array(
		'title' => esc_html__( 'Enable/Disable', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'label' => esc_html__( 'Enable Subscriptions', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'checkbox',
		'description' => '',
		'default' => 'no',
	),
	
	/**
	 * Enable guest subscriptions.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'guest_subscriptions' => array(
		'title' => esc_html__( 'Guest Subscriptions', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Allow un-registered customers to view and/or purchase subscription products.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'read_only' => esc_html__( 'View Subscriptions Only', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'enabled' => esc_html__( 'View and Purchase Subscriptions', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Section: My Account
	 *
	 * @since  1.0.0
	 */
	'myaccount' => array(
		'title' => sprintf( esc_html__( '%sMy Account', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<hr><br>' ),
		'type' => 'title',
		'description' => esc_html__( 'Allow registered customers to view/cancel their purchased subscriptions.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
	),
	
	/**
	 * Enable guest subscriptions.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'myaccount_subscriptions' => array(
		'title' => esc_html__( 'Enable/Disable', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Allow registered customers to view/cancel their subscriptions.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'read_only' => esc_html__( 'View Subscriptions Only', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'enabled' => esc_html__( 'View and Cancel Subscriptions', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Payment method title (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'myaccount_subscriptions_endpoint' => array(
		'title' => esc_html__( 'Endpoint', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'URL suffix to handle My Account actions. Must be unique and contain no special characters.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( 'wsspg-custom-endpoint', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Payment method title (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'myaccount_subscriptions_title' => array(
		'title' => esc_html__( 'Endpoint Title', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'The endpoint title. ', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( 'Subscriptions', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Section: Customize
	 *
	 * @since  1.0.0
	 */
	'customize' => array(
		'title' => sprintf( esc_html__( '%sCustomize', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<hr><br>' ),
		'type' => 'title',
		'description' => sprintf( esc_html__( 'Change the plugin\'s appearance.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<a href="/docs" target="_blank">', '</a>' ),
	),
	
	/**
	 * Payment method title (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'title' => array(
		'title' => esc_html__( 'Title', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'The payment method title.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => WSSPG_PLUGIN_TITLE,
		'desc_tip' => true,
	),
	
	/**
	 * A description of the payment method (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'description' => array(
		'title' => esc_html__( 'Description', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'A description of the payment method which appears on the checkout.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => '',
		'desc_tip' => true,
	),
	
	/**
	 * Overrides the text displayed in the #place_order button on the checkout page (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'order_button_text' => array(
		'title' => esc_html__( 'Place Order Button', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'Text displayed on the checkout place order button.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( 'Place Order', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Section: Stripe Checkout
	 *
	 * @since  1.0.0
	 */
	'stripe_checkout' => array(
		'title' => sprintf( esc_html__( '%sStripe Checkout', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<hr><br>' ),
		'type' => 'title',
		'description' => sprintf( esc_html__( '%sStripe Checkout%s is an embeddable payment form that, if enabled, replaces the inline credit card form on the checkout page.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<a href="https://stripe.com/checkout" target="_blank">', '</a>' ),
	),
	
	/**
	 * Enable/disable Stripe Checkout.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'stripe_checkout_enabled' => array(
		'title' => esc_html__( 'Enable/Disable', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Enable or disable the Stripe Checkout embedded form.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
		'default' => 'disabled',
		'options' => array(
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'enabled' => esc_html__( 'Enabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Gateway mode: live or test.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'stripe_checkout_remember_me' => array(
		'title' => esc_html__( 'Remember Me', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Specify whether to include the option to "Remember Me" for future purchases.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
		'default' => 'enabled',
		'options' => array(
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'enabled' => esc_html__( 'Enabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * The label of the payment button in the Checkout form (e.g. Subscribe, Pay {{amount}}, etc.).
	 * If you include {{amount}} in the label value, it will be replaced by a localized version of data-amount. 
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'stripe_checkout_button' => array(
		'title' => esc_html__( 'Pay Button', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'description' => esc_html__( 'Displayed on the pop-up modal submit button.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => esc_html__( 'Pay {{amount}}', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'desc_tip' => true,
	),
	
	/**
	 * Specify auto to display Checkout in the user's preferred language, if available. English will be used by default.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'stripe_checkout_locale' => array(
		'title' => esc_html__( 'Locale', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Specify auto to display Checkout in the user\'s preferred language, if available.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'en',
		'desc_tip' => true,
		'options' => array(
			'auto' => esc_html__( 'Auto', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'zh' => esc_html__( 'Simplified Chinese', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'nl' => esc_html__( 'Dutch', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'en' => esc_html__( 'English', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'fr' => esc_html__( 'French', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'de' => esc_html__( 'German', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'it' => esc_html__( 'Italian', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'ja' => esc_html__( 'Japanese', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'es' => esc_html__( 'Spanish', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * A relative or absolute URL pointing to a square image of your brand or product.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'stripe_checkout_thumbnail' => array(
		'title' => esc_html__( 'Thumbnail', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'description' => esc_html__( 'A relative or absolute URL pointing to a square image of your brand or product. The recommended minimum size is 128x128px. The supported image types are: .gif, .jpeg, and .png.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'text',
		'default' => '',
		'desc_tip' => true,
	),
	
	/**
	 * Enable Bitcoin support.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'bitcoin' => array(
		'title' => esc_html__( 'Bitcoin', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Accept Bitcoin through Stripe Checkout (only available for USD). Note: funds in Bitcoin transactions are captured immediately.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => esc_html__( 'Enabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Automatically refund Bitcoin mispayments after one hour.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'bitcoin_refund_mispayments' => array(
		'title' => esc_html__( 'Refund Mispayments', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Automatically refund Bitcoin mispayments after one hour.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'enabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => esc_html__( 'Enabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Enable Bitcoin support.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'alipay' => array(
		'title' => esc_html__( 'Alipay', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => esc_html__( 'Accept Alipay through Stripe Checkout (only available for USD). Note: funds in Alipay transactions are captured immediately.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => esc_html__( 'Enabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
	/**
	 * Section: Advanced Options
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'advanced_options' => array(
		'title' => sprintf( esc_html__( '%sAdvanced Options', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), '<hr><br>' ),
		'type' => 'title',
		'description' => esc_html__( '', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
	),
	
	/**
	 * Debug
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'debug' => array(
		'title' => esc_html__( 'Debug', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => sprintf( esc_html__( 'Log events at: <code>%s</code>', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ), wc_get_log_file_path( 'wsspg-woocommerce-stripe-subscription-payment-gateway' ) ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'disabled' => esc_html__( 'Disabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'enabled' => esc_html__( 'Enabled', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		),
	),
	
);
