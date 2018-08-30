<?php
class Geopportal_MainPageTwo_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_mainpagetwo_widget', // Base ID
			esc_html__( 'GeoPlatform Features & Announcements V2', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform features & announcements version 2 widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    // if (array_key_exists('geopportal_mainpagetwo_title', $instance) && isset($instance['geopportal_mainpagetwo_title']) && !empty($instance['geopportal_mainpagetwo_title']))
    //   $geopportal_mainpagetwo_disp_title = apply_filters('widget_title', $instance['geopportal_mainpagetwo_title']);
		// else
    //   $geopportal_mainpagetwo_disp_title = "Features &amp; Announcements";

		if (array_key_exists('geopportal_mainpagetwo_first_link', $instance) && isset($instance['geopportal_mainpagetwo_first_link']) && !empty($instance['geopportal_mainpagetwo_first_link']))
      $geopportal_mainpagetwo_disp_first_link = apply_filters('widget_title', $instance['geopportal_mainpagetwo_first_link']);
		else
      $geopportal_mainpagetwo_disp_first_link = "";
		if (array_key_exists('geopportal_mainpagetwo_first_subtitle', $instance) && isset($instance['geopportal_mainpagetwo_first_subtitle']) && !empty($instance['geopportal_mainpagetwo_first_subtitle']))
      $geopportal_mainpagetwo_disp_first_subtitle = apply_filters('widget_title', $instance['geopportal_mainpagetwo_first_subtitle']);
		else
      $geopportal_mainpagetwo_disp_first_subtitle = "";
		if (array_key_exists('geopportal_mainpagetwo_first_content', $instance) && isset($instance['geopportal_mainpagetwo_first_content']) && !empty($instance['geopportal_mainpagetwo_first_content']))
			$geopportal_mainpagetwo_disp_first_content = apply_filters('widget_title', $instance['geopportal_mainpagetwo_first_content']);
		else
			$geopportal_mainpagetwo_disp_first_content = "";

		if (array_key_exists('geopportal_mainpagetwo_second_link', $instance) && isset($instance['geopportal_mainpagetwo_second_link']) && !empty($instance['geopportal_mainpagetwo_second_link']))
      $geopportal_mainpagetwo_disp_second_link = apply_filters('widget_title', $instance['geopportal_mainpagetwo_second_link']);
		else
    	$geopportal_mainpagetwo_disp_second_link = "";
		if (array_key_exists('geopportal_mainpagetwo_second_content', $instance) && isset($instance['geopportal_mainpagetwo_second_content']) && !empty($instance['geopportal_mainpagetwo_second_content']))
			$geopportal_mainpagetwo_disp_second_content = apply_filters('widget_title', $instance['geopportal_mainpagetwo_second_content']);
		else
			$geopportal_mainpagetwo_disp_second_content = "";

		if (array_key_exists('geopportal_mainpagetwo_third_link', $instance) && isset($instance['geopportal_mainpagetwo_third_link']) && !empty($instance['geopportal_mainpagetwo_third_link']))
      $geopportal_mainpagetwo_disp_third_link = apply_filters('widget_title', $instance['geopportal_mainpagetwo_third_link']);
		else
      $geopportal_mainpagetwo_disp_third_link = "";
		if (array_key_exists('geopportal_mainpagetwo_third_content', $instance) && isset($instance['geopportal_mainpagetwo_third_content']) && !empty($instance['geopportal_mainpagetwo_third_content']))
			$geopportal_mainpagetwo_disp_third_content = apply_filters('widget_title', $instance['geopportal_mainpagetwo_third_content']);
		else
			$geopportal_mainpagetwo_disp_third_content = "";

		if (array_key_exists('geopportal_mainpagetwo_browse_link', $instance) && isset($instance['geopportal_mainpagetwo_browse_link']) && !empty($instance['geopportal_mainpagetwo_browse_link']))
			$geopportal_mainpagetwo_disp_browse_link = apply_filters('widget_title', $instance['geopportal_mainpagetwo_browse_link']);
		else
			$geopportal_mainpagetwo_disp_browse_link = "#";

		// Turns the slugs into pages.
		$geopportal_mainpagetwo_disp_first_page = get_page_by_path($geopportal_mainpagetwo_disp_first_link, OBJECT, 'post');
		$geopportal_mainpagetwo_disp_second_page = get_page_by_path($geopportal_mainpagetwo_disp_second_link, OBJECT, 'post');
		$geopportal_mainpagetwo_disp_third_page = get_page_by_path($geopportal_mainpagetwo_disp_third_link, OBJECT, 'post');

		// Sets up default thumbnails and overwrites if post has one.
		$geopportal_mainpagetwo_disp_first_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpagetwo_disp_second_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpagetwo_disp_third_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';

		if ( has_post_thumbnail($geopportal_mainpagetwo_disp_first_page) )
			$geopportal_mainpagetwo_disp_first_thumb = get_the_post_thumbnail_url($geopportal_mainpagetwo_disp_first_page);
		if ( has_post_thumbnail($geopportal_mainpagetwo_disp_second_page) )
			$geopportal_mainpagetwo_disp_second_thumb = get_the_post_thumbnail_url($geopportal_mainpagetwo_disp_second_page);
		if ( has_post_thumbnail($geopportal_mainpagetwo_disp_third_page) )
			$geopportal_mainpagetwo_disp_third_thumb = get_the_post_thumbnail_url($geopportal_mainpagetwo_disp_third_page);

		// Sets up invalid post notices for dates and overwrites if the associated post is valid with an actual date.
		$geopportal_mainpagetwo_disp_first_date = "Invalid Post";
		$geopportal_mainpagetwo_disp_second_date = "Invalid Post";
		$geopportal_mainpagetwo_disp_third_date = "Invalid Post";

		if ( isset($geopportal_mainpagetwo_disp_first_page->ID) )
			$geopportal_mainpagetwo_disp_first_date = get_the_date("F j, Y", $geopportal_mainpagetwo_disp_first_page->ID);
		if ( isset($geopportal_mainpagetwo_disp_second_page->ID) )
			$geopportal_mainpagetwo_disp_second_date = get_the_date("F j, Y", $geopportal_mainpagetwo_disp_second_page->ID);
		if ( isset($geopportal_mainpagetwo_disp_third_page->ID) )
			$geopportal_mainpagetwo_disp_third_date = get_the_date("F j, Y", $geopportal_mainpagetwo_disp_third_page->ID);




    echo $args['before_widget'];?>

		<div class="m-featured">

		    <div class="m-featured__main">

		        <div class="m-featured__primary">
		            <div style="margin-bottom:0.25em"><a href="<?php echo get_the_permalink($geopportal_mainpagetwo_disp_first_page); ?>" class="m-featured__heading"><?php echo get_the_title($geopportal_mainpagetwo_disp_first_page); ?></a></div>
		            <img class="m-featured__thumb" src="<?php echo $geopportal_mainpagetwo_disp_first_thumb; ?>">
		            <div class="m-featured__sub-heading"><?php _e(sanitize_text_field($geopportal_mainpagetwo_disp_first_subtitle), 'geoplatform-ccb') ?></div>
		            <div><?php echo $geopportal_mainpagetwo_disp_first_date; ?></div>
		            <div class="m-featured__desc">
									<?php echo do_shortcode($geopportal_mainpagetwo_disp_first_content); ?>
		            </div>
		        </div>

		        <div class="m-featured__secondary">
		            <img class="m-featured__thumb"  src="<?php echo $geopportal_mainpagetwo_disp_second_thumb; ?>">
		            <a href="<?php echo get_the_permalink($geopportal_mainpagetwo_disp_second_page); ?>" class="m-featured__heading"><?php echo get_the_title($geopportal_mainpagetwo_disp_second_page); ?></a>
		            <div><?php echo $geopportal_mainpagetwo_disp_second_date; ?></div>
		            <div class="m-featured__desc">
		              <?php echo do_shortcode($geopportal_mainpagetwo_disp_second_content); ?>
		            </div>
		        </div>
		        <div class="m-featured__secondary">
		            <img class="m-featured__thumb"  src="<?php echo $geopportal_mainpagetwo_disp_third_thumb; ?>">
		            <a href="<?php echo get_the_permalink($geopportal_mainpagetwo_disp_third_page); ?>" class="m-featured__heading"><?php echo get_the_title($geopportal_mainpagetwo_disp_third_page); ?></a>
		            <div><?php echo $geopportal_mainpagetwo_disp_third_date; ?></div>
		            <div class="m-featured__desc">
	                <?php echo do_shortcode($geopportal_mainpagetwo_disp_third_content); ?>
		            </div>
		        </div>

		    </div>

		    <div class="m-featured__side">
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
						if ($geopportal_post->geop_portal_post_priority > 0)
							array_push($geopportal_posts_trimmed, $geopportal_post);
					}

					// Bubble sorts the resulting posts.
					$geopportal_posts_size = count($geopportal_posts_trimmed)-1;
					for ($i = 0; $i < $geopportal_posts_size; $i++) {
						for ($j = 0; $j < $geopportal_posts_size - $i; $j++) {
							$k = $j + 1;
							$geopportal_test_left = $geopportal_posts_trimmed[$j]->geop_portal_post_priority;
							$geopportal_test_right = $geopportal_posts_trimmed[$k]->geop_portal_post_priority;
							if ($geopportal_test_left > $geopportal_test_right) {
								// Swap elements at indices: $j, $k
								list($geopportal_posts_trimmed[$j], $geopportal_posts_trimmed[$k]) = array($geopportal_posts_trimmed[$k], $geopportal_posts_trimmed[$j]);
							}
						}
					}

					// Removes all posts after the first 8.
					$geopportal_posts_final = array_slice($geopportal_posts_trimmed, 0, 6);

					// Outputs posts.
					foreach ($geopportal_posts_final as $geopccb_post){
						?>
						<div class="m-featured__secondary">
		            <a href="<?php echo get_the_permalink($geopccb_post); ?>"><?php echo get_the_title($geopccb_post); ?></a><br>
		            <span><?php echo get_the_date("F j, Y", $geopccb_post->ID); ?></span>
		        </div>
						<?php
					}

					?>
		      <a class="btn btn-light" href="<?php echo esc_url($geopportal_mainpagetwo_disp_browse_link); ?>">Browse All</a>
		    </div>
		</div>

    <?php
    echo $args['after_widget'];
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpagetwo_cb_bool = false;
		$geopportal_mainpagetwo_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_mainpagetwo_cb_bool = true;
			$geopportal_mainpagetwo_cb_message = "Click here to edit this content block";
		}

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_mainpagetwo_title = ! empty( $instance['geopportal_mainpagetwo_title'] ) ? $instance['geopportal_mainpagetwo_title'] : 'Features &amp; Announcements';
		$geopportal_mainpagetwo_first_link = ! empty( $instance['geopportal_mainpagetwo_first_link'] ) ? $instance['geopportal_mainpagetwo_first_link'] : '';
		$geopportal_mainpagetwo_first_subtitle = ! empty( $instance['geopportal_mainpagetwo_first_subtitle'] ) ? $instance['geopportal_mainpagetwo_first_subtitle'] : '';
		$geopportal_mainpagetwo_first_content = ! empty( $instance['geopportal_mainpagetwo_first_content'] ) ? $instance['geopportal_mainpagetwo_first_content'] : '';
		$geopportal_mainpagetwo_second_link = ! empty( $instance['geopportal_mainpagetwo_second_link'] ) ? $instance['geopportal_mainpagetwo_second_link'] : '';
		$geopportal_mainpagetwo_second_content = ! empty( $instance['geopportal_mainpagetwo_second_content'] ) ? $instance['geopportal_mainpagetwo_second_content'] : '';
		$geopportal_mainpagetwo_third_link = ! empty( $instance['geopportal_mainpagetwo_third_link'] ) ? $instance['geopportal_mainpagetwo_third_link'] : '';
		$geopportal_mainpagetwo_third_content = ! empty( $instance['geopportal_mainpagetwo_third_content'] ) ? $instance['geopportal_mainpagetwo_third_content'] : '';
		$geopportal_mainpagetwo_browse_link = ! empty( $instance['geopportal_mainpagetwo_browse_link'] ) ? $instance['geopportal_mainpagetwo_browse_link'] : '';

		// Sets up the first content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_mainpagetwo_first_content', $instance) && isset($instance['geopportal_mainpagetwo_first_content']) && !empty($instance['geopportal_mainpagetwo_first_content']) && $geopportal_mainpagetwo_cb_bool){
    	$geopportal_mainpagetwo_first_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpagetwo_first_content' ]);
    	if (is_numeric($geopportal_mainpagetwo_first_temp_url))
      	$geopportal_mainpagetwo_first_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpagetwo_first_temp_url . "&action=edit";
    	else
      	$geopportal_mainpagetwo_first_url = home_url();
		}
		else
			$geopportal_mainpagetwo_first_url = home_url();

		// Sets up the first content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_mainpagetwo_second_content', $instance) && isset($instance['geopportal_mainpagetwo_second_content']) && !empty($instance['geopportal_mainpagetwo_second_content']) && $geopportal_mainpagetwo_cb_bool){
    	$geopportal_mainpagetwo_second_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpagetwo_second_content' ]);
    	if (is_numeric($geopportal_mainpagetwo_second_temp_url))
      	$geopportal_mainpagetwo_second_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpagetwo_second_temp_url . "&action=edit";
    	else
      	$geopportal_mainpagetwo_second_url = home_url();
		}
		else
			$geopportal_mainpagetwo_second_url = home_url();

			// Sets up the first content box link, or just a home link if invalid.
			if (array_key_exists('geopportal_mainpagetwo_third_content', $instance) && isset($instance['geopportal_mainpagetwo_third_content']) && !empty($instance['geopportal_mainpagetwo_third_content']) && $geopportal_mainpagetwo_cb_bool){
	    	$geopportal_mainpagetwo_third_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpagetwo_third_content' ]);
	    	if (is_numeric($geopportal_mainpagetwo_third_temp_url))
	      	$geopportal_mainpagetwo_third_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpagetwo_third_temp_url . "&action=edit";
	    	else
	      	$geopportal_mainpagetwo_third_url = home_url();
			}
			else
				$geopportal_mainpagetwo_third_url = home_url();
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('The boxes below accept the slugs of the linked post. Please ensure that any input slugs are valid.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_first_link' ); ?>">Primary Post Slug:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpagetwo_first_link ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_first_subtitle' ); ?>">Primary Post Sub-Heading:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_first_subtitle' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_first_subtitle' ); ?>" value="<?php echo esc_attr( $geopportal_mainpagetwo_first_subtitle ); ?>" />
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_first_content' ); ?>">Primary Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_first_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_first_content' ); ?>" value="<?php echo esc_attr($geopportal_mainpagetwo_first_content); ?>" />
			<a href="<?php echo esc_url($geopportal_mainpagetwo_first_url); ?>" target="_blank"><?php _e($geopportal_mainpagetwo_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_second_link' ); ?>">First Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpagetwo_second_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_second_content' ); ?>">First Sub-Feature Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_second_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_second_content' ); ?>" value="<?php echo esc_attr($geopportal_mainpagetwo_second_content); ?>" />
			<a href="<?php echo esc_url($geopportal_mainpagetwo_second_url); ?>" target="_blank"><?php _e($geopportal_mainpagetwo_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_third_link' ); ?>">Second Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpagetwo_third_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_third_content' ); ?>">Second Sub-Feature Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_third_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_third_content' ); ?>" value="<?php echo esc_attr($geopportal_mainpagetwo_third_content); ?>" />
			<a href="<?php echo esc_url($geopportal_mainpagetwo_third_url); ?>" target="_blank"><?php _e($geopportal_mainpagetwo_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<hr>
		<p>
			<p>
				<label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_browse_link' ); ?>">Browse All URL:</label><br>
				<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_browse_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_browse_link' ); ?>" value="<?php echo esc_attr($geopportal_mainpagetwo_browse_link); ?>" />
			</p>
		</p>
		<hr>
		<p>
			<?php _e('Tertiary content is controlled by the post priority settings. Navigate to the admin post panel to set these.', 'geoplatform-ccb'); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpagetwo_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_mainpagetwo_cb_bool = true;

    // $instance[ 'geopportal_mainpagetwo_title' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_title' ] );
		$instance[ 'geopportal_mainpagetwo_first_link' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_first_link' ] );
		$instance[ 'geopportal_mainpagetwo_first_subtitle' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_first_subtitle' ] );
		$instance[ 'geopportal_mainpagetwo_first_content' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_first_content' ] );
		$instance[ 'geopportal_mainpagetwo_second_link' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_second_link' ] );
		$instance[ 'geopportal_mainpagetwo_second_content' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_second_content' ] );
		$instance[ 'geopportal_mainpagetwo_third_link' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_third_link' ] );
		$instance[ 'geopportal_mainpagetwo_third_content' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_third_content' ] );
		$instance[ 'geopportal_mainpagetwo_browse_link' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_browse_link' ] );

		// Validity check for the content box URL.
		if (array_key_exists('geopportal_mainpagetwo_first_content', $instance) && isset($instance['geopportal_mainpagetwo_first_content']) && !empty($instance['geopportal_mainpagetwo_first_content']) && $geopportal_mainpagetwo_cb_bool){
    	$geopportal_mainpagetwo_first_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpagetwo_first_content' ]);
    	if (is_numeric($geopportal_mainpagetwo_first_temp_url))
      	$geopportal_mainpagetwo_first_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpagetwo_first_temp_url . "&action=edit";
    	else
      	$geopportal_mainpagetwo_first_url = home_url();
		}
		else
			$geopportal_mainpagetwo_first_url = home_url();

		if (array_key_exists('geopportal_mainpagetwo_second_content', $instance) && isset($instance['geopportal_mainpagetwo_second_content']) && !empty($instance['geopportal_mainpagetwo_second_content']) && $geopportal_mainpagetwo_cb_bool){
    	$geopportal_mainpagetwo_second_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpagetwo_second_content' ]);
    	if (is_numeric($geopportal_mainpagetwo_second_temp_url))
      	$geopportal_mainpagetwo_second_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpagetwo_second_temp_url . "&action=edit";
    	else
      	$geopportal_mainpagetwo_second_url = home_url();
		}
		else
			$geopportal_mainpagetwo_second_url = home_url();

		if (array_key_exists('geopportal_mainpagetwo_third_content', $instance) && isset($instance['geopportal_mainpagetwo_third_content']) && !empty($instance['geopportal_mainpagetwo_third_content']) && $geopportal_mainpagetwo_cb_bool){
    	$geopportal_mainpagetwo_third_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_mainpagetwo_third_content' ]);
    	if (is_numeric($geopportal_mainpagetwo_third_temp_url))
      	$geopportal_mainpagetwo_third_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_mainpagetwo_third_temp_url . "&action=edit";
    	else
      	$geopportal_mainpagetwo_third_url = home_url();
		}
		else
			$geopportal_mainpagetwo_third_url = home_url();

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_mainpagetwo_widget() {
		register_widget( 'Geopportal_mainpagetwo_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_mainpagetwo_widget' );
