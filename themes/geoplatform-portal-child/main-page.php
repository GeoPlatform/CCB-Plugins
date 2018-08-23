<?php
class Geopportal_MainPage_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_mainpage_widget', // Base ID
			esc_html__( 'GeoPlatform Features & Announcements', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform features & announcements widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_mainpage_title', $instance) && isset($instance['geopportal_mainpage_title']) && !empty($instance['geopportal_mainpage_title']))
      $geopportal_mainpage_disp_title = apply_filters('widget_title', $instance['geopportal_mainpage_title']);
		else
      $geopportal_mainpage_disp_title = "Features &amp; Announcements";

		if (array_key_exists('geopportal_mainpage_first_link', $instance) && isset($instance['geopportal_mainpage_first_link']) && !empty($instance['geopportal_mainpage_first_link']))
      $geopportal_mainpage_disp_first_link = apply_filters('widget_title', $instance['geopportal_mainpage_first_link']);
		else
      $geopportal_mainpage_disp_first_link = "";
		if (array_key_exists('geopportal_mainpage_first_content', $instance) && isset($instance['geopportal_mainpage_first_content']) && !empty($instance['geopportal_mainpage_first_content']))
			$geopportal_mainpage_disp_first_content = apply_filters('widget_title', $instance['geopportal_mainpage_first_content']);
		else
			$geopportal_mainpage_disp_first_content = "";

		if (array_key_exists('geopportal_mainpage_second_link', $instance) && isset($instance['geopportal_mainpage_second_link']) && !empty($instance['geopportal_mainpage_second_link']))
      $geopportal_mainpage_disp_second_link = apply_filters('widget_title', $instance['geopportal_mainpage_second_link']);
		else
      $geopportal_mainpage_disp_second_link = "";
		if (array_key_exists('geopportal_mainpage_third_link', $instance) && isset($instance['geopportal_mainpage_third_link']) && !empty($instance['geopportal_mainpage_third_link']))
      $geopportal_mainpage_disp_third_link = apply_filters('widget_title', $instance['geopportal_mainpage_third_link']);
		else
      $geopportal_mainpage_disp_third_link = "";


    echo $args['before_widget'];?>

    <div class="whatsNew section--linked">
      <div class="container-fluid">
        <div class="line"></div>
        <div class="line-arrow"></div>

        <div class="col-lg-10 col-lg-offset-1">
          <h4 class="heading text-centered">
            <div class="title darkened">
              <?php _e(sanitize_text_field($geopportal_mainpage_disp_title), 'geoplatform-ccb') ?>
            </div>
          </h4>
          <div class="row">
						<?php
						$geopportal_mainpage_disp_post_array = array($geopportal_mainpage_disp_first_link, $geopportal_mainpage_disp_second_link, $geopportal_mainpage_disp_third_link);
						$geopportal_mainpage_disp_content_array = array($geopportal_mainpage_disp_first_content, $geopportal_mainpage_disp_first_content, $geopportal_mainpage_disp_first_content);
						for ($x = 0; $x <= 2; $x++){
							include( locate_template('post-card.php', false, false));
						}
            ?>
            <div class="clearfix"></div>
          </div><!--#row-->
        </div><!--#col-lg-10 col-lg-offset-1-->
      </div><!--#container-fluid-->
      <br>
      <div class="footing">
          <div class="line-cap"></div>
          <div class="line"></div>
      </div><!--#footing-->
    </div><!--#whatsNew section-linked-->
    <?php
    echo $args['after_widget'];
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpage_cb_bool = false;
		$geopportal_mainpage_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_mainpage_cb_bool = true;
			$geopportal_mainpage_cb_message = "Click here to edit this content block";
		}

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    $geopportal_mainpage_title = ! empty( $instance['geopportal_mainpage_title'] ) ? $instance['geopportal_mainpage_title'] : 'Features &amp; Announcements';
		$geopportal_mainpage_first_link = ! empty( $instance['geopportal_mainpage_first_link'] ) ? $instance['geopportal_mainpage_first_link'] : '';
		$geopportal_mainpage_first_content = ! empty( $instance['geopportal_mainpage_first_content'] ) ? $instance['geopportal_mainpage_first_content'] : '';
		$geopportal_mainpage_second_link = ! empty( $instance['geopportal_mainpage_second_link'] ) ? $instance['geopportal_mainpage_second_link'] : '';
		$geopportal_mainpage_third_link = ! empty( $instance['geopportal_mainpage_third_link'] ) ? $instance['geopportal_mainpage_third_link'] : '';

		// Sets up the top-left content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_mainpage_first_content', $instance) && isset($instance['geopportal_mainpage_first_content']) && !empty($instance['geopportal_mainpage_first_content']) && $geopportal_mainpage_cb_bool){
    	$geopportal_mainpage_first_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpage_first_content' ]);
    	if (is_numeric($geopportal_mainpage_first_temp_url))
      	$geopportal_mainpage_first_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpage_first_temp_url . "&action=edit";
    	else
      	$geopportal_mainpage_first_url = home_url();
		}
		else
			$geopportal_mainpage_first_url = home_url();


		?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_title' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_title ); ?>" />
    </p>
		<hr>
		<p>
			<?php _e('The boxes below accept the slugs of the linked post or page. Please ensure that any input slugs are valid.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_first_link' ); ?>">First Post Slug:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_first_link ); ?>" />
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_first_content' ); ?>">First Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_mainpage_first_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_first_content' ); ?>" value="<?php echo esc_attr($geopportal_mainpage_first_content); ?>" />
			<a href="<?php echo esc_url($geopportal_mainpage_first_url); ?>" target="_blank"><?php _e($geopportal_mainpage_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_second_link' ); ?>">Second Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_second_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_third_link' ); ?>">Third Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_third_link ); ?>" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpage_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_mainpage_cb_bool = true;

    $instance[ 'geopportal_mainpage_title' ] = strip_tags( $new_instance[ 'geopportal_mainpage_title' ] );
		$instance[ 'geopportal_mainpage_first_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_first_link' ] );
		$instance[ 'geopportal_mainpage_first_content' ] = strip_tags( $new_instance[ 'geopportal_mainpage_first_content' ] );
		$instance[ 'geopportal_mainpage_second_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_second_link' ] );
		$instance[ 'geopportal_mainpage_third_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_third_link' ] );

		// Validity check for the content box URL.
		if (array_key_exists('geopportal_mainpage_first_content', $instance) && isset($instance['geopportal_mainpage_first_content']) && !empty($instance['geopportal_mainpage_first_content']) && $geopportal_mainpage_cb_bool){
    	$geopportal_mainpage_first_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpage_first_content' ]);
    	if (is_numeric($geopportal_mainpage_first_temp_url))
      	$geopportal_mainpage_first_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpage_first_temp_url . "&action=edit";
    	else
      	$geopportal_mainpage_first_url = home_url();
		}
		else
			$geopportal_mainpage_first_url = home_url();


		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_mainpage_widget() {
		register_widget( 'Geopportal_MainPage_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_mainpage_widget' );
