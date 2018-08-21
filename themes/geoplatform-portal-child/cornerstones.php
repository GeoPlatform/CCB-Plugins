<?php
class Geopportal_Cornerstones_Widget extends WP_Widget {

	// Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_cornerstones_widget', // Base ID
			esc_html__( 'GeoPlatform Cornerstones', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform cornerstones widget for the front page. Requires the Content Blocks plugin.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

	// Handles the widget output.
	public function widget( $args, $instance ) {

		// Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_corn_topleft_title', $instance) && isset($instance['geopportal_corn_topleft_title']) && !empty($instance['geopportal_corn_topleft_title']))
      $geopportal_corn_disp_topleft_title = apply_filters('widget_title', $instance['geopportal_corn_topleft_title']);
		else
      $geopportal_corn_disp_topleft_title = "GeoPlatform Communities";
		if (array_key_exists('geopportal_corn_topleft_link', $instance) && isset($instance['geopportal_corn_topleft_link']) && !empty($instance['geopportal_corn_topleft_link']))
      $geopportal_corn_disp_topleft_link = apply_filters('widget_title', $instance['geopportal_corn_topleft_link']);
		else
      $geopportal_corn_disp_topleft_link = "https://www.geoplatform.gov/communities/";
		if (array_key_exists('geopportal_corn_topleft_content', $instance) && isset($instance['geopportal_corn_topleft_content']) && !empty($instance['geopportal_corn_topleft_content']))
      $geopportal_corn_disp_topleft_content = apply_filters('widget_title', $instance['geopportal_corn_topleft_content']);
		else
      $geopportal_corn_disp_topleft_content = "A key goal of the Geospatial Platform is to expand the use and understanding of National geospatial resources. Active social interaction plays an important role in sharing vital, timely knowledge to keep our collective data, content and services fresh and engaging. The more you participate and contribute, the better the GeoPlatform.gov experience becomes for everyone.";

		if (array_key_exists('geopportal_corn_topright_title', $instance) && isset($instance['geopportal_corn_topright_title']) && !empty($instance['geopportal_corn_topright_title']))
      $geopportal_corn_disp_topright_title = apply_filters('widget_title', $instance['geopportal_corn_topright_title']);
		else
      $geopportal_corn_disp_topright_title = "GeoPlatform on ArcGIS Online";
		if (array_key_exists('geopportal_corn_topright_link', $instance) && isset($instance['geopportal_corn_topright_link']) && !empty($instance['geopportal_corn_topright_link']))
      $geopportal_corn_disp_topright_link = apply_filters('widget_title', $instance['geopportal_corn_topright_link']);
		else
      $geopportal_corn_disp_topright_link = "https://geoplatform.maps.arcgis.com/home/";
		if (array_key_exists('geopportal_corn_topright_content', $instance) && isset($instance['geopportal_corn_topright_content']) && !empty($instance['geopportal_corn_topright_content']))
      $geopportal_corn_disp_topright_content = apply_filters('widget_title', $instance['geopportal_corn_topright_content']);
		else
      $geopportal_corn_disp_topright_content = "The GeoPlatformArcGIS Online (AGOL) organization account provides enhanced web capabilities. Federal partners are able to publish their data as services with no size restrictions. The GeoPlatform builds on AGOL services with the harvesting capability of the GeoPlatform Map Manager.";

		if (array_key_exists('geopportal_corn_botleft_title', $instance) && isset($instance['geopportal_corn_botleft_title']) && !empty($instance['geopportal_corn_botleft_title']))
      $geopportal_corn_disp_botleft_title = apply_filters('widget_title', $instance['geopportal_corn_botleft_title']);
		else
      $geopportal_corn_disp_botleft_title = "Search Data.gov";
		if (array_key_exists('geopportal_corn_botleft_link', $instance) && isset($instance['geopportal_corn_botleft_link']) && !empty($instance['geopportal_corn_botleft_link']))
      $geopportal_corn_disp_botleft_link = apply_filters('widget_title', $instance['geopportal_corn_botleft_link']);
		else
      $geopportal_corn_disp_botleft_link = "https://data.geoplatform.gov/";
		if (array_key_exists('geopportal_corn_botleft_content', $instance) && isset($instance['geopportal_corn_botleft_content']) && !empty($instance['geopportal_corn_botleft_content']))
      $geopportal_corn_disp_botleft_content = apply_filters('widget_title', $instance['geopportal_corn_botleft_content']);
		else
      $geopportal_corn_disp_botleft_content = "Search the <a href='https://data.gov' target='_blank'>Data.gov</a> catalog of geospatial data and tools provided by a multitude of federal agencies. Whether you are a Geographic Professional, Student, Teacher or Citizen, you can find data that will help you with your project, assignment, presentation or concern.";

		if (array_key_exists('geopportal_corn_botright_title', $instance) && isset($instance['geopportal_corn_botright_title']) && !empty($instance['geopportal_corn_botright_title']))
      $geopportal_corn_disp_botright_title = apply_filters('widget_title', $instance['geopportal_corn_botright_title']);
		else
      $geopportal_corn_disp_botright_title = "Explore the Marketplace";
		if (array_key_exists('geopportal_corn_botright_link', $instance) && isset($instance['geopportal_corn_botright_link']) && !empty($instance['geopportal_corn_botright_link']))
      $geopportal_corn_disp_botright_link = apply_filters('widget_title', $instance['geopportal_corn_botright_link']);
		else
      $geopportal_corn_disp_botright_link = "https://marketplace.geoplatform.gov/";
		if (array_key_exists('geopportal_corn_botright_content', $instance) && isset($instance['geopportal_corn_botright_content']) && !empty($instance['geopportal_corn_botright_content']))
      $geopportal_corn_disp_botright_content = apply_filters('widget_title', $instance['geopportal_corn_botright_content']);
		else
      $geopportal_corn_disp_botright_content = "The Marketplace facilitates collaboration on the planned acquisition and production of geospatial data, such as elevation and bathymetric datasets. You can use this filtered catalog search to determine whether a potential partner is already planning to invest in data for which you have a requirement.";

		echo $args['before_widget'];?>

		<div class="cornerstones whatsNew section--linked">
	    <!-- top directional lines and section heading -->
	    <h4 class="heading">
        <div class="line"></div>
        <div class="line-arrow"></div>
    	</h4>
		  <br>
		  <div class="container-fluid">
		    <div class="row">
		      <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
            <div class="row">
              <div class="col-xs-12 col-sm-4 col-sm-push-4">
                <img alt="Communicate and Contribute" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/social_md.jpeg"); ?>" class="img--prominent">
              </div><!--#col-xs-12 col-sm-4 col-sm-push-4-->
              <div class="col-xs-12 col-sm-4 col-sm-pull-4">
                <h5><a href="<?php echo $geopportal_corn_disp_topleft_link ?>" target="_blank"><?php echo $geopportal_corn_disp_topleft_title ?></a></h5>
                <p><?php echo do_shortcode($geopportal_corn_disp_topleft_content) ?></p>
              </div><!--#col-xs-12 col-sm-4 col-sm-pull-4-->
              <div class="col-xs-12 col-sm-4">
                <h5><a href="<?php echo $geopportal_corn_disp_topright_link ?>" target="_blank"><?php echo $geopportal_corn_disp_topright_title ?></a></h5>
                <p><?php echo do_shortcode($geopportal_corn_disp_topright_content) ?></p>
            	</div><!--#col-xs-12 col-sm-4-->
            </div><!--#row-->
            <br>
            <hr>
            <br>
            <div class="row">
              <div class="col-xs-12 col-sm-4 col-sm-push-4">
                <img alt="Search" src="<?php echo esc_url("" . get_stylesheet_directory_uri() . "/img/search_md.jpeg"); ?>" class="img--prominent">
              </div><!--#col-xs-12 col-sm-4 col-sm-push-4-->
              <div class="col-xs-12 col-sm-4 col-sm-pull-4">
								<h5><a href="<?php echo $geopportal_corn_disp_botleft_link ?>" target="_blank"><?php echo $geopportal_corn_disp_botleft_title ?></a></h5>
                <p><?php echo do_shortcode($geopportal_corn_disp_botleft_content) ?></p>
            	</div><!--#col-xs-12 col-sm-4 col-sm-pull-4-->
              <div class="col-xs-12 col-sm-4">
								<h5><a href="<?php echo $geopportal_corn_disp_botright_link ?>" target="_blank"><?php echo $geopportal_corn_disp_botright_title ?></a></h5>
                <p><?php echo do_shortcode($geopportal_corn_disp_botright_content) ?></p>
              </div><!--#col-xs-12 col-sm-4-->
            </div><!--#row-->
          </div><!--#col-xs-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1-->
      	</div><!--#row-->
		  </div><!--#conatiner-fluid-->
	   	<br>
		  <br>
	    <div class="footing">
        <div class="line-cap"></div>
        <div class="line"></div>
	    </div><!--#footing-->
		</div><!--#cornerstones section-linked-->
		<?php
    echo $args['after_widget'];
	}

	// The admin side of the widget
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_corn_cb_bool = false;
		$geopportal_corn_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_corn_cb_bool = true;
			$geopportal_corn_cb_message = "Click here to edit the content block";
		}

