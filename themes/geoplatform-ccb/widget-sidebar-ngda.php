<?php
/**
 * Template Name: Widget Sidebar NGDA
 *
 * Widget for the sidebar, displays an NGDA info card.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
class Geopccb_Side_Content_NGDA_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopccb_side_ngda_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar NGDA', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform NGDA widget for the sidebar. Takes NGDA theme information and displays it in the sidebar.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopccb_ngda_name', $instance) && isset($instance['geopccb_ngda_name']) && !empty($instance['geopccb_ngda_name']))
      $geopccb_ngda_name = apply_filters('widget_title', $instance['geopccb_ngda_name']);
		else
    	$geopccb_ngda_name = "NGDA Theme Name";
		if (array_key_exists('geopccb_ngda_type', $instance) && isset($instance['geopccb_ngda_type']) && !empty($instance['geopccb_ngda_type']))
      $geopccb_ngda_type = apply_filters('widget_title', $instance['geopccb_ngda_type']);
		else
    	$geopccb_ngda_type = "NGDA";
		if (array_key_exists('geopccb_ngda_sponsor', $instance) && isset($instance['geopccb_ngda_sponsor']) && !empty($instance['geopccb_ngda_sponsor']))
      $geopccb_ngda_sponsor = apply_filters('widget_title', $instance['geopccb_ngda_sponsor']);
		else
    	$geopccb_ngda_sponsor = "FGDC";
		if (array_key_exists('geopccb_ngda_email', $instance) && isset($instance['geopccb_ngda_email']) && !empty($instance['geopccb_ngda_email']))
      $geopccb_ngda_email = apply_filters('widget_title', $instance['geopccb_ngda_email']);
		else
    	$geopccb_ngda_email = "servicedesk@geoplatform.gov";
		if (array_key_exists('geopccb_ngda_agency', $instance) && isset($instance['geopccb_ngda_agency']) && !empty($instance['geopccb_ngda_agency']))
      $geopccb_ngda_agency = apply_filters('widget_title', $instance['geopccb_ngda_agency']);
		else
    	$geopccb_ngda_agency = "Theme lead agency info needed.";
		if (array_key_exists('geopccb_ngda_lead', $instance) && isset($instance['geopccb_ngda_lead']) && !empty($instance['geopccb_ngda_lead']))
      $geopccb_ngda_lead = apply_filters('widget_title', $instance['geopccb_ngda_lead']);
		else
    	$geopccb_ngda_lead = "Theme lead names needed.";

		// ELEMENTS
		echo "<article class='m-article'>";
      echo "<div class='m-article__heading'><span class='fas fa-star'></span>  " . esc_attr($geopccb_ngda_name, 'geoplatform-ccb') . "</div>";
      echo "<div class='m-article__desc'>";
				echo "<br>";
				echo "<p><strong>Community Type: </strong>" . esc_attr($geopccb_ngda_type, 'geoplatform-ccb') . "</p>";
				echo "<p><strong>Sponsor: </strong>" . esc_attr($geopccb_ngda_sponsor, 'geoplatform-ccb') . "</p>";
				echo "<p><strong>Sponsor Email: </strong><a href='mailto:" . esc_html($geopccb_ngda_email, 'geoplatform-ccb') . "'>" . esc_attr($geopccb_ngda_email, 'geoplatform-ccb') . "</a></p>";
				echo "<p><strong>Theme Lead Agency: </strong>" . esc_attr($geopccb_ngda_agency, 'geoplatform-ccb') . "</p>";
				echo "<p><strong>Theme Lead: </strong>" . esc_attr($geopccb_ngda_lead, 'geoplatform-ccb') . "</p>";
      echo "</div>";
    echo "</article>";
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopccb_ngda_name = ! empty( $instance['geopccb_ngda_name'] ) ? $instance['geopccb_ngda_name'] : 'NGDA Theme Name';
		$geopccb_ngda_type = ! empty( $instance['geopccb_ngda_type'] ) ? $instance['geopccb_ngda_type'] : 'NGDA';
		$geopccb_ngda_sponsor = ! empty( $instance['geopccb_ngda_sponsor'] ) ? $instance['geopccb_ngda_sponsor'] : 'FGDC';
		$geopccb_ngda_email = ! empty( $instance['geopccb_ngda_email'] ) ? $instance['geopccb_ngda_email'] : 'servicedesk@geoplatform.gov';
		$geopccb_ngda_agency = ! empty( $instance['geopccb_ngda_agency'] ) ? $instance['geopccb_ngda_agency'] : 'Theme lead agency info needed.';
		$geopccb_ngda_lead = ! empty( $instance['geopccb_ngda_lead'] ) ? $instance['geopccb_ngda_lead'] : 'Theme lead names needed.';

    // HTML for the widget control box.
		echo "<p>";
			_e('If you have an NGDA community, input its information here.', 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_ngda_name' ) . "'>Theme Name:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopccb_ngda_name' ) . "' name='" . $this->get_field_name( 'geopccb_ngda_name' ) . "' value='" . esc_attr( $geopccb_ngda_name ) . "' />";
    echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_ngda_type' ) . "'>Community Type:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopccb_ngda_type' ) . "' name='" . $this->get_field_name( 'geopccb_ngda_type' ) . "' value='" . esc_attr( $geopccb_ngda_type ) . "' />";
    echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_ngda_sponsor' ) . "'>Sponsor:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopccb_ngda_sponsor' ) . "' name='" . $this->get_field_name( 'geopccb_ngda_sponsor' ) . "' value='" . esc_attr( $geopccb_ngda_sponsor ) . "' />";
    echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_ngda_email' ) . "'>Sponsor Email:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopccb_ngda_email' ) . "' name='" . $this->get_field_name( 'geopccb_ngda_email' ) . "' value='" . esc_attr( $geopccb_ngda_email ) . "' />";
    echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_ngda_agency' ) . "'>Theme Lead Agency:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopccb_ngda_agency' ) . "' name='" . $this->get_field_name( 'geopccb_ngda_agency' ) . "' value='" . esc_attr( $geopccb_ngda_agency ) . "' />";
    echo "</p>";
		echo "<p>";
      echo "<label for='" . $this->get_field_id( 'geopccb_ngda_lead' ) . "'>Theme Lead:</label>";
      echo "<input type='text' id='" . $this->get_field_id( 'geopccb_ngda_lead' ) . "' name='" . $this->get_field_name( 'geopccb_ngda_lead' ) . "' value='" . esc_attr( $geopccb_ngda_lead ) . "' />";
    echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopccb_community_link' ] = strip_tags( $new_instance[ 'geopccb_community_link' ] );
		$instance[ 'geopccb_ngda_name' ] = strip_tags( $new_instance[ 'geopccb_ngda_name' ] );
		$instance[ 'geopccb_ngda_type' ] = strip_tags( $new_instance[ 'geopccb_ngda_type' ] );
		$instance[ 'geopccb_ngda_sponsor' ] = strip_tags( $new_instance[ 'geopccb_ngda_sponsor' ] );
		$instance[ 'geopccb_ngda_email' ] = strip_tags( $new_instance[ 'geopccb_ngda_email' ] );
		$instance[ 'geopccb_ngda_agency' ] = strip_tags( $new_instance[ 'geopccb_ngda_agency' ] );
		$instance[ 'geopccb_ngda_lead' ] = strip_tags( $new_instance[ 'geopccb_ngda_lead' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopccb_register_side_ngda_widget() {
		register_widget( 'Geopccb_Side_Content_NGDA_Widget' );
}
add_action( 'widgets_init', 'geopccb_register_side_ngda_widget' );
