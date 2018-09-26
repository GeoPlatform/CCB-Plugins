<?php
class Geopportal_Services_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_services_widget', // Base ID
			esc_html__( 'GeoPlatform Apps & Services', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform apps & services widget for the front page. Requires the Content Blocks plugin.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_apsrv_title', $instance) && isset($instance['geopportal_apsrv_title']) && !empty($instance['geopportal_apsrv_title']))
      $geopportal_apsrv_disp_title = apply_filters('widget_title', $instance['geopportal_apsrv_title']);
		else
      $geopportal_apsrv_disp_title = "Apps &amp; Services";

    if (array_key_exists('geopportal_apsrv_content', $instance) && isset($instance['geopportal_apsrv_content']) && !empty($instance['geopportal_apsrv_content']))
      $geopportal_apsrv_disp_content = apply_filters('widget_title', $instance['geopportal_apsrv_content']);
		else{
      $geopportal_apsrv_disp_content = '<p>Web services play a key role in any open platform experience. GeoPlatform.gov provides this experience in two ways:
      <p><ul><li>Application services (tools) that run in your browser, which you can use to perform useful tasks</li>
      <li>Web services that a developer integrates into their own application, through standards-based application program interfaces (APIs).</li>
      </ul><br><br><p>There are many apps and services already available for use and many more to come in the future!</p>';
    }

    if (array_key_exists('geopportal_apsrv_button_text', $instance) && isset($instance['geopportal_apsrv_button_text']) && !empty($instance['geopportal_apsrv_button_text']))
      $geopportal_apsrv_disp_button_text = apply_filters('widget_title', $instance['geopportal_apsrv_button_text']);
		else
      $geopportal_apsrv_disp_button_text = "View GeoPlatform Services";
		if (array_key_exists('geopportal_apsrv_button_link', $instance) && isset($instance['geopportal_apsrv_button_link']) && !empty($instance['geopportal_apsrv_button_link']))
      $geopportal_apsrv_disp_button_link = apply_filters('widget_title', $instance['geopportal_apsrv_button_link']);
		else
      $geopportal_apsrv_disp_button_link = "https://www.geoplatform.gov/applications-and-services/";

		?>


    <!-- All the HTML output that the widget generates -->
    <div class="apps-and-services section--linked">
      <div class="container">
        <h4 class="heading text-centered">
          <div class="line"></div>
          <div class="title darkened"><?php echo sanitize_text_field($geopportal_apsrv_disp_title) ?></div>
        </h4>
        <br><br>
        <div class="row">
          <div class="col-sm-6">
            <img src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/services_md.jpeg"); ?>" class="img--prominent">
          </div><!--#col-sm-6-->
          <div class="col-sm-6">
            <?php echo do_shortcode($geopportal_apsrv_disp_content); ?>
            <div class="text-centered">
              <a class="btn btn-info" href="<?php echo esc_url($geopportal_apsrv_disp_button_link) ?>"><?php echo sanitize_text_field($geopportal_apsrv_disp_button_text) ?></a>
            </div><!--#text-centered-->
          </div><!--#col-sm-6-->
        </div><!--#row-->
      </div><!--#container-->
      <br><br>
      <div class="footing">
        <div class="line-cap"></div>
        <div class="line"></div>
      </div><!--#footing-->
    </div><!--#apps-and-services section-linked-->
    <?php
  }

  // The admin side of the widget.
  public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_apsrv_cb_bool = false;
		$geopportal_apsrv_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_apsrv_cb_bool = true;
			$geopportal_apsrv_cb_message = "Click here to edit this content block";
		}

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    $geopportal_apsrv_title = ! empty( $instance['geopportal_apsrv_title'] ) ? $instance['geopportal_apsrv_title'] : 'Apps &amp; Services';
    $geopportal_apsrv_content = ! empty( $instance['geopportal_apsrv_content'] ) ? $instance['geopportal_apsrv_content'] : '';
    $geopportal_apsrv_button_text = ! empty( $instance['geopportal_apsrv_button_text'] ) ? $instance['geopportal_apsrv_button_text'] : 'View GeoPlatform Services';
		$geopportal_apsrv_button_link = ! empty( $instance['geopportal_apsrv_button_link'] ) ? $instance['geopportal_apsrv_button_link'] : 'https://www.geoplatform.gov/applications-and-services/';

    // Sets up the content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_apsrv_content', $instance) && isset($instance['geopportal_apsrv_content']) && !empty($instance['geopportal_apsrv_content']) && $geopportal_apsrv_cb_bool){
    	$geopportal_apsrv_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_apsrv_content' ]);
    	if (is_numeric($geopportal_apsrv_temp_url))
      	$geopportal_apsrv_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_apsrv_temp_url . "&action=edit";
    	else
      	$geopportal_apsrv_url = home_url();
		}
		else
			$geopportal_apsrv_url = home_url();
		?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apsrv_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_apsrv_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apsrv_title' ); ?>" value="<?php echo esc_attr( $geopportal_apsrv_title ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apsrv_content' ); ?>">Content Block Shortcode:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_apsrv_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apsrv_content' ); ?>" value="<?php echo esc_attr($geopportal_apsrv_content); ?>" />
      <a href="<?php echo esc_url($geopportal_apsrv_url); ?>" target="_blank"><?php _e($geopportal_apsrv_cb_message, 'geoplatform-ccb') ?></a><br>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apsrv_button_text' ); ?>">Button Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_apsrv_button_text' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apsrv_button_text' ); ?>" value="<?php echo esc_attr( $geopportal_apsrv_button_text ); ?>" />
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_apsrv_button_link' ); ?>">Button Link:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_apsrv_button_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apsrv_button_link' ); ?>" value="<?php echo esc_attr( $geopportal_apsrv_button_link ); ?>" />
		</p>
    <?php
  }

  // Update function, replaces old values with new upon publish.
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'geopportal_apsrv_title' ] = strip_tags( $new_instance[ 'geopportal_apsrv_title' ] );
    $instance[ 'geopportal_apsrv_content' ] = strip_tags( $new_instance[ 'geopportal_apsrv_content' ] );
    $instance[ 'geopportal_apsrv_button_text' ] = strip_tags( $new_instance[ 'geopportal_apsrv_button_text' ] );
		$instance[ 'geopportal_apsrv_button_link' ] = strip_tags( $new_instance[ 'geopportal_apsrv_button_link' ] );
    $instance[ 'geopportal_apsrv_url' ] = strip_tags( $new_instance[ 'geopportal_apsrv_url' ] );

		// Checks if the Content Boxes plugin is installed.
		$geopportal_apsrv_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_apsrv_cb_bool = true;

    // Validity check for the content box URL.
		if (array_key_exists('geopportal_apsrv_content', $instance) && isset($instance['geopportal_apsrv_content']) && !empty($instance['geopportal_apsrv_content']) && $geopportal_apsrv_cb_bool){
    	$geopportal_apsrv_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_apsrv_content' ]);
    	if (is_numeric($geopportal_apsrv_temp_url))
      	$geopportal_apsrv_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_apsrv_temp_url . "&action=edit";
    	else
      	$geopportal_apsrv_url = home_url();
		}
		else
			$geopportal_apsrv_url = home_url();

    return $instance;
  }
}

// Registers and enqueues the widget.
function geopportal_register_portal_service_widget() {
		register_widget( 'Geopportal_Services_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_service_widget' );
