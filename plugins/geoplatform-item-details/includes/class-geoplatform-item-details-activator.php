<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.0.0
 *
 * @package    Geoplatform_Item_details
 * @subpackage Geoplatform_Item_details/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Geoplatform_Item_details
 * @subpackage Geoplatform_Item_details/includes
 * @author     Image Matters LLC <servicedesk@geoplatform.gov>
 */
class Geoplatform_Item_details_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-items' ))), true);
		$geopitems_interface_post = array(
			'post_title' => 'GeoPlatform Items',
			'post_name' => 'geoplatform-items',
			'post_status' => 'publish',
			'post_type' => 'page',
		);
		if ((strpos(strtolower(wp_get_theme()->get('Name')), 'geoplatform') !== false) && is_page_template('page-templates/geop_items_page.php'))
			$geopitems_interface_post = array_merge($geopitems_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/geop_items_page.php'));
		else if ((strpos(strtolower(wp_get_theme()->get('Name')), 'geoplatform') !== false) && is_page_template('page-templates/page_full-width.php'))
			$geopitems_interface_post = array_merge($geopitems_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/page_full-width.php'));
		else
			$geopitems_interface_post = array_merge($geopitems_interface_post, array('post_content' => '<app-root></app-root>'));

		wp_insert_post($geopitems_interface_post);

    // wpdocs_custom_post_types_registration();
		flush_rewrite_rules();
	}
}
