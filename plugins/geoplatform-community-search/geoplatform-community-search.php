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
 * @since             1.0.7
 * @package           GP_Search
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Community Search
 * Plugin URI:        https://www.geoplatform.gov/about-geoplatform/ctk/geoplatform-community-search
 * Description:       Search for geoplatform community objects.
 * Version:           1.0.7
 * Author:            Image Matters LLC
 * Author URI:        http://www.imagemattersllc.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-community-search
 */

define("GP_SEARCH_UAL", "https://ual.geoplatform.gov");
define('GP_SEARCH_DIR', plugin_dir_path(__FILE__));
define('GP_SEARCH_URL', plugin_dir_url(__FILE__));
define('GP_SEARCH_NAME', "GeoPlatform Community Search");
define('GP_SEARCH_VERSION', "1.0.5");


/**
* Add settings action link to the plugins page.
*
* @since    1.0.0
*/
function plugin_add_settings_link( $links ) {
    $settings_link = array('<a href="options-general.php?page=GeoPlatform+Community+Search">' . __( 'Settings' ) . '</a>');
      return array_merge(  $settings_link, $links );
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_" . $plugin, 'plugin_add_settings_link' );


function geopcomsearch_add_stylesheet() {
  wp_register_style('geopcomsearch', GP_SEARCH_URL . 'assets/css/geoplatform-community-search-core.css', array(), false, 'all');
  wp_enqueue_style('geopcomsearch');
}
add_action( 'wp_print_styles', 'geopcomsearch_add_stylesheet' );

function geopcomsearch_add_script() {
  wp_register_script('geopcomsearch_framework', GP_SEARCH_URL . 'assets/js/geoplatform.js', array(), null);
  wp_enqueue_script('geopcomsearch_framework');
  wp_register_script('geopcomsearch_q', GP_SEARCH_URL . 'assets/js/q_2.0.3.js', array(), null);
  wp_enqueue_script('geopcomsearch_q');
  wp_register_script('geopcomsearch_client_api', GP_SEARCH_URL . 'assets/js/geoplatform.client.min.js', array(), null);
  wp_enqueue_script('geopcomsearch_client_api');
}
add_action( 'wp_print_scripts', 'geopcomsearch_add_script' );

// Hook backbone for shortcode interpretation.
function geopcomsearch__shortcode_creation($geopcomsearch_atts){
  // generate a new geopcomsearch_uuid for each instance
 // $geopcomsearch_uuid = wp_generate_geopcomsearch_uuid4();
  $geopcomsearch_uuid = uniqid();
  $geopcomsearch_uuid = str_replace("-", "", $geopcomsearch_uuid);
  $geopcomsearch_pagingVal = 1;
  $geopcomsearch_searchVal = 1;

  // load the settings - add default values if none exist already
  $geopcomsearch_options = get_option('geopcomsearch_settings', array(
    'geopcomsearch_select_community' => 'any',
    'geopcomsearch_text_title' => 'Community Items',
    'geopcomsearch_select_objtype' => 'any',
    'geopcomsearch_checkbox_show_paging' => 1,
    'geopcomsearch_checkbox_show_search' => 1,
    'geopcomsearch_select_sort' => 'modified',
    'geopcomsearch_text_keyword' => '',
    'geopcomsearch_theme' => 'any',
    'geopcomsearch_select_perpage' => 10));

  // populate via shortcode, using settings api values as defaults
  $geopmap_shortcode_array = shortcode_atts(array(
    'community' => $geopcomsearch_options['geopcomsearch_select_community'],
    'title' => $geopcomsearch_options['geopcomsearch_text_title'],
    'objtype' => $geopcomsearch_options['geopcomsearch_select_objtype'],
		'showpaging' => $geopcomsearch_options['geopcomsearch_checkbox_show_paging'],
    'showsearch' => $geopcomsearch_options['geopcomsearch_checkbox_show_search'],
    'sort' => $geopcomsearch_options['geopcomsearch_select_sort'],
    'maxresults' => $geopcomsearch_options['geopcomsearch_select_perpage'],
    'keyword' => $geopcomsearch_options['geopcomsearch_text_keyword'],
    'theme' => $geopcomsearch_options['geopcomsearch_theme'],
    'geopcomsearch_uuid' => $geopcomsearch_uuid
  ), $geopcomsearch_atts);

  // handle true/false values in shortcode.  is stored in settings api as 1 or 0
  if ($geopcomsearch_atts !=  null)
  {
    if (array_key_exists('showpaging', $geopcomsearch_atts) && isset($geopcomsearch_atts['showpaging'])){
      if ($geopcomsearch_atts['showpaging'] == 'false' || $geopcomsearch_atts['showpaging'] == '0' || $geopcomsearch_atts['showpaging'] == 'f')
        $geopcomsearch_pagingVal = 0;
    }

    if (array_key_exists('showsearch', $geopcomsearch_atts) && isset($geopcomsearch_atts['showsearch'])){
      if ($geopcomsearch_atts['showsearch'] == 'false' || $geopcomsearch_atts['showsearch'] == '0' || $geopcomsearch_atts['showsearch'] == 'f')
        $geopcomsearch_searchVal = 0;
    }
  }

  ob_start();
  include( GP_SEARCH_DIR . '/includes/geoplatform-community-search-core.php' );  // inject php
	return ob_get_clean();
}

// Adds the shortcode hook to init.
function geopcomsearch__shortcodes_init()
{
    add_shortcode('geopcomsearch', 'geopcomsearch__shortcode_creation');
}
add_action('init', 'geopcomsearch__shortcodes_init');


// ADMIN SECTION

// need to add these scripts on the admin side too
function geopcomsearch_add_admin_script() {
  wp_register_script('geopcomsearch_framework', GP_SEARCH_URL . 'assets/js/geoplatform.js', array(), null);
  wp_enqueue_script('geopcomsearch_framework');
  wp_register_script('geopcomsearch_q', GP_SEARCH_URL . 'assets/js/q_2.0.3.js', array(), null);
  wp_enqueue_script('geopcomsearch_q');
  wp_register_script('geopcomsearch_client_api', GP_SEARCH_URL . 'assets/js/geoplatform.client.min.js', array(), null);
  wp_enqueue_script('geopcomsearch_client_api');
  wp_register_style('geopcomsearchadmin_css', GP_SEARCH_URL . 'assets/css/geoplatform-community-search-admin.css', false, 'all');
  wp_enqueue_style('geopcomsearchadmin_css');
}
add_action( 'admin_enqueue_scripts', 'geopcomsearch_add_admin_script' );

// add to settings menu
function geopcomsearch__plugin_admin_menu(){
  add_options_page( 'GeoPlatform Community Search Plugin Settings Page', GP_SEARCH_NAME, 'edit_others_posts', GP_SEARCH_NAME, 'geopcomsearch__plugin_display_admin_page');
}
add_action('admin_menu', 'geopcomsearch__plugin_admin_menu');

// tell it what to display for admin page
function geopcomsearch__plugin_display_admin_page() {
  include( GP_SEARCH_DIR . '/includes/geoplatform-community-search-admin.php' ); // inject php
}

// settings api setup.  requires callbacks to handling displaying the fields.
add_action('admin_init', 'geopcomsearch_settings_init');
function geopcomsearch_settings_init() {
	register_setting( 'geopcomsearch', 'geopcomsearch_settings' );
  add_settings_section('geopcomsearch_section', 'TESTxx', 'geopcomsearch_settings_section_callback', 'geopcomsearch' );
	add_settings_field('geopcomsearch_select_community', __( 'Community:', 'wordpress' ), 'geopcomsearch_select_community_render', 'geopcomsearch', 'geopcomsearch_section' );
  add_settings_field('geopcomsearch_text_title', __( 'Title:', 'wordpress' ), 'geopcomsearch_text_title_render', 'geopcomsearch', 'geopcomsearch_section' );
  add_settings_field('geopcomsearch_select_objtype', __( 'Object Type:', 'wordpress' ), 'geopcomsearch_select_objtype_render', 'geopcomsearch', 'geopcomsearch_section' );
  add_settings_field('geopcomsearch_checkbox_show_paging', __( 'Show Paging Control:', 'wordpress' ), 'geopcomsearch_checkbox_show_paging_render', 'geopcomsearch', 'geopcomsearch_section' );
  add_settings_field('geopcomsearch_checkbox_show_search', __( 'Show Search Bar:', 'wordpress' ), 'geopcomsearch_checkbox_show_search_render', 'geopcomsearch', 'geopcomsearch_section' );
  add_settings_field('geopcomsearch_select_sort', __( 'Sort Field:', 'wordpress' ), 'geopcomsearch_select_sort_render', 'geopcomsearch', 'geopcomsearch_section' );
	add_settings_field('geopcomsearch_select_perpage', __( 'Records Per Page:', 'wordpress' ), 'geopcomsearch_select_perpage_render', 'geopcomsearch', 'geopcomsearch_section' );
  add_settings_field('geopcomsearch_text_keyword', __( 'Default Keywords:', 'wordpress' ), 'geopcomsearch_text_keyword_render', 'geopcomsearch', 'geopcomsearch_section' );
  add_settings_field('geopcomsearch_theme', __( 'Default Themes:', 'wordpress' ), 'geopcomsearch_theme_render', 'geopcomsearch', 'geopcomsearch_section' );
}

function geopcomsearch_settings_section_callback(  ) {	echo __( '', 'wordpress' ); } // don't display anything for settings title
function geopcomsearch_settings_section_callback2(  ) {	echo __( '', 'wordpress' ); } // don't display anything for settings title

function geopcomsearch_select_community_render() {
  // loading communities from ual
  $geopcomsearch_url = GP_SEARCH_UAL . "/api/communities";
  $geopcomsearch_request = wp_remote_get($geopcomsearch_url);
  $geopcomsearch_body = wp_remote_retrieve_body($geopcomsearch_request);
  $geopcomsearch_data = json_decode($geopcomsearch_body);
  $geopcomsearch_communities = $geopcomsearch_data->results;

  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_select_community' => 'any' ));
  $geopcomsearch_communityVal = $geopcomsearch_options['geopcomsearch_select_community'];
	?>
	<select name='geopcomsearch_settings[geopcomsearch_select_community]' class='gp-form-control'>
		<option value='any' <?php selected($geopcomsearch_communityVal, 'any'); ?>>Any Community</option>
    <?php foreach($geopcomsearch_communities as $val): ?>
         <option value='<?php echo $val->id ?>' <?php selected($geopcomsearch_communityVal, $val->id ); ?> ><?php echo $val->label ?></option>
    <?php endforeach; ?>
	</select>
<?php
}

function geopcomsearch_text_title_render(  ) {
  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_text_title' => 'Community Items' ));
  $geopcomsearch_titleVal = $geopcomsearch_options['geopcomsearch_text_title'];
	?>
	<input type='text' class='gp-form-control' name='geopcomsearch_settings[geopcomsearch_text_title]' value='<?php echo $geopcomsearch_titleVal; ?>'>
	<?php
}

function geopcomsearch_select_objtype_render(  ) {
  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_select_objtype' => 'any' ));
  $geopcomsearch_objtypeVal = $geopcomsearch_options['geopcomsearch_select_objtype'];
	?>
	<select name='geopcomsearch_settings[geopcomsearch_select_objtype]' class='gp-form-control'>
    <option value='any' <?php selected($geopcomsearch_objtypeVal, 'any'); ?>>Any Type</option>
    <option value='community' <?php selected($geopcomsearch_objtypeVal, 'community'); ?>>Community</option>
    <option value='dataset' <?php selected($geopcomsearch_objtypeVal, 'dataset'); ?>>Dataset</option>
    <option value='gallery' <?php selected($geopcomsearch_objtypeVal, 'gallery'); ?>>Gallery</option>
    <option value='layer' <?php selected($geopcomsearch_objtypeVal, 'layer'); ?>>Layer</option>
    <option value='map' <?php selected($geopcomsearch_objtypeVal, 'map'); ?>>Map</option>
    <option value='service' <?php selected($geopcomsearch_objtypeVal, 'service'); ?>>Service</option>
	</select>
<?php
}

function geopcomsearch_checkbox_show_paging_render(  ) {
  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_checkbox_show_paging' => 1 ));
  $geopcomsearch_pagingVal =  $geopcomsearch_options['geopcomsearch_checkbox_show_paging']
	?>
	<input type='checkbox' name='geopcomsearch_settings[geopcomsearch_checkbox_show_paging]' <?php checked($geopcomsearch_pagingVal, 1 ); ?> value='1'>
	<?php
}

function geopcomsearch_checkbox_show_search_render(  ) {
  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_checkbox_show_search' => 1 ));
  $geopcomsearch_searchVal = $geopcomsearch_options['geopcomsearch_checkbox_show_search'];

	?>
	<input type='checkbox' name='geopcomsearch_settings[geopcomsearch_checkbox_show_search]' <?php checked( $geopcomsearch_searchVal, 1 ); ?> value='1'>
	<?php
}

function geopcomsearch_select_sort_render(  ) {
  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_select_sort' => 'modified' ));
  $geopcomsearch_sortVal = $geopcomsearch_options['geopcomsearch_select_sort'];

	?>
	<select name='geopcomsearch_settings[geopcomsearch_select_sort]' class='gp-form-control'>
		<option value='label' <?php selected($geopcomsearch_sortVal, 'label'); ?>>Label (asc)</option>
		<option value='modified' <?php selected($geopcomsearch_sortVal, 'modified'); ?>>Modified (desc)</option>
	</select>
<?php
}

function geopcomsearch_select_perpage_render(  ) {
  $geopcomsearch_options = get_option('geopcomsearch_settings', array( 'geopcomsearch_select_perpage' => 10 ));
  $geopcomsearch_perpageVal = $geopcomsearch_options['geopcomsearch_select_perpage'];

  ?>
	<select name='geopcomsearch_settings[geopcomsearch_select_perpage]' class='gp-form-control'>
		<option value='5' <?php selected( $geopcomsearch_perpageVal, 5 ); ?>>5 Items</option>
    <option value='10' <?php selected( $geopcomsearch_perpageVal, 10 ); ?>>10 Items</option>
    <option value='15' <?php selected( $geopcomsearch_perpageVal, 15 ); ?>>15 Items</option>
    <option value='20' <?php selected( $geopcomsearch_perpageVal, 20 ); ?>>20 Items</option>
    <option value='25' <?php selected( $geopcomsearch_perpageVal, 25 ); ?>>25 Items</option>
	</select>
<?php
}

function geopcomsearch_text_keyword_render(  ) {
  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_text_keyword' => '' ));
  $geopcomsearch_keywordVal = $geopcomsearch_options['geopcomsearch_text_keyword'];
	?>
	<input type='text' class='gp-form-control' name='geopcomsearch_settings[geopcomsearch_text_keyword]' value='<?php echo $geopcomsearch_keywordVal; ?>'>
	<?php
}

function geopcomsearch_theme_render(  ) {
  $geopcomsearch_options = get_option( 'geopcomsearch_settings', array( 'geopcomsearch_theme' => 'any' ));
  $geopcomsearch_themeVal = $geopcomsearch_options['geopcomsearch_theme'];
	?>
	<input type='text' class='gp-form-control' name='geopcomsearch_settings[geopcomsearch_theme]' value='<?php echo $geopcomsearch_themeVal; ?>'>
	<?php
}


 ?>
