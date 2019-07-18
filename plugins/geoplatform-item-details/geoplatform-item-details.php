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
 * @since             1.0.2
 * @package           Geoplatform_Item_Details
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Item Details
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       View details on individual resources in a dedicated interface page.
 * Version:           1.0.2
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
 * Start at version 1.0.2 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEOITEMS_PLUGIN', '1.0.2' );

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

// Establishes the global GeoPlatform class if it hasn't been set yet.
function geopitems_establish_globals() {
	$geopoauth_portalUrl = isset($_ENV['wpp_url']) ? $_ENV['wpp_url'] : 'https://www.geoplatform.gov';
	$geopoauth_home = home_url();
	$geopoauth_login = wp_login_url();
	$geopoauth_logout = wp_logout_url();
	$geopoauth_ualUrl = isset($_ENV['ual_url']) ? $_ENV['ual_url'] : 'https://ual.geoplatform.gov';
	$geopoauth_rpmUrl = isset($_ENV['rpm_url']) ? $_ENV['rpm_url'] : 'https://rpm.geoplatform.gov';
	$geopoauth_idpUrl = isset($_ENV['ual_url']) ? $_ENV['ual_url'] : 'https://ual.geoplatform.gov';
	$geopoauth_token = isset($_ENV['rpm_token']) ? $_ENV['rpm_token'] : '';

	$geopoauth_stuff = <<<_GEOPLATFORMVAR
  <script type="text/javascript">
  if(typeof(GeoPlatform) === 'undefined') GeoPlatform = {};
  GeoPlatform.config = {
     wpUrl: "$geopoauth_home",
     ualUrl: "$geopoauth_ualUrl",
		 portalUrl: "$geopoauth_portalUrl",
     rpm: {
       rpmUrl: "$geopoauth_rpmUrl",
       rpmToken: "$geopoauth_token",
     },
     auth: {
       APP_BASE_URL: "$geopoauth_home",
       IDP_BASE_URL: "$geopoauth_idpUrl",
       LOGIN_URL: "$geopoauth_login",
       LOGOUT_URL: "$geopoauth_logout",
      }
  }
  </script>
_GEOPLATFORMVAR;

	echo $geopoauth_stuff;
}
add_action('wp_head', 'geopitems_establish_globals');

// Redirect logic for item details page. Will redirect to UAL if...
// 1) There is a recognized Accept header element.
// 2) There is a recognized extension in the URL bar.
function geopccb_redirect_logic(){

	// This will only trigger on the item details page.
	if (is_page()){
		global $post;
		if ($post->post_name == 'geoplatform-items'){

			// Gets the wordpress global and url, and sets up redirect extension var.
			global $wp;
			$url_dump = home_url($wp->request);
			$geopccb_redirect_val = false;

			// The URL bar is checked for extensions and redirect_val is assigned or
			// passed on as appropriate.
	    $geopccb_redirect_val = (strpos($url_dump, '.rdf')) ? ".rdf" : $geopccb_redirect_val;
	    $geopccb_redirect_val = (strpos($url_dump, '.json')) ? ".json" : $geopccb_redirect_val;
			$geopccb_redirect_val = (strpos($url_dump, '.jsonld')) ? ".jsonld" : $geopccb_redirect_val;
	    $geopccb_redirect_val = (strpos($url_dump, '.ttl')) ? ".ttl" : $geopccb_redirect_val;
	    $geopccb_redirect_val = (strpos($url_dump, '.n3')) ? ".n3" : $geopccb_redirect_val;
	    $geopccb_redirect_val = (strpos($url_dump, '.nt')) ? ".nt" : $geopccb_redirect_val;

			// If no extension was found, the headers are checked.
			if (!$geopccb_redirect_val){

				// Grabs the accept header, either capitalized or not.
				$head_dump = isset(getallheaders()['accept']) ? getallheaders()['accept'] : getallheaders()['Accept'];

				// If the header exists, explodes it into an array for analysis. Checks
				// the array for each desired type, and assigns an associated extension
				// to the redirect val if found; otherwise, the val is passed on.
				if (!empty($head_dump)){
					$head_explode = explode(",", $head_dump);
					for ($i = 0; $i < count($head_explode) && !$geopccb_redirect_val; $i++){
						$head_element = $head_explode[$i];
						$geopccb_redirect_val = ($head_element == "application/rdf+xml") ? ".rdf" : $geopccb_redirect_val;
						$geopccb_redirect_val = ($head_element == "application/json") ? ".json" : $geopccb_redirect_val;
						$geopccb_redirect_val = ($head_element == "application/ld+json") ? ".jsonld" : $geopccb_redirect_val;
						$geopccb_redirect_val = ($head_element == "text/x-turtle") ? ".ttl" : $geopccb_redirect_val;
						$geopccb_redirect_val = ($head_element == "text/n3") ? ".n3" : $geopccb_redirect_val;
						$geopccb_redirect_val = ($head_element == "application/n-triples") ? ".nt" : $geopccb_redirect_val;
					}
				}
			}

			// If there is a valid redirect, the redirection can occur.
			if ($geopccb_redirect_val){

				// Determines UAL env.
			  $geopccb_ual_url_base = isset($_ENV['ual_url']) ? $_ENV['ual_url'] : 'https://ual.geoplatform.gov';
				// $geopccb_ual_url_base = 'https://sit-ual.geoplatform.us';

				// Gets the ID of the asset from the URL bar.
			  $geopccb_redirect_id = "";
			  preg_match('([a-f\d]{32})', $url_dump, $geopccb_redirect_id);
				if (isset($geopccb_redirect_id[0]) && strlen($geopccb_redirect_id[0]) == 32){
					// Constructs the complete UAL url.
			  	$geopccb_ual_url_full = $geopccb_ual_url_base . "/api/items/" . $geopccb_redirect_id[0] . $geopccb_redirect_val;

					// Performs 302 redirection.
			  	wp_redirect($geopccb_ual_url_full, 302);
					exit;
				}
			}
		}
	}
}
add_action('template_redirect', 'geopccb_redirect_logic');


// Rule rewrites to control the URL of the items page.
function geopitems_add_rewrite_rules( $wp_rewrite )
{
	$geop_items_namespaces = 'dataset|datasets|service|services|layer|layers|map|maps|gallery|galleries|community|communities|organization|organizations|contact|contacts|person|persons|people|concept|concepts|conceptscheme|conceptschemes|webapp|webapps|website|websites|topic|topics|application|applications|rightstatement|rightstatements|rightsstatement|rightsstatements|product|products|imageproducts|imageproduct';
	$geop_items_key = 'resources\/(' . $geop_items_namespaces . ')\/([a-f\d]*)\/?';
	$geop_items_value = 'index.php?pagename=geoplatform-items&q=' . $wp_rewrite->preg_index(1);
  // $geop_items_new_rules = array( 'resources/([a-f\d]{32})/?' => 'index.php?pagename=' . get_theme_mod('headlink_items') . '&q=' . $wp_rewrite->preg_index(1) );
	$geop_items_new_rules = array( $geop_items_key => $geop_items_value );

  // Add the new rewrite rule into the top of the global rules array
  $wp_rewrite->rules = $geop_items_new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'geopitems_add_rewrite_rules');


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
