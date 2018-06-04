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
 * @since             1.0.2
 * @package           GP_Search
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Community Search
 * Plugin URI:        www.geoplatform.gov
 * Description:       Search for geoplatform community objects.
 * Version:           1.0.2
 * Author:            Image Matters LLC
 * Author URI:        www.geoplatform.gov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geoplatform-search
 */

define("UAL", "https://ual.geoplatform.gov");
define('GP_SEARCH_DIR', plugin_dir_path(__FILE__));
define('GP_SEARCH_URL', plugin_dir_url(__FILE__));
define('GP_SEARCH_NAME', "GeoPlatform Community Search");
define('GP_SEARCH_VERSION', "1.0.2");

function gpsearch_add_stylesheet() {
  wp_register_style('gpsearch', GP_SEARCH_URL . 'assets/css/geoplatform-search-core.css', array(), false, 'all');
  wp_enqueue_style('gpsearch');
}
add_action( 'wp_print_styles', 'gpsearch_add_stylesheet' );

function gpsearch_add_script() {
  wp_register_script('geoplatform', GP_SEARCH_URL . 'assets/js/geoplatform.js', array(), null);
  wp_enqueue_script('geoplatform');
  wp_register_script('q', 'https://cdnjs.cloudflare.com/ajax/libs/q.js/1.5.1/q.js', array(), null);
  wp_enqueue_script('q');
  wp_register_script('clientapi', GP_SEARCH_URL . 'assets/js/geoplatform.client.min.js', array(), null);
  wp_enqueue_script('clientapi');
}
add_action( 'wp_print_scripts', 'gpsearch_add_script' );

// Hook backbone for shortcode interpretation.
function gp_search_shortcode_creation($atts){
  // generate a new uuid for each instance
 // $uuid = wp_generate_uuid4();
  $uuid = uniqid();
  $uuid = str_replace("-", "", $uuid);
  $pagingVal = 1;
  $searchVal = 1;

  // load the settings - add default values if none exist already
  $options = get_option('gpsearch_settings', array(
    'gpsearch_select_community' => 'any',
    'gpsearch_text_title' => 'Community Items',
    'gpsearch_select_objtype' => 'any',
    'gpsearch_checkbox_show_paging' => 1,
    'gpsearch_checkbox_show_search' => 1,
    'gpsearch_select_sort' => 'modified',
    'gpsearch_select_perpage' => 10));

  // populate via shortcode, using settings api values as defaults
  $a = shortcode_atts(array(
    'community' => $options['gpsearch_select_community'],
    'title' => $options['gpsearch_text_title'],
    'objtype' => $options['gpsearch_select_objtype'],
		'showpaging' => $options['gpsearch_checkbox_show_paging'],
    'showsearch' => $options['gpsearch_checkbox_show_search'],
    'sort' => $options['gpsearch_select_sort'],
    'maxresults' => $options['gpsearch_select_perpage'],
    'uuid' => $uuid
  ), $atts);

  // handle true/false values in shortcode.  is stored in settings api as 1 or 0
  if ($atts !=  null)
  {
    if (array_key_exists('showpaging', $atts) && isset($atts['showpaging'])){
      if ($atts['showpaging'] == 'false' || $atts['showpaging'] == '0' || $atts['showpaging'] == 'f')
        $pagingVal = 0;
    }

    if (array_key_exists('showsearch', $atts) && isset($atts['showsearch'])){
      if ($atts['showsearch'] == 'false' || $atts['showsearch'] == '0' || $atts['showsearch'] == 'f')
        $searchVal = 0;
    }
  }

  ob_start();
  include( GP_SEARCH_DIR . '/includes/geoplatform-search-core.php' );  // inject php
	return ob_get_clean();
}

// Adds the shortcode hook to init.
function gp_search_shortcodes_init()
{
    add_shortcode('gpsearch', 'gp_search_shortcode_creation');
}
add_action('init', 'gp_search_shortcodes_init');


