<?php
/**
 * Wsspg Uninstall
 *
 * Fired when the plugin is uninstalled ( i.e. deleted ).
 *
 * Removes options and/or settings specific to the plugin, or other database values that need to be removed.
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @since      1.0.0
 * @package    Wsspg
 * @author     wsspg <wsspg@mail.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright  (c) 2016 https://github.com/wsspg
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

//	uninstall code goes here...
//	remember to call wp_cache_flush();
