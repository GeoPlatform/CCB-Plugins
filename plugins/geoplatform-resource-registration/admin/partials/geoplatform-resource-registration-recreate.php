<?php
// The only operation this page actually does is delete and recreate the search page.
// Checks first for the specifically-designed GeoPlatform search page template,
// then the full-page template with added hook text. If neither found, creates a
// basic page with the hook text.
$geopregister_parent_id = url_to_postid( get_permalink( get_page_by_path( 'resources' )));

wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'resources/register' ))), true);
$geopregister_interface_post = array(
  'post_title' => 'Register Your Data with GeoPlatform.gov',
  'post_name' => 'register',
  'post_status' => 'publish',
  'post_type' => 'page',
  'post_parent' => $geopregister_parent_id,
  'post_content' => '<app-root></app-root>',
);

$geopregister_creation_id = wp_insert_post($geopregister_interface_post);
update_post_meta($geopregister_creation_id, 'geopportal_breadcrumb_title', 'Register Data');
?>
