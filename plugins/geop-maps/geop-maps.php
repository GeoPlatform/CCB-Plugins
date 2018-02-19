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
 * Author:            Kevin Schmidt & Lee Heazel
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

// Hook backbone for shortcode interpretation.
function shortcode_creation($atts){

	// Establishes a base array with default values required for shortcode creation
	// and overwrites them with values from $atts.
  $a = shortcode_atts(array(
    'id' => '62c29fe8103c713904d23b8354ba41c8',
    'name' => '',
    'url' => '',
		'width' => '0',
		'height' => '0'
  ), $atts);
  ob_start();

  // Uses the map ID provided to grab the map data from the GeoPlatform site and
	// decode it into usable JSON info.
	$ual_url = 'https://sit-ual.geoplatform.us/api/maps/' . $a['id'];
	$link_scrub = wp_remote_get( ''.$ual_url.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
	$response = wp_remote_retrieve_body( $link_scrub );
	if(!empty($response)){
	  $result = json_decode($response, true);
	}else{
	  $result = "This Gallery has no recent activity. Try adding some maps!";
	}

	// The JSON info grabbed is checked for a value found only in AGOL maps. If it
	// is found, output is generated using the agol method. Otherwise, the geop
	// method is called.
	if ($result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap")
		agol_map_gen($a);
	else
		geop_map_gen($a);

	return ob_get_clean();
}


// Method for agol map display.
function agol_map_gen($a){

	// Random number generation to give this instance of objects unique element IDs.
	$divrand = rand(0, 99999); ?>

<!-- Main div block that will contain this entry. It has a constant width as
 	   determined by the page layout on load, so its width is set to the widthGrab
	 	 variable. -->
	<div id="master_<?php echo $divrand; ?>" style="clear:both;">
		<script>
			var widthGrab = jQuery('#master_<?php echo $divrand ?>').width();
		</script>

<!-- This is the div block that defines the output proper. It defines the width
 		 and height of the visible map and title card, and contains those elements.
	 	 Its values are set initially to those of height and width as passed by array.-->
	  <div class="gp-ui-card t-bg--primary" id="middle_<?php echo $divrand; ?>" style="width:<?php echo $a['width']; ?>px; height:<?php echo $a['height']; ?>px;">

 <!-- The contents of this entire div act as a hyperlink, set here. -->
			<a title="Visit full map of <?php echo $a['name']; ?>" href="https://sit-maps.geoplatform.us/map.html?id=<?php echo $a['id']; ?>" target="_blank">

	 <!-- Scaling code. If this page element does not have custom-set width or
	 			height. If the user did not specify a width or set a width too wide for
				its container, this check sets the width instead to 100% of the master
				div. Height is also checked for no entry, and set to 75% of the master
				div's width. -->
				<script>
				if (<?php echo $a['width']; ?> == 0 || <?php echo $a['width']; ?> > widthGrab)
					jQuery('#middle_<?php echo $divrand; ?>').width('100%');
				if (<?php echo $a['height']; ?> == 0)
					jQuery('#middle_<?php echo $divrand; ?>').height(widthGrab * 0.75);
				</script>


	 <!-- Actual output in HTML, displaying the title card and thumbnail. -->
				<h4 class="text-white u-pd--lg u-mg--xs">
					<span class="text--primary:visited text-white" style="font-family:Lato,Helvetica,Arial,sans-serif;"><?php echo $a['name']; ?></span>
					<span class="alignright glyphicon glyphicon-info-sign"></span>
				</h4>
	    	<div class="media u-mg--xs">
					<img class="embed-responsive-item" href="https://sit-maps.geoplatform.us/map.html?id=<?php echo $a['id']; ?>" target="_blank" src="https://sit-ual.geoplatform.us/api/maps/<?php echo $a['id']; ?>/thumbnail" alt="Thumbnail failed to load">
				</div>
			</a>
	  </div>
	</div>
<?php
}


// Method for geop map display. Much more dynamic than the agol map generator.
function geop_map_gen($a){

	// Grabs the working environment URI format globals.
	global $viewer_url;
	?>
<!-- Imports all of the resources needed to generate a map. -->
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

<!-- Random number generation to give this instance of objects unique element IDs. -->
	<?php $divrand = rand(0, 99999); ?>

<!-- Main div block that will contain this entry. It has a constant width as
 	   determined by the page layout on load, so its width is set to the widthGrab
	 	 variable. -->
	<div id="master_<?php echo $divrand; ?>" style="clear:both;">
		<script>
			var widthGrab = jQuery('#master_<?php echo $divrand ?>').width();
		</script>

<!-- This is the div block that defines the output proper. It defines the width
 		 of the visible map and title card, and contains those elements. Its values
		 are set initially to those of the width as passed by array.-->
	  <div class="gp-ui-card t-bg--primary" id="middle_<?php echo $divrand; ?>" style="width:<?php echo $a['width']; ?>px;">

 <!-- Name and link card. -->
			<h4 class="text-white u-pd--lg u-mg--xs">
				<span><a title="Visit full map of <?php echo $a['name']; ?>" style="font-family:Lato,Helvetica,Arial,sans-serif; color:white;" href="https://sit-viewer.geoplatform.us/map.html?id=<?php echo $a['id']; ?>" target="_blank"><?php echo $a['name']; ?></a></span>
				<span class="alignright">
					<!-- <span class="glyphicon glyphicon-menu-hamburger"></span> -->
					<a title="Visit full map of <?php echo $a['name']; ?>" style="color:white;" href="https://sit-viewer.geoplatform.us/map.html?id=<?php echo $a['id']; ?>" target="_blank"><span class="glyphicon glyphicon-info-sign"></span></a>
				</span>
			</h4>

 <!-- The container that will hold the leaflet map. Also defines entree height. -->
			<div id="container_<?php echo $divrand; ?>" style="height:<?php echo $a['height']; ?>px;"></div>

			<script>

			  // Scaling code. If this page element does not have custom-set width or
	 	 		// height. If the user did not specify a width or set a width too wide
	 			// for its container, this check sets the width instead to 100% of the
	 			// master div. Height is also checked for no entry, and set to 75% of
	 			// the master div's width.
				if (<?php echo $a['width']; ?> == 0 || <?php echo $a['width']; ?> > widthGrab)
					jQuery('#middle_<?php echo $divrand; ?>').width('100%');
				if (<?php echo $a['height']; ?> == 0)
					jQuery('#container_<?php echo $divrand; ?>').height(widthGrab * 0.75);

				// Javascript block that creates the leaflet map container, wraps it in
		    // a GeoPlatform instance, and sets it up for display.
				var lat = 38.8282;
				var lng = -98.5795;
				var zoom = 3;
				var mapCode = "<?php echo $a['id']; ?>";
				var leafBase = L.map("container_<?php echo $divrand;?>");
	      var mapInstance = GeoPlatform.MapFactory.get();
	      mapInstance.setMap(leafBase);
	      mapInstance.setView(51.505, -0.09, 13);

	      mapInstance.loadMap(mapCode).then( mapObj => {
					let blObj = mapInstance.getBaseLayer();
	        let layerStates = mapInstance.getLayers();
	      });
			</script>
  	</div>
	</div>
<?php
}


// Adds the shortcode hook to init.
function wporg_shortcodes_init()
{
    add_shortcode('geopmap', 'shortcode_creation');
}
add_action('init', 'wporg_shortcodes_init');


// Linking of dependenceis into the document.
?>
