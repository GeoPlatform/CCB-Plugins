<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.imagemattersllc.com
 * @since      1.1.1
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
      <p>
        Input a title:&nbsp
        <input type="text" class="regular-text" id="serve_name_in" value="<?php if(!empty($serve_name)) echo esc_attr($serve_name); ?>"/>
        &nbsp&nbsp&nbsp&nbspResult Count:&nbsp
        <input type="number" class="regular-text" id="serve_count" value="6" min='1' style="width:5em;"/>
      </p>
      <hr>
      <p>
        Choose a standard or compact output format.
        <table>
          <tr>
            <th>
              <input type="radio" name="serve_format_group" class="regular-text" id="serve_format_standard" value="serve_format_standard" checked>Standard&nbsp&nbsp
            </th>
            <th>
              <input type="radio" name="serve_format_group" class="regular-text" id="serve_format_compact" value="serve_format_compact">Compact&nbsp&nbsp
            </th>
          </tr>
        </table>
      </p>
      <p>
        Display title:&nbsp&nbsp
        <input type="checkbox" class="regular-text" id="serve_title_bool" value="serve_title_bool" checked>
      </p>
      <p>
        Display tabs:&nbsp&nbsp
        <input type="checkbox" class="regular-text" id="serve_tabs_bool" value="serve_tabs_bool" checked>
      </p>
      <p>
        Enable pagination:&nbsp&nbsp
        <input type="checkbox" class="regular-text" id="serve_page_bool" value="serve_page_bool">
      </p>
      <p>
        Be advised that if tabs are hidden, only the first item type (as determined by the items to be included in the output) can be displayed.
      </p>
      <hr>
      <p>
        Choose the search bar format.
        <br>
        For "Standard", the search keywords are used to narrow down results provided using the initial criteria.
        <br>
        For "GeoPlatform Search", select the asset types to search for. The search bar will collect criteria and redirect to the GeoPlatform Search interface. If the GeoPlatform Search plugin is not installed, the output will default to "Hidden".
        <br>
        For "Hidden", the search bar will not be shown. Type selection is unimportant.
        <table>
          <tr>
            <th>
              <input type="radio" name="serve_search_group" class="regular-text" id="serve_search_standard" value="serve_search_standard" checked>Standard&nbsp&nbsp
            </th>
            <th>
              <input type="radio" name="serve_search_group" class="regular-text" id="serve_search_geoplatform" value="serve_search_geoplatform">GeoPlatform&nbsp&nbsp
            </th>
            <th>
              <input type="radio" name="serve_search_group" class="regular-text" id="serve_search_hidden" value="serve_search_hidden">Hidden&nbsp&nbsp
            </th>
          </tr>
        </table>
      </p>
      <hr>
      <p>
        Please insert initial display criteria in the proper boxes and check those that you wish to apply to the carousel.
      </p>
      <p>
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_type_community_bool" value="serve_type_community_bool">
              Community&nbsp
            </th>
            <th>
              <input type="text" class="regular-text" id="serve_type_community_text" value=""/>
            </th>
          </tr>
        </table>
      </p>
      <p>
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_type_theme_bool" value="serve_type_theme_bool">
              Theme&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </th>
            <th>
              <input type="text" class="regular-text" id="serve_type_theme_text" value=""/>
            </th>
          </tr>
        </table>
      </p>
      <p>
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_type_title_bool" value="serve_type_title_bool">
              Title/Label&nbsp
            </th>
            <th>
              <input type="text" class="regular-text" id="serve_type_title_text" value=""/>
            </th>
          </tr>
        </table>
      </p>
      <p>
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_type_keyword_bool" value="serve_type_keyword_bool">
              Keyword&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </th>
            <th>
              <input type="text" class="regular-text" id="serve_type_keyword_text" value=""/>
            </th>
          </tr>
        </table>
      </p>
      <p>
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_type_topic_bool" value="serve_type_topic_bool">
              Topic&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </th>
            <th>
              <input type="text" class="regular-text" id="serve_type_topic_text" value=""/>
            </th>
          </tr>
        </table>
      </p>
      <p>
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_type_usedby_bool" value="serve_type_usedby_bool">
              Used By&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            </th>
            <th>
              <input type="text" class="regular-text" id="serve_type_usedby_text" value=""/>
            </th>
          </tr>
        </table>
      </p>
      <p>
        <table>
          <tr>
            <th>
              <input type="checkbox" class="regular-text" id="serve_type_class_bool" value="serve_type_class_bool">
              Classifier&nbsp&nbsp&nbsp&nbsp&nbsp
            </th>
            <th>
              <input type="text" class="regular-text" id="serve_type_class_text" value=""/>
            </th>
          </tr>
        </table>
      </p>
      <hr>
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
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_community" value="serve_cat_community">Community&nbsp&nbsp
            </th>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_app" value="serve_cat_app">Application&nbsp&nbsp
            </th>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_topic" value="serve_cat_topic">Topic&nbsp&nbsp
            </th>
            <th>
              <input type="checkbox" class="regular-text" id="serve_cat_website" value="serve_cat_website">Website&nbsp&nbsp
            </th>
          </tr>
        </table>
      </p>
    </fieldset>

<!-- Add Carousel Button -->
    <hr>
    <input type="submit" id="geopserve_add_action" value="Add Carousel"/>
 <!-- Procedural table creation block.  Here the carousel collection output is
      set. It begins with the header of the table.-->
      <p><strong>Carousel Details Table</strong></p>
        <table class="widefat">
        	<thead>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Title', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Output Format', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Filter Criteria', 'geoplatform-serves' ); ?></th>
        		<th><?php esc_attr_e( 'Output Types', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Search Format', 'geoplatform-serves' ); ?></th>
            <th><?php esc_attr_e( 'Additional Elements', 'geoplatform-serves' ); ?></th>
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
          $geopserve_table_name = $wpdb->prefix . "geop_asset_db";
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
            (substr(($geopserve_entry->serve_cat), 5, 1) == 'T') ? array_push($geopserve_cat_array, 'Communities') : '';
            (substr(($geopserve_entry->serve_cat), 6, 1) == 'T') ? array_push($geopserve_cat_array, 'Applications') : '';
            (substr(($geopserve_entry->serve_cat), 7, 1) == 'T') ? array_push($geopserve_cat_array, 'Topics') : '';
            (substr(($geopserve_entry->serve_cat), 8, 1) == 'T') ? array_push($geopserve_cat_array, 'Websites') : '';
            $geopserve_cat_out = implode(", ", $geopserve_cat_array);

            (is_null($geopserve_entry->serve_source) || empty($geopserve_entry->serve_source)) ? $geopserve_source_out = 'Community' : $geopserve_source_out = ucfirst($geopserve_entry->serve_source);
            ?>
            <tr>
          		<td><?php echo esc_attr($geopserve_entry->serve_title); ?></td>
              <td><?php echo esc_attr($geopserve_entry->serve_name); ?></td>
              <td><?php echo esc_attr($geopserve_cat_out); ?></td>
          		<td><?php echo esc_attr($geopserve_entry->serve_count); ?></td>
              <td><?php echo esc_attr($geopserve_source_out); ?></td>
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
