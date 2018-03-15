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
 * @since             0.9.1
 * @package           Geop_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Maps Plugin
 * Plugin URI:        www.geoplatform.gov
 * Description:       Manage your own personal GeoPlatform maps and use shortcode to insert them into your posts.
 * Version:           1.0.0
 * Author:            Image Matters LLC
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

	global $ual_url;

	// Empty error text output string.
	$error_text = '';

  // Uses the map ID provided to grab the map data from the GeoPlatform site and
	// decode it into usable JSON info. Produces a bum result and error text if
	// it fails.
	$ual_url_in = $ual_url . '/api/maps/' . $a['id'];
	$link_scrub = wp_remote_get( ''.$ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
	$response = wp_remote_retrieve_body( $link_scrub );
	if(!empty($response))
	  $result = json_decode($response, true);
	else{
	  $result = "This Gallery has no recent activity. Try adding some maps!";
		$error_text .= "The GeoPlatform server could not be contacted to verify this map.<BR>" . $ual_url;
	}

	// Invalid map ID check. A faulty map ID will return a generic JSON dataset
	// from GeoPlatform with a statusCode entry containing "404" code. This will
	// add text to $error_text, which will be used for error reporting later.
	if ($result['statusCode'] == "404")
	  $error_text .= "Your map ID could not be found on the GeoPlatform server. Please check your map ID and try again.<BR>";


	// The JSON info grabbed is checked for a value found only in AGOL maps. If it
	// is found, the process proceeds with agol map generation. Otherwise, the
	// geop method is called.
	if ($result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap"){
		$landing_page = '';
		if (isset($result['landingPage'])){
			$landing_page = $result['landingPage'];
		}
		agol_map_gen($a, $error_text, $landing_page);
	}
	else
		geop_map_gen($a, $error_text);

	return ob_get_clean();
}



// Method for agol map display.
function agol_map_gen($a, $error_text, $landing_page){

	global $ual_url;
	global $maps_url;
	// Random number generation to give this instance of objects unique element IDs.
	$divrand = rand(0, 99999);

	if (empty($landing_page))
		$landing_page = $maps_url . '/map.html?id=' . $a['id'];
	?>

<!-- Main div block that will contain this entry. It has a constant width as
 	   determined by the page layout on load, so its width is set to the widthGrab
	 	 variable. -->
	<div id="master_<?php echo $divrand; ?>" style="clear:both;">
		<script>
			var widthGrab = jQuery('#master_<?php echo $divrand ?>').width();
		</script>

<!-- This is the div block that defines the output proper. It defines the width
 		 of the visible map and title card, and contains those elements. Its values
		 are set initially to those of width as passed by array. The contents of the
	 	 entire div also act as a hyperlink, set here-->
	  <div class="gp-ui-card t-bg--primary" id="middle_<?php echo $divrand; ?>" style="width:<?php echo $a['width']; ?>px;">
			<a title="Visit full map of <?php echo $a['name']; ?>" href="<?php echo $landing_page ?>" target="_blank" style="z-index:1;">

	 <!-- Actual output in HTML, displaying the title card and thumbnail. -->
				<h4 class="text-white u-pd--lg u-mg--xs">
					<span class="text--primary:visited text-white" style="font-family:Lato,Helvetica,Arial,sans-serif;"><?php echo $a['name']; ?></span>
					<span class="alignright glyphicon glyphicon-info-sign"></span>
				</h4>
				<img class="embed-responsive-item" id="image_<?php echo $divrand; ?>" href="<?php echo $landing_page ?>" target="_blank" src="<?php echo $ual_url ?>/api/maps/<?php echo $a['id']; ?>/thumbnail" alt="Thumbnail failed to load" style="width:100%; height:<?php echo $a['height']; ?>px;" onerror="geop_thumb_error(this);"/>
			</a>

 <!-- Error report container with heading, an empty output region, and a button
	 		to close it disguised as text. -->
			<div class="t-bg--danger pd-lg" id="errorbox_<?php echo $divrand; ?>" style="font-family:Lato,Helvetica,Arial,sans-serif; width:<?php echo $a['width']; ?>px; z-index:3; position:absolute; left:0; bottom:0;">
				<p class="text-white media-heading" style="font-weight:900;">An Error Has Occurred</p>
		 		<p class="u-pd-right--lg text-white comment-content" id="errorout_<?php echo $divrand; ?>"></p>
		 		<button class="text-white mg-xxlg--right" id="errorclose_<?php echo $divrand; ?>" style="background:none; border:none; float:right;">Dismiss</button>
		 	</div>
	  </div>
	</div>

<!-- Javascript region. -->
	<script>
	jQuery('document').ready(function(){

		// Error report string grabbing data from the $error_text argument.
		var error_report = "<?php echo $error_text ?>";

		// Verifies if the thumbnail exists and adds to the error report if not.
		jQuery.get("<?php echo $maps_url ?>/map.html?id=<?php echo $a['id']; ?>").fail(function(){
			error_report += "The thumbnail image for this map failed to load or does not exist.<BR>";
		})

		// Scaling code. If this page element does not have custom-set width or
		// height. If the user did not specify a width or set a width too wide for
		// its container, this check sets the width instead to 100% of the master
		// div. Height is also checked for no entry, and set to 75% of the master
		// div's width.
		if (<?php echo $a['width']; ?> == 0 || <?php echo $a['width']; ?> > widthGrab){
			jQuery('#middle_<?php echo $divrand; ?>').width('100%');
			jQuery('#errorbox_<?php echo $divrand; ?>').width('100%');
			jQuery('#image_<?php echo $divrand; ?>').width('100%');
		}
		if (<?php echo $a['height']; ?> == 0)
			jQuery('#image_<?php echo $divrand; ?>').height(jQuery('#image_<?php echo $divrand; ?>').width() * 0.56);

		// Error report handler. If there is content in error_report, that string
		// is set to the error output in the error div. Otherwise, that div is
		// hidden.
		if (error_report)
			jQuery('#errorout_<?php echo $divrand; ?>').html(error_report);
		else
			jQuery('#errorbox_<?php echo $divrand; ?>').hide();

		// Hiding control for the error div, sliding it down when the user clicks
		// the dismiss button/text.
		jQuery('#errorclose_<?php echo $divrand; ?>').click(function(){
			jQuery('#errorbox_<?php echo $divrand; ?>').slideToggle();
		});

		// If the map is valid but for some reason does not possess a thumbnail, this
	  // method is called and will supply a local default borrowed from the sit Map
	  // Viewer site.
	  function geop_thumb_error(geop_image_in){
	    geop_image_in.onerror = "";
	    geop_image_in.src = "/wp-content/plugins/geop-maps/includes/img-404.png";
			error_report += "The thumbnail image for this map failed to load or does not exist.<BR>";
	    return true;
	  }
	})
	</script>
<?php
}


