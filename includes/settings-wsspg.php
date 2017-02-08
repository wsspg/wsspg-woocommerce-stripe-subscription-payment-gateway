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
		'title' => __( 'Enable/Disable', 'wsspg' ),
		'label' => __( 'Enable&nbsp;', 'wsspg' ).WSSPG_PLUGIN_TITLE,
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
		'title' => __( 'Mode', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Run the plugin in test mode or go live.', 'wsspg' ),
		'desc_tip' => true,
		'default' => 'test',
		'options' => array(
			'test' => __( 'Test', 'wsspg' ),
			'live' => __( 'Live', 'wsspg' ),
		),
	),
	
	/**
	 * Authorize or capture.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'payment_action' => array(
		'title' => __( 'Payment Action', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Capture funds immediately or authorize payment only.', 'wsspg' ),
		'default' => 'capture',
		'desc_tip' => true,
		'options' => array(
			'capture' => __( 'Capture', 'wsspg' ),
			'authorize' => __( 'Authorize', 'wsspg' )
		),
	),
	
	/**
	 * Enabled saved payment methods for registered users.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'save_payment_method' => array(
		'title' => __( 'Save Payment Method', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Allow registered customers to save their (tokenized) payment method(s) for future use.', 'wsspg' ),
		'default' => 'enabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => __( 'Enabled', 'wsspg' ),
			'disabled' => __( 'Disabled', 'wsspg' ),
		),
	),
	
	/**
	 * Section: API Keys
	 *
	 * @since  1.0.0
	 */
	'api_keys' => array(
		'title' => sprintf( __( '%sAPI Keys', 'wsspg' ), '<hr><br>' ),
		'type' => 'title',
		'description' => sprintf( __( 'You can find your API keys on your %sStripe dashboard%s.', 'wsspg' ), '<a href="https://dashboard.stripe.com/account/apikeys" target="_blank">', '</a>' ),
	),
	
	/**
	 * Live secret key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'live_secret_key' => array(
		'title' => __( 'Live Secret Key', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'Live Secret Key', 'wsspg' ),
		'default' => __( '', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Live publishable key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'live_publishable_key' => array(
		'title' => __( 'Live Publishable Key', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'Live Publishable Key', 'wsspg' ),
		'default' => __( '', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Test secret key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'test_secret_key' => array(
		'title' => __( 'Test Secret Key', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'Test Secret Key', 'wsspg' ),
		'default' => __( '', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Test publishable key.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'test_publishable_key' => array(
		'title' => __( 'Test Publishable Key', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'Test Publishable Key', 'wsspg' ),
		'default' => __( '', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Section: Subscriptions
	 *
	 * @since  1.0.0
	 */
	'subscriptions' => array(
		'title' => sprintf( __( '%sSubscriptions', 'wsspg' ), '<hr><br>' ),
		'type' => 'title',
		'description' => __( 'Wsspg defines a new product type that connects your store to Stripe\'s Subscriptions API.', 'wsspg' ),
	),
	
	/**
	 * Are subscriptions enabled ?
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'subscriptions_enabled' => array(
		'title' => __( 'Enable/Disable', 'wsspg' ),
		'label' => __( 'Enable Subscriptions', 'wsspg' ),
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
		'title' => __( 'Guest Subscriptions', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Allow un-registered customers to view and/or purchase subscription products.', 'wsspg' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'disabled' => __( 'Disabled', 'wsspg' ),
			'read_only' => __( 'View Subscriptions Only', 'wsspg' ),
			'enabled' => __( 'View and Purchase Subscriptions', 'wsspg' ),
		),
	),
	
	/**
	 * Section: My Account
	 *
	 * @since  1.0.0
	 */
	'myaccount' => array(
		'title' => sprintf( __( '%sMy Account', 'wsspg' ), '<hr><br>' ),
		'type' => 'title',
		'description' => __( 'Allow registered customers to view/cancel their purchased subscriptions.', 'wsspg' ),
	),
	
	/**
	 * Enable guest subscriptions.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'myaccount_subscriptions' => array(
		'title' => __( 'Enable/Disable', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Allow registered customers to view/cancel their subscriptions.', 'wsspg' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'disabled' => __( 'Disabled', 'wsspg' ),
			'read_only' => __( 'View Subscriptions Only', 'wsspg' ),
			'enabled' => __( 'View and Cancel Subscriptions', 'wsspg' ),
		),
	),
	
	/**
	 * Payment method title (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'myaccount_subscriptions_endpoint' => array(
		'title' => __( 'Endpoint', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'URL suffix to handle My Account actions. Must be unique and contain no special characters.', 'wsspg' ),
		'default' => __( 'wsspg-custom-endpoint', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Payment method title (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'myaccount_subscriptions_title' => array(
		'title' => __( 'Endpoint Title', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'The endpoint title. ', 'wsspg' ),
		'default' => __( 'Subscriptions', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Section: Customize
	 *
	 * @since  1.0.0
	 */
	'customize' => array(
		'title' => sprintf( __( '%sCustomize', 'wsspg' ), '<hr><br>' ),
		'type' => 'title',
		'description' => sprintf( __( 'Change the plugin\'s appearance.', 'wsspg' ), '<a href="/docs" target="_blank">', '</a>' ),
	),
	
	/**
	 * Payment method title (frontend).
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'title' => array(
		'title' => __( 'Title', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'The payment method title.', 'wsspg' ),
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
		'title' => __( 'Description', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'A description of the payment method which appears on the checkout.', 'wsspg' ),
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
		'title' => __( 'Place Order Button', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'Text displayed on the checkout place order button.', 'wsspg' ),
		'default' => __( 'Place Order', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Section: Stripe Checkout
	 *
	 * @since  1.0.0
	 */
	'stripe_checkout' => array(
		'title' => sprintf( __( '%sStripe Checkout', 'wsspg' ), '<hr><br>' ),
		'type' => 'title',
		'description' => sprintf( __( '%sStripe Checkout%s is an embeddable payment form that, if enabled, replaces the inline credit card form on the checkout page.', 'wsspg' ), '<a href="https://stripe.com/checkout" target="_blank">', '</a>' ),
	),
	
	/**
	 * Enable/disable Stripe Checkout.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'stripe_checkout_enabled' => array(
		'title' => __( 'Enable/Disable', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Enable or disable the Stripe Checkout embedded form.', 'wsspg' ),
		'desc_tip' => true,
		'default' => 'disabled',
		'options' => array(
			'disabled' => __( 'Disabled', 'wsspg' ),
			'enabled' => __( 'Enabled', 'wsspg' ),
		),
	),
	
	/**
	 * Gateway mode: live or test.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'stripe_checkout_remember_me' => array(
		'title' => __( 'Remember Me', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Specify whether to include the option to "Remember Me" for future purchases.', 'wsspg' ),
		'desc_tip' => true,
		'default' => 'enabled',
		'options' => array(
			'disabled' => __( 'Disabled', 'wsspg' ),
			'enabled' => __( 'Enabled', 'wsspg' ),
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
		'title' => __( 'Pay Button', 'wsspg' ),
		'type' => 'text',
		'description' => __( 'Displayed on the pop-up modal submit button.', 'wsspg' ),
		'default' => __( 'Pay {{amount}}', 'wsspg' ),
		'desc_tip' => true,
	),
	
	/**
	 * Specify auto to display Checkout in the user's preferred language, if available. English will be used by default.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'stripe_checkout_locale' => array(
		'title' => __( 'Locale', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Specify auto to display Checkout in the user\'s preferred language, if available.', 'wsspg' ),
		'default' => 'en',
		'desc_tip' => true,
		'options' => array(
			'auto' => __( 'Auto', 'wsspg' ),
			'zh' => __( 'Simplified Chinese', 'wsspg' ),
			'nl' => __( 'Dutch', 'wsspg' ),
			'en' => __( 'English', 'wsspg' ),
			'fr' => __( 'French', 'wsspg' ),
			'de' => __( 'German', 'wsspg' ),
			'it' => __( 'Italian', 'wsspg' ),
			'ja' => __( 'Japanese', 'wsspg' ),
			'es' => __( 'Spanish', 'wsspg' ),
		),
	),
	
	/**
	 * A relative or absolute URL pointing to a square image of your brand or product.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'stripe_checkout_thumbnail' => array(
		'title' => __( 'Thumbnail', 'wsspg' ),
		'description' => __( 'A relative or absolute URL pointing to a square image of your brand or product. The recommended minimum size is 128x128px. The supported image types are: .gif, .jpeg, and .png.', 'wsspg' ),
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
		'title' => __( 'Bitcoin', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Accept Bitcoin through Stripe Checkout (only available for USD). Note: funds in Bitcoin transactions are captured immediately.', 'wsspg' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => __( 'Enabled', 'wsspg' ),
			'disabled' => __( 'Disabled', 'wsspg' ),
		),
	),
	
	/**
	 * Automatically refund Bitcoin mispayments after one hour.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'bitcoin_refund_mispayments' => array(
		'title' => __( 'Refund Mispayments', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Automatically refund Bitcoin mispayments after one hour.', 'wsspg' ),
		'default' => 'enabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => __( 'Enabled', 'wsspg' ),
			'disabled' => __( 'Disabled', 'wsspg' ),
		),
	),
	
	/**
	 * Enable Bitcoin support.
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'alipay' => array(
		'title' => __( 'Alipay', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => __( 'Accept Alipay through Stripe Checkout (only available for USD). Note: funds in Alipay transactions are captured immediately.', 'wsspg' ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'enabled' => __( 'Enabled', 'wsspg' ),
			'disabled' => __( 'Disabled', 'wsspg' ),
		),
	),
	
	/**
	 * Section: Advanced Options
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	'advanced_options' => array(
		'title' => sprintf( __( '%sAdvanced Options', 'wsspg' ), '<hr><br>' ),
		'type' => 'title',
		'description' => __( '', 'wsspg' ),
	),
	
	/**
	 * Debug
	 *
	 * @since  1.0.0
	 * @var    boolean
	 */
	'debug' => array(
		'title' => __( 'Debug', 'wsspg' ),
		'type' => 'select',
		'class' => 'wc-enhanced-select',
		'description' => sprintf( __( 'Log events at: <code>%s</code>', 'wsspg' ), wc_get_log_file_path( 'wsspg' ) ),
		'default' => 'disabled',
		'desc_tip' => true,
		'options' => array(
			'disabled' => __( 'Disabled', 'wsspg' ),
			'enabled' => __( 'Enabled', 'wsspg' ),
		),
	),
	
);
