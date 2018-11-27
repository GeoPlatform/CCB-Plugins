<?php
class Geopportal_MainPage_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_mainpage_widget', // Base ID
			esc_html__( 'GeoPlatform Featured', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform featured articles widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    // if (array_key_exists('geopportal_mainpage_title', $instance) && isset($instance['geopportal_mainpage_title']) && !empty($instance['geopportal_mainpage_title']))
    //   $geopportal_mainpage_disp_title = apply_filters('widget_title', $instance['geopportal_mainpage_title']);
		// else
    //   $geopportal_mainpage_disp_title = "Features &amp; Announcements";

		if (array_key_exists('geopportal_mainpage_first_link', $instance) && isset($instance['geopportal_mainpage_first_link']) && !empty($instance['geopportal_mainpage_first_link']))
      $geopportal_mainpage_disp_first_link = apply_filters('widget_title', $instance['geopportal_mainpage_first_link']);
		else
      $geopportal_mainpage_disp_first_link = "";
		if (array_key_exists('geopportal_mainpage_first_subtitle', $instance) && isset($instance['geopportal_mainpage_first_subtitle']) && !empty($instance['geopportal_mainpage_first_subtitle']))
      $geopportal_mainpage_disp_first_subtitle = apply_filters('widget_title', $instance['geopportal_mainpage_first_subtitle']);
		else
      $geopportal_mainpage_disp_first_subtitle = "";

		if (array_key_exists('geopportal_mainpage_second_link', $instance) && isset($instance['geopportal_mainpage_second_link']) && !empty($instance['geopportal_mainpage_second_link']))
      $geopportal_mainpage_disp_second_link = apply_filters('widget_title', $instance['geopportal_mainpage_second_link']);
		else
    	$geopportal_mainpage_disp_second_link = "";

		if (array_key_exists('geopportal_mainpage_third_link', $instance) && isset($instance['geopportal_mainpage_third_link']) && !empty($instance['geopportal_mainpage_third_link']))
      $geopportal_mainpage_disp_third_link = apply_filters('widget_title', $instance['geopportal_mainpage_third_link']);
		else
      $geopportal_mainpage_disp_third_link = "";

		if (array_key_exists('geopportal_mainpage_more_count', $instance) && isset($instance['geopportal_mainpage_more_count']) && !empty($instance['geopportal_mainpage_more_count']))
			$geopportal_mainpage_disp_more_count = apply_filters('widget_title', $instance['geopportal_mainpage_more_count']);
		else
			$geopportal_mainpage_disp_more_count = "6";

		if (array_key_exists('geopportal_mainpage_browse_link', $instance) && isset($instance['geopportal_mainpage_browse_link']) && !empty($instance['geopportal_mainpage_browse_link']))
			$geopportal_mainpage_disp_browse_link = apply_filters('widget_title', $instance['geopportal_mainpage_browse_link']);
		else
			$geopportal_mainpage_disp_browse_link = "#";

		// Turns the slugs into pages.
		$geopportal_mainpage_disp_first_page = get_page_by_path($geopportal_mainpage_disp_first_link, OBJECT, 'post');
		$geopportal_mainpage_disp_second_page = get_page_by_path($geopportal_mainpage_disp_second_link, OBJECT, 'post');
		$geopportal_mainpage_disp_third_page = get_page_by_path($geopportal_mainpage_disp_third_link, OBJECT, 'post');

		// Sets up default thumbnails and overwrites if post has one.
		$geopportal_mainpage_disp_first_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_second_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_third_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';

		if ( has_post_thumbnail($geopportal_mainpage_disp_first_page) )
			$geopportal_mainpage_disp_first_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_first_page);
		if ( has_post_thumbnail($geopportal_mainpage_disp_second_page) )
			$geopportal_mainpage_disp_second_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_second_page);
		if ( has_post_thumbnail($geopportal_mainpage_disp_third_page) )
			$geopportal_mainpage_disp_third_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_third_page);

		// Sets up invalid post notices for dates and overwrites if the associated post is valid with an actual date.
		$geopportal_mainpage_disp_first_date = "";
		$geopportal_mainpage_disp_second_date = "";
		$geopportal_mainpage_disp_third_date = "";

		if ( isset($geopportal_mainpage_disp_first_page->ID) )
			$geopportal_mainpage_disp_first_date = get_the_date("F j, Y", $geopportal_mainpage_disp_first_page->ID);
		if ( isset($geopportal_mainpage_disp_second_page->ID) )
			$geopportal_mainpage_disp_second_date = get_the_date("F j, Y", $geopportal_mainpage_disp_second_page->ID);
		if ( isset($geopportal_mainpage_disp_third_page->ID) )
			$geopportal_mainpage_disp_third_date = get_the_date("F j, Y", $geopportal_mainpage_disp_third_page->ID);

		// Checks if there's data in the excerpt and, if so, assigns it to be displayed.
		// If not, grabs post content and clips it at 200 characters.
		$geopportal_mainpage_disp_first_excerpt = "";
		$geopportal_mainpage_disp_second_excerpt = "";
		$geopportal_mainpage_disp_third_excerpt = "";

		if (!empty($geopportal_mainpage_disp_first_page->post_excerpt))
		  $geopportal_mainpage_disp_first_excerpt = esc_attr(wp_strip_all_tags($geopportal_mainpage_disp_first_page->post_excerpt));
		else if (!empty($geopportal_mainpage_disp_first_page->post_content)){
		  $geopportal_mainpage_disp_first_excerpt = esc_attr(wp_strip_all_tags($geopportal_mainpage_disp_first_page->post_content));
		  if (strlen($geopportal_mainpage_disp_first_excerpt) > 200)
		    $geopportal_mainpage_disp_first_excerpt = esc_attr(wp_strip_all_tags(substr($geopportal_mainpage_disp_first_excerpt, 0, 200) . '...'));
		}

		if (!empty($geopportal_mainpage_disp_second_page->post_excerpt))
		  $geopportal_mainpage_disp_second_excerpt = esc_attr(wp_strip_all_tags($geopportal_mainpage_disp_second_page->post_excerpt));
			else if (!empty($geopportal_mainpage_disp_second_page->post_content)){
		  $geopportal_mainpage_disp_second_excerpt = esc_attr(wp_strip_all_tags($geopportal_mainpage_disp_second_page->post_content));
		  if (strlen($geopportal_mainpage_disp_second_excerpt) > 200)
		    $geopportal_mainpage_disp_second_excerpt = esc_attr(wp_strip_all_tags(substr($geopportal_mainpage_disp_second_excerpt, 0, 200) . '...'));
		}

		if (!empty($geopportal_mainpage_disp_third_page->post_excerpt))
		  $geopportal_mainpage_disp_third_excerpt = esc_attr(wp_strip_all_tags($geopportal_mainpage_disp_third_page->post_excerpt));
			else if (!empty($geopportal_mainpage_disp_third_page->post_content)){
		  $geopportal_mainpage_disp_third_excerpt = esc_attr(wp_strip_all_tags($geopportal_mainpage_disp_third_page->post_content));
		  if (strlen($geopportal_mainpage_disp_third_excerpt) > 200)
		    $geopportal_mainpage_disp_third_excerpt = esc_attr(wp_strip_all_tags(substr($geopportal_mainpage_disp_third_excerpt, 0, 200) . '...'));
		}

		// Makes sure count is a number.
		if (!is_numeric($geopportal_mainpage_disp_more_count) || $geopportal_mainpage_disp_more_count <= 0)
			$geopportal_mainpage_disp_more_count = 6;

		?>

		<!--
		FEATURED ITEMS SECTION
		-->
		<div class="o-featured">

				<div class="o-featured__main">

						<div class="o-featured__primary">
								<a class="is-linkless o-featured__heading" href="<?php echo get_the_permalink($geopportal_mainpage_disp_first_page); ?>"><?php echo get_the_title($geopportal_mainpage_disp_first_page); ?></a>
								<!-- <img alt="Article Heading" class="o-featured__thumb" src="/img/img.png"> -->
								<div class="o-featured__thumb" id="deck-container" style="width: 50%; height: 300px; background-color: #abc8dc;">
									<img src="<?php echo $geopportal_mainpage_disp_first_thumb; ?>">
								</div>
								<div class="o-featured__sub-heading"><?php _e(sanitize_text_field($geopportal_mainpage_disp_first_subtitle), 'geoplatform-ccb') ?></div>
								<div><?php echo $geopportal_mainpage_disp_first_date; ?></div>
								<div class="o-featured__desc">
									<?php echo $geopportal_mainpage_disp_first_excerpt; ?>
								</div>
						</div>
						<div class="o-featured__secondary">
								<img alt="Article Heading" class="o-featured__thumb"  src="<?php echo $geopportal_mainpage_disp_second_thumb; ?>">
								<a class="is-linkless o-featured__heading" href="<?php echo get_the_permalink($geopportal_mainpage_disp_second_page); ?>"><?php echo get_the_title($geopportal_mainpage_disp_second_page); ?></a>
								<div><?php echo $geopportal_mainpage_disp_second_date; ?></div>
								<div class="o-featured__desc">
								  <?php echo $geopportal_mainpage_disp_second_excerpt; ?>
								</div>
						</div>
						<div class="o-featured__secondary">
								<img alt="Article Heading" class="o-featured__thumb"  src="<?php echo $geopportal_mainpage_disp_third_thumb; ?>">
								<a class="is-linkless o-featured__heading" href="<?php echo get_the_permalink($geopportal_mainpage_disp_third_page); ?>"><?php echo get_the_title($geopportal_mainpage_disp_third_page); ?></a>
								<div><?php echo $geopportal_mainpage_disp_third_date; ?></div>
								<div class="o-featured__desc">
									<?php echo $geopportal_mainpage_disp_third_excerpt; ?>
								</div>
						</div>

				</div>

				<div class="o-featured__side">
					<h5>More Featured Content</h5>

					<?php
					$geopportal_posts_final = array();

					// Custom sorting method. Begins by getting all of the posts.
					$geopportal_posts = get_posts( array(
							'post_type'   => 'post',
							'numberposts' => -1
					) );

					// Assigns posts with valid priority values to the trimmed array.
					$geopportal_posts_trimmed = array();
					foreach($geopportal_posts as $geopportal_post){
						if ($geopportal_post->geop_ccb_post_priority > 0)
							array_push($geopportal_posts_trimmed, $geopportal_post);
					}

					// Bubble sorts the resulting posts.
					$geopportal_posts_size = count($geopportal_posts_trimmed)-1;
					for ($i = 0; $i < $geopportal_posts_size; $i++) {
						for ($j = 0; $j < $geopportal_posts_size - $i; $j++) {
							$k = $j + 1;
							$geopportal_test_left = $geopportal_posts_trimmed[$j]->geop_ccb_post_priority;
							$geopportal_test_right = $geopportal_posts_trimmed[$k]->geop_ccb_post_priority;
							if ($geopportal_test_left > $geopportal_test_right) {
								// Swap elements at indices: $j, $k
								list($geopportal_posts_trimmed[$j], $geopportal_posts_trimmed[$k]) = array($geopportal_posts_trimmed[$k], $geopportal_posts_trimmed[$j]);
							}
						}
					}

					// Removes all posts after the count set by more_count.
					$geopportal_posts_final = array_slice($geopportal_posts_trimmed, 0, $geopportal_mainpage_disp_more_count);

					// Outputs posts.
					foreach ($geopportal_posts_final as $geopccb_post){
						?>
						<div class="o-featured__secondary">
		            <a class="is-linkless" href="<?php echo get_the_permalink($geopccb_post); ?>"><?php echo get_the_title($geopccb_post); ?></a><br>
		            <span><?php echo get_the_date("F j, Y", $geopccb_post->ID); ?></span>
		        </div>
						<?php
					}

					?>
					<a class="btn btn-light" href="<?php echo esc_url($geopportal_mainpage_disp_browse_link); ?>">Browse All</a>
				</div>
		</div>

    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpage_cb_bool = false;
		$geopportal_mainpage_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_mainpage_cb_bool = true;
			$geopportal_mainpage_cb_message = "Click here to edit this content block";
		}

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_mainpage_title = ! empty( $instance['geopportal_mainpage_title'] ) ? $instance['geopportal_mainpage_title'] : 'Features &amp; Announcements';
		$geopportal_mainpage_first_link = ! empty( $instance['geopportal_mainpage_first_link'] ) ? $instance['geopportal_mainpage_first_link'] : '';
		$geopportal_mainpage_first_subtitle = ! empty( $instance['geopportal_mainpage_first_subtitle'] ) ? $instance['geopportal_mainpage_first_subtitle'] : '';
		$geopportal_mainpage_second_link = ! empty( $instance['geopportal_mainpage_second_link'] ) ? $instance['geopportal_mainpage_second_link'] : '';
		$geopportal_mainpage_third_link = ! empty( $instance['geopportal_mainpage_third_link'] ) ? $instance['geopportal_mainpage_third_link'] : '';
		$geopportal_mainpage_more_count = ! empty( $instance['geopportal_mainpage_more_count'] ) ? $instance['geopportal_mainpage_more_count'] : '0';
		$geopportal_mainpage_browse_link = ! empty( $instance['geopportal_mainpage_browse_link'] ) ? $instance['geopportal_mainpage_browse_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('The boxes below accept the slugs of the linked post. Please ensure that any input slugs are valid.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_first_link' ); ?>">Primary Post Slug:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_first_link ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_first_subtitle' ); ?>">Primary Post Sub-Heading:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_first_subtitle' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_first_subtitle' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_first_subtitle ); ?>" />
    </p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_second_link' ); ?>">First Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_second_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_third_link' ); ?>">Second Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_third_link ); ?>" />
		</p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_more_count' ); ?>">Featured Content Count:</label><br>
			<input type="number"  id="<?php echo $this->get_field_id( 'geopportal_mainpage_more_count' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_more_count' ); ?>" value="<?php echo esc_attr($geopportal_mainpage_more_count); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_browse_link' ); ?>">Browse All URL:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_mainpage_browse_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_browse_link' ); ?>" value="<?php echo esc_attr($geopportal_mainpage_browse_link); ?>" />
		</p>
		<p>
			<?php _e('Tertiary content is controlled by the post priority settings. Navigate to the admin post panel to set these.', 'geoplatform-ccb'); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpage_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_mainpage_cb_bool = true;

    // $instance[ 'geopportal_mainpage_title' ] = strip_tags( $new_instance[ 'geopportal_mainpage_title' ] );
		$instance[ 'geopportal_mainpage_first_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_first_link' ] );
		$instance[ 'geopportal_mainpage_first_subtitle' ] = strip_tags( $new_instance[ 'geopportal_mainpage_first_subtitle' ] );
		$instance[ 'geopportal_mainpage_second_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_second_link' ] );
		$instance[ 'geopportal_mainpage_third_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_third_link' ] );
		$instance[ 'geopportal_mainpage_more_count' ] = strip_tags( $new_instance[ 'geopportal_mainpage_more_count' ] );
		$instance[ 'geopportal_mainpage_browse_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_browse_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_mainpage_widget() {
		register_widget( 'Geopportal_MainPage_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_mainpage_widget' );
