<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.0.0
 *
 * @package    Geoplatform_Map_Preview
 * @subpackage Geoplatform_Map_Preview/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Geoplatform_Map_Preview
 * @subpackage Geoplatform_Map_Preview/includes
 * @author     Image Matters LLC <servicedesk@geoplatform.gov>
 */
class Geoplatform_Map_Preview_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Activation flushes the page's rewrite rules.
		flush_rewrite_rules();
	}

}
