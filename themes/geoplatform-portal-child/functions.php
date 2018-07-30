<?php

function geopportal_enqueue_scripts() {

    $parent_style = 'custom-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
    wp_enqueue_style( 'jquery' );
    wp_enqueue_script( 'auth', get_stylesheet_directory_uri() . '/scripts/authentication.js' );
    wp_enqueue_script( 'fixedScroll', get_stylesheet_directory_uri() . '/scripts/fixed_scroll.js');
}
add_action( 'wp_enqueue_scripts', 'geopportal_enqueue_scripts' );

//Disable admin bar (un-comment for prod sites)
// add_filter('show_admin_bar', '__return_false');

//--------------------------
//Support adding Menus for header and footer
//https://premium.wpmudev.org/blog/add-menus-to-wordpress/?utm_expid=3606929-97.J2zL7V7mQbSNQDPrXwvBgQ.0&utm_referrer=https%3A%2F%2Fwww.google.com%2F
//--------------------------
function geop_ccb_register_menus() {
  register_nav_menus(
    array(
      'headfoot-featured' => __( 'HF - Featured' ),
      'headfoot-getInvolved' => __( 'HF - Get Involved' ),
      'headfoot-appservices' => __( 'HF - Apps and Services' ),
      'headfoot-aboutL' => __( 'HF - About Left' ),
      'headfoot-aboutR' => __( 'HF - About Right' ),
      'headfoot-help' => __( 'HF - Help' ),
      'headfoot-themes' => __( 'HF - Themes')
    )
  );
}
add_action( 'init', 'geop_ccb_register_menus' );

//-------------------------------
// Diabling auto formatting and adding <p> tags to copy/pasted HTML in pages
//-------------------------------
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );



?>
