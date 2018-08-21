<?php
class Geopportal_Featured_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_featured_widget', // Base ID
			esc_html__( 'GeoPlatform Featured Applications', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform featured applications widget for the front page, featuring title and hotlink customization.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true ) // Args
		);
	}

  // Handles the widget output
	public function widget( $args, $instance ) {
    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_featapp_title', $instance) && isset($instance['geopportal_featapp_title']) && !empty($instance['geopportal_featapp_title']))
      $geopportal_featapp_disp_title = apply_filters('widget_title', $instance['geopportal_featapp_title']);
		else
      $geopportal_featapp_disp_title = "Featured Applications";

    if (array_key_exists('geopportal_featapp_one_title', $instance) && isset($instance['geopportal_featapp_one_title']) && !empty($instance['geopportal_featapp_one_title']))
      $geopportal_featapp_disp_one_title = apply_filters('widget_title', $instance['geopportal_featapp_one_title']);
    else
      $geopportal_featapp_disp_one_title = "Map Manager";

    if (array_key_exists('geopportal_featapp_one_link', $instance) && isset($instance['geopportal_featapp_one_link']) && !empty($instance['geopportal_featapp_one_link']))
      $geopportal_featapp_disp_one_link = apply_filters('widget_title', $instance['geopportal_featapp_one_link']);
    else
      $geopportal_featapp_disp_one_link = "https://maps.geoplatform.gov/";

    if (array_key_exists('geopportal_featapp_two_title', $instance) && isset($instance['geopportal_featapp_two_title']) && !empty($instance['geopportal_featapp_two_title']))
      $geopportal_featapp_disp_two_title = apply_filters('widget_title', $instance['geopportal_featapp_two_title']);
		else
      $geopportal_featapp_disp_two_title = "Map Viewer";

    if (array_key_exists('geopportal_featapp_two_link', $instance) && isset($instance['geopportal_featapp_two_link']) && !empty($instance['geopportal_featapp_two_link']))
      $geopportal_featapp_disp_two_link = apply_filters('widget_title', $instance['geopportal_featapp_two_link']);
		else
      $geopportal_featapp_disp_two_link = "https://viewer.geoplatform.gov/";

    if (array_key_exists('geopportal_featapp_three_title', $instance) && isset($instance['geopportal_featapp_three_title']) && !empty($instance['geopportal_featapp_three_title']))
      $geopportal_featapp_disp_three_title = apply_filters('widget_title', $instance['geopportal_featapp_three_title']);
  	else
      $geopportal_featapp_disp_three_title = "Marketplace Preview";

    if (array_key_exists('geopportal_featapp_three_link', $instance) && isset($instance['geopportal_featapp_three_link']) && !empty($instance['geopportal_featapp_three_link']))
      $geopportal_featapp_disp_three_link = apply_filters('widget_title', $instance['geopportal_featapp_three_link']);
  	else
      $geopportal_featapp_disp_three_link = "https://marketplace.geoplatform.gov/";

    if (array_key_exists('geopportal_featapp_four_title', $instance) && isset($instance['geopportal_featapp_four_title']) && !empty($instance['geopportal_featapp_four_title']))
      $geopportal_featapp_disp_four_title = apply_filters('widget_title', $instance['geopportal_featapp_four_title']);
    else
      $geopportal_featapp_disp_four_title = "Performance Dashboard";

    if (array_key_exists('geopportal_featapp_four_link', $instance) && isset($instance['geopportal_featapp_four_link']) && !empty($instance['geopportal_featapp_four_link']))
      $geopportal_featapp_disp_four_link = apply_filters('widget_title', $instance['geopportal_featapp_four_link']);
    else
      $geopportal_featapp_disp_four_link = "https://dashboard.geoplatform.gov/";
    echo $args['before_widget'];?>

    <div class="section--linked">
      <div class="container-fluid">
      <div class="line"></div>
      <h4 class="heading text-centered">
          <div class="line"></div>
          <div class="title darkened"><?php echo $geopportal_featapp_disp_title ?></div>
      </h4>
        <div class="row">
          <div class='col-md-3 col-sm-3'>
            <div class="text--primary">
              <h5 style="margin-right:0.85em;">
                <a href="<?php echo $geopportal_featapp_disp_one_link ?>" target="_blank"><?php echo $geopportal_featapp_disp_one_title ?></a>
              </h5>
            </div><!--#text-primary-->
                <a class="media embed-responsive embed-responsive-16by9"
                    href="<?php echo $geopportal_featapp_disp_one_link ?>" target="_blank" title="Open the <?php echo $geopportal_featapp_disp_one_title ?> Application">
                    <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/map_manager.jpg"); ?>" alt="<?php echo $geopportal_featapp_disp_one_title ?>">
                </a>
          </div><!--#col-md-3 col-sm-3-->

          <div class='col-md-3 col-sm-3 '>
            <div class="text--primary">
              <h5 style="margin-right:0.85em;">
                <a href="<?php echo $geopportal_featapp_disp_two_link ?>" target="_blank"><?php echo $geopportal_featapp_disp_two_title ?></a>
              </h5>
            </div><!--#text-primary-->
                <a class="media embed-responsive embed-responsive-16by9"
                    href="<?php echo $geopportal_featapp_disp_two_link ?>" target="_blank" title="Open the <?php echo $geopportal_featapp_disp_two_title ?> Application">
                    <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/map_viewer.jpg"); ?>" alt="<?php echo $geopportal_featapp_disp_two_title ?>">
                </a>
          </div><!--#col-md-3 col-sm-3-->

          <div class='col-md-3 col-sm-3 '>
            <div class="text--primary">
              <h5 style="margin-right:0.85em;">
                <a href="<?php echo $geopportal_featapp_disp_three_link ?>" target="_blank"><?php echo $geopportal_featapp_disp_three_title ?></a>
              </h5>
            </div><!--#text-primary-->
                <a class="media embed-responsive embed-responsive-16by9"
                    href="<?php echo $geopportal_featapp_disp_three_link ?>" target="_blank" title="Open the <?php echo $geopportal_featapp_disp_three_title ?> Application">
                    <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/marketplace_preview.jpg"); ?>" alt="<?php echo $geopportal_featapp_disp_three_title ?>">
                </a>
          </div><!--#col-md-3 col-sm-3-->

          <div class='col-md-3 col-sm-3 '>
            <div class="text--primary">
              <h5 style="margin-right:0.85em;">
                <a href="<?php echo $geopportal_featapp_disp_four_link ?>" target="_blank"><?php echo $geopportal_featapp_disp_four_title ?></a>
              </h5>
            </div><!--#text-primary-->
                <a class="media embed-responsive embed-responsive-16by9"
                    href="<?php echo $geopportal_featapp_disp_four_link ?>" target="_blank" title="Open the <?php echo $geopportal_featapp_disp_four_title ?> Application">
                    <img class="embed-responsive-item" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/performance_dashboard.jpg"); ?>" alt="<?php echo $geopportal_featapp_disp_four_title ?>">
                </a>
            </div><!--#col-md-3 col-sm-3-->
          </div><!--#row-->
        <br>
      	<div class="footing">
      	    <div class="line-cap"></div>
      	    <div class="line"></div>
      	</div><!--#footing-->
      </div><!--#container-fluid-->
    </div><!--#section-linked-->
    <?php
    echo $args['after_widget'];
	}

	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    $geopportal_featapp_disp_title = ! empty( $instance['geopportal_featapp_title'] ) ? $instance['geopportal_featapp_title'] : 'Featured Applications';
    $geopportal_featapp_disp_one_title = ! empty( $instance['geopportal_featapp_one_title'] ) ? $instance['geopportal_featapp_one_title'] : 'Map Manager';
    $geopportal_featapp_disp_one_link = ! empty( $instance['geopportal_featapp_one_link'] ) ? $instance['geopportal_featapp_one_link'] : 'https://maps.geoplatform.gov/';
    $geopportal_featapp_disp_two_title = ! empty( $instance['geopportal_featapp_two_title'] ) ? $instance['geopportal_featapp_two_title'] : 'Map Viewer';
    $geopportal_featapp_disp_two_link = ! empty( $instance['geopportal_featapp_two_link'] ) ? $instance['geopportal_featapp_two_link'] : 'https://viewer.geoplatform.gov/';
    $geopportal_featapp_disp_three_title = ! empty( $instance['geopportal_featapp_three_title'] ) ? $instance['geopportal_featapp_three_title'] : 'Marketplace Preview';
    $geopportal_featapp_disp_three_link = ! empty( $instance['geopportal_featapp_three_link'] ) ? $instance['geopportal_featapp_three_link'] : 'https://marketplace.geoplatform.gov/';
    $geopportal_featapp_disp_four_title = ! empty( $instance['geopportal_featapp_four_title'] ) ? $instance['geopportal_featapp_four_title'] : 'Performance Dashboard';
    $geopportal_featapp_disp_four_link = ! empty( $instance['geopportal_featapp_four_link'] ) ? $instance['geopportal_featapp_four_link'] : 'https://dashboard.geoplatform.gov/';
    ?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featapp_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_title' ); ?>" value="<?php echo esc_attr( $geopportal_featapp_disp_title ); ?>" />
    </p>
    <hr>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_one_title' ); ?>">First Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featapp_one_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_one_title' ); ?>" value="<?php echo esc_attr($geopportal_featapp_disp_one_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_one_link' ); ?>">First Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featapp_one_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_one_link' ); ?>" value="<?php echo esc_attr( $geopportal_featapp_disp_one_link ); ?>" />
    </p>
    <hr>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_two_title' ); ?>">Second Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featapp_two_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_two_title' ); ?>" value="<?php echo esc_attr($geopportal_featapp_disp_two_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_two_link' ); ?>">Second Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featapp_two_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_two_link' ); ?>" value="<?php echo esc_attr( $geopportal_featapp_disp_two_link ); ?>" />
    </p>
    <hr>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_three_title' ); ?>">Third Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featapp_three_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_three_title' ); ?>" value="<?php echo esc_attr($geopportal_featapp_disp_three_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_three_link' ); ?>">Third Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featapp_three_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_three_link' ); ?>" value="<?php echo esc_attr( $geopportal_featapp_disp_three_link ); ?>" />
    </p>
    <hr>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_four_title' ); ?>">Fourth Service Title:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_featapp_four_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_four_title' ); ?>" value="<?php echo esc_attr($geopportal_featapp_disp_four_title); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_featapp_four_link' ); ?>">Fourth Service Hyperlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_featapp_four_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_featapp_four_link' ); ?>" value="<?php echo esc_attr( $geopportal_featapp_disp_four_link ); ?>" />
    </p>
    <?php
	}

	public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'geopportal_featapp_title' ] = strip_tags( $new_instance[ 'geopportal_featapp_title' ] );
    $instance[ 'geopportal_featapp_one_title' ] = strip_tags( $new_instance[ 'geopportal_featapp_one_title' ] );
    $instance[ 'geopportal_featapp_one_link' ] = strip_tags( $new_instance[ 'geopportal_featapp_one_link' ] );
    $instance[ 'geopportal_featapp_two_title' ] = strip_tags( $new_instance[ 'geopportal_featapp_two_title' ] );
    $instance[ 'geopportal_featapp_two_link' ] = strip_tags( $new_instance[ 'geopportal_featapp_two_link' ] );
    $instance[ 'geopportal_featapp_three_title' ] = strip_tags( $new_instance[ 'geopportal_featapp_three_title' ] );
    $instance[ 'geopportal_featapp_three_link' ] = strip_tags( $new_instance[ 'geopportal_featapp_three_link' ] );
    $instance[ 'geopportal_featapp_four_title' ] = strip_tags( $new_instance[ 'geopportal_featapp_four_title' ] );
    $instance[ 'geopportal_featapp_four_link' ] = strip_tags( $new_instance[ 'geopportal_featapp_four_link' ] );

    return $instance;
  }
}

// Registers and enqueues the widget.
function geopportal_register_portal_featured_frontpage_widget() {
  register_widget( 'Geopportal_Featured_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_featured_frontpage_widget' );
