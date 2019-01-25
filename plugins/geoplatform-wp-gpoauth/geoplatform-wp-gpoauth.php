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
 * @package           Geoplatform_Wp_Gpoauth
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform WP GPOAuth
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Activation of this plugin creates a page "checktoken," where an authorization token can be obtained.
 * Version:           1.0.0
 * Author:            Image Matters LLC
 * Author URI:        https://www.imagemattersllc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geoplatform-wp-gpoauth
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
 * This action is documented in includes/class-geoplatform-wp-gpoauth-activator.php
 */
function activate_geoplatform_wp_gpoauth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-wp-gpoauth-activator.php';
	Geoplatform_Wp_Gpoauth_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-wp-gpoauth-deactivator.php
 */
function deactivate_geoplatform_wp_gpoauth() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-wp-gpoauth-deactivator.php';
	Geoplatform_Wp_Gpoauth_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geoplatform_wp_gpoauth' );
register_deactivation_hook( __FILE__, 'deactivate_geoplatform_wp_gpoauth' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-wp-gpoauth.php';







// Sets the parameters of and then creates the search page. It deletes any old
// version of that page before each generation.
function geopoauth_add_interface_page() {
	wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'checktoken' ))), true);
	$geopsearch_interface_post = array(
		'post_title' => 'GeoPlatform Token Check',
		'post_name' => 'checktoken',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_content' => 'This page exists to pass authentication tokens off to Item Detail and Registration plugins. Please do not alter, delete, or replace this page. If it is somehow corrupted, please disable and reactivate the GeoPlatform WP GPOAuth plugin.',
	);

	wp_insert_post($geopsearch_interface_post);
}

// Activation hooks, including our interface addition to fire on activation.
register_activation_hook( __FILE__, 'geopoauth_add_interface_page' );



add_action('template_redirect', 'geopoauth_register_authorize');

function geopoauth_register_authorize(){
	if (is_page()){
		global $post;
		if ($post->post_name == 'checktoken'){
			$header = "Authorize: Bearer " . get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token'];
			// $header = "Authorize: Bearer " . $post->post_name;
			header($header);
		}
	}
}

// function getUserAccessToken($userID){
//
// 	$accessToken = NULL;
// 	// if (!empty(get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token']))
// 	// 	$accessToken = get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token'];
//
// 	if (!empty(get_user_meta($userID, 'wp_capabilities', true)['administrator']))
// 		$accessToken = get_user_meta($userID, 'wp_capabilities', true)['administrator'];
//
// 	$accessToken = get_user_meta($userID, 'wp_capabilities', true);
//
// 	return $accessToken;
// }

// add_action( 'rest_api_init', function () {
//     register_rest_route( 'wp-gpoauth/v1', '/get_token', array(
//         'methods'  => 'GET',
//         'callback' => function () {
//             return getUserAccessToken($userID);
//         },
//     ) );
// } );






/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geoplatform_wp_gpoauth() {

	$plugin = new Geoplatform_Wp_Gpoauth();
	$plugin->run();

}
run_geoplatform_wp_gpoauth();
