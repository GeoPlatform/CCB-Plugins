<?php
/**
 * Provide an area to run code in charge of removing maps to the database. This
 * class is called by the Remove Map button in the display.php class.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 */

 // Some legs had to be pulled to get $wpbd in here. Unsure why.
$geopmap_parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require( $geopmap_parse_uri[0] . 'wp-load.php' );
global $wpdb;

// URL variables for resource collection, defaults to production environment.
$geopmap_ual_url = 'https://ual.geoplatform.gov';
$geopmap_invalid_bool = false;
$geopmap_ual_map_id = sanitize_key($_POST["mapID"]);

if (!ctype_xdigit($geopmap_ual_map_id) || strlen($geopmap_ual_map_id) != 32){
  $geopmap_invalid_bool = true;
  echo '{"status" : "Addition failed. Invalid map ID."}';
}
else {

  /* Assigns the variable stored in $_POST to $geopmap_ual_map_id, which will guide this
   * process. $geopmap_ual_map_id is then scrubbed and prepped for use.
   */
  $geopmap_ual_url_in = $geopmap_ual_url . '/api/maps/' . $geopmap_ual_map_id;
  $geopmap_link_scrub = wp_remote_get( ''.$geopmap_ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
  $geopmap_response = wp_remote_retrieve_body( $geopmap_link_scrub );

  // Invalid submission detection.
  if(!empty($geopmap_response)){
    $geopmap_result = json_decode($geopmap_response, true);
  }else{
    echo '{"status" : "Removal failed. Invalid map ID."}';
    $geopmap_invalid_bool = true;
  }

  /* Our custom table is pulled from $wpdb and set up for iteration. A for loop
   * then cycles through the table, seeking rows with map_ids matching $geopmap_ual_map_id,
   * removing them when found.
  */
  $geopmap_table_name = $wpdb->prefix . 'geop_maps_db';
  $geopmap_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopmap_table_name" );

  if (!$geopmap_invalid_bool){
    foreach ($geopmap_retrieved_data as $geopmap_entry)
      $wpdb->delete($geopmap_table_name, array('map_id' => $geopmap_ual_map_id));
  }
}
?>
