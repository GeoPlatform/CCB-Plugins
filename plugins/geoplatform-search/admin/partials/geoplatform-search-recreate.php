<?php
// The only operation this page actually does is delete and recreate the search page.
// Checks first for the specifically-designed GeoPlatform search page template,
// then the full-page template with added hook text. If neither found, creates a
// basic page with the hook text.
wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-search' ))), true);
$geopsearch_interface_post = array(
  'post_title' => 'GeoPlatform Search',
  'post_name' => 'geoplatform-search',
  'post_status' => 'publish',
  'post_type' => 'page',
);
if (is_page_template('page-templates/geop_search_page.php'))
  $geopsearch_interface_post = array_merge($geopsearch_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/geop_search_page.php'));
else if (is_page_template('page-templates/page_full-width.php'))
  $geopsearch_interface_post = array_merge($geopsearch_interface_post, array('post_content' => '<app-root></app-root>', 'page_template' => 'page-templates/page_full-width.php'));
else
  $geopsearch_interface_post = array_merge($geopsearch_interface_post, array('post_content' => '<app-root></app-root>'));
wp_insert_post($geopsearch_interface_post);
?>
