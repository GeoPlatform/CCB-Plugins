<?php
class Geopportal_Resource_Creation_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_resource_creation_widget', // Base ID
			esc_html__( 'GeoPlatform Resource Creation', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform creation widget for the header sub-pages. A simple means to zip right to the Resource Registration page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_resource_creation_singular', $instance) && isset($instance['geopportal_resource_creation_singular']) && !empty($instance['geopportal_resource_creation_singular']))
      $geopportal_resource_creation_singular = apply_filters('widget_title', $instance['geopportal_resource_creation_singular']);
		else
      $geopportal_resource_creation_singular = "asset";
		if (array_key_exists('geopportal_resource_creation_plural', $instance) && isset($instance['geopportal_resource_creation_plural']) && !empty($instance['geopportal_resource_creation_plural']))
      $geopportal_resource_creation_plural = apply_filters('widget_title', $instance['geopportal_resource_creation_plural']);
		else
      $geopportal_resource_creation_plural = "assets";
		if (array_key_exists('geopportal_resource_creation_url', $instance) && isset($instance['geopportal_resource_creation_url']) && !empty($instance['geopportal_resource_creation_url']))
      $geopportal_resource_creation_url = apply_filters('widget_title', $instance['geopportal_resource_creation_url']);
		else
      $geopportal_resource_creation_url = "";
		?>

		<!--
		CREATION
		-->
		<div class="m-section-group">

				<article class="m-article">
						<div class="m-article__heading">Creating <?php _e(sanitize_text_field($geopportal_resource_creation_plural), 'geoplatform-ccb') ?></div>
						<div class="m-article__desc">
								<p>Talk about how users can create their own <?php _e(sanitize_text_field($geopportal_resource_creation_plural), 'geoplatform-ccb') ?>... </p>
						</div>
						<div class="article__actions">
								<a href="<?php echo esc_url($geopportal_resource_creation_url) ?>" class="btn btn-primary">Create a <?php _e(sanitize_text_field(ucfirst($geopportal_resource_creation_singular)), 'geoplatform-ccb') ?></a>
						</div>
				</article>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		$geopportal_resource_creation_singular = ! empty( $instance['geopportal_resource_creation_singular'] ) ? $instance['geopportal_resource_creation_singular'] : 'asset';
    $geopportal_resource_creation_plural = ! empty( $instance['geopportal_resource_creation_plural'] ) ? $instance['geopportal_resource_creation_plural'] : 'assets';
		$geopportal_resource_creation_url = ! empty( $instance['geopportal_resource_creation_url'] ) ? $instance['geopportal_resource_creation_url'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e("Input a singular and plural name for the asset type referenced, as well as a valid full URL for the button. All lower-case text use is suggested for proper output formatting.", 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_resource_creation_singular' ); ?>">Singular Name:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_resource_creation_singular' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_resource_creation_singular' ); ?>" value="<?php echo esc_attr( $geopportal_resource_creation_singular ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_resource_creation_plural' ); ?>">Plural Name:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_resource_creation_plural' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_resource_creation_plural' ); ?>" value="<?php echo esc_attr( $geopportal_resource_creation_plural ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_resource_creation_url' ); ?>">Redirect URL:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_resource_creation_url' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_resource_creation_url' ); ?>" value="<?php echo esc_attr( $geopportal_resource_creation_url ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_resource_creation_singular' ] = strip_tags( $new_instance[ 'geopportal_resource_creation_singular' ] );
		$instance[ 'geopportal_resource_creation_plural' ] = strip_tags( $new_instance[ 'geopportal_resource_creation_plural' ] );
		$instance[ 'geopportal_resource_creation_url' ] = strip_tags( $new_instance[ 'geopportal_resource_creation_url' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_resource_creation_widget() {
		register_widget( 'Geopportal_Resource_Creation_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_resource_creation_widget' );
