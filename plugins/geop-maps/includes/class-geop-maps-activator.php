<?php

/**
 * Fired during plugin activation
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 * @package    Geop_Maps
 * @subpackage Geop_Maps/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Geop_Maps
 * @subpackage Geop_Maps/includes
 * @author     Kevin Schmidt <kevins@imagemattersllc.com>
 */
class Geop_Maps_Activator {


	/**
	 * Creates the wpdb table for storing map input information.
	*/
	private static function gpf_database_gen() {
	  global $wpdb;

	  $table_name = $wpdb->prefix . "newsmap_db";
	  $charset_collate = $wpdb->get_charset_collate();

	  // This creation segment only executes if the database does not already exist.
	  if($wpdb->get_var("show tables like '$table_name'") != $table_name){
	    $sql = "CREATE TABLE $table_name (
	      id mediumint(9) NOT NULL AUTO_INCREMENT,
	      time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	      map_id varchar(255) NOT NULL,
	      map_name varchar(255) NOT NULL,
				map_description varchar(255) NOT NULL,
				map_shortcode varchar(255) NOT NULL,
				map_url varchar(255) NOT NULL,
				map_thumbnail varchar(255) NOT NULL,
				map_agol varchar(255) NOT NULL,
	      PRIMARY KEY  (id)
	    ) $charset_collate;";

	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($sql);
	  }
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		self::gpf_database_gen();
	}
}
