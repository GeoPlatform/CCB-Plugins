<?php
/**
 * Enqueue stylesheets. Parent first
 * 
 * @link https://codex.wordpress.org/Child_Themes
 *
 * @return void
 */
// function my_theme_enqueue_styles() {
//     wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/css/Geomain_style.css' );

// }
// add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

/**
 * Enqueue stylesheets. Parent first
 * 
 * @link https://codex.wordpress.org/Child_Themes
 *
 * @return void
 */
function my_theme_enqueue_styles() {

    $parent_style = 'theme-style'; // This is 'theme-style' for the Geoplatform-CCB theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/css/Geomain_style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

?>