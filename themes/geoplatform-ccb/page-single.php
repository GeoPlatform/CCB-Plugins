<?php
/**
 * The template for page content 
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 * 
 * @package GeoPlatform CCB
 * 
 * @since 3.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<br />


  <?php if ( has_post_thumbnail() ) {
    the_post_thumbnail();
  } ?>

	<?php the_content(); ?>

<!-- the rest of the content -->
<h5><?php the_tags();?></h5>
<h5 class="blog-post-meta"><?php _e( 'Updated', 'geoplatform-ccb'); ?> <?php the_modified_date(); ?></h5>

</article><!-- post-the_ID();-->
