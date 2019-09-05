<?php
/**
 * Template Name: Widget Sidebar Preview
 *
 * Widget for the sidebar, displays the featured image of the current post.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
class Geopportal_Side_Content_Preview_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_side_content_prev_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Image', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( "GeoPlatform Image widget for the sidebar. Will display the current post's featured image. An optional title input is also present.", 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_side_cont_prev_title', $instance) && isset($instance['geopportal_side_cont_prev_title']) && !empty($instance['geopportal_side_cont_prev_title']))
      $geopportal_side_cont_prev_title = apply_filters('widget_title', $instance['geopportal_side_cont_prev_title']);
		else
      $geopportal_side_cont_prev_title = "";

		$geopporatl_current_post = get_queried_object();

		$geopportal_side_cont_prev_thumb = get_template_directory_uri() . '/img/img-404.png';
		$geopportal_side_cont_prev_text = 'There is no image here.';

		if ($geopporatl_current_post){
			if ( has_post_thumbnail($geopporatl_current_post) )
				$geopportal_side_cont_prev_thumb = get_the_post_thumbnail_url($geopporatl_current_post);
			if ( is_singular($geopporatl_current_post) )
				$geopportal_side_cont_prev_text = get_the_title($geopporatl_current_post);
		}

		// SIDEBAR CONTENT PREVIEW
		echo "<article class='m-article'>";
      echo "<div class='m-article__heading'>" . __(sanitize_text_field($geopportal_side_cont_prev_title), 'geoplatform-ccb') . "</div>";
      echo "<div class='m-article__desc' style='max-width:320px'>";
        echo "<img src='" . $geopportal_side_cont_prev_thumb . "' title='" . $geopportal_side_cont_prev_text . "'>";
      echo "</div>";
    echo "</article>";
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopportal_side_cont_prev_title = ! empty( $instance['geopportal_side_cont_prev_title'] ) ? $instance['geopportal_side_cont_prev_title'] : '';

 		// HTML for the widget control box.
		echo "<p>";
			_e('The featured image of the current page will be displayed automatically. You may insert an optional title, but it will display on all pages with a sidebar.', 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopportal_side_cont_prev_title' ) . "'>Widget Title:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopportal_side_cont_prev_title' ) . "' name='" . $this->get_field_name( 'geopportal_side_cont_prev_title' ) . "' value='" . esc_attr( $geopportal_side_cont_prev_title ) . "' />";
    echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_side_cont_prev_title' ] = strip_tags( $new_instance[ 'geopportal_side_cont_prev_title' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_side_cont_prev_widget() {
		register_widget( 'Geopportal_Side_Content_Preview_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_side_cont_prev_widget' );
