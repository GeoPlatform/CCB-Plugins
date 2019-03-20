<?php
class Geopportal_Resource_Elements_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_elements_widget', // Base ID
			esc_html__( 'GeoPlatform Resource Elements', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Elements widget for resource pages. Takes Asset Carousel shortcode as input and displays the assets in a list format. The shortcode must be modified manually to provide only one data type and no id to function properly.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_elements_title', $instance) && isset($instance['geopportal_elements_title']) && !empty($instance['geopportal_elements_title']))
      $geopportal_elements_title = apply_filters('widget_title', $instance['geopportal_elements_title']);
		else
      $geopportal_elements_title = "Elements";
		if (array_key_exists('geopportal_elements_shortcode', $instance) && isset($instance['geopportal_elements_shortcode']) && !empty($instance['geopportal_elements_shortcode'])){
      $geopportal_elements_shortcode = apply_filters('widget_title', $instance['geopportal_elements_shortcode']);
			$geopportal_elements_shortcode = esc_attr(substr($geopportal_elements_shortcode, 0, -1) . " hide=T]");
		}
		else
      $geopportal_elements_shortcode = "";
 		?>

		<!--
		ELEMENTS
		-->
		<div class="m-section-group">
			<div class="m-article__heading">
					<?php echo $geopportal_elements_title; ?>
			</div>
			<?php echo do_shortcode($geopportal_elements_shortcode); ?>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopportal_elements_title = ! empty( $instance['geopportal_elements_title'] ) ? $instance['geopportal_elements_title'] : 'Elements';
		$geopportal_elements_shortcode = ! empty( $instance['geopportal_elements_shortcode'] ) ? $instance['geopportal_elements_shortcode'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('Input a GeoPlatform Asset Carousel plugin shortcode. There should only be one enabled data type. You will also need to manually remove the community ID and title attributes from the shortcode, leaving only the cat and count values without quotation marks.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_elements_title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_elements_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_elements_title' ); ?>" value="<?php echo esc_attr( $geopportal_elements_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_elements_shortcode' ); ?>">Gallery Shortcode:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_elements_shortcode' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_elements_shortcode' ); ?>" value="<?php echo esc_attr( $geopportal_elements_shortcode ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_elements_title' ] = strip_tags( $new_instance[ 'geopportal_elements_title' ] );
		$instance[ 'geopportal_elements_shortcode' ] = strip_tags( $new_instance[ 'geopportal_elements_shortcode' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_elements_widget() {
		register_widget( 'Geopportal_Resource_Elements_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_elements_widget' );
