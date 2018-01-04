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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

  <?php global $wpdb; ?>

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" name="map_options" action="options.php">

      <?php
       //Grab all options
       $options = get_option($this->plugin_name);
//       var_dump($options);
       // Cleanup
       $ual_map_id = $options['ual_map_id'];
//       $ual_map_id = "62c29fe8103c713904d23b8354ba41c8";
//       $map_env = $options['map_env'];
//       $map_env_select = isset($options['map_env_select']) ? $options['map_env_select'] : "";
       //$map_env_select = $options['map_env_select'];
   ?>

   <?php
       settings_fields($this->plugin_name);
       do_settings_sections($this->plugin_name);
   ?>

        <fieldset>
            <p>Please Provide the ID of the map you created from <a href="https://maps.geoplatform.gov">Maps.GeoPlatform.gov</a> to be embedded into your Wordpress site.</p>
            <legend class="screen-reader-text"><span><?php _e('Please input a map ID', $this->plugin_name); ?></span></legend>
            <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-ual_map_id" name="<?php echo $this->plugin_name; ?>[ual_map_id]" value="<?php if(!empty($ual_map_id)) echo $ual_map_id; ?>"/>
        </fieldset>

        <!-- Add Map button, basically a copied and pasted clone of the Save All
        Changes button at form's bottom. -->
        <!-- <button onclick="add_map()">Add Map</button> -->
        <input type="button" onclick="add_map()" value="Add Map" name="add_map">

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
      /**
       * Map handler from ual
       *
       * @link       www.geoplatform.gov
       * @since      1.0.0
       *
       *Documentation:
       */
        if (isset($ual_map_id)) {
          //build UAL call
          $ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $ual_map_id;
          $link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
          $response = wp_remote_retrieve_body( $link_scrub );
          //call UAL
          //pull data from UAL call
          if(!empty($response)){
            $result = json_decode($response, true);
          }else{
            $result = "This Gallery has no recent activity. Try adding some maps!";
          }

          //var_dump($result);
          //build variables from UAL Call data
          // $map_id = $ual_map_id;
          // $map_name = $result['label'];
          // $map_description = $result['description'];
          // $map_shortcode = "[geopmap id='" . $map_id . "' name='" . $map_name . "']";
          // $map_url = 'https://sit-viewer.geoplatform.us/' . '/?id=' . $map_id;
          // $map_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";

         add_map($ual_map_id, $result);

          // Temp addition code, to be moved elsewhere.
          // $table_name = $wpdb->prefix . 'newsmap_db';
          // $input = !empty($ual_map_id) ? $ual_map_id : "";
          //
          // $wpdb->insert($table_name,
          //   array(
          //     'map_id' => $map_id,
          //     'map_name' => $map_name,
          //     'map_description' => $map_description,
          //     'map_shortcode' => $map_shortcode,
          //     'map_url' => $map_url,
          //     'map_thumbnail' => $map_thumbnail,
          //   )
          // );

          //build shortcode from data
        }
        else{
          echo"Please provide a Map ID";
        }

      ?>



      <p><strong>Map details table</strong></p>
        <table class="widefat">
        	<thead>
        	<tr>
        		<th class="row-title"><?php esc_attr_e( 'Map ID', 'geop-maps' ); ?></th>
        		<th><?php esc_attr_e( 'Map Name', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Description', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'Shortcode', 'geop-maps' ); ?></th>
            <th><?php esc_attr_e( 'URL', 'geop-maps' ); ?></th>
            <th>Remove from List</th>
            <th><?php esc_attr_e( 'Thumbnail', 'geop-maps' ); ?></th>
        	</tr>
        	</thead>
        	<tbody>
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

          /* Pulls the entries from the database and cycles through them, giving
           * output from them in proper formatting.
          */
          $table_name = $wpdb->prefix . "newsmap_db";
          $retrieved_data = $wpdb->get_results( "SELECT * FROM $table_name" );
          $iter = 0;

          foreach ($retrieved_data as $entry){?>
            <tr>
          		<td class="row-title"><label for="tablecell"><?php echo $entry->map_id; ?></label></td>
          		<td><?php echo $entry->map_name; ?></td>
              <td><?php echo $entry->map_description; ?></td>
              <?php $temp_short = $entry->map_shortcode;?>
              <td><code><?php echo $entry->map_shortcode; ?></code></td>
              <td><a class="button-secondary" href="<?php echo $entry->map_url ?>" title="<?php echo $entry->map_url?>"><?php esc_attr_e( 'View in Map Viewer' ); ?></a></td>
              <td><input type="submit" name="geop_remove_action" value="Remove Map"></td>
              <td><a class="embed-responsive embed-responsive-16by9"><img class="embed-responsive-item" src="<?php echo $entry->map_thumbnail; ?>" alt=""></a></td>
              <input type="hidden" name="geop_remove_id" value="geop_remove_row_<?php echo $iter; ?>"/>
              <?php $iter++; ?>
          	</tr><?php
          }?>

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

        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>

</div>
<?php

function add_map($param_id, $param_result){
  global $wpdb;
  $table_name = $wpdb->prefix . 'newsmap_db';

  $input = !empty($param_id) ? $param_id : "Empty";
  $map_id = $input;
  $map_name = $param_result['label'];
  $map_description = $param_result['description'];
  $map_shortcode = "[geopmap id='" . $map_id . "' name='" . $map_name . "']";
  $map_url = 'https://sit-viewer.geoplatform.us/' . '/?id=' . $map_id;
  $map_thumbnail = 'https://sit-ual.geoplatform.us/api/maps/'. $map_id . "/thumbnail";

  $wpdb->insert($table_name,
    array(
      'map_id' => $map_id,
      'map_name' => $map_name,
      'map_description' => $map_description,
      'map_shortcode' => $map_shortcode,
      'map_url' => $map_url,
      'map_thumbnail' => $map_thumbnail,
    )
  );
}



function remove_map($param_id, $param_result){

}?>
