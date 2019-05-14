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
$geopserve_count = sanitize_key($_POST["serve_count"]);

$geopserve_format_standard = sanitize_key($_POST["serve_format_standard"]);
$geopserve_format_compact = sanitize_key($_POST["serve_format_compact"]);

$geopserve_title_bool = sanitize_key($_POST["serve_title_bool"]);
$geopserve_tabs_bool = sanitize_key($_POST["serve_tabs_bool"]);
$geopserve_page_bool = sanitize_key($_POST["serve_page_bool"]);

$geopserve_search_standard = sanitize_key($_POST["serve_search_standard"]);
$geopserve_search_geoplatform = sanitize_key($_POST["serve_search_geoplatform"]);
$geopserve_search_hidden = sanitize_key($_POST["serve_search_hidden"]);

$geopserve_type_community_bool = sanitize_key($_POST["serve_type_community_bool"]);
$geopserve_type_community_text = sanitize_key($_POST["serve_type_community_text"]);
$geopserve_type_theme_bool = sanitize_key($_POST["serve_type_theme_bool"]);
$geopserve_type_theme_text = sanitize_key($_POST["serve_type_theme_text"]);
$geopserve_type_title_bool = sanitize_key($_POST["serve_type_title_bool"]);
$geopserve_type_title_text = sanitize_key($_POST["serve_type_title_text"]);
$geopserve_type_keyword_bool = sanitize_key($_POST["serve_type_keyword_bool"]);
$geopserve_type_keyword_text = sanitize_key($_POST["serve_type_keyword_text"]);
$geopserve_type_topic_bool = sanitize_key($_POST["serve_type_topic_bool"]);
$geopserve_type_topic_text = sanitize_key($_POST["serve_type_topic_text"]);
$geopserve_type_usedby_bool = sanitize_key($_POST["serve_type_usedby_bool"]);
$geopserve_type_usedby_text = sanitize_key($_POST["serve_type_usedby_text"]);
$geopserve_type_class_bool = sanitize_key($_POST["serve_type_class_bool"]);
$geopserve_type_class_text = sanitize_key($_POST["serve_type_class_text"]);

$geopserve_cat_dat = sanitize_key($_POST["serve_cat_dat"]);
$geopserve_cat_ser = sanitize_key($_POST["serve_cat_ser"]);
$geopserve_cat_lay = sanitize_key($_POST["serve_cat_lay"]);
$geopserve_cat_map = sanitize_key($_POST["serve_cat_map"]);
$geopserve_cat_gal = sanitize_key($_POST["serve_cat_gal"]);
$geopserve_cat_com = sanitize_key($_POST["serve_cat_com"]);
$geopserve_cat_app = sanitize_key($_POST["serve_cat_app"]);
$geopserve_cat_top = sanitize_key($_POST["serve_cat_top"]);
$geopserve_cat_web = sanitize_key($_POST["serve_cat_web"]);

// Submission validity variable, turned to false if input error.
$geopserve_valid_bool = true;

// Random value determination.
$geopserve_rand = rand(0, 10000000000000);

// Filter validity checking.
// if (!($geopserve_type_community_bool || $geopserve_type_theme_bool || $geopserve_type_title_bool || $geopserve_type_keyword_bool || $geopserve_type_topic_bool || $geopserve_type_usedby_bool || $geopserve_type_class_bool)){
//   $geopserve_valid_bool = false;
//   echo "Addition failed. No constraints provided.";
// }
if ($geopserve_type_community_bool == 'true' && (empty($geopserve_type_community_text) || !ctype_xdigit($geopserve_type_community_text) || strlen($geopserve_type_community_text) != 32)){
  $geopserve_valid_bool = false;
  echo "Addition failed. Invalid community ID format or empty input.\n";
}
if ($geopserve_type_theme_bool == 'true' && (empty($geopserve_type_theme_text) || !ctype_xdigit($geopserve_type_theme_text) || strlen($geopserve_type_theme_text) != 32)){
  $geopserve_valid_bool = false;
  echo "Addition failed. Invalid ID theme format or empty input.\n";
}
if ($geopserve_type_title_bool == 'true' && empty($geopserve_type_title_text)){
  $geopserve_valid_bool = false;
  echo "Addition failed. Empty input for theme criteria.\n";
}
if ($geopserve_type_keyword_bool == 'true' && empty($geopserve_type_keyword_text)){
  $geopserve_valid_bool = false;
  echo "Addition failed. Empty input for keyword criteria.\n";
}
if ($geopserve_type_topic_bool == 'true' && empty($geopserve_type_topic_text)){
  $geopserve_valid_bool = false;
  echo "Addition failed. Empty input for topic criteria.\n";
}
if ($geopserve_type_usedby_bool == 'true' && empty($geopserve_type_usedby_text)){
  $geopserve_valid_bool = false;
  echo "Addition failed. Empty input for 'used by' criteria.\n";
}
if ($geopserve_type_class_bool == 'true' && empty($geopserve_type_class_text)){
  $geopserve_valid_bool = false;
  echo "Addition failed. Empty input for classifier criteria.\n";
}