		// Top-left input boxes.
		$geopportal_corn_topleft_title = ! empty( $instance['geopportal_corn_topleft_title'] ) ? $instance['geopportal_corn_topleft_title'] : 'GeoPlatform Communities';
		$geopportal_corn_topleft_link = ! empty( $instance['geopportal_corn_topleft_link'] ) ? $instance['geopportal_corn_topleft_link'] : 'https://www.geoplatform.gov/communities/';
		$geopportal_corn_topleft_content = ! empty( $instance['geopportal_corn_topleft_content'] ) ? $instance['geopportal_corn_topleft_content'] : '';

		// Sets up the top-left content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_corn_topleft_content', $instance) && isset($instance['geopportal_corn_topleft_content']) && !empty($instance['geopportal_corn_topleft_content']) && $geopportal_corn_cb_bool){
    	$geopportal_corn_topleft_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_topleft_content' ]);
    	if (is_numeric($geopportal_corn_topleft_temp_url))
      	$geopportal_corn_topleft_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_topleft_temp_url . "&action=edit";
    	else
      	$geopportal_corn_topleft_url = home_url();
		}
		else
			$geopportal_corn_topleft_url = home_url();

		// Top-right input boxes.
		$geopportal_corn_topright_title = ! empty( $instance['geopportal_corn_topright_title'] ) ? $instance['geopportal_corn_topright_title'] : 'GeoPlatform on ArcGIS Online';
		$geopportal_corn_topright_link = ! empty( $instance['geopportal_corn_topright_link'] ) ? $instance['geopportal_corn_topright_link'] : 'https://geoplatform.maps.arcgis.com/home/';
		$geopportal_corn_topright_content = ! empty( $instance['geopportal_corn_topright_content'] ) ? $instance['geopportal_corn_topright_content'] : '';

		// Sets up the top-right content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_corn_topright_content', $instance) && isset($instance['geopportal_corn_topright_content']) && !empty($instance['geopportal_corn_topright_content']) && $geopportal_corn_cb_bool){
    	$geopportal_corn_topright_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_topright_content' ]);
    	if (is_numeric($geopportal_corn_topright_temp_url))
      	$geopportal_corn_topright_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_topright_temp_url . "&action=edit";
    	else
      	$geopportal_corn_topright_url = home_url();
		}
		else
			$geopportal_corn_topright_url = home_url();

		// Bottom-left input boxes.
		$geopportal_corn_botleft_title = ! empty( $instance['geopportal_corn_botleft_title'] ) ? $instance['geopportal_corn_botleft_title'] : 'Search Data.gov';
		$geopportal_corn_botleft_link = ! empty( $instance['geopportal_corn_botleft_link'] ) ? $instance['geopportal_corn_botleft_link'] : 'https://data.geoplatform.gov/';
		$geopportal_corn_botleft_content = ! empty( $instance['geopportal_corn_botleft_content'] ) ? $instance['geopportal_corn_botleft_content'] : '';

		// Sets up the bottom-left content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_corn_botleft_content', $instance) && isset($instance['geopportal_corn_botleft_content']) && !empty($instance['geopportal_corn_botleft_content']) && $geopportal_corn_cb_bool){
    	$geopportal_corn_botleft_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_botleft_content' ]);
    	if (is_numeric($geopportal_corn_botleft_temp_url))
      	$geopportal_corn_botleft_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_botleft_temp_url . "&action=edit";
    	else
      	$geopportal_corn_botleft_url = home_url();
		}
		else
			$geopportal_corn_botleft_url = home_url();

		// Bottom-right input boxes.
		$geopportal_corn_botright_title = ! empty( $instance['geopportal_corn_botright_title'] ) ? $instance['geopportal_corn_botright_title'] : 'Explore the Marketplace';
		$geopportal_corn_botright_link = ! empty( $instance['geopportal_corn_botright_link'] ) ? $instance['geopportal_corn_botright_link'] : 'https://marketplace.geoplatform.gov/';
		$geopportal_corn_botright_content = ! empty( $instance['geopportal_corn_botright_content'] ) ? $instance['geopportal_corn_botright_content'] : '';

		// Sets up the bottom-right content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_corn_botright_content', $instance) && isset($instance['geopportal_corn_botright_content']) && !empty($instance['geopportal_corn_botright_content']) && $geopportal_corn_cb_bool){
    	$geopportal_corn_botright_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_botright_content' ]);
    	if (is_numeric($geopportal_corn_botright_temp_url))
      	$geopportal_corn_botright_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_botright_temp_url . "&action=edit";
    	else
      	$geopportal_corn_botright_url = home_url();
		}
		else
			$geopportal_corn_botright_url = home_url(); ?>


