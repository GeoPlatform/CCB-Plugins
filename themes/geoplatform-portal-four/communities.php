<?php
class Geopportal_Communities_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_communities_widget', // Base ID
			esc_html__( 'GeoPlatform Communities', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Communities widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    // if (array_key_exists('geopportal_communities_title', $instance) && isset($instance['geopportal_communities_title']) && !empty($instance['geopportal_communities_title']))
    //   $geopportal_communities_disp_title = apply_filters('widget_title', $instance['geopportal_communities_title']);
		// else
    //   $geopportal_communities_disp_title = "Features &amp; Announcements";

		if (array_key_exists('geopportal_communities_main_title', $instance) && isset($instance['geopportal_communities_main_title']) && !empty($instance['geopportal_communities_main_title']))
      $geopportal_communities_main_title = apply_filters('widget_title', $instance['geopportal_communities_main_title']);
		else
      $geopportal_communities_main_title = "COMMUNITIES";
		if (array_key_exists('geopportal_communities_main_content', $instance) && isset($instance['geopportal_communities_main_content']) && !empty($instance['geopportal_communities_main_content']))
      $geopportal_communities_main_content = apply_filters('widget_title', $instance['geopportal_communities_main_content']);
		else
      $geopportal_communities_main_content = "";
		if (array_key_exists('geopportal_communities_sub_title_one', $instance) && isset($instance['geopportal_communities_sub_title_one']) && !empty($instance['geopportal_communities_sub_title_one']))
      $geopportal_communities_sub_title_one = apply_filters('widget_title', $instance['geopportal_communities_sub_title_one']);
		else
      $geopportal_communities_sub_title_one = "Trending Communities";
		if (array_key_exists('geopportal_communities_sub_title_two', $instance) && isset($instance['geopportal_communities_sub_title_two']) && !empty($instance['geopportal_communities_sub_title_two']))
      $geopportal_communities_sub_title_two = apply_filters('widget_title', $instance['geopportal_communities_sub_title_two']);
		else
      $geopportal_communities_sub_title_two = "New Communities";

		if (array_key_exists('geopportal_communities_trend_first_link', $instance) && isset($instance['geopportal_communities_trend_first_link']) && !empty($instance['geopportal_communities_trend_first_link']))
      $geopportal_communities_trend_first_link = apply_filters('widget_link', $instance['geopportal_communities_trend_first_link']);
		else
      $geopportal_communities_trend_first_link = home_url();
		if (array_key_exists('geopportal_communities_trend_second_link', $instance) && isset($instance['geopportal_communities_trend_second_link']) && !empty($instance['geopportal_communities_trend_second_link']))
      $geopportal_communities_trend_second_link = apply_filters('widget_link', $instance['geopportal_communities_trend_second_link']);
		else
      $geopportal_communities_trend_second_link = home_url();
		if (array_key_exists('geopportal_communities_trend_third_link', $instance) && isset($instance['geopportal_communities_trend_third_link']) && !empty($instance['geopportal_communities_trend_third_link']))
	    $geopportal_communities_trend_third_link = apply_filters('widget_link', $instance['geopportal_communities_trend_third_link']);
		else
	    $geopportal_communities_trend_third_link = home_url();
		if (array_key_exists('geopportal_communities_trend_fourth_link', $instance) && isset($instance['geopportal_communities_trend_fourth_link']) && !empty($instance['geopportal_communities_trend_fourth_link']))
	    $geopportal_communities_trend_fourth_link = apply_filters('widget_link', $instance['geopportal_communities_trend_fourth_link']);
		else
	    $geopportal_communities_trend_fourth_link = home_url();

		if (array_key_exists('geopportal_communities_new_first_link', $instance) && isset($instance['geopportal_communities_new_first_link']) && !empty($instance['geopportal_communities_new_first_link']))
      $geopportal_communities_new_first_link = apply_filters('widget_link', $instance['geopportal_communities_new_first_link']);
		else
      $geopportal_communities_new_first_link = home_url();
		if (array_key_exists('geopportal_communities_new_second_link', $instance) && isset($instance['geopportal_communities_new_second_link']) && !empty($instance['geopportal_communities_new_second_link']))
      $geopportal_communities_new_second_link = apply_filters('widget_link', $instance['geopportal_communities_new_second_link']);
		else
      $geopportal_communities_new_second_link = home_url();
		if (array_key_exists('geopportal_communities_new_third_link', $instance) && isset($instance['geopportal_communities_new_third_link']) && !empty($instance['geopportal_communities_new_third_link']))
	    $geopportal_communities_new_third_link = apply_filters('widget_link', $instance['geopportal_communities_new_third_link']);
		else
	    $geopportal_communities_new_third_link = home_url();
		if (array_key_exists('geopportal_communities_new_fourth_link', $instance) && isset($instance['geopportal_communities_new_fourth_link']) && !empty($instance['geopportal_communities_new_fourth_link']))
	    $geopportal_communities_new_fourth_link = apply_filters('widget_link', $instance['geopportal_communities_new_fourth_link']);
		else
	    $geopportal_communities_new_fourth_link = home_url();

		// Turns the slugs into pages.
		$geopportal_communities_get_page_params = array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post');
		$geopportal_communities_trend_first_page = get_page_by_path($geopportal_communities_trend_first_link, OBJECT, $geopportal_communities_get_page_params);
		$geopportal_communities_trend_second_page = get_page_by_path($geopportal_communities_trend_second_link, OBJECT, $geopportal_communities_get_page_params);
		$geopportal_communities_trend_third_page = get_page_by_path($geopportal_communities_trend_third_link, OBJECT, $geopportal_communities_get_page_params);
		$geopportal_communities_trend_fourth_page = get_page_by_path($geopportal_communities_trend_fourth_link, OBJECT, $geopportal_communities_get_page_params);
		$geopportal_communities_new_first_page = get_page_by_path($geopportal_communities_new_first_link, OBJECT, $geopportal_communities_get_page_params);
		$geopportal_communities_new_second_page = get_page_by_path($geopportal_communities_new_second_link, OBJECT, $geopportal_communities_get_page_params);
		$geopportal_communities_new_third_page = get_page_by_path($geopportal_communities_new_third_link, OBJECT, $geopportal_communities_get_page_params);
		$geopportal_communities_new_fourth_page = get_page_by_path($geopportal_communities_new_fourth_link, OBJECT, $geopportal_communities_get_page_params);

		// Groups pages into an array and creates 3 support arrays for relevant info.
		$geopportal_communities_page_array = array($geopportal_communities_trend_first_page, $geopportal_communities_trend_second_page, $geopportal_communities_trend_third_page, $geopportal_communities_trend_fourth_page,
			$geopportal_communities_new_first_page, $geopportal_communities_new_second_page, $geopportal_communities_new_third_page, $geopportal_communities_new_fourth_page);
		$geopportal_communities_thumb_array = array();
		$geopportal_communities_link_array = array();
		$geopportal_communities_title_array = array();

		// Determination loop that populates the support arrays with data from the page array.
		foreach($geopportal_communities_page_array as $geopportal_communities_page){
			if (has_post_thumbnail($geopportal_communities_page))
				array_push($geopportal_communities_thumb_array, get_the_post_thumbnail_url($geopportal_communities_page));
			else
				array_push($geopportal_communities_thumb_array, get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png');

		  if (get_post_type($geopportal_communities_page) == 'geopccb_catlink')
				array_push($geopportal_communities_link_array, esc_url($geopportal_communities_page->geop_ccb_cat_link_url));
			else
				array_push($geopportal_communities_link_array, get_the_permalink($geopportal_communities_page));

			array_push($geopportal_communities_title_array, get_the_title($geopportal_communities_page));
		}
		?>

		<!--
		COMMUNITIES SECTION
		-->
		<div id="geopportal_anchor_communities" title="Explore Communities">
			<article class="p-landing-page__communities">
			    <div class="m-article__heading m-article__heading--front-page"><?php _e(sanitize_text_field($geopportal_communities_main_title), 'geoplatform-ccb') ?></div>
			    <div class="m-article__desc">
						<?php echo do_shortcode($geopportal_communities_main_content) ?>
			    </div>
			    <br>
			    <div class="m-communities__content">
			        <div>
			            <div class="a-heading"><?php _e(sanitize_text_field($geopportal_communities_sub_title_one), 'geoplatform-ccb') ?></div>
			            <div class="d-grid">
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[0] ?>">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[0] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[0] ?></div>
			                </a>
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[1] ?>" target="">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[1] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[1] ?></div>
			                </a>
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[2] ?>l" target="">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[2] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[2] ?></div>
			                </a>
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[3] ?>" target="">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[3] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[3] ?></div>
			                </a>
			            </div>
			        </div>
			        <div>
			            <div class="a-heading"><?php _e(sanitize_text_field($geopportal_communities_sub_title_two), 'geoplatform-ccb') ?></div>
			            <div class="d-grid">
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[4] ?>" target="">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[4] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[4] ?></div>
			                </a>
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[5] ?>" target="">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[5] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[5] ?></div>
			                </a>
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[6] ?>" target="">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[6] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[6] ?></div>
			                </a>
			                <a class="m-card--simple" href="<?php echo $geopportal_communities_link_array[7] ?>" target="">
			                    <img alt="Community Name" class="m-card__thumb" src="<?php echo $geopportal_communities_thumb_array[7] ?>">
			                    <div class="m-card__label"><?php echo $geopportal_communities_title_array[7] ?></div>
			                </a>
			            </div>
			        </div>
			    </div>
			</article>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_communities_cb_bool = false;
		$geopportal_communities_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_communities_cb_bool = true;
			$geopportal_communities_cb_message = "Click here to edit this content block";
		}

		// Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_communities_title = ! empty( $instance['geopportal_communities_title'] ) ? $instance['geopportal_communities_title'] : 'Features &amp; Announcements';
    $geopportal_communities_main_title = ! empty( $instance['geopportal_communities_main_title'] ) ? $instance['geopportal_communities_main_title'] : 'COMMUNITIES';
    $geopportal_communities_main_content = ! empty( $instance['geopportal_communities_main_content'] ) ? $instance['geopportal_communities_main_content'] : '';
    $geopportal_communities_sub_title_one = ! empty( $instance['geopportal_communities_sub_title_one'] ) ? $instance['geopportal_communities_sub_title_one'] : 'Trending Communities';
    $geopportal_communities_sub_title_two = ! empty( $instance['geopportal_communities_sub_title_two'] ) ? $instance['geopportal_communities_sub_title_two'] : 'New Communities';

    $geopportal_communities_trend_first_link = ! empty( $instance['geopportal_communities_trend_first_link'] ) ? $instance['geopportal_communities_trend_first_link'] : '';
    $geopportal_communities_trend_second_link = ! empty( $instance['geopportal_communities_trend_second_link'] ) ? $instance['geopportal_communities_trend_second_link'] : '';
    $geopportal_communities_trend_third_link = ! empty( $instance['geopportal_communities_trend_third_link'] ) ? $instance['geopportal_communities_trend_third_link'] : '';
    $geopportal_communities_trend_fourth_link = ! empty( $instance['geopportal_communities_trend_fourth_link'] ) ? $instance['geopportal_communities_trend_fourth_link'] : '';

    $geopportal_communities_new_first_link = ! empty( $instance['geopportal_communities_new_first_link'] ) ? $instance['geopportal_communities_new_first_link'] : '';
    $geopportal_communities_new_second_link = ! empty( $instance['geopportal_communities_new_second_link'] ) ? $instance['geopportal_communities_new_second_link'] : '';
    $geopportal_communities_new_third_link = ! empty( $instance['geopportal_communities_new_third_link'] ) ? $instance['geopportal_communities_new_third_link'] : '';
    $geopportal_communities_new_fourth_link = ! empty( $instance['geopportal_communities_new_fourth_link'] ) ? $instance['geopportal_communities_new_fourth_link'] : '';

		// Sets up the content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_communities_main_content', $instance) && isset($instance['geopportal_communities_main_content']) && !empty($instance['geopportal_communities_main_content']) && $geopportal_communities_cb_bool){
    	$geopportal_communities_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_communities_main_content' ]);
    	if (is_numeric($geopportal_communities_temp_url))
      	$geopportal_communities_cb_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_communities_temp_url . "&action=edit";
    	else
      	$geopportal_communities_cb_url = home_url();
		}
		else
			$geopportal_communities_cb_url = home_url();

		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('The boxes below accept text, slugs, and a Content Block. The Content Blocks plugin is required for the descriptive area. Please ensure that any input slugs are valid. If the slug points to a Category Link post type, the external URL will be used instead.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_communities_main_title' ); ?>">Main Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_main_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_main_title' ); ?>" value="<?php echo esc_attr( $geopportal_communities_main_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_communities_main_content' ); ?>">Discriptive Content Block Shortcode:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_main_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_main_content' ); ?>" value="<?php echo esc_attr( $geopportal_communities_main_content ); ?>" />
			<a href="<?php echo esc_url($geopportal_communities_cb_url); ?>" target="_blank"><?php _e($geopportal_communities_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_sub_title_one' ); ?>">Left Section Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_sub_title_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_sub_title_one' ); ?>" value="<?php echo esc_attr( $geopportal_communities_sub_title_one ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_trend_first_link' ); ?>">Left Section 1st Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_trend_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_trend_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_trend_first_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_trend_second_link' ); ?>">Left Section 2nd Post Slug:</label><br>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_trend_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_trend_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_trend_second_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_trend_third_link' ); ?>">Left Section 3rd Post Slug:</label><br>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_trend_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_trend_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_trend_third_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_trend_fourth_link' ); ?>">Left Section 4th Post Slug:</label><br>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_trend_fourth_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_trend_fourth_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_trend_fourth_link ); ?>" />
		</p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_sub_title_two' ); ?>">Right Section Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_sub_title_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_sub_title_two' ); ?>" value="<?php echo esc_attr( $geopportal_communities_sub_title_two ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_new_first_link' ); ?>">Right Section 1st Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_new_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_new_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_new_first_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_new_second_link' ); ?>">Right Section 2nd Post Slug:</label><br>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_new_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_new_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_new_second_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_new_third_link' ); ?>">Right Section 3rd Post Slug:</label><br>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_new_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_new_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_new_third_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_communities_new_fourth_link' ); ?>">Right Section 4th Post Slug:</label><br>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_communities_new_fourth_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_communities_new_fourth_link' ); ?>" value="<?php echo esc_attr( $geopportal_communities_new_fourth_link ); ?>" />
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_communities_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_communities_cb_bool = true;

		$instance[ 'geopportal_communities_main_title' ] = strip_tags( $new_instance[ 'geopportal_communities_main_title' ] );
		$instance[ 'geopportal_communities_main_content' ] = strip_tags( $new_instance[ 'geopportal_communities_main_content' ] );
		$instance[ 'geopportal_communities_sub_title_one' ] = strip_tags( $new_instance[ 'geopportal_communities_sub_title_one' ] );
		$instance[ 'geopportal_communities_sub_title_two' ] = strip_tags( $new_instance[ 'geopportal_communities_sub_title_two' ] );
		$instance[ 'geopportal_communities_trend_first_link' ] = strip_tags( $new_instance[ 'geopportal_communities_trend_first_link' ] );
		$instance[ 'geopportal_communities_trend_second_link' ] = strip_tags( $new_instance[ 'geopportal_communities_trend_second_link' ] );
		$instance[ 'geopportal_communities_trend_third_link' ] = strip_tags( $new_instance[ 'geopportal_communities_trend_third_link' ] );
		$instance[ 'geopportal_communities_trend_fourth_link' ] = strip_tags( $new_instance[ 'geopportal_communities_trend_fourth_link' ] );
		$instance[ 'geopportal_communities_new_first_link' ] = strip_tags( $new_instance[ 'geopportal_communities_new_first_link' ] );
		$instance[ 'geopportal_communities_new_second_link' ] = strip_tags( $new_instance[ 'geopportal_communities_new_second_link' ] );
		$instance[ 'geopportal_communities_new_third_link' ] = strip_tags( $new_instance[ 'geopportal_communities_new_third_link' ] );
		$instance[ 'geopportal_communities_new_fourth_link' ] = strip_tags( $new_instance[ 'geopportal_communities_new_fourth_link' ] );

		// Sets up the content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_communities_main_content', $instance) && isset($instance['geopportal_communities_main_content']) && !empty($instance['geopportal_communities_main_content']) && $geopportal_communities_cb_bool){
    	$geopportal_communities_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_communities_main_content' ]);
    	if (is_numeric($geopportal_communities_temp_url))
      	$geopportal_communities_cb_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_communities_temp_url . "&action=edit";
    	else
      	$geopportal_communities_cb_url = home_url();
		}
		else
			$geopportal_communities_cb_url = home_url();

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_communities_widget() {
		register_widget( 'Geopportal_Communities_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_communities_widget' );
