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
		$geopportal_category = $wp_query->get_queried_object_id();

		// Grabs all child categories of the parent one.
		$geopportal_categories = get_categories( array(
				'parent'     => $geopportal_category,
				'orderby'   => 'name',
				'order'     => 'ASC',
				'hide_empty'=> 0,
		) );

		// Checks the theme sorting setting and switches be default date or the custom method.
		$geopportal_categories_trimmed = array();
		$geopportal_featured_sort_format = get_theme_mod('featured_appearance', 'date');
		if ($geopportal_featured_sort_format == 'date'){
			$geopportal_categories_trimmed = $geopportal_categories;
		}
		else {
			// Removes categories to be excluded from the featured output array.
			foreach($geopportal_categories as $geopportal_cat_iter){
				if (get_term_meta($geopportal_cat_iter->cat_ID, 'cat_priority', true) > 0)
					array_push($geopportal_categories_trimmed, $geopportal_cat_iter);
			}

			// Bubble sorts the remaining array by cat_priority value.
			$geopportal_categories_size = count($geopportal_categories_trimmed)-1;
			for ($i = 0; $i < $geopportal_categories_size; $i++) {
				for ($j = 0; $j < $geopportal_categories_size - $i; $j++) {
					$k = $j + 1;
					$geopportal_test_left = get_term_meta($geopportal_categories_trimmed[$j]->cat_ID, 'cat_priority', true);
					$geopportal_test_right = get_term_meta($geopportal_categories_trimmed[$k]->cat_ID, 'cat_priority', true);
					if ($geopportal_test_left > $geopportal_test_right) {
						// Swap elements at indices: $j, $k
						list($geopportal_categories_trimmed[$j], $geopportal_categories_trimmed[$k]) = array($geopportal_categories_trimmed[$k], $geopportal_categories_trimmed[$j]);
					}
				}
			}
		}

		// Outputs the categories in the same format as the posts.
		foreach ($geopportal_categories_trimmed as $geopportal_cat_iter){

			// Grabs default 404 image as thumb and overwrites if the post has one.
 			$geopportal_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
 			if (get_term_meta($geopportal_cat_iter->cat_ID, 'category-image-id', true))
 				$geopportal_archive_disp_thumb = wp_get_attachment_image_src(get_term_meta($geopportal_cat_iter->cat_ID, 'category-image-id', true), 'full')[0];

 			// To prevent entries overlapping their blocks, sets min height to match thumb.
 			list($width, $height) = getimagesize($geopportal_archive_disp_thumb);
 			$geopportal_archive_scaled_height = ((350 * $height) / $width) + 30;
    	?>

 			<div class="o-featured__primary" style="min-height:<?php echo $geopportal_archive_scaled_height ?>px">
 				<a class="is-linkless o-featured__heading" href="<?php echo esc_url( get_category_link( $geopportal_cat_iter->term_id ) ); ?>"><?php echo esc_attr($geopportal_cat_iter->name); ?></a>
 				<img alt="Article Heading" class="o-featured__thumb" src="<?php echo $geopportal_archive_disp_thumb ?>">
 				<div class="o-featured__desc">
 					<?php echo esc_attr($geopportal_cat_iter->description); ?>
 				</div>
 			</div>
		<?php }


		// Time for posts, pages, and cat links.
		// Get view perms.
		$geop_ccb_private_perm = array('publish');
		if (current_user_can('read_private_pages'))
			$geop_ccb_private_perm = array('publish', 'private');

    $geopportal_featured_sort_format = get_theme_mod('featured_appearance', 'date');
    $geopportal_pages_final = array();

    $geopportal_pages = get_posts(array(
      'post_type' => array('post','page','geopccb_catlink'),
      'orderby' => 'date',
      'order' => 'DESC',
      'numberposts' => -1,
			'cat'=> $geopportal_category,
			'post_status' => $geop_ccb_private_perm
    ));

    // Mimics the old way of populating, but functional. Grabs all pages.
    if ($geopportal_featured_sort_format == 'date'){
      $geopportal_pages_final = $geopportal_pages;
    }
    else {
      // Assigns pages with valid priority values to the trimmed array.
      $geopportal_pages_trimmed = array();
      foreach($geopportal_pages as $geopportal_page){
        if ($geopportal_page->geop_ccb_post_priority > 0)
          array_push($geopportal_pages_trimmed, $geopportal_page);
      }

      // Bubble sorts the resulting pages.
      $geopportal_pages_size = count($geopportal_pages_trimmed)-1;
      for ($i = 0; $i < $geopportal_pages_size; $i++) {
        for ($j = 0; $j < $geopportal_pages_size - $i; $j++) {
          $k = $j + 1;
          $geopportal_test_left = $geopportal_pages_trimmed[$j]->geop_ccb_post_priority;
          $geopportal_test_right = $geopportal_pages_trimmed[$k]->geop_ccb_post_priority;
          if ($geopportal_test_left > $geopportal_test_right) {
            // Swap elements at indices: $j, $k
            list($geopportal_pages_trimmed[$j], $geopportal_pages_trimmed[$k]) = array($geopportal_pages_trimmed[$k], $geopportal_pages_trimmed[$j]);
          }
        }
      }
      $geopportal_pages_final = $geopportal_pages_trimmed;
    }

 		foreach($geopportal_pages_final as $geopportal_post){

 			// Grabs default 404 image as thumb and overwrites if the post has one.
 			$geopportal_archive_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
 			if ( has_post_thumbnail($geopportal_post) )
 				$geopportal_archive_disp_thumb = get_the_post_thumbnail_url($geopportal_post);

 			// To prevent entries overlapping their blocks, sets min height to match thumb.
 			list($width, $height) = getimagesize($geopportal_archive_disp_thumb);
 			$geopportal_archive_scaled_height = ((350 * $height) / $width) + 30;

			// Sets the More Information link to point to the post or page, but replaces
			// it with the cat link's URL custom value if it is a cat link.
			$geopportal_link_url = get_the_permalink($geopportal_post);
			if (get_post_type($geopportal_post) == 'geopccb_catlink')
				$geopportal_link_url = esc_url($geopportal_post->geop_ccb_cat_link_url);
    	?>

 			<div class="o-featured__primary" style="min-height:<?php echo $geopportal_archive_scaled_height ?>px">
 				<a class="is-linkless o-featured__heading" href="<?php echo $geopportal_link_url; ?>"><?php echo get_the_title($geopportal_post) ?></a>
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
