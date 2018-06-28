<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.imagemattersllc.com/
 * @since             1.0.2
 * @package           Geop_Search
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Search
 * Plugin URI:        www.geoplatform.gov
 * Description:       Browse, search, and filter GeoPlatform service objects.
 * Version:           1.0.2
 * Author:            Image Matters LLC
 * Author URI:        http://www.imagemattersllc.com/
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-search
 * Domain Path:       /languages
 *
 *
 *
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
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEOSEARCH_PLUGIN', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geoplatform-search-activator.php
 */
function activate_geop_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-search-activator.php';
	Geop_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-search-deactivator.php
 */
function deactivate_geop_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-search-deactivator.php';
	Geop_Search_Deactivator::deactivate();
}

// Applies our custom page template to the created page.
add_filter('page_template', 'geopsearch_apply_template');
function geopsearch_apply_template($geopsearch_page_template) {
    if (is_page('geoplatform-search'))
        $geopsearch_page_template = dirname( __FILE__ ) . '/public/partials/geoplatform-search-page-template.php';
    return $geopsearch_page_template;
}

// Sets the parameters of and then creates the search page. It deletes any old
// version of that page before each generation.
function geopsearch_add_interface_page() {
	wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-search' ))), true);
	$geopsearch_interface_post = array(
		'post_title' => 'GeoPlatform Search',
		'post_name' => 'geoplatform-search',
		'post_content' => '',
		'post_status' => 'publish',
		'post_type' => 'page'
	);
	wp_insert_post($geopsearch_interface_post);
}

// Activation hooks, including our interface addition to fire on activation.
register_activation_hook( __FILE__, 'activate_geop_search' );
register_activation_hook( __FILE__, 'geopsearch_add_interface_page' );
register_deactivation_hook( __FILE__, 'deactivate_geop_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-search.php';


function geopsearch_page_enqueues(){
	if (is_page('geoplatform-search')){
		wp_enqueue_script( 'inline_bundle', plugin_dir_url( __FILE__ ) . 'public/js/inline.bundle.js', array(), false, true );
		wp_enqueue_script( 'polyfills_bundle', plugin_dir_url( __FILE__ ) . 'public/js/polyfills.bundle.js', array(), false, true );
		wp_enqueue_script( 'scripts_bundle', plugin_dir_url( __FILE__ ) . 'public/js/scripts.bundle.js', array(), false, true );
		wp_enqueue_script( 'main_bundle', plugin_dir_url( __FILE__ ) . 'public/js/main.bundle.js', array(), false, true );
		wp_enqueue_style( 'styles_bundle', plugin_dir_url( __FILE__ ) . 'public/css/styles.bundle.css', array(), false, 'all' );
	}
}

add_action( 'template_redirect', 'geopsearch_page_enqueues' );




// Hook backbone for shortcode interpretation.
function geopsearch_shortcode_creation($atts){
  // generate a new geopsearch_uuid for each instance
 // $geopsearch_uuid = wp_generate_geopsearch_uuid4();
  // $geopsearch_uuid = uniqid();
  // $geopsearch_uuid = str_replace("-", "", $geopsearch_uuid);
  // $geopsearch_pagingVal = 1;
  // $geopsearch_searchVal = 1;

  // // load the settings - add default values if none exist already
  // $options = get_option('geopsearch_settings', array(
  //   'geopsearch_select_community' => 'any',
  //   'geopsearch_text_title' => 'Community Items',
  //   'geopsearch_select_objtype' => 'any',
  //   'geopsearch_checkbox_show_paging' => 1,
  //   'geopsearch_checkbox_show_search' => 1,
  //   'geopsearch_select_sort' => 'modified',
  //   'geopsearch_select_keyword' => 'any',
  //   'geopsearch_select_perpage' => 10));
	//
  // // populate via shortcode, using settings api values as defaults
  // $a = shortcode_atts(array(
  //   'community' => $options['geopsearch_select_community'],
  //   'title' => $options['geopsearch_text_title'],
  //   'objtype' => $options['geopsearch_select_objtype'],
	// 	'showpaging' => $options['geopsearch_checkbox_show_paging'],
  //   'showsearch' => $options['geopsearch_checkbox_show_search'],
  //   'sort' => $options['geopsearch_select_sort'],
  //   'maxresults' => $options['geopsearch_select_perpage'],
  //   'keyword' => $options['geopsearch_select_keyword'],
  //   'geopsearch_uuid' => $geopsearch_uuid
  // ), $atts);

  // handle true/false values in shortcode.  is stored in settings api as 1 or 0
  // if ($atts !=  null)
  // {
  //   if (array_key_exists('showpaging', $atts) && isset($atts['showpaging'])){
  //     if ($atts['showpaging'] == 'false' || $atts['showpaging'] == '0' || $atts['showpaging'] == 'f')
  //       $geopsearch_pagingVal = 0;
  //   }
	//
  //   if (array_key_exists('showsearch', $atts) && isset($atts['showsearch'])){
  //     if ($atts['showsearch'] == 'false' || $atts['showsearch'] == '0' || $atts['showsearch'] == 'f')
  //       $geopsearch_searchVal = 0;
  //   }
  // }

  ob_start();
  include( plugin_dir_url(__FILE__) . '/public/partials/geoplatform-search-shortcode.php' );  // inject php
	return ob_get_clean();
}

// Adds the shortcode hook to init.
function geopsearch__shortcodes_init()
{
    add_shortcode('geopsearch', 'geopsearch__shortcode_creation');
}
add_action('init', 'geopsearch__shortcodes_init');






/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geop_search() {

	$plugin = new Geop_Search();
	$plugin->run();

}
run_geop_search();
