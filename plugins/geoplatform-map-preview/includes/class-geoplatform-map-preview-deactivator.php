<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.0.0
 *
 * @package    Geoplatform_Map_Preview
 * @subpackage Geoplatform_Map_Preview/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Geoplatform_Map_Preview
 * @subpackage Geoplatform_Map_Preview/includes
 * @author     Image Matters LLC <servicedesk@geoplatform.gov>
 */
class Geoplatform_Map_Preview_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Deactivation flushes the page's rewrite rules.
		flush_rewrite_rules();
	}
}
