<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 * @package    Geop_Galleries
 * @subpackage Geop_Galleries/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Geop_Galleries
 * @subpackage Geop_Galleries/includes
 * @author     Image Matters LLC <leeh@imagemattersllc.com>
 */
class Geop_Galleries_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'geop-galleries',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
