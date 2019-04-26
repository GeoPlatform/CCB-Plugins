<?php
/**
 * A GeoPlatform Category template
 *
 * @link https://codex.wordpress.org/Category_Templates
 *
 * @package GeoPlatform CCB
 *
 * @since 2.0.0
 */

get_header();
get_template_part( 'sub-header-cat', get_post_format() );

?>
<div class="l-body l-body--two-column">
	<div class="l-body__main-column">
		<?php

		//gets id of current category
		$geopccb_category = $wp_query->get_queried_object_id();

		// Grabs all child categories of the parent one.
		$geopccb_categories = get_categories( array(
				'parent'     => $geopccb_category,
				'orderby'   => 'name',
				'order'     => 'ASC',
				'hide_empty'=> 0,
		) );

		// Checks the theme sorting setting and switches be default date or the custom method.
		$geopccb_categories_trimmed = array();
		$geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');
		if ($geopccb_featured_sort_format == 'date'){
			$geopccb_categories_trimmed = $geopccb_categories;
		}
		else {
			// Removes categories to be excluded from the featured output array.
			foreach($geopccb_categories as $geopccb_cat_iter){
				if (get_term_meta($geopccb_cat_iter->cat_ID, 'cat_priority', true) > 0)
					array_push($geopccb_categories_trimmed, $geopccb_cat_iter);
			}

			// Bubble sorts the remaining array by cat_priority value.
			$geopccb_categories_size = count($geopccb_categories_trimmed)-1;
			for ($i = 0; $i < $geopccb_categories_size; $i++) {
				for ($j = 0; $j < $geopccb_categories_size - $i; $j++) {
					$k = $j + 1;
					$geopccb_test_left = get_term_meta($geopccb_categories_trimmed[$j]->cat_ID, 'cat_priority', true);
					$geopccb_test_right = get_term_meta($geopccb_categories_trimmed[$k]->cat_ID, 'cat_priority', true);
					if ($geopccb_test_left > $geopccb_test_right) {
						// Swap elements at indices: $j, $k
						list($geopccb_categories_trimmed[$j], $geopccb_categories_trimmed[$k]) = array($geopccb_categories_trimmed[$k], $geopccb_categories_trimmed[$j]);
					}
				}
			}
		}

		// Outputs the categories in the same format as the posts.
		foreach ($geopccb_categories_trimmed as $geopccb_cat_iter){

			// Grabs default 404 image as thumb and overwrites if the post has one.
 			$geopccb_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
 			if (get_term_meta($geopccb_cat_iter->cat_ID, 'category-image-id', true))
 				$geopccb_archive_disp_thumb = wp_get_attachment_image_src(get_term_meta($geopccb_cat_iter->cat_ID, 'category-image-id', true), 'full')[0];

 			// To prevent entries overlapping their blocks, sets min height to match thumb.
 			list($width, $height) = getimagesize($geopccb_archive_disp_thumb);
 			$geopccb_archive_scaled_height = ((350 * $height) / $width) + 30;
    	?>

			<div class="m-article m-article--flex">
				<a class="m-article__thumbnail is-16x9" href="<?php echo esc_url( get_category_link( $geopccb_cat_iter->term_id ) ); ?>">
					<img alt="Article Heading" src="<?php echo $geopccb_archive_disp_thumb ?>">
				</a>
				<div class="m-article__body">
					<a class="m-article__heading" href="<?php echo esc_url( get_category_link( $geopccb_cat_iter->term_id ) ); ?>"><?php echo esc_attr($geopccb_cat_iter->name); ?></a>
					<div class="m-article__desc"><?php echo esc_attr($geopccb_cat_iter->description); ?></div>
				</div>
			</div>
		<?php }


		// Time for posts, pages, and cat links.
		// Get view perms.
		$geop_ccb_private_perm = array('publish');
		if (current_user_can('read_private_pages'))
			$geop_ccb_private_perm = array('publish', 'private');

    $geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');
    $geopccb_pages_final = array();

    $geopccb_pages = get_posts(array(
      'post_type' => array('post','page','geopccb_catlink', 'community-post', 'ngda-post'),
      'orderby' => 'date',
      'order' => 'DESC',
      'numberposts' => -1,
			'cat'=> $geopccb_category,
			'post_status' => $geop_ccb_private_perm
    ));

    // Mimics the old way of populating, but functional. Grabs all pages.
    if ($geopccb_featured_sort_format == 'date'){
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
 			$geopccb_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
 			if ( has_post_thumbnail($geopccb_post) )
 				$geopccb_archive_disp_thumb = get_the_post_thumbnail_url($geopccb_post);

 			// To prevent entries overlapping their blocks, sets min height to match thumb.
 			list($width, $height) = getimagesize($geopccb_archive_disp_thumb);
 			$geopccb_archive_scaled_height = ((350 * $height) / $width) + 30;

			// Sets the More Information link to point to the post or page, but replaces
			// it with the cat link's URL custom value if it is a cat link.
			$geopccb_link_url = get_the_permalink($geopccb_post);
			if (get_post_type($geopccb_post) == 'geopccb_catlink')
				$geopccb_link_url = esc_url($geopccb_post->geop_ccb_cat_link_url);
    	?>

			<div class="m-article m-article--flex">
				<a class="m-article__thumbnail is-16x9" href="<?php echo $geopccb_link_url; ?>">
					<img alt="Article Heading" src="<?php echo $geopccb_archive_disp_thumb ?>">
				</a>
				<div class="m-article__body">
					<a class="m-article__heading" href="<?php echo $geopccb_link_url; ?>"><?php echo get_the_title($geopccb_post) ?></a>
					<div class="m-article__desc"><?php echo get_the_date("F j, Y", $geopccb_post->ID) ?></div>
					<div class="m-article__desc"><?php echo esc_attr(wp_strip_all_tags($geopccb_post->post_excerpt)) ?></div>
				</div>
			</div>
 		<?php } ?>

  </div>
  <?php get_template_part( 'sidebar', get_post_format() ); ?>
</div>
<?php get_footer(); ?>
