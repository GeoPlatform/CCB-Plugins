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
 * @since             1.0.9
 * @package           Geop_Search
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Search
 * Plugin URI:        www.geoplatform.gov
 * Description:       Browse, search, and filter GeoPlatform service objects.
 * Version:           1.0.9
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
//add_filter('page_template', 'geopsearch_apply_template');
// function geopsearch_apply_template($geopsearch_page_template) {
//     if (is_page('geoplatform-search') && ! is_page_template('page-templates/search_page.php'))
//         $geopsearch_page_template = dirname( __FILE__ ) . '/public/partials/geoplatform-search-page-template.php';
//     return $geopsearch_page_template;
// }

// Sets the parameters of and then creates the search page. It deletes any old
// version of that page before each generation.
function geopsearch_add_interface_page() {
	wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-search' ))), true);
	$geopsearch_interface_post = array(
		'post_title' => 'GeoPlatform Search',
		'post_name' => 'geoplatform-search',
		'post_status' => 'publish',
		'post_type' => 'page',
	);
	if ((strpos(strtolower(wp_get_theme()->get('Name')), 'geoplatform') !== false) && is_page_template('page-templates/geop_search_page.php'))
		$geopsearch_interface_post = array_merge($geopsearch_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/geop_search_page.php'));
	else if ((strpos(strtolower(wp_get_theme()->get('Name')), 'geoplatform') !== false) && is_page_template('page-templates/page_full-width.php'))
		$geopsearch_interface_post = array_merge($geopsearch_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/page_full-width.php'));
	else
		$geopsearch_interface_post = array_merge($geopsearch_interface_post, array('post_content' => '<app-root></app-root>'));

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

// AJAX handling only seems to function properly if both the hooks and PHP
// functions are placed in this file. Instead of producing clutter, the files
// that perform the settings interface add and remove map operations are simply
// included here.
function geopsearch_process_refresh() {
	include 'admin/partials/geoplatform-search-recreate.php';
	wp_die();
}
add_action('wp_ajax_geopsearch_refresh', 'geopsearch_process_refresh');

function geopsearch_perform_site_search() {
	include 'public/partials/geoplatform-search-site_search.php';
	wp_die();
}
add_action('wp_ajax_geopsearch_site_search', 'geopsearch_perform_site_search');





















/* Endpoint for custom search algorithm.
 *
 * Reference: https://benrobertson.io/wordpress/wordpress-custom-search-endpoint
 *
 * This method below registers the endpoint route.
*/
function geopsearch_register_search_route() {
    register_rest_route('geoplatform-search/v1', '/gpsearch', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'geopsearch_ajax_search',
        'args' => geopsearch_get_search_args(),
    ]);
}
add_action( 'rest_api_init', 'geopsearch_register_search_route');

// Sets up the search args.
function geopsearch_get_search_args() {
    $args = [];
		$args['type'] = [
       'description' => esc_html__( 'asset type. post, page, or media.', 'geopsearch' ),
       'type'        => 'string',
   ];
    $args['s'] = [
       'description' => esc_html__( 'The search term.', 'geopsearch' ),
       'type'        => 'string',
   ];
   return $args;
}

// Performs the actual search operation.
function geopsearch_ajax_search( $request ) {
  $posts = [];
  $results = [];
	$stype = isset($request['type']) ? $request['type'] : ['page', 'post'];

  // check for a search term
  if( isset( $request['s'] ) ){
		// get posts
	  $posts = get_posts([
	    'posts_per_page' => -1,
	    'post_type' => $stype,
			// 's' => 'hurricane+hurricane',
	    's' => $request['s'],
	  ]);
		// set up the data I want to return
	  foreach($posts as $post){
	    $results[] = [
	      'title' => $post->post_title,
	      'link' => get_permalink( $post->ID ),
				'meta' => $post,
	    ];
  	}
	}

	if ( empty($results) ) :
    return new WP_Error( 'front_end_ajax_search', 'No results');
  endif;

  return rest_ensure_response( $results );
}


































// Registers the rest api endpoint for the search, which calls its function.
//
// Endpoint: {home url}/wp-json/geoplatform-search/v1/{type}/{query}/{author}/{page}/{per_page}/{order}/{orderby}
//
// @Param {home url}: home url, such as "sit.geoplatform.us" or "communities.geoplatform.gov/oem"
// @Param {type}: Type of asset to search for, must be "post", "page", or "media"
// @Param {query}: Search query. Spaces must be replaced with underscores.
// @Param {author}: name of the author, not ID.
// @Param {page}: current page, parameter not currently in use.
// @Param {perpage}: results per page, parameter not currently in use.
// @Param {order}: how the results are to be ordered, "asc" or "desc".
// @Param {orderby}: the means by which the results are ordered, such as "modified" or "date"
//
function geopsearch_rest_api_register(){
	register_rest_route( 'geoplatform-search/v1', '/(?P<type>[^/]*)/(?P<search>[^/]*)/(?P<author>[^/]*)/(?P<page>[\d]*)/(?P<per_page>-?[\d]*)/(?P<order>[^/]*)/(?P<orderby>[^/]*)', array(
		'methods' => 'GET',
		'callback' => 'geopsearch_rest_api_search',
	));
}
add_action( 'rest_api_init', 'geopsearch_rest_api_register');




function geopsearch_rest_api_test(){
	register_rest_route( 'geoplatform-test/v1', '/(?P<type>[^/]*)/(?P<search>[^/]*)/(?P<author>[^/]*)/(?P<page>[\d]*)/(?P<per_page>-?[\d]*)/(?P<order>[^/]*)/(?P<orderby>[^/]*)', array(
		'methods' => 'GET',
		'callback' => 'geopsearch_output_test',
	));
}
add_action( 'rest_api_init', 'geopsearch_rest_api_test');


function geopsearch_output_test( WP_REST_Request $geopsearch_data_in ){
	return $geopsearch_data_in->get_params();
}

// Actual search function.
function geopsearch_rest_api_search( WP_REST_Request $geopsearch_data_in ){

	// Parse input data
	$geopsearch_content_type = $geopsearch_data_in->get_param('type');
	$geopsearch_content_search = $geopsearch_data_in->get_param('search');
	$geopsearch_content_author = $geopsearch_data_in->get_param('author');
	$geopsearch_content_page = $geopsearch_data_in->get_param('page');
	$geopsearch_content_perpage = $geopsearch_data_in->get_param('per_page');
	$geopsearch_content_order = $geopsearch_data_in->get_param('order');
	$geopsearch_content_orderby = $geopsearch_data_in->get_param('orderby');

	$geopsearch_content_terms = explode("_", $geopsearch_content_search);

	// Post search methodology
	if ($geopsearch_content_type == 'post'){
	  $geopsearch_post_args_two = array(
	    'post_type' => 'post',
	    'numberposts' => -1,
			'posts_per_page' => -1,
	    'author_name' => $geopsearch_content_author,
	    'order' => $geopsearch_content_order,
	    'orderby' => $geopsearch_content_orderby,
	    'tax_query' => array(
	      'relation' => 'OR',
	      array(
	        'taxonomy' => 'category',
	        'field' => 'slug',
	        'terms' => array($geopsearch_content_search),
	      ),
	      array(
	        'taxonomy' => 'post_tag',
	        'field' => 'slug',
	        'terms' => array($geopsearch_content_search),
	      ),
	    )
	  );

	  $geopsearch_post_args_one = array(
	    'post_type' => 'post',
	    'numberposts' => -1,
			'posts_per_page' => -1,
	    'author_name' => $geopsearch_content_author,
	    's' => $geopsearch_content_search,
	    'order' => $geopsearch_content_order,
	    'orderby' => $geopsearch_content_orderby,
	  );

	  $geopsearch_post_fetch_one = new WP_Query($geopsearch_post_args_one);
	  $geopsearch_post_fetch_two = new WP_Query($geopsearch_post_args_two);
	  $geopsearch_post_fetch_final = array();

	  $geopsearch_post_fetch_final = array_unique(array_merge( $geopsearch_post_fetch_one->posts, $geopsearch_post_fetch_two->posts ), SORT_REGULAR );
	  // $geopsearch_post_fetch_final->post_count = count($geopsearch_post_fetch_final->posts);

	  return $geopsearch_post_fetch_final;
	}

	// Page search methodology.
	if ($geopsearch_content_type == 'page'){
	  $geopsearch_page_args_two = array(
	    'post_type' => 'page',
	    'numberposts' => -1,
			'posts_per_page' => -1,
	    'author_name' => $geopsearch_content_author,
	    'order' => $geopsearch_content_order,
	    'orderby' => $geopsearch_content_orderby,
	    'tax_query' => array(
	      'relation' => 'OR',
	      array(
	        'taxonomy' => 'category',
	        'field' => 'slug',
	        'terms' => array($geopsearch_content_search),
	      ),
	      array(
	        'taxonomy' => 'post_tag',
	        'field' => 'slug',
	        'terms' => array($geopsearch_content_search),
	      ),
	    )
	  );

	  $geopsearch_page_args_one = array(
	    'post_type' => 'page',
	    'numberposts' => -1,
			'posts_per_page' => -1,
	    'author_name' => $geopsearch_content_author,
	    's' => $geopsearch_content_search,
	    'order' => $geopsearch_content_order,
	    'orderby' => $geopsearch_content_orderby,
	  );

	  $geopsearch_page_fetch_one = new WP_Query($geopsearch_page_args_one);
	  $geopsearch_page_fetch_two = new WP_Query($geopsearch_page_args_two);
	  $geopsearch_page_fetch_final = array();

	  $geopsearch_page_fetch_final = array_unique( array_merge( $geopsearch_page_fetch_one->posts, $geopsearch_page_fetch_two->posts ), SORT_REGULAR );
	  // $geopsearch_page_fetch_combo->post_count = count($geopsearch_page_fetch_final->posts);

	  return $geopsearch_page_fetch_final;
	}

	// Media search methodology
	if ($geopsearch_content_type == 'media'){
	  $geopsearch_media_args = array(
	    'post_type'      => 'attachment',
	    'post_mime_type' => 'image',
	    'post_status'    => 'inherit',
	    'posts_per_page' => - 1,
	    's' => $geopsearch_content_search,
	    'order' => $geopsearch_content_order,
	    'orderby' => $geopsearch_content_orderby,
	  );

	  $geopsearch_media_fetch = new WP_Query( $geopsearch_media_args );
	  return $geopsearch_media_fetch->posts;
	}
}



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
