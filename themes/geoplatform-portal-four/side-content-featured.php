<?php
class Geopportal_Side_Content_Featured_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_side_content_featured_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Featured', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform sidebar content widget for featured post display. Requires posts to be set in the Featured Pages section.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_side_cont_feat_title', $instance) && isset($instance['geopportal_side_cont_feat_title']) && !empty($instance['geopportal_side_cont_feat_title']))
      $geopportal_side_cont_feat_title = apply_filters('widget_title', $instance['geopportal_side_cont_feat_title']);
		else
      $geopportal_side_cont_feat_title = "Side Content";
		if (array_key_exists('geopportal_side_cont_feat_content', $instance) && isset($instance['geopportal_side_cont_feat_content']) && !empty($instance['geopportal_side_cont_feat_content']))
	     $geopportal_side_cont_feat_content = apply_filters('widget_title', $instance['geopportal_side_cont_feat_content']);
		else
	     $geopportal_side_cont_feat_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
		if (array_key_exists('geopportal_side_cont_feat_count', $instance) && isset($instance['geopportal_side_cont_feat_count']) && !empty($instance['geopportal_side_cont_feat_count']))
      $geopportal_side_cont_feat_count = apply_filters('widget_title', $instance['geopportal_side_cont_feat_count']);
		else
    	$geopportal_side_cont_feat_count = 3;

    //Creates an empty array and fills it based upon the input of count above.
		$geopportal_feat_pages = array();
		array_push($geopportal_feat_pages, get_page_by_path(get_theme_mod('featured_primary_post'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post')));
		if ($geopportal_side_cont_feat_count >= 2)
			array_push($geopportal_feat_pages, get_page_by_path(get_theme_mod('featured_secondary_one'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post')));
		if ($geopportal_side_cont_feat_count >= 3)
			array_push($geopportal_feat_pages, get_page_by_path(get_theme_mod('featured_secondary_two'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post')));
		if ($geopportal_side_cont_feat_count >= 4)
			array_push($geopportal_feat_pages, get_page_by_path(get_theme_mod('featured_secondary_three'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post')));
		if ($geopportal_side_cont_feat_count >= 5)
			array_push($geopportal_feat_pages, get_page_by_path(get_theme_mod('featured_secondary_four'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post')));
		?>

		<!--
		SIDEBAR CONTENT LINKS
		-->
		<article class="m-article">
      <div class="m-article__heading"><?php _e(sanitize_text_field($geopportal_side_cont_feat_title), 'geoplatform-ccb') ?></div>
			<div class="m-article__desc">
				<?php echo do_shortcode($geopportal_side_cont_feat_content) ?>
      </div>
			<div class="m-article__desc m-list">

				<?php
				foreach ($geopportal_feat_pages as $geopportal_post){

					// Makes sure the excerpt is only one sentence.
					$geopportal_post_excerpt = $geopportal_post->post_excerpt;

					if (!empty($geopportal_post->post_excerpt)){
						$geopportal_post_explode = explode('.', $geopportal_post->post_excerpt);
						$geopportal_post_excerpt = $geopportal_post_explode[0] . ".";
					}
				?>

        <div class="m-list__item">
          <a class="is-linkless" href="<?php echo get_the_permalink($geopportal_post); ?>"><?php echo get_the_title($geopportal_post); ?></a>
          <!-- <div class="m-list__item__text"><?php //_e(sanitize_text_field($geopportal_post_excerpt), 'geoplatform-ccb') ?></div> -->
        </div>

				<?php	} ?>

      </div>
    </article>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_side_cont_feat_cb_bool = false;
		$geopportal_side_cont_feat_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_side_cont_feat_cb_bool = true;
			$geopportal_side_cont_feat_cb_message = "Click here to edit this content block";
		}

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_side_cont_feat_title = ! empty( $instance['geopportal_side_cont_feat_title'] ) ? $instance['geopportal_side_cont_feat_title'] : 'Features &amp; Announcements';
		$geopportal_side_cont_feat_title = ! empty( $instance['geopportal_side_cont_feat_title'] ) ? $instance['geopportal_side_cont_feat_title'] : 'Themes';
		$geopportal_side_cont_feat_content = ! empty( $instance['geopportal_side_cont_feat_content'] ) ? $instance['geopportal_side_cont_feat_content'] : '';
		$geopportal_side_cont_feat_count = ! empty( $instance['geopportal_side_cont_feat_count'] ) ? $instance['geopportal_side_cont_feat_count'] : 3;
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('Count is limited to between 1 and 5.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_side_cont_feat_title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_side_cont_feat_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_side_cont_feat_title' ); ?>" value="<?php echo esc_attr( $geopportal_side_cont_feat_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_side_cont_feat_content' ); ?>">Content Block Shortcode:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_side_cont_feat_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_side_cont_feat_content' ); ?>" value="<?php echo esc_attr( $geopportal_side_cont_feat_content ); ?>" />
			<a href="<?php echo esc_url($geopportal_side_cont_feat_url); ?>" target="_blank"><?php _e($geopportal_side_cont_feat_cb_message, 'geoplatform-ccb') ?></a><br>
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_side_cont_feat_count' ); ?>">Output Count:</label>
			<input type="number" id="<?php echo $this->get_field_id( 'geopportal_side_cont_feat_count' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_side_cont_feat_count' ); ?>" value="<?php echo esc_attr( $geopportal_side_cont_feat_count ); ?>" min="1" max="5" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_side_cont_feat_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_side_cont_feat_cb_bool = true;

		$instance[ 'geopportal_side_cont_feat_title' ] = strip_tags( $new_instance[ 'geopportal_side_cont_feat_title' ] );
		$instance[ 'geopportal_side_cont_feat_content' ] = strip_tags( $new_instance[ 'geopportal_side_cont_feat_content' ] );
		$instance[ 'geopportal_side_cont_feat_count' ] = strip_tags( $new_instance[ 'geopportal_side_cont_feat_count' ] );

		// Validity check for the content box URL.
		if (array_key_exists('geopportal_side_cont_feat_content', $instance) && isset($instance['geopportal_side_cont_feat_content']) && !empty($instance['geopportal_side_cont_feat_content']) && $geopportal_side_cont_feat_cb_bool){
	  	$geopportal_side_cont_feat_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_side_cont_feat_content' ]);
	  	if (is_numeric($geopportal_side_cont_feat_temp_url))
	    	$geopportal_side_cont_feat_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_side_cont_feat_temp_url . "&action=edit";
	  	else
	    	$geopportal_side_cont_feat_url = home_url();
		}
		else
			$geopportal_side_cont_feat_url = home_url();

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_side_cont_feat_widget() {
		register_widget( 'Geopportal_Side_Content_Featured_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_side_cont_feat_widget' );
