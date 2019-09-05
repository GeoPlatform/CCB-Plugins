<?php
/**
 * Provide an area to run code in charge of removing maps to the database. This
 * class is called by the Remove Map button in the display.php class.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 */

 // $wpdb is evoked.
global $wpdb;

// All necessary variables are established, including pulling the map_id from $_POST.
$geopmap_ual_url = isset($_ENV['ual_url']) ? $_ENV['ual_url'] : 'https://ual.geoplatform.gov';
$geopmap_invalid_bool = false;
$geopmap_ual_map_id = sanitize_key($_POST["map_id"]);

// Data validation; map IDs must be hex and 32 characters long. Failure will
// end execution of the code in this file and an error return.
if (!ctype_xdigit($geopmap_ual_map_id) || strlen($geopmap_ual_map_id) != 32){
  $geopmap_invalid_bool = true;
  echo "Removal failed. Invalid map ID.";
}
else {

  // $geopmap_ual_map_id is scrubbed and prepped for use.
  $geopmap_ual_url_in = $geopmap_ual_url . '/api/maps/' . $geopmap_ual_map_id;
  $geopmap_link_scrub = wp_remote_get( ''.$geopmap_ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
  $geopmap_response = wp_remote_retrieve_body( $geopmap_link_scrub );

  // Invalid submission detection.
  if(!empty($geopmap_response)){
    $geopmap_result = json_decode($geopmap_response, true);
  }else{
    echo "Removal failed. Invalid map ID.";
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
