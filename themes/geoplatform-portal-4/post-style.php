<?php
/**
 * The template for single post content
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */
?>

<div class="l-body__main-column" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_content('Read more...'); ?>
</div>
