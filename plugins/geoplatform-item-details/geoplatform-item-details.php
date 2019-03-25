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
 * @since             1.0.1
 * @package           Geoplatform_Item_Details
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Item Details
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       View details on individual resources in a dedicated interface page.
 * Version:           1.0.1
 * Author:            Image Matters LLC
 * Author URI:        https://www.imagemattersllc.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-item-details
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
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEOITEMS_PLUGIN', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geoplatform-item-details-activator.php
 */
function activate_geoplatform_item_details() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-item-details-activator.php';
	Geoplatform_Item_Details_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-item-details-deactivator.php
 */
function deactivate_geoplatform_item_details() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-item-details-deactivator.php';
	Geoplatform_Item_Details_Deactivator::deactivate();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-item-details.php';

// Sets the parameters of and then creates the item details page. It deletes any
// old version of that page before each generation.
function geopitems_add_interface_page() {
	wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-items' ))), true);
	$geopitems_interface_post = array(
		'post_title' => 'GeoPlatform Items',
		'post_name' => 'geoplatform-items',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_content' => '<app-root></app-root>',
	);

	wp_insert_post($geopitems_interface_post);
}

// Activation hooks, including our interface addition to fire on activation.
register_activation_hook( __FILE__, 'activate_geoplatform_item_details' );
register_activation_hook( __FILE__, 'geopitems_add_interface_page' );
register_deactivation_hook( __FILE__, 'deactivate_geoplatform_item_details' );


// Rule rewrites to control the URL of the items page.
function geopitems_add_rewrite_rules( $wp_rewrite )
{
	$geop_items_key = 'resources\/(dataset|datasets|service|services|layer|layers|map|maps|gallery|galleries|community|communities|organization|organizations|contact|contacts|person|persons|people|concept|concepts|conceptscheme|conceptschemes|webapp|webapps|website|websites|topic|topics|application|applications)\/([a-f\d]*)\/?';
	$geop_items_value = 'index.php?pagename=geoplatform-items&q=' . $wp_rewrite->preg_index(1);
  // $geop_items_new_rules = array( 'resources/([a-f\d]{32})/?' => 'index.php?pagename=' . get_theme_mod('headlink_items') . '&q=' . $wp_rewrite->preg_index(1) );
	$geop_items_new_rules = array( $geop_items_key => $geop_items_value );

  // Add the new rewrite rule into the top of the global rules array
  $wp_rewrite->rules = $geop_items_new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'geopitems_add_rewrite_rules');

// Deprecated function.
function myplugin_rewrite_tag() {
	add_rewrite_tag( '%q%', '([^/]+)' );
}
// add_action('init', 'myplugin_rewrite_tag', 10, 0);


// Additional dependency enqueues.
function geopitems_page_enqueues(){
	if (is_page('geoplatform-items')){
		wp_enqueue_script( 'inline_bundle', plugin_dir_url( __FILE__ ) . 'public/js/inline.bundle.js', array(), false, true );
		wp_enqueue_script( 'polyfills_bundle', plugin_dir_url( __FILE__ ) . 'public/js/polyfills.bundle.js', array(), false, true );
		wp_enqueue_script( 'scripts_bundle', plugin_dir_url( __FILE__ ) . 'public/js/scripts.bundle.js', array(), false, true );
		wp_enqueue_script( 'main_bundle', plugin_dir_url( __FILE__ ) . 'public/js/main.bundle.js', array(), false, true );
		wp_enqueue_style( 'styles_bundle', plugin_dir_url( __FILE__ ) . 'public/css/styles.bundle.css', array(), false, 'all' );
	}
}
add_action( 'template_redirect', 'geopitems_page_enqueues' );


// AJAX handling only seems to function properly if both the hooks and PHP
// functions are placed in this file. Instead of producing clutter, the files
// that perform the settings interface add and remove map operations are simply
// included here.
function geopitems_process_refresh() {
	geopitems_add_interface_page();
	wp_die();
}
add_action('wp_ajax_geopitems_refresh', 'geopitems_process_refresh');

// No include necessary, simple rule flush action.
function geopitems_process_flush() {
	flush_rewrite_rules();
	wp_die();
}
add_action('wp_ajax_geopitems_flush', 'geopitems_process_flush');


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geoplatform_item_details() {

	$plugin = new Geoplatform_Item_Details();
	$plugin->run();

}
run_geoplatform_item_details();
