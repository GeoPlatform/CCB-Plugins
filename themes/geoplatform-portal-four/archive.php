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

		$geopportal_posts = get_posts(array(
			'post_type' => 'post',
			'orderby' => 'date',
			'order' => 'DSC',
			'numberposts' => -1,
		) );

		foreach($geopportal_posts as $geopportal_post){

			// Grabs default 404 image as thumb and overwrites if the post has one.
			$geopportal_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
			if ( has_post_thumbnail($geopportal_post) )
				$geopportal_archive_disp_thumb = get_the_post_thumbnail_url($geopportal_post);

			// To prevent entries overlapping their blocks, sets min height to match thumb.
			list($width, $height) = getimagesize($geopportal_archive_disp_thumb);
			$geopportal_archive_scaled_height = ((350 * $height) / $width) + 30;
   		?>

			<div class="o-featured__primary" style="min-height:<?php echo $geopportal_archive_scaled_height ?>px">
				<a class="is-linkless o-featured__heading" href="<?php echo get_the_permalink($geopportal_post); ?>"><?php echo get_the_title($geopportal_post) ?></a>
				<img alt="Article Heading" class="o-featured__thumb" src="<?php echo $geopportal_archive_disp_thumb ?>">
				<div class="o-featured__sub-heading"><?php echo wp_kses_post(get_post_meta($geopportal_post->ID, 'geop_ccb_custom_wysiwyg', true)); ?></div>
				<div><?php echo get_the_date("F j, Y", $geopportal_post->ID) ?></div>
				<div class="o-featured__desc">
					<?php echo esc_attr(wp_strip_all_tags($geopportal_post->post_excerpt)) ?>
				</div>
			</div>

		<?php } ?>

  </div>
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>
<?php get_footer(); ?>
