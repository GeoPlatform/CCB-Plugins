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
 * Plugin Name:       GeoPlatform Maps Plugin
 * Plugin URI:        www.geoplatform.gov
 * Description:       Manage your own personal database of GeoPlatform interactive maps and use shortcode to insert them into your posts.
 * Version:           1.0.3
 * Author:            Image Matters LLC
 * Author URI:        www.imagemattersllc.com
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

	// Grabs the file that handles environmental variables.
	require_once('includes/class-geop-maps-urlbank.php');

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

  // URL variables for pinging the url bank for environment URLs. Checks for a
	// GeoPlatform theme, pulling the global env variable and checking it as well
	// for a valid value. If either check fails, geop_env defaults to 'prd', which
	// will produce production-state URLs from the url bank.
	$geop_env = 'prd';
	$geop_theme = 'F';
	if (substr(get_template(), 0, 11) == "GeoPlatform"){
	 	global $env;
		$geop_theme = 'T';
		if ($env == 'dev' || $env == 'stg')
			$geop_env = $env;
	}

	// Instantiates the URL bank for environment variable grabbing.
	$Geop_url_class = new Geop_url_bank;

	// Empty error text output string.
	$error_text = '';

  // Uses the map ID provided to grab the map data from the GeoPlatform site and
	// decode it into usable JSON info. Produces a bum result and error text if
	// it fails.
	$ual_url_in = $Geop_url_class->geop_maps_get_ual_url($geop_env) . '/api/maps/' . $a['id'];
	$link_scrub = wp_remote_get( ''.$ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
	$response = wp_remote_retrieve_body( $link_scrub );
	if(!empty($response))
	  $result = json_decode($response, true);
	else{
	  $result = "This Gallery has no recent activity. Try adding some maps!";
		$error_text .= "The GeoPlatform server could not be contacted to verify this map.<BR>" . $Geop_url_class->geop_maps_get_ual_url($geop_env);
	}

	// Invalid map ID check. A faulty map ID will return a generic JSON dataset
	// from GeoPlatform with a statusCode entry containing "404" code. This will
	// add text to $error_text, which will be used for error reporting later.
	if (array_key_exists('statusCode', $result) && $result['statusCode'] == "404")
	  $error_text .= "Your map ID could not be found on the GeoPlatform server. Please check your map ID and try again.<BR>";

	// The JSON info grabbed is checked for a value found only in AGOL maps. If it
	// is found, the landing page value is pulled from the JSON and the process
	// proceeds with agol map generation. Otherwise, the geop method is called.
	if (array_key_exists('resourceTypes', $result) && $result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap"){
		$landing_page = '';
		if (array_key_exists('landingPage', $result) && isset($result['landingPage']))
			$landing_page = $result['landingPage'];
		agol_map_gen($a, $error_text, $Geop_url_class->geop_maps_get_ual_url($geop_env), $Geop_url_class->geop_maps_get_maps_url($geop_env), $landing_page, $geop_theme);
	}
	else
		geop_map_gen($a, $error_text, $Geop_url_class->geop_maps_get_ual_url($geop_env), $Geop_url_class->geop_maps_get_viewer_url($geop_env), $Geop_url_class->geop_maps_get_oe_url($geop_env), $geop_theme);
	return ob_get_clean();
}