<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_topleft_title' ); ?>">Top-Left Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_topleft_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_topleft_title' ); ?>" value="<?php echo esc_attr( $geopportal_corn_topleft_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_topleft_link' ); ?>">Top-Left Hotlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_topleft_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_topleft_link' ); ?>" value="<?php echo esc_url( $geopportal_corn_topleft_link ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_topleft_content' ); ?>">Top-Left Content Block Shortcode:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_corn_topleft_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_topleft_content' ); ?>" value="<?php echo esc_attr($geopportal_corn_topleft_content); ?>" />
      <a href="<?php echo esc_url($geopportal_corn_topleft_url); ?>" target="_blank"><?php _e($geopportal_corn_cb_message, 'geoplatform-ccb') ?></a><br>
    </p>
		<hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_topright_title' ); ?>">Top-Right Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_topright_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_topright_title' ); ?>" value="<?php echo esc_attr( $geopportal_corn_topright_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_topright_link' ); ?>">Top-Right Hotlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_topright_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_topright_link' ); ?>" value="<?php echo esc_url( $geopportal_corn_topright_link ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_topright_content' ); ?>">Top-Right Content Block Shortcode:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_corn_topright_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_topright_content' ); ?>" value="<?php echo esc_attr($geopportal_corn_topright_content); ?>" />
      <a href="<?php echo esc_url($geopportal_corn_topright_url); ?>" target="_blank"><?php _e($geopportal_corn_cb_message, 'geoplatform-ccb') ?></a><br>
    </p>
		<hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_botleft_title' ); ?>">Bottom-Left Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_botleft_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_botleft_title' ); ?>" value="<?php echo esc_attr( $geopportal_corn_botleft_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_botleft_link' ); ?>">Bottom-Left Hotlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_botleft_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_botleft_link' ); ?>" value="<?php echo esc_url( $geopportal_corn_botleft_link ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_botleft_content' ); ?>">Bottom-Left Content Block Shortcode:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_corn_botleft_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_botleft_content' ); ?>" value="<?php echo esc_attr($geopportal_corn_botleft_content); ?>" />
      <a href="<?php echo esc_url($geopportal_corn_botleft_url); ?>" target="_blank"><?php _e($geopportal_corn_cb_message, 'geoplatform-ccb') ?></a><br>
    </p>
		<hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_botright_title' ); ?>">Bottom-Right Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_botright_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_botright_title' ); ?>" value="<?php echo esc_attr( $geopportal_corn_botright_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_botright_link' ); ?>">Bottom-Right Hotlink:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_corn_botright_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_botright_link' ); ?>" value="<?php echo esc_url( $geopportal_corn_botright_link ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_corn_botright_content' ); ?>">Bottom-Right Content Block Shortcode:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_corn_botright_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_corn_botright_content' ); ?>" value="<?php echo esc_attr($geopportal_corn_botright_content); ?>" />
      <a href="<?php echo esc_url($geopportal_corn_botright_url); ?>" target="_blank"><?php _e($geopportal_corn_cb_message, 'geoplatform-ccb') ?></a><br>
    </p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_corn_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_corn_cb_bool = true;

		$instance[ 'geopportal_corn_topleft_title' ] = strip_tags( $new_instance[ 'geopportal_corn_topleft_title' ] );
	  $instance[ 'geopportal_corn_topleft_link' ] = strip_tags( $new_instance[ 'geopportal_corn_topleft_link' ] );
	  $instance[ 'geopportal_corn_topleft_content' ] = strip_tags( $new_instance[ 'geopportal_corn_topleft_content' ] );
		$instance[ 'geopportal_corn_topleft_url' ] = strip_tags( $new_instance[ 'geopportal_corn_topleft_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_corn_topleft_content', $instance) && isset($instance['geopportal_corn_topleft_content']) && !empty($instance['geopportal_corn_topleft_content']) && $geopportal_corn_cb_bool){
	  	$geopportal_corn_topleft_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_topleft_content' ]);
	  	if (is_numeric($geopportal_corn_topleft_temp_url))
	    	$geopportal_corn_topleft_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_topleft_temp_url . "&action=edit";
	  	else
	    	$geopportal_corn_topleft_url = home_url();
		}
		else
			$geopportal_corn_topleft_url = home_url();

		$instance[ 'geopportal_corn_topright_title' ] = strip_tags( $new_instance[ 'geopportal_corn_topright_title' ] );
	  $instance[ 'geopportal_corn_topright_link' ] = strip_tags( $new_instance[ 'geopportal_corn_topright_link' ] );
	  $instance[ 'geopportal_corn_topright_content' ] = strip_tags( $new_instance[ 'geopportal_corn_topright_content' ] );
		$instance[ 'geopportal_corn_topright_url' ] = strip_tags( $new_instance[ 'geopportal_corn_topright_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_corn_topright_content', $instance) && isset($instance['geopportal_corn_topright_content']) && !empty($instance['geopportal_corn_topright_content']) && $geopportal_corn_cb_bool){
	  	$geopportal_corn_topright_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_topright_content' ]);
	  	if (is_numeric($geopportal_corn_topright_temp_url))
	    	$geopportal_corn_topright_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_topright_temp_url . "&action=edit";
	  	else
	    	$geopportal_corn_topright_url = home_url();
		}
		else
			$geopportal_corn_topright_url = home_url();

	  $instance[ 'geopportal_corn_botleft_title' ] = strip_tags( $new_instance[ 'geopportal_corn_botleft_title' ] );
	  $instance[ 'geopportal_corn_botleft_link' ] = strip_tags( $new_instance[ 'geopportal_corn_botleft_link' ] );
	  $instance[ 'geopportal_corn_botleft_content' ] = strip_tags( $new_instance[ 'geopportal_corn_botleft_content' ] );
		$instance[ 'geopportal_corn_botleft_url' ] = strip_tags( $new_instance[ 'geopportal_corn_botleft_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_corn_botleft_content', $instance) && isset($instance['geopportal_corn_botleft_content']) && !empty($instance['geopportal_corn_botleft_content']) && $geopportal_corn_cb_bool){
	  	$geopportal_corn_botleft_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_botleft_content' ]);
	  	if (is_numeric($geopportal_corn_botleft_temp_url))
	    	$geopportal_corn_botleft_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_botleft_temp_url . "&action=edit";
	  	else
	    	$geopportal_corn_botleft_url = home_url();
		}
		else
			$geopportal_corn_botleft_url = home_url();

		$instance[ 'geopportal_corn_botright_title' ] = strip_tags( $new_instance[ 'geopportal_corn_botright_title' ] );
	  $instance[ 'geopportal_corn_botright_link' ] = strip_tags( $new_instance[ 'geopportal_corn_botright_link' ] );
	  $instance[ 'geopportal_corn_botright_content' ] = strip_tags( $new_instance[ 'geopportal_corn_botright_content' ] );
		$instance[ 'geopportal_corn_botright_url' ] = strip_tags( $new_instance[ 'geopportal_corn_botright_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_corn_botright_content', $instance) && isset($instance['geopportal_corn_botright_content']) && !empty($instance['geopportal_corn_botright_content']) && $geopportal_corn_cb_bool){
	  	$geopportal_corn_botright_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_corn_botright_content' ]);
	  	if (is_numeric($geopportal_corn_botright_temp_url))
	    	$geopportal_corn_botright_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_corn_botright_temp_url . "&action=edit";
	  	else
	    	$geopportal_corn_botright_url = home_url();
		}
		else
			$geopportal_corn_botright_url = home_url();

	  return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_cornerstones_widget() {
		register_widget( 'Geopportal_Cornerstones_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_cornerstones_widget' );
