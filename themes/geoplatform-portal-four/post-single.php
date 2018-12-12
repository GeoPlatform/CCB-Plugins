<?php
/**
 * The template for single post content
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */
?>


<article class="m-article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="m-article__desc">
		<?php the_content('Read more...'); ?>
	</div>

	<!-- the rest of the content -->
	<div class="article__actions"><?php the_tags();?></div>
	<div class="blog-post-meta"><?php _e( 'Updated on', 'geoplatform-ccb'); ?> <?php the_modified_date(); ?></div>
</article><!-- post-the_ID();-->
