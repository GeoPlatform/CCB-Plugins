<?php
/**
 * Widget Name: Widget Sidebar Links
 *
 * Widget for the sidebar, displays a title and list of links from a category.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
class Geopportal_Side_Content_Links_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_side_content_link_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Category', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Content widget for the sidebar. Accepts a section title and a category slug to output its contents.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_side_cont_link_title', $instance) && isset($instance['geopportal_side_cont_link_title']) && !empty($instance['geopportal_side_cont_link_title']))
      $geopportal_side_cont_link_title = apply_filters('widget_title', $instance['geopportal_side_cont_link_title']);
		else
      $geopportal_side_cont_link_title = "Side Content";
		if (array_key_exists('geopportal_side_cont_link_link', $instance) && isset($instance['geopportal_side_cont_link_link']) && !empty($instance['geopportal_side_cont_link_link']))
      $geopportal_side_cont_link_link = apply_filters('widget_title', $instance['geopportal_side_cont_link_link']);
		else
    	$geopportal_side_cont_link_link = "";

    //Grabs the featured_appearance value and declares the trimmed post array.
    $geopportal_side_cont_link_sort_format = get_theme_mod('featured_appearance', 'date');

		$geopccb_featured_sort_order = "DESC";
		if ($geopportal_side_cont_link_sort_format == 'dateAsc')
			$geopccb_featured_sort_order = "ASC";

    $geopportal_pages_final = array();
		$geopportal_pages_sort = array();

		$geopportal_pages = get_posts(array(
			'orderby' => 'date',
			'order' => $geopccb_featured_sort_order,
			'numberposts' => -1,
			'post_status' => 'publish'
		) );

		// This list is then filtered for all pages in the input category,
		// ending the loop after 6 results.
		foreach($geopportal_pages as $geopportal_page){
			if (in_category($geopportal_side_cont_link_link, $geopportal_page))
				array_push($geopportal_pages_sort, $geopportal_page);
		}

    // Mimics the old way of populating, but functional.
    if ($geopportal_side_cont_link_sort_format != 'custom'){
			$geopportal_pages_final = $geopportal_pages_sort;
    }
    else {
			$geopportal_pages_trimmed = array();

      // Assigns pages with valid priority values to the trimmed array.
      foreach($geopportal_pages_sort as $geopportal_page){
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

		// SIDEBAR CONTENT LINKS
		echo "<article class='m-article'>";
      echo "<div class='m-article__heading'>" . __(sanitize_text_field($geopportal_side_cont_link_title), 'geoplatform-ccb') . "</div>";
      echo "<div class='m-article__desc m-list'>";

				foreach ($geopportal_pages_final as $geopportal_post){

					// Makes sure the excerpt is only one sentence.
					$geopportal_post_excerpt = $geopportal_post->post_excerpt;

					if (!empty($geopportal_post->post_excerpt)){
						$geopportal_post_explode = explode('.', $geopportal_post->post_excerpt);
						$geopportal_post_excerpt = $geopportal_post_explode[0] . ".";
					}

        echo "<div class='m-list__item'>";
          echo "<a class='is-linkless' href='" . get_the_permalink($geopportal_post) . "'>" . get_the_title($geopportal_post) . "</a>";
          echo "<div class='m-list__item__text'>" . __(sanitize_text_field($geopportal_post_excerpt), 'geoplatform-ccb') . "</div>";
        echo "</div>";

				}

      echo "</div>";
    echo "</article>";
  }

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopportal_side_cont_link_title = ! empty( $instance['geopportal_side_cont_link_title'] ) ? $instance['geopportal_side_cont_link_title'] : 'Themes';
		$geopportal_side_cont_link_link = ! empty( $instance['geopportal_side_cont_link_link'] ) ? $instance['geopportal_side_cont_link_link'] : '';

		// HTML for the widget control box.
		echo "<p>";
			_e('Ensure to use a valid category name, not a slug.', 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopportal_side_cont_link_title' ) . "'>Widget Title:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopportal_side_cont_link_title' ) . "' name='" . $this->get_field_name( 'geopportal_side_cont_link_title' ) . "' value='" . esc_attr( $geopportal_side_cont_link_title ) . "' />";
    echo "</p>";
		echo "<p>";
			echo "<label for='" . $this->get_field_id( 'geopportal_side_cont_link_link' ) . "'>Source Category:</label>";
			echo "<input type='text' id='" . $this->get_field_id( 'geopportal_side_cont_link_link' ) . "' name='" . $this->get_field_name( 'geopportal_side_cont_link_link' ) . "' value='" . esc_attr( $geopportal_side_cont_link_link ) . "' />";
		echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_side_cont_link_title' ] = strip_tags( $new_instance[ 'geopportal_side_cont_link_title' ] );
		$instance[ 'geopportal_side_cont_link_link' ] = strip_tags( $new_instance[ 'geopportal_side_cont_link_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_side_cont_link_widget() {
		register_widget( 'Geopportal_Side_Content_Links_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_side_cont_link_widget' );
