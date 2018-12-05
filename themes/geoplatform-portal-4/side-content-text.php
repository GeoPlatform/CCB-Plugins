<?php
class Geopportal_Side_Content_Text_Widget extends WP_Widget {

	// Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_side_content_text_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Text', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform side content widget for sidebar text display. Requires the Content Blocks plugin.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

	// Handles the widget output.
	public function widget( $args, $instance ) {

		// Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_side_cont_text_title', $instance) && isset($instance['geopportal_side_cont_text_title']) && !empty($instance['geopportal_side_cont_text_title']))
      $geopportal_side_cont_text_title = apply_filters('widget_title', $instance['geopportal_side_cont_text_title']);
		else
      $geopportal_side_cont_text_title = "Side Content";
		if (array_key_exists('geopportal_side_cont_text_content', $instance) && isset($instance['geopportal_side_cont_text_content']) && !empty($instance['geopportal_side_cont_text_content']))
      $geopportal_side_cont_text_content = apply_filters('widget_title', $instance['geopportal_side_cont_text_content']);
		else
      $geopportal_side_cont_text_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		?>

		<!--
		SIDEBAR CONTENT TEXT SECTION
		-->
		<article class="m-article">
      <div class="m-article__heading"><?php echo sanitize_text_field($geopportal_side_cont_text_title) ?></div>
      <div class="m-article__desc">
        <?php echo do_shortcode($geopportal_side_cont_text_content) ?>
      </div>
    </article>
		<?php
	}

	// The admin side of the widget
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_side_cont_text_cb_bool = false;
		$geopportal_side_cont_text_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_side_cont_text_cb_bool = true;
			$geopportal_side_cont_text_cb_message = "Click here to edit this content block";
		}

		// Input boxes.
		$geopportal_side_cont_text_title = ! empty( $instance['geopportal_side_cont_text_title'] ) ? $instance['geopportal_side_cont_text_title'] : 'Side Content';
		$geopportal_side_cont_text_content = ! empty( $instance['geopportal_side_cont_text_content'] ) ? $instance['geopportal_side_cont_text_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';

		// Sets up the content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_side_cont_text_content', $instance) && isset($instance['geopportal_side_cont_text_content']) && !empty($instance['geopportal_side_cont_text_content']) && $geopportal_side_cont_text_cb_bool){
    	$geopportal_side_cont_text_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_side_cont_text_content' ]);
    	if (is_numeric($geopportal_side_cont_text_temp_url))
      	$geopportal_side_cont_text_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_side_cont_text_temp_url . "&action=edit";
    	else
      	$geopportal_side_cont_text_url = home_url();
		}
		else
			$geopportal_side_cont_text_url = home_url();
 ?>

<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_side_cont_text_title' ); ?>">Main Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_side_cont_text_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_side_cont_text_title' ); ?>" value="<?php echo esc_attr( $geopportal_side_cont_text_title ); ?>" />
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_side_cont_text_content' ); ?>">Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_side_cont_text_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_side_cont_text_content' ); ?>" value="<?php echo esc_attr($geopportal_side_cont_text_content); ?>" />
			<a href="<?php echo esc_url($geopportal_side_cont_text_url); ?>" target="_blank"><?php _e($geopportal_side_cont_text_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_side_cont_text_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_side_cont_text_cb_bool = true;

		$instance[ 'geopportal_side_cont_text_title' ] = strip_tags( $new_instance[ 'geopportal_side_cont_text_title' ] );
	  $instance[ 'geopportal_side_cont_text_content' ] = strip_tags( $new_instance[ 'geopportal_side_cont_text_content' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_side_cont_text_content', $instance) && isset($instance['geopportal_side_cont_text_content']) && !empty($instance['geopportal_side_cont_text_content']) && $geopportal_side_cont_text_cb_bool){
	  	$geopportal_side_cont_text_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_side_cont_text_content' ]);
	  	if (is_numeric($geopportal_side_cont_text_temp_url))
	    	$geopportal_side_cont_text_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_side_cont_text_temp_url . "&action=edit";
	  	else
	    	$geopportal_side_cont_text_url = home_url();
		}
		else
			$geopportal_side_cont_text_url = home_url();

	  return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_side_content_text_widget() {
		register_widget( 'Geopportal_Side_Content_Text_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_side_content_text_widget' );
