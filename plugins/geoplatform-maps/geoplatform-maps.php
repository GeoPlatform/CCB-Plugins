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
 * @since             1.1.2
 * @package           Geop_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Maps
 * Plugin URI:        www.geoplatform.gov
 * Description:       Manage your own personal database of GeoPlatform interactive maps and use shortcode to insert them into your posts.
 * Version:           1.1.2
 * Author:            Image Matters LLC: Lee Heazel
 * Author URI:        http://www.imagemattersllc.com
 * License:           Apache 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       geoplatform-maps
 * Domain Path:       /languages
 *
 *
 *
 * Copyright 2018 Image Matters LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
define( 'GEOPMAP_PLUGIN', '1.1.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geoplatform-maps-activator.php
 */
function activate_geop_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-maps-activator.php';
	Geop_Maps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-maps-deactivator.php
 */
function deactivate_geop_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-maps-deactivator.php';
	Geop_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geop_maps' );
register_deactivation_hook( __FILE__, 'deactivate_geop_maps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-maps.php';

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
function geopmap_shortcode_creation($geopmap_atts){

	// Establishes a base array with default values required for shortcode creation
	// and overwrites them with values from $geopmap_atts.
  $geopmap_shortcode_array = shortcode_atts(array(
    'id' => '62c29fe8103c713904d23b8354ba41c8',
    'name' => '',
    'url' => '',
		'width' => '0',
		'height' => '0',
		'use' => 'page',
  ), $geopmap_atts);
  ob_start();

  // GeoPlatform theme detection. Checks whether or not the active theme is a
	// GeoPlatform theme and if so sets to true. Right now it's only used to switch
	// from Font Awesome icons to glyphicons and adjust some text sizes. More
	// functionality may be included in the future.
	$geopmap_theme = 'F';
	if (wp_get_theme()->get('Name') == 'Geoplatform Portal 4.0')
		$geopmap_theme = 'T';

	// Creates an empty error text report string, grabs the map_id string after
	// sanitation, and creates the ual_url string.
	$geopmap_error_text = '';
	$geopmap_map_id_in = sanitize_key($geopmap_shortcode_array['id']);
	$geopmap_ual_url_in = 'https://ual.geoplatform.gov/api/maps/';
	$geopmap_maps_url = 'https://maps.geoplatform.gov';

	// Verifies validity of map_id_in and concats it to ual_url_in, forming the
	// full ual string to the map.
	if (!ctype_xdigit($geopmap_map_id_in) || strlen($geopmap_map_id_in) != 32)
		$geopmap_error_text .= "Your map ID is in an invalid format. Please check your map ID and try again.<BR>";
	else
		$geopmap_ual_url_in .= $geopmap_map_id_in;


  // Uses the map ID provided to grab the map data from the GeoPlatform site and
	// decode it into usable JSON info. Produces a bum result and error text if it
	// fails.
	$geopmap_link_scrub = wp_remote_get( ''.$geopmap_ual_url_in.'', array( 'timeout' => 120, 'httpversion' => '1.1' ) );
	$geopmap_response = wp_remote_retrieve_body( $geopmap_link_scrub );
	if(!empty($geopmap_response))
	  $geopmap_result = json_decode($geopmap_response, true);
	else{
	  $geopmap_result = "This Gallery has no recent activity. Try adding some maps!";
		$geopmap_error_text .= "The GeoPlatform server could not be contacted to verify this map.<BR>";
	}

	// Invalid map ID check. A faulty map ID will return a generic JSON dataset
	// from GeoPlatform with a statusCode entry containing "404" code. This will
	// add text to $geopmap_error_text, which will be used for error reporting later.
	if (array_key_exists('statusCode', $geopmap_result) && $geopmap_result['statusCode'] == "404")
	  $geopmap_error_text .= "Your map ID could not be found on the GeoPlatform server. Please check your map ID and try again.<BR>";
	if (array_key_exists('statusCode', $geopmap_result) && $geopmap_result['statusCode'] >= "500" && $geopmap_result['statusCode'] < "600")
	  $geopmap_error_text .= "The map service provider could not be contacted. Please try again later.<BR>";

	// Includes the plugin.php file so that active plugins can be checked public-side.
	include_once(ABSPATH.'wp-admin/includes/plugin.php');

	// The JSON info grabbed is checked for a value found only in AGOL maps. If it
	// is found, the landing page value is pulled from the JSON and the process
	// proceeds with agol map generation. Otherwise, the geop method is called.
	// AGOL maps aren't typically hosted on the GeoPlatform maps server, so a
	// landing page is needed. If the json doesn't contain a landing page, the
	// hotlink is set to the GEOP maps server to handle the request.
	if (array_key_exists('resourceTypes', $geopmap_result) && $geopmap_result['resourceTypes'][0] == "http://www.geoplatform.gov/ont/openmap/AGOLMap"){
		$geopmap_landing_page = '';
		if (array_key_exists('landingPage', $geopmap_result) && isset($geopmap_result['landingPage']))
			$geopmap_landing_page = $geopmap_result['landingPage'];
		else
			$geopmap_landing_page = $geopmap_maps_url . '/map.html?id=' . $geopmap_map_id_in;
		geopmap_agol_gen($geopmap_shortcode_array, $geopmap_error_text, $geopmap_landing_page, $geopmap_theme);
	}
	else
		geopmap_geop_gen($geopmap_shortcode_array, $geopmap_error_text, $geopmap_theme);
	return ob_get_clean();
}


/** Method for agol map display in a GeoPlatform theme environment.
 *
 *  #param $geopmap_shortcode_array: array of information captured from the shortcode string.
 *  #param $geopmap_error_text: string of error text passed in, preferably empty.
 *  #param $geopmap_landing_page: url that the map links to on the GeoPlatform maps page.
 *  #param $geopmap_theme: a 'T' or 'F' value reflecting whether or not a GeopPlatform theme is in use.
*/
function geopmap_agol_gen($geopmap_shortcode_array, $geopmap_error_text, $geopmap_landing_page, $geopmap_theme){

	// Random number generation to give this instance of objects unique element IDs.
	// Also declares GeoPlatform url fields.
	$geopmap_divrand = rand(0, 99999);
	$geopmap_ual_url = 'https://ual.geoplatform.gov';
	$geopmap_maps_url = 'https://maps.geoplatform.gov';
	?>

<!-- Main div block that will contain this entry. It has a constant width as
 	   determined by the page layout on load, so its width is set to the widthGrab
	 	 variable. -->
	<div id="master_<?php echo $geopmap_divrand; ?>" style="clear:both;">
		<script>
			var widthGrab = jQuery('#master_<?php echo $geopmap_divrand ?>').width();
		</script>

<!-- This is the div block that defines the output proper. It defines the width
 		 of the visible map and title card, and contains those elements. Its values
		 are set initially to those of width as passed by array. The contents of the
	 	 entire div also act as a hyperlink, set here-->
	  <div class="geop-display-main" id="middle_<?php echo $geopmap_divrand; ?>" style="width:<?php echo esc_attr($geopmap_shortcode_array['width']); ?>px; height:<?php echo esc_attr($geopmap_shortcode_array['height']); ?>px;">

<!-- Name, link, and layer control card. Provides a link to the map with the
		 title text, link to the object editor with the info icon link, and has a
		 button disguised as an image that toggles layer control sidebar visibility. -->
		 	<?php
		 	$geopmap_redirect_item_details = esc_url($geopmap_landing_page);
		 	if ( is_plugin_active( 'geoplatform-item-details/geoplatform-item-details.php' ) )
		 		$geopmap_redirect_item_details = home_url() . "/resources/maps/" . esc_attr($geopmap_shortcode_array['id']);
		 	?>
			<div class="geop-container-controls" id="image_<?php echo $geopmap_divrand; ?>" src="" alt="Thumbnail failed to load" style="background-image: url( <?php echo $geopmap_ual_url . '/api/maps/' . esc_attr($geopmap_shortcode_array['id']) . '/thumbnail' ?> );" onerror="geopmap_thumb_error(this);"></div>
		 	<div class="geop-title-grad-div" id="title_<?php echo $geopmap_divrand ?>">
		 		<a href="<?php echo $geopmap_redirect_item_details; ?>" target="_blank" class="geop-map-title-text">
		 			<span class="t-fg--white geop-hidden-link"><?php echo $geopmap_shortcode_array['name']; ?></span>
		 		</a>
		 		<div>

					<?php
					if ( is_plugin_active( 'geoplatform-item-details/geoplatform-item-details.php' ) ){
						echo "<a href='" . home_url() . "/resources/maps/" . esc_attr($geopmap_shortcode_array['id']) . "' target='_blank' class='geop-sub-buttons btn btn-light btn-sm'>";
						echo "<span style='color: #212529'>View Details</span></a>";
					}?>

		 			<a href="<?php echo esc_url($geopmap_landing_page); ?>" target="_blank" class="btn btn-info btn-sm geop-sub-buttons">
		 				<span>Open Map</span>
		 			</a>
		 		</div>
		 	</div>

 <!-- Error report container with heading, an empty output region, and a button
	 		to close it disguised as text. 4f97782131ca3e1fbdeea2bccc8946d7 1a827dc62e09d08a834f22bf3d67b720 -->
			<div class="geop-error-box" id="errorbox_<?php echo $geopmap_divrand; ?>" style="width:<?php echo esc_attr($geopmap_shortcode_array['width']); ?>px;">
				<p class="t-fg--white geop-heavy-text geop-sixteen-text geop-error-bottom-eight-marg">An Error Has Occurred</p>
		 		<p class="t-fg--white geop-error-report geop-sixteen-text geop-error-bottom-twelve-marg" id="errorout_<?php echo $geopmap_divrand; ?>"></p>
		 		<button class="t-fg--white geop-no-transform geop-right-marg-float geop-text-button geop-sixteen-text" id="errorclose_<?php echo $geopmap_divrand; ?>">Dismiss</button>
		 	</div>
	  </div>
	</div>

<!-- Javascript region. -->
	<script>
	jQuery('document').ready(function(){

		// Error report string grabbing data from the $geopmap_error_text argument.
		var geopmap_error_report = "<?php echo $geopmap_error_text ?>";

		// Verifies if the thumbnail exists and adds to the error report if not.
		jQuery.get("<?php echo $geopmap_maps_url ?>/map.html?id=<?php echo esc_attr($geopmap_shortcode_array['id']); ?>").fail(function(err){
			geopmap_error_report += "The thumbnail image for this map failed to load or does not exist.<BR>";
		})

		// Scaling code. If this page element does not have custom-set width or
		// height. If the user did not specify a width or set a width too wide for
		// its container, this check sets the width instead to 100% of the master
		// div. Height is also checked for no entry, and set to 56% of the master
		// div's width.
		if (<?php echo esc_attr($geopmap_shortcode_array['width']); ?> == 0 || <?php echo esc_attr($geopmap_shortcode_array['width']); ?> > widthGrab){
			jQuery('#middle_<?php echo $geopmap_divrand; ?>').width('98%');
			jQuery('#errorbox_<?php echo $geopmap_divrand; ?>').width('100%');
		}
		if (<?php echo esc_attr($geopmap_shortcode_array['height']); ?> == 0){
			jQuery('#middle_<?php echo $geopmap_divrand; ?>').height(widthGrab * 0.56);
		}

		// Error report handler. If there is content in error_report, that string
		// is set to the error output in the error div. Otherwise, that div is
		// hidden.
		if (geopmap_error_report){
			jQuery('#errorout_<?php echo $geopmap_divrand; ?>').html(geopmap_error_report);
			jQuery('#title_<?php echo $geopmap_divrand; ?>').hide();
		}
		else
			jQuery('#errorbox_<?php echo $geopmap_divrand; ?>').hide();

		// Hiding control for the error div, sliding it down when the user clicks
		// the dismiss button/text.
		jQuery('#errorclose_<?php echo $geopmap_divrand; ?>').click(function(){
			jQuery('#errorbox_<?php echo $geopmap_divrand; ?>').slideToggle();
			jQuery('#title_<?php echo $geopmap_divrand; ?>').slideToggle();
		});

		// If the map is valid but for some reason does not possess a thumbnail, this
	  // method is called and will supply a local default borrowed from the sit Map
	  // Viewer site.
	  function geopmap_thumb_error(geopmap_image_in){
	    geopmap_image_in.onerror = "";
	    geopmap_image_in.src = "<?php echo plugin_dir_url(__FILE__) ?>includes/img-404.png";
			geopmap_error_report += "The thumbnail image for this map failed to load or does not exist.<BR>";
	    return true;
	  }
	})
	</script>
<?php
}

/** Method for geop map display. Much more dynamic than the agol map generator.
*
*  #param $geopmap_shortcode_array: array of information captured from the shortcode string.
*  #param $geopmap_error_text: string of error text passed in, preferably empty.
*  #param $geopmap_theme: a 'T' or 'F' value reflecting whether or not a GeopPlatform theme is in use.
*/
function geopmap_geop_gen($geopmap_shortcode_array, $geopmap_error_text, $geopmap_theme){

	// Generates the random number used for unique element referencing. Also sets
	// up the URL fields.
	$geopmap_divrand = rand(0, 99999);
	$geopmap_viewer_url = 'https://viewer.geoplatform.gov';
	$geopmap_oe_url = 'https://oe.geoplatform.gov';

	// Variables that vary among themes. They are set to default values for work
	// in the GeoPlatform themes, then changed if one such theme is absent.
	$geopmap_info_icon = 'fa fa-info-circle';
	$geopmap_base_icon = 'fa';
	$geopmap_check_icon = 'fa-check-square';
	$geopmap_uncheck_icon = 'fa-square';
	$geopmap_layer_icon = 'icon-layer';

	if ($geopmap_theme == 'F'){
		$geopmap_layer_icon = 'fas fa-bars';
	}
	?>

<!-- Main div block that will contain this entry. It has a constant width as
 	   determined by the page layout on load, so its width is set to the widthGrab
	 	 variable. -->
	<div id="master_<?php echo $geopmap_divrand; ?>" style="clear:both;">
		<script>
			var widthGrab = jQuery('#master_<?php echo $geopmap_divrand ?>').width();
		</script>

<!-- This is the div block that defines the output proper. It defines the width
 		 of the visible map and title card, and contains those elements. Its values
		 are set initially to those of the width as passed by array.-->
	  <div class="geop-display-main" id="middle_<?php echo $geopmap_divrand; ?>" style="width:<?php echo $geopmap_shortcode_array['width']; ?>px;">

 <!-- Name, link, and layer control card. Provides a link to the map with the
 			title text and link to the object editor with the info icon link. -->
			<?php
			$geopmap_redirect_item_details = $geopmap_viewer_url . '/?id=' . esc_attr($geopmap_shortcode_array['id']);
			if ( is_plugin_active( 'geoplatform-item-details/geoplatform-item-details.php' ) )
				$geopmap_redirect_item_details = home_url() . "/resources/maps/" . esc_attr($geopmap_shortcode_array['id']);
			?>
			<div class="geop-title-grad-div" id="title_<?php echo $geopmap_divrand ?>">
					<a href="<?php echo $geopmap_redirect_item_details; ?>" target="_blank" class="geop-map-title-text geop-text-shadow">
						<span class="t-fg--white geop-hidden-link"><?php echo $geopmap_shortcode_array['name']; ?></span>
					</a>
					<div>

						<?php
						if (esc_attr($geopmap_shortcode_array['use']) != 'featured'){
							echo "<button id='layer_menu_button_" . $geopmap_divrand . "' class='geop-sub-buttons btn btn-light btn-sm' style='margin-right:.5em;'>";
								echo "<span class='geop-redirect-icon t-fg--selected " . $geopmap_layer_icon . "'></span>";
							echo "</button>";
						}

						if ( is_plugin_active( 'geoplatform-item-details/geoplatform-item-details.php' ) ){
							echo "<a href='" . home_url() . "/resources/maps/" . esc_attr($geopmap_shortcode_array['id']) . "' target='_blank' class='geop-sub-buttons btn btn-light btn-sm'>";
							echo "<span style='color: #212529'>View Details</span></a>";
						}?>

						<a href="<?php echo $geopmap_viewer_url . '/?id=' . esc_attr($geopmap_shortcode_array['id']); ?>" target="_blank" class="geop-sub-buttons btn btn-info btn-sm">
							<span>Open Map</span>
						</a>
					</div>
			</div>

 <!-- The container that will hold the leaflet map. Also defines entree height. -->
			<div class="geop-container-controls" id="container_<?php echo $geopmap_divrand; ?>" style="height:<?php echo esc_attr($geopmap_shortcode_array['height']); ?>px;"></div>

 <!-- Layer control container. Provides the base container that holds the layer
 			controls generated later, and populates it with its first box, a simple
			informational header. -->
			<div class="geop-layer-menu" id="layerbox_<?php echo $geopmap_divrand; ?>" style="height:<?php echo esc_attr($geopmap_shortcode_array['height']); ?>px;">
				<div class="geop-layer-box" id="layer_header_<?php echo $geopmap_divrand; ?>" style="width:100%">
					<p class="geop-caption-text geop-no-transform" id="layer_head_text_<?php echo $geopmap_divrand; ?>">Layer Menu</p>
				</div>
			</div>

 <!-- Error report container with heading, an empty output region, and a button
 			to close it disguised as text. The output region has the possibility to be
		 	filled later if errors are found. -->
			<div class="geop-error-box" id="errorbox_<?php echo $geopmap_divrand; ?>">
				<p class="t-fg--white geop-heavy-text geop-sixteen-text geop-error-bottom-eight-marg">An Error Has Occurred</p>
				<p class="t-fg--white geop-error-report geop-sixteen-text geop-error-bottom-twelve-marg" id="errorout_<?php echo $geopmap_divrand; ?>"></p>
				<button class="t-fg--white geop-no-transform geop-right-marg-float geop-text-button geop-sixteen-text" id="errorclose_<?php echo $geopmap_divrand; ?>">Dismiss</button>
			</div>
  	</div>
	</div>

<!-- Javascript region. -->
	<script>
	jQuery('document').ready(function(){

		// Error report string, which will be filled for display if necessary.
		var geopmap_error_report = "<?php echo $geopmap_error_text ?>";

		// Checks whether the item details plugin is active. If so, sets layer links
		// to be directed towards that interface. Otherwise, goes to OE.
		var geopmap_item_details_check = "<?php echo is_plugin_active( 'geoplatform-item-details/geoplatform-item-details.php' ); ?>";
		geopmap_item_details_base = '<?php echo $geopmap_oe_url; ?>' + "/view/";
		if (geopmap_item_details_check)
			geopmap_item_details_base = '<?php echo home_url() ?>' + "/resources/layers/";


		// Scaling code. If this page element does not have custom-set width or
		// height. If the user did not specify a width or set a width too wide
		// for its container, this check sets the width instead to 100% of the
		// master div. Height is also checked for no entry, and set to 75% of
		// the master div's width.
		if (<?php echo esc_attr($geopmap_shortcode_array['width']); ?> == 0 || <?php echo esc_attr($geopmap_shortcode_array['width']); ?> > widthGrab)
			jQuery('#middle_<?php echo $geopmap_divrand; ?>').width('98%');
		if (<?php echo esc_attr($geopmap_shortcode_array['height']); ?> == 0){
			jQuery('#container_<?php echo $geopmap_divrand; ?>').height(widthGrab * 0.56);
			jQuery('#layerbox_<?php echo $geopmap_divrand; ?>').height(widthGrab * 0.56);
		}
		if ('<?php echo esc_attr($geopmap_shortcode_array['use']); ?>' == 'featured'){
			jQuery('#container_<?php echo $geopmap_divrand; ?>').height('100%');
		}
		if (jQuery('#middle_<?php echo $geopmap_divrand; ?>').width() <= 400)
			jQuery('#layer_menu_button_<?php echo $geopmap_divrand; ?>').hide();

		// Javascript block that creates the leaflet map container, with GeopMapInstance
		// being our GeoPlatform map. This section is wrapped in a try-catch block
		// to catch any errors that may come. As of now, it's a pure error output
		// concatenated to error_report.
		var GeopMapInstance;
		try {
			var geopmap_lat = 38.8282;
			var geopmap_lng = -98.5795;
			var geopmap_zoom = 3;
			var geopmap_mapCode = "<?php echo esc_attr($geopmap_shortcode_array['id']); ?>";
			var geopmap_leafBase = L.map("container_<?php echo $geopmap_divrand;?>", {
				minZoom: 2,
				maxZoom: 21,
				attributionControl: false
			});

			// Instantiates GeopMapInstance, passing the leaflet map and view constants.
			GeopMapInstance = GeoPlatform.MapFactory.get();
			GeopMapInstance.setMap(geopmap_leafBase);
			GeopMapInstance.setView(51.505, -0.09, 13);

      // Actual attribute setting function including layer grab function, which
			// cycles through the layers and populates the layer control sidebar. Also
			// has error catching to grab errors through the promise system.
			var geopmap_layerStates;
			GeopMapInstance.loadMap(geopmap_mapCode).then( function(){
				var geopmap_baseLayer = GeopMapInstance.getBaseLayer();
				geopmap_layerStates = GeopMapInstance.getLayers();
				geop_layer_control_gen(GeopMapInstance, geopmap_layerStates, geopmap_item_details_base);
			}).catch( function(error){
				geopmap_error_report += (error + "<BR>");
			})
		}
		catch(err){
			geopmap_error_report += (err + "<BR>");
		}

    /** Function for generating the layer control sidebar entries. For each layer
		*  found, it creates a series of HTML elements using the geopmap_createEl()
		*  method and appends them into a DOM heirarchy before adding them to the
		*  layerbox div. Layer toggling outside of the GeopPlatform themes currently
		*	 does not function, so the checkboxes have been removed and the layer name
		*  output given left padding in such cases.
		*
		*  #param GeopMapInstance: the GeoPlatform map instance being referenced.
		*  #param geopmap_layerStates: the layers from that map instance.
		*  #param geopmap_item_details_base: string for the base to which the layer link will be redirected.
		*/
		function geop_layer_control_gen(GeopMapInstance, geopmap_layerStates, geopmap_item_details_base){

			// Checks to ensure that there are layers to process. If so, cycles through
			// each layer and creates local variables in the form of html elements.
			if (geopmap_layerStates.length > 0){
				for (var i = 0; i < geopmap_layerStates.length; i++){
					var main_table = geopmap_createEl({type: 'table', class: 'geop-layer-box', style: 'width:100%'});
					var table_row = geopmap_createEl({type: 'tr', class: 'geop-no-border'});
					var first_td = geopmap_createEl({type: 'td', class: 'geop-no-border geop-table-pad'});
					var check_button = geopmap_createEl({type: 'button', class: 'geop-text-button layer_button_class_<?php echo $geopmap_divrand; ?>', id: 'layer_button_id_<?php echo $geopmap_divrand; ?>', style: 'width:auto', opac: '1.0', text: geopmap_layerStates[i].layer_id});
					var check_icon = geopmap_createEl({type: 'span', class: 'layer_button_icon_<?php echo $geopmap_divrand; ?> <?php echo $geopmap_base_icon . " " . $geopmap_check_icon ?>', style: 'color:black;'});
					var second_td = geopmap_createEl({type: 'td', class: 'layer_content_class_<?php echo $geopmap_divrand; ?> geop-layer-text-style', id: 'layer_content_id_<?php echo $geopmap_divrand; ?>', html: geopmap_layerStates[i].layer.label});
					var second_td = geopmap_createEl({type: 'td', class: 'layer_content_class_<?php echo $geopmap_divrand; ?> geop-layer-text-style', id: 'layer_content_id_<?php echo $geopmap_divrand; ?>', style: 'padding-left:16px;', html: geopmap_layerStates[i].layer.label});
					var third_td = geopmap_createEl({type: 'td', class: 'geop-no-border geop-table-pad geop-layer-right-sixteen-pad'});
					var info_link = geopmap_createEl({type: 'a', class: 'geop-layer-black-float geop-text-button geop-hidden-link', title: 'View this layer of <?php echo esc_attr($geopmap_shortcode_array['name']); ?> in detail.', style: "color:black; box-shadow:none;", href: geopmap_item_details_base + geopmap_layerStates[i].layer_id, target: "_blank"})
					var info_icon = geopmap_createEl({type: 'span', class: '<?php echo $geopmap_info_icon ?>'});

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
					document.getElementById('layerbox_<?php echo $geopmap_divrand; ?>').appendChild(main_table);
				}

				// Layer toggle detector and executor. Must be put placed here as the
				// elements involved cannot be manipulated outside of the promise stack.
				jQuery('.layer_button_class_<?php echo $geopmap_divrand; ?>').click(function(){
					jQuery(this).attr('opac', 1 - jQuery(this).attr('opac'));
					GeopMapInstance.updateLayerOpacity(jQuery(this).attr('text'), jQuery(this).attr('opac'));
					jQuery(this).children().toggleClass('<?php echo $geopmap_check_icon . " " . $geopmap_uncheck_icon ?>');
				});
			}
			else {

				// If there are no layers to the map, the layer header div for that map
				// is modified to express such.
				jQuery("#layer_head_text_<?php echo $geopmap_divrand; ?>").text("This map has no layers.");
			}
		}

		// Creates an HTML element and, using the arrays of string pairs passed here
		// from geop_layer_control_gen(), adds attributes to it that make it into a
		// functional element before returning it.
		function geopmap_createEl(geopmap_el_atts){
			geopmap_el_atts = geopmap_el_atts || {};
			var new_el = document.createElement(geopmap_el_atts.type);
			if(geopmap_el_atts.html)
				new_el.innerHTML = geopmap_el_atts.html;
			if(geopmap_el_atts.text)
				new_el.setAttribute('text', geopmap_el_atts.text);
			if(geopmap_el_atts.class)
				new_el.setAttribute('class', geopmap_el_atts.class);
			if(geopmap_el_atts.style)
				new_el.setAttribute('style', geopmap_el_atts.style);
			if(geopmap_el_atts.id)
				new_el.setAttribute('id', geopmap_el_atts.id);
			if(geopmap_el_atts.title)
				new_el.setAttribute('title', geopmap_el_atts.title);
			if(geopmap_el_atts.href)
				new_el.setAttribute('href', geopmap_el_atts.href);
			if(geopmap_el_atts.target)
				new_el.setAttribute('target', geopmap_el_atts.target);
			if(geopmap_el_atts.span)
				new_el.setAttribute('span', geopmap_el_atts.span);
			if(geopmap_el_atts.opac)
				new_el.setAttribute('opac', geopmap_el_atts.opac);
			return new_el;
		}

		// Show/hide toggle control for the layer div, showing or hiding it when the
		// user presses the layer view button.
		jQuery('#layer_menu_button_<?php echo $geopmap_divrand; ?>').click(function(){
			jQuery('#layerbox_<?php echo $geopmap_divrand; ?>').animate({ width: "toggle" });
		});

		// Error report handler. If there is content in error_report, that string
		// is set to the error output in the error div. Otherwise, that div is
		// hidden.
		if (geopmap_error_report){
			jQuery('#errorout_<?php echo $geopmap_divrand; ?>').html(geopmap_error_report);
			jQuery('#title_<?php echo $geopmap_divrand; ?>').hide();
		}
		else
			jQuery('#errorbox_<?php echo $geopmap_divrand; ?>').hide();

		// Hide control for the error div, sliding it down when the user clicks the
		// dismiss button/text.
		jQuery('#errorclose_<?php echo $geopmap_divrand; ?>').click(function(){
			jQuery('#errorbox_<?php echo $geopmap_divrand; ?>').slideToggle();
			jQuery('#title_<?php echo $geopmap_divrand; ?>').slideToggle();
		});
	})
	</script>
<?php
}

// Adds the shortcode hook to init.
function geopmap_shortcodes_init()
{
    add_shortcode('geopmap', 'geopmap_shortcode_creation');
}
add_action('init', 'geopmap_shortcodes_init');

// AJAX handling only seems to function properly if both the hooks and PHP
// functions are placed in this file. Instead of producing clutter, the files
// that perform the settings interface add and remove map operations are simply
// included here.
function geopmap_process_addition() {
	include 'admin/partials/geoplatform-maps-admin-add-map.php';
	wp_die();
}

function geopmap_process_removal() {
	include 'admin/partials/geoplatform-maps-admin-remove-map.php';
  wp_die();
}

// Adds ajax hooks for add and remove operations in the admin menu.
add_action('wp_ajax_geopmap_remove_action', 'geopmap_process_removal');
add_action('wp_ajax_geopmap_add_action', 'geopmap_process_addition');
?>
