<!--include header area-->
<?php

    get_header('ngda');
    get_template_part( 'mega-menu', get_post_format() );
    get_template_part( 'gp_intro', get_post_format() );
    get_template_part( 'ngda_main-page', get_post_format() );
    get_template_part( 'featured-posts', get_post_format() );

      ?>

  <?php
  $customizerLink = get_theme_mod( 'Map_Gallery_link_box' );

//  if($customizerLink){
  get_template_part( 'map-gallery', get_post_format() );
//} else{

//}?>

<?php get_footer(); ?>
