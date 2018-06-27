<?php 
/*
    A GeoPlatform Index page
*/

get_header();
get_template_part( 'mega-menu', get_post_format() ); 
get_template_part( 'gp_intro', get_post_format() ); 
get_template_part( 'main-page', get_post_format() ); 

$theme_options = geop_ccb_get_options();
$customizerLink = $theme_options['map_gallery_link_box_setting'];
if($customizerLink){
get_template_part( 'map-gallery', get_post_format() );
} 

get_footer(); 

?>
