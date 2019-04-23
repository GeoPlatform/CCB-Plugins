<?php
/**
 * Template Name: 2 Column, Left Sidebar
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
 ?>

 <?php get_header(); ?>
 <!--Used for the Main banner background to show up properly-->
 <?php get_template_part( 'single-banner', get_post_format() ); ?>

<div class="container">
  <div class="row">
    <div class="col-12">
       <div class="col-md-4 col-sm-4">
       <?php get_template_part( 'sidebar', get_post_format() ); ?>
       </div><!--#col-md-4 col-sm-4-->

       <div class="col-md-8 col-sm-8">
         <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

            get_template_part( 'page-single', get_post_format() );

             //Un-comment the code below to show comments on the posts
             //if ( comments_open() || get_comments_number() ) :
             //	  comments_template();
             //	endif;
            endwhile; endif;
        ?>
      </div><!--#col-md-8 col-sm-8-->
    </div><!--#col-12-->
  </div><!--#row-->
</div><!--#container-->

<?php get_footer(); ?>
