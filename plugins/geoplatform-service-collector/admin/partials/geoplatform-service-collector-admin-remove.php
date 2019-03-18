<?php
/**
 * Provide an area to run code in charge of removing carousels from the database.
 * This class is called by the Remove Map button in the display.php class.
 *
 * @link       www.geoplatform.gov
 * @since      1.1.0
 *
 */

 // $wpdb is evoked.
global $wpdb;

// All necessary variables are established, including pulling the serve rand
// value from $_POST.
$geoserve_rand_id = sanitize_key($_POST["serve_rand"]);

/* Our custom table is pulled from $wpdb and set up for iteration. A loop then
 * cycles through the table, seeking rows with serve_num that matches
 * geoserve_rand_id, removing it when found.
*/
$geopmap_table_name = $wpdb->prefix . 'geop_serve_db';
$geopmap_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopmap_table_name" );

foreach ($geopmap_retrieved_data as $geopmap_entry)
  $wpdb->delete($geopmap_table_name, array('serve_num' => $geoserve_rand_id));

?>
