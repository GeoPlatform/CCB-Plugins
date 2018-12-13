<?php
class Geopportal_Partners_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_partners_widget', // Base ID
			esc_html__( 'GeoPlatform Partners', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Partners widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    // if (array_key_exists('geopportal_partners_title', $instance) && isset($instance['geopportal_partners_title']) && !empty($instance['geopportal_partners_title']))
    //   $geopportal_partners_disp_title = apply_filters('widget_title', $instance['geopportal_partners_title']);
		// else
    //   $geopportal_partners_disp_title = "Features &amp; Announcements";

		if (array_key_exists('geopportal_partners_main_title', $instance) && isset($instance['geopportal_partners_main_title']) && !empty($instance['geopportal_partners_main_title']))
      $geopportal_partners_main_title = apply_filters('widget_title', $instance['geopportal_partners_main_title']);
		else
      $geopportal_partners_main_title = "PARTNERS";

		if (array_key_exists('geopportal_partners_first_link', $instance) && isset($instance['geopportal_partners_first_link']) && !empty($instance['geopportal_partners_first_link']))
      $geopportal_partners_first_link = apply_filters('widget_link', $instance['geopportal_partners_first_link']);
		else
      $geopportal_partners_first_link = home_url();
		if (array_key_exists('geopportal_partners_second_link', $instance) && isset($instance['geopportal_partners_second_link']) && !empty($instance['geopportal_partners_second_link']))
      $geopportal_partners_second_link = apply_filters('widget_link', $instance['geopportal_partners_second_link']);
		else
      $geopportal_partners_second_link = home_url();
		if (array_key_exists('geopportal_partners_third_link', $instance) && isset($instance['geopportal_partners_third_link']) && !empty($instance['geopportal_partners_third_link']))
	    $geopportal_partners_third_link = apply_filters('widget_link', $instance['geopportal_partners_third_link']);
		else
	    $geopportal_partners_third_link = home_url();
		if (array_key_exists('geopportal_partners_fourth_link', $instance) && isset($instance['geopportal_partners_fourth_link']) && !empty($instance['geopportal_partners_fourth_link']))
	    $geopportal_partners_fourth_link = apply_filters('widget_link', $instance['geopportal_partners_fourth_link']);
		else
	    $geopportal_partners_fourth_link = home_url();
		if (array_key_exists('geopportal_partners_fifth_link', $instance) && isset($instance['geopportal_partners_fifth_link']) && !empty($instance['geopportal_partners_fifth_link']))
      $geopportal_partners_fifth_link = apply_filters('widget_link', $instance['geopportal_partners_fifth_link']);
		else
      $geopportal_partners_fifth_link = home_url();

		// Turns the slugs into pages.
		$geopportal_partners_get_page_params = array('post', 'page', 'geopccb_catlink');
		$geopportal_partners_first_page = get_page_by_path($geopportal_partners_first_link, OBJECT, $geopportal_partners_get_page_params);
		$geopportal_partners_second_page = get_page_by_path($geopportal_partners_second_link, OBJECT, $geopportal_partners_get_page_params);
		$geopportal_partners_third_page = get_page_by_path($geopportal_partners_third_link, OBJECT, $geopportal_partners_get_page_params);
		$geopportal_partners_fourth_page = get_page_by_path($geopportal_partners_fourth_link, OBJECT, $geopportal_partners_get_page_params);
		$geopportal_partners_fifth_page = get_page_by_path($geopportal_partners_fifth_link, OBJECT, $geopportal_partners_get_page_params);

		// Groups pages into an array and creates 2 support arrays for relevant info.
		$geopportal_partners_page_array = array($geopportal_partners_first_page, $geopportal_partners_second_page, $geopportal_partners_third_page, $geopportal_partners_fourth_page, $geopportal_partners_fifth_page);
		$geopportal_partners_thumb_array = array();
		$geopportal_partners_link_array = array();
		$geopportal_partners_title_array = array();

		// Determination loop that populates the support arrays with data from the page array.
		foreach($geopportal_partners_page_array as $geopportal_partners_page){
			if (has_post_thumbnail($geopportal_partners_page))
				array_push($geopportal_partners_thumb_array, get_the_post_thumbnail_url($geopportal_partners_page));
			else
				array_push($geopportal_partners_thumb_array, get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png');

		  if (get_post_type($geopportal_partners_page) == 'geopccb_catlink')
				array_push($geopportal_partners_link_array, esc_url($geopportal_partners_page->geop_ccb_cat_link_url));
			else
				array_push($geopportal_partners_link_array, get_the_permalink($geopportal_partners_page));

			array_push($geopportal_partners_title_array, get_the_title($geopportal_partners_page));
		}
		?>

		<div id="neg_geopportal_anchor_partners" title="Explore Partners">
			<article class="p-landing-page__partners">
		    <div class="m-article__heading m-article__heading--front-page"><?php _e(sanitize_text_field($geopportal_partners_main_title), 'geoplatform-ccb') ?></div>
		    <div class="m-partners__content">
		      <img alt="<?php echo $geopportal_partners_title_array[0] ?>" src="<?php echo $geopportal_partners_thumb_array[0] ?>">
					<img alt="<?php echo $geopportal_partners_title_array[1] ?>" src="<?php echo $geopportal_partners_thumb_array[1] ?>">
					<img alt="<?php echo $geopportal_partners_title_array[2] ?>" src="<?php echo $geopportal_partners_thumb_array[2] ?>">
					<img alt="<?php echo $geopportal_partners_title_array[3] ?>" src="<?php echo $geopportal_partners_thumb_array[3] ?>">
					<img alt="<?php echo $geopportal_partners_title_array[4] ?>" src="<?php echo $geopportal_partners_thumb_array[4] ?>">
		    </div>
			</article>
		</div>

    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_partners_title = ! empty( $instance['geopportal_partners_title'] ) ? $instance['geopportal_partners_title'] : 'Features &amp; Announcements';
    $geopportal_partners_main_title = ! empty( $instance['geopportal_partners_main_title'] ) ? $instance['geopportal_partners_main_title'] : 'PARTNERS';
    $geopportal_partners_first_link = ! empty( $instance['geopportal_partners_first_link'] ) ? $instance['geopportal_partners_first_link'] : '';
    $geopportal_partners_second_link = ! empty( $instance['geopportal_partners_second_link'] ) ? $instance['geopportal_partners_second_link'] : '';
    $geopportal_partners_third_link = ! empty( $instance['geopportal_partners_third_link'] ) ? $instance['geopportal_partners_third_link'] : '';
    $geopportal_partners_fourth_link = ! empty( $instance['geopportal_partners_fourth_link'] ) ? $instance['geopportal_partners_fourth_link'] : '';
		$geopportal_partners_fifth_link = ! empty( $instance['geopportal_partners_fifth_link'] ) ? $instance['geopportal_partners_fifth_link'] : '';

		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('The boxes below accept text and page slugs. Please ensure that any input slugs are valid. If the slug points to a Category Link post type, the external URL will be used instead.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_partners_main_title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_partners_main_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_partners_main_title' ); ?>" value="<?php echo esc_attr( $geopportal_partners_main_title ); ?>" />
    </p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_partners_first_link' ); ?>">First Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_partners_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_partners_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_partners_first_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_partners_second_link' ); ?>">Second Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_partners_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_partners_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_partners_second_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_partners_third_link' ); ?>">Third Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_partners_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_partners_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_partners_third_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_partners_fourth_link' ); ?>">Fourth Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_partners_fourth_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_partners_fourth_link' ); ?>" value="<?php echo esc_attr( $geopportal_partners_fourth_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_partners_fifth_link' ); ?>">Fifth Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_partners_fifth_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_partners_fifth_link' ); ?>" value="<?php echo esc_attr( $geopportal_partners_fifth_link ); ?>" />
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_partners_main_title' ] = strip_tags( $new_instance[ 'geopportal_partners_main_title' ] );
		$instance[ 'geopportal_partners_first_link' ] = strip_tags( $new_instance[ 'geopportal_partners_first_link' ] );
		$instance[ 'geopportal_partners_second_link' ] = strip_tags( $new_instance[ 'geopportal_partners_second_link' ] );
		$instance[ 'geopportal_partners_third_link' ] = strip_tags( $new_instance[ 'geopportal_partners_third_link' ] );
		$instance[ 'geopportal_partners_fourth_link' ] = strip_tags( $new_instance[ 'geopportal_partners_fourth_link' ] );
		$instance[ 'geopportal_partners_fifth_link' ] = strip_tags( $new_instance[ 'geopportal_partners_fifth_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_partners_widget() {
		register_widget( 'Geopportal_Partners_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_partners_widget' );