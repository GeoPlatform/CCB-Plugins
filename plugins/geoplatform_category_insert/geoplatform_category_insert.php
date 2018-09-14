<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.geoplatform.gov
 * @since             1.0.10
 * @package           Geop_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Category Insert
 * Plugin URI:        www.geoplatform.gov
 * Description:       Creates a custom post type for categories.
 * Version:           1.0.10
 * Author:            Image Matters LLC: Lee Heazel
 * Author URI:        http://www.imagemattersllc.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-maps
 * Domain Path:       /languages
*/


if ( ! function_exists ( 'geop_ccb_create_category_post' ) ) {
  function geop_ccb_create_category_post() {
    register_post_type( 'geopccb_catlink',
      array(
        'labels' => array(
          'name' => 'Category Link',
          'singular_name' => 'Category Links'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'author', 'thumbnail', 'excerpt'),
      )
    );
  }
  add_action( 'init', 'geop_ccb_create_category_post' );
}

 ?>
