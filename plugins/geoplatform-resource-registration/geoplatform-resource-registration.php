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
 * @package           Geoplatform_Resource_Registration
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Resource Registration
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Use a dedicated interface page to register resources with the GeoPlatform Portfolio.
 * Version:           1.0.0
 * Author:            Image Matters LLC
 * Author URI:        https://www.imagemattersllc.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-resource-registration
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
define( 'GEOREGISTER_PLUGIN', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geoplatform-resource-registration-activator.php
 */
function activate_geoplatform_resource_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-resource-registration-activator.php';
	Geoplatform_Resource_Registration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-resource-registration-deactivator.php
 */
function deactivate_geoplatform_resource_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-resource-registration-deactivator.php';
	Geoplatform_Resource_Registration_Deactivator::deactivate();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-resource-registration.php';

function geopregister_establish_globals() {
  ?>
  <script type="text/javascript">
		window.GeoPlatformPluginEnv = window.GeoPlatformPluginEnv || {}
		window.GeoPlatformPluginEnv.wpUrl = "<?php bloginfo('wpurl') ?>";

		window.GeoPlatform = window.GeoPlatform || {};
		window.GeoPlatform.APP_BASE_URL = "<?php echo home_url() ?>"; // root dir for site (ex: 'https://geoplatform.gov' or 'https://communities.geoplatform.gov/ngda-wildbeasts'
		window.GeoPlatform.IDP_BASE_URL = "<?php echo isset($_ENV['idp_url']) ? $_ENV['idp_url'] : 'https://idp.geoplatform.gov' ?>"; // Where IDP is
		window.GeoPlatform.ALLOW_SSO_LOGIN = "false";
		window.GeoPlatform.LOGIN_URL = "<?php echo wp_login_url() ?>";
		window.GeoPlatform.LOGOUT_URL = "<?php echo wp_logout_url() ?>";
  </script>
	<?php
}
add_action('wp_head', 'geopregister_establish_globals');

// Sets the parameters of and then creates the item details page. It deletes any
// old version of that page before each generation.
function geopregister_add_interface_page() {
	$geopregister_parent_id = url_to_postid( get_permalink( get_page_by_path( 'resources' )));

	// If there is no 'resources' page, it is made here and its ID passed off for
	// use in the registration page creation below.
	if ($geopregister_parent_id == null){
	  $geopregister_parent_post = array(
	    'post_title' => 'Resources',
	    'post_name' => 'resources',
	    'post_status' => 'publish',
	    'post_type' => 'page',
	  );

	  $geopregister_parent_id = wp_insert_post($geopregister_parent_post);
	}

	// The previous registration page is deleted and the array for the new one made.
	wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'resources/register' ))), true);
	$geopregister_interface_post = array(
		'post_title' => 'Register Your Data with GeoPlatform.gov',
		'post_name' => 'register',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_parent' => $geopregister_parent_id,
		'post_content' => '<app-root></app-root>',
	);

	// The page is created, and the resulting page ID is used to apply a value to
	// it's breadcrumb title.
	$geopregister_creation_id = wp_insert_post($geopregister_interface_post);
	update_post_meta($geopregister_creation_id, 'geopportal_breadcrumb_title', 'Register Data');
}

register_activation_hook( __FILE__, 'geopregister_add_interface_page' );
register_activation_hook( __FILE__, 'activate_geoplatform_resource_registration' );
register_deactivation_hook( __FILE__, 'deactivate_geoplatform_resource_registration' );

// Seperate dedicated google fonts enqueue to make sure it comes before others.
function geopregister_google_enqueue(){
	if (is_page('register')){
		wp_enqueue_style('google_fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons');
	}
}
add_action( 'template_redirect', 'geopregister_google_enqueue', 5 );

// Additional dependency enqueues.
function geopregister_page_enqueues(){
	if (is_page('register')){
		wp_enqueue_script( 'inline_bundle', plugin_dir_url( __FILE__ ) . 'public/js/inline.bundle.js', array(), false, true );
		wp_enqueue_script( 'polyfills_bundle', plugin_dir_url( __FILE__ ) . 'public/js/polyfills.bundle.js', array(), false, true );
		wp_enqueue_script( 'scripts_bundle', plugin_dir_url( __FILE__ ) . 'public/js/scripts.bundle.js', array(), false, true );
		wp_enqueue_script( 'main_bundle', plugin_dir_url( __FILE__ ) . 'public/js/main.bundle.js', array(), false, true );
		wp_enqueue_style( 'styles_bundle', plugin_dir_url( __FILE__ ) . 'public/css/styles.bundle.css', array(), false, 'all' );
	}
}
add_action( 'template_redirect', 'geopregister_page_enqueues', 10 );

// AJAX handling only seems to function properly if both the hooks and PHP
// functions are placed in this file. Instead of producing clutter, the files
// that perform the settings interface add and remove map operations are simply
// included here.
function geopregister_process_refresh() {
	geopregister_add_interface_page();
	wp_die();
}
add_action('wp_ajax_geopregister_refresh', 'geopregister_process_refresh');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geoplatform_resource_registration() {

	$plugin = new Geoplatform_Resource_Registration();
	$plugin->run();

}
run_geoplatform_resource_registration();
