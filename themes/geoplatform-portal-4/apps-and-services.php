<?php
class Geopportal_Apps_Services_Widget extends WP_Widget {

  // Constructor, simple.
	function __construct() {
	   parent::__construct(
  		'geopportal_apps_services_widget', // Base ID
  		esc_html__( 'GeoPlatform Apps & Services', 'geoplatform-ccb' ), // Name
  		array( 'description' => esc_html__( 'GeoPlatform Apps & Services widget for the front page. Requires the Content Blocks plugin.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true ) // Args
  	);
  }

  // Handles widget output
	public function widget( $args, $instance ) {
    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    if (array_key_exists('geopportal_apps_service_title', $instance) && isset($instance['geopportal_apps_service_title']) && !empty($instance['geopportal_apps_service_title']))
      $geopportal_apps_service_title = apply_filters('widget_title', $instance['geopportal_apps_service_title']);
    else
      $geopportal_apps_service_title = "Apps & Services";
    if (array_key_exists('geopportal_apps_service_content', $instance) && isset($instance['geopportal_apps_service_content']) && !empty($instance['geopportal_apps_service_content']))
      $geopportal_apps_service_content = apply_filters('widget_title', $instance['geopportal_apps_service_content']);
    else
      $geopportal_apps_service_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
		if (array_key_exists('geopportal_apps_service_link', $instance) && isset($instance['geopportal_apps_service_link']) && !empty($instance['geopportal_apps_service_link']))
      $geopportal_apps_service_link = apply_filters('widget_title', $instance['geopportal_apps_service_link']);
    else
      $geopportal_apps_service_link = home_url();

		// This file is for the main page account widget. For the sidebar widget in posts, see account.php.

		?>
		<article class="p-landing-page__apps" style="background-image:url('<?php echo get_stylesheet_directory_uri() . '/img/wave-green.svg' ?>')">
				<div class="m-article__heading m-article__heading--front-page"><?php _e(sanitize_text_field($geopportal_apps_service_title), 'geoplatform-ccb') ?></div>
				<div class="m-apps__content">
						<img alt="Apps" src="<?php echo get_stylesheet_directory_uri() . '/img/apps.svg' ?>">
						<div class="flex-1">
								<div class="a-summary">
									<?php echo do_shortcode($geopportal_apps_service_content) ?>
								</div>
								<br>
								<a class="btn btn-info" href="<?php echo esc_url($geopportal_apps_service_link); ?>">Learn More</a>
						</div>
				</div>
		</article>
    <?php
	}

  // admin side of the widget
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_apps_service_cb_bool = false;
		$geopportal_apps_service_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_apps_service_cb_bool = true;
			$geopportal_apps_service_cb_message = "Click here to edit this content block";
		}

    // Input boxes and defaults.
		$geopportal_apps_service_title = ! empty( $instance['geopportal_apps_service_title'] ) ? $instance['geopportal_apps_service_title'] : 'Apps & Services';
		$geopportal_apps_service_content = ! empty( $instance['geopportal_apps_service_content'] ) ? $instance['geopportal_apps_service_content'] : '';
    $geopportal_apps_service_link = ! empty( $instance['geopportal_apps_service_link'] ) ? $instance['geopportal_apps_service_link'] : '';

		// Sets up the content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_apps_service_content', $instance) && isset($instance['geopportal_apps_service_content']) && !empty($instance['geopportal_apps_service_content']) && $geopportal_apps_service_cb_bool){
    	$geopportal_apps_service_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_apps_service_content' ]);
    	if (is_numeric($geopportal_apps_service_temp_url))
      	$geopportal_apps_service_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_apps_service_temp_url . "&action=edit";
    	else
      	$geopportal_apps_service_url = home_url();
		}
		else
			$geopportal_apps_service_url = home_url();?>

		<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apps_service_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_apps_service_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apps_service_title' ); ?>" value="<?php echo esc_attr( $geopportal_apps_service_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apps_service_content' ); ?>">Content Blocks Shortcode:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_apps_service_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apps_service_content' ); ?>" value="<?php echo esc_attr( $geopportal_apps_service_content ); ?>" />
			<a href="<?php echo esc_url($geopportal_apps_service_url); ?>" target="_blank"><?php _e($geopportal_apps_service_cb_message, 'geoplatform-ccb') ?></a><br>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_apps_service_link' ); ?>">Learn More URL:</label><br>
      <input type="text"  id="<?php echo $this->get_field_id( 'geopportal_apps_service_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_apps_service_link' ); ?>" value="<?php echo esc_attr($geopportal_apps_service_link); ?>" />
    </p>
    <?php
	}

	public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
		$instance[ 'geopportal_apps_service_title' ] = strip_tags( $new_instance[ 'geopportal_apps_service_title' ] );
	  $instance[ 'geopportal_apps_service_content' ] = strip_tags( $new_instance[ 'geopportal_apps_service_content' ] );
	  $instance[ 'geopportal_apps_service_link' ] = strip_tags( $new_instance[ 'geopportal_apps_service_link' ] );

		// Checks if the Content Boxes plugin is installed.
		$geopportal_apps_service_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_apps_service_cb_bool = true;

    // Validity check for the content box URL.
		if (array_key_exists('geopportal_apps_service_content', $instance) && isset($instance['geopportal_apps_service_content']) && !empty($instance['geopportal_apps_service_content']) && $geopportal_apps_service_cb_bool){
	  	$geopportal_apps_service_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_apps_service_content' ]);
	  	if (is_numeric($geopportal_apps_service_temp_url))
	    	$geopportal_apps_service_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_apps_service_temp_url . "&action=edit";
	  	else
	    	$geopportal_apps_service_url = home_url();
		}
		else
			$geopportal_apps_service_url = home_url();

	  return $instance;
  }
}

// Registers and enqueues the widget.
function geopportal_register_portal_apps_services_widget() {
  register_widget( 'Geopportal_Apps_Services_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_apps_services_widget' );
