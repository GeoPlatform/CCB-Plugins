<?php


$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require( $parse_uri[0] . 'wp-load.php' );
global $wpdb;
$ual_map_id = $_POST["mapID"];

$ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
$link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
$response = wp_remote_retrieve_body( $link_scrub );


if(!empty($response)){
  $result = json_decode($response, true);
}else{
  $result = "This Gallery has no recent activity. Try adding some maps!";
}

$table_name = $wpdb->prefix . 'newsmap_db';

$input = !empty($ual_map_id) ? $ual_map_id : "Empty";
$map_id = $input;
$map_name = $result['label'];
$map_description = $result['description'];
$map_shortcode = "[geopmap id='" . $map_id . "' name='" . $map_name . "']";
$map_url = 'https://sit-viewer.geoplatform.us/' . '/?id=' . $map_id;
$map_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";
$map_rand_id = rand();

//echo var_dump($table_name);

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
);?>
