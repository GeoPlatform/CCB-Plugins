<?php
/**
 * Template Name: Full Width Page
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
       <div class="loop">
         <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

       get_template_part( 'page-single', get_post_format() );

       //Un-comment the code below to show comments on the posts
       //if ( comments_open() || get_comments_number() ) :
       //	  comments_template();
       //	endif;
     endwhile; endif;
     ?>
      </div><!--#loop-->
     </div><!--#row-->
   </div><!--#container-->


<?php get_footer(); ?>
