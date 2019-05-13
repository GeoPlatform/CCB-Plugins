<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.1.1
 *
 * @package    Geoplatform_Service_Collector
 * @subpackage Geoplatform_Service_Collector/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.1
 * @package    Geoplatform_Service_Collector
 * @subpackage Geoplatform_Service_Collector/includes
 * @author     Image Matters LLC <servicedesk@geoplatform.gov>
 */
class Geoplatform_Service_Collector_Activator {


	/**
	 * Creates the wpdb table for storing service input information.
	*/
	private static function geopserve_database_gen() {
	  global $wpdb;

	  $geopserve_table_name = $wpdb->prefix . 'geop_serve_db';
	  $geopserve_charset_collate = $wpdb->get_charset_collate();

	  // This creation segment only executes if the database does not already exist.
	  if($wpdb->get_var("show tables like '$geopserve_table_name'") != $geopserve_table_name){
	    $geopserve_sql = "CREATE TABLE $geopserve_table_name (
	      id mediumint(9) NOT NULL AUTO_INCREMENT,
	      time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	      serve_num varchar(255) NOT NULL,
				serve_id varchar(255) NOT NULL,
	      serve_name varchar(255) NOT NULL,
				serve_title varchar(255) NOT NULL,
				serve_cat varchar(255) NOT NULL,
				serve_count varchar(255) NOT NULL,
				serve_source varchar(255) NOT NULL,
				serve_shortcode varchar(255) NOT NULL,
	      PRIMARY KEY  (id)
	    ) $geopserve_charset_collate;";

	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	    dbDelta($geopserve_sql);
	  }

		// if($wpdb->get_var("show tables like '$geopserve_table_name'") != $geopserve_table_name){
	  //   $geopserve_sql = "CREATE TABLE $geopserve_table_name (
	  //     id mediumint(9) NOT NULL AUTO_INCREMENT,
	  //     time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  //     serve_num varchar(255) NOT NULL,
		// 		serve_id varchar(255) NOT NULL,
	  //     serve_name varchar(255) NOT NULL,
		// 		serve_title varchar(255) NOT NULL,
		// 		serve_cat varchar(255) NOT NULL,
		// 		serve_count varchar(255) NOT NULL,
		// 		serve_source varchar(255) NOT NULL,
		// 		serve_shortcode varchar(255) NOT NULL,
	  //     PRIMARY KEY  (id)
	  //   ) $geopserve_charset_collate;";
		//
	  //   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	  //   dbDelta($geopserve_sql);
	  // }
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.1
	 */
	public static function activate() {
		global $wpdb;
		self::geopserve_database_gen();
	}

}
