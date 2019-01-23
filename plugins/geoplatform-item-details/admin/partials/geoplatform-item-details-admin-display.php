<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.0.0
 *
 * @package    Geoplatform_Item_Details
 * @subpackage Geoplatform_Item_Details/admin/partials
 */
?>

<!-- This brief section is the currently simple items admin interface, offering
     the ability to visit or recreate the items page anew. -->
<html>
<body>
  <div class="wrap">
  <h2 class="geopitems_admin_title"><?php echo esc_html(get_admin_page_title()); ?></h2>
  <div style="width:50%;">
    <p>Your GeoPlatform Items Manager has been created and can be accessed via the Visit Items Interface button below.
      Alternatively, if something has occured that renders the page inoperable, press the Recreate Items Interface button below.
    </p>
    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'geoplatform-items' ))); ?>" target="_blank">
      <input class="button-secondary" type="submit" value="Visit Items Interface">
    </a>&nbsp&nbsp
    <button class="button-secondary" id="geopitems_reset">Recreate Items Interface</button>
    <p>
      You can also use this plugin to insert a items bar into your posts. Inputing item terms into the bar will direct you to
      the items interface and provide you with your results. This can be done by simply adding <code>[geopitems]</code> into
      your posts.
    </p>
  </div>
</div>
</body>
</html>
