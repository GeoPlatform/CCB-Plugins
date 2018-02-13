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
$ual_map_agol = $_POST["mapAgol"];
$ual_url = '';
$link_scrub = '';
$response = '';
$result = '';
$invalid_bool = false;


// Field assignment. The map's url is set up, verified, and json decoded so that
// it may be used down the line. If any part of the process fails, invalid_bool
// is set to true and the process carries on. However, most of the remaining
// operations here require a false $invalid_bool.
$ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
$link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
$response = wp_remote_retrieve_body( $link_scrub );
if(!empty($response)){
  $result = json_decode($response, true);
}else{
  $result = "This Gallery has no recent activity. Try adding some maps!";
  $invalid_bool = true;
}


// Our custom table is pulled from $wpdb and prepped for iteration.
$table_name = $wpdb->prefix . 'newsmap_db';
$retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );


/* Validity and duplication check. Checks for Geoplatform maps with an AGOl-only
 * attribute, AGOL maps without that attribute, and duplicates. It will deny all
 * of these.
*/
if (!$invalid_bool){
  if ($ual_map_agol == 'Y' && !$result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap")
    $invalid_bool = true;
  if ($ual_map_agol == 'N' && $result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap")
    $invalid_bool = true;

  // DUPLICATE CHECK, CURRENTLY OUT FOR TESTING. ABSENSE MAY CAUSE ERRORS.
  foreach ($retrieved_data as $entry){
    if ($entry->map_id == $ual_map_id){
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
  $map_agol = $ual_map_agol;
  $map_url = "";
  $map_thumbnail = "";

  // Geomap block, featuring basic data setting from passed array.
  if ($map_agol == 'N'){
    $map_url = 'https://sit-viewer.geoplatform.us/' . '?id=' . $map_id;
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
    $map_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";
  }

  /* The values of ual_map_height and _width are checked if numeric. If so, they
   * are concatenated into the shortcode. If not, the output will use default
   * side values. Agol's value is also added to the shortcode string.
  */
  $map_shortcode = "[geopmap id='" . $map_id . "' name='" . $map_name . "'";
  if (is_numeric($ual_map_height) && $map_agol == 'N')
    $map_shortcode .= " height='" . $ual_map_height . "'";
  if (is_numeric($ual_map_width) && $map_agol == 'N')
    $map_shortcode .= " width='" . $ual_map_width . "'";
  $map_shortcode .= " agol='" . $map_agol . "'";
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
