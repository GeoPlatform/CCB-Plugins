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
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
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
