<?php 
/*
    A GeoPlatform Front Page Template
*/

if ( 'page' == get_option( 'show_on_front' ) ) {
    include( get_page_template() );
} else {
    get_header();
    get_template_part( 'mega-menu', get_post_format() ); 
    get_template_part( 'gp_intro', get_post_format() ); 
    get_template_part( 'main-page', get_post_format() ); 
    var_dump(get_theme_mods());
    $theme_options = geop_ccb_get_options();
    //$customizerLink = get_theme_mod( 'Map_Gallery_link_box' );
    $customizerLink = $theme_options['map_gallery_link_box_setting'];
    if($customizerLink){
        get_template_part( 'map-gallery', get_post_format() );
    } 
    get_footer(); 
}
?>