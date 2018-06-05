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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<html>
<body>
  <div class="wrap">
  <h2 class="geopsearch_admin_title"><?php echo esc_html(get_admin_page_title()); ?></h2>
  <div style="width:70%;">
    <p>Your GeoPlatform Search Manager has been created and can be accessed via the Visit Search Interface button below.
      Alternatively, if something has occured that renders the page inoperable, press the Recreate Search Interface button below.
    </p>
    <a href="<?php echo esc_url( get_permalink( get_page_by_title( 'GeoPlatform Search' ))); ?>" target="_blank">
      <input class="button-secondary" type="submit" value="Visit Search Interface" />
    </a>
    <button class="button-secondary" id="geopsearch_reset">Recreate Search Interface</button>
  </div>

  <script>
  jQuery('document').ready(function(){
    jQuery('#geopsearch_reset').click(function(){
      <?php
      if (get_post_status(3333)){
        $interface_post = array(
          'post_title' => 'GeoPlatform Search',
          'post_name' => 'geoplatform_search',
          'post_content' => '[geopsearch_page]',
          'post_status' => 'publish',
          'post_type' => 'page',
          'ID' => 3333
        );
      }
      wp_insert_post($interface_post);
      ?>
    });
  })
  </script>
</div>

</body>
</html>
