<?php

//--------------------------------------------------
// Child theme setup for functions.php
// https://codex.wordpress.org/Child_Themes
//--------------------------------------------------
function geopccb_child_theme_enqueue_styles() {

  wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/css/Geomain_style.css' );
	wp_enqueue_style( 'bootstrap-css',get_template_directory_uri() . '/css/bootstrap.css');
  wp_enqueue_style( 'child-test-css',get_template_directory_uri() . '/test_this_css.css');

  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'custom-style' ), wp_get_theme()->get('Version'));
  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/Geomain_style.css', array( 'theme-style' ), wp_get_theme()->get('Version'));
  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/bootstrap.css', array( 'bootstrap-css' ), wp_get_theme()->get('Version'));
}
add_action( 'wp_enqueue_scripts', 'geopccb_child_theme_enqueue_styles' );


//------------------------------------
// Function that overrides the same-named one in the parent theme.
// https://code.tutsplus.com/tutorials/a-guide-to-overriding-parent-theme-functions-in-your-child-theme--cms-22623
//------------------------------------
// add_theme_support( 'custom-logo' );
function geop_ccb_custom_logo_setup() {
    $geop_ccb_logo_defaults = array(
        'height'      => 140,
        'width'       => 140,
        'flex-height' => false,
        'flex-width'  => false
    );
    add_theme_support( 'custom-logo', $geop_ccb_logo_defaults );
    echo "CHILD";
}
add_action( 'after_setup_theme', 'geop_ccb_custom_logo_setup', 15 );



?>
