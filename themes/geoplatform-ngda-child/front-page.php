<!--include header area-->
<?php

    get_header('ngda');
    get_template_part( 'mega-menu', get_post_format() );
    get_template_part( 'gp_intro', get_post_format() );
    get_template_part( 'ngda_main-page', get_post_format() );
    get_template_part( 'featured-posts', get_post_format() );

      ?>

  <?php
  $geopccb_theme_options = geop_ccb_get_theme_mods();
  $geopccb_customizerLink = get_theme_mod('map_gallery_link_box_setting', $geopccb_theme_options['map_gallery_link_box_setting']);
  if($geopccb_customizerLink){
    get_template_part( 'map-gallery', get_post_format() );
  }
//}?>

<?php get_footer(); ?>
