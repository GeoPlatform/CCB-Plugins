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
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require( $parse_uri[0] . 'wp-load.php' );
global $wpdb;

/* Assigns the variable stored in $_POST to $ual_map_id, which will guide this
 * process. $ual_map_id is then scrubbed and prepped for use.
*/
$ual_map_id = $_POST["mapID"];
$ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
$link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
$response = wp_remote_retrieve_body( $link_scrub );

// Invalid submission detection.
if(!empty($response)){
  $result = json_decode($response, true);
}else{
  $result = "This Gallery has no recent activity. Try adding some maps!";
}

/* Our custom table is pulled from $wpdb and set up for iteration. A for loop
 * then cycles through the table, seeking rows with map_ids matching $ual_map_id,
 * removing them when found.
*/
$table_name = $wpdb->prefix . 'newsmap_db';
$retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );

foreach ($retrieved_data as $entry){
    $wpdb->delete($table_name, array('map_id' => $ual_map_id));
}
?>
