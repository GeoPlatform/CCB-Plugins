<?php
class Geopportal_Resource_Creation_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_resource_creation_widget', // Base ID
			esc_html__( 'GeoPlatform Resource Creation', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Creation widget for resource pages. A simple means to navigate right to the Resource Registration page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_resource_creation_title', $instance) && isset($instance['geopportal_resource_creation_title']) && !empty($instance['geopportal_resource_creation_title']))
      $geopportal_resource_creation_title = apply_filters('widget_title', $instance['geopportal_resource_creation_title']);
		else
      $geopportal_resource_creation_title = "Sample Title";
		if (array_key_exists('geopportal_resource_creation_text', $instance) && isset($instance['geopportal_resource_creation_text']) && !empty($instance['geopportal_resource_creation_text']))
      $geopportal_resource_creation_text = apply_filters('widget_title', $instance['geopportal_resource_creation_text']);
		else
      $geopportal_resource_creation_text = "Talk about how users can create their own asset... ";
		if (array_key_exists('geopportal_resource_creation_button', $instance) && isset($instance['geopportal_resource_creation_button']) && !empty($instance['geopportal_resource_creation_button']))
      $geopportal_resource_creation_button = apply_filters('widget_title', $instance['geopportal_resource_creation_button']);
		else
      $geopportal_resource_creation_button = "Register an Asset";
		if (array_key_exists('geopportal_resource_creation_url', $instance) && isset($instance['geopportal_resource_creation_url']) && !empty($instance['geopportal_resource_creation_url']))
      $geopportal_resource_creation_url = apply_filters('widget_title', $instance['geopportal_resource_creation_url']);
		else
      $geopportal_resource_creation_url = "";

		// CREATION
		echo "<div class='m-section-group'>";
			echo "<article class='m-article'>";
				echo "<div class='m-article__heading'>" . __(sanitize_text_field($geopportal_resource_creation_title), 'geoplatform-ccb') . "</div>";
				echo "<div class='m-article__desc'>";
					echo "<p>" . __(sanitize_text_field($geopportal_resource_creation_text), 'geoplatform-ccb') . "</p>";
				echo "</div>";
				echo "<div class='article__actions'>";
					echo "<a href='" . esc_url($geopportal_resource_creation_url) . "' class='btn btn-primary'>" . __(sanitize_text_field($geopportal_resource_creation_button), 'geoplatform-ccb') . "</a>";
				echo "</div>";
			echo "</article>";
		echo "</div>";
	}

  // The admin side of the widget.
	public function form( $instance ) {

		$geopportal_resource_creation_title = ! empty( $instance['geopportal_resource_creation_title'] ) ? $instance['geopportal_resource_creation_title'] : 'Sample Title';
    $geopportal_resource_creation_text = ! empty( $instance['geopportal_resource_creation_text'] ) ? $instance['geopportal_resource_creation_text'] : 'Talk about how users can create their own asset... ';
		$geopportal_resource_creation_button = ! empty( $instance['geopportal_resource_creation_button'] ) ? $instance['geopportal_resource_creation_button'] : 'Register an Asset';
		$geopportal_resource_creation_url = ! empty( $instance['geopportal_resource_creation_url'] ) ? $instance['geopportal_resource_creation_url'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e("This widget is very straight-forward. Simply input text as it should appear in the output, as well as the button URL.", 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_resource_creation_title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_resource_creation_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_resource_creation_title' ); ?>" value="<?php echo esc_attr( $geopportal_resource_creation_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_resource_creation_text' ); ?>">Body Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_resource_creation_text' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_resource_creation_text' ); ?>" value="<?php echo esc_attr( $geopportal_resource_creation_text ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_resource_creation_button' ); ?>">Button Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_resource_creation_button' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_resource_creation_button' ); ?>" value="<?php echo esc_attr( $geopportal_resource_creation_button ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_resource_creation_url' ); ?>">Redirect URL:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_resource_creation_url' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_resource_creation_url' ); ?>" value="<?php echo esc_attr( $geopportal_resource_creation_url ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_resource_creation_title' ] = strip_tags( $new_instance[ 'geopportal_resource_creation_title' ] );
		$instance[ 'geopportal_resource_creation_text' ] = strip_tags( $new_instance[ 'geopportal_resource_creation_text' ] );
		$instance[ 'geopportal_resource_creation_button' ] = strip_tags( $new_instance[ 'geopportal_resource_creation_button' ] );
		$instance[ 'geopportal_resource_creation_url' ] = strip_tags( $new_instance[ 'geopportal_resource_creation_url' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_resource_creation_widget() {
		register_widget( 'Geopportal_Resource_Creation_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_resource_creation_widget' );
