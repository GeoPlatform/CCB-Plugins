<!--include header area-->
<?php get_header('ngda'); ?>

<!--Sectioning of pages, the Loop setup, pagination, and general flow of a lot of this theme came from
https://www.taniarascia.com/developing-a-wordpress-theme-from-scratch/ -->
<?php get_template_part( 'mega-menu', get_post_format() ); ?>
  <!--GeoPlatform header with intro -->
    <?php //get_template_part( 'gp_intro', get_post_format() ); ?>

<!--Featured Services section-->
<!--<?php// get_template_part( 'featured', get_post_format() ); ?>-->
<!--WP backend test section-->
  <?php //get_template_part( 'main-page', get_post_format() ); ?>

  <?php $customizerLink = get_theme_mod( 'Map_Gallery_link_box' );?>
  <?php
  if($customizerLink){
  get_template_part( 'map-gallery', get_post_format() );
    } ?>


<?php get_footer(); ?>
