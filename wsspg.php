<?php
/**
 * Wsspg
 *
 * @package    Wsspg
 * @author     wsspg <wsspg@mail.com>
 * @version    1.0.6
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright  (c) 2016 https://github.com/wsspg
 *
 * @wordpress-plugin
 * Plugin Name:        WooCommerce Stripe Subscription Payment Gateway
 * Plugin URI:         https://github.com/wsspg/wsspg-woocommerce-stripe-subscription-payment-gateway
 * Version:            1.0.6
 * Author:             Wsspg
 * Author URI:         https://github.com/wsspg
 * Description:        Accept <strong>Credit Cards</strong>, <strong>Bitcoin</strong>, <strong>Alipay</strong>, and connect your <strong>WooCommerce</strong> store to <strong>Stripe</strong>'s Subscription API.
 * Tags:               wsspg, stripe, subscription, subscriptions, woocommerce, pci, dss,
 * Text Domain:        wsspg-woocommerce-stripe-subscription-payment-gateway
 * Domain Path:        /i18n/languages
 * Requires at least:  4.5.3
 * Tested up to:       4.9.1
 * License:            GNU General Public License, version 3 (GPL-3.0)
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Copyright (c) 2016 https://github.com/wsspg
 *
 * Wsspg is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Wsspg is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Wsspg. If not, see <https://www.gnu.org/licenses/gpl-3.0.txt>.
 */

if( ! defined( 'ABSPATH' ) ) exit; // exit if accessed directly.

/**
 * Activate the plugin.
 *
 * @since  1.0.0
 * @hook   register_activation_hook
 */
function activate_wsspg() {

	require_once plugin_dir_path( __FILE__ ).'includes/class-wsspg-activator.php';
	Wsspg_Activator::activate();
}

/**
 * Deactivate the plugin.
 *
 * @since  1.0.0
 * @hook   register_deactivation_hook
 */
function deactivate_wsspg() {

	require_once plugin_dir_path( __FILE__ ).'includes/class-wsspg-deactivator.php';
	Wsspg_Deactivator::deactivate();
}

/**
 * Localize the plugin.
 *
 * @since  1.0.0
 * @hook   plugins_loaded
 */
function localize_wsspg() {

	require_once plugin_dir_path( __FILE__ ).'includes/class-wsspg-i18n.php';
	Wsspg_i18n::load_textdomain();
}

/**
 * Run the plugin.
 *
 * @since  1.0.0
 */
function run_wsspg() {

	require_once plugin_dir_path( __FILE__ ).'includes/class-wsspg.php';
	$plugin = new Wsspg();
	$plugin->run();
}

/**
 * The plugin failed pre-flight checks.
 *
 * @since  1.0.0
 * @hook   admin_init
 */
function fail_wsspg() {

	deactivate_plugins( plugin_basename( __FILE__ ) );
	if( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
	add_action( 'admin_notices', 'wsspg_failed' );
}

/**
 * Let the user know that the plugin failed.
 *
 * @since  1.0.0
 * @hook   admin_notices
 */
function wsspg_failed() {

	echo sprintf(
		esc_html__( '%sOops:%s WooCommerce Stripe Subscription Payment Gateway encountered an error.%s', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
		'<div class="notice notice-error is-dismissible"><p><strong>','</strong>','</p></div>'
	);
}

/**
 * Initiate the plugin.
 *
 * Do pre-flight checks, define constants, setup the plugin environment, then run the plugin.
 *
 * @since  1.0.0
 * @hook   init
 */
function init_wsspg() {

	if( ! class_exists( 'WC_Payment_Gateway' ) ) {
		add_action( 'admin_init', 'fail_wsspg' );
	} else {
		$_version   = '1.0.5';
		$_id        = 'wsspg';
		$_title     = 'Stripe (wsspg)';
		$_desc      = sprintf(
			esc_html__( 'Accept %sCredit Cards%s, %sBitcoin%s, %sAlipay%s, and connect your %sWooCommerce%s store to %sStripe%s\'s Subscription API.', 'wsspg-woocommerce-stripe-subscription-payment-gateway' ),
			'<strong>','</strong>',
			'<strong>','</strong>',
			'<strong>','</strong>',
			'<strong>','</strong>',
			'<strong>','</strong>'
		);
		$_plugin    = __FILE__;
		$_dir       = plugin_dir_path( $_plugin );
		$_url       = plugin_dir_url( $_plugin );
		$_base      = plugin_basename( $_plugin );
		$_settings  = get_option( "woocommerce_{$_id}_settings" );
		$_mode      = $_settings['mode'] === 'test' || ! is_ssl() ? 'wsspg_test' : 'wsspg_live';
		$_debug     = $_settings['debug'] === 'enabled' ? true : false;
		$_dev       = SCRIPT_DEBUG;
		$_api       = 'https://api.stripe.com/v1/';
		defined( 'WSSPG_PLUGIN_VERSION' )    or define( 'WSSPG_PLUGIN_VERSION',    $_version );
		defined( 'WSSPG_PLUGIN_ID' )         or define( 'WSSPG_PLUGIN_ID',         $_id );
		defined( 'WSSPG_PLUGIN_TITLE' )      or define( 'WSSPG_PLUGIN_TITLE',      $_title );
		defined( 'WSSPG_PLUGIN_DESC' )       or define( 'WSSPG_PLUGIN_DESC',       $_desc );
		defined( 'WSSPG_PLUGIN_FILE' )       or define( 'WSSPG_PLUGIN_FILE',       $_plugin );
		defined( 'WSSPG_PLUGIN_DIR_PATH' )   or define( 'WSSPG_PLUGIN_DIR_PATH',   $_dir );
		defined( 'WSSPG_PLUGIN_DIR_URL' )    or define( 'WSSPG_PLUGIN_DIR_URL',    $_url );
		defined( 'WSSPG_PLUGIN_BASENAME' )   or define( 'WSSPG_PLUGIN_BASENAME',   $_base );
		defined( 'WSSPG_PLUGIN_MODE' )       or define( 'WSSPG_PLUGIN_MODE',       $_mode );
		defined( 'WSSPG_PLUGIN_DEBUG' )      or define( 'WSSPG_PLUGIN_DEBUG',      $_debug );
		defined( 'WSSPG_PLUGIN_DEV' )        or define( 'WSSPG_PLUGIN_DEV',        $_dev );
		defined( 'WSSPG_PLUGIN_API' )        or define( 'WSSPG_PLUGIN_API',        $_api );
		if( current_user_can( 'activate_plugins' ) ) {
			register_activation_hook( $_plugin, 'activate_wsspg' );
			register_deactivation_hook( $_plugin, 'deactivate_wsspg' );
		}
		run_wsspg();
	}
}

/**
 * Apply localization once everything has been loaded.
 *
 * @since  1.0.0
 */
add_action( 'plugins_loaded', 'localize_wsspg' );

/**
 * Run on init hook.
 *
 * @since  1.0.0
 */
add_action( 'init', 'init_wsspg' );
