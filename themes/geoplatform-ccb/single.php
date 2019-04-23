<?php
/**
 * The template for Single posts
 *
 * @link https://developer.wordpress.org/themes/template-files-section/post-template-files/
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */

get_header();
// get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );
?>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-sm-8">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
      //https://codex.wordpress.org/Customizing_the_Read_More#How_to_use_Read_More_in_Pages
      // global $more;
      // $more = 0;
      get_template_part( 'post-single', get_post_format() );

      //Un-comment the code below to show comments on the posts
      if ( comments_open() || get_comments_number() ) :
      	  comments_template();
          echo "</br>";
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
