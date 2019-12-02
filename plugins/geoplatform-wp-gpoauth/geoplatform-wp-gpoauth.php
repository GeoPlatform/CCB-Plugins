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
 * @since             1.0.3
 * @package           Geoplatform_Wp_Gpoauth
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform WP GPOAuth
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Provides a variety of support functions for the GeoPlatform Portal experience.
 * Version:           1.0.3
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
define( 'GEOWPOAUTH_PLUGIN', '1.0.3' ); // <-- Is this a typo?


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
	if (is_page()){
		global $post;
		if (!is_user_logged_in()){
			$compath = isset($_ENV['sitename']) ? "/" . $_ENV['sitename'] : "";
			setcookie('geop_auth_cookie', '', current_time( 'timestamp' , TRUE ) - 3600, $compath . '/checktoken/', '', TRUE, TRUE);
		}
		if ($post->post_name == 'checktoken'){
			$header = "Authorization: Bearer";
			if (is_user_logged_in()){
				/**
				 * We need to instead set a top level domain cookie with the token.
				 *
				 * Example:
				 * domain: .geoplatform.us / .geoplatform.gov
				 * value: <base 64 encoded version of the token (not the raw token)>
				 *
				 * We no longer need to set any headers. We now only need to set the cookie.
				 * This is a new cookie. It is not the same as the OPEN-ID-CONNECT plugin.
				 *
				 * COOKIE MUST BE: 'gpoauth-a'
				 *
				 * Things to remember:
				 * - make miniaml change to prevent regressions this late in the Beta period
				 *
				 */
				// Warning: sudo code
				// setcookie('gpoauth-a', <base64 encoded token>, <expire in 1 day 3600 * 24>)
				$header = "Authorization: Bearer " . get_user_meta(get_current_user_id(), 'openid-connect-generic-last-token-response', true)['access_token'];

				if (isset($_COOKIE['geop_auth_cookie'])){
					$header = "Authorization: Bearer " . base64_decode($_COOKIE['geop_auth_cookie']);
				}
			}
			header($header);
		}
	}
}

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
