<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.0.0
 *
 * @package    Geoplatform_Service_Collector
 * @subpackage Geoplatform_Service_Collector/admin/partials
 *
 * Documentation: https://scotch.io/tutorials/how-to-build-a-wordpress-plugin-part-1#adding-custom-input-fields
 *
 */
?>
<html>
<body>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

<!-- global $wpdb for database access. It is followed by page formatting-->
  <?php global $wpdb; ?>
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <form method="post" name="geopserve_options" action="options.php">

<!-- options collection from plugin and data cleanup -->
    <?php
      //Grab all options
      $geopserve_options = get_option($this->plugin_name);
      // Cleanup
      $ual_serve_id = $geopserve_options['ual_serve_id'];

      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);
    ?>

<!-- Label and text field for serve ID, height, and width input. -->
    <fieldset>
      <div style="width:70%;">
        <p>Please enter the ID of the community you wish to pull information from. You will also need to set the title of the categories that will be displayed,  you want to add into your WordPress Community Space. To find the serve ID, use the GeoPlatform Map Manager (<a href="https://serves.geoplatform.gov" target="_blank">serves.geoplatform.gov</a>)
        to search and select the serve. Map IDs can be found as part of the URL in the displayed area shown when "Show Embed" is selected. GeoPlatform Open Map IDs can also be taken from the address bar of serves in
        the GeoPlatform Map Viewer, but this will not work with other serve formats.<BR><BR>
        Height and width inputs will control the serve's dimensions in shortcode form; if left blank, the generated serves will scale to fill the page.
        </p>
      </div>
      <legend class="screen-reader-text"><span><?php _e('Please input a serve ID', $this->plugin_name); ?></span></legend>
      <p>Please input a serve ID:&nbsp
        <input type="text" class="regular-text" id="serve_id_in" name="<?php echo $this->plugin_name; ?>[ual_serve_id]" value="<?php if(!empty(esc_attr($ual_serve_id))) echo esc_attr($ual_serve_id); ?>"/>
        &nbsp&nbsp&nbsp&nbspDesired height:
        <input type="text" class="regular-text" id="serve_height" name="<?php echo $this->plugin_name; ?>[ual_height]" style="width:5em;"/>
        &nbsp&nbsp&nbsp&nbspDesired width:
        <input type="text" class="regular-text" id="serve_width" name="<?php echo $this->plugin_name; ?>[ual_width]" style="width:5em;"/>
      </p>
    </fieldset>

<!-- Add Map Button -->
    <input type="submit" id="geopserve_add_action" value="Add Map"/>

 <!-- Procedural table creation block.  Here the serve collection output is set. It
      begins with the header of the table.-->
      <p><strong>Map Details Table</strong></p>
        <table class="widefat">
        	<thead>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Map ID', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Map Format', 'geoplatform-serves' ); ?></th>
        		<th><?php esc_attr_e( 'Map Name', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Description', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Shortcode', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Controls', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Thumbnail', 'geoplatform-serves' ); ?></th>
        	</tr>
        	</thead>
        	<tbody>

          <?php
          /* The actual table construction. The data is pulled from the database
           * and translated into usable table information. The table is then
           * looped through. Each loop pulls information from a specific table
           * row and uses it to construct a page row.
          */
          $geopserve_table_name = $wpdb->prefix . "geop_serves_db";
          $geopserve_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopserve_table_name" );

          foreach ($geopserve_retrieved_data as $geopserve_entry){
            $geopserve_agolOut = "GeoPlatform Map";
            if ($geopserve_entry->serve_agol == "1")
              $geopserve_agolOut = "AGOL Web Map";
            ?>
            <tr>
          		<td class="row-title"><label for="tablecell"><?php echo esc_attr($geopserve_entry->serve_id); ?></label></td>
              <td><?php echo $geopserve_agolOut; ?></td>
          		<td><?php echo esc_attr($geopserve_entry->serve_name); ?></td>
              <td><?php echo esc_attr($geopserve_entry->serve_description); ?></td>
              <td><code><?php echo esc_attr($geopserve_entry->serve_shortcode); ?></code></td>
              <td>
                <a class="button-secondary" href="<?php echo esc_url($geopserve_entry->serve_url) ?>" title="<?php echo esc_url($geopserve_entry->serve_url) ?>" target="_blank"><?php esc_attr_e( 'View in Map Viewer' ); ?></a>
                <button class="geopserve_indiv_remove_action button-secondary" value="<?php echo $geopserve_entry->serve_id; ?>">Remove Map</button>
              </td>
              <td><a class="embed-responsive embed-responsive-16by9"><img class="embed-responsive-item" src="<?php echo $geopserve_entry->serve_thumbnail; ?>" width="200px" height="112px" alt="The thumbnail for this serve failed to load."/></a></td>
          	</tr><?php
          }?>
        </table>
    </form>
</div>
<?php
