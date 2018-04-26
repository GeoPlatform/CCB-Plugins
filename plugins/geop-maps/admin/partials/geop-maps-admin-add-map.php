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
$geopmap_parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require( $geopmap_parse_uri[0] . 'wp-load.php' );
global $wpdb;

// Grabs the file that handles environmental variables.
require_once('../../includes/class-geop-maps-urlbank.php');

/* Assigns the variables stored in $_POST while instantiating blank variables
 * for conditional assignment.
*/
$geopmap_ual_map_id = $_POST["mapID"];
$geopmap_ual_map_height = $_POST["mapHeight"];
$geopmap_ual_map_width = $_POST["mapWidth"];
$geopmap_ual_url_in = '';
$geopmap_link_scrub = '';
$geopmap_response = '';
$geopmap_result = '';
$geopmap_agol = '0';
$geopmap_invalid_bool = false;

// URL variables for pinging the url bank for environment URLs. Checks for a
// GeoPlatform theme, pulling the global env variable and checking it as well
// for a valid value. If either check fails, geop_env defaults to 'prd', which
// will produce production-state URLs from the url bank.
//
// Disabled for Wordpress public distribution due to reliance on an as-yet
// unreleased GeoPlatform theme.

$geop_env = 'prd';
// if (substr(get_template(), 0, 11) == "GeoPlatform"){
//   global $env;
//   if ($env == 'dev' || $env == 'stg')
//     $geop_env = $env;
// }

// Instantiates the URL bank for environment variable grabbing.
$Geop_url_class = new Geop_url_bank;

// URL variables for resource collection, defaults to production environment.
$geop_ual_url = $Geop_url_class->geop_maps_get_ual_url($geop_env);
$geop_viewer_url = $Geop_url_class->geop_maps_get_viewer_url($geop_env);

// Field assignment. The map's url is set up, verified, and json decoded so that
// it may be used down the line. If any part of the process fails, invalid_bool
// is set to true and the process carries on. However, most of the remaining
// operations here require a false $geopmap_invalid_bool.
$geopmap_ual_url_in = $geop_ual_url . '/api/maps/' . $geopmap_ual_map_id;
$geopmap_link_scrub = wp_remote_get( ''.$geopmap_ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
$geopmap_response = wp_remote_retrieve_body( $geopmap_link_scrub );
if(!empty($geopmap_response))
  $geopmap_result = json_decode($geopmap_response, true);
else {
  $geopmap_invalid_bool = true;
  echo '{"status" : "Addition failed. Map source could not be contacted."}';
}


// Invalid map ID check. A faulty map ID will return a generic JSON dataset from
// GeoPlatform with a statusCode entry containing the "404" code. This will
// trigger invalid_bool and cause an echo back for user error reporting.
if (!$geopmap_invalid_bool && array_key_exists('statusCode', $geopmap_result) && $geopmap_result['statusCode'] == "404"){
  $geopmap_invalid_bool = true;
  echo '{"status" : "Addition failed. Invalid map ID."}';
}


// Our custom table is pulled from $wpdb and prepped for iteration.
$geopmap_table_name = $wpdb->prefix . 'geop_maps_db';
$geopmap_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopmap_table_name" );


/* Validity and duplication check. Checks for Geoplatform maps for an AGOl-only
 * attribute and flip the $geopmap_agol variable to 1/true if found. It will also
 * check for duplicate map IDs, echoing a failure message if found and flipping
 * $geopmap_invalid_bool to true.
*/
if (!$geopmap_invalid_bool){
  if (array_key_exists('resourceTypes', $geopmap_result) && $geopmap_result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap")
    $geopmap_agol = '1';
  foreach ($geopmap_retrieved_data as $geopmap_entry){
    if ($geopmap_entry->map_id == $geopmap_ual_map_id){
      echo '{"status" : "Addition failed. Duplicate map detected."}';
      $geopmap_invalid_bool = true;
      break;
    }
  }
}


/* If the map is valid and not a duplicate final values of the entry are set up
 * and entered into the database table.
*/
if (!$geopmap_invalid_bool){

  // Basic information setup and blank field instantiation for conditional filling.
  $geopmap_input = !empty($geopmap_ual_map_id) ? $geopmap_ual_map_id : "Empty";
  $geopmap_map_id = $geopmap_input;
  $geopmap_map_url = "";
  $geopmap_map_thumbnail = "";

  // Geomap block, featuring basic data setting from passed array.
  if ($geopmap_agol == '0'){
    $geopmap_map_url = $geop_viewer_url . '/?id=' . $geopmap_map_id;
    $geopmap_map_name = $geopmap_result['label'];
    $geopmap_map_description = $geopmap_result['description'];
    $geopmap_map_thumbnail = $geop_ual_url . '/api/maps/'. $geopmap_map_id . "/thumbnail";
  }
  else {
    // Agol block, pulling different values. Not all Agol maps have a description,
    // so such is checked for and a generic supplied if necessary. For extra
    // insurance, the same is done for the label/title.
    $geopmap_map_url = $geopmap_result['landingPage'];
    $geopmap_map_name = $geopmap_result['label'];
    if (empty($geopmap_map_name)){
      $geopmap_map_name = $geopmap_result['title'];
      if (empty($geopmap_map_name))
        $geopmap_map_name = "An AGOL map.";
    }
    $geopmap_map_description = $geopmap_result['description'];
    if (empty($geopmap_map_description)){
      $geopmap_map_description = $geopmap_result['title'];
      if (empty($geopmap_map_description))
        $geopmap_map_description = "This map does not have a description.";
    }
    $geopmap_map_thumbnail = $geop_ual_url . '/api/maps/'. $geopmap_map_id . "/thumbnail";
  }

  /* The values of ual_map_height and _width are checked if numeric. If so, they
   * are concatenated into the shortcode. If not, the output will use default
   * side values.
  */
  $geopmap_shortcode = "[geopmap id='" . $geopmap_map_id . "' name='" . $geopmap_map_name . "'";
  if (is_numeric($geopmap_ual_map_height))
    $geopmap_shortcode .= " height='" . $geopmap_ual_map_height . "'";
  if (is_numeric($geopmap_ual_map_width))
    $geopmap_shortcode .= " width='" . $geopmap_ual_map_width . "'";
  $geopmap_shortcode .= "]";

  // Finally, the variables are added to the table in key/value pairs.
  $wpdb->insert($geopmap_table_name,
    array(
      'map_id' => $geopmap_map_id,
      'map_name' => $geopmap_map_name,
      'map_description' => $geopmap_map_description,
      'map_shortcode' => $geopmap_shortcode,
      'map_url' => $geopmap_map_url,
      'map_thumbnail' => $geopmap_map_thumbnail,
      'map_agol' => $geopmap_agol
    )
  );
}

?>
