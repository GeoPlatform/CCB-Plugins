<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://www.imagemattersllc.com/
 * @since      1.0.0
 *
 * @package    Geop_Search
 */

// If uninstall not called from WordPress, then exit. Otherwise, delete the wpdb
// table created for use with the GeoPlatform Maps plugin.
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
else {
	global $wpdb;
	$geopmap_table_name = $wpdb->prefix . 'geop_maps_db';
	$geopmap_sql = "DROP TABLE IF EXISTS $geopmap_table_name;";
	$wpdb->query($geopmap_sql);
	delete_option("geop_maps_db_version");
}
