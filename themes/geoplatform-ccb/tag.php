<?php
/**
 * The template for displaying Tag the archive
 *
 * @package GeoPlatform CCB
 *
 * @link https://codex.wordpress.org/Tag_Templates
 *
 * @since 3.0.0
 */

get_header();
get_template_part( 'sub-header-tag', get_post_format() );

$geopportal_sidebar_vis = get_theme_mod('sidebar_controls', 'on');

if ($geopportal_sidebar_vis == 'on')
	echo "<div class='l-body l-body--two-column'>";
else
	echo "<div class='l-body l-body--one-column'>";

	echo "<div class='l-body__main-column'>";

		//gets id of current tag
		$geopccb_tag = get_queried_object()->slug;

		// Time for posts, pages, and cat links.
		// Get view perms.
		$geop_ccb_private_perm = array('publish');
		if (current_user_can('read_private_pages'))
			$geop_ccb_private_perm = array('publish', 'private');

    $geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');

		$geopccb_featured_sort_order = "DESC";
		if ($geopccb_featured_sort_format == 'dateAsc')
			$geopccb_featured_sort_order = "ASC";

    $geopccb_pages_final = array();

    $geopccb_pages = get_posts(array(
      'post_type' => array('post','page','geopccb_catlink', 'community-post', 'ngda-post'),
      'orderby' => 'date',
      'order' => $geopccb_featured_sort_order,
      'numberposts' => -1,
      'tag'=> $geopccb_tag,
			'post_status' => $geop_ccb_private_perm
    ));

    // Mimics the old way of populating, but functional. Grabs all pages.
    if ($geopccb_featured_sort_format != 'custom'){
      $geopccb_pages_final = $geopccb_pages;
    }
    else {
      // Assigns pages with valid priority values to the trimmed array.
      $geopccb_pages_trimmed = array();
      foreach($geopccb_pages as $geopccb_page){
        if ($geopccb_page->geop_ccb_post_priority > 0)
          array_push($geopccb_pages_trimmed, $geopccb_page);
      }

      // Bubble sorts the resulting pages.
      $geopccb_pages_size = count($geopccb_pages_trimmed)-1;
      for ($i = 0; $i < $geopccb_pages_size; $i++) {
        for ($j = 0; $j < $geopccb_pages_size - $i; $j++) {
          $k = $j + 1;
          $geopccb_test_left = $geopccb_pages_trimmed[$j]->geop_ccb_post_priority;
          $geopccb_test_right = $geopccb_pages_trimmed[$k]->geop_ccb_post_priority;
          if ($geopccb_test_left > $geopccb_test_right) {
            // Swap elements at indices: $j, $k
            list($geopccb_pages_trimmed[$j], $geopccb_pages_trimmed[$k]) = array($geopccb_pages_trimmed[$k], $geopccb_pages_trimmed[$j]);
          }
        }
      }
      $geopccb_pages_final = $geopccb_pages_trimmed;
    }

 		foreach($geopccb_pages_final as $geopccb_post){

 			// Grabs default 404 image as thumb and overwrites if the post has one.
 			if ( has_post_thumbnail($geopccb_post) )
 				$geopccb_archive_disp_thumb = get_the_post_thumbnail_url($geopccb_post, 'medium');
			else
	      $geopccb_archive_disp_thumb = get_template_directory_uri() . "/img/default-featured.jpg";

 			// To prevent entries overlapping their blocks, sets min height to match thumb.
 			list($width, $height) = getimagesize($geopccb_archive_disp_thumb);
 			$geopccb_archive_scaled_height = ((350 * $height) / $width) + 30;

			// Sets the More Information link to point to the post or page, but replaces
			// it with the cat link's URL custom value if it is a cat link.
			$geopccb_link_url = get_the_permalink($geopccb_post);
			if (get_post_type($geopccb_post) == 'geopccb_catlink')
				$geopccb_link_url = esc_url($geopccb_post->geop_ccb_cat_link_url);

			echo "<div class='m-article m-article--flex'>";
				echo "<a class='m-article__thumbnail is-16x9' href='" . $geopccb_link_url . "'>";
					echo "<img alt='Article Heading' src='" . $geopccb_archive_disp_thumb . "'>";
				echo "</a>";
				echo "<div class='m-article__body'>";
					echo "<a class='m-article__heading' href='" . $geopccb_link_url . "'>" . get_the_title($geopccb_post) . "</a>";
					echo "<div class='m-article__desc'>" . get_the_date('F j, Y', $geopccb_post->ID) . "</div>";
					echo "<div class='m-article__desc'>" . esc_attr(wp_strip_all_tags($geopccb_post->post_excerpt)) . "</div>";
				echo "</div>";
			echo "</div>";
 		}

  echo "</div>";

	if ($geopportal_sidebar_vis == 'on')
  	get_template_part( 'sidebar', get_post_format() );

echo "</div>";
get_footer();
