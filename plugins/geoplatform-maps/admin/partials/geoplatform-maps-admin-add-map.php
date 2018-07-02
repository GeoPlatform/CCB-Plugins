<?php
/**
 * Provide an area to run code in charge of adding maps to the database. This
 * class is called by the Add Map button in the display.php class.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 */

// $wpdb is evoked.
global $wpdb;

/* Assigns the variables stored in $_POST while instantiating blank variables
 * for conditional assignment.
*/
$geopmap_ual_map_id = sanitize_key($_POST["map_id"]);
$geopmap_ual_map_height = sanitize_key($_POST["map_height"]);
$geopmap_ual_map_width = sanitize_key($_POST["map_width"]);
$geopmap_ual_url_in = '';
$geopmap_link_scrub = '';
$geopmap_response = '';
$geopmap_result = '';
$geopmap_agol = '0';
$geopmap_invalid_bool = false;
$geopmap_table_name = "";
$geopmap_retrieved_data = "";

// URL variables for resource collection.
$geopmap_ual_url = 'https://ual.geoplatform.gov';
$geopmap_viewer_url = 'https://viewer.geoplatform.gov';

// Data validation. $geopmap_ual_map_id must be a 32-digit HEX value, while the
// height and width inputs must be either numeric or blank. Failure anywhere
// here switches invalid_bool to true.
if (!ctype_xdigit($geopmap_ual_map_id) || strlen($geopmap_ual_map_id) != 32){
  $geopmap_invalid_bool = true;
  echo "Addition failed. Invalid map ID format.";
}
if (!$geopmap_invalid_bool && $geopmap_ual_map_height != "" && !is_numeric($geopmap_ual_map_height)){
  $geopmap_invalid_bool = true;
  echo "Addition failed. Invalid map height. Only a numeric value or blank input is allowed.";
}
if (!$geopmap_invalid_bool && $geopmap_ual_map_width != "" && !is_numeric($geopmap_ual_map_width)){
  $geopmap_invalid_bool = true;
  echo "Addition failed. Invalid map width. Only a numeric value or blank input is allowed.";
}

// If any of the validation checks failed, the remainder of this file will not
// be executed.
if (!$geopmap_invalid_bool){

  // Field assignment. The map's url is set up, verified, and json decoded so that
  // it may be used down the line. If any part of the process fails, invalid_bool
  // is set to true and the process carries on. However, most of the remaining
  // operations here require a false $geopmap_invalid_bool.
  $geopmap_ual_url_in = $geopmap_ual_url . '/api/maps/' . $geopmap_ual_map_id;
  $geopmap_link_scrub = wp_remote_get( ''.$geopmap_ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
  $geopmap_response = wp_remote_retrieve_body( $geopmap_link_scrub );
  if (!empty($geopmap_response))
    $geopmap_result = json_decode($geopmap_response, true);
  else {
    $geopmap_invalid_bool = true;
    echo "Addition failed. Map source could not be contacted.";
  }

  // Invalid map ID check. A faulty map ID will return a generic JSON dataset from
  // GeoPlatform with a statusCode entry containing the "404" code. This will
  // trigger invalid_bool and cause an echo back for user error reporting.
  if (!$geopmap_invalid_bool && array_key_exists('statusCode', $geopmap_result) && $geopmap_result['statusCode'] == "404"){
    $geopmap_invalid_bool = true;
    echo "Addition failed. No map with this ID exists.";
  }

  // Validity and duplication checks.
  if (!$geopmap_invalid_bool){

    // Our custom table is pulled from $wpdb and prepped for iteration.
    $geopmap_table_name = $wpdb->prefix . 'geop_maps_db';
    $geopmap_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopmap_table_name" );

    // Checks the JSON for an AGOL map only attribute and switches the addition
    // to AGOL mode if found.
    if (array_key_exists('resourceTypes', $geopmap_result) && $geopmap_result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap")
      $geopmap_agol = '1';

    // Duplicate check; searches for a map already in the database with the same
    // map ID and cancels the entire addition process if found.
    foreach ($geopmap_retrieved_data as $geopmap_entry){
      if ($geopmap_entry->map_id == $geopmap_ual_map_id){
        echo "Addition failed. Duplicate map found.";
        $geopmap_invalid_bool = true;
        break;
      }
    }
  }

  /* If the map is valid and not a duplicate, final values of the entry are set
   * up and entered into the database table.
  */
  if (!$geopmap_invalid_bool){

    // Basic information setup and blank field instantiation for conditional filling.
    $geopmap_input = !empty($geopmap_ual_map_id) ? $geopmap_ual_map_id : "Empty";
    $geopmap_map_id = $geopmap_input;
    $geopmap_map_url = "";
    $geopmap_map_thumbnail = "";

    // Geomap block, featuring basic data setting from passed array.
    if ($geopmap_agol == '0'){
      $geopmap_map_url = $geopmap_viewer_url . '/?id=' . $geopmap_map_id;
      $geopmap_map_name = $geopmap_result['label'];
      $geopmap_map_description = $geopmap_result['description'];
      $geopmap_map_thumbnail = $geopmap_ual_url . '/api/maps/'. $geopmap_map_id . "/thumbnail";
    }
    else {
      // Agol block, pulling different values. Not all Agol maps have a
      // description, so such is checked for and a generic supplied if necessary.
      // For extra insurance, the same is done for the label/title.
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
      $geopmap_map_thumbnail = $geopmap_ual_url . '/api/maps/'. $geopmap_map_id . "/thumbnail";
    }

    /* The values of ual_map_height and _width are checked if numeric. If so,
     * they are concatenated into the shortcode. If not, the output will use
     * values determined during page load.
     */
    $geopmap_shortcode = "[geopmap id='" . $geopmap_map_id . "' name='" . $geopmap_map_name . "'";
    if (is_numeric($geopmap_ual_map_height))
      $geopmap_shortcode .= " height='" . $geopmap_ual_map_height . "'";
    if (is_numeric($geopmap_ual_map_width))
      $geopmap_shortcode .= " width='" . $geopmap_ual_map_width . "'";
    $geopmap_shortcode .= "]";

    // Cuts off anything from in a map's description after the first new line,
    // which was breaking the addition operation.
    $geopmap_map_description = (explode("\n", $geopmap_map_description))[0];

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
}

?>
