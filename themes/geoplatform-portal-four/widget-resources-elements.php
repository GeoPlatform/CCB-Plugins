<?php
class Geopportal_Resource_Elements_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_elements_widget', // Base ID
			esc_html__( 'GeoPlatform Resource Elements', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform elements widget for header sub-pages.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_elements_title', $instance) && isset($instance['geopportal_elements_title']) && !empty($instance['geopportal_elements_title']))
      $geopportal_elements_title = apply_filters('widget_title', $instance['geopportal_elements_title']);
		else
      $geopportal_elements_title = "Elements";
		if (array_key_exists('geopportal_elements_link', $instance) && isset($instance['geopportal_elements_link']) && !empty($instance['geopportal_elements_link']))
      $geopportal_elements_link = apply_filters('widget_title', $instance['geopportal_elements_link']);
		else
    	$geopportal_elements_link = "";


    //Grabs the featured_appearance value and declares the trimmed post array.
    $geopportal_featured_sort_format = get_theme_mod('featured_appearance', 'date');
    $geopportal_pages_final = array();
		$geopportal_pages_sort = array();

		$geopportal_pages = get_posts(array(
			'orderby' => 'date',
			'order' => 'DSC',
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_type' => array('post','page','geopccb_catlink'),
		) );

		// This list is then filtered for all pages in the Front Page category,
		// ending the loop after 6 results.
		foreach($geopportal_pages as $geopportal_page){
			if (in_category($geopportal_elements_link, $geopportal_page))
				array_push($geopportal_pages_sort, $geopportal_page);
		}

    // Mimics the old way of populating, but functional.
    if ($geopportal_featured_sort_format == 'date'){
			$geopportal_pages_final = $geopportal_pages_sort;
    }
    else {
			$geopportal_pages_trimmed = array();

      // Assigns pages with valid priority values to the trimmed array.
      foreach($geopportal_pages_sort as $geopportal_page){
        if ($geopportal_page->geop_ccb_post_priority > 0)
        	array_push($geopportal_pages_trimmed, $geopportal_page);
      }

      // Bubble sorts the resulting pages.
      $geopportal_pages_size = count($geopportal_pages_trimmed)-1;
      for ($i = 0; $i < $geopportal_pages_size; $i++) {
        for ($j = 0; $j < $geopportal_pages_size - $i; $j++) {
          $k = $j + 1;
          $geopportal_test_left = $geopportal_pages_trimmed[$j]->geop_ccb_post_priority;
          $geopportal_test_right = $geopportal_pages_trimmed[$k]->geop_ccb_post_priority;
          if ($geopportal_test_left > $geopportal_test_right) {
            // Swap elements at indices: $j, $k
            list($geopportal_pages_trimmed[$j], $geopportal_pages_trimmed[$k]) = array($geopportal_pages_trimmed[$k], $geopportal_pages_trimmed[$j]);
          }
        }
      }
      $geopportal_pages_final = $geopportal_pages_trimmed;
    }
		?>

		<!--
		ELEMENTS
		-->
		<div class="m-section-group">
				<article class="m-article">
						<div class="m-article__heading" id="geopportal_anchor_elements" title="Resource Elements"><?php _e(sanitize_text_field($geopportal_elements_title), 'geoplatform-ccb') ?></div>
						<div class="d-grid d-grid--3-col--lg">

						<?php
						foreach ($geopportal_pages_final as $geopportal_post){
							$geopportal_elements_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
							if ( has_post_thumbnail($geopportal_post) )
								$geopportal_elements_disp_thumb = get_the_post_thumbnail_url($geopportal_post);

							$geopportal_elements_disp_url = get_post_type($geopportal_post) == 'geopccb_catlink' ? esc_url($geopportal_post->geop_ccb_cat_link_url) : get_the_permalink($geopportal_post);
							?>
              <div class="m-tile m-tile--16x9">
                  <div class="m-tile__thumbnail">
                      <img alt="<?php echo get_the_title($geopportal_post); ?>" src="<?php echo $geopportal_elements_disp_thumb ?>">
                  </div>
                  <div class="m-tile__body">
                      <a href="<?php echo $geopportal_elements_disp_url ?>" class="m-tile__heading"><?php echo get_the_title($geopportal_post); ?></a>
                  </div>
              </div>
						<?php	} ?>
            </div>
				</article>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_elements_title = ! empty( $instance['geopportal_elements_title'] ) ? $instance['geopportal_elements_title'] : 'Features &amp; Announcements';
		$geopportal_elements_title = ! empty( $instance['geopportal_elements_title'] ) ? $instance['geopportal_elements_title'] : 'Elements';
		$geopportal_elements_link = ! empty( $instance['geopportal_elements_link'] ) ? $instance['geopportal_elements_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('Ensure to use a valid category name, not a slug.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_elements_title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_elements_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_elements_title' ); ?>" value="<?php echo esc_attr( $geopportal_elements_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_elements_link' ); ?>">Source Category:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_elements_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_elements_link' ); ?>" value="<?php echo esc_attr( $geopportal_elements_link ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

    // $instance[ 'geopportal_elements_title' ] = strip_tags( $new_instance[ 'geopportal_elements_title' ] );
		$instance[ 'geopportal_elements_title' ] = strip_tags( $new_instance[ 'geopportal_elements_title' ] );
		$instance[ 'geopportal_elements_link' ] = strip_tags( $new_instance[ 'geopportal_elements_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_elements_widget() {
		register_widget( 'Geopportal_Resource_Elements_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_elements_widget' );
