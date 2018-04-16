<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.geoplatform.gov
 * @since      1.0.0
 *
 * @package    Geop_Galleries
 * @subpackage Geop_Galleries/admin/partials
 *
 *Documentation: https://scotch.io/tutorials/how-to-build-a-wordpress-plugin-part-1#adding-custom-input-fields
 *
 */
?>+

<html>
<body>

<!-- This upper area is dedicated to AJAX handling. It is here that the Add and
     Remove buttons have their triggers detected, values pulled, and thrown at
     their respective executing classes.-->
<script>
  /* This is the document ready jQuery block, which contains the button press
   * detectors for the add and remove map buttons. With add, it collects the
   * necessary information from the input boxes and calls the addition AJAX
   * method below.
  */
  jQuery(document).ready(function() {
    jQuery("#geop_add_action").click(function(e){
      var map_id = jQuery("#map_id_in").val();
      var map_height = jQuery("#map_height").val();
      var map_width = jQuery("#map_width").val();
      add_map_ajax(map_id, map_height, map_width);

      e.preventDefault();
    });

    /* The remove button handler, which functions on class due to the procedural
     * nature of the remove buttons being evoked. Grabs the value of the pressed
     * button, which is the map ID, and passes it to the remove AJAX method.
    */
    jQuery(".geop_indiv_remove_action").click(function(e){
      var map_id = jQuery(this).val();
      remove_map_ajax(map_id);

      e.preventDefault();
    });
  });

  /* This is the actual AJAX call. It gathers the data for passing to the function,
   * then, within a jQuery.ajax() call, passes the necessary parameters along with
   * console error reporting actions and a force page reload. It also checks for
   * any data echoed back from the add file, indicating an error, and sends it
   * out as an alert to the user.
  */
  // Use "http://" for localhost use, "https://" for outside of localhost.
  function add_map_ajax(map_id, map_height, map_width){
      var map_data = {
          mapID: map_id,
          mapHeight: map_height,
          mapWidth: map_width
      };

      jQuery.ajax({
          url: "https://" + window.location.hostname + "/wp-content/plugins/geop-maps/admin/partials/geop-maps-admin-add-map.php", //whatever the URL you need to access your php function
          type:"POST",
          dataType:"json",
          data: map_data,
          success:function(return_data){
            if (return_data)
              alert(return_data.status);
            location.reload();
          },
          error:function(return_data){
            if (return_data)
              alert(return_data.status);
            location.reload();
          }
      });
  }

  /* This is the AJAX call for the Remove button. It works exactly like the Add
   * Button's but with a different file evocation and only one argument.
  */
  function remove_map_ajax(map_id){
      var map_data = {
          mapID: map_id
      };

      jQuery.ajax({
          url: "https://" + window.location.hostname + "/wp-content/plugins/geop-maps/admin/partials/geop-maps-admin-remove-map.php", //whatever the URL you need to access your php function
          type:"POST",
          dataType:"json",
          data: map_data,
          success:function(return_data){
            if (return_data)
              alert(return_data.status);
            location.reload();
          },
          error:function(return_data){
            if (return_data)
              alert(return_data.status);
            location.reload();
          }
      });
  }
</script>



<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

<!-- global $wpdb for database access. It is followed by page formatting-->
  <?php global $wpdb; ?>
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <form method="post" name="map_options" action="options.php">

<!-- options collection from plugin and data cleanup -->
    <?php
      //Grab all options
      $options = get_option($this->plugin_name);
      // Cleanup
      $ual_map_id = $options['ual_map_id'];

      settings_fields($this->plugin_name);
      do_settings_sections($this->plugin_name);
    ?>

<!-- Label and text field for map ID, height, and width input. -->
    <fieldset>
      <div style="width:70%;">
        <p>Please enter the ID of the map you want to add into your WordPress Community Space. To find the map ID, use the GeoPlatform Map Manager (<a href="https://maps.geoplatform.gov">maps.geoplatform.gov</a>)
        to search and select the map. Map IDs can be found as part of the URL in the displayed area shown when "Show Embed" is selected. GeoPlatform Open Map IDs can also be taken from the address bar of maps in
        the GeoPlatform Map Viewer, but this will not work with other map formats.<BR><BR>
        Height and width inputs will control the map's dimensions in shortcode form; if left blank, the generated maps will scale to fill the page.
        </p>
      </div>
      <legend class="screen-reader-text"><span><?php _e('Please input a map ID', $this->plugin_name); ?></span></legend>
      <p>Please input a map ID:&nbsp
        <input type="text" class="regular-text" id="map_id_in" name="<?php echo $this->plugin_name; ?>[ual_map_id]" value="<?php if(!empty($ual_map_id)) echo $ual_map_id; ?>"/>
        &nbsp&nbsp&nbsp&nbspDesired height:
        <input type="text" class="regular-text" id="map_height" name="<?php echo $this->plugin_name; ?>[ual_height]" style="width:5em;"/>
        &nbsp&nbsp&nbsp&nbspDesired width:
        <input type="text" class="regular-text" id="map_width" name="<?php echo $this->plugin_name; ?>[ual_width]" style="width:5em;"/>
      </p>
    </fieldset>


<!-- Add Map Button -->
    <input type="button" id="geop_add_action" value="Add Map"></input>


 <!-- Procedural table creation block.  Here the map collection output is set. It
      begins with the header of the table.-->
      <p><strong>Map Details Table</strong></p>
        <table class="widefat">
        	<thead>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Map ID', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Map Format', 'geop-maps' ); ?></th>
        		<th><?php esc_attr_e( 'Map Name', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Description', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Shortcode', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Controls', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Thumbnail', 'geop-maps' ); ?></th>
        	</tr>
        	</thead>
        	<tbody>

          <?php
          /* The actual table construction. The data is pulled from the database
           * and translated into usable table information. The table is then
           * looped through. Each loop pulls information from a specific table
           * row and uses it to construct a page row.
          */
          $table_name = $wpdb->prefix . "newsmap_db";
          $retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );

          foreach ($retrieved_data as $entry){
            $agolOut = "GeoPlatform Map";
            if ($entry->map_agol == "1")
              $agolOut = "AGOL Web Map";
            ?>
            <tr>
          		<td class="row-title"><label for="tablecell"><?php echo $entry->map_id; ?></label></td>
              <td><?php echo $agolOut; ?></td>
          		<td><?php echo $entry->map_name; ?></td>
              <td><?php echo $entry->map_description; ?></td>
              <?php $temp_short = $entry->map_shortcode;?>
              <td><code><?php echo $entry->map_shortcode; ?></code></td>
              <td>
                <a class="button-secondary" href="<?php echo $entry->map_url ?>" title="<?php echo $entry->map_url?>" target="_blank"><?php esc_attr_e( 'View in Map Viewer' ); ?></a>
                <button class="geop_indiv_remove_action button-secondary" value="<?php echo $entry->map_id; ?>">Remove Map</button>
              </td>
              <td><a class="embed-responsive embed-responsive-16by9"><img class="embed-responsive-item" src="<?php echo $entry->map_thumbnail; ?>" width="200px" height="112px" alt="The thumbnail for this map failed to load." onerror="geop_thumb_error(this);"/></a></td>
          	</tr><?php
          }?>
        </table>
    </form>
</div>

<script>
  // If the map is valid but for some reason does not possess a thumbnail, this
  // method is called and will supply a local default borrowed from the sit Map
  // Viewer site.
  function geop_thumb_error(geop_image_in){
    geop_image_in.onerror = "";
    geop_image_in.src = "/wp-content/plugins/geop-maps/includes/img-404.png";
    return true;
  }
</script>

<?php
