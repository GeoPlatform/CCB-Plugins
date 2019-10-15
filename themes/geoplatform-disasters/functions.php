<?php

// Core child theme register.
function geop_disasters_enqueue_scripts() {

    $parent_style = 'parent-style';

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',  get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
}

add_action( 'wp_enqueue_scripts', 'geop_disasters_enqueue_scripts' );


// Manually including widgets that CCB 4 excludes from child themes.
get_template_part( 'widget-front-featured', get_post_format() );
get_template_part( 'widget-front-banner', get_post_format() );
get_template_part( 'widget-front-maps', get_post_format() );
get_template_part( 'widget-sidebar-ngda', get_post_format() );


/**
 * Widgetizing Page One
 */
if ( ! function_exists ( 'geop_disasters_widget_page_one' ) ) {
	function geop_disasters_widget_page_one() {
		register_sidebar(
		array(
			'id' => 'geop-disasters-widget-page-one',
			'name' => __( 'Widget Page One', 'geoplatform-ccb' ),
			'description' => __( 'Full-width page that can host widgets.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_disasters_widget_page_one' );
}


/**
 * Widgetizing Page Two
 */
if ( ! function_exists ( 'geop_disasters_widget_page_two' ) ) {
	function geop_disasters_widget_page_two() {
		register_sidebar(
		array(
			'id' => 'geop-disasters-widget-page-two',
			'name' => __( 'Widget Page Two', 'geoplatform-ccb' ),
			'description' => __( 'Full-width page that can host widgets.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_disasters_widget_page_two' );
}


/**
 * Widgetizing Page Three
 */
if ( ! function_exists ( 'geop_disasters_widget_page_three' ) ) {
	function geop_disasters_widget_page_three() {
		register_sidebar(
		array(
			'id' => 'geop-disasters-widget-page-three',
			'name' => __( 'Widget Page Three', 'geoplatform-ccb' ),
			'description' => __( 'Full-width page that can host widgets.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_disasters_widget_page_three' );
}


?>
