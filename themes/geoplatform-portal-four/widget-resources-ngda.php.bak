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

		if (array_key_exists('geopportal_com_ngda_icon_portfolio', $instance) && isset($instance['geopportal_com_ngda_icon_portfolio']) && !empty($instance['geopportal_com_ngda_icon_portfolio']))
      $geopportal_com_ngda_icon_portfolio = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_portfolio']);
		else
      $geopportal_com_ngda_icon_portfolio = "home";
		if (array_key_exists('geopportal_com_ngda_icon_address', $instance) && isset($instance['geopportal_com_ngda_icon_address']) && !empty($instance['geopportal_com_ngda_icon_address']))
      $geopportal_com_ngda_icon_address = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_address']);
		else
      $geopportal_com_ngda_icon_address = "home";
		if (array_key_exists('geopportal_com_ngda_icon_bio', $instance) && isset($instance['geopportal_com_ngda_icon_bio']) && !empty($instance['geopportal_com_ngda_icon_bio']))
      $geopportal_com_ngda_icon_bio = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_bio']);
		else
      $geopportal_com_ngda_icon_bio = "frog";
		if (array_key_exists('geopportal_com_ngda_icon_cadastre', $instance) && isset($instance['geopportal_com_ngda_icon_cadastre']) && !empty($instance['geopportal_com_ngda_icon_cadastre']))
      $geopportal_com_ngda_icon_cadastre = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_cadastre']);
		else
      $geopportal_com_ngda_icon_cadastre = "scroll";
		if (array_key_exists('geopportal_com_ngda_icon_climate', $instance) && isset($instance['geopportal_com_ngda_icon_climate']) && !empty($instance['geopportal_com_ngda_icon_climate']))
      $geopportal_com_ngda_icon_climate = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_climate']);
		else
      $geopportal_com_ngda_icon_climate = "cloud-sun-rain";
		if (array_key_exists('geopportal_com_ngda_icon_culture', $instance) && isset($instance['geopportal_com_ngda_icon_culture']) && !empty($instance['geopportal_com_ngda_icon_culture']))
      $geopportal_com_ngda_icon_culture = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_culture']);
		else
      $geopportal_com_ngda_icon_culture = "home";
		if (array_key_exists('geopportal_com_ngda_icon_elevate', $instance) && isset($instance['geopportal_com_ngda_icon_elevate']) && !empty($instance['geopportal_com_ngda_icon_elevate']))
      $geopportal_com_ngda_icon_elevate = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_elevate']);
		else
      $geopportal_com_ngda_icon_elevate = "mountain";
		if (array_key_exists('geopportal_com_ngda_icon_geodetic', $instance) && isset($instance['geopportal_com_ngda_icon_geodetic']) && !empty($instance['geopportal_com_ngda_icon_geodetic']))
      $geopportal_com_ngda_icon_geodetic = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_geodetic']);
		else
      $geopportal_com_ngda_icon_geodetic = "compass";
		if (array_key_exists('geopportal_com_ngda_icon_geology', $instance) && isset($instance['geopportal_com_ngda_icon_geology']) && !empty($instance['geopportal_com_ngda_icon_geology']))
      $geopportal_com_ngda_icon_geology = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_geology']);
		else
      $geopportal_com_ngda_icon_geology = "gem";
		if (array_key_exists('geopportal_com_ngda_icon_govunit', $instance) && isset($instance['geopportal_com_ngda_icon_govunit']) && !empty($instance['geopportal_com_ngda_icon_govunit']))
      $geopportal_com_ngda_icon_govunit = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_govunit']);
		else
      $geopportal_com_ngda_icon_govunit = "flag-usa";
		if (array_key_exists('geopportal_com_ngda_icon_imagery', $instance) && isset($instance['geopportal_com_ngda_icon_imagery']) && !empty($instance['geopportal_com_ngda_icon_imagery']))
      $geopportal_com_ngda_icon_imagery = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_imagery']);
		else
      $geopportal_com_ngda_icon_imagery = "layer-group";
		if (array_key_exists('geopportal_com_ngda_icon_land', $instance) && isset($instance['geopportal_com_ngda_icon_land']) && !empty($instance['geopportal_com_ngda_icon_land']))
      $geopportal_com_ngda_icon_land = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_land']);
		else
      $geopportal_com_ngda_icon_land = "home";
		if (array_key_exists('geopportal_com_ngda_icon_prop', $instance) && isset($instance['geopportal_com_ngda_icon_prop']) && !empty($instance['geopportal_com_ngda_icon_prop']))
      $geopportal_com_ngda_icon_prop = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_prop']);
		else
      $geopportal_com_ngda_icon_prop = "building";
		if (array_key_exists('geopportal_com_ngda_icon_soil', $instance) && isset($instance['geopportal_com_ngda_icon_soil']) && !empty($instance['geopportal_com_ngda_icon_soil']))
      $geopportal_com_ngda_icon_soil = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_soil']);
		else
      $geopportal_com_ngda_icon_soil = "home";
		if (array_key_exists('geopportal_com_ngda_icon_transport', $instance) && isset($instance['geopportal_com_ngda_icon_transport']) && !empty($instance['geopportal_com_ngda_icon_transport']))
      $geopportal_com_ngda_icon_transport = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_transport']);
		else
    	$geopportal_com_ngda_icon_transport = "shuttle-van";
		if (array_key_exists('geopportal_com_ngda_icon_util', $instance) && isset($instance['geopportal_com_ngda_icon_util']) && !empty($instance['geopportal_com_ngda_icon_util']))
      $geopportal_com_ngda_icon_util = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_util']);
		else
      $geopportal_com_ngda_icon_util = "bolt";
		if (array_key_exists('geopportal_com_ngda_icon_waterin', $instance) && isset($instance['geopportal_com_ngda_icon_waterin']) && !empty($instance['geopportal_com_ngda_icon_waterin']))
      $geopportal_com_ngda_icon_waterin = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_waterin']);
		else
      $geopportal_com_ngda_icon_waterin = "stream";
		if (array_key_exists('geopportal_com_ngda_icon_waterocean', $instance) && isset($instance['geopportal_com_ngda_icon_waterocean']) && !empty($instance['geopportal_com_ngda_icon_waterocean']))
      $geopportal_com_ngda_icon_waterocean = apply_filters('widget_title', $instance['geopportal_com_ngda_icon_waterocean']);
		else
      $geopportal_com_ngda_icon_waterocean = "stream";

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
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_portfolio) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">NGDA Portfolio Community</span>
		      </a>
		      <a href="<?php echo home_url('ngda/address/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_address) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Address Theme</span>
		      </a>
		      <a href="<?php echo home_url('ngda/biodiversity/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_bio) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Biodiversity &amp; Ecosystems</span>
		      </a>
		      <a href="<?php echo home_url('ngda/cadastre/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_cadastre) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Cadastre</span>
		      </a>
		      <a href="<?php echo home_url('ngda/climate/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_climate) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Climate &amp; Weather</span>
		      </a>
		      <a href="<?php echo home_url('ngda/cultural/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_culture) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Cultural Resources</span>
		      </a>
		      <a href="<?php echo home_url('ngda/elevation/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_elevate) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Elevation</span>
		      </a>
		      <a href="<?php echo home_url('ngda/geodeticcontrol/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_geodetic) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Geodetic Control</span>
		      </a>
		      <a href="<?php echo home_url('ngda/geology/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_geology) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Geology</span>
		      </a>
		      <a href="<?php echo home_url('ngda/govunits/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_govunit) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Government Units and Administrative & Statistical Boundaries</span>
		      </a>
		      <a href="<?php echo home_url('ngda/imagery/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_imagery) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Imagery</span>
		      </a>
		      <a href="<?php echo home_url('ngda/landuse/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_land) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Land Use - Land Cover</span>
		      </a>
		      <a href="<?php echo home_url('ngda/realproperty/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_prop) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Real Property</span>
		      </a>
		      <a href="<?php echo home_url('ngda/soils/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_soil) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Soils</span>
		      </a>
		      <a href="<?php echo home_url('ngda/transportation/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_transport) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Transportation</span>
		      </a>
		      <a href="<?php echo home_url('ngda/utilities/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_util) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Utilities</span>
		      </a>
		      <a href="<?php echo home_url('ngda/waterinland/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_waterin) ?> t-fg--blue-dk"></span><br>
						<span class="u-text--sm">Water - Inland</span>
		      </a>
		      <a href="<?php echo home_url('ngda/waterocean/'); ?>" class="u-text--center">
						<span class="fas fa-3x fa-<?php echo sanitize_text_field($geopportal_com_ngda_icon_waterocean) ?> t-fg--blue-dk"></span><br>
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
		$geopportal_com_ngda_icon_portfolio = ! empty( $instance['geopportal_com_ngda_icon_portfolio'] ) ? $instance['geopportal_com_ngda_icon_portfolio'] : 'home';
		$geopportal_com_ngda_icon_address = ! empty( $instance['geopportal_com_ngda_icon_address'] ) ? $instance['geopportal_com_ngda_icon_address'] : 'home';
		$geopportal_com_ngda_icon_bio = ! empty( $instance['geopportal_com_ngda_icon_bio'] ) ? $instance['geopportal_com_ngda_icon_bio'] : 'frog';
		$geopportal_com_ngda_icon_cadastre = ! empty( $instance['geopportal_com_ngda_icon_cadastre'] ) ? $instance['geopportal_com_ngda_icon_cadastre'] : 'scroll';
		$geopportal_com_ngda_icon_climate = ! empty( $instance['geopportal_com_ngda_icon_climate'] ) ? $instance['geopportal_com_ngda_icon_climate'] : 'cloud-sun-rain';
		$geopportal_com_ngda_icon_culture = ! empty( $instance['geopportal_com_ngda_icon_culture'] ) ? $instance['geopportal_com_ngda_icon_culture'] : 'home';
		$geopportal_com_ngda_icon_elevate = ! empty( $instance['geopportal_com_ngda_icon_elevate'] ) ? $instance['geopportal_com_ngda_icon_elevate'] : 'mountain';
		$geopportal_com_ngda_icon_geodetic = ! empty( $instance['geopportal_com_ngda_icon_geodetic'] ) ? $instance['geopportal_com_ngda_icon_geodetic'] : 'compass';
		$geopportal_com_ngda_icon_geology = ! empty( $instance['geopportal_com_ngda_icon_geology'] ) ? $instance['geopportal_com_ngda_icon_geology'] : 'gem';
		$geopportal_com_ngda_icon_govunit = ! empty( $instance['geopportal_com_ngda_icon_govunit'] ) ? $instance['geopportal_com_ngda_icon_govunit'] : 'flag-usa';
		$geopportal_com_ngda_icon_imagery = ! empty( $instance['geopportal_com_ngda_icon_imagery'] ) ? $instance['geopportal_com_ngda_icon_imagery'] : 'layer-group';
		$geopportal_com_ngda_icon_land = ! empty( $instance['geopportal_com_ngda_icon_land'] ) ? $instance['geopportal_com_ngda_icon_land'] : 'home';
		$geopportal_com_ngda_icon_prop = ! empty( $instance['geopportal_com_ngda_icon_prop'] ) ? $instance['geopportal_com_ngda_icon_prop'] : 'building';
		$geopportal_com_ngda_icon_soil = ! empty( $instance['geopportal_com_ngda_icon_soil'] ) ? $instance['geopportal_com_ngda_icon_soil'] : 'home';
		$geopportal_com_ngda_icon_transport = ! empty( $instance['geopportal_com_ngda_icon_transport'] ) ? $instance['geopportal_com_ngda_icon_transport'] : 'shuttle-van';
		$geopportal_com_ngda_icon_util = ! empty( $instance['geopportal_com_ngda_icon_util'] ) ? $instance['geopportal_com_ngda_icon_util'] : 'bolt';
		$geopportal_com_ngda_icon_waterin = ! empty( $instance['geopportal_com_ngda_icon_waterin'] ) ? $instance['geopportal_com_ngda_icon_waterin'] : 'stream';
		$geopportal_com_ngda_icon_waterocean = ! empty( $instance['geopportal_com_ngda_icon_waterocean'] ) ? $instance['geopportal_com_ngda_icon_waterocean'] : 'stream';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e("In addition to title, this widget allows you to switch out icons. Please use names utilized by Font Awesome, with the 'fa-' portion left out.", 'geoplatform-ccb'); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_title' ); ?>">Widget Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_title' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_title ); ?>" />
		</p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_portfolio' ); ?>">Portfolio Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_portfolio' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_portfolio' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_portfolio ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_address' ); ?>">Address Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_address' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_address' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_address ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_bio' ); ?>">Biodiversity Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_bio' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_bio' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_bio ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_cadastre' ); ?>">Cadastre Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_cadastre' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_cadastre' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_cadastre ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_climate' ); ?>">Climate Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_climate' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_climate' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_climate ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_culture' ); ?>">Cultural Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_culture' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_culture' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_culture ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_elevate' ); ?>">Elevation Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_elevate' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_elevate' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_elevate ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_geodetic' ); ?>">Geodetic Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_geodetic' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_geodetic' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_geodetic ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_geology' ); ?>">Geology Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_geology' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_geology' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_geology ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_govunit' ); ?>">Gov Units Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_govunit' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_govunit' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_govunit ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_imagery' ); ?>">Imagery Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_imagery' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_imagery' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_imagery ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_land' ); ?>">Land Use Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_land' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_land' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_land ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_prop' ); ?>">Real Property Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_prop' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_prop' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_prop ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_soil' ); ?>">Soils Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_soil' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_soil' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_soil ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_transport' ); ?>">Transportation Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_transport' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_transport' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_transport ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_util' ); ?>">Utilities Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_util' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_util' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_util ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_waterin' ); ?>">Water - Inland Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_waterin' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_waterin' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_waterin ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_waterocean' ); ?>">Water - Oceans Icon:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_com_ngda_icon_waterocean' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_com_ngda_icon_waterocean' ); ?>" value="<?php echo esc_attr( $geopportal_com_ngda_icon_waterocean ); ?>" />
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance[ 'geopportal_com_ngda_title' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_title' ] );
		$instance[ 'geopportal_com_ngda_icon_portfolio' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_portfolio' ] );
		$instance[ 'geopportal_com_ngda_icon_address' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_address' ] );
		$instance[ 'geopportal_com_ngda_icon_bio' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_bio' ] );
		$instance[ 'geopportal_com_ngda_icon_cadastre' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_cadastre' ] );
		$instance[ 'geopportal_com_ngda_icon_climate' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_climate' ] );
		$instance[ 'geopportal_com_ngda_icon_culture' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_culture' ] );
		$instance[ 'geopportal_com_ngda_icon_elevate' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_elevate' ] );
		$instance[ 'geopportal_com_ngda_icon_geodetic' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_geodetic' ] );
		$instance[ 'geopportal_com_ngda_icon_geology' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_geology' ] );
		$instance[ 'geopportal_com_ngda_icon_govunit' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_govunit' ] );
		$instance[ 'geopportal_com_ngda_icon_imagery' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_imagery' ] );
		$instance[ 'geopportal_com_ngda_icon_land' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_land' ] );
		$instance[ 'geopportal_com_ngda_icon_prop' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_prop' ] );
		$instance[ 'geopportal_com_ngda_icon_soil' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_soil' ] );
		$instance[ 'geopportal_com_ngda_icon_transport' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_transport' ] );
		$instance[ 'geopportal_com_ngda_icon_util' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_util' ] );
		$instance[ 'geopportal_com_ngda_icon_waterin' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_waterin' ] );
		$instance[ 'geopportal_com_ngda_icon_waterocean' ] = strip_tags( $new_instance[ 'geopportal_com_ngda_icon_waterocean' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_com_ngda_widget() {
		register_widget( 'Geopportal_Resource_NGDA_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_com_ngda_widget' );
