<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 * @package    Geop_Maps
 * @subpackage Geop_Maps/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Geop_Maps
 * @subpackage Geop_Maps/includes
 * @author     Kevin Schmidt <kevins@imagemattersllc.com>
 */
class Geop_Maps_Deactivator {

	/**
	 * Destroys the table. Here for testing, should be moved to uninstall later.
	*/
	private static function gpf_database_remove() {
	     global $wpdb;
	     $table_name = $wpdb->prefix . 'newsmap_db';
	     $sql = "DROP TABLE IF EXISTS $table_name;";
	     $wpdb->query($sql);
	     delete_option("my_plugin_db_version");
	}


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
		self::gpf_database_remove();
	}

}
