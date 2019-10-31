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

echo "<div class='l-body l-body--two-column'>";
	echo "<div class='l-body__main-column'>";

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

			// Adds categories categories with positive priority to another array which
			// will be utilized in processes going forward.
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

		// Time for posts, pages, and cat links.
		// Get view perms.
		$geop_ccb_private_perm = array('publish');
		if (current_user_can('read_private_pages'))
			$geop_ccb_private_perm = array('publish', 'private');

		// Grab relevant post types.
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
      $geopccb_pages_trimmed = $geopccb_pages;
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
    }

		// Array of final output arrays.
		$geopccb_final_objects_array = array();

		// Function for turning posts and pages into an array of relevant info.
		if ( ! function_exists ( 'geopccb_add_featured_post' ) ) {
	    function geopccb_add_featured_post($geopccb_post){
	      $geopccb_temp_array = array();

				if(!empty($geopccb_post->geopccb_featcard_title))
					$geopccb_temp_array['name'] = $geopccb_post->geopccb_featcard_title;
				else
					$geopccb_temp_array['name'] = get_the_title($geopccb_post);

	      if (has_post_thumbnail($geopccb_post))
	        $geopccb_temp_array['thumb'] = get_the_post_thumbnail_url($geopccb_post, 'medium');
	      else
	        $geopccb_temp_array['thumb'] = get_template_directory_uri() . "/img/img-404.png";

	      if (get_post_type($geopccb_post) == 'geopccb_catlink')
	        $geopccb_temp_array['url'] = $geopccb_post->geop_ccb_cat_link_url;
	      else
	        $geopccb_temp_array['url'] = get_the_permalink($geopccb_post);

				$geopccb_temp_array['desc'] = esc_attr(wp_strip_all_tags($geopccb_post->post_excerpt));

	      return $geopccb_temp_array;
	    }
		}

		// Creates an array of key-values from a category input.
		if ( ! function_exists ( 'geopccb_add_featured_category' ) ) {
	    function geopccb_add_featured_category($geopccb_cat){
	      $geopccb_temp_array = array();

	      $geopccb_temp_array['name'] = $geopccb_cat->name;

	      if (get_term_meta($geopccb_cat->cat_ID, 'category-image-id', true))
	        $geopccb_temp_array['thumb'] = wp_get_attachment_image_src(get_term_meta($geopccb_cat->cat_ID, 'category-image-id', true), 'medium')[0];
	      else
	        $geopccb_temp_array['thumb'] = get_template_directory_uri() . "/img/img-404.png";

	      $geopccb_temp_array['url'] = get_category_link( $geopccb_cat->term_id );

				$geopccb_temp_array['desc'] = esc_attr(wp_strip_all_tags($geopccb_cat->description));

	      return $geopccb_temp_array;
	    }
		}



		// If date is set, all remaining categories, then pages, are feed in order
		// through the info-parsing functions.
    if ($geopccb_featured_sort_format == 'date'){

			// Categories added.
      foreach ($geopccb_categories_trimmed as $geopccb_cat)
        array_push($geopccb_final_objects_array, geopccb_add_featured_category($geopccb_cat));

      // Pages added.
      foreach ($geopccb_pages_trimmed as $geopccb_post)
        array_push($geopccb_final_objects_array, geopccb_add_featured_post($geopccb_post));
    }
    else {

      // Final array construction based upon priority values. This algorithm
			// continues until both trimmed arrays are empty. With each cycle, it
			// grabs the priority value of the top item in each array (already sorted
			// by priority), and compares them. The lowest value one is popped out of
			// its array and fed to the appropriate parsing function declared above.
			// The returned data array is then added to the final output array.

      while (!empty($geopccb_pages_trimmed) || !empty($geopccb_categories_trimmed)){

        // Value checks and grabs.
        $geopccb_page_val = 0;
        if (!empty($geopccb_pages_trimmed))
          $geopccb_page_val = $geopccb_pages_trimmed[0]->geop_ccb_post_priority;
        $geopccb_cat_val = 0;
        if (!empty($geopccb_categories_trimmed))
          $geopccb_cat_val = get_term_meta($geopccb_categories_trimmed[0]->cat_ID, 'cat_priority', true);

        // Check and action. Page victory in first check, cats in else.
        if ($geopccb_cat_val == 0 || ($geopccb_page_val > 0 && ($geopccb_page_val < $geopccb_cat_val)))
          array_push($geopccb_final_objects_array, geopccb_add_featured_post(array_shift($geopccb_pages_trimmed)));
        else
          array_push($geopccb_final_objects_array, geopccb_add_featured_category(array_shift($geopccb_categories_trimmed)));
      }
    }

		// FINAL output!!
		for ($i = 0; $i < count($geopccb_final_objects_array); $i++){
			echo "<div class='m-article m-article--flex'>";
				echo "<a class='m-article__thumbnail is-16x9' href='" . esc_url( $geopccb_final_objects_array[$i]['url'] ) . "'>";
					echo "<img alt='" . esc_attr( __( $geopccb_final_objects_array[$i]['name'], 'geoplatform-ccb' ) )  . "' src='" . $geopccb_final_objects_array[$i]['thumb'] . "'>";
				echo "</a>";
				echo "<div class='m-article__body'>";
					echo "<a class='m-article__heading' href='" . esc_url( $geopccb_final_objects_array[$i]['url'] ) . "'>" . esc_attr( __( strtoupper($geopccb_final_objects_array[$i]['name']), 'geoplatform-ccb' ) ) . "</a>";
					echo "<div class='m-article__desc'>" . esc_attr( __( $geopccb_final_objects_array[$i]['desc']), 'geoplatform-ccb' ) . "</div>";
				echo "</div>";
			echo "</div>";
		}


  echo "</div>";
  get_template_part( 'sidebar', get_post_format() );
echo "</div>";
get_footer();
