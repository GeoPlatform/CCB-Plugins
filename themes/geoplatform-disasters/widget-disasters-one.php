<?php
/**
 * Template Name: Widget Front Featured
 *
 * Widget for the front page, displays featured images in a tile format using a category.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
class Geopccb_Disasters_One_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopccb_disasters_one_widget', // Base ID
			esc_html__( 'GeoPlatform Disasters One', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Disasters widget one for list pages. Takes a category as input and displays its content, along with their featured images, in a tile format.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopccb_community_link', $instance) && isset($instance['geopccb_community_link']) && !empty($instance['geopccb_community_link']))
      $geopccb_community_link = apply_filters('widget_title', $instance['geopccb_community_link']);
		else
    	$geopccb_community_link = "Front Page";
		if (array_key_exists('geopccb_community_text', $instance) && isset($instance['geopccb_community_text']) && !empty($instance['geopccb_community_text']))
      $geopccb_community_text = apply_filters('widget_title', $instance['geopccb_community_text']);
		else
    	$geopccb_community_text = "";

    // Sets default image.
    $geopccb_category_image_default = get_template_directory_uri() . "/img/default-category-photo.jpeg";

    //Grabs the featured_appearance value and declares the trimmed post array.
    $geopccb_categories_trimmed = array();
    $geopccb_pages_trimmed = array();
    $geopccb_final_objects_array = array();

    $geopccb_category = get_cat_ID($geopccb_community_link);
    if ($geopccb_category == 0)
      $geopccb_category = get_cat_ID('Front Page');

    // Grabs all child categories of the parent one.
		$geopccb_categories = get_categories( array(
				'parent'     => $geopccb_category,
				'orderby'   => 'date',
				'order'     => 'DESC',
				'hide_empty'=> 0,
		) );

    // Checks the theme sorting setting and switches be default date or the custom method.
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

    // getting the posts and pages
    // Get view perms.
    $geop_ccb_private_perm = array('publish');
    if (current_user_can('read_private_pages'))
      $geop_ccb_private_perm = array('publish', 'private');

    // Sets the result types to post and page, including cat links if available
    $geop_ccb_post_types = array('post','page');
    if (post_type_exists('geopccb_catlink'))
      $geop_ccb_post_types = array('post','page','geopccb_catlink');

    // Grabs the posts.
    $geopccb_pages = get_posts(array(
      'post_type' => $geop_ccb_post_types,
      'orderby' => 'date',
      'order' => 'DESC',
      'numberposts' => -1,
      'cat'=> $geopccb_category,
      'post_status' => $geop_ccb_private_perm
    ) );

		// Filters out subcategory content, so only surface-level content remains.
		$geopccb_pages_cated = array();
		foreach($geopccb_pages as $geopccb_page){
			$geopccb_category_array = get_the_category($geopccb_page->ID);
			foreach($geopccb_category_array as $geopccb_category_attribute){
				if ($geopccb_category == $geopccb_category_attribute->term_id){
					array_push($geopccb_pages_cated, $geopccb_page);
					break;
				}
			}
		}

    // Mimics the old way of populating, but functional.
    // Assigns pages with valid priority values to the trimmed array.
    foreach($geopccb_pages_cated as $geopccb_page){
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
          // Swap community at indices: $j, $k
          list($geopccb_pages_trimmed[$j], $geopccb_pages_trimmed[$k]) = array($geopccb_pages_trimmed[$k], $geopccb_pages_trimmed[$j]);
        }
      }
    }

    // 2D array for final output. Also here are the post and category
    // value grab functiosn. Feed a post/category into the respective
    // function and it returns a 3-value array of the object values
    // in name-thumbnail-url format.
		if ( ! function_exists ( 'geopccb_add_featured_post' ) ) {
	    function geopccb_add_featured_post($geopccb_post){
	      $geopccb_temp_array = array();

				if(!empty($geopccb_post->geopccb_featcard_title))
					$geopccb_temp_array['name'] = $geopccb_post->geopccb_featcard_title;
				else
					$geopccb_temp_array['name'] = get_the_title($geopccb_post);

	      if (has_post_thumbnail($geopccb_post))
	        $geopccb_temp_array['thumb'] = get_the_post_thumbnail_url($geopccb_post, 'large');
	      else
	        $geopccb_temp_array['thumb'] = get_template_directory_uri() . "/img/default-category-photo.jpeg";

	      if (get_post_type($geopccb_post) == 'geopccb_catlink')
	        $geopccb_temp_array['url'] = $geopccb_post->geop_ccb_cat_link_url;
	      else
	        $geopccb_temp_array['url'] = get_the_permalink($geopccb_post);

	      return $geopccb_temp_array;
	    }
		}

		// Creates an array of key-values from a category input.
		if ( ! function_exists ( 'geopccb_add_featured_category' ) ) {
	    function geopccb_add_featured_category($geopccb_cat){
	      $geopccb_temp_array = array();

	      $geopccb_temp_array['name'] = $geopccb_cat->name;

	      if (get_term_meta($geopccb_cat->cat_ID, 'category-image-id', true))
	        $geopccb_temp_array['thumb'] = wp_get_attachment_image_src(get_term_meta($geopccb_cat->cat_ID, 'category-image-id', true), 'large')[0];
	      else
	        $geopccb_temp_array['thumb'] = get_template_directory_uri() . "/img/default-category-photo.jpeg";

	      $geopccb_temp_array['url'] = get_category_link( $geopccb_cat->term_id );

	      return $geopccb_temp_array;
	    }
		}

    // Bubble sorts the category array by cat_priority value.
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

    // Bubble sorts the pages and posts.
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

    // Final array construction based upon priority values.
    // Categories lose ties.
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

    $geopccb_featured_card_style = get_theme_mod('feature_controls', 'fade');
    $geopccb_featured_card_fade = "widget-featured-fade-zero";
    $geopccb_featured_card_outline = "";

    if ($geopccb_featured_card_style == 'fade' || $geopccb_featured_card_style == 'both')
      $geopccb_featured_card_fade = "widget-featured-fade-five";
    if ($geopccb_featured_card_style == 'outline' || $geopccb_featured_card_style == 'both')
      $geopccb_featured_card_outline = " widget-featured-fade-outline";

		// ELEMENTS
		if (empty($geopccb_community_text)){
	    echo "<div class='p-landing-page__community-menu featured-flex-parent'>";
	      for ($i = 0; $i < count($geopccb_final_objects_array); $i++) {
	        echo "<a class='m-tile m-tile--16x9 featured-flex-child' href='" . esc_url( $geopccb_final_objects_array[$i]['url'] ) . "' title='" . esc_attr( __( 'More information', 'geoplatform-ccb' ) ) . "'>";
	          echo "<div class='m-tile__thumbnail'>";
							echo "<img alt='" . esc_attr( __( $geopccb_final_objects_array[$i]['name'], 'geoplatform-ccb' ) ) . " Alternate Text' src='" . esc_url($geopccb_final_objects_array[$i]['thumb']) . "'>";
						echo "</div>";
	          echo "<div class='m-tile__body " . $geopccb_featured_card_fade . "'>";
	            echo "<div class='m-tile__heading". $geopccb_featured_card_outline . "'>" . esc_attr( __( strtoupper($geopccb_final_objects_array[$i]['name']), 'geoplatform-ccb' ) ) . "</div>";
	          echo "</div>";
	        echo "</a>";
	      }
	    echo "</div>";
		}
		else {
			echo "<div class='widget-featured-topborder'>";
				echo "<div class='m-article__heading m-article__heading--front-page' style='margin-bottom:0em;margin-top:1em;' title='Featured Pages'>" . __(sanitize_text_field($geopccb_community_text), 'geoplatform-ccb') . "</div>";
		    echo "<div class='p-landing-page__community-menu featured-flex-parent' style='border-top:0px;'>";
		      for ($i = 0; $i < count($geopccb_final_objects_array); $i++) {
		        echo "<a class='m-tile m-tile--16x9 featured-flex-child' href='" . esc_url( $geopccb_final_objects_array[$i]['url'] ) . "' title='" . esc_attr( __( 'More information', 'geoplatform-ccb' ) ) . "'>";
		          echo "<div class='m-tile__thumbnail'>";
								echo "<img alt='" . esc_attr( __( $geopccb_final_objects_array[$i]['name'], 'geoplatform-ccb' ) ) . " Alternate Text' src='" . esc_url($geopccb_final_objects_array[$i]['thumb']) . "'>";
							echo "</div>";
		          echo "<div class='m-tile__body " . $geopccb_featured_card_fade . "'>";
		            echo "<div class='m-tile__heading". $geopccb_featured_card_outline . "'>" . esc_attr( __( strtoupper($geopccb_final_objects_array[$i]['name']), 'geoplatform-ccb' ) ) . "</div>";
		          echo "</div>";
		        echo "</a>";
		      }
		    echo "</div>";
			echo "</div>";
		}
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopccb_community_link = ! empty( $instance['geopccb_community_link'] ) ? $instance['geopccb_community_link'] : 'Front Page';
		$geopccb_community_text = ! empty( $instance['geopccb_community_text'] ) ? $instance['geopccb_community_text'] : '';

		// HTML for the widget control box.
		echo "<p>";
			_e('Ensure to use a valid category name, not a slug.', 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
			echo "<label for='" . $this->get_field_id( 'geopccb_community_link' ) . "'>Source Category:</label>";
			echo "<input type='text' id='" . $this->get_field_id( 'geopccb_community_link' ) . "' name='" . $this->get_field_name( 'geopccb_community_link' ) . "' value='" . esc_attr( $geopccb_community_link ) . "' />";
		echo "</p>";
		echo "<p>";
			echo "<label for='" . $this->get_field_id( 'geopccb_community_text' ) . "'>Section Title:</label>";
			echo "<input type='text' id='" . $this->get_field_id( 'geopccb_community_text' ) . "' name='" . $this->get_field_name( 'geopccb_community_text' ) . "' value='" . esc_attr( $geopccb_community_text ) . "' />";
		echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopccb_community_link' ] = strip_tags( $new_instance[ 'geopccb_community_link' ] );
		$instance[ 'geopccb_community_text' ] = strip_tags( $new_instance[ 'geopccb_community_text' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopccb_register_disasters_one_widget() {
		register_widget( 'Geopccb_Disasters_One_Widget' );
}
add_action( 'widgets_init', 'geopccb_register_disasters_one_widget' );
