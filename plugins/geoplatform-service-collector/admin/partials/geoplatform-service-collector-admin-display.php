<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.1.0
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
      // $geopserve_options = get_option($this->plugin_name);

      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);
    ?>

<!-- Label and text field for serve ID, height, and width input. -->
    <fieldset>
      <div style="width:70%;">
        <p>
          For each carousel, you will need to provide a title, community ID, desired number of items, and at check at least one type of object to display.
        </p>
      </div>
      <legend class="screen-reader-text"><span><?php _e('Please input a community ID', $this->plugin_name); ?></span></legend>
      <p>Please input a title:&nbsp
        <input type="text" class="regular-text" id="serve_name_in" name="<?php echo $this->plugin_name; ?>[serve_name]" value="<?php if(!empty($serve_name)) echo esc_attr($serve_name); ?>"/>
        &nbsp&nbsp&nbsp&nbspPlease input a community ID:&nbsp
        <input type="text" class="regular-text" id="serve_id_in" name="<?php echo $this->plugin_name; ?>[serve_id]" value="<?php if(!empty($serve_id)) echo esc_attr($serve_id); ?>"/>
        &nbsp&nbsp&nbsp&nbspResult Count:&nbsp
        <input type="number" class="regular-text" id="serve_count" value="6" name="<?php echo $this->plugin_name; ?>[serve_count]" style="width:5em;"/>
      </p>
      <p>Select the items to include in the output
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_data" value="serve_cat_data">Dataset&nbsp&nbsp
            </th>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_serve" value="serve_cat_serve">Service&nbsp&nbsp
            </th>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_layer" value="serve_cat_layer">Layer&nbsp&nbsp
            </th>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_map" value="serve_cat_map">Map&nbsp&nbsp
            </th>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_gallery" value="serve_cat_gallery">Gallery&nbsp&nbsp
            </th>
          </tr>
        </table>
      </p>
    </fieldset>

<!-- Add Carousel Button -->
    <input type="submit" id="geopserve_add_action" value="Add Carousel"/>

 <!-- Procedural table creation block.  Here the carousel collection output is
      set. It begins with the header of the table.-->
      <p><strong>Carousel Details Table</strong></p>
        <table class="widefat">
        	<thead>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Title', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Community', 'geoplatform-serves' ); ?></th>
        		<th><?php esc_attr_e( 'Output Types', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Output Count', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Shortcode', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Controls', 'geoplatform-serves' ); ?></th>
        	</tr>
        	</thead>
        	<tbody>

          <?php
          /* The actual table construction. The data is pulled from the database
           * and translated into usable table information. The table is then
           * looped through. Each loop pulls information from a specific table
           * row and uses it to construct a page row.
          */
          $geopserve_table_name = $wpdb->prefix . "geop_serve_db";
          $geopserve_retrieved_data = $wpdb->get_results( "SELECT * FROM $geopserve_table_name" );

          foreach ($geopserve_retrieved_data as $geopserve_entry){

            // Most data can be pulled straight from the database for use. Cats
            // however are translated into a string of values for output.
            $geopserve_cat_array = array();
            (substr(($geopserve_entry->serve_cat), 0, 1) == 'T') ? array_push($geopserve_cat_array, 'Datasets') : '';
            (substr(($geopserve_entry->serve_cat), 1, 1) == 'T') ? array_push($geopserve_cat_array, 'Services') : '';
            (substr(($geopserve_entry->serve_cat), 2, 1) == 'T') ? array_push($geopserve_cat_array, 'Layers') : '';
            (substr(($geopserve_entry->serve_cat), 3, 1) == 'T') ? array_push($geopserve_cat_array, 'Maps') : '';
            (substr(($geopserve_entry->serve_cat), 4, 1) == 'T') ? array_push($geopserve_cat_array, 'Galleries') : '';
            $geopserve_cat_out = implode(", ", $geopserve_cat_array);
            ?>
            <tr>
          		<td><?php echo esc_attr($geopserve_entry->serve_title); ?></td>
              <td><?php echo esc_attr($geopserve_entry->serve_name); ?></td>
              <td><?php echo esc_attr($geopserve_cat_out); ?></td>
          		<td><?php echo esc_attr($geopserve_entry->serve_count); ?></td>
              <td><code><?php echo esc_attr($geopserve_entry->serve_shortcode); ?></code></td>
              <td>
                <button class="geopserve_indiv_car_remove_action button-secondary" value="<?php echo $geopserve_entry->serve_num; ?>">Remove Carousel</button>
              </td>
          	</tr><?php
          }?>
        </table>
    </form>
</div>
<?php
