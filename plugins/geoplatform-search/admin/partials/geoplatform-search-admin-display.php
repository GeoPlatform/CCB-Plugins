<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.imagemattersllc.com/
 * @since      1.0.0
 *
 * @package    Geop_Search
 * @subpackage Geop_Search/admin/partials
 */
?>

<!-- This brief section is the currently simple search admin interface, offering
     the ability to visit or recreate the search page anew. -->
<html>
<body>
  <div class="wrap">
  <h2 class="geopsearch_admin_title"><?php echo esc_html(get_admin_page_title()); ?></h2>
  <div style="width:50%;">
    <p>Your GeoPlatform Search Manager has been created and can be accessed via the Visit Search Interface button below.
      Alternatively, if something has occured that renders the page inoperable, press the Recreate Search Interface button below.
    </p>
    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'geoplatform-search' ))); ?>" target="_blank">
      <input class="button-secondary" type="submit" value="Visit Search Interface">
    </a>&nbsp&nbsp
    <button class="button-secondary" id="geopsearch_reset">Recreate Search Interface</button>
    <p>
      You can also use this plugin to insert a search bar into your posts. Inputing search terms into the bar will direct you to
      the search interface and provide you with your results. This can be done by simply adding <code>[geopsearch]</code> into
      your posts.
    </p>
  </div>

  <script>
// The only operation this page actually does is delete and recreate the search page. The options here
// will likely be expanded upon in the future, but are currently minimal.
  jQuery('document').ready(function(){
    jQuery('#geopsearch_reset').click(function(){
      <?php
      wp_delete_post(url_to_postid( get_permalink( get_page_by_path( 'geoplatform-search' ))), true);
      $geopsearch_interface_post = array(
        'post_title' => 'GeoPlatform Search',
    		'post_name' => 'geoplatform-search',
    		'post_content' => '',
    		'post_status' => 'publish',
    		'post_type' => 'page'
      );
      wp_insert_post($geopsearch_interface_post);
      ?>
      location.reload();
    });
  })
  </script>
</div>
</body>
</html>
