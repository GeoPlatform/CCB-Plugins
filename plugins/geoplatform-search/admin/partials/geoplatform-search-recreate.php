<?php
// The only operation this page actually does is delete and recreate the search page.
// Checks first for the specifically-designed GeoPlatform search page template,
// then the full-page template with added hook text. If neither found, creates a
// basic page with the hook text.
wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-search' ))), true);
$geopsearch_interface_post = array(
  'post_title' => 'Search the GeoPlatform',
  'post_name' => 'geoplatform-search',
  'post_status' => 'publish',
  'post_type' => 'page',
  'post_content' => '<app-root></app-root>',
);

$geopsearch_creation_id = wp_insert_post($geopsearch_interface_post);
update_post_meta($geopsearch_creation_id, 'geopportal_breadcrumb_title', 'Search');
?>