// Method for geop map display. Much more dynamic than the agol map generator.
function geop_map_gen($a, $error_text){

	// Grabs the working environment URI format globals. Also generates the random
	// number used for unique element referencing.
	global $viewer_url;
	global $oe_url;
	$divrand = rand(0, 99999);

	?>
<!-- Imports all of the resources needed to generate a map. Why doesn't enquque work? -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/q.js/1.5.1/q.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/iso8601-js-period@0.2.1/iso8601.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.1/leaflet.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/esri-leaflet/2.1.2/esri-leaflet.js"></script>
	<script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css">
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css">
	<script src="https://cdn.jsdelivr.net/npm/leaflet-timedimension@1.1.0/dist/leaflet.timedimension.src.js"></script>
	<script>
	 GeoPlatform = {

		 //REQUIRED: environment the application is deployed within
		 // one of "development", "sit", "stg", "prd", or "production"
		 "env" : "development",

		 //REQUIRED: URL to GeoPlatform UAL for API usage
		 "ualUrl" : "https://sit-ual.geoplatform.us",

		 //Object Editor URL.
		 "oeUrl" : "https://sit-oe.geoplatform.us",

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

<!-- Local styles that will be used. Connecting a viable CSS file seems broken. -->
	<style>
	.geop-layer-menu {
		font-family: Lato,Helvetica,Arial,sans-serif;
		border: 1px solid #ddd;
		width: 50%;
		background-color: #fff;
		z-index: 2;
		position: absolute;
		bottom: 0;
		right: 0;
		overflow: auto;
		display: none;
	}

	.geop-layer-box {
		border: 1px solid #ddd;
		background-color: #fff;
		color: black;
		padding: 8px;
		display: in-line;
	}

	.geop-text-button {
		background: none;
		border: none;
	}

	.geop-layer-text-style {
		padding: 8px;
		word-wrap: break-word;
	}

	.geop-error-text-style {
		font-family: Lato,Helvetica,Arial,sans-serif;
		z-index: 3;
		position: absolute;
		left: 0;
		bottom: 0;
	}
	</style>

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

 <!-- Name, link, and layer control card. Provides a link to the map with the
 			title text, link to the object editor with the info icon link, and has a
			button disguised as an image that toggles layer control sidebar visibility. -->
			<h4 class="text-white u-pd--lg u-mg--xs" id="title_<?php echo $divrand; ?>">
				<span><a title="Visit full map of <?php echo $a['name']; ?>" style="font-family:Lato,Helvetica,Arial,sans-serif; color:white;" href="<?php echo $viewer_url ?>/?id=<?php echo $a['id']; ?>" target="_blank"><?php echo $a['name']; ?></a></span>
				<span class="alignright">
					<button class="glyphicon glyphicon-menu-hamburger geop-text-button" id="layer_menu_button_<?php echo $divrand; ?>"></button>
					<a class="glyphicon glyphicon-info-sign" title="Visit full map of <?php echo $a['name']; ?> in the Object Editor." style="color:white;" href="<?php echo $oe_url; ?>/view/<?php echo $a['id']; ?>" target="_blank"></a>
				</span>
			</h4>

 <!-- The container that will hold the leaflet map. Also defines entree height. -->
			<div id="container_<?php echo $divrand; ?>" style="height:<?php echo $a['height']; ?>px; position:relative; z-index:1"></div>

 <!-- Layer control container. Provides the base container that holds the layer
 			controls generated later, and populates it with its first box, a simple
			informational header. -->
			<div class="geop-layer-menu" id="layerbox_<?php echo $divrand; ?>" style="height:<?php echo $a['height']; ?>px;">
				<div class="geop-layer-box wp-caption-text" id="layer_header_<?php echo $divrand; ?>" style="width:100%"><h4>Layer Menu</h4></div>
			</div>

 <!-- Error report container with heading, an empty output region, and a button
 			to close it disguised as text. The output region has the possibility to be
		 	filled later if errors are found. -->
			<div class="t-bg--danger pd-lg geop-error-text-style" id="errorbox_<?php echo $divrand; ?>" style="width:<?php echo $a['width']; ?>px;">
				<p class="text-white media-heading" style="font-weight:900;">An Error Has Occurred</p>
				<p class="u-pd-right--lg text-white comment-content" id="errorout_<?php echo $divrand; ?>"></p>
				<button class="text-white mg-xxlg--right geop-text-button" id="errorclose_<?php echo $divrand; ?>" style="float:right;">Dismiss</button>
			</div>
  	</div>
	</div>

<!-- Javascript region. -->
	<script>
	jQuery('document').ready(function(){

		// Error report string, which will be filled for display if necessary.
		var error_report = "<?php echo $error_text ?>";

		// Scaling code. If this page element does not have custom-set width or
		// height. If the user did not specify a width or set a width too wide
		// for its container, this check sets the width instead to 100% of the
		// master div. Height is also checked for no entry, and set to 75% of
		// the master div's width.
		if (<?php echo $a['width']; ?> == 0 || <?php echo $a['width']; ?> > widthGrab){
			jQuery('#middle_<?php echo $divrand; ?>').width('100%');
			jQuery('#errorbox_<?php echo $divrand; ?>').width('100%');
		}
		if (<?php echo $a['height']; ?> == 0){
			jQuery('#container_<?php echo $divrand; ?>').height(widthGrab * 0.75);
			jQuery('#layerbox_<?php echo $divrand; ?>').height(widthGrab * 0.75);
		}
		if (jQuery('#middle_<?php echo $divrand; ?>').width() <= 400)
			jQuery('#layer_menu_button_<?php echo $divrand; ?>').hide();

		// Javascript block that creates the leaflet map container, with mapInstance
		// being our GeoPlatform map. This section is wrapped in a try-catch block
		// to catch any errors that may come. As of now, it's a pure error output
		// concatenated to error_report.
		var mapInstance;
		try {
			var lat = 38.8282;
			var lng = -98.5795;
			var zoom = 3;
			var mapCode = "<?php echo $a['id']; ?>";
			var leafBase = L.map("container_<?php echo $divrand;?>", {
				minZoom: 2,
				maxZoom: 21
			});

			mapInstance = GeoPlatform.MapFactory.get();
			mapInstance.setMap(leafBase);
			mapInstance.setView(51.505, -0.09, 13);

      // Actual attribute setting function including layer grab function, which
			// cycles through the layers and populates the layer control sidebar. Also
			// has error catching to grab and errors through the promise system.
			var layerStates;
			mapInstance.loadMap(mapCode).then( function(){
				var baseLayer = mapInstance.getBaseLayer();
				layerStates = mapInstance.getLayers();
				geop_layer_control_gen(mapInstance, layerStates);
			}).catch( function(error){
				error_report += (error + "<BR>");
			})
		}
		catch(err){
			error_report += (err + "<BR>");
		}


    // Function for generating the layer control sidebar entries. For each layer
		// found, it creates a series of HTML elements using the geop_createEl()
		// method and appends them into a DOM heirarchy before adding them to the
		// layerbox div.
		function geop_layer_control_gen(mapInstance, layerStates){

			if (layerStates.length > 0){
				for (var i = 0; i < layerStates.length; i++){
					var main_box = geop_createEl({type: 'div', class: 'geop-layer-box'});
					var main_table = geop_createEl({type: 'table', style: 'width:100%'});
					var table_row = geop_createEl({type: 'tr'});
					var first_td = geop_createEl({type: 'td'});
					var check_button = geop_createEl({type: 'button', class: 'glyphicon glyphicon-check geop-text-button layer_button_class_<?php echo $divrand; ?>', id: 'layer_button_id_<?php echo $divrand; ?>', style: 'width:auto', text: layerStates[i].layer_id});
					var second_td = geop_createEl({type: 'td', class: 'layer_content_class_<?php echo $divrand; ?> geop-layer-text-style', id: 'layer_content_id_<?php echo $divrand; ?>', html: layerStates[i].layer.label});
					var third_td = geop_createEl({type: 'td', class: 'pd-md--right'});
					var info_link = geop_createEl({type: 'a', class: 'glyphicon glyphicon-info-sign', title: 'View this layer of <?php echo $a['name']; ?> in the Object Viewer.', href: '<?php echo $oe_url; ?>/view/' + layerStates[i].layer_id, target: "_blank", style: 'color:black; float:right;'})

					first_td.appendChild(check_button);
					third_td.appendChild(info_link);
					table_row.appendChild(first_td);
					table_row.appendChild(second_td);
					table_row.appendChild(third_td);
					main_table.appendChild(table_row);
					main_box.appendChild(main_table);
					document.getElementById('layerbox_<?php echo $divrand; ?>').appendChild(main_box);
				}

				// Layer toggle detector and executor. Must be put placed here as the
				// elements involved cannot be manipulated outside of the promise stack.
				jQuery('.layer_button_class_<?php echo $divrand; ?>').click(function(){
					jQuery(this).toggleClass('glyphicon-check glyphicon-unchecked');
					mapInstance.toggleLayerVisibility(jQuery(this).attr('text'));
				});
			}
			else{

				// If there are no layers to the map, the layer header div for that map
				// is modified to express such.
				jQuery('#layer_header_<?php echo $divrand; ?>').html('<h4>This map has no layers.</h4>');
			}
		}


		// Creates an HTML element and, using the arrays of string pairs passed here
		// from geop_layer_control_gen(), adds attributes to it that make it into a
		// functional element before returning it.
		function geop_createEl(atts){
			atts = atts || {};
			var new_el = document.createElement(atts.type);
			if(atts.html)
				new_el.innerHTML = atts.html;
			if(atts.text)
				new_el.setAttribute('text', atts.text);
			if(atts.class)
				new_el.setAttribute('class', atts.class);
			if(atts.style)
				new_el.setAttribute('style', atts.style);
			if(atts.id)
				new_el.setAttribute('id', atts.id);
			if(atts.title)
				new_el.setAttribute('title', atts.title);
			if(atts.href)
				new_el.setAttribute('href', atts.href);
			if(atts.target)
				new_el.setAttribute('target', atts.target);
			return new_el;
		}

		// Show/hide toggle control for the layer div, showing or hiding it when the
		// user presses the layer view button.
		jQuery('#layer_menu_button_<?php echo $divrand; ?>').click(function(){
			jQuery('#layerbox_<?php echo $divrand; ?>').animate({ width: "toggle" });
		});

		// Error report handler. If there is content in error_report, that string
		// is set to the error output in the error div. Otherwise, that div is
		// hidden.
		if (error_report)
			jQuery('#errorout_<?php echo $divrand; ?>').html(error_report);
		else
			jQuery('#errorbox_<?php echo $divrand; ?>').hide();

		// Hide control for the error div, sliding it down when the user clicks the
		// dismiss button/text.
		jQuery('#errorclose_<?php echo $divrand; ?>').click(function(){
			jQuery('#errorbox_<?php echo $divrand; ?>').slideToggle();
		});
	})
	</script>
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
