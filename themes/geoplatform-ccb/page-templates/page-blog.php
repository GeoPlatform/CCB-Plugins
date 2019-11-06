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

?>
<div class="l-body l-body--two-column">
	<div class="l-body__main-column">
	<?php

	$args = array(
		'posts_per_page' => get_theme_mod('blogcount_controls', 5),
		'paged' => $paged,
	);
	$wp_query = new WP_Query(); $wp_query->query($args);
	while ($wp_query->have_posts()) : $wp_query->the_post();

		// Grabs default 404 image as thumb and overwrites if the post has one.
 		if ( has_post_thumbnail() )
 			$geopccb_archive_disp_thumb = get_the_post_thumbnail_url($post->ID, 'medium');
		elseif (get_post_type() == 'post')
      $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-post.jpg";
		elseif (get_post_type() == 'page')
      $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-page.jpg";
		elseif (get_post_type() == 'geopccb_catlink')
      $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-catlink.jpg";
		else
      $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-other.jpg";
    ?>

		<div class="m-article m-article--flex">
			<a class="m-article__thumbnail is-16x9" href="<?php the_permalink(); ?>">
				<img alt="Thumbnail for <?php the_title() ?>" src="<?php echo $geopccb_archive_disp_thumb ?>">
			</a>
			<div class="m-article__body">
				<a class="m-article__heading" href="<?php the_permalink(); ?>"><?php the_title() ?></a>
				<div class="m-article__desc"><?php echo get_the_date("F j, Y") ?></div>
				<div class="m-article__desc"><?php echo esc_attr(wp_strip_all_tags(get_the_excerpt())) ?></div>
			</div>
		</div>

	<?php
	endwhile;
	if ($paged > 1) { ?>

		<nav id="nav-posts">
			<br />
			<div class="prev"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
			<div class="next"><?php previous_posts_link('Newer Posts &raquo;'); ?></div>
		</nav>

		<?php } else { ?>

		<nav id="nav-posts">
			<br />
			<div class="prev"><?php next_posts_link('&laquo; Previous Posts'); ?></div>
		</nav>

		<?php }
		wp_reset_postdata(); ?>

  </div>
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>
<?php get_footer(); ?>
