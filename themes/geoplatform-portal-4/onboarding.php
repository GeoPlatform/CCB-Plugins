<?php
class Geopportal_Onboarding_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_onboarding_widget', // Base ID
			esc_html__( 'GeoPlatform Onboarding', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform onboarding widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_onboarding_first_text', $instance) && isset($instance['geopportal_onboarding_first_text']) && !empty($instance['geopportal_onboarding_first_text']))
      $geopportal_onboarding_first_text = apply_filters('widget_title', $instance['geopportal_onboarding_first_text']);
		else
      $geopportal_onboarding_first_text = "Don't have a GeoPlatform.gov account yet?";

		if (array_key_exists('geopportal_onboarding_first_link', $instance) && isset($instance['geopportal_onboarding_first_link']) && !empty($instance['geopportal_onboarding_first_link']))
      $geopportal_onboarding_first_link = apply_filters('widget_title', $instance['geopportal_onboarding_first_link']);
		else
      $geopportal_onboarding_first_link = "";

		if (array_key_exists('geopportal_onboarding_second_text', $instance) && isset($instance['geopportal_onboarding_second_text']) && !empty($instance['geopportal_onboarding_second_text']))
	    $geopportal_onboarding_second_text = apply_filters('widget_title', $instance['geopportal_onboarding_second_text']);
		else
	    $geopportal_onboarding_second_text = "about what the GeoPlatform offers.";

		if (array_key_exists('geopportal_onboarding_second_link', $instance) && isset($instance['geopportal_onboarding_second_link']) && !empty($instance['geopportal_onboarding_second_link']))
	    $geopportal_onboarding_second_link = apply_filters('widget_title', $instance['geopportal_onboarding_second_link']);
		else
	    $geopportal_onboarding_second_link = "";


    ?>

		<div class="p-landing-page__onboarding">
		    <div class="u-text--center">
		        <img alt="Sign Up" src="<?php echo get_stylesheet_directory_uri() . '/img/register.svg' ?>">
		        <div>
		            <?php _e(sanitize_text_field($geopportal_onboarding_first_text), 'geoplatform-ccb') ?>
								<a class="btn btn-white" href="<?php echo esc_url($geopportal_onboarding_first_link) ?>  "><span style="color:#000">Sign up!</span></a>
		            <!-- <button type="button" class="btn btn-white">Sign up!</button> -->
		        </div>
		    </div>
		    <div class="u-text--center">
		        <img alt="Learn more" src="<?php echo get_stylesheet_directory_uri() . '/img/learn.svg' ?>">
		        <div>
							<a class="btn btn-white" href="<?php echo esc_url($geopportal_onboarding_second_link) ?>"><span style="color:#000">Learn more</span></a>
		            <!-- <button type="button" class="btn btn-white">Learn more</button> -->
								<?php _e(sanitize_text_field($geopportal_onboarding_second_text), 'geoplatform-ccb') ?>
		        </div>
		    </div>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    $geopportal_onboarding_first_text = ! empty( $instance['geopportal_onboarding_first_text'] ) ? $instance['geopportal_onboarding_first_text'] : "Don't have a GeoPlatform.gov account yet?";
		$geopportal_onboarding_first_link = ! empty( $instance['geopportal_onboarding_first_link'] ) ? $instance['geopportal_onboarding_first_link'] : '';
		$geopportal_onboarding_second_text = ! empty( $instance['geopportal_onboarding_second_text'] ) ? $instance['geopportal_onboarding_second_text'] : 'about what the GeoPlatform offers.';
		$geopportal_onboarding_second_link = ! empty( $instance['geopportal_onboarding_second_link'] ) ? $instance['geopportal_onboarding_second_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('Please ensure that the boxes for URLs point to valid URLs before inputting.', 'geoplatform-ccb'); ?>
		</p>
		<hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_onboarding_first_text' ); ?>">Sign up text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_onboarding_first_text' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_onboarding_first_text' ); ?>" value="<?php echo esc_attr( $geopportal_onboarding_first_text ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_onboarding_first_link' ); ?>">Sign up url:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_onboarding_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_onboarding_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_onboarding_first_link ); ?>" />
    </p>
		<hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_onboarding_second_text' ); ?>">Learn more text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_onboarding_second_text' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_onboarding_second_text' ); ?>" value="<?php echo esc_attr( $geopportal_onboarding_second_text ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_onboarding_second_link' ); ?>">Learn more url:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_onboarding_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_onboarding_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_onboarding_second_link ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

    $instance[ 'geopportal_onboarding_first_text' ] = strip_tags( $new_instance[ 'geopportal_onboarding_first_text' ] );
		$instance[ 'geopportal_onboarding_first_link' ] = strip_tags( $new_instance[ 'geopportal_onboarding_first_link' ] );
		$instance[ 'geopportal_onboarding_second_text' ] = strip_tags( $new_instance[ 'geopportal_onboarding_second_text' ] );
		$instance[ 'geopportal_onboarding_second_link' ] = strip_tags( $new_instance[ 'geopportal_onboarding_second_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_onboarding_widget() {
		register_widget( 'Geopportal_Onboarding_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_onboarding_widget' );
