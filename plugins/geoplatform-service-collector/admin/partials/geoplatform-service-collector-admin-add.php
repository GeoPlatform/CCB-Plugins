<?php
/**
 * Provide an area to run code in charge of adding carousels to the database. This
 * class is called by the Add Carousel button in the display.php class.
 *
 * @link       www.geoplatform.gov
 * @since      1.1.1
 *
 */

// $wpdb is evoked.
global $wpdb;

/* Assigns the variables stored in $_POST while instantiating blank variables
 * for conditional assignment.
*/
$geopserve_title = $_POST["serve_name"];
$geopserve_id = sanitize_key($_POST["serve_id"]);
$geopserve_count = sanitize_key($_POST["serve_count"]);
$geopserve_cat_dat = sanitize_key($_POST["serve_cat_dat"]);
$geopserve_cat_ser = sanitize_key($_POST["serve_cat_ser"]);
$geopserve_cat_lay = sanitize_key($_POST["serve_cat_lay"]);
$geopserve_cat_map = sanitize_key($_POST["serve_cat_map"]);
$geopserve_cat_gal = sanitize_key($_POST["serve_cat_gal"]);
$geopserve_rand = rand(0, 10000000000000);

// Working default variables.
$geopserve_invalid_bool = false;
$geopserve_ual_url_in = '';
$geopserve_link_scrub = '';
$geopserve_response = '';
$geopserve_result = '';
$geopserve_table_name = "";
$geopserve_retrieved_data = "";

// URL variables for resource collection.
$geopserve_ual_url = 'https://ual.geoplatform.gov';

// Data validation. $geopserve_ual_map_id must be a 32-digit HEX value, while the
// height and width inputs must be either numeric or blank. Failure anywhere
// here switches invalid_bool to true.
if (!ctype_xdigit($geopserve_id) || strlen($geopserve_id) != 32){
  $geopserve_invalid_bool = true;
  echo "Addition failed. Invalid community ID format.";
}

// Count validation, which must be at least one.
if (!$geopserve_invalid_bool && $geopserve_count <= 0){
  $geopserve_invalid_bool = true;
  echo "Addition failed. The carousel must have at least one output.";
}

// Category output validation, of which there must be at least one selected.
if (!$geopserve_invalid_bool && $geopserve_cat_dat == 'false' && $geopserve_cat_ser == 'false' && $geopserve_cat_lay == 'false' && $geopserve_cat_map == 'false' && $geopserve_cat_gal == 'false'){
  $geopserve_invalid_bool = true;
  echo "Addition failed. At least one category must be selected for output.";
}

// If any of the validation checks failed, the remainder of this file will not
// be executed.
if (!$geopserve_invalid_bool){

  // Field assignment. The map's url is set up, verified, and json decoded so that
  // it may be used down the line. If any part of the process fails, invalid_bool
  // is set to true and the process carries on. However, most of the remaining
  // operations here require a false $geopserve_invalid_bool.
  $geopserve_ual_url_in = $geopserve_ual_url . '/api/communities/' . $geopserve_id;
  $geopserve_link_scrub = wp_remote_get( ''.$geopserve_ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
  $geopserve_response = wp_remote_retrieve_body( $geopserve_link_scrub );

  if (!empty($geopserve_response))
    $geopserve_result = json_decode($geopserve_response, true);
  else {
    $geopserve_invalid_bool = true;
    echo "Addition failed. Community source could not be contacted.";
  }

  // Invalid map ID check. A faulty map ID will return a generic JSON dataset from
  // GeoPlatform with a statusCode entry containing the "404" code. This will
  // trigger invalid_bool and cause an echo back for user error reporting.
  if (!$geopserve_invalid_bool && array_key_exists('statusCode', $geopserve_result) && $geopserve_result['statusCode'] == "404"){
    $geopserve_invalid_bool = true;
    echo "Addition failed. No community with this ID exists.";
  }

  // Validity and duplication checks.
  if (!$geopserve_invalid_bool){
    // Our custom table is pulled from $wpdb and prepped for iteration.
    $geopserve_table_name = $wpdb->prefix . 'geop_serve_db';
    $geopserve_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopserve_table_name" );
  }

  /* If the map is valid and not a duplicate, final values of the entry are set
   * up and entered into the database table.
  */
  if (!$geopserve_invalid_bool){

    // Blank title handling.
    if (empty($geopserve_title)){
      $geopserve_title = "N/A";
    }

    // Community name handling
    $geopserve_name = $geopserve_result['label'];

    $geopserve_cats = ($geopserve_cat_dat == 'true') ? 'T' : 'F';
    $geopserve_cats .= ($geopserve_cat_ser == 'true') ? 'T' : 'F';
    $geopserve_cats .= ($geopserve_cat_lay == 'true') ? 'T' : 'F';
    $geopserve_cats .= ($geopserve_cat_map == 'true') ? 'T' : 'F';
    $geopserve_cats .= ($geopserve_cat_gal == 'true') ? 'T' : 'F';

    $geopserve_shortcode = "[geopserve ";
    if ($geopserve_title != "N/A")
      $geopserve_shortcode .= "title='" . $geopserve_title . "' ";
    $geopserve_shortcode .= "id='" . $geopserve_id . "' cat='" . $geopserve_cats . "' count='" . $geopserve_count . "']";

    // echo $geopserve_title . "-" . $geopserve_name . "-" . $geopserve_id . "-" . $geopserve_count . "-" . $geopserve_cats . "-" . $geopserve_rand . "-" . $geopserve_shortcode;

    // Finally, the variables are added to the table in key/value pairs.
    $wpdb->insert($geopserve_table_name,
      array(
        'serve_num' => $geopserve_rand,
        'serve_id' => $geopserve_id,
        'serve_name' => $geopserve_name,
        'serve_title' => $geopserve_title,
        'serve_cat' => $geopserve_cats,
        'serve_count' => $geopserve_count,
        'serve_shortcode' => $geopserve_shortcode
      )
    );
  }
}

?>
