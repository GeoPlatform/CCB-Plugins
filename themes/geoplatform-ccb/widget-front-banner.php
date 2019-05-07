<?php
/**
 * Template Name: Widget Front Banner
 *
 * Widget for the front page, displays the site's banner image with super-imposed text.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
class Geopccb_Front_Page_Banner_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopccb_front_banner_widget', // Base ID
			esc_html__( 'GeoPlatform Front Page Banner', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Banner widget for the front page. Takes an editor area as input and displays it along with the banner image.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

		$geopccb_wysiwyg_default = "<h1 style='text-align:center; color:white;'>Your Community Title</h1><p style='text-align:center; color:white;'>Create and manage your own Dynamic Digital Community on the GeoPlatform!</p>";

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopccb_banner_content', $instance) && isset($instance['geopccb_banner_content']) && !empty($instance['geopccb_banner_content']))
      $geopccb_banner_content = $instance['geopccb_banner_content'];
		else
      $geopccb_banner_content = $geopccb_wysiwyg_default;

		if (array_key_exists('geopccb_banner_cta_text', $instance) && isset($instance['geopccb_banner_cta_text']) && !empty($instance['geopccb_banner_cta_text']))
      $geopccb_banner_cta_text = apply_filters('widget_title', $instance['geopccb_banner_cta_text']);
		else
    	$geopccb_banner_cta_text = "";

		if (array_key_exists('geopccb_banner_cta_link', $instance) && isset($instance['geopccb_banner_cta_link']) && !empty($instance['geopccb_banner_cta_link']))
      $geopccb_banner_cta_link = apply_filters('widget_title', $instance['geopccb_banner_cta_link']);
		else
    	$geopccb_banner_cta_link = "https://www.geoplatform.gov/";

		// ELEMENTS
    echo "<div class='widget-banner-main'>";
			echo "<div class='widget-banner-sub'>";
				echo "<div class='widget-banner-container container'>";
					echo $geopccb_banner_content;
					if ( !empty($geopccb_banner_cta_text) ){
						echo "<div class='text-centered'>";
							echo "<a href='" . esc_url($geopccb_banner_cta_link) . "' class='btn btn-lg btn-white-outline'>";
								echo esc_html($geopccb_banner_cta_text);
							echo "</a>";
						echo "</div>";
					}
				echo "</div>";
				echo "<div class='u-mg-top--xxxlg'></div>";
			echo "</div>";
    echo "</div>";
	}

  // The admin side of the widget.
	public function form( $instance ) {

		$geopccb_wysiwyg_rand = rand(0, 999);
		$geopccb_wysiwyg_default = "<h1 style='text-align: center; color:white;'>Your Community Title</h1><p style='text-align: center; color:white;'>Create and manage your own Dynamic Digital Community on the GeoPlatform!</p>";
		$geopccb_wysiwyg_id = $this->get_field_id( 'wp_editor_' . $geopccb_wysiwyg_rand );
		$geopccb_wysiwyg_name = $this->get_field_name( 'wp_editor_' . $geopccb_wysiwyg_rand );
		$geopccb_wysiwyg_setting = array(
			'tinymce' => false,
			'media_buttons' => false,
			'textarea_rows' => 8,
      'textarea_name' => $geopccb_wysiwyg_name,
			'quicktags' => false,
		);

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopccb_banner_content = ! empty( $instance['geopccb_banner_content'] ) ? $instance['geopccb_banner_content'] : $geopccb_wysiwyg_default;
		$geopccb_banner_cta_text = ! empty( $instance['geopccb_banner_cta_text'] ) ? $instance['geopccb_banner_cta_text'] : '';
		$geopccb_banner_cta_link = ! empty( $instance['geopccb_banner_cta_link'] ) ? $instance['geopccb_banner_cta_link'] : 'https://www.geoplatform.gov/';

		// Sets the random value that needs to be passed for content to be saved.
		printf(
		  '<input type="hidden" id="%s" name="%s" value="%d" />',
		  $this->get_field_id( 'geopccb_wysiwyg_rand' ),
		  $this->get_field_name( 'geopccb_wysiwyg_rand' ),
		  $geopccb_wysiwyg_rand
		);

		// HTML for the widget control box.
		echo "<p>";
			_e('The text area can accept HTML and text elements. Please ensure that your HTML is tested before you input it into the Content Area text block.', 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_banner_content' ) . "'>Content Area Text:</label>";

			wp_editor( $geopccb_banner_content, $geopccb_wysiwyg_id, $geopccb_wysiwyg_setting );

    echo "</p>";

		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_banner_cta_text' ) . "'>Call to Action Text:</label>";
			echo "<input type='text' id='" . $this->get_field_id( 'geopccb_banner_cta_text' ) . "' name='" . $this->get_field_name( 'geopccb_banner_cta_text' ) . "' value='" . esc_attr( $geopccb_banner_cta_text ) . "' />";
    echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_banner_cta_link' ) . "'>Call to Action URL:</label>";
			echo "<input type='text' id='" . $this->get_field_id( 'geopccb_banner_cta_link' ) . "' name='" . $this->get_field_name( 'geopccb_banner_cta_link' ) . "' value='" . esc_attr( $geopccb_banner_cta_link ) . "' />";
    echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$geopccb_temp_rand = (int)$new_instance['geopccb_wysiwyg_rand'];
		$geopccb_editor_content = $new_instance['wp_editor_' . $geopccb_temp_rand];
		$instance[ 'geopccb_banner_content' ] = $geopccb_editor_content;

  	$instance[ 'geopccb_banner_cta_text' ] = strip_tags( $new_instance[ 'geopccb_banner_cta_text' ] );
		$instance[ 'geopccb_banner_cta_link' ] = strip_tags( $new_instance[ 'geopccb_banner_cta_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopccb_register_front_page_banner_widget() {
		register_widget( 'Geopccb_Front_Page_Banner_Widget' );
}
add_action( 'widgets_init', 'geopccb_register_front_page_banner_widget' );
