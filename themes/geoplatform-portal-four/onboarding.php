<?php
class Geopportal_Onboarding_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_onboarding_widget', // Base ID
			esc_html__( 'GeoPlatform Onboarding', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Onboarding widget for the front page. Provides a means for users to sign up or sign in, as well as a redirect to learn more about the GeoPlatform.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_onboarding_first_link', $instance) && isset($instance['geopportal_onboarding_first_link']) && !empty($instance['geopportal_onboarding_first_link']))
      $geopportal_onboarding_first_link = apply_filters('widget_title', $instance['geopportal_onboarding_first_link']);
		else
      $geopportal_onboarding_first_link = "";
		if (array_key_exists('geopportal_onboarding_second_link', $instance) && isset($instance['geopportal_onboarding_second_link']) && !empty($instance['geopportal_onboarding_second_link']))
	    $geopportal_onboarding_second_link = apply_filters('widget_title', $instance['geopportal_onboarding_second_link']);
		else
	    $geopportal_onboarding_second_link = "";
		if (array_key_exists('geopportal_onboarding_third_link', $instance) && isset($instance['geopportal_onboarding_third_link']) && !empty($instance['geopportal_onboarding_third_link']))
	    $geopportal_onboarding_third_link = apply_filters('widget_title', $instance['geopportal_onboarding_third_link']);
		else
	    $geopportal_onboarding_third_link = "";

    ?>
		<div class="p-landing-page__onboarding" id="geopportal_anchor_onboard" title="Sign Up">
				<div class="d-flex flex-justify-between flex-align-center">
						<div class="u-mg-right--md">
								<img alt="Sign Up" src="<?php echo get_stylesheet_directory_uri() . '/img/register.svg' ?>">
						</div>
						<div class="flex-1 u-text--sm">
								<div class="u-text--xlg t-text--bold">Don't have a GeoPlatform.gov account yet?</div>
								<p>Sign up to access thousands of datasets uploaded by others and contribute your own data to the world! You can also share your expertise and find experts to help with your geospatial data needs by joining one of our Communities. Submit your metadata to Data.gov  and we’ll add it to GeoPlatform so others can use it.</p>
								<button type="button" class="btn btn-light" onclick="location.href='<?php echo esc_url($geopportal_onboarding_first_link) ?>';">Sign up!</button>&nbsp;&nbsp;
								<a class="btn btn-outline-light" href="<?php echo esc_url($geopportal_onboarding_second_link) ?>">Learn More...</a>
								<br><br>
								<div>If you already have an account, <a href="<?php echo esc_url($geopportal_onboarding_third_link) ?>">sign in</a></div>
						</div>
				</div>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopportal_onboarding_first_link = ! empty( $instance['geopportal_onboarding_first_link'] ) ? $instance['geopportal_onboarding_first_link'] : '';
		$geopportal_onboarding_second_link = ! empty( $instance['geopportal_onboarding_second_link'] ) ? $instance['geopportal_onboarding_second_link'] : '';
		$geopportal_onboarding_third_link = ! empty( $instance['geopportal_onboarding_third_link'] ) ? $instance['geopportal_onboarding_third_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('Please ensure that the boxes for URLs point to valid URLs before inputting.', 'geoplatform-ccb'); ?>
		</p>
		<hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_onboarding_first_link' ); ?>">Sign up url:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_onboarding_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_onboarding_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_onboarding_first_link ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_onboarding_second_link' ); ?>">Learn more url:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_onboarding_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_onboarding_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_onboarding_second_link ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_onboarding_third_link' ); ?>">Sign in url:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_onboarding_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_onboarding_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_onboarding_third_link ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_onboarding_first_link' ] = strip_tags( $new_instance[ 'geopportal_onboarding_first_link' ] );
		$instance[ 'geopportal_onboarding_second_link' ] = strip_tags( $new_instance[ 'geopportal_onboarding_second_link' ] );
		$instance[ 'geopportal_onboarding_third_link' ] = strip_tags( $new_instance[ 'geopportal_onboarding_third_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_onboarding_widget() {
		register_widget( 'Geopportal_Onboarding_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_onboarding_widget' );
