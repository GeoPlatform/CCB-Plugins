<?php
/**
 * A GeoPlatform Front Page Template
 *
 * @link https://codex.wordpress.org/Creating_a_Static_Front_Page
 *
 * @package GeoPlatform CCB
 *
 * @since 3.1.3
 */

if ( 'page' == get_option( 'show_on_front' ) ) {
    include( get_page_template() );
} else {
    $geopccb_theme_options = geop_ccb_get_theme_mods();

    get_header();
    // if (get_theme_mod('bootstrap_controls', $geopccb_theme_options['bootstrap_controls']) != 'gone'){
    //   get_template_part( 'mega-menu', get_post_format() );
    // }
    get_template_part( 'gp_intro', get_post_format() );
    get_template_part( 'main-page', get_post_format() );
    if (get_theme_mod('map_gallery_link_box_setting', $geopccb_theme_options['map_gallery_link_box_setting'])){
      get_template_part( 'map-gallery', get_post_format() );
    }
    get_footer();
}
?>
