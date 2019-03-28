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
    <p>Your GeoPlatform Items Manager has been created. You can find it at "{base site name}/resources/{resource type}/{resource id}".<br><br>
      If something has occured that renders the page inoperable, press the Recreate Items Interface button below.<br><br>
      If there has been an update to the rewrite rules for any reason, they can be applied by flushing the rules with the button below.<br>
      It should be noted that if no changes have been made to rewrite rules, flushing them will have no effect.
    </p>
    <button class="button-secondary" id="geopitems_reset">Recreate Items Interface</button>
    &nbsp&nbsp
    <button class="button-secondary" id="geopitems_flush">Flush Rewrite Rules</button>
  </div>
</div>
</body>
</html>
