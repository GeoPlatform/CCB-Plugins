<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.1.1
 *
 * @package    Geoplatform_Service_Collector
 * @subpackage Geoplatform_Service_Collector/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Geoplatform_Service_Collector
 * @subpackage Geoplatform_Service_Collector/public
 * @author     Image Matters LLC <servicedesk@geoplatform.gov>
 */
class Geoplatform_Service_Collector_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.1
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
	 * @since    1.1.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Geoplatform_Service_Collector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Geoplatform_Service_Collector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'geop_bootstrap_css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'geop_font_awesome', plugin_dir_url( __FILE__ ) . 'font/fontawesome-all_5.0.10.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/geoplatform-service-collector-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.1.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Geoplatform_Service_Collector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Geoplatform_Service_Collector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'jquery' );

		if (wp_script_is('geop_bootstrap_js'))
			wp_enqueue_script( 'geop_bootstrap_js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'geop_q', plugin_dir_url( __FILE__ ) . 'js/q_2.0.3.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_client_api', plugin_dir_url( __FILE__ ) . 'js/geoplatform.client.js', array(), $this->version, false );
		wp_enqueue_script( 'geop_framework', plugin_dir_url( __FILE__ ) . 'js/geoplatform.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/geoplatform-service-collector-public.js', array( 'jquery' ), $this->version, false );
	}

}