/** Method for agol map display in a GeoPlatform theme environment.
 *
 *  #param $a: array of information captured from the shortcode string.
 *  #param $error_text: string of error text passed in, preferably empty.
 *  #param $geop_ual_url: url to the expected ual server.
 *  #param $geop_maps_url: url to the expected maps server.
 *  #param $landing_page: url that the map links to on the GeoPlatform maps page.
 *  #param $geop_theme: a 'T' or 'F' value reflecting whether or not a GeopPlatform theme is in use.
*/
function agol_map_gen($a, $error_text, $geop_ual_url, $geop_maps_url, $landing_page, $geop_theme){

	// Random number generation to give this instance of objects unique element IDs.
	$divrand = rand(0, 99999);

	// Variables that vary among themes. They are set to default values for work in
	// the GeoPlatform themes, then changed if one such theme is absent.
	$geop_info_icon = 'glyphicon glyphicon-info-sign';
	$geop_heading_title_size = '1.125em';

	if ($geop_theme == 'F'){
		$geop_info_icon = 'fas fa-info-circle';
		$geop_heading_title_size = '1em';
	}

	// Landing page check. If no landing page was passed, a default is generated.
	if (empty($landing_page))
		$landing_page = $geop_maps_url . '/map.html?id=' . $a['id'];
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
	  <div class="geop-display-main" id="middle_<?php echo $divrand; ?>" style="width:<?php echo $a['width']; ?>px;">
			<a title="Visit full map of <?php echo $a['name']; ?>" href="<?php echo $landing_page ?>" target="_blank" style="z-index:1;">

	 <!-- Actual output in HTML, displaying the title card and thumbnail. -->
				<div class="geop-display-header" style="font-size:<?php echo $geop_heading_title_size ?>;">
					<table class="geop-no-border geop-no-cushion geop-header-table-layout">
						<tr class="geop-no-border">
							<th class="geop-no-border geop-no-cushion">
								<span class="geop-white-item geop-display-header-text geop-no-transform"><?php echo $a['name']; ?></span>
							</th>
							<th class="geop-no-border geop-no-cushion">
								<span class="<?php echo $geop_info_icon ?> geop-white-item geop-header-controls"></span>
							</th>
						</tr>
					</table>
				</div>
				<img class="geop-container-controls" id="image_<?php echo $divrand; ?>" href="<?php echo $landing_page ?>" target="_blank" src="<?php echo $geop_ual_url ?>/api/maps/<?php echo $a['id']; ?>/thumbnail" alt="Thumbnail failed to load" style="height:<?php echo $a['height']; ?>px;" onerror="geop_thumb_error(this);"/>
			</a>

 <!-- Error report container with heading, an empty output region, and a button
	 		to close it disguised as text. -->
			<div class="geop-error-box" id="errorbox_<?php echo $divrand; ?>" style="width:<?php echo $a['width']; ?>px;">
				<p class="geop-white-item geop-heavy-text geop-sixteen-text geop-error-bottom-eight-marg">An Error Has Occurred</p>
		 		<p class="geop-white-item geop-error-report geop-sixteen-text geop-error-bottom-twelve-marg" id="errorout_<?php echo $divrand; ?>"></p>
		 		<button class="geop-white-item geop-no-transform geop-right-marg-float geop-text-button geop-sixteen-text" id="errorclose_<?php echo $divrand; ?>">Dismiss</button>
		 	</div>
	  </div>
	</div>

<!-- Javascript region. -->
	<script>
	jQuery('document').ready(function(){

		// Error report string grabbing data from the $error_text argument.
		var error_report = "<?php echo $error_text ?>";

		// Verifies if the thumbnail exists and adds to the error report if not.
		jQuery.get("<?php echo $geop_maps_url ?>/map.html?id=<?php echo $a['id']; ?>").fail(function(err){
			error_report += "The thumbnail image for this map failed to load or does not exist.<BR>";
		})

		// Scaling code. If this page element does not have custom-set width or
		// height. If the user did not specify a width or set a width too wide for
		// its container, this check sets the width instead to 100% of the master
		// div. Height is also checked for no entry, and set to 56% of the master
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


/** Method for geop map display. Much more dynamic than the agol map generator.
*
*  #param $a: array of information captured from the shortcode string.
*  #param $error_text: string of error text passed in, preferably empty.
*  #param $geop_viewer_url: url to the expected viewer server.
*  #param $geop_maps_url: url to the expected object editor server.
*  #param $geop_theme: a 'T' or 'F' value reflecting whether or not a GeopPlatform theme is in use.
*/
function geop_map_gen($a, $error_text, $geop_ual_url, $geop_viewer_url, $geop_oe_url, $geop_theme){

	// Generates the random number used for unique element referencing.
	$divrand = rand(0, 99999);

	// Variables that vary among themes. They are set to default values for work in
	// the GeoPlatform themes, then changed if one such theme is absent.
	$geop_list_icon = 'glyphicon glyphicon-menu-hamburger';
	$geop_info_icon = 'glyphicon glyphicon-info-sign';
	$geop_base_icon = 'glyphicon';
	$geop_check_icon = 'glyphicon-check';
	$geop_uncheck_icon = 'glyphicon-unchecked';
	$geop_heading_title_size = '1.125em';

	if ($geop_theme == 'F'){
		$geop_list_icon = 'fa fa-bars';
		$geop_info_icon = 'fa fa-info-circle';
		$geop_base_icon = 'fa';
		$geop_check_icon = 'fa-check-square';
		$geop_uncheck_icon = 'fa-square';
		$geop_heading_title_size = '1em';
	}
	?>


<!-- Due to limitations when enqueueing files, maps will not load if done so
 	in SIT or STG. In those environments, this code must be used. Otherwise,
 	enqueueing works perfectly fine. -->
	<script>
	 GeoPlatform = {

		 //REQUIRED: environment the application is deployed within
		 // one of "development", "sit", "stg", "prd", or "production"
		 "env" : "development",

		 //REQUIRED: URL to GeoPlatform UAL for API usage
		 "ualUrl" : "<?php echo $geop_ual_url ?>",

		 //Object Editor URL.
		 "oeUrl" : "<?php echo $geop_oe_url ?>",

		 //timeout max for requests
		 "timeout" : "5000",

		 //identifier of GP Layer to use as default base layer
		 "defaultBaseLayerId" : "209573d18298e893f21e6064b23c8638",

		 //{env}-{id} of application deployed
		 "appId" : "development-mv"
	 };
	</script>
	<script src="<?php echo plugin_dir_url(__FILE__) ?>public/assets/geoplatform.client.js" type="text/javascript"></script>
	<script src="<?php echo plugin_dir_url(__FILE__) ?>public/assets/geoplatform.mapcore.js" type="text/javascript"></script>


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
	  <div class="geop-display-main" id="middle_<?php echo $divrand; ?>" style="width:<?php echo $a['width']; ?>px;">

 <!-- Name, link, and layer control card. Provides a link to the map with the
 			title text, link to the object editor with the info icon link, and has a
			button disguised as an image that toggles layer control sidebar visibility. -->
			<div class="geop-display-header" id="title_<?php echo $divrand; ?>" style="font-size:<?php echo $geop_heading_title_size ?>;">
				<table class="geop-no-border geop-no-cushion geop-header-table-layout">
					<tr class="geop-no-border">
						<th class="geop-no-border geop-no-cushion">
							<a class="geop-hidden-link geop-no-transform" title="Visit full map of <?php echo $a['name']; ?>" href="<?php echo $geop_viewer_url ?>/?id=<?php echo $a['id']; ?>" target="_blank">
								<span class="geop-white-item geop-hidden-link geop-display-header-text"><?php echo $a['name']; ?></span>
							</a>
						</th>
						<th class="geop-no-border geop-no-cushion">
							<span class="geop-header-controls">
								<button class="geop-text-button" id="layer_menu_button_<?php echo $divrand; ?>">
									<span class="<?php echo $geop_list_icon ?> geop-white-item"></span>
								</button>
								<a class="geop-hidden-link" title="Visit full map of <?php echo $a['name']; ?> in the Object Editor." href="<?php echo $geop_oe_url; ?>/view/<?php echo $a['id']; ?>" target="_blank">
									<span class="<?php echo $geop_info_icon ?> geop-white-item"></span>
								</a>
							</span>
						</th>
					</tr>
				</table>
			</div>

 <!-- The container that will hold the leaflet map. Also defines entree height. -->
			<div class="geop-container-controls" id="container_<?php echo $divrand; ?>" style="height:<?php echo $a['height']; ?>px;"></div>

 <!-- Layer control container. Provides the base container that holds the layer
 			controls generated later, and populates it with its first box, a simple
			informational header. -->
			<div class="geop-layer-menu" id="layerbox_<?php echo $divrand; ?>" style="height:<?php echo $a['height']; ?>px;">
				<div class="geop-layer-box" id="layer_header_<?php echo $divrand; ?>" style="width:100%">
					<p class="geop-caption-text geop-no-transform" id="layer_head_text_<?php echo $divrand; ?>">Layer Menu</p>
				</div>
			</div>

 <!-- Error report container with heading, an empty output region, and a button
 			to close it disguised as text. The output region has the possibility to be
		 	filled later if errors are found. -->
			<div class="geop-error-box" id="errorbox_<?php echo $divrand; ?>">
				<p class="geop-white-item geop-heavy-text geop-sixteen-text geop-error-bottom-eight-marg">An Error Has Occurred</p>
				<p class="geop-white-item geop-error-report geop-sixteen-text geop-error-bottom-twelve-marg" id="errorout_<?php echo $divrand; ?>"></p>
				<button class="geop-white-item geop-no-transform geop-right-marg-float geop-text-button geop-sixteen-text" id="errorclose_<?php echo $divrand; ?>">Dismiss</button>
			</div>
  	</div>
	</div>

<!-- Javascript region. -->
	<script>
	jQuery('document').ready(function(){

		// Error report string, which will be filled for display if necessary.
		var error_report = "<?php echo $error_text ?>";

		// Grabs the geop_theme PHP param as a Javascript param.
		var geop_theme = "<?php echo $geop_theme ?>";

		// Scaling code. If this page element does not have custom-set width or
		// height. If the user did not specify a width or set a width too wide
		// for its container, this check sets the width instead to 100% of the
		// master div. Height is also checked for no entry, and set to 75% of
		// the master div's width.
		if (<?php echo $a['width']; ?> == 0 || <?php echo $a['width']; ?> > widthGrab)
			jQuery('#middle_<?php echo $divrand; ?>').width('100%');
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
				maxZoom: 21,
				attributionControl: false
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
				geop_layer_control_gen(mapInstance, layerStates, geop_theme);
			}).catch( function(error){
				error_report += (error + "<BR>");
			})
		}
		catch(err){
			error_report += (err + "<BR>");
		}

    /** Function for generating the layer control sidebar entries. For each layer
		*  found, it creates a series of HTML elements using the geop_createEl()
		*  method and appends them into a DOM heirarchy before adding them to the
		*  layerbox div. Layer toggling outside of the GeopPlatform themes currently
		*	 does not function, so the checkboxes have been removed and the layer name
		*  output given left padding in such cases.
		*
		*  #param mapInstance: the GeoPlatform map instance being referenced.
		*  #param layerStates: the layers from that map instance.
		*  #param geop_theme: a 'T' or 'F' value reflecting whether or not a GeopPlatform theme is in use.
		*/
		function geop_layer_control_gen(mapInstance, layerStates, geop_theme){

			// Checks to ensure that there are layers to process. If so, cycles through
			// each layer and creates local variables in the form of html elements.
			if (layerStates.length > 0){
				for (var i = 0; i < layerStates.length; i++){
					var main_table = geop_createEl({type: 'table', class: 'geop-layer-box', style: 'width:100%'});
					var table_row = geop_createEl({type: 'tr', class: 'geop-no-border'});
					var first_td = geop_createEl({type: 'td', class: 'geop-no-border geop-table-pad'});
					var check_button = geop_createEl({type: 'button', class: 'geop-text-button layer_button_class_<?php echo $divrand; ?>', id: 'layer_button_id_<?php echo $divrand; ?>', style: 'width:auto', opac: '1.0', text: layerStates[i].layer_id});
					var check_icon = geop_createEl({type: 'span', class: 'layer_button_icon_<?php echo $divrand; ?> <?php echo $geop_base_icon . " " . $geop_check_icon ?>', style: 'color:black;'});
					var second_td = geop_createEl({type: 'td', class: 'layer_content_class_<?php echo $divrand; ?> geop-layer-text-style', id: 'layer_content_id_<?php echo $divrand; ?>', html: layerStates[i].layer.label});
					var second_td = geop_createEl({type: 'td', class: 'layer_content_class_<?php echo $divrand; ?> geop-layer-text-style', id: 'layer_content_id_<?php echo $divrand; ?>', style: 'padding-left:16px;', html: layerStates[i].layer.label});
					var third_td = geop_createEl({type: 'td', class: 'geop-no-border geop-table-pad geop-layer-right-sixteen-pad'});
					var info_link = geop_createEl({type: 'a', class: 'geop-layer-black-float geop-text-button geop-hidden-link', title: 'View this layer of <?php echo $a['name']; ?> in the Object Viewer.', style: "color:black;", href: '<?php echo $geop_oe_url; ?>/view/' + layerStates[i].layer_id, target: "_blank"})
					var info_icon = geop_createEl({type: 'span', class: '<?php echo $geop_info_icon ?>'});

					// With all elements created, they are appended to each other in the
					// desired order before attachement to the layer menu.
					check_button.appendChild(check_icon);
					first_td.appendChild(check_button);
					table_row.appendChild(first_td);
					info_link.appendChild(info_icon);
					third_td.appendChild(info_link);
					table_row.appendChild(second_td);
					table_row.appendChild(third_td);
					main_table.appendChild(table_row);
					document.getElementById('layerbox_<?php echo $divrand; ?>').appendChild(main_table);
				}

				// Layer toggle detector and executor. Must be put placed here as the
				// elements involved cannot be manipulated outside of the promise stack.
				jQuery('.layer_button_class_<?php echo $divrand; ?>').click(function(){
					jQuery(this).attr('opac', 1 - jQuery(this).attr('opac'));
					mapInstance.updateLayerOpacity(jQuery(this).attr('text'), jQuery(this).attr('opac'));
					jQuery(this).children().toggleClass('<?php echo $geop_check_icon . " " . $geop_uncheck_icon ?>');
				});
			}
			else{

				// If there are no layers to the map, the layer header div for that map
				// is modified to express such.
				jQuery("#layer_head_text_<?php echo $divrand; ?>").text("This map has no layers.");
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
			if(atts.span)
				new_el.setAttribute('span', atts.span);
			if(atts.opac)
				new_el.setAttribute('opac', atts.opac);
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
