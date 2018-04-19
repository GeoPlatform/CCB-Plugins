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

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Geop_Maps
 * @subpackage Geop_Maps/public
 * @author     Kevin Schmidt <kevins@imagemattersllc.com>
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

		wp_enqueue_style( 'geop_leaflet_css', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_marker_cluster_css', 'https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_marker_default_css', 'https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_timedimension_css', 'https://cdn.rawgit.com/socib/Leaflet.TimeDimension/master/dist/leaflet.timedimension.control.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_font_awesome', 'https://use.fontawesome.com/releases/v5.0.10/css/all.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/geop-maps-public.css', array(), $this->version, 'all' );
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
		 */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'geop_leaflet_js', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_q', 'https://cdnjs.cloudflare.com/ajax/libs/q.js/1.5.1/q.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_iso8601', 'https://cdn.jsdelivr.net/npm/iso8601-js-period@0.2.1/iso8601.min.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_esri', 'https://cdnjs.cloudflare.com/ajax/libs/esri-leaflet/2.1.2/esri-leaflet.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_marker_cluster_js', 'https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_esri', 'https://cdnjs.cloudflare.com/ajax/libs/esri-leaflet/2.1.2/esri-leaflet.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_timedimension_js', 'https://cdn.rawgit.com/socib/Leaflet.TimeDimension/master/dist/leaflet.timedimension.min.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/geop-maps-public.js', array(), $this->version, false );
	}
}
