<?php
class Geopportal_Featured_Sidebar_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
    parent::__construct(
			'geopportal_featured_sidebar_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Featured', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Sidebar Featured', 'geoplatform-ccb' ), 'customize_selective_refresh' => true ) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {
    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_featap_title', $instance) && isset($instance['geopportal_featap_title']) && !empty($instance['geopportal_featap_title']))
      $geopportal_featap_disp_title = apply_filters('widget_title', $instance['geopportal_featap_title']);
		else
      $geopportal_featap_disp_title = "Featured Applications";

    if (array_key_exists('geopportal_featap_one_title', $instance) && isset($instance['geopportal_featap_one_title']) && !empty($instance['geopportal_featap_one_title']))
      $geopportal_featap_disp_one_title = apply_filters('widget_title', $instance['geopportal_featap_one_title']);
    else
      $geopportal_featap_disp_one_title = "Map Manager";

    if (array_key_exists('geopportal_featap_one_link', $instance) && isset($instance['geopportal_featap_one_link']) && !empty($instance['geopportal_featap_one_link']))
      $geopportal_featap_disp_one_link = apply_filters('widget_title', $instance['geopportal_featap_one_link']);
    else
      $geopportal_featap_disp_one_link = "https://maps.geoplatform.gov/";

    if (array_key_exists('geopportal_featap_two_title', $instance) && isset($instance['geopportal_featap_two_title']) && !empty($instance['geopportal_featap_two_title']))
      $geopportal_featap_disp_two_title = apply_filters('widget_title', $instance['geopportal_featap_two_title']);
		else
      $geopportal_featap_disp_two_title = "Map Viewer";

    if (array_key_exists('geopportal_featap_two_link', $instance) && isset($instance['geopportal_featap_two_link']) && !empty($instance['geopportal_featap_two_link']))
      $geopportal_featap_disp_two_link = apply_filters('widget_title', $instance['geopportal_featap_two_link']);
		else
      $geopportal_featap_disp_two_link = "https://viewer.geoplatform.gov/";

    if (array_key_exists('geopportal_featap_three_title', $instance) && isset($instance['geopportal_featap_three_title']) && !empty($instance['geopportal_featap_three_title']))
      $geopportal_featap_disp_three_title = apply_filters('widget_title', $instance['geopportal_featap_three_title']);
  	else
      $geopportal_featap_disp_three_title = "Marketplace Preview";

    if (array_key_exists('geopportal_featap_three_link', $instance) && isset($instance['geopportal_featap_three_link']) && !empty($instance['geopportal_featap_three_link']))
      $geopportal_featap_disp_three_link = apply_filters('widget_title', $instance['geopportal_featap_three_link']);
  	else
      $geopportal_featap_disp_three_link = "https://marketplace.geoplatform.gov/";

    if (array_key_exists('geopportal_featap_four_title', $instance) && isset($instance['geopportal_featap_four_title']) && !empty($instance['geopportal_featap_four_title']))
      $geopportal_featap_disp_four_title = apply_filters('widget_title', $instance['geopportal_featap_four_title']);
    else
      $geopportal_featap_disp_four_title = "Performance Dashboard";

    if (array_key_exists('geopportal_featap_four_link', $instance) && isset($instance['geopportal_featap_four_link']) && !empty($instance['geopportal_featap_four_link']))
      $geopportal_featap_disp_four_link = apply_filters('widget_title', $instance['geopportal_featap_four_link']);
    else
      $geopportal_featap_disp_four_link = "https://dashboard.geoplatform.gov/";
    ?>

    <div class="card">

      <h4 class="card-title"><span class="glyphicon glyphicon-star"></span><?php echo $geopportal_featap_disp_title ?></h4>

      <div class="gp-ui-card" style="box-shadow:0 1px 3px rgba(0, 0, 0, .25)!important;">
          <a class="media embed-responsive embed-responsive-16by9"
              href="<?php echo $geopportal_featap_disp_one_link ?>" target="_blank" title="Open the Map Manager Application">
              <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/map_manager.jpg"); ?>" alt="Map Manager">
          </a>
          <div class="gp-ui-card__body">
              <div class="text--primary">
                  <a href="<?php echo $geopportal_featap_disp_one_link ?>" target="_blank"><?php echo $geopportal_featap_disp_one_title ?></a>
              </div><!--#text-primary-->
          </div><!--#gp-ui-card__body-->
      </div><!--#gp-ui-card-->

      <div class="gp-ui-card" style="box-shadow:0 1px 3px rgba(0, 0, 0, .25)!important;">
          <a class="media embed-responsive embed-responsive-16by9"
              href="<?php echo $geopportal_featap_disp_two_link ?>" target="_blank" title="Open the Map Viewer Application">
              <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/map_viewer.jpg"); ?>" alt="Map Viewer">
          </a>
          <div class="gp-ui-card__body">
              <div class="text--primary">
                  <a href="<?php echo $geopportal_featap_disp_two_link ?>" target="_blank"><?php echo $geopportal_featap_disp_two_title ?></a>
              </div><!--#text-primary-->
          </div><!--#gp-ui-card__body-->
      </div><!--#gp-ui-card-->

      <div class="gp-ui-card" style="box-shadow:0 1px 3px rgba(0, 0, 0, .25)!important;">
          <a class="media embed-responsive embed-responsive-16by9"
              href="<?php echo $geopportal_featap_disp_three_link ?>" target="_blank" title="Open the Marketplace Preview Application">
              <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/marketplace_preview.jpg"); ?>" alt="Marketplace Preview App">
          </a>
          <div class="gp-ui-card__body">
              <div class="text--primary">
                  <a href="<?php echo $geopportal_featap_disp_three_link ?>" target="_blank"><?php echo $geopportal_featap_disp_three_title ?></a>
              </div><!--#text-primary-->
          </div><!--#gp-ui-card__body-->
      </div><!--#gp-ui-card-->

      <div class="gp-ui-card" style="box-shadow:0 1px 3px rgba(0, 0, 0, .25)!important;">
          <a class="media embed-responsive embed-responsive-16by9"
              href="<?php echo $geopportal_featap_disp_four_link ?>" target="_blank" title="Open the Performance Dashboard Application">
              <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/performance_dashboard.jpg"); ?>" alt="Performance Dashboard">
          </a>
          <div class="gp-ui-card__body">
              <div class="text--primary">
                  <a href="<?php echo $geopportal_featap_disp_four_link ?>" target="_blank"><?php echo $geopportal_featap_disp_four_title ?></a>
              </div><!--#text-primary-->
          </div><!--#gp-ui-card__body-->
      </div><!--#gp-ui-card-->

    </div><!--#card-->
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {
		$title = "Sidebar Featured Services";

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    $geopportal_featap_disp_title = ! empty( $instance['geopportal_featap_title'] ) ? $instance['geopportal_featap_title'] : 'Featured Applications';
    $geopportal_featap_disp_one_title = ! empty( $instance['geopportal_featap_one_title'] ) ? $instance['geopportal_featap_one_title'] : 'Map Manager';
    $geopportal_featap_disp_one_link = ! empty( $instance['geopportal_featap_one_link'] ) ? $instance['geopportal_featap_one_link'] : 'https://maps.geoplatform.gov/';
    $geopportal_featap_disp_two_title = ! empty( $instance['geopportal_featap_two_title'] ) ? $instance['geopportal_featap_two_title'] : 'Map Viewer';
    $geopportal_featap_disp_two_link = ! empty( $instance['geopportal_featap_two_link'] ) ? $instance['geopportal_featap_two_link'] : 'https://viewer.geoplatform.gov/';
    $geopportal_featap_disp_three_title = ! empty( $instance['geopportal_featap_three_title'] ) ? $instance['geopportal_featap_three_title'] : 'Marketplace Preview';
    $geopportal_featap_disp_three_link = ! empty( $instance['geopportal_featap_three_link'] ) ? $instance['geopportal_featap_three_link'] : 'https://marketplace.geoplatform.gov/';
    $geopportal_featap_disp_four_title = ! empty( $instance['geopportal_featap_four_title'] ) ? $instance['geopportal_featap_four_title'] : 'Performance Dashboard';
    $geopportal_featap_disp_four_link = ! empty( $instance['geopportal_featap_four_link'] ) ? $instance['geopportal_featap_four_link'] : 'https://dashboard.geoplatform.gov/';
    ?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featap_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_title' ); ?>" value="<?php echo esc_attr( $geopportal_featap_disp_title ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_one_title' ); ?>">First Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featap_one_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_one_title' ); ?>" value="<?php echo esc_attr($geopportal_featap_disp_one_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_one_link' ); ?>">First Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featap_one_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_one_link' ); ?>" value="<?php echo esc_attr( $geopportal_featap_disp_one_link ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_two_title' ); ?>">Second Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featap_two_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_two_title' ); ?>" value="<?php echo esc_attr($geopportal_featap_disp_two_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_two_link' ); ?>">Second Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featap_two_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_two_link' ); ?>" value="<?php echo esc_attr( $geopportal_featap_disp_two_link ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_three_title' ); ?>">Third Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featap_three_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_three_title' ); ?>" value="<?php echo esc_attr($geopportal_featap_disp_three_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_three_link' ); ?>">Third Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featap_three_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_three_link' ); ?>" value="<?php echo esc_attr( $geopportal_featap_disp_three_link ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_four_title' ); ?>">Fourth Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featap_four_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_four_title' ); ?>" value="<?php echo esc_attr($geopportal_featap_disp_four_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featap_four_link' ); ?>">Fourth Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featap_four_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featap_four_link' ); ?>" value="<?php echo esc_attr( $geopportal_featap_disp_four_link ); ?>" />
    </p>
    <?php
	}

  // Update function, replaces old values with new upon publish.
	public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'geopportal_featap_title' ] = strip_tags( $new_instance[ 'geopportal_featap_title' ] );
    $instance[ 'geopportal_featap_one_title' ] = strip_tags( $new_instance[ 'geopportal_featap_one_title' ] );
    $instance[ 'geopportal_featap_one_link' ] = strip_tags( $new_instance[ 'geopportal_featap_one_link' ] );
    $instance[ 'geopportal_featap_two_title' ] = strip_tags( $new_instance[ 'geopportal_featap_two_title' ] );
    $instance[ 'geopportal_featap_two_link' ] = strip_tags( $new_instance[ 'geopportal_featap_two_link' ] );
    $instance[ 'geopportal_featap_three_title' ] = strip_tags( $new_instance[ 'geopportal_featap_three_title' ] );
    $instance[ 'geopportal_featap_three_link' ] = strip_tags( $new_instance[ 'geopportal_featap_three_link' ] );
    $instance[ 'geopportal_featap_four_title' ] = strip_tags( $new_instance[ 'geopportal_featap_four_title' ] );
    $instance[ 'geopportal_featap_four_link' ] = strip_tags( $new_instance[ 'geopportal_featap_four_link' ] );

    return $instance;
  }
}

// Registers and enqueues the widget.
function geopportal_register_portal_featured_sidebar_widget() {
  register_widget( 'Geopportal_Featured_Sidebar_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_featured_sidebar_widget' );
