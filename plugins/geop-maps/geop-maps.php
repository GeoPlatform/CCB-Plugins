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
		'height' => '180',
		'agol' => 'N'
  ), $atts);
  ob_start();

	if ($a['agol'] == 'N')
		geop_map_gen($a);
	else
		agol_map_gen($a);

	return ob_get_clean();
}



function agol_map_gen($a){
	?>
	<div style="margin:1em;clear:both;padding:1em;">
	  <div class="gp-ui-card gp-ui-card--minimal" style="width:200px;">
			<a title="Visit full map of <?php echo $a['name']; ?>" href="https://geoplatform.maps.arcgis.com/home/webmap/viewer.html?webmap=<?php echo $a['id']; ?>">
	    	<div class="media">
					<img class="embed-responsive-item" href="https://geoplatform.maps.arcgis.com/home/webmap/viewer.html?webmap=<?php echo $a['id']; ?>" src="https://geoplatform.maps.arcgis.com/sharing/rest/content/items/<?php echo $a['id'] ?>/info/thumbnail/ago_downloaded.png" alt="Thumbnail failed to load">
				</div> <!--media-->
	    	<div>
					<h4 class="text--primary"><?php echo $a['name']; ?></h4>
	    	</div>
			</a>
	  </div>
	</div>


<?php
}




function geop_map_gen($a){
	?>

	<div style="margin:1em;clear:both;padding:1em;">
	  <div class="gp-ui-card gp-ui-card--minimal" style="width:<?php echo $a['width']; ?>px;">
	    <div class="media">
				<?php $divrand = rand(0, 99999); ?>
				<div id="container_<?php echo $divrand; ?>" style="height:<?php echo $a['height']; ?>px;"></div>

				<script>
					var lat = 38.8282;
					var lng = -98.5795;
					var zoom = 3;
					var mapCode = "<?php echo $a['id']; ?>";

					// Reactive zoom levels based upon input map zoom, current algorithm functional, needs tweaking.
					var widthRatio = <?php echo $a['width'] ?> / window.innerWidth;
					var heightRatio = <?php echo $a['height'] ?> / window.innerHeight;
					var zoomFlex = (widthRatio < heightRatio ? widthRatio : heightRatio);
					var zoomFinal = zoom * (1 + zoomFlex);





					var leafBase = L.map("container_<?php echo $divrand;?>");
		      var mapInstance = GeoPlatform.MapFactory.get();
		      mapInstance.setMap(leafBase);
		      mapInstance.setView(51.505, -0.09, 13);



		      mapInstance.loadMap(mapCode).then( mapObj => {
						let blObj = mapInstance.getBaseLayer();
		        let layerStates = mapInstance.getLayers();
		      });






				</script>

				<!-- <img class="embed-responsive-item" src="<?php echo $ual_url ?>/api/maps/<?php echo $a['id'] ?>/thumbnail" alt=""></a> -->
	    </div> <!--media-->
	    <div>
	      <a title="Visit full map of <?php echo $a['name']; ?>" href="<?php echo $viewer_url ?>/?id=<?php echo $a['id']; ?>"><h4 class="text--primary"><?php echo $a['name']; ?></h4></a>
	    </div>
	  </div>
	</div>
		<?php
}








function wporg_shortcodes_init()
{
    add_shortcode('geopmap', 'shortcode_creation');
}

add_action('init', 'wporg_shortcodes_init');
?>




<!-- Utilize wp_enqueue_script() to load these dependenceis in order before final product -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/q.js/1.5.1/q.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/esri-leaflet/2.1.2/esri-leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.3.0/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-timedimension@1.1.0/dist/leaflet.timedimension.src.js"></script>
<script src="https://cdn.jsdelivr.net/npm/iso8601-js-period@0.2.1/iso8601.min.js"></script>
<script>
 GeoPlatform = {

	 //REQUIRED: environment the application is deployed within
	 // one of "development", "sit", "stg", "prd", or "production"
	 "env" : "development",

	 //REQUIRED: URL to GeoPlatform UAL for API usage
	 "ualUrl" : "https://sit-ual.geoplatform.us",

	 //timeout max for requests
	 "timeout" : "5000",

	 //identifier of GP Layer to use as default base layer
	 "defaultBaseLayerId" : "209573d18298e893f21e6064b23c8638",

	 //{env}-{id} of application deployed
	 "appId" : "development-mv"
 };
</script>
<script src="/wp-content/plugins/geop-maps/public/js/geoplatform.client.js"></script>
<script src="/wp-content/plugins/geop-maps/public/js/geoplatform.mapcore.js"></script>
<script>
	console.log(GeoPlatform)
</script>
