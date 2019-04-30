<?php
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

		$geopccb_wysiwyg_default = "<h1 style='text-align: center; color:white;'>Your Community Title</h1><p style='text-align: center'>Create and manage your own Dynamic Digital Community on the GeoPlatform!</p>";

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopccb_banner_content', $instance) && isset($instance['geopccb_banner_content']) && !empty($instance['geopccb_banner_content']))
      $geopccb_banner_content = $instance['geopccb_banner_content'];
		else
      $geopccb_banner_content = $geopccb_wysiwyg_default;

		// if (array_key_exists('geopccb_community_link', $instance) && isset($instance['geopccb_community_link']) && !empty($instance['geopccb_community_link']))
    //   $geopccb_community_link = apply_filters('widget_title', $instance['geopccb_community_link']);
		// else
    // 	$geopccb_community_link = "Front Page";

    // Sets default image.
    // $geopccb_category_image_default = get_template_directory_uri() . "/img/default-category-photo.jpeg";

		// ELEMENTS
    echo "<div class='widget-banner-main'>";
			echo "<div class='widget-banner-sub'>";
				echo "<div class='widget-banner-container container'>";
				echo $geopccb_banner_content;
        // echo "<a class='m-tile m-tile--16x9' href='" . esc_url( $geopccb_final_objects_array[$i]['url'] ) . "' title='" . esc_attr( __( 'More information', 'geoplatform-ccb' ) ) . "'>";
        //   echo "<div class='m-tile__thumbnail'><img alt='" . $geopccb_category_image_default . "' src='" . esc_url($geopccb_final_objects_array[$i]['thumb']) . "'></div>";
        //   echo "<div class='m-tile__body'>";
        //     echo "<div class='m-tile__heading'>" . esc_attr( __( strtoupper($geopccb_final_objects_array[$i]['name']), 'geoplatform-ccb' ) ) . "</div>";
        //     echo "<div class='m-tile-desc'>";
        //       echo esc_attr( __( $geopccb_final_objects_array[$i]['excerpt'], 'geoplatform-ccb' ) );
        //     echo "</div>";
        //   echo "</div>";
        // echo "</a>";
				echo "</div>";
			echo "</div>";
    echo "</div>";
	}

  // The admin side of the widget.
	public function form( $instance ) {

		$geopccb_wysiwyg_rand = rand(0, 999);
		$geopccb_wysiwyg_default = "<h1 style='text-align: center; color:white;'>Your Community Title</h1><p style='text-align: center'>Create and manage your own Dynamic Digital Community on the GeoPlatform!</p>";
		// $geopccb_wysiwyg_content_grab = ! empty( $instance['geopccb_banner_content'] ) ? $instance['geopccb_banner_content'] : $geopccb_wysiwyg_default;
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
		// $geopccb_community_link = ! empty( $instance['geopccb_community_link'] ) ? $instance['geopccb_community_link'] : '';


		printf(
		  '<input type="hidden" id="%s" name="%s" value="%d" />',
		  $this->get_field_id( 'geopccb_wysiwyg_rand' ),
		  $this->get_field_name( 'geopccb_wysiwyg_rand' ),
		  $geopccb_wysiwyg_rand
		);

		// HTML for the widget control box.
		echo "<p>";
			_e('Ensure to use a valid category name, not a slug.', 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_banner_content' ) . "'>Section Title:</label>";

			wp_editor( $geopccb_banner_content, $geopccb_wysiwyg_id, $geopccb_wysiwyg_setting );

			// echo "<input type='text' id='" . $this->get_field_id( 'geopccb_banner_content' ) . "' name='" . $this->get_field_name( 'geopccb_banner_content' ) . "' value='" . esc_attr( $geopccb_banner_content ) . "' />";
    echo "</p>";


	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$geopccb_temp_rand = (int)$new_instance['geopccb_wysiwyg_rand'];
		$geopccb_editor_content = $new_instance['wp_editor_' . $geopccb_temp_rand];
		$instance[ 'geopccb_banner_content' ] = $geopccb_editor_content;
		// $instance[ 'geopccb_community_link' ] = strip_tags( $new_instance[ 'geopccb_community_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopccb_register_front_page_banner_widget() {
		register_widget( 'Geopccb_Front_Page_Banner_Widget' );
}
add_action( 'widgets_init', 'geopccb_register_front_page_banner_widget' );