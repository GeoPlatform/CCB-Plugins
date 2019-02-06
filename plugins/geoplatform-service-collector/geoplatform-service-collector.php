<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.imagemattersllc.com
 * @since             1.0.0
 * @package           Geoplatform_Service_Collector
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Service Collector
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Display your data from the GeoPlatform portfolio in a carousel format.
 * Version:           1.0.0
 * Author:            Image Matters LLC
 * Author URI:        https://www.imagemattersllc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geoplatform-service-collector
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
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEOSERVE_PLUGIN', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-geoplatform-service-collector-activator.php
 */
function activate_geoplatform_service_collector() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-service-collector-activator.php';
	Geoplatform_Service_Collector_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-geoplatform-service-collector-deactivator.php
 */
function deactivate_geoplatform_service_collector() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-service-collector-deactivator.php';
	Geoplatform_Service_Collector_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_geoplatform_service_collector' );
register_deactivation_hook( __FILE__, 'deactivate_geoplatform_service_collector' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-geoplatform-service-collector.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_geoplatform_service_collector() {

	$plugin = new Geoplatform_Service_Collector();
	$plugin->run();

}
run_geoplatform_service_collector();


// The Service Collector output operates by using a shortcode invocation of a
// carousel. This is handled below.
function geopserve_com_shortcodes_creation($geopserve_atts){

	?><script type="text/javascript">
		jQuery(document).ready(function() {

			// Button color controls, because the CSS doesn't work for plugins. On
			// click, active classes are removed from all buttons, then granted to the
			// button that was clicked.
			jQuery(".geopserve-carousel-button-base").click(function(event){
				jQuery(".geopserve-carousel-button-base").removeClass("geopserve-carousel-active active");
				jQuery(this).addClass("geopserve-carousel-active active");
			});

			// Search functionality trigger on button click.
			jQuery(".geopportal_port_community_search_button").click(function(event){
				var geopportal_grabs_from = jQuery(this).attr("grabs-from");
				var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
				window.open(
					"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
					'_blank'
				);
			});

			// Search functionality trigger on pressing enter in search bar.
			jQuery( ".geopportal_port_community_search_form" ).submit(function(event){
				event.preventDefault();
				var geopportal_grabs_from = jQuery(this).attr("grabs-from");
				var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
				window.open(
					"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
					'_blank'
				);
			});
		});
	</script><?php



	// Establishes a base array with default values required for shortcode creation
	// and overwrites them with values from $geoserve_atts.
  $geoserve_shortcode_array = shortcode_atts(array(
		'title' => '',
    'id' => '',
    'cat' => 'TFFFFFF',
		'count' => '6',
		'hide' => 'F',
  ), $geopserve_atts);
  ob_start();

	// Checks the "cat" shortcode value char by char, populating the generation array
	// with sub-arrays of constants for each asset type.
	$geoserve_generation_array = array();
	if (substr(($geoserve_shortcode_array['cat']), 0, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Data',
				'search' => 'Search for associated datasets',
				'query' => '&types=dcat:Dataset&q=',
				'uri' => 'https://ual.geoplatform.gov/api/datasets/',
				'type' => 'datasets',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/dataset.svg',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 1, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Services',
				'search' => 'Search for associated services',
				'query' => '&types=regp:Service&q=',
				'uri' => 'https://ual.geoplatform.gov/api/services/',
				'type' => 'services',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/service.svg',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 2, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Layers',
				'search' => 'Search for associated layers',
				'query' => '&types=Layer&q=',
				'uri' => 'https://ual.geoplatform.gov/api/layers/',
				'type' => 'layers',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/layer.svg',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 3, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Maps',
				'search' => 'Search for associated maps',
				'query' => '&types=Map&q=',
				'uri' => 'https://ual.geoplatform.gov/api/maps/',
				'type' => 'maps',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/map.svg',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 4, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Galleries',
				'search' => 'Search for associated galleries',
				'query' => '&types=Gallery&q=',
				'uri' => 'https://ual.geoplatform.gov/api/galleries/',
				'type' => 'galleries',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/gallery.svg',
			)
		);
	}

	// Required inclusion for detecting if the Item Details plugin is active.
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Default image.
	$geopserve_disp_thumb = plugin_dir_url(__FILE__) . 'public/assets/sample_1.jpg';


	// CAROUSEL CONSTRUCTION BEGINS
	// Everywhere that 'hide' is checked is indicitive of an option that strips out
	// the titles of the carousel and each entry, as well as the search bar. This
	// Cuts it down to just the panel outputs and buttons.

	if ($geoserve_shortcode_array['hide'] != 'T')
		echo "<div class='m-article'>";
	else
		echo "<div class='m-article' style='border-bottom:0px'>";

	// Optional title display
	if (!empty($geoserve_shortcode_array['title'])){
		?>
    <div class="m-article__heading">
        <?php echo $geoserve_shortcode_array['title'] ?>
    </div>
    <br>
	<?php }	?>

	<div class="carousel slide" data-ride="carousel" data-interval="false" id="geopserve_community_anchor_carousel">

		<?php
		// Generates the top buttons, but only if there are at least two data
		// types to provide output for.
		if (sizeof($geoserve_generation_array) > 1){ ?>
		  <ol class="geopserve-carousel-button-parent carousel-indicators">
				<?php
				for ($i = 0; $i < sizeof($geoserve_generation_array); $i++){
					if ($i == 0){ ?>
						<li data-target="#geopserve_community_anchor_carousel" data-slide-to="<?php echo $i ?>" class="geopserve-carousel-button-base geopserve-carousel-active active" title="<?php echo $geoserve_generation_array[$i]['button'] ?>"><?php echo $geoserve_generation_array[$i]['button'] ?></li>
					<?php } else { ?>
						<li data-target="#geopserve_community_anchor_carousel" data-slide-to="<?php echo $i ?>" class="geopserve-carousel-button-base" title="<?php echo $geoserve_generation_array[$i]['button'] ?>"><?php echo $geoserve_generation_array[$i]['button'] ?></li>
					<?php }
				} ?>
		  </ol>
		<?php } ?>

		<div class="carousel-inner">

			<?php
			// Carousel block creation. Sets the first created data type to the
			// active status, then produces the remaining elements.
			for ($i = 0; $i < sizeof($geoserve_generation_array); $i++){

				// Item Details plugin detection. If found, will pass off the relevant
				// redirected url to the function. If not, it will set it to OE.
				$geoserve_redirect_url = "https://oe.geoplatform.gov/view/";
				if ( is_plugin_active('geoplatform-item-details/geoplatform-item-details.php') )
					$geoserve_redirect_url = home_url() . "/resources/" . $geoserve_generation_array[$i]['type'] . "/";

				// Sets the first part of the carousel as active.
				if ($i == 0)
					echo "<div class='carousel-item active'>";
				else
					echo "<div class='carousel-item'>";

					// Displays the current carousel item title, if not hidden.
					if ($geoserve_shortcode_array['hide'] != 'T'){
						echo "<div class='m-article'>";
						echo "<div class='m-article__heading' style='text-align:center;'>Recent " . $geoserve_generation_array[$i]['type'] . "</div>";
					}?>

					<div class="m-article__desc">
				    <div class="d-grid d-grid--3-col--lg" id="geopserve_carousel_gen_div_<?php echo $i ?>">

							<!-- Carousel pane generation script. -->
							<script type="text/javascript">
								geopserve_gen_carousel("<?php echo $geoserve_shortcode_array['id'] ?>", "<?php echo $geoserve_generation_array[$i]['button'] ?>", <?php echo $geoserve_shortcode_array['count'] ?>, <?php echo $i ?>, "<?php echo $geoserve_generation_array[$i]['thumb'] ?>", "<?php echo $geoserve_generation_array[$i]['uri'] ?>", "<?php echo $geoserve_redirect_url ?>", "<?php echo $geoserve_shortcode_array['hide'] ?>");
							</script>
			      </div>

						<?php
						// Generates and outputs the search bar if not hidden.
						if ($geoserve_shortcode_array['hide'] != 'T'){?>
			        <div class="u-mg-top--xlg d-flex flex-justify-between flex-align-center">
				        <form class="input-group-slick flex-1 geopportal_port_community_search_form" grabs-from="geopportal_community_<?php echo $geoserve_generation_array[$i]['button'] ?>_search">
				          <span class="icon fas fa-search"></span>
				          <input type="text" class="form-control" id="geopportal_community_<?php echo $geoserve_generation_array[$i]['button'] ?>_search"
				              query-prefix="/#/?communities=<?php echo $geoserve_shortcode_array['id'] . $geoserve_generation_array[$i]['query'] ?>"
				              aria-label="<?php echo $geoserve_generation_array[$i]['search'] ?>" placeholder="<?php echo $geoserve_generation_array[$i]['search'] ?>">
				        </form>
				        <button class="u-mg-left--lg btn btn-secondary geopportal_port_community_search_button" grabs-from="geopportal_community_<?php echo $geoserve_generation_array[$i]['button'] ?>_search">SEARCH <?php echo strtoupper($geoserve_generation_array[$i]['button']) ?></button>
				      </div>
						<?php } ?>
				  </div>

				<!-- Closes the carousel heading div if necessary. -->
				<?php if ($geoserve_shortcode_array['hide'] != 'T'){ echo "</div>"; } ?>
			  </div>

			<?php } ?>

		  </div>
		</div>
	</div>



	<?php
	return ob_get_clean();
}

// Adds the shortcode hook to init.
function geopserve_com_shortcodes_init()
{
    add_shortcode('geopserve', 'geopserve_com_shortcodes_creation');
}
add_action('init', 'geopserve_com_shortcodes_init');

// AJAX handling only seems to function properly if both the hooks and PHP
// functions are placed in this file. Instead of producing clutter, the files
// that perform the settings interface add and remove map operations are simply
// included here.
function geopserve_process_addition() {
	include 'admin/partials/geoplatform-service-collector-admin-add.php';
	wp_die();
}

function geopserve_process_removal() {
	include 'admin/partials/geoplatform-service-collector-admin-remove.php';
  wp_die();
}

// Adds ajax hooks for add and remove operations in the admin menu.
add_action('wp_ajax_geopserve_remove_action', 'geopserve_process_removal');
add_action('wp_ajax_geopserve_add_action', 'geopserve_process_addition');
?>
