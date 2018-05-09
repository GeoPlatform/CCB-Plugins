<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.geoplatform.gov
 * @since             1.0.0
 * @package           Test_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Test_Plugin
 * Plugin URI:        www.geoplatform.gov
 * Description:       Do stuff.
 * Version:           1.0.5
 * Author:            Image Matters LLC
 * Author URI:        www.imagemattersllc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geop-maps
 * Domain Path:       /languages
 */

function aad_admin_page(){
  global $aad_settings;
  $aad_settings = add_options_page(__('Admin Ajax Demo', 'aa'), __('Admin Ajax', 'aad'), 'manage_options', 'admin-ajax-demo', 'aad_render_admin');
}

add_action('admin_menu', 'aad_admin_page');

function aad_render_admin() {
  ?>

    <div class="wrap">
      <h2>Admin Ajax Demo</h2>
      <form id="aad-form" action="" method="POST">
        <div>
          <input id="aad-button" class="button-primary" type="submit" name="aad_submit" value="<?php _e('Get Results', 'aad'); ?>"/>
        </div>
      </form>
    </div>

  <?php
}

function aad_load_scripts($hook) {
  global $aad_settings;

  if ($hook != $aad_settings)
    return;

  wp_enqueue_script('aad-ajax', plugin_dir_url(__FILE__) . 'js/aad_ajax.js', array('jquery'));
}

add_action('admin_enqueue_scripts', 'aad_load_scripts');

function aad_process_ajax() {
  echo "This is my response";
  die();
}

add_action('wp_ajax_aad_get_results', 'aad_process_ajax');

?>
