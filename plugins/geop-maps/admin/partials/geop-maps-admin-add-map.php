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


// Agol-based field assignment. If agol is false, the map's url is set up,
// verified, and json decoded so that it may be used down the line. If any part
// of the process fails, an invalid result is generated. If agol is true, the
// process is similar, using the arcgis base url.
if ($ual_map_agol == 'N'){
  $ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
  $link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
  $response = wp_remote_retrieve_body( $link_scrub );
  if(!empty($response)){
    $result = json_decode($response, true);
  }else{
    $result = "This Gallery has no recent activity. Try adding some maps!";
  }
}
else{
  $ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
  $link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
  $response = wp_remote_retrieve_body( $link_scrub );
  if(!empty($response)){
    $result = json_decode($response, true);
  }else{
    $result = "This Gallery has no recent activity. Try adding some maps!";
  }
}


// Our custom table is pulled from $wpdb and prepped for iteration.
$table_name = $wpdb->prefix . 'newsmap_db';
$retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );


/* Validity and duplication check. Denies duplicate map additions and agol maps
 * with invalid map ids, denying the addition of either.
*/
$duplicate = false;
foreach ($retrieved_data as $entry){
  if ($entry->map_id == $ual_map_id){
    $duplicate = true;
    break;
  }
  // $temp_thumb = "https://geoplatform.maps.arcgis.com/sharing/rest/content/items/". $ual_map_id . "/info/thumbnail/ago_downloaded.png";
  // if ($ual_map_agol == 'Y' && !is_array(getimagesize($temp_thumb))){
  //   $duplicate = true;
  //   break;
  // }
}


/* If the map is valid and not a duplicate, final values of the entry are set up
 * and entered into the database table.
*/
if (!$duplicate){

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
    if (empty($map_description))
      $map_description = "This map does not have a description.";
    $map_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";
  }

  /* The values of ual_map_height and _width are checked if numeric. If so, they
   * are concatenated into the shortcode. If not, the output will use default
   * side values. Agol's value is also added to the shortcode string.
  */
  $map_shortcode = "[geopmap id='" . $map_id . "' name='" . $map_name . "'";
  if (is_numeric($ual_map_height))
    $map_shortcode .= " height='" . $ual_map_height . "'";
  if (is_numeric($ual_map_width))
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
