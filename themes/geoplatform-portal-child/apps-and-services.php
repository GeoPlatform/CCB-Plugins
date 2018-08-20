<?php
class Geopportal_Services_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_services_widget', // Base ID
			esc_html__( 'GeoPlatform Apps & Services', 'geoplatform-portal-child' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Apps & Services', 'geoplatform-portal-child' ), 'customize_selective_refresh' => true) // Args
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

    if (array_key_exists('geopportal_apsrv_button', $instance) && isset($instance['geopportal_apsrv_button']) && !empty($instance['geopportal_apsrv_button']))
      $geopportal_apsrv_disp_button = apply_filters('widget_title', $instance['geopportal_apsrv_button']);
		else
      $geopportal_apsrv_disp_button = "View GeoPlatform Services";
		echo $args['before_widget'];?>


    <!-- All the HTML output that the widget generates -->
    <div class="apps-and-services section--linked">
      <div class="container">
        <h4 class="heading text-centered">
          <div class="line"></div>
          <div class="title darkened"><?php echo $geopportal_apsrv_disp_title ?></div>
        </h4>
        <br><br>
        <div class="row">
          <div class="col-sm-6">
            <img src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/services_md.jpeg"); ?>" class="img--prominent">
          </div><!--#col-sm-6-->
          <div class="col-sm-6">
            <?php echo do_shortcode($geopportal_apsrv_disp_content); ?>

            <!-- <p>Web services play a key role in any open platform experience. GeoPlatform.gov provides this experience in two ways:<p>
            <ul>
              <li>Application services (tools) that run in your browser, which you can use to perform useful tasks</li>
              <li>Web services that a developer integrates into their own application, through standards-based application program interfaces (APIs).</li>
            </ul><br><br>
            <p>There are many apps and services already available for use and many more to come in the future!</p> -->

            <div class="text-centered">
              <a class="btn btn-info" href="applications-and-services"><?php echo $geopportal_apsrv_disp_button ?></a>
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
    echo $args['after_widget'];
  }

  // The admin side of the widget.
  public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    $geopportal_apsrv_title = ! empty( $instance['geopportal_apsrv_title'] ) ? $instance['geopportal_apsrv_title'] : 'Apps &amp; Services';
    $geopportal_apsrv_content = ! empty( $instance['geopportal_apsrv_content'] ) ? $instance['geopportal_apsrv_content'] : '';
    $geopportal_apsrv_button = ! empty( $instance['geopportal_apsrv_button'] ) ? $instance['geopportal_apsrv_button'] : 'View GeoPlatform Services';

    // Sets up the content box link, or just a home link if invalid.
    $geopportal_apsrv_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_apsrv_content' ]);
    if (is_numeric($geopportal_apsrv_temp_url)){
      $geopportal_apsrv_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_apsrv_temp_url . "&action=edit";
    }
    else
      $geopportal_apsrv_url = home_url(); ?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apsrv_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_apsrv_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apsrv_title' ); ?>" value="<?php echo esc_attr( $geopportal_apsrv_title ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apsrv_content' ); ?>">Content Block Shortcode:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_apsrv_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apsrv_content' ); ?>" value="<?php echo esc_attr($geopportal_apsrv_content); ?>" />
      <a href="<?php echo esc_url($geopportal_apsrv_url); ?>" target="_blank">Click here to edit the content block</a><br>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apsrv_button' ); ?>">Button Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_apsrv_button' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apsrv_button' ); ?>" value="<?php echo esc_attr( $geopportal_apsrv_button ); ?>" />
    </p>
    <?php
  }

  // Update function, replaces old values with new upon publish.
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'geopportal_apsrv_title' ] = strip_tags( $new_instance[ 'geopportal_apsrv_title' ] );
    $instance[ 'geopportal_apsrv_content' ] = strip_tags( $new_instance[ 'geopportal_apsrv_content' ] );
    $instance[ 'geopportal_apsrv_button' ] = strip_tags( $new_instance[ 'geopportal_apsrv_button' ] );
    $instance[ 'geopportal_apsrv_url' ] = strip_tags( $new_instance[ 'geopportal_apsrv_url' ] );

    // Validity check for the content box URL.
    $geopportal_apsrv_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_apsrv_content' ]);
    if (is_numeric($geopportal_apsrv_temp_url)){
      $geopportal_apsrv_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_apsrv_temp_url . "&action=edit";
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