// ADMIN SECTION

// need to add these scripts on the admin side too
function gpsearch_add_admin_script() {
  wp_register_script('geoplatform', GP_SEARCH_URL . 'assets/js/geoplatform.js', array(), null);
  wp_enqueue_script('geoplatform');
  wp_register_script('q', 'https://cdnjs.cloudflare.com/ajax/libs/q.js/1.5.1/q.js', array(), null);
  wp_enqueue_script('q');
  wp_register_script('clientapi', GP_SEARCH_URL . 'assets/js/geoplatform.client.min.js', array(), null);
  wp_enqueue_script('clientapi');
  wp_register_style('gpsearchadmin_css', GP_SEARCH_URL . 'assets/css/geoplatform-search-admin.css', false, 'all');
  wp_enqueue_style('gpsearchadmin_css');
}
add_action( 'admin_enqueue_scripts', 'gpsearch_add_admin_script' );

// add to settings menu
function gp_search_plugin_admin_menu(){
  add_options_page( 'GeoPlatform Search Plugin Settings Page', GP_SEARCH_NAME, 'edit_others_posts', GP_SEARCH_NAME, 'gp_search_plugin_display_admin_page');
}
add_action('admin_menu', 'gp_search_plugin_admin_menu');

// tell it what to display for admin page
function gp_search_plugin_display_admin_page() {
  include( GP_SEARCH_DIR . '/includes/geoplatform-search-admin.php' ); // inject php
}

// settings api setup.  requires callbacks to handling displaying the fields.
add_action('admin_init', 'gpsearch_settings_init');
function gpsearch_settings_init() {
	register_setting( 'gpsearch', 'gpsearch_settings' );
  add_settings_section('gpsearch_section', 'TESTxx', 'gpsearch_settings_section_callback', 'gpsearch' );
	add_settings_field('gpsearch_select_community', __( 'Community:', 'wordpress' ), 'gpsearch_select_community_render', 'gpsearch', 'gpsearch_section' );
  add_settings_field('gpsearch_text_title', __( 'Title:', 'wordpress' ), 'gpsearch_text_title_render', 'gpsearch', 'gpsearch_section' );
  add_settings_field('gpsearch_select_objtype', __( 'Object Type:', 'wordpress' ), 'gpsearch_select_objtype_render', 'gpsearch', 'gpsearch_section' );
  add_settings_field('gpsearch_checkbox_show_paging', __( 'Show Paging Control:', 'wordpress' ), 'gpsearch_checkbox_show_paging_render', 'gpsearch', 'gpsearch_section' );
  add_settings_field('gpsearch_checkbox_show_search', __( 'Show Search Bar:', 'wordpress' ), 'gpsearch_checkbox_show_search_render', 'gpsearch', 'gpsearch_section' );
  add_settings_field('gpsearch_select_sort', __( 'Sort Field:', 'wordpress' ), 'gpsearch_select_sort_render', 'gpsearch', 'gpsearch_section' );
	add_settings_field('gpsearch_select_perpage', __( 'Records Per Page:', 'wordpress' ), 'gpsearch_select_perpage_render', 'gpsearch', 'gpsearch_section' );
}

function gpsearch_settings_section_callback(  ) {	echo __( '', 'wordpress' ); } // don't display anything for settings title
function gpsearch_settings_section_callback2(  ) {	echo __( '', 'wordpress' ); } // don't display anything for settings title

function gpsearch_select_community_render() {
  // loading communities from ual
  $url = UAL . "/api/communities";
  $request = wp_remote_get($url);
  $body = wp_remote_retrieve_body($request);
  $data = json_decode($body);
  $communities = $data->results;

  $options = get_option( 'gpsearch_settings', array( 'gpsearch_select_community' => 'any' ));
  $communityVal = $options['gpsearch_select_community'];
	?>
	<select name='gpsearch_settings[gpsearch_select_community]' class='gp-form-control'>
		<option value='any' <?php selected($communityVal, 'any'); ?>>Any Community</option>
    <?php foreach($communities as $val): ?>
         <option value='<?php echo $val->id ?>' <?php selected($communityVal, $val->id ); ?> ><?php echo $val->label ?></option>
    <?php endforeach; ?>
	</select>
<?php
}

