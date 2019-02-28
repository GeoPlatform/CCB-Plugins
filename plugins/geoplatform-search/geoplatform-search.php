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
 * @since             1.0.10
 * @package           Geop_Search
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Search
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Browse, search, and filter GeoPlatform service objects.
 * Version:           1.0.10
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
 * Start at version 1.0.10 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEOSEARCH_PLUGIN', '1.0.10' );

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
		'post_title' => 'Search the GeoPlatform',
		'post_name' => 'geoplatform-search',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_content' => '<app-root></app-root>',
	);

	$geopsearch_creation_id = wp_insert_post($geopsearch_interface_post);
	update_post_meta($geopsearch_creation_id, 'geopportal_breadcrumb_title', 'Search');
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
	geopsearch_add_interface_page();
	wp_die();
}
add_action('wp_ajax_geopsearch_refresh', 'geopsearch_process_refresh');

function geopsearch_perform_site_search() {
	include 'public/partials/geoplatform-search-site_search.php';
	wp_die();
}
add_action('wp_ajax_geopsearch_site_search', 'geopsearch_perform_site_search');

/**
 * Get Docker container enviroment variables
 *
 * @param [string] $name
 * @param [string] $def (default)
 * @return ENV[$name] or $def if none found
 */

function geop_ccb_getEnv($name, $def){
  return isset($_ENV[$name]) ? $_ENV[$name] : $def;
}
//set env variables
$geopccb_maps_url = geop_ccb_getEnv('maps_url', 'https://maps.geoplatform.gov');



function geopsearch_establish_globals() {
  ?>
  <script type="text/javascript">
	GeoPlatform = {
	 config: {
	   wpUrl: "<?php echo home_url() ?>",
	   ualUrl: "<?php echo isset($_ENV['ual_url']) ? $_ENV['ual_url'] : 'https://ual.geoplatform.gov' ?>",
	   rpm: {
	     rpmUrl: "<?php echo isset($_ENV['rpm_url']) ? $_ENV['rpm_url'] : 'https://rpm.geoplatform.gov' ?>",
	     rpmToken: "<?php echo isset($_ENV['rpm_token']) ? $_ENV['rpm_token'] : '' ?>",
	   }
	   auth: {
	     APP_BASE_URL: "<?php echo home_url() ?>", // same as "wpUrl"
	     IDP_BASE_URL: "<?php echo isset($_ENV['accounts_url']) ? $_ENV['accounts_url'] : 'https://accounts.geoplatform.gov' ?>",
	     LOGIN_URL: "<?php echo wp_login_url() ?>",
	     LOGOUT_URL: "<?php echo wp_logout_url() ?>",
	    }
	  }
	}
  </script>
	<?php
}
add_action('wp_head', 'geopsearch_establish_globals');


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
  $args['q'] = [
    'description' => esc_html__( 'The search term.', 'geopsearch' ),
    'type'        => 'string',
  ];
	$args['author'] = [
    'description' => esc_html__( 'The author filter.', 'geopsearch' ),
    'type'        => 'string',
  ];
	$args['page'] = [
    'description' => esc_html__( 'The current page.', 'geopsearch' ),
    'type'        => 'string',
  ];
	$args['per_page'] = [
    'description' => esc_html__( 'Results per page.', 'geopsearch' ),
    'type'        => 'string',
  ];
	$args['order'] = [
    'description' => esc_html__( 'Binary order by which results are returned. asc or desc.', 'geopsearch' ),
    'type'        => 'string',
  ];
	$args['orderby'] = [
    'description' => esc_html__( 'Sorting order by which results are returned.', 'geopsearch' ),
    'type'        => 'string',
  ];
  return $args;
}

