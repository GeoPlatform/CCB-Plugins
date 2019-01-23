<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.0.0
 *
 * @package    Geoplatform_Item_Details
 * @subpackage Geoplatform_Item_Details/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Geoplatform_Item_Details
 * @subpackage Geoplatform_Item_Details/includes
 * @author     Image Matters LLC <servicedesk@geoplatform.gov>
 */
class Geoplatform_Item_Details_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'geoplatform-item-details',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
