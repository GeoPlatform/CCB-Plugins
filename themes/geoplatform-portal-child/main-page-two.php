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
		            <a href="#" class="m-featured__heading"><?php echo get_the_title($geopportal_mainpagetwo_disp_first_page); ?></a>
		            <img class="m-featured__thumb" src="<?php echo $geopportal_mainpagetwo_disp_first_thumb; ?>">
		            <div class="m-featured__sub-heading"><?php _e(sanitize_text_field($geopportal_mainpagetwo_disp_first_subtitle), 'geoplatform-ccb') ?></div>
		            <div><?php echo $geopportal_mainpagetwo_disp_first_date; ?></div>
		            <div class="m-featured__desc">
									<?php echo do_shortcode($geopportal_mainpagetwo_disp_first_content); ?>
		            </div>
		        </div>

		        <div class="m-featured__secondary">
		            <img class="m-featured__thumb"  src="<?php echo $geopportal_mainpagetwo_disp_second_thumb; ?>">
		            <a href="#" class="m-featured__heading"><?php echo get_the_title($geopportal_mainpagetwo_disp_second_page); ?></a>
		            <div><?php echo $geopportal_mainpagetwo_disp_second_date; ?></div>
		            <div class="m-featured__desc">
		              <?php echo do_shortcode($geopportal_mainpagetwo_disp_second_content); ?>
		            </div>
		        </div>
		        <div class="m-featured__secondary">
		            <img class="m-featured__thumb"  src="<?php echo $geopportal_mainpagetwo_disp_third_thumb; ?>">
		            <a href="#" class="m-featured__heading"><?php echo get_the_title($geopportal_mainpagetwo_disp_third_page); ?></a>
		            <div><?php echo $geopportal_mainpagetwo_disp_third_date; ?></div>
		            <div class="m-featured__desc">
	                <?php echo do_shortcode($geopportal_mainpagetwo_disp_third_content); ?>
		            </div>
		        </div>

		    </div>

		    <div class="m-featured__side">

		        <div class="m-featured__secondary">
		            <h5>More Featured Content</h5>
		            <a href="#">Featured Article #4</a><br>
		            <span>Jan 1, 2018</span>
		        </div>
		        <div class="m-featured__secondary">
		            <a href="#">Featured Article #5</a><br>
		            <span>Jan 1, 2018</span>
		        </div>
		        <div class="m-featured__secondary">
		            <a href="#">Featured Article #6</a><br>
		            <span>Jan 1, 2018</span>
		        </div>
		        <div class="m-featured__secondary">
		            <a href="#">Featured Article #7</a><br>
		            <span>Jan 1, 2018</span>
		        </div>
		        <div class="m-featured__secondary">
		            <a href="#">Featured Article #8</a><br>
		            <span>Jan 1, 2018</span>
		        </div>

		        <a class="btn btn-light" href="#">Browse All</a>
		    </div>

		</div>




    <!-- <div class="whatsNew section--linked">
      <div class="container-fluid">
        <div class="line"></div>
        <div class="line-arrow"></div>

        <div class="col-lg-10 col-lg-offset-1">
          <h4 class="heading text-centered">
            <div class="title darkened">
              <?php _e(sanitize_text_field($geopportal_mainpagetwo_disp_title), 'geoplatform-ccb') ?>
            </div>
          </h4>
          <div class="row"> -->
						<?php
						// $geopportal_mainpagetwo_disp_post_array = array($geopportal_mainpagetwo_disp_first_link, $geopportal_mainpagetwo_disp_second_link, $geopportal_mainpagetwo_disp_third_link);
						// $geopportal_mainpagetwo_disp_content_array = array($geopportal_mainpagetwo_disp_first_content, $geopportal_mainpagetwo_disp_second_content, $geopportal_mainpagetwo_disp_third_content);
						// for ($geopportal_counter = 0; $geopportal_counter <= 2; $geopportal_counter++){
						// 	include( locate_template('post-card.php', false, false));
						// }
            ?>
            <!-- <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <br>
      <div class="footing">
          <div class="line-cap"></div>
          <div class="line"></div>
      </div>
    </div> -->
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
    <!-- <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpagetwo_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpagetwo_title' ); ?>" value="<?php echo esc_attr( $geopportal_mainpagetwo_title ); ?>" />
    </p>
		<hr> -->
		<p>
			<?php _e('The boxes below accept the slugs of the linked post or page. Please ensure that any input slugs are valid.', 'geoplatform-ccb'); ?>
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
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpagetwo_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_mainpagetwo_cb_bool = true;

    $instance[ 'geopportal_mainpagetwo_title' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_title' ] );
		$instance[ 'geopportal_mainpagetwo_first_link' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_first_link' ] );
		$instance[ 'geopportal_mainpagetwo_first_subtitle' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_first_subtitle' ] );
		$instance[ 'geopportal_mainpagetwo_first_content' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_first_content' ] );
		$instance[ 'geopportal_mainpagetwo_second_link' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_second_link' ] );
		$instance[ 'geopportal_mainpagetwo_second_content' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_second_content' ] );
		$instance[ 'geopportal_mainpagetwo_third_link' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_third_link' ] );
		$instance[ 'geopportal_mainpagetwo_third_content' ] = strip_tags( $new_instance[ 'geopportal_mainpagetwo_third_content' ] );

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
