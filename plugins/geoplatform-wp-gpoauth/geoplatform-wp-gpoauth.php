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
 * @since             1.0.4
 * @package           Geoplatform_Wp_Gpoauth
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform WP GPOAuth
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Provides a variety of support functions for the GeoPlatform Portal experience.
 * Version:           1.0.4
 * Author:            Image Matters LLC
 * Author URI:        https://www.imagemattersllc.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-wp-gpoauth
 * Domain Path:       /languages
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
define( 'GEOWPOAUTH_PLUGIN', '1.0.4' );


// Enqueues the JS portion of this plugin.
function geopoauth_enqueue_scripts() {
	wp_enqueue_script( 'geopoauth_js', plugin_dir_url( __FILE__ ) . 'geoplatform-wp-gpoauth.js');

	wp_localize_script( 'geopoauth_js', 'MyAjax', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'security' => wp_create_nonce( 'nonce-string' )
  ));
}
// add_action( 'wp_enqueue_scripts', 'geopoauth_enqueue_scripts' );










// Sets the parameters of and then creates the token page. It deletes any old
// version of that page before each generation.
function geopoauth_add_interface_page() {
	wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'checktoken' ))), true);
	$geopsearch_interface_post = array(
		'post_title' => 'GeoPlatform Token Check',
		'post_name' => 'checktoken',
		'post_status' => 'publish',
		'post_type' => 'page',
		'post_content' => 'This page exists to pass authentication tokens off to Item Detail and Registration plugins. Please do not alter, delete, or replace this page. If it is somehow corrupted, please disable and reactivate the GeoPlatform WP GPOAuth plugin.',
	);

	wp_insert_post($geopsearch_interface_post);
}

// Activation hook for the token page.
register_activation_hook( __FILE__, 'geopoauth_add_interface_page' );


// Hook for application of response headers.
add_action('template_redirect', 'geopoauth_register_authorize');

// Checks the current page, ensuring that it is the "checktoken" page. If it is,
// the Authorize => Bearer token response header is applied to that page.
function geopoauth_register_authorize(){
	global $post;
	// $geopoauth_domain = isset($_ENV['wpp_url']) ? ltrim(strstr($_ENV['wpp_url'], '.'), '.') : 'geoplatform.gov';
	$geopoauth_domain = "localhost";
	if (is_user_logged_in()){
		setrawcookie('gpoauth-a', base64_encode(get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token']), current_time( 'timestamp' , TRUE ) + 86400, "/", $geopoauth_domain, TRUE, FALSE);
		if (isset($_COOKIE['geop_auth_cookie'])){
			setrawcookie('gpoauth-a', $_COOKIE['geop_auth_cookie'], current_time( 'timestamp' , TRUE ) + 86400, "/", $geopoauth_domain, TRUE, FALSE);
		}
	}
	else {
		$compath = isset($_ENV['sitename']) ? "/" . $_ENV['sitename'] : "";
		unset($_COOKIE['geop_auth_cookie']);
		setrawcookie('geop_auth_cookie', '', 1, '/', $geopoauth_domain, TRUE, FALSE);
		unset($_COOKIE['gpoauth-a']);
		setrawcookie('gpoauth-a', '', 1, '/', $geopoauth_domain, TRUE, FALSE);
		// setcookie('geop_auth_cookie', '', current_time( 'timestamp' , TRUE ) - 3600, $compath . '/checktoken/', '', TRUE, FALSE);
	}
}







function temp_cookie(){
	$cookie_string = "ZXlKaGJHY2lPaUpJVXpJMU5pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnpkV0lpT2lJMVlUa3dNbVZpTnpReU5qZGtaakF3TVdJM1kyVXlPRGtpTENKcGMzTWlPaUpvZEhSd2N6b3ZMMnh2WTJGc2FHOXpkQzVzYjJOaGJDSXNJbVY0Y0NJNk1UVTNOalk1TkRnME1pd2lZWFZrSWpvaU5XRTNZMkprTldJeU16RTJORFV3TURGaU5Ua3daamMwSWl3aWMyTnZjR1VpT2xzaWNtVmhaQ0pkTENKdVlXMWxJam9pVEdWbElFaGxZWHBsYkNJc0ltVnRZV2xzSWpvaWJHVmxhRUJwYldGblpXMWhkSFJsY25Oc2JHTXVZMjl0SWl3aWRYTmxjbTVoYldVaU9pSnNhR1ZoZW1Wc0lpd2ljbTlzWlhNaU9pSmhaRzFwYmlJc0ltOXlaM01pT2x0N0lsOXBaQ0k2SWpWaE5UZzFOMlZtTVdVNE9UY3dNREF4WWpkbU56SmlaQ0lzSW01aGJXVWlPaUpKYldGblpTQk5ZWFIwWlhKeklFeE1ReUo5WFN3aVozSnZkWEJ6SWpwYmV5SmZhV1FpT2lJMVlUWmhNamt3TVdZME5qRTFOVEF3TVdKbU5EY3laREFpTENKdVlXMWxJam9pWjNCZlpXUnBkRzl5SW4xZExDSnBZWFFpT2pFMU56WTJPVFExTkRKOS5RMzMtRXBhRTB4UkozT0FuOFlwc2VRV1NTb2NDaGtReVB0eF9zcHNNUXRZ";

	setrawcookie('gpoauth-b', $cookie_string, current_time( 'timestamp' , TRUE ) + 86400, '/', "localhost");
}
// add_action('template_redirect', 'temp_cookie');



function geopoauth_ajax_function(){
	check_ajax_referer( 'nonce-string', 'security' );
	$total = $_POST['timeone'] - $_POST['timetwo'];

	// If the result of total is negative, it indicates an expired gpoauth cookie.
	// This means that the user should be logged out.
	if ($total < 0 && is_user_logged_in()){
		wp_clear_auth_cookie();
		echo "die";
		die();
	}

	echo $total;
	die();
}
// add_action( 'wp_ajax_geopoauth_ajax_function', 'geopoauth_ajax_function' );
// add_action( 'wp_ajax_nopriv_geopoauth_ajax_function', 'geopoauth_ajax_function' );













// Establishes the global GeoPlatform class if it hasn't been set yet.
function geopoauth_establish_globals() {
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
add_action('wp_head', 'geopoauth_establish_globals');


// Injects RPM reporting code into each page's head.
function geopoauth_establish_rpm() {
	$cache_buster = time();

	$geopoauth_stuff = <<<_GEOPLATFORMRPM
  <!-- RPM Reporting -->
  <script src="https://s3.amazonaws.com/geoplatform-cdn/gp.rpm/stable/js/gp.rpm.browser.min.js?t=$cache_buster"></script>
  <script type="text/javascript">
	/* Setup Global RPM variable for usage in page.
	 *
	 * SIDE-EFFECT:
	 * 	This call will report a 'PageView' event.
	 *
	 * Usage:
	 * 	RPM.logEvent(TYPE, EVENT, ID)
	 *
	 * Full Usage Documentation:
	 * 	http://geoplatform-cdn.s3-website-us-east-1.amazonaws.com/gp.rpm/stable/docs/jsdocs/RPMService.html
	 */
	window.RPM = RPMService();
  </script>
_GEOPLATFORMRPM;

	echo $geopoauth_stuff;
}
add_action('wp_head', 'geopoauth_establish_rpm');
