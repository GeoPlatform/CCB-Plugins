<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 * @package    Geop_Maps
 * @subpackage Geop_Maps/admin/partials
 *
 *Documentation: https://scotch.io/tutorials/how-to-build-a-wordpress-plugin-part-1#adding-custom-input-fields
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
  <form method="post" name="geopmap_options" action="options.php">

<!-- options collection from plugin and data cleanup -->
    <?php
      //Grab all options
      $geopmap_options = get_option($this->plugin_name);
      // Cleanup
      $ual_map_id = $geopmap_options['ual_map_id'];

      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);
    ?>

<!-- Label and text field for map ID, height, and width input. -->
    <fieldset>
      <div style="width:70%;">
        <p>Please enter the ID of the map you want to add into your WordPress Community Space. To find the map ID, use the GeoPlatform Map Manager (<a href="https://maps.geoplatform.gov" target="_blank">maps.geoplatform.gov</a>)
        to search and select the map. Map IDs can be found as part of the URL in the displayed area shown when "Show Embed" is selected. GeoPlatform Open Map IDs can also be taken from the address bar of maps in
        the GeoPlatform Map Viewer, but this will not work with other map formats.<BR><BR>
        Height and width inputs will control the map's dimensions in shortcode form; if left blank, the generated maps will scale to fill the page.
        </p>
      </div>
      <legend class="screen-reader-text"><span><?php _e('Please input a map ID', $this->plugin_name); ?></span></legend>
      <p>Please input a map ID:&nbsp
        <input type="text" class="regular-text" id="map_id_in" name="<?php echo $this->plugin_name; ?>[ual_map_id]" value="<?php if(!empty(esc_attr($ual_map_id))) echo esc_attr($ual_map_id); ?>"/>
        &nbsp&nbsp&nbsp&nbspDesired height:
        <input type="text" class="regular-text" id="map_height" name="<?php echo $this->plugin_name; ?>[ual_height]" style="width:5em;"/>
        &nbsp&nbsp&nbsp&nbspDesired width:
        <input type="text" class="regular-text" id="map_width" name="<?php echo $this->plugin_name; ?>[ual_width]" style="width:5em;"/>
      </p>
    </fieldset>

<!-- Add Map Button -->
    <input type="submit" id="geopmap_add_action" value="Add Map"/>

 <!-- Procedural table creation block.  Here the map collection output is set. It
      begins with the header of the table.-->
      <p><strong>Map Details Table</strong></p>
        <table class="widefat">
        	<thead>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Map ID', 'geoplatform-maps' ); ?></th>
            <th><?php esc_attr_e( 'Map Format', 'geoplatform-maps' ); ?></th>
        		<th><?php esc_attr_e( 'Map Name', 'geoplatform-maps' ); ?></th>
            <th><?php esc_attr_e( 'Description', 'geoplatform-maps' ); ?></th>
            <th><?php esc_attr_e( 'Shortcode', 'geoplatform-maps' ); ?></th>
            <th><?php esc_attr_e( 'Controls', 'geoplatform-maps' ); ?></th>
            <th><?php esc_attr_e( 'Thumbnail', 'geoplatform-maps' ); ?></th>
        	</tr>
        	</thead>
        	<tbody>

          <?php
          /* The actual table construction. The data is pulled from the database
           * and translated into usable table information. The table is then
           * looped through. Each loop pulls information from a specific table
           * row and uses it to construct a page row.
          */
          $geopmap_table_name = $wpdb->prefix . "geop_maps_db";
          $geopmap_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopmap_table_name" );

          foreach ($geopmap_retrieved_data as $geopmap_entry){
            $geopmap_agolOut = "GeoPlatform Map";
            if ($geopmap_entry->map_agol == "1")
              $geopmap_agolOut = "AGOL Web Map";
            ?>
            <tr>
          		<td class="row-title"><label for="tablecell"><?php echo esc_attr($geopmap_entry->map_id); ?></label></td>
              <td><?php echo $geopmap_agolOut; ?></td>
          		<td><?php echo esc_attr($geopmap_entry->map_name); ?></td>
              <td><?php echo esc_attr($geopmap_entry->map_description); ?></td>
              <td><code><?php echo esc_attr($geopmap_entry->map_shortcode); ?></code></td>
              <td>
                <a class="button-secondary" href="<?php echo esc_url($geopmap_entry->map_url) ?>" title="<?php echo esc_url($geopmap_entry->map_url) ?>" target="_blank"><?php esc_attr_e( 'View in Map Viewer' ); ?></a>
                <button class="geopmap_indiv_remove_action button-secondary" value="<?php echo $geopmap_entry->map_id; ?>">Remove Map</button>
              </td>
              <td><a class="embed-responsive embed-responsive-16by9"><img class="embed-responsive-item" src="<?php echo $geopmap_entry->map_thumbnail; ?>" width="200px" height="112px" alt="The thumbnail for this map failed to load."/></a></td>
          	</tr><?php
          }?>
        </table>
    </form>
</div>
<?php
