<?php
/**
 * Provide an area to run code in charge of adding maps to the database. This
 * class is called by the Add Map button in the display.php class.
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
$ual_map_height = $_POST["mapHeight"];
$ual_map_width = $_POST["mapWidth"];
$ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
$link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
$response = wp_remote_retrieve_body( $link_scrub );

// Invalid submission detection.
if(!empty($response)){
  $result = json_decode($response, true);
}else{
  $result = "This Gallery has no recent activity. Try adding some maps!";
}

// Our custom table is pulled from $wpdb.
$table_name = $wpdb->prefix . 'newsmap_db';
$retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );
$duplicate = false;

/* Duplicate check, which will deny addition if a map with this ID already exists
 * within the database.
*/
foreach ($retrieved_data as $entry){
  if ($entry->map_id == $ual_map_id){
    $duplicate = true;
    break;
  }
}

/* If the map is not a duplicate, variables are declared and given values based
 * upon the information grabbed from the geoplatform site using the $ual_map_id
 * key.
*/
if (!$duplicate){
  $input = !empty($ual_map_id) ? $ual_map_id : "Empty";
  $map_id = $input;
  $map_name = $result['label'];
  $map_description = $result['description'];
  $map_shortcode = "[geopmap id='" . $map_id . "' name='" . $map_name . "'";
  $map_url = 'https://sit-viewer.geoplatform.us/' . '?id=' . $map_id;
  $map_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";
  $map_rand_id = rand();

  /* The values of ual_map_height and _width are checked if numeric. If so, they
   * are concatenated into the shortcode. If not, the output will use default
   * side values.
  */
  if (is_numeric($ual_map_height))
    $map_shortcode .= " height='" . $ual_map_height . "'";
  if (is_numeric($ual_map_width))
    $map_shortcode .= " width='" . $ual_map_width . "'";
  $map_shortcode .= "]";

  // Finally, the variables are added to the table in key/value pairs.
  $wpdb->insert($table_name,
    array(
      'map_id' => $map_id,
      'map_name' => $map_name,
      'map_description' => $map_description,
      'map_shortcode' => $map_shortcode,
      'map_url' => $map_url,
      'map_thumbnail' => $map_thumbnail,
      'map_rand_id' => $map_rand_id
    )
  );
}

?>
