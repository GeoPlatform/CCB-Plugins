<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.0.0
 *
 * @package    Geoplatform_Resource_Registration
 * @subpackage Geoplatform_Resource_Registration/admin/partials
 */
?>

<!-- This brief section is the currently simple search admin interface, offering
     the ability to visit or recreate the search page anew. -->
<html>
<body>
  <div class="wrap">
  <h2 class="geopregister_admin_title"><?php echo esc_html(get_admin_page_title()); ?></h2>
  <div style="width:50%;">
    <p>Your GeoPlatform Resource Registration Manager has been created and can be accessed via the Visit Registration Interface button below.
      Alternatively, if something has occured that renders the page inoperable, press the Recreate Registration Interface button below.
      Please note that in order for navigation and old post deletion to operate properly, there MUST be a page with the slug "resources".
      If there is not, the plugin will create a blank page with that slug.
    </p>
    <a class="button-secondary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'resources/register' ))); ?>" target="_blank">Visit Registration Interface</a>
    &nbsp&nbsp
    <button class="button-secondary" id="geopregister_reset">Recreate Registration Interface</button>
  </div>
</div>
</body>
</html>
