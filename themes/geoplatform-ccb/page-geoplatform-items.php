<?php
/**
 * Name: GeoPlatform Items Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 * @subpackage geoplatform-portal-four
 * @since 2.0.0
 */
get_header();
$geopccb_id_array = array();
preg_match("/[0-9a-fA-F]{32}/", $_SERVER['REQUEST_URI'], $geopccb_id_array);
if (!empty($geopccb_id_array)){
  $geopccb_id_grab = $geopccb_id_array[0];

  if ($geopccb_id_grab){
    $geopccb_ual_root = geop_ccb_getEnv('ual_url',"https://ual.geoplatform.gov");
    $geopccb_fetch_url = $geopccb_ual_root . "/api/items/" . $geopccb_id_grab . ".jsonld?embedded=true&profile=sdo";

    try {
      $geopccb_json = @file_get_contents($geopccb_fetch_url);

      if ($geopccb_json){
        echo "<script type='application/ld+json'>" . $geopccb_json . "</script>";
      }
    }
    catch (Exception $e){}
  }
}


?>

<?php
if ( have_posts() ) : while ( have_posts() ) : the_post();
  the_content();
endwhile; endif;

get_footer(); ?>
