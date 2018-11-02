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
 * @since             1.0.8
 * @package           Geop_Search
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Search
 * Plugin URI:        www.geoplatform.gov
 * Description:       Browse, search, and filter GeoPlatform service objects.
 * Version:           1.0.8
 * Author:            Image Matters LLC: Patrick Neal, Lee Heazel
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
define( 'GEOSEARCH_PLUGIN', '1.0.3' );

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
  ob_start();?>

	<!-- Search bar section. -->
	<br>
	<div class="container-fluid">
	  <div class="row">
  		<form id="geoplatformsearchform">
      	<div class="input-group-slick input-group-slick--lg" style="width: 100%; font-family: Lato,Helvetica,Arial,sans-serif;">
          <span class="glyphicon glyphicon-search"></span>
          <input id="geoplatformsearchfield" type="text" placeholder="Search the GeoPlatform" class="form-control input-lg">
          <button id="geoplatformsearchbutton" type="button" class="btn btn-primary">Search</button>
        </div>
	    </form>
	  </div>
	</div>

	<script>

	// Code section. First jQuery triggers off of form submission (enter button) and
	// navigates to the geoplatform-search page with the search field params.
	jQuery( "#geoplatformsearchform" ).submit(function( event ) {
	  event.preventDefault();
	  window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatformsearchfield').val();
	});

	// Functionally identical to above, triggered by submit button press.
	jQuery( "#geoplatformsearchbutton" ).click(function( event ) {
	  window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatformsearchfield').val();
	});
	</script>

	<?php
	return ob_get_clean();
}

// Adds the shortcode hook to init.
function geopsearch_shortcodes_init()
{
    add_shortcode('geopsearch', 'geopsearch_shortcode_creation');
}
add_action('init', 'geopsearch_shortcodes_init');


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
