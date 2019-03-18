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
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('This widget is currently fully autonamous. Customization options may be added at a later date if needed.', 'geoplatform-ccb'); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_com_ngda_widget() {
		register_widget( 'Geopportal_Resource_NGDA_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_com_ngda_widget' );