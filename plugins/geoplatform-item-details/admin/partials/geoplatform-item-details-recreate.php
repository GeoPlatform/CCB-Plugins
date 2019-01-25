<?php
// The only operation this page actually does is delete and recreate the items page.
// Checks first for the specifically-designed GeoPlatform items page template,
// then the full-page template with added hook text. If neither found, creates a
// basic page with the hook text.
wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-items' ))), true);
$geopitems_interface_post = array(
  'post_title' => 'GeoPlatform Items',
  'post_name' => 'geoplatform-items',
  'post_status' => 'publish',
  'post_type' => 'page',
);
if (is_page_template('page-templates/geop_items_page.php'))
  $geopitems_interface_post = array_merge($geopitems_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/geop_items_page.php'));
else if (is_page_template('page-templates/page_full-width.php'))
  $geopitems_interface_post = array_merge($geopitems_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/page_full-width.php'));
else
  $geopitems_interface_post = array_merge($geopitems_interface_post, array('post_content' => '<app-root></app-root>'));
wp_insert_post($geopitems_interface_post);
?>