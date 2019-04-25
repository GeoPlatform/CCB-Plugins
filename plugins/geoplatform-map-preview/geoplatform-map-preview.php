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
 * @package           Geoplatform_Map_Preview
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Map Preview
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       View preview maps of GeoPlatform Assets
 * Version:           1.0.2
 * Author:            Image Matters LLC
 * Author URI:        https://www.imagemattersllc.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-map-preview
 * Domain Path:       /languages

 * Copyright 2018 Image Matters LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.2 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEOMAPPREVIEW_PLUGIN', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geoplatform-map-preview-activator.php
 */
function activate_geoplatform_map_preview() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-map-preview-activator.php';
	Geoplatform_Map_Preview_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-map-preview-deactivator.php
 */
function deactivate_geoplatform_map_preview() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-map-preview-deactivator.php';
	Geoplatform_Map_Preview_Deactivator::deactivate();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-map-preview.php';

// Sets the parameters of and then creates the page. It deletes any
// old version of that page before each generation.
function geopmappreview_add_interface_page() {
	wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-map-preview' ))), true);
	$geopmappreview_interface_post = array(
		'post_title' => 'GeoPlatform Map Preview',
		'post_name' => 'geoplatform-map-preview',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_content' => '<app-root></app-root>',
	);

	wp_insert_post($geopmappreview_interface_post);
}

// Activation hooks, including our interface addition to fire on activation.
register_activation_hook( __FILE__, 'activate_geoplatform_map_preview' );
register_activation_hook( __FILE__, 'geopmappreview_add_interface_page' );
register_deactivation_hook( __FILE__, 'deactivate_geoplatform_map_preview' );


// Rule rewrites to control the URL of the items page.
function geopmappreview_add_rewrite_rules( $wp_rewrite )
{
	$geop_map_preview_namespaces = 'dataset|datasets|service|services|layer|layers';
	$geop_map_preview_key = 'resources\/(' . $geop_map_preview_namespaces . ')\/([a-f\d]*)\/map\/?';
	$geop_map_preview_value = 'index.php?pagename=geoplatform-map-preview&q=' . $wp_rewrite->preg_index(1);
  	$geop_map_preview_new_rules = array( $geop_map_preview_key => $geop_map_preview_value );

    // Add the new rewrite rule into the top of the global rules array
    $wp_rewrite->rules = $geop_map_preview_new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'geopmappreview_add_rewrite_rules');



// Additional dependency enqueues.
function geopmappreview_page_enqueues(){
	if (is_page('geoplatform-items')){
		wp_enqueue_script( 'inline_bundle', plugin_dir_url( __FILE__ ) . 'public/js/inline.bundle.js', array(), false, true );
		wp_enqueue_script( 'polyfills_bundle', plugin_dir_url( __FILE__ ) . 'public/js/polyfills.bundle.js', array(), false, true );
		wp_enqueue_script( 'scripts_bundle', plugin_dir_url( __FILE__ ) . 'public/js/scripts.bundle.js', array(), false, true );
		wp_enqueue_script( 'main_bundle', plugin_dir_url( __FILE__ ) . 'public/js/main.bundle.js', array(), false, true );
		wp_enqueue_style( 'styles_bundle', plugin_dir_url( __FILE__ ) . 'public/css/styles.bundle.css', array(), false, 'all' );
	}
}
add_action( 'template_redirect', 'geopmappreview_page_enqueues' );


// AJAX handling only seems to function properly if both the hooks and PHP
// functions are placed in this file. Instead of producing clutter, the files
// that perform the settings interface add and remove map operations are simply
// included here.
function geopmappreview_process_refresh() {
	geopmappreview_add_interface_page();
	wp_die();
}
add_action('wp_ajax_geopmappreview_refresh', 'geopmappreview_process_refresh');

// No include necessary, simple rule flush action.
function geopmappreview_process_flush() {
	flush_rewrite_rules();
	wp_die();
}
add_action('wp_ajax_geopmappreview_flush', 'geopmappreview_process_flush');


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geoplatform_map_preview() {

	$plugin = new Geoplatform_Map_Preview();
	$plugin->run();

}
run_geoplatform_map_preview();
