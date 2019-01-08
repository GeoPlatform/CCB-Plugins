<?php
class Geopportal_Themes_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_themes_widget', // Base ID
			esc_html__( 'GeoPlatform Category List', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform themes widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_themes_title', $instance) && isset($instance['geopportal_themes_title']) && !empty($instance['geopportal_themes_title']))
      $geopportal_themes_title = apply_filters('widget_title', $instance['geopportal_themes_title']);
		else
      $geopportal_themes_title = "Themes";
		if (array_key_exists('geopportal_themes_link', $instance) && isset($instance['geopportal_themes_link']) && !empty($instance['geopportal_themes_link']))
      $geopportal_themes_link = apply_filters('widget_title', $instance['geopportal_themes_link']);
		else
    	$geopportal_themes_link = "";


    //Grabs the featured_appearance value and declares the trimmed post array.
    $geopportal_featured_sort_format = get_theme_mod('featured_appearance', 'date');
    $geopportal_pages_final = array();
		$geopportal_pages_sort = array();

		$geopportal_pages = get_posts(array(
			'orderby' => 'date',
			'order' => 'DSC',
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_type' => array('post','page','geopccb_catlink', 'community-post'),

		) );

		// This list is then filtered for all pages in the Front Page category,
		// ending the loop after 6 results.
		foreach($geopportal_pages as $geopportal_page){
			if (in_category($geopportal_themes_link, $geopportal_page))
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
		THEMES
		-->
		<article class="p-landing-page__themes">
		  <div class="m-article__heading m-article__heading--front-page" id="geopportal_anchor_themes" title="Themes"><?php _e(sanitize_text_field($geopportal_themes_title), 'geoplatform-ccb') ?></div>
		    <div class="m-article__desc">
          <div class="d-grid">
						<?php
						foreach ($geopportal_pages_final as $geopportal_post){
							$geopportal_themes_disp_thumb = get_template_directory_uri() . '/img/img-404.png';
							if ( has_post_thumbnail($geopportal_post) )
								$geopportal_themes_disp_thumb = get_the_post_thumbnail_url($geopportal_post);
							?>

							<a class="m-tile m-tile--4x3" href="<?php echo get_the_permalink($geopportal_post); ?>" title="<?php echo get_the_title($geopportal_post); ?>">
				      	<div class="m-tile__thumbnail">
				        	<img alt="ALT" src="<?php echo $geopportal_themes_disp_thumb ?>">
				      	</div>
				      	<div class="m-tile__body">
				        	<div class="m-tile__heading"><?php echo get_the_title($geopportal_post); ?></div>
				      	</div>
				    	</a>

						<?php	} ?>
				  </div>
			  </div>
			</article>

    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_themes_title = ! empty( $instance['geopportal_themes_title'] ) ? $instance['geopportal_themes_title'] : 'Features &amp; Announcements';
		$geopportal_themes_title = ! empty( $instance['geopportal_themes_title'] ) ? $instance['geopportal_themes_title'] : 'Themes';
		$geopportal_themes_link = ! empty( $instance['geopportal_themes_link'] ) ? $instance['geopportal_themes_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('Ensure to use a valid category name, not a slug.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_themes_title' ); ?>">Widget Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_themes_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_themes_title' ); ?>" value="<?php echo esc_attr( $geopportal_themes_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_themes_link' ); ?>">Source Category:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_themes_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_themes_link' ); ?>" value="<?php echo esc_attr( $geopportal_themes_link ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

    // $instance[ 'geopportal_themes_title' ] = strip_tags( $new_instance[ 'geopportal_themes_title' ] );
		$instance[ 'geopportal_themes_title' ] = strip_tags( $new_instance[ 'geopportal_themes_title' ] );
		$instance[ 'geopportal_themes_link' ] = strip_tags( $new_instance[ 'geopportal_themes_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_themes_widget() {
		register_widget( 'Geopportal_Themes_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_themes_widget' );
