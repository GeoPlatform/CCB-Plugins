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
get_header();
get_template_part( 'sub-header-post', get_post_format() );
?>

<div class="l-body l-body--one-column">
  <div class="l-body__main-column">
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        get_template_part( 'post-single', get_post_format() );

        //Un-comment the code below to show comments on the posts
        //if ( comments_open() || get_comments_number() ) :
        //	  comments_template();
        //	endif;
      endwhile; endif;
      ?>
  </div>
</div>
<?php get_footer(); ?>
