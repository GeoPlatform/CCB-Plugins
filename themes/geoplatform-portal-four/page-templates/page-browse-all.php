<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package GeoPlatform CCB
 *
 * Template Name: Browse All Posts
 *
 * @since 3.0.0
 */

get_header();
get_template_part( 'sub-header-post', get_post_format() );

?>
<div class="l-body l-body--two-column">
	<div class="l-body__main-column">
	<?php

		$geopportal_mainpage_disp_first_page = get_page_by_path(get_theme_mod('featured_primary_post'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_second_page = get_page_by_path(get_theme_mod('featured_secondary_one'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_third_page = get_page_by_path(get_theme_mod('featured_secondary_two'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_fourth_page = get_page_by_path(get_theme_mod('featured_secondary_three'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_fifth_page = get_page_by_path(get_theme_mod('featured_secondary_four'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_slug_array = array($geopportal_mainpage_disp_first_page->post_name, $geopportal_mainpage_disp_second_page->post_name, $geopportal_mainpage_disp_third_page->post_name, $geopportal_mainpage_disp_fourth_page->post_name, $geopportal_mainpage_disp_fifth_page->post_name);

		// Final sorted more content array.
		$geopportal_pages_sort = array($geopportal_mainpage_disp_first_page, $geopportal_mainpage_disp_second_page, $geopportal_mainpage_disp_third_page, $geopportal_mainpage_disp_fourth_page, $geopportal_mainpage_disp_fifth_page);

		// gets posts.
		$geopportal_pages = get_posts(array(
			'orderby' => 'date',
			'order' => 'DSC',
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_type' => 'post',
		) );

		// Filters posts already featured out of the featured content array.
		if (count($geopportal_pages) > 0){
			foreach($geopportal_pages as $geopportal_page){
				$geopportal_mainpage_temp_bool = true;
				for ($i = 0; $i < count($geopportal_mainpage_disp_slug_array) && $geopportal_mainpage_temp_bool; $i++){
					if ($geopportal_page->post_name == $geopportal_mainpage_disp_slug_array[$i]){
						$geopportal_mainpage_temp_bool = false;
					}
				}
				if ($geopportal_mainpage_temp_bool)
					array_push($geopportal_pages_sort, $geopportal_page);
			}
		}

 		foreach($geopportal_pages_sort as $geopportal_post){

 			// Grabs default 404 image as thumb and overwrites if the post has one.
 			$geopportal_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
 			if ( has_post_thumbnail($geopportal_post) )
 				$geopportal_archive_disp_thumb = get_the_post_thumbnail_url($geopportal_post);

 			// To prevent entries overlapping their blocks, sets min height to match thumb.
 			list($width, $height) = getimagesize($geopportal_archive_disp_thumb);
 			$geopportal_archive_scaled_height = ((350 * $height) / $width) + 30;
    	?>

			<div class="m-article m-article--flex">
				<a class="m-article__thumbnail is-16x9" href="<?php echo get_the_permalink($geopportal_post); ?>">
					<img alt="Article Heading" src="<?php echo $geopportal_archive_disp_thumb ?>">
				</a>
				<div class="m-article__body">
					<a class="m-article__heading" href="<?php echo get_the_permalink($geopportal_post); ?>"><?php echo get_the_title($geopportal_post) ?></a>
					<div class="m-article__desc"><?php echo get_the_date("F j, Y", $geopportal_post->ID) ?></div>
					<div class="m-article__desc"><?php echo esc_attr(wp_strip_all_tags($geopportal_post->post_excerpt)) ?></div>
				</div>
			</div>

 		<?php } ?>

  </div>
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>
<?php get_footer(); ?>