// Item tab check, to ensure that at least one is selected.
if ($geopserve_cat_dat  == 'false' && $geopserve_cat_ser  == 'false' && $geopserve_cat_lay == 'false' && $geopserve_cat_map == 'false' && $geopserve_cat_gal == 'false' && $geopserve_cat_com == 'false' && $geopserve_cat_app == 'false' && $geopserve_cat_top == 'false' && $geopserve_cat_web == 'false'){
  $geopserve_valid_bool = false;
  echo "Addition failed. At least one item type must be selected for output.\n";
}

// Count validation, which must be at least one.
if ($geopserve_count <= 0){
  $geopserve_valid_bool = false;
  echo "Addition failed. The carousel must have at least one output.\n";
}

// If any of the validation checks failed, the remainder of this file will not
// be executed.
if ($geopserve_valid_bool == 'true'){

  // Blank title handling.
  if (empty($geopserve_title))
    $geopserve_title = "N/A";

  // Output format determination.
  $geopserve_format_final = 'standard';
  if ($geopserve_format_compact == 'true')
    $geopserve_format_final = 'compact';

  // Setting up the output criteria string.
  $geopserve_criteria_final = ($geopserve_type_community_bool == 'true') ? 'T' : 'F';
  $geopserve_criteria_final .= ($geopserve_type_theme_bool == 'true') ? 'T' : 'F';
  $geopserve_criteria_final .= ($geopserve_type_title_bool == 'true') ? 'T' : 'F';
  $geopserve_criteria_final .= ($geopserve_type_keyword_bool == 'true') ? 'T' : 'F';
  $geopserve_criteria_final .= ($geopserve_type_topic_bool == 'true') ? 'T' : 'F';
  $geopserve_criteria_final .= ($geopserve_type_usedby_bool == 'true') ? 'T' : 'F';
  $geopserve_criteria_final .= ($geopserve_type_class_bool == 'true') ? 'T' : 'F';

  // Setting up the additional aspect string.
  $geopserve_adds_final = ($geopserve_title_bool == 'true') ? 'T' : 'F';
  $geopserve_adds_final .= ($geopserve_tabs_bool == 'true') ? 'T' : 'F';
  $geopserve_adds_final .= ($geopserve_page_bool == 'true') ? 'T' : 'F';

  // Setting up the output tab options.
  $geopserve_cats_final = ($geopserve_cat_dat == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_ser == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_lay == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_map == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_gal == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_com == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_app == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_top == 'true') ? 'T' : 'F';
  $geopserve_cats_final .= ($geopserve_cat_web == 'true') ? 'T' : 'F';

  // Setting up the search bar format.
  $geopserve_search_final = "stand";
  if ($geopserve_search_geoplatform == 'true')
    $geopserve_search_final = "geop";
  elseif ($geopserve_search_hidden == 'true')
    $geopserve_search_final = "hide";

  // Begin shortcode construction. Adds title, count, cats, and adds, which are
  // mandatory for each carousel.
  $geopserve_shortcode_final = "[geopserve title='" . $geopserve_title . "' count='" . $geopserve_count . "' cat='" . $geopserve_cats_final . "' adds='" . $geopserve_adds_final . "'";

  // Compact output styling.
  if ($geopserve_format_final == 'compact')
    $geopserve_shortcode_final .= " form='compact'";

  // Search bar incorporation.
  if ($geopserve_search_final == 'geop')
    $geopserve_shortcode_final .= " search='geop'";
  elseif ($geopserve_search_final == 'hide')
    $geopserve_shortcode_final .= " search='hide'";

  // Filtering criteria incorporation.
  if ($geopserve_type_community_bool == 'true')
    $geopserve_shortcode_final .= " community='" . $geopserve_type_community_text . "'";
  if ($geopserve_type_theme_bool == 'true')
    $geopserve_shortcode_final .= " theme='" . $geopserve_type_theme_text . "'";
  if ($geopserve_type_title_bool == 'true')
    $geopserve_shortcode_final .= " title='" . $geopserve_type_title_text . "'";
  if ($geopserve_type_keyword_bool == 'true')
    $geopserve_shortcode_final .= " keyword='" . $geopserve_type_keyword_text . "'";
  if ($geopserve_type_topic_bool == 'true')
    $geopserve_shortcode_final .= " topic='" . $geopserve_type_topic_text . "'";
  if ($geopserve_type_usedby_bool == 'true')
    $geopserve_shortcode_final .= " usedby='" . $geopserve_type_usedby_text . "'";
  if ($geopserve_type_class_bool == 'true')
    $geopserve_shortcode_final .= " class='" . $geopserve_type_class_text . "'";

  // Closing out the string.
  $geopserve_shortcode_final .= "]";

  // Finally, the variables are added to the table in key/value pairs.
  $geopserve_table_name = $wpdb->prefix . "geop_asset_db";
  $wpdb->insert($geopserve_table_name,
    array(
      'serve_num' => $geopserve_rand,
      'serve_title' => $geopserve_title,
      'serve_format' => $geopserve_format_final,
      'serve_criteria' => $geopserve_criteria_final,
      'serve_adds' => $geopserve_adds_final,
      'serve_cat' => $geopserve_cats_final,
      'serve_search' => $geopserve_search_final,
      'serve_count' => $geopserve_count,
      'serve_shortcode' => $geopserve_shortcode_final
    )
  );
}
