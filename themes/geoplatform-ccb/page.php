<?php
get_header();
get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );
?>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-sm-8">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'page-single', get_post_format() );
        //Un-comment the code below to show comments
        if ( comments_open() || get_comments_number() ) :
        	  comments_template();
        	endif;
        endwhile; endif;

      //Paginate posts if the <!--nextpage--> <!--tag is added to the content
      wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'geoplatform-ccb' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        ) );
      ?>
    </div><!--#col-md-8 col-sm-8-->
    <div class="col-md-4 col-sm-4">
      <?php get_template_part( 'sidebar', get_post_format() ); ?>
    </div><!--#col-md-4 col-sm-4-->
  </div><!--#row-->
</div><!--#container-->
<?php get_footer(); ?>
