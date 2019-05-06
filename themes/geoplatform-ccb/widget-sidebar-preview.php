<?php
class Geopportal_Side_Content_Preview_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_side_content_prev_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Image', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( "GeoPlatform Image widget for the sidebar. Will display the current post's featured image. An optional title input is also present.", 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_side_cont_prev_title', $instance) && isset($instance['geopportal_side_cont_prev_title']) && !empty($instance['geopportal_side_cont_prev_title']))
      $geopportal_side_cont_prev_title = apply_filters('widget_title', $instance['geopportal_side_cont_prev_title']);
		else
      $geopportal_side_cont_prev_title = "";

		$geopporatl_current_post = get_queried_object();

		$geopportal_side_cont_prev_thumb = get_template_directory_uri() . '/img/img-404.png';
		if ($geopporatl_current_post){
			if ( has_post_thumbnail($geopporatl_current_post) )
				$geopportal_side_cont_prev_thumb = get_the_post_thumbnail_url($geopporatl_current_post);
		}

		$geopportal_side_cont_prev_excerpt = "";
		// if (!empty($geopportal_side_cont_prev_page->post_excerpt))
		//   $geopportal_side_cont_prev_excerpt = esc_attr(wp_strip_all_tags($geopportal_side_cont_prev_page->post_excerpt));
		?>

		<!--
		SIDEBAR CONTENT PREVIEW
		-->
		<article class="m-article">
      <div class="m-article__heading"><?php _e(sanitize_text_field($geopportal_side_cont_prev_title), 'geoplatform-ccb') ?></div>
      <div class="m-article__desc" style="max-width:320px">
        <img src="<?php echo $geopportal_side_cont_prev_thumb ?>">
        <?php echo $geopportal_side_cont_prev_excerpt; ?>
      </div>
    </article>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
		$geopportal_side_cont_prev_title = ! empty( $instance['geopportal_side_cont_prev_title'] ) ? $instance['geopportal_side_cont_prev_title'] : '';
		// $geopportal_side_cont_prev_link = ! empty( $instance['geopportal_side_cont_prev_link'] ) ? $instance['geopportal_side_cont_prev_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('The featured image of the current page will be displayed automatically. You may insert an optional title, but it will display on all pages with a sidebar.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_side_cont_prev_title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_side_cont_prev_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_side_cont_prev_title' ); ?>" value="<?php echo esc_attr( $geopportal_side_cont_prev_title ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

    // $instance[ 'geopportal_side_cont_prev_title' ] = strip_tags( $new_instance[ 'geopportal_side_cont_prev_title' ] );
		$instance[ 'geopportal_side_cont_prev_title' ] = strip_tags( $new_instance[ 'geopportal_side_cont_prev_title' ] );
		// $instance[ 'geopportal_side_cont_prev_link' ] = strip_tags( $new_instance[ 'geopportal_side_cont_prev_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_side_cont_prev_widget() {
		register_widget( 'Geopportal_Side_Content_Preview_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_side_cont_prev_widget' );
