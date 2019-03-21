<?php
class Geopportal_Resource_NGDA_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_com_ngda_widget', // Base ID
			esc_html__( 'GeoPlatform Resource NGDA', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform NGDA Theme widget for resource pages. Outputs the NGDA themes in a grid format of icons. No customization methods are present at this time.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

		// Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_com_ngda_title', $instance) && isset($instance['geopportal_com_ngda_title']) && !empty($instance['geopportal_com_ngda_title']))
      $geopportal_com_ngda_title = apply_filters('widget_title', $instance['geopportal_com_ngda_title']);
		else
      $geopportal_com_ngda_title = "NGDA Themes";
		?>

		<!--
		ELEMENTS
		-->
		<div class="m-section-group">
		  <div class="m-article">
				<div class="m-article__heading"><?php _e(sanitize_text_field($geopportal_com_ngda_title), 'geoplatform-ccb') ?></div>
		    <br>
		    <div class="m-icon-grid">

		      <a href="<?php echo home_url('ngda/portfolio/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						<span class="u-text--sm">NGDA Portfolio Community</span>
		      </a>
		      <a href="<?php echo home_url('ngda/address/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Address Theme</span>
		      </a>
		      <a href="<?php echo home_url('ngda/biodiversity/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-frog t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Biodiversity &amp; Ecosystems</span>
		      </a>
		      <a href="<?php echo home_url('ngda/cadastre/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-scroll t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Cadastre</span>
		      </a>
		      <a href="<?php echo home_url('ngda/climate/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-cloud-sun-rain t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Climate &amp; Weather</span>
		      </a>
		      <a href="<?php echo home_url('ngda/cultural/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Cultural Resources</span>
		      </a>
		      <a href="<?php echo home_url('ngda/elevation/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-mountain t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Elevation</span>
		      </a>
		      <a href="<?php echo home_url('ngda/geodeticcontrol/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-compass t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Geodetic Control</span>
		      </a>
		      <a href="<?php echo home_url('ngda/geology/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-gem t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Geology</span>
		      </a>
		      <a href="<?php echo home_url('ngda/govunits/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-flag-usa t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Government Units and Administrative & Statistical Boundaries</span>
		      </a>
		      <a href="<?php echo home_url('ngda/imagery/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-layer-group t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Imagery</span>
		      </a>
		      <a href="<?php echo home_url('ngda/landuse/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Land Use - Land Cover</span>
		      </a>
		      <a href="<?php echo home_url('ngda/realproperty/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-building t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Real Property</span>
		      </a>
		      <a href="<?php echo home_url('ngda/soils/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-home t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Soils</span>
		      </a>
		      <a href="<?php echo home_url('ngda/transportation/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-shuttle-van t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Transportation</span>
		      </a>
		      <a href="<?php echo home_url('ngda/utilities/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-bolt t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Utilities</span>
		      </a>
		      <a href="<?php echo home_url('ngda/waterinland/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-stream t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Water - Inland</span>
		      </a>
		      <a href="<?php echo home_url('ngda/waterocean/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-stream t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Water - Oceans &amp; Coasts</span>
		      </a>
		    </div>
		  </div>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		$geopportal_com_ngda_title = ! empty( $instance['geopportal_com_ngda_title'] ) ? $instance['geopportal_com_ngda_title'] : 'NGDA Themes';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e("This widget's links are currently fully autonamous. You can however add a custom title.", 'geoplatform-ccb'); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_title' ); ?>">Widget Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_title' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_title ); ?>" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_com_ngda_title' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_title' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_com_ngda_widget() {
		register_widget( 'Geopportal_Resource_NGDA_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_com_ngda_widget' );
