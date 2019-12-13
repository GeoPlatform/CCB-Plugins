<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform CCB
 *
 * Template Name: Blog
 *
 * @since 3.0.0
 */

get_header();
get_template_part( 'sub-header-post', get_post_format() );

$geopportal_sidebar_vis = get_theme_mod('sidebar_controls', 'on');

if ($geopportal_sidebar_vis == 'on')
	echo "<div class='l-body l-body--two-column'>";
else
	echo "<div class='l-body l-body--one-column'>";

	echo "<div class='l-body__main-column'>";

	$args = array(
		'posts_per_page' => get_theme_mod('blogcount_controls', 5),
		'paged' => $paged,
	);
	$wp_query = new WP_Query(); $wp_query->query($args);
	while ($wp_query->have_posts()) : $wp_query->the_post();

		// Grabs default 404 image as thumb and overwrites if the post has one.
 		if ( has_post_thumbnail() )
 			$geopccb_archive_disp_thumb = get_the_post_thumbnail_url($post->ID, 'medium');
		else
      $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-featured.jpg";

		echo "<div class='m-article m-article--flex'>";
			echo "<a class='m-article__thumbnail is-16x9' href='" . get_the_permalink() . "'>";
				echo "<img alt='Thumbnail for " .  get_the_title() . "' src='" . $geopccb_archive_disp_thumb . "'>";
			echo "</a>";
			echo "<div class='m-article__body'>";
				echo "<a class='m-article__heading' href='" . get_the_permalink() . "'>" . get_the_title() . "</a>";
				echo "<div class='m-article__desc'>" . get_the_date("F j, Y") . "</div>";
				echo "<div class='m-article__desc'>" . esc_attr(wp_strip_all_tags(get_the_excerpt())) . "</div>";
			echo "</div>";
		echo "</div>";

	endwhile;
	if ($paged > 1) {

		echo "<nav id='nav-posts'>";
			echo "<br />";
			echo "<div class='prev'>" . next_posts_link('&laquo; Previous Posts') . "</div>";
			echo "<div class='next'>" . previous_posts_link('Newer Posts &raquo;') . "</div>";
		echo "</nav>";

		} else {

		echo "<nav id='nav-posts'>";
			echo "<br />";
			echo "<div class='prev'>" . next_posts_link('&laquo; Previous Posts') . "</div>";
		echo "</nav>";

		}
		wp_reset_postdata();

	echo "</div>";

	if ($geopportal_sidebar_vis == 'on')
  	get_template_part( 'sidebar', get_post_format() );

echo "</div>";
get_footer(); ?>
