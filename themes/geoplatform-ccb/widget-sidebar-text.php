<?php
/**
 * Widget Name: Widget Sidebar Text
 *
 * Widget for the sidebar, displays a title and WYSIWYG text.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
class Geopportal_Side_Content_Text_Widget extends WP_Widget {

	// Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_side_content_text_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Text', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Text widget for the sidebar. Allows text input into the associated box to appear alongside a title in the sidebar.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

	// Handles the widget output.
	public function widget( $args, $instance ) {

		$geopccb_side_wysiwyg_default = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

		// Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_side_cont_text_title', $instance) && isset($instance['geopportal_side_cont_text_title']) && !empty($instance['geopportal_side_cont_text_title']))
      $geopportal_side_cont_text_title = apply_filters('widget_title', $instance['geopportal_side_cont_text_title']);
		else
      $geopportal_side_cont_text_title = "Side Content";
		if (array_key_exists('geopportal_side_cont_text_content', $instance) && isset($instance['geopportal_side_cont_text_content']) && !empty($instance['geopportal_side_cont_text_content']))
      $geopportal_side_cont_text_content = apply_filters('widget_title', $instance['geopportal_side_cont_text_content']);
		else
      $geopportal_side_cont_text_content = $geopccb_side_wysiwyg_default;


		// SIDEBAR CONTENT TEXT SECTION
		echo "<article class='m-article'>";
      echo "<div class='m-article__heading'>" . sanitize_text_field($geopportal_side_cont_text_title) . "</div>";
      echo "<div class='m-article__desc'>";
        echo esc_html($geopportal_side_cont_text_content);
      echo "</div>";
    echo "</article>";
	}

	// The admin side of the widget
	public function form( $instance ) {

		$geopccb_side_wysiwyg_rand = rand(0, 999);
		$geopccb_side_wysiwyg_default = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

		$geopccb_side_wysiwyg_id = $this->get_field_id( 'wp_editor_' . $geopccb_side_wysiwyg_rand );
		$geopccb_side_wysiwyg_name = $this->get_field_name( 'wp_editor_' . $geopccb_side_wysiwyg_rand );
		$geopccb_side_wysiwyg_setting = array(
			'tinymce' => false,
			'media_buttons' => false,
			'textarea_rows' => 8,
      'textarea_name' => $geopccb_side_wysiwyg_name,
			'quicktags' => false,
		);
		// Input boxes.
		$geopportal_side_cont_text_title = ! empty( $instance['geopportal_side_cont_text_title'] ) ? $instance['geopportal_side_cont_text_title'] : 'Side Content';
		$geopportal_side_cont_text_content = ! empty( $instance['geopportal_side_cont_text_content'] ) ? $instance['geopportal_side_cont_text_content'] : $geopccb_side_wysiwyg_default;

		printf(
		  '<input type="hidden" id="%s" name="%s" value="%d" />',
		  $this->get_field_id( 'geopccb_side_wysiwyg_rand' ),
		  $this->get_field_name( 'geopccb_side_wysiwyg_rand' ),
		  $geopccb_side_wysiwyg_rand
		);

		// HTML for the widget control box.
		echo "<p>";
			_e('Input a title and text for a text block that you would like to see in the sidebar.', 'geoplatform-ccb');
		echo "</p>";
    echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopportal_side_cont_text_title' ) . "'>Main Title:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopportal_side_cont_text_title' ) . "' name='" . $this->get_field_name( 'geopportal_side_cont_text_title' ) . "' value='" . esc_attr( $geopportal_side_cont_text_title ) . "' />";
    echo "</p>";
		echo "<p>";
			echo "<label for='" . $this->get_field_id( 'geopportal_side_cont_text_content' ) . "'>Content Text:</label><br>";

			wp_editor( $geopportal_side_cont_text_content, $geopccb_side_wysiwyg_id, $geopccb_side_wysiwyg_setting );

		echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$geopccb_temp_rand = (int)$new_instance['geopccb_side_wysiwyg_rand'];
		$geopccb_editor_content = $new_instance['wp_editor_' . $geopccb_temp_rand];
		$instance[ 'geopportal_side_cont_text_content' ] = $geopccb_editor_content;

		$instance[ 'geopportal_side_cont_text_title' ] = strip_tags( $new_instance[ 'geopportal_side_cont_text_title' ] );

	  return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_side_content_text_widget() {
		register_widget( 'Geopportal_Side_Content_Text_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_side_content_text_widget' );
