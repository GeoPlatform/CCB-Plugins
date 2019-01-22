<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.imagemattersllc.com
 * @since             1.0.0
 * @package           Geoplatform_Item_Detials
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Item Details
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Image Matters LLC
 * Author URI:        https://www.imagemattersllc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geoplatform-item-detials
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geoplatform-item-detials-activator.php
 */
function activate_geoplatform_item_detials() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-item-detials-activator.php';
	Geoplatform_Item_Detials_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-item-detials-deactivator.php
 */
function deactivate_geoplatform_item_detials() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-item-detials-deactivator.php';
	Geoplatform_Item_Detials_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geoplatform_item_detials' );
register_deactivation_hook( __FILE__, 'deactivate_geoplatform_item_detials' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-item-detials.php';





add_action('generate_rewrite_rules', 'geotags_add_rewrite_rules');

function geotags_add_rewrite_rules( $wp_rewrite )
{
  $new_rules = array( 'resources/([a-f\d]{32})/?' => 'index.php?pagename=geoplatform-items&q=' . $wp_rewrite->preg_index(1) );

  // Add the new rewrite rule into the top of the global rules array
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}







// add_action('generate_rewrite_rules', 'geotags_add_rewrite_rules');
//
// function geotags_add_rewrite_rules( $wp_rewrite )
// {
//   $new_rules = array( 'geoplatform-items/(.+)' => 'geoplatform-items/#/?q=' . $wp_rewrite->preg_index(1) );
//
//   // Add the new rewrite rule into the top of the global rules array
//   $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
// }


function myplugin_rewrite_tag() {
	add_rewrite_tag( '%q%', '([^/]+)' );
}
add_action('init', 'myplugin_rewrite_tag', 10, 0);
//
// function myplugin_rewrite_rule() {
// 	add_rewrite_rule('^geoplatform-items/item_id/([^/]*)/?', 'index.php?pagename=geoplatform-items&item_id=$matches[1]', 'top');
// }
// add_action('init', 'myplugin_rewrite_rule', 10, 0);



// function myplugin_rewrite_tag() {
// 	add_rewrite_tag( '%item_id%', '([^&]+)' );
// }
// add_action('init', 'myplugin_rewrite_tag', 10, 0);
//
// function myplugin_rewrite_rule() {
// 	add_rewrite_rule('^geoplatform-search/item_id/([^/]*)/?', 'index.php?pagename=geoplatform-search&item_id=$matches[1]', 'top');
// }
// add_action('init', 'myplugin_rewrite_rule', 10, 0);




// function myplugin_rewrite_tag() {
// 	add_rewrite_tag( '%book-author%', '([^&]+)' );
// }
// add_action('init', 'myplugin_rewrite_tag', 10, 0);
//
// function myplugin_rewrite_rule() {
// 	add_rewrite_rule( '^book/book-author/([^/]*)/?', 'index.php?post_type=book&book-author=$matches[1]','top' );
// }
// add_action('init', 'myplugin_rewrite_rule', 10, 0);


// function myplugin_register_query_vars( $vars ) {
// 	$vars[] = 'item_id';
// 	// $vars[] = 'key2';
// 	return $vars;
// }
// add_filter( 'query_vars', 'myplugin_register_query_vars' );



// localhost/my-test-site/?pagename=geoplatform-items&key1=water










/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geoplatform_item_detials() {

	$plugin = new Geoplatform_Item_Detials();
	$plugin->run();

}
run_geoplatform_item_detials();
