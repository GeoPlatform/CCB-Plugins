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
			if ( has_post_thumbnail($geopccb_post) )
				$geopccb_archive_disp_thumb = get_the_post_thumbnail_url($geopccb_post, 'medium');
			elseif (get_post_type($geopccb_post) == 'post')
        $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-post.jpg";
			elseif (get_post_type($geopccb_post) == 'page')
	      $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-page.jpg";
			elseif (get_post_type($geopccb_post) == 'geopccb_catlink')
        $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-catlink.jpg";
			else
        $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-other.jpg";

			// To prevent entries overlapping their blocks, sets min height to match thumb.
			list($width, $height) = getimagesize($geopccb_archive_disp_thumb);
			$geopccb_archive_scaled_height = ((350 * $height) / $width) + 30;


			echo "<div class='m-article m-article--flex'>";
				echo "<a class='m-article__thumbnail is-16x9' href='" . get_the_permalink($geopccb_post) . "'>";
					echo "<img alt='Thumbnail for " . get_the_title($geopccb_post). "' src='" . $geopccb_archive_disp_thumb . "'>";
				echo "</a>";
				echo "<div class='m-article__body'>";
					echo "<a class='m-article__heading' href='" . get_the_permalink($geopccb_post) . "'>" . get_the_title($geopccb_post). "</a>";
					echo "<div class='m-article__desc'>" . get_the_date("F j, Y", $geopccb_post->ID) . "</div>";
					echo "<div class='m-article__desc'>" . esc_attr(wp_strip_all_tags($geopccb_post->post_excerpt)) . "</div>";
				echo "</div>";
			echo "</div>";

		}

  echo "</div>";
  get_template_part( 'sidebar', get_post_format() );
echo "</div>";
get_footer();
