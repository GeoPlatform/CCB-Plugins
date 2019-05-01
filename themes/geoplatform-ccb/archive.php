<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform CCB
 *
 * Template Name: Archive
 *
 * @since 3.0.0
 */

get_header();
get_template_part( 'sub-header-post', get_post_format() );

?>
<div class="l-body l-body--two-column">
	<div class="l-body__main-column">
	<?php

		$geopccb_posts = get_posts(array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DSC',
			'numberposts' => -1,
		) );

		foreach($geopccb_posts as $geopccb_post){

			// Grabs default 404 image as thumb and overwrites if the post has one.
			$geopccb_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
			if ( has_post_thumbnail($geopccb_post) )
				$geopccb_archive_disp_thumb = get_the_post_thumbnail_url($geopccb_post);

			// To prevent entries overlapping their blocks, sets min height to match thumb.
			list($width, $height) = getimagesize($geopccb_archive_disp_thumb);
			$geopccb_archive_scaled_height = ((350 * $height) / $width) + 30;
   		?>

			<div class="m-article m-article--flex">
				<a class="m-article__thumbnail is-16x9" href="<?php echo get_the_permalink($geopccb_post); ?>">
					<img alt="Article Heading" src="<?php echo $geopccb_archive_disp_thumb ?>">
				</a>
				<div class="m-article__body">
					<a class="m-article__heading" href="<?php echo get_the_permalink($geopccb_post); ?>"><?php echo get_the_title($geopccb_post) ?></a>
					<div class="m-article__desc"><?php echo get_the_date("F j, Y", $geopccb_post->ID) ?></div>
					<div class="m-article__desc"><?php echo esc_attr(wp_strip_all_tags($geopccb_post->post_excerpt)) ?></div>
				</div>
			</div>

		<?php } ?>


  </div>
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>
<?php get_footer(); ?>
