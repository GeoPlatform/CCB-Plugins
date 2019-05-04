<?php
/**
 * The main index page
 *
 * @link https://codex.wordpress.org/Theme_Development#Index_.28index.php.29
 *
 * @package GeoPlatform CCB
 *
 * @since 1.0.0
 */

get_header();
// get_template_part( 'mega-menu', get_post_format() );
// get_template_part( 'gp_intro', get_post_format() );
// get_template_part( 'main-page', get_post_format() );

$geopccb_theme_options = geop_ccb_get_theme_mods();
$geopccb_customizerLink = get_theme_mod('map_gallery_link_box_setting', $geopccb_theme_options['map_gallery_link_box_setting']);
if($geopccb_customizerLink){
  // get_template_part( 'map-gallery', get_post_format() );
}

get_footer();

?>
