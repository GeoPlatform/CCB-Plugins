<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 * @package    Geop_Maps
 * @subpackage Geop_Maps/public
 */

class Geop_Maps_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Geop_Maps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Geop_Maps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	 	wp_enqueue_style( 'geop_bootstrap_css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_leaflet_css', plugin_dir_url( __FILE__ ) . 'assets/leaflet_1.3.1.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_marker_cluster_css', plugin_dir_url( __FILE__ ) . 'assets/MarkerCluster_1.3.0.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_marker_default_css', plugin_dir_url( __FILE__ ) . 'assets/MarkerCluster.Default_1.3.0.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_timedimension_css', plugin_dir_url( __FILE__ ) . 'assets/leaflet.timedimension.control_1.1.0.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_font_awesome', plugin_dir_url( __FILE__ ) . 'assets/fontawesome-all.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/geoplatform-maps-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Geop_Maps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Geop_Maps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 *
		 * Make sure to uncomment the geoplatform enqueues and comment out their
		 * straight evocations in geoplatform-maps.php in production or non-GeoPlatform
		 * theme environments.
		 */
		wp_enqueue_script( 'jquery' );


		wp_enqueue_script( 'geop_leaflet_js', plugin_dir_url( __FILE__ ) . 'assets/leaflet-src_1.3.1.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_q', plugin_dir_url( __FILE__ ) . 'assets/q_2.0.3.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_iso8601', plugin_dir_url( __FILE__ ) . 'assets/iso8601_0.2.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_marker_cluster_js', plugin_dir_url( __FILE__ ) . 'assets/leaflet.markercluster-src_1.3.0.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_esri', plugin_dir_url( __FILE__ ) . 'assets/esri-leaflet_2.1.3.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_timedimension_js', plugin_dir_url( __FILE__ ) . 'assets/leaflet.timedimension.src_1.1.0.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_framework', plugin_dir_url( __FILE__ ) . 'assets/geoplatform.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_leaflet_draw', plugin_dir_url( __FILE__ ) . 'assets/leaflet.draw.js', array(), $this->version, true );

		wp_enqueue_script( 'geop_axios', plugin_dir_url( __FILE__ ) . 'assets/axios.js', array(), $this->version, true );
		wp_enqueue_script( 'geop_client_api', plugin_dir_url( __FILE__ ) . 'assets/geoplatform-client.umd.min.js', array( 'geop_axios', 'jquery' ), $this->version, true );
		wp_enqueue_script( 'geop_mapcore', plugin_dir_url( __FILE__ ) . 'assets/geoplatform-mapcore.umd.min.js', array( 'geop_axios' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/geoplatform-maps-public.js', array(), $this->version, true );
	}
}
