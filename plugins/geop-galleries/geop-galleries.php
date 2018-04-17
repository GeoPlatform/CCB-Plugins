<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.geoplatform.gov
 * @since             1.0.0
 * @package           Geop_Galleries
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Galleries Plugin
 * Plugin URI:        www.geoplatform.gov
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Image Matters LLC
 * Author URI:        www.geoplatform.gov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geop-galleries
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
 * This action is documented in includes/class-geop-galleries-activator.php
 */
function activate_geop_galleries() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geop-galleries-activator.php';
	Geop_Galleries_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geop-galleries-deactivator.php
 */
function deactivate_geop_galleries() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geop-galleries-deactivator.php';
	Geop_Galleries_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geop_galleries' );
register_deactivation_hook( __FILE__, 'deactivate_geop_galleries' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geop-galleries.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geop_galleries() {

	$plugin = new Geop_Galleries();
	$plugin->run();

}
run_geop_galleries();
