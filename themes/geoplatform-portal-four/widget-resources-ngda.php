<?php
class Geopportal_Resource_NGDA_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_com_ngda_widget', // Base ID
			esc_html__( 'GeoPlatform Resource NGDA', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform NGDA Theme widget for header sub-pages. Outputs the NGDA themes in an icon format. No customization methods are present at this time.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		// if (array_key_exists('geopportal_com_ngda_title', $instance) && isset($instance['geopportal_com_ngda_title']) && !empty($instance['geopportal_com_ngda_title']))
    //   $geopportal_com_ngda_title = apply_filters('widget_title', $instance['geopportal_com_ngda_title']);
		// else
    //   $geopportal_com_ngda_title = "Community";
		// if (array_key_exists('geopportal_com_ngda_link', $instance) && isset($instance['geopportal_com_ngda_link']) && !empty($instance['geopportal_com_ngda_link']))
    //   $geopportal_com_ngda_link = apply_filters('widget_title', $instance['geopportal_com_ngda_link']);
		// else
    // 	$geopportal_com_ngda_link = "";


    //Grabs the featured_appearance value and declares the trimmed post array.
    // $geopportal_featured_sort_format = get_theme_mod('featured_appearance', 'date');
    // $geopportal_pages_final = array();
		// $geopportal_pages_sort = array();
		//
		// $geopportal_pages = get_posts(array(
		// 	'orderby' => 'date',
		// 	'order' => 'DSC',
		// 	'numberposts' => -1,
		// 	'post_status' => 'publish',
		// 	'post_type' => array('post','page','geopccb_catlink', 'community-post', 'ngda-post'),
		// ) );

		// This list is then filtered for all pages in the Front Page category,
		// ending the loop after 6 results.
		// foreach($geopportal_pages as $geopportal_page){
		// 	if (in_category($geopportal_com_ngda_link, $geopportal_page))
		// 		array_push($geopportal_pages_sort, $geopportal_page);
		// }
		//
    // // Mimics the old way of populating, but functional.
    // if ($geopportal_featured_sort_format == 'date'){
		// 	$geopportal_pages_final = $geopportal_pages_sort;
    // }
    // else {
		// 	$geopportal_pages_trimmed = array();
		//
    //   // Assigns pages with valid priority values to the trimmed array.
    //   foreach($geopportal_pages_sort as $geopportal_page){
    //     if ($geopportal_page->geop_ccb_post_priority > 0)
    //     	array_push($geopportal_pages_trimmed, $geopportal_page);
    //   }
		//
    //   // Bubble sorts the resulting pages.
    //   $geopportal_pages_size = count($geopportal_pages_trimmed)-1;
    //   for ($i = 0; $i < $geopportal_pages_size; $i++) {
    //     for ($j = 0; $j < $geopportal_pages_size - $i; $j++) {
    //       $k = $j + 1;
    //       $geopportal_test_left = $geopportal_pages_trimmed[$j]->geop_ccb_post_priority;
    //       $geopportal_test_right = $geopportal_pages_trimmed[$k]->geop_ccb_post_priority;
    //       if ($geopportal_test_left > $geopportal_test_right) {
    //         // Swap community at indices: $j, $k
    //         list($geopportal_pages_trimmed[$j], $geopportal_pages_trimmed[$k]) = array($geopportal_pages_trimmed[$k], $geopportal_pages_trimmed[$j]);
    //       }
    //     }
    //   }
    //   $geopportal_pages_final = $geopportal_pages_trimmed;
    // }
		?>

		<!--
		ELEMENTS
		-->
		<div class="m-section-group">
		  <div class="m-article">
		    <div class="m-article__heading">NGDA Themes</div>
		    <br>
		    <div class="d-grid d-grid--3-col--lg">

		      <a href="<?php echo home_url('ngda/portfolio/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						NGDA Portfolio Community
		      </a>
		      <a href="<?php echo home_url('ngda/address/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						Address Theme
		      </a>
		      <a href="<?php echo home_url('ngda/biodiversity/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-frog t-fg--blue-dk"></span><br>
						Biodiversity &amp; Ecosystems
		      </a>
		      <a href="<?php echo home_url('ngda/cadastre/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-scroll t-fg--blue-dk"></span><br>
						Cadastre
		      </a>
		      <a href="<?php echo home_url('ngda/climate/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-cloud-sun-rain t-fg--blue-dk"></span><br>
						Climate &amp; Weather
		      </a>
		      <a href="<?php echo home_url('ngda/cultural/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						Cultural Resources
		      </a>
		      <a href="<?php echo home_url('ngda/elevation/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-mountain t-fg--blue-dk"></span><br>
						Elevation
		      </a>
		      <a href="<?php echo home_url('ngda/geodeticcontrol/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-compass t-fg--blue-dk"></span><br>
						Geodetic Control
		      </a>
		      <a href="<?php echo home_url('ngda/geology/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-gem t-fg--blue-dk"></span><br>
						Geology
		      </a>
		      <a href="<?php echo home_url('ngda/govunits/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-flag-usa t-fg--blue-dk"></span><br>
						Units and Administrative & Statistical Boundaries
		      </a>
		      <a href="<?php echo home_url('ngda/imagery/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-layer-group t-fg--blue-dk"></span><br>
						Imagery
		      </a>
		      <a href="<?php echo home_url('ngda/landuse/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						Land Use - Land Cover
		      </a>
		      <a href="<?php echo home_url('ngda/realproperty/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-building t-fg--blue-dk"></span><br>
						Real Property
		      </a>
		      <a href="<?php echo home_url('ngda/soils/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						Soils
		      </a>
		      <a href="<?php echo home_url('ngda/transportation/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-shuttle-van t-fg--blue-dk"></span><br>
						Transportation
		      </a>
		      <a href="<?php echo home_url('ngda/utilities/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-bolt t-fg--blue-dk"></span><br>
						Utilities
		      </a>
		      <a href="<?php echo home_url('ngda/waterinland/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-stream t-fg--blue-dk"></span><br>
						Water - Inland
		      </a>
		      <a href="<?php echo home_url('ngda/waterocean/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-stream t-fg--blue-dk"></span><br>
						Water - Oceans &amp; Coasts
		      </a>
		    </div>
		  </div>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_com_ngda_title = ! empty( $instance['geopportal_com_ngda_title'] ) ? $instance['geopportal_com_ngda_title'] : 'Features &amp; Announcements';
		// $geopportal_com_ngda_title = ! empty( $instance['geopportal_com_ngda_title'] ) ? $instance['geopportal_com_ngda_title'] : 'Community';
		// $geopportal_com_ngda_link = ! empty( $instance['geopportal_com_ngda_link'] ) ? $instance['geopportal_com_ngda_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('This widget is currently fully autonamous. Customization options may be added at a later date if needed.', 'geoplatform-ccb'); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

    // $instance[ 'geopportal_com_ngda_title' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_title' ] );
		// $instance[ 'geopportal_com_ngda_title' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_title' ] );
		// $instance[ 'geopportal_com_ngda_link' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_com_ngda_widget() {
		register_widget( 'Geopportal_Resource_NGDA_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_com_ngda_widget' );