// Performs the actual search operation.
function geopsearch_ajax_search( $request ) {
	global $wpdb;

  $posts = [];
  $results = [];
	$post_type = isset($request['type']) ? $request['type'] : 'fail';
	$search_query = isset($request['q']) ? $request['q'] : '';
	$search_author = isset($request['author']) ? $request['author'] : '';
	$order_binary = isset($request['order']) ? $request['order'] : 'asc';
	$order_sort = isset($request['orderby']) ? $request['orderby'] : 'modified';
	$page = isset($request['page']) ? $request['page'] : 0;
	$per_page = isset($request['per_page']) ? $request['per_page'] : 5;
	$geopsearch_post_fetch_total = array();

	if ($post_type == 'fail' || ($post_type != 'media' && $post_type != 'page' && $post_type != 'post'))
		return array('error message' => 'Invalid request type. Must be post, page, or media.');

	if (!is_numeric($page) || !is_numeric($per_page))
		return array('error message' => 'Page and per_page parameters must be numeric.');

	if ($post_type == 'media'){
	  $geopsearch_media_args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => - 1,
			'author_name' => $search_author,
			's' => $search_query,
			'order' => $order_binary,
			'orderby' => $order_sort,
		);

		$geopsearch_media_fetch = new WP_Query( $geopsearch_media_args );
		$geopsearch_post_fetch_total = $geopsearch_media_fetch->posts;
	}
	else {
		// get posts
	  $posts_one = array(
			'numberposts' => -1,
			'posts_per_page' => -1,
	    'post_type' => $post_type,
	    'author_name' => $search_author,
	    'order' => $order_binary,
	    'orderby' => $order_sort,
	    's' => $search_query,
	  );

		$termIDs = get_terms(array(
			'name__like' => $search_query,
	    'fields' => 'ids',
		));

		$posts_two = array(
			'numberposts' => -1,
			'posts_per_page' => -1,
			'post_type' => $post_type,
			'author_name' => $search_author,
			'order' => $order_binary,
			'orderby' => $order_sort,
			'tax_query' => array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $termIDs,
				),
				array(
					'taxonomy' => 'post_tag',
					'field' => 'id',
					'terms' => $termIDs,
				),
			)
		);

		$geopsearch_post_fetch_one = new WP_Query($posts_one);
		$geopsearch_post_fetch_two = new WP_Query($posts_two);

		$geopsearch_post_fetch_total = array_unique(array_merge( $geopsearch_post_fetch_one->posts, $geopsearch_post_fetch_two->posts ), SORT_REGULAR );
	}
	$geopsearch_post_fetch_total = sort_posts($geopsearch_post_fetch_total, $order_sort, $order_binary);
	$geopsearch_total_count = count($geopsearch_post_fetch_total);

	if ( empty($geopsearch_post_fetch_total) )
    return array('message' => 'No results.');

	class SearchResults
	{
		public $page;
	  public $size;
		public $totalResults;
		public $type;
	  public $results;

	  function __construct($page, $size, $total, $type, $results){
	    $this->page = (int)$page;
	    $this->size = (int)$size;
			$this->totalResults = (int)$total;
			$this->type = $type;
	    $this->results = $results;
	  }
	}

	$slice_start = $page * $per_page;
	// instead of $start use start
	// instead of $per_page use $size
	$results = array_slice($geopsearch_post_fetch_total, $slice_start, $per_page, true);

	$page_object = new SearchResults($page, $per_page, $geopsearch_total_count, $post_type, $results);

	if ( empty($page_object->results) )
	 	return array('message' => 'Requested page exceeds result count.');

	return rest_ensure_response($page_object);
}

// From https://gist.github.com/bradyvercher/1576900
function sort_posts( $posts, $orderby, $order = 'ASC', $unique = true ) {
	if ( ! is_array( $posts ) ) {
		return false;
	}

	usort( $posts, array( new Sort_Posts( $orderby, $order ), 'sort' ) );

	return $posts;
}

class Sort_Posts {
	var $order, $orderby;

	function __construct( $orderby, $order ) {
		$this->orderby = $orderby;
		$this->order = ( 'desc' == strtolower( $order ) ) ? 'DESC' : 'ASC';
	}

	function sort( $a, $b ) {
		if ( $a->{$this->orderby} == $b->{$this->orderby} ) {
			return 0;
		}

		if ( $a->{$this->orderby} < $b->{$this->orderby} ) {
			return ( 'ASC' == $this->order ) ? -1 : 1;
		} else {
			return ( 'ASC' == $this->order ) ? 1 : -1;
		}
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
