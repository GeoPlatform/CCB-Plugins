<?php
// The only operation this page actually does is delete and recreate the search page.
// Checks first for the specifically-designed GeoPlatform search page template,
// then the full-page template with added hook text. If neither found, creates a
// basic page with the hook text.
$geopregister_parent_id = url_to_postid( get_permalink( get_page_by_path( 'resources' )));

// If there is no 'resources' page, it is made here and its ID passed off for
// the registration page's creation below.
if ($geopregister_parent_id == null){
  $geopregister_parent_post = array(
    'post_title' => 'Resources',
    'post_name' => 'resources',
    'post_status' => 'publish',
    'post_type' => 'page',
  );

  $geopregister_parent_id = wp_insert_post($geopregister_parent_post);
}

// The previous registration page is deleted and the array for the new one made.
wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'resources/register' ))), true);
$geopregister_interface_post = array(
  'post_title' => 'Register Your Data with GeoPlatform.gov',
  'post_name' => 'register',
  'post_status' => 'publish',
  'post_type' => 'page',
  'post_parent' => $geopregister_parent_id,
  'post_content' => '<app-root></app-root>',
);

// The page is created, and the resulting page ID is used to apply a value to
// it's breadcrumb title.
$geopregister_creation_id = wp_insert_post($geopregister_interface_post);
update_post_meta($geopregister_creation_id, 'geopportal_breadcrumb_title', 'Register Data');
?>
