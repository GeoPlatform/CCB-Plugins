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

/* Assigns the variables stored in $_POST while instantiating blank variables
 * for conditional assignment.
*/
$ual_map_id = $_POST["mapID"];
$ual_map_height = $_POST["mapHeight"];
$ual_map_width = $_POST["mapWidth"];
$ual_url = '';
$link_scrub = '';
$response = '';
$result = '';
$map_agol = '0';
$invalid_bool = false;


// Field assignment. The map's url is set up, verified, and json decoded so that
// it may be used down the line. If any part of the process fails, invalid_bool
// is set to true and the process carries on. However, most of the remaining
// operations here require a false $invalid_bool.
$ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
$link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
$response = wp_remote_retrieve_body( $link_scrub );
if(!empty($response))
  $result = json_decode($response, true);
else
  $invalid_bool = true;


// Invalid map ID check. A faulty map ID will return a generic JSON dataset from
// GeoPlatform with a statusCode entry containing the "404" code. This will
// trigger invalid_bool and cause an echo back for user error reporting.
if ($result['statusCode'] == "404"){
  $invalid_bool = true;
  echo '{"status" : "Addition failed. Invalid map ID."}';
}


// Our custom table is pulled from $wpdb and prepped for iteration.
$table_name = $wpdb->prefix . 'newsmap_db';
$retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );


/* Validity and duplication check. Checks for Geoplatform maps for an AGOl-only
 * attribute and flip the $map_agol variable to 1/true if found. It will also
 * check for duplicate map IDs, echoing a failure message if found and flipping
 * $invalid_bool to true.
*/
if (!$invalid_bool){
  if ($result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap")
    $map_agol = '1';
  foreach ($retrieved_data as $entry){
    if ($entry->map_id == $ual_map_id){
      echo '{"status" : "Addition failed. Duplicate map detected."}';
      $invalid_bool = true;
      break;
    }
  }
}


/* If the map is valid and not a duplicate final values of the entry are set up
 * and entered into the database table.
*/
if (!$invalid_bool){

  // Basic information setup and blank field instantiation for conditional filling.
  $input = !empty($ual_map_id) ? $ual_map_id : "Empty";
  $map_id = $input;
  $map_url = "";
  $map_thumbnail = "";

  // Geomap block, featuring basic data setting from passed array.
  if ($map_agol == '0'){
    $map_url = 'http://sit-viewer.geoplatform.us/' . '?id=' . $map_id;
    $map_name = $result['label'];
    $map_description = $result['description'];
    $map_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";
  }
  else {
    // Agol block, pulling different values. Not all Agol maps have a description,
    // so such is checked for and a generic supplied if necessary. For extra
    // insurance, the same is done for the label/title.
    $map_url = $result['landingPage'];
    $map_name = $result['label'];
    if (empty($map_name)){
      $map_name = $result['title'];
      if (empty($map_name))
        $map_name = "An AGOL map.";
    }
    $map_description = $result['description'];
    if (empty($map_description)){
      $map_description = $result['title'];
      if (empty($map_description))
        $map_description = "This map does not have a description.";
    }
    $map_thumbnail = 'http://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";
  }

  /* The values of ual_map_height and _width are checked if numeric. If so, they
   * are concatenated into the shortcode. If not, the output will use default
   * side values.
  */
  $map_shortcode = "[geopmap id='" . $map_id . "' name='" . $map_name . "'";
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
      'map_agol' => $map_agol
    )
  );
}

?>
