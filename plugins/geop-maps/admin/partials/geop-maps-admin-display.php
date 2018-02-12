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


<!-- This upper area is dedicated to AJAX handling. It is here that the Add and
     Remove buttons have their triggers detected, values pulled, and thrown at
     their respective executing classes.-->
<html>
<body>

<script>
  /* This is the document ready jQuery block, which contains the button press
   * detectors for the add and remove map buttons. With either press, it collects
   * the necessary information from the input boxes and calls the appropriate
   * AJAX handling method. It also contains a refresh suppressor that is likely
   * unnecessary.
  */
  jQuery(document).ready(function() {
    jQuery("#geop_add_action").click(function(e){   //Replace #btnSubmit with whatever your selector is. Jquery lets you select based off css classes & IDs (plus many other combinations, but this should be enough for now)
      var map_id = jQuery("#map_id_in").val();  // You'll need to get the map ID somehow. I'm not sure how the web page is setup so it's hard to answer this one for you. This line would get the value out of an input field with the ID "map_id" (which could presumably be a hidden input anywhere on the page)
      var map_height = jQuery("#map_height").val();  // You'll need to get the map ID somehow. I'm not sure how the web page is setup so it's hard to answer this one for you. This line would get the value out of an input field with the ID "map_id" (which could presumably be a hidden input anywhere on the page)
      var map_width = jQuery("#map_width").val();  // You'll need to get the map ID somehow. I'm not sure how the web page is setup so it's hard to answer this one for you. This line would get the value out of an input field with the ID "map_id" (which could presumably be a hidden input anywhere on the page)
      var map_agol = jQuery("#map_agol").val();
      add_map_ajax(map_id, map_height, map_width, map_agol);

      e.preventDefault();
    });

    jQuery("#geop_remove_action").click(function(e){   //Replace #btnSubmit with whatever your selector is. Jquery lets you select based off css classes & IDs (plus many other combinations, but this should be enough for now)
      var map_id = jQuery("#map_id_in").val();  // You'll need to get the map ID somehow. I'm not sure how the web page is setup so it's hard to answer this one for you. This line would get the value out of an input field with the ID "map_id" (which could presumably be a hidden input anywhere on the page)
      remove_map_ajax(map_id);

      e.preventDefault();
    });
  });

  /* This is the actual AJAX call. It gathers the data for passing to the function,
   * then, within a jQuery.ajax() call, passes the necessary parameters along with
   * console error reporting actions and a force page reload.
  */
  function add_map_ajax(map_id, map_height, map_width, map_agol){
      var map_data = {
          mapID: map_id,
          mapHeight: map_height,
          mapWidth: map_width,
          mapAgol: map_agol
      };

      jQuery.ajax({
          url: "http://" + window.location.hostname + "/wp-content/plugins/geop-maps/admin/partials/geop-maps-admin-add-map.php", //whatever the URL you need to access your php function
          type:"POST",
          dataType:"json",
          data: map_data,
          success:function(data){
              console.log("success",data);
              location.reload();
          },
          error:function(data){
              console.log("error",data);
              location.reload();
          }
      });
  }

  /* This is the AJAX call for the Remove button. It works exactly like the Add
   * Button's but with a different file evocation.
  */
  function remove_map_ajax(map_id){
      var map_data = {
          mapID: map_id
      };

      jQuery.ajax({
          url: "http://" + window.location.hostname + "/wp-content/plugins/geop-maps/admin/partials/geop-maps-admin-remove-map.php", //whatever the URL you need to access your php function
          type:"POST",
          dataType:"json",
          data: map_data,
          success:function(data){
              console.log("success",data);
              location.reload();
          },
          error:function(data){
              console.log("error",data);
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
      <p>Please Provide the ID of the map you created from <a href="https://maps.geoplatform.gov">Maps.GeoPlatform.gov</a> to be embedded into your Wordpress site.</p>
        <legend class="screen-reader-text"><span><?php _e('Please input a map ID', $this->plugin_name); ?></span></legend>
        <p>Please input a map ID:
          <input type="text" class="regular-text" id="map_id_in" name="<?php echo $this->plugin_name; ?>[ual_map_id]" value="<?php if(!empty($ual_map_id)) echo $ual_map_id; ?>"/>
          <select name="agolBool" id="map_agol">
            <option value="N">GeoPlatform</option>
            <option value="Y">AGOL Web</option>
          </select>
          &nbsp&nbsp&nbsp&nbspDesired height:
          <input type="text" class="regular-text" id="map_height" name="<?php echo $this->plugin_name; ?>[ual_height]" style="width:5em;"/>
          &nbsp&nbsp&nbsp&nbspDesired width:
          <input type="text" class="regular-text" id="map_width" name="<?php echo $this->plugin_name; ?>[ual_width]" style="width:5em;"/>
        </p>
        <!-- <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-ual_map_id" name="<?php echo $this->plugin_name; ?>[ual_map_id]" value="<?php if(!empty($ual_map_id)) echo $ual_map_id; ?>"/> -->
      </p>
    </fieldset>


<!-- Add and Remove Map Buttons -->
    <input type="button" id="geop_add_action" value="Add Map"></input>
    <input type="button" id="geop_remove_action" value="Remove Map"></input>
    <div id='empty'><?php
      global $wpdb;
      $stringout = "";
      $table_name = $wpdb->prefix . 'newsmap_db';
      $retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );
      $iter = 0;
      foreach ($retrieved_data as $entry){
        $iter++;
      }
      echo $iter;?>
    </div>



<!-- The below options are currently commented out for future implimentation -->
<!-- Map Environment Choice Radio-->
<!-- <fieldset>
    	<legend class="screen-reader-text"><span>Map Environment</span></legend>
      <label title='Systems Integrations Testing'>
      	<input type="radio" name="<?php echo $this->plugin_name; ?>[map_env]" value="sit" <?php checked('sit', $map_env, true); ?> />
      	<span><?php esc_attr_e( 'SIT - Map ID is from https://sit-maps.geoplatform.us', 'geop-maps' ); ?></span>
      </label><br>
      <label title='Staging'>
      	<input type="radio" name="<?php echo $this->plugin_name; ?>[map_env]" value="stg" <?php checked('stg', $map_env, true); ?>/>
      	<span><?php esc_attr_e( 'STG - Map ID is from https://stg-maps.geoplatform.gov', 'geop-maps' ); ?></span>
      </label><br>
      <label title='Production'>
      	<input type="radio" name="<?php echo $this->plugin_name; ?>[map_env]" value="prod" <?php checked('prod', $map_env, true); ?>/>
      	<span><?php esc_attr_e( 'PROD - Map ID is from https://maps.geoplatform.gov', 'geop-maps' ); ?></span>
      </label>
    </fieldset> -->
    <!-- Map Environment Choice dropdown-->
<!-- <fieldset>
      <legend class="screen-reader-text"><span><?php _e('Map Environment', $this->plugin_name);?></span></legend>
        <h4><?php esc_attr_e('Select Map Environment', $this->plugin_name);?></h4>
          <select name="<?php echo $this->plugin_name;?>[map_env]">
            <option value="sit" <?php selected($map_env_select, 'sit', true);?>>SIT (https://sit-maps.geoplatform.us)</option>
            <option value="stg" <?php selected($map_env_select, 'stg', true);?>>STG(https://stg-maps.geoplatform.gov)</option>
            <option value="prod" <?php selected($map_env_select, 'prod', true);?>>Prod(https://maps.geoplatform.gov)</option>
        </select>
    </fieldset> -->



      <?php
      /* This code block is part of the page's options data collection. It has
       * moved to the add-map.php file, but there's still some here for reference.
       */
        // if (isset($ual_map_id)) {
        //   //build UAL call
        //   $ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
        //   $link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
        //   $response = wp_remote_retrieve_body( $link_scrub );
        //   //call UAL
        //   //pull data from UAL call
        //   if(!empty($response)){
        //     $result = json_decode($response, true);
        //   }else{
        //     $result = "This Gallery has no recent activity. Try adding some maps!";
        //   }
        //
        //
        // }
        // else{
        //   echo"Please provide a Map ID";
        // }

      ?>


 <!-- Procedural table creation block.  Here the map collection output is set. It
      begins with the header of the tabel.-->
      <p><strong>Map details table</strong></p>
        <table class="widefat">
        	<thead>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Map ID', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Map Format', 'geop-maps' ); ?></th>
        		<th><?php esc_attr_e( 'Map Name', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Description', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Shortcode', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'URL', 'geop-maps' ); ?></th>
            <!-- <th>Remove from List</th> -->
            <th><?php esc_attr_e( 'Thumbnail', 'geop-maps' ); ?></th>
        	</tr>
        	</thead>
        	<tbody>

      <!-- The code block below is depricated, but remains in case of the potential
          of future reference or use.  -->
        	<!-- <tr>
        		<td class="row-title"><label for="tablecell"><?php esc_attr_e(
        					'$map_ID', 'geop-maps'
        				); ?></label></td>
        		<td><?php esc_attr_e( '$map_name', 'geop-maps' ); ?></td>
            <td><?php esc_attr_e( '$map_description', 'geop-maps' ); ?></td>
            <td><code><?php esc_attr_e( '$map_shortcode', 'geop-maps' ); ?></code></td>
            <td><?php esc_attr_e( '$map_url', 'geop-maps' ); ?></td>
            <td><?php esc_attr_e( '$map_thumbnail', 'geop-maps' ); ?></td>
        	</tr> -->
          <?php

          /* The actual table construction. The data is pulled from the database
           * and translated into a usable table of information. The table is then
           * looped through. Each loop pulls information from a specific table
           * row and uses it to construct a page row.
           *
           * $iter and the remove button are currently not in use.
          */
          $table_name = $wpdb->prefix . "newsmap_db";
          $retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );
          $iter = 0;

          foreach ($retrieved_data as $entry){
            $agolOut = "GeoPlatform Map";
            if ($entry->map_agol == "Y")
              $agolOut = "AGOL Web Map";
            ?>
            <tr>
          		<td class="row-title"><label for="tablecell"><?php echo $entry->map_id; ?></label></td>
              <td><?php echo $agolOut; ?></td>
          		<td><?php echo $entry->map_name; ?></td>
              <td><?php echo $entry->map_description; ?></td>
              <?php $temp_short = $entry->map_shortcode;?>
              <td><code><?php echo $entry->map_shortcode; ?></code></td>
              <td><a class="button-secondary" href="<?php echo $entry->map_url ?>" title="<?php echo $entry->map_url?>"><?php esc_attr_e( 'View in Map Viewer' ); ?></a></td>
              <!-- <td><input type="button" id="final_remove_button" value="Remove Map"></td> -->
              <td><a class="embed-responsive embed-responsive-16by9"><img class="embed-responsive-item" src="<?php echo $entry->map_thumbnail; ?>" alt="Invalid Map"></a></td>
              <?php $iter++; ?>
          	</tr><?php
          }?>

      <!-- You know, I'm not sure off-hand what these are. Look like copies of
          the stuff commented out above. Probably don't need it.-->
          <!-- <tr>
        		<td class="row-title"><label for="tablecell"><?php echo $map_id; ?></label></td>
        		<td><?php echo $map_name; ?></td>
            <td><?php echo $map_description; ?></td>
            <td><code><?php echo $map_shortcode ?></code></td>
            <td><a href="<?php echo $map_url; ?>" target="_blank">View in Map Viewer</a></td>
            <td><a class="embed-responsive embed-responsive-16by9"><img class="embed-responsive-item" src="<?php echo $map_thumbnail; ?>" alt=""></a></td>
        	</tr>
        	</tbody>
        	<tfoot>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Map ID', 'geop-maps' ); ?></th>
        		<th><?php esc_attr_e( 'Map Name', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Description', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Shortcode', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'URL', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Thumbnail', 'geop-maps' ); ?></th>
        	</tr>
        	</tfoot> -->
        </table>

      <!-- Save All button, probibly has no further use. -->
      <!-- <?php submit_button('Save all changes', 'primary','submit', TRUE); ?> -->

    </form>
</div>

<?php
