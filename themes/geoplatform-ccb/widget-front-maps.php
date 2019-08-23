<?php
/**
 * Template Name: Widget Front Maps
 *
 * Widget for the front page, displays a list of maps in tile format using a gallery ID input.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
class Geopccb_Front_Page_Maps_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopccb_front_maps_widget', // Base ID
			esc_html__( 'GeoPlatform Front Page Maps', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Maps widget for the front page. Takes a gallery ID as input and displays its maps.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopccb_gallery_id', $instance) && isset($instance['geopccb_gallery_id']) && !empty($instance['geopccb_gallery_id']))
      $geopccb_gallery_id = apply_filters('widget_title', $instance['geopccb_gallery_id']);
		else
    	$geopccb_gallery_id = "aea7f60a21362a06dbc11bb37078df38";

		$geopccb_invalid_bool = false;
		$geopccb_error_report = '';
		$geopccb_ual_root = geop_ccb_getEnv('ual_url',"https://ual.geoplatform.gov");
		$geopccb_gallery_full = $geopccb_ual_root . "/api/galleries/" . $geopccb_gallery_id;

		// Pre-grab validity check.
		if ( empty($geopccb_gallery_id) ){
			$geopccb_invalid_bool = true;
			$geopccb_error_report = 'The Map Gallery Link in Customizer->GeoPlatform Controls is blank. Please fill it in to see your Map Gallery.';
		}
		elseif (!ctype_xdigit($geopccb_gallery_id) || strlen($geopccb_gallery_id) != 32){
			$geopccb_invalid_bool = true;
			$geopccb_error_report = 'Invalid gallery ID. Please check your your input and try again.';
		}

		// Map link format validity block two.
		// Grabs the gallery JSON. If the grab fails or returns a non-gallery, an error is reported.
		if (!$geopccb_invalid_bool){
			$geopccb_link_scrub = wp_remote_get( ''.$geopccb_gallery_full.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
			$geopccb_response = wp_remote_retrieve_body( $geopccb_link_scrub );
			if(!empty($geopccb_response)){
				$geopccb_result = json_decode($geopccb_response, true);
			}
			else{
				$geopccb_invalid_bool = true;
				$geopccb_error_report = 'Invalid gallery ID. Please check your your input and try again.';
			}

			if (!$geopccb_invalid_bool && !is_array($geopccb_result)){
				$geopccb_invalid_bool = true;
				$geopccb_error_report = 'Invalid gallery link provided. Please check your input and try again.';
			}
			elseif (!$geopccb_invalid_bool && array_key_exists('statusCode', $geopccb_result) && $geopccb_result['statusCode'] == "404"){
				$geopccb_invalid_bool = true;
				$geopccb_error_report = 'Invalid gallery ID. Please check your your input and try again.';
			}
			elseif (!$geopccb_invalid_bool && array_key_exists('statusCode', $geopccb_result) && $geopccb_result['statusCode'] >= "500" && $geopccb_result['statusCode'] < "600"){
				$geopccb_invalid_bool = true;
				$geopccb_error_report = 'The map service provider could not be contacted. Please try again later.';
			}
			elseif (!$geopccb_invalid_bool && array_key_exists('type', $geopccb_result) && $geopccb_result['type'] != "Gallery"){
				$geopccb_invalid_bool = true;
				$geopccb_error_report = 'This is not a gallery ID. Please check your your input and try again.';
			}
			elseif (!$geopccb_invalid_bool && empty($geopccb_result['items'])){
				$geopccb_invalid_bool = true;
				$geopccb_error_report = 'There are no items in this gallery, or the map service could not otherwise be contacted.';
			}
		}

		// Further operations continue only if all validity checks pass.
		if( !$geopccb_invalid_bool ) {

			$geopccb_map_card_style = get_theme_mod('feature_controls', 'fade');
	    $geopccb_map_card_fade = "linear-gradient(rgba(0, 0, 0, 0.0), rgba(0, 0, 0, 0.0))";
	    $geopccb_map_card_outline = "";

			if ($geopccb_map_card_style == 'fade' || $geopccb_map_card_style == 'both')
				$geopccb_map_card_fade = "linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5))";
			if ($geopccb_map_card_style == 'outline' || $geopccb_map_card_style == 'both')
				$geopccb_map_card_outline = "-webkit-text-stroke-width: 0.3px; -webkit-text-stroke-color: #000000;";

			echo "<div class='p-landing-page__community-menu featured-flex-parent'>";

			foreach($geopccb_result['items'] as $geopccb_map){
				try {
					//set map ID
					$geopccb_map_id = NULL;
					if (isset($geopccb_map['asset']['id']))
						$geopccb_map_id = $geopccb_map['asset']['id'];
					elseif (isset($geopccb_map['assetId']))
						$geopccb_map_id = $geopccb_map['assetId'];
					else{
						$geopccb_single_result = __( 'The map does not an expected metadata format.', 'geoplatform-ccb');
						return false;
					}
					$geopccb_single_map = wp_remote_get( $GLOBALS['geopccb_ual_url'] .'/api/maps/'.$geopccb_map_id.'');

					if( is_wp_error( $geopccb_single_map ) ) {
						return false; // Bail early
					}
					$geopccb_map_body = wp_remote_retrieve_body( $geopccb_single_map );
					//if the map is empty, handle it
					if(!empty($geopccb_map_body)){
						$geopccb_single_result = json_decode($geopccb_map_body, true);
					}else{
						$geopccb_single_result = __( 'The map did not load properly', 'geoplatform-ccb');
					}

					//for AGOL Maps
					//use isset() to get rid of php notices
					$geopccb_thumbnail = get_template_directory_uri() . '/img/img-404.png';
					if (isset($geopccb_single_result['thumbnail']['url'])){
						$geopccb_thumbnail = $geopccb_single_result['thumbnail']['url'];
					}
					//for MM maps
					elseif (isset($geopccb_single_result['thumbnail'])) {
						$geopccb_thumbnail = $GLOBALS['geopccb_ual_url'] . '/api/maps/' . $geopccb_map_id . "/thumbnail";
					}

					$geopccb_href = "";
					//use isset() to get rid of php notices
					if (isset($geopccb_single_result['landingPage'])) {
						$geopccb_href = $geopccb_single_result['landingPage'];
					}
					//use isset() to get rid of php notices
					if (isset($geopccb_map['description'])) {
						$geopccb_description = $geopccb_map['description'];
					}
					$geopccb_label = $geopccb_map['label'];

					echo "<a class='m-tile m-tile--16x9 featured-flex-child' href='" . esc_url( $geopccb_href ) . "' title='" . esc_attr( __( $geopccb_label, 'geoplatform-ccb' ) ) . "'>";
	          echo "<div class='m-tile__thumbnail'><img alt='" . get_template_directory_uri() . "/img/img-404.png' src='" . esc_url($geopccb_thumbnail) . "'></div>";
	          echo "<div class='m-tile__body' style='background:" . $geopccb_map_card_fade . "'>";
	            echo "<div class='m-tile__heading' style='" . $geopccb_map_card_outline . "'>" . esc_attr( __( strtoupper($geopccb_label), 'geoplatform-ccb' ) ) . "</div>";
	          echo "</div>";
	        echo "</a>";

					// var_dump($geopccb_result);
				} //try
					catch (Exception $e){
						_e('There are errors with the linked gallery', 'geoplatform-ccb');
					} //catch
				}

				echo "</div>";

			}	else {
				echo "<div class='widget-maps-error-out'>";
				_e(strtoupper($geopccb_error_report), 'geoplatform-ccb');
				echo "</div>";
			}

	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopccb_gallery_id = ! empty( $instance['geopccb_gallery_id'] ) ? $instance['geopccb_gallery_id'] : 'aea7f60a21362a06dbc11bb37078df38';

		// HTML for the widget control box.
		echo "<p>";
			_e('Please ensure that your gallery ID is valid. A full URL is not required.', 'geoplatform-ccb');
		echo "</p>";
		echo "<p>";
			echo "<label for='" . $this->get_field_id( 'geopccb_gallery_id' ) . "'>Gallery ID:</label>";
			echo "<input type='text' id='" . $this->get_field_id( 'geopccb_gallery_id' ) . "' name='" . $this->get_field_name( 'geopccb_gallery_id' ) . "' value='" . esc_attr( $geopccb_gallery_id ) . "' />";
		echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopccb_gallery_id' ] = strip_tags( $new_instance[ 'geopccb_gallery_id' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopccb_register_frong_page_maps_widget() {
		register_widget( 'Geopccb_Front_Page_Maps_Widget' );
}
add_action( 'widgets_init', 'geopccb_register_frong_page_maps_widget' );
