<?php
/*
 Plugin Name: Content Blocks (Custom Post Widget)
 Plugin URI: http://www.vanderwijk.com/wordpress/wordpress-custom-post-widget/?utm_source=wordpress&utm_medium=plugin&utm_campaign=custom_post_widget
 Description: Show the content of a custom post of the type 'content_block' in a widget or with a shortcode.
 Version: 3.0.3
 Author: Johan van der Wijk
 Author URI: http://vanderwijk.nl
 Text Domain: custom-post-widget
 Domain Path: /languages
 License: GPL2

 Release notes: You can now show the featured image when using the shortcode to display a content block.

 Copyright 2017 Johan van der Wijk

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Launch the plugin.
function custom_post_widget_plugin_init() {
	add_action( 'widgets_init', 'custom_post_widget_load_widgets' );
}
add_action( 'plugins_loaded', 'custom_post_widget_plugin_init' );

// Load plugin textdomain.
function custom_post_widget_load_textdomain() {
	load_plugin_textdomain( 'custom-post-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'custom_post_widget_load_textdomain' );

// Loads the widgets packaged with the plugin.
function custom_post_widget_load_widgets() {
	require_once( 'post-widget.php' );
	register_widget( 'custom_post_widget' );
}

// Admin-only functions
if ( is_admin() ) {

	// Add donation and review links to plugin description
	if ( ! function_exists ( 'cpw_plugin_links' ) ) {
		function cpw_plugin_links( $links, $file ) {
			$base = plugin_basename( __FILE__ );
			if ( $file == $base ) {
				$links[] = '<a href="https://wordpress.org/support/plugin/custom-post-widget/reviews/" target="_blank">' . __( 'Review', 'custom-post-widget' ) . ' <span class="dashicons dashicons-thumbs-up"></span></a> | <a href="https://paypal.me/vanderwijk">' . __( 'Donate', 'custom-post-widget' ) . ' <span class="dashicons dashicons-money"></span></a>';
			}
			return $links;
		}
	}
	add_filter( 'plugin_row_meta', 'cpw_plugin_links', 10, 2 );

	require_once( 'meta-box.php' );
	require_once( 'popup.php' );

	// Enqueue styles and scripts on content_block edit page
	function cpw_enqueue() {
		$screen = get_current_screen();
		// Check screen base and current post type
		if ( 'post' === $screen -> base && 'content_block' === $screen -> post_type ) {
			wp_enqueue_style( 'cpw-style', plugins_url( '/assets/css/custom-post-widget.css', __FILE__ ) );
			wp_enqueue_script( 'clipboard', plugins_url( '/assets/js/clipboard.min.js', __FILE__ ), array(), '1.5.16', true );
			wp_enqueue_script( 'clipboard-init', plugins_url( '/assets/js/clipboard.js', __FILE__ ), array(), false, true );
		}
	}
	add_action( 'admin_enqueue_scripts', 'cpw_enqueue' );

}
