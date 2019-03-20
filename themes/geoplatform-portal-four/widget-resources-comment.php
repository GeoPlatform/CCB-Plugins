<?php
class Geopportal_Resource_Comment_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_comment_widget', // Base ID
			esc_html__( 'GeoPlatform Resource Commentary', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Commentary widget for resource pages. Accepts a content block shortcode and outputs its results in the widget area. Simple and to the point. Requires the Content Blocks plugin.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_comment_content', $instance) && isset($instance['geopportal_comment_content']) && !empty($instance['geopportal_comment_content']))
      $geopportal_comment_content = apply_filters('widget_title', $instance['geopportal_comment_content']);
		else
      $geopportal_comment_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		?>

		<!--
		ELEMENTS
		-->
		<div class="m-section-group">
				<article class="m-article">
					<div class="m-article__desc">
						<?php echo do_shortcode($geopportal_comment_content) ?>
					</div>
				</article>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_comment_bool = false;
		$geopportal_comment_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_comment_bool = true;
			$geopportal_comment_message = "Click here to edit this content block";
		}

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopportal_comment_content = ! empty( $instance['geopportal_comment_content'] ) ? $instance['geopportal_comment_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';

		// Sets up the first content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_comment_content', $instance) && isset($instance['geopportal_comment_content']) && !empty($instance['geopportal_comment_content']) && $geopportal_comment_bool){
    	$geopportal_comment_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_comment_content' ]);
    	if (is_numeric($geopportal_comment_temp_url))
      	$geopportal_comment_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_comment_temp_url . "&action=edit";
    	else
      	$geopportal_comment_url = home_url();
		}
		else
			$geopportal_comment_url = home_url();

		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('Please make sure that the Content Blocks plugin is active and a valid shortcode input.', 'geoplatform-ccb'); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_comment_content' ); ?>">Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_comment_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_comment_content' ); ?>" value="<?php echo esc_attr($geopportal_comment_content); ?>" />
			<a href="<?php echo esc_url($geopportal_comment_url); ?>" target="_blank"><?php _e($geopportal_comment_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_content_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_content_bool = true;

	  $instance[ 'geopportal_comment_content' ] = strip_tags( $new_instance[ 'geopportal_comment_content' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_comment_content', $instance) && isset($instance['geopportal_comment_content']) && !empty($instance['geopportal_comment_content']) && $geopportal_content_cb_bool){
	  	$geopportal_content_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_comment_content' ]);
	  	if (is_numeric($geopportal_content_temp_url))
	    	$geopportal_content_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_content_temp_url . "&action=edit";
	  	else
	    	$geopportal_content_url = home_url();
		}
		else
			$geopportal_content_url = home_url();

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_comment_widget() {
		register_widget( 'Geopportal_Resource_Comment_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_comment_widget' );