function gpsearch_text_title_render(  ) {
  $options = get_option( 'gpsearch_settings', array( 'gpsearch_text_title' => 'Community Items' ));
  $titleVal = $options['gpsearch_text_title'];
	?>
	<input type='text' class='gp-form-control' name='gpsearch_settings[gpsearch_text_title]' value='<?php echo $titleVal; ?>'>
	<?php
}

function gpsearch_select_objtype_render(  ) {
  $options = get_option( 'gpsearch_settings', array( 'gpsearch_select_objtype' => 'any' ));
  $objtypeVal = $options['gpsearch_select_objtype'];
	?>
	<select name='gpsearch_settings[gpsearch_select_objtype]' class='gp-form-control'>
    <option value='any' <?php selected($objtypeVal, 'any'); ?>>Any Type</option>
    <option value='community' <?php selected($objtypeVal, 'community'); ?>>Community</option>
    <option value='dataset' <?php selected($objtypeVal, 'dataset'); ?>>Dataset</option>
    <option value='gallery' <?php selected($objtypeVal, 'gallery'); ?>>Gallery</option>
    <option value='layer' <?php selected($objtypeVal, 'layer'); ?>>Layer</option>
    <option value='map' <?php selected($objtypeVal, 'map'); ?>>Map</option>
    <option value='service' <?php selected($objtypeVal, 'service'); ?>>Service</option>
	</select>
<?php
}

function gpsearch_checkbox_show_paging_render(  ) {
  $options = get_option( 'gpsearch_settings', array( 'gpsearch_checkbox_show_paging' => 1 ));
  $pagingVal =  $options['gpsearch_checkbox_show_paging']
	?>
	<input type='checkbox' name='gpsearch_settings[gpsearch_checkbox_show_paging]' <?php checked($pagingVal, 1 ); ?> value='1'>
	<?php
}

function gpsearch_checkbox_show_search_render(  ) {
  $options = get_option( 'gpsearch_settings', array( 'gpsearch_checkbox_show_search' => 1 ));
  $searchVal = $options['gpsearch_checkbox_show_search'];

	?>
	<input type='checkbox' name='gpsearch_settings[gpsearch_checkbox_show_search]' <?php checked( $searchVal, 1 ); ?> value='1'>
	<?php
}

function gpsearch_select_sort_render(  ) {
  $options = get_option( 'gpsearch_settings', array( 'gpsearch_select_sort' => 'modified' ));
  $sortVal = $options['gpsearch_select_sort'];

	?>
	<select name='gpsearch_settings[gpsearch_select_sort]' class='gp-form-control'>
		<option value='label' <?php selected($sortVal, 'label'); ?>>Label (asc)</option>
		<option value='modified' <?php selected($sortVal, 'modified'); ?>>Modified (desc)</option>
	</select>
<?php
}

function gpsearch_select_perpage_render(  ) {
  $options = get_option('gpsearch_settings', array( 'gpsearch_select_perpage' => 10 ));
  $perpageVal = $options['gpsearch_select_perpage'];

  ?>
	<select name='gpsearch_settings[gpsearch_select_perpage]' class='gp-form-control'>
		<option value='5' <?php selected( $perpageVal, 5 ); ?>>5 Items</option>
    <option value='10' <?php selected( $perpageVal, 10 ); ?>>10 Items</option>
    <option value='15' <?php selected( $perpageVal, 15 ); ?>>15 Items</option>
    <option value='20' <?php selected( $perpageVal, 20 ); ?>>20 Items</option>
    <option value='25' <?php selected( $perpageVal, 25 ); ?>>25 Items</option>
	</select>
<?php
}

 ?>
