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
 * @package           Geop_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       Geoplatform Maps Plugin
 * Plugin URI:        www.geoplatform.gov
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kevin Schmidt
 * Author URI:        www.geoplatform.gov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geop-maps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geop-maps-activator.php
 */
function activate_geop_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geop-maps-activator.php';
	Geop_Maps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geop-maps-deactivator.php
 */
function deactivate_geop_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geop-maps-deactivator.php';
	Geop_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geop_maps' );
register_deactivation_hook( __FILE__, 'deactivate_geop_maps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geop-maps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geop_maps() {

	$plugin = new Geop_Maps();
	$plugin->run();

}
run_geop_maps();

function shortcode_creation($atts){

	global $ual_url;
	global $viewer_url;


  $a = shortcode_atts(array(
    'id' => '62c29fe8103c713904d23b8354ba41c8',
    'name' => '',
    'url' => '',
		'width' => '270',
		'height' => '180'
  ), $atts);
  ob_start();
?>
<!-- <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12"> -->
  <div class="gp-ui-card gp-ui-card--minimal" style="width:<?php echo $a['width']; ?>px;">
      <div class="media">
				<div id="<?php echo $a['id']; ?>" style="height:<?php echo $a['height']; ?>px;"></div>

				<script>
					var lat = 38.8282;
					var lng = -98.5795;
					var zoom = 3;

					// Reactive zoom levels based upon input map zoom, current algorithm functional, needs tweaking.
					var widthRatio = <?php echo $a['width'] ?> / window.innerWidth;
					var heightRatio = <?php echo $a['height'] ?> / window.innerHeight;
					var zoomFlex = (widthRatio < heightRatio ? widthRatio : heightRatio);
					var zoomFinal = zoom * (1 + zoomFlex);

					// Document write-out. Unsecure, will be removed before release.
					document.write(widthRatio + " " + heightRatio + " " + zoomFlex + " " + zoomFinal);

					// L is pulled in from another dependency.
					// newsMap must match the newsMap id in newsmap.html
					var map = L.map('<?php echo $a['id']; ?>').setView([lat, lng], zoomFinal);




					// Functioning, usable default map.
	      	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	        	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a>'
	      	}).addTo(map);

					/* Sets up a layergroup and adds it to the map. This is followed by the
					 * function call that adds the elements to the group.
					*/
					markers = L.layerGroup([]).addTo(map);
					addPoints();

				</script>


			<!-- <img class="embed-responsive-item" src="<?php echo $ual_url ?>/api/maps/<?php echo $a['id'] ?>/thumbnail" alt=""></a> -->
      </div> <!--media-->
        <div class="gp-ui-card__body" style="height:45px;">
            <a title="Visit full map of <?php echo $a['name']; ?>" href="<?php echo $viewer_url ?>/?id=<?php echo $a['id']; ?>"><h4 class="text--primary"><?php echo $a['name']; ?></h4></a>
        </div>
  </div> <!--gp-ui-card gp-ui-card-minimal-->
<!-- </div> -->
<?php
return ob_get_clean();
}




function wporg_shortcodes_init()
{
    add_shortcode('geopmap', 'shortcode_creation');
}

add_action('init', 'wporg_shortcodes_init');
?>


<!-- Marker addition script. Currently hard-coded, will be modular later with
 		input from the geoplatform site before it goes live. -->
<script>
function addPoints(){

	// Simple marker with popup-information functionality.
	var popupString = "<b>Hello, good viewer.</b><br>This is a pop-up, which uses HTML notation for its output content.";
	var marker = L.marker([lat + 5, lng - 20]);
	marker.bindPopup(popupString);
	marker.addTo(markers);

	// Four circles of different colors.
	var circle = L.circle([34, -85], {
	    color: 'red',
	    fillColor: '#f00',
	    fillOpacity: 0.5,
	    radius: 250000
	})
	circle.addTo(markers);
	var circle = L.circle([34, -81.25], {
	    color: 'yellow',
	    fillColor: '#ff0',
	    fillOpacity: 0.5,
	    radius: 250000
	})
	circle.addTo(markers);
	var circle = L.circle([37.25, -85], {
	    color: 'blue',
	    fillColor: '#00f',
	    fillOpacity: 0.5,
	    radius: 250000
	})
	circle.addTo(markers);
	var circle = L.circle([37.25, -81.25], {
	    color: 'green',
	    fillColor: '#0f0',
	    fillOpacity: 0.5,
	    radius: 250000
	})
	circle.addTo(markers);

	// A polygon, triangle to be exact.
	var polygon = L.polygon([
		[40, -107],
		[40, -111],
		[38, -109]
	])
	polygon.addTo(markers);

}
</script>
