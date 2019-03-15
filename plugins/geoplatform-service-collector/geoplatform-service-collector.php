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
 * @since             1.1.0
 * @package           Geoplatform_Service_Collector
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Asset Carousel
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Display your data from the GeoPlatform portfolio in a carousel format.
 * Version:           1.1.0
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
 * Start at version 1.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GEOSERVE_PLUGIN', '1.1.0' );

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
 * @since    1.1.0
 */
function run_geoplatform_service_collector() {

	$plugin = new Geoplatform_Service_Collector();
	$plugin->run();

}
run_geoplatform_service_collector();


// The Asset Carousel output operates by using a shortcode invocation of a
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
	// and overwrites them with values from $geopserve_atts.
  $geopserve_shortcode_array = shortcode_atts(array(
		'title' => '',
    'id' => '',
    'cat' => 'TFFFFFF',
		'count' => '6',
		'hide' => 'F',
  ), $geopserve_atts);
  ob_start();

	// Checks the "cat" shortcode value char by char, populating the generation array
	// with sub-arrays of constants for each asset type.
	$geopserve_generation_array = array();
	if (substr(($geopserve_shortcode_array['cat']), 0, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'title' => 'Datasets',
				'search' => 'Search for associated datasets',
				'query' => '&types=dcat:Dataset&q=',
				'uri' => '/api/datasets/',
				'type' => 'datasets',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/dataset.svg',
				'icon' => 'icon-dataset',
			)
		);
	}
	if (substr(($geopserve_shortcode_array['cat']), 1, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'title' => 'Services',
				'search' => 'Search for associated services',
				'query' => '&types=regp:Service&q=',
				'uri' => '/api/services/',
				'type' => 'services',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/service.svg',
				'icon' => 'icon-service',
			)
		);
	}
	if (substr(($geopserve_shortcode_array['cat']), 2, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'title' => 'Layers',
				'search' => 'Search for associated layers',
				'query' => '&types=Layer&q=',
				'uri' => '/api/layers/',
				'type' => 'layers',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/layer.svg',
				'icon' => 'icon-layer',
			)
		);
	}
	if (substr(($geopserve_shortcode_array['cat']), 3, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'title' => 'Maps',
				'search' => 'Search for associated maps',
				'query' => '&types=Map&q=',
				'uri' => '/api/maps/',
				'type' => 'maps',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/map.svg',
				'icon' => 'icon-map',
			)
		);
	}
	if (substr(($geopserve_shortcode_array['cat']), 4, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'title' => 'Galleries',
				'search' => 'Search for associated galleries',
				'query' => '&types=Gallery&q=',
				'uri' => '/api/galleries/',
				'type' => 'galleries',
				'thumb' => plugin_dir_url(__FILE__) . 'public/assets/gallery.svg',
				'icon' => 'icon-gallery',
			)
		);
	}

	// Required inclusion for detecting if the Item Details plugin is active.
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Default image and environment pull.
	$geopserve_disp_thumb = plugin_dir_url(__FILE__) . 'public/assets/sample_1.jpg';
	$geopserve_ual_domain = isset($_ENV['ual_url']) ? $_ENV['ual_url'] : 'https://ual.geoplatform.gov';

	// CAROUSEL CONSTRUCTION BEGINS
	// Everywhere that 'hide' is checked is indicitive of an option that strips out
	// the titles of the carousel and each entry, as well as the search bar. This
	// Cuts it down to just the panel outputs and buttons.

	// if ($geopserve_shortcode_array['hide'] != 'T')
	// 	echo "<div class='m-article'>";
	// else
	// 	echo "<div class='m-article' style='border-bottom:0px'>";
?>





	<div class='m-article'>
		<?php
		if (!empty($geopserve_shortcode_array['title'])){
			echo "<div class='m-article__heading'>";
			echo "Explore " . $geopserve_shortcode_array['title'] . " Resources";
			echo "</div><br>";
		}	?>

    <div class="carousel slide" data-ride="carousel" data-interval="false" id="themeCarousel" id="geopserve_community_anchor_carousel">

			<?php
			// Generates the top buttons, but only if there are at least two data
			// types to provide output for.
			if (sizeof($geopserve_generation_array) > 1){
					echo "<ol class='carousel-indicators u-mg-bottom--xlg'>";
					for ($i = 0; $i < sizeof($geopserve_generation_array); $i++){
						if ($i == 0){
							echo "<li data-target='#geopserve_community_anchor_carousel' data-slide-to='" . $i . "' class='carousel-indicators geopserve-carousel-button-base geopserve-carousel-active active' title='" . $geopserve_generation_array[$i]['title'] . "'>";
						} else {
							echo "<li data-target='#geopserve_community_anchor_carousel' data-slide-to='" . $i . "' class='carousel-indicators geopserve-carousel-button-base' title='" . $geopserve_generation_array[$i]['title'] . "'>";
						}
						switch ($geopserve_generation_array[$i]['title']){
							case "Datasets":
								echo "<span class='icon-dataset'></span> Datasets </li>";
								break;
							case "Services":
								echo "<span class='icon-service'></span> Services </li>";
								break;
							case "Layers":
								echo "<span class='icon-layer'></span> Layers </li>";
								break;
							case "Maps":
								echo "<span class='icon-map'></span> Maps </li>";
								break;
							case "Galleries":
								echo "<span class='icon-gallery'></span> Galleries </li>";
								break;
						}
					}
			  echo "</ol>";
			}?>

			<!-- Icons are centered, not sure why it doesn't work above -->
			<!-- <ol class="carousel-indicators u-mg-bottom-/-xlg">
        <li data-target="#themeCarousel" data-slide-to="0" class="active" title="Data">
          <span class="icon-dataset"></span> Datasets
        </li>
        <li data-target="#themeCarousel" data-slide-to="1" title="Data Services">
          <span class="icon-service"></span> Services
        </li>
        <li data-target="#themeCarousel" data-slide-to="2" title="Layers">
          <span class="icon-layer"></span> Layers
        </li>
        <li data-target="#themeCarousel" data-slide-to="3" title="Open Maps">
          <span class="icon-map"></span> Maps
        </li>
      </ol> -->

			<?php
      echo "<div class='carousel-inner'>";

			// Carousel block creation. Sets the first created data type to the
			// active status, then produces the remaining elements.
			for ($i = 0; $i < sizeof($geopserve_generation_array); $i++){

				// Item Details plugin detection. If found, will pass off the relevant
				// redirected url to the function. If not, it will set it to OE.
				$geopserve_redirect_url = "https://oe.geoplatform.gov/view/";
				if ( is_plugin_active('geoplatform-item-details/geoplatform-item-details.php') )
					$geopserve_redirect_url = home_url() . "/" . "resources/" . $geopserve_generation_array[$i]['type'] . "/";

				// Sets the first part of the carousel as active.
				if ($i == 0)
					echo "<div class='carousel-item active'>";
				else
					echo "<div class='carousel-item'>";

				echo "<div class='m-article'>";

				// Displays the current carousel item title, if not hidden.
				if ($geopserve_shortcode_array['hide'] != 'T')
					echo "<div class='m-article__heading u-text--sm'>Recent " . $geopserve_generation_array[$i]['type'] . "</div>";

				echo "<div class='m-article__desc'>";
				echo "<div class='m-results' id='geopserve_carousel_gen_div_" . $i . "'>";

				?>
		<!-- Carousel pane generation script. -->
				<script type="text/javascript">
					var geopserve_community_id = "<?php echo $geopserve_shortcode_array['id'] ?>";
					var geopserve_asset_name = "<?php echo $geopserve_generation_array[$i]['title'] ?>";
					var geopserve_result_count = "<?php echo $geopserve_shortcode_array['count'] ?>";
					var geopserve_iter = "<?php echo $i ?>";
					var geopserve_icon = "<?php echo $geopserve_generation_array[$i]['icon'] ?>";
					var geopserve_ual_domain = "<?php echo $geopserve_ual_domain ?>";
					var geopserve_ual_endpoint = "<?php echo $geopserve_generation_array[$i]['uri'] ?>";
					var geopserve_redirect = "<?php echo $geopserve_redirect_url ?>";
					var geopserve_new_tab = "<?php echo $geopserve_shortcode_array['hide'] ?>";
					var geopserve_fourohfour = "<?php echo plugin_dir_url(__FILE__) . 'public/assets/img-404.png' ?>";

					geopserve_gen_list(geopserve_community_id, geopserve_asset_name, geopserve_result_count, geopserve_iter, geopserve_icon,
						geopserve_ual_domain, geopserve_ual_endpoint, geopserve_redirect, geopserve_new_tab, geopserve_fourohfour);
				</script>



							<?php
							// Generates and outputs the search bar if not hidden.
							if ($geopserve_shortcode_array['hide'] != 'T'){?>

								<div class="m-results-item">
									<div class="m-results-item__body flex-align-center">
										<a href="#" class="u-pd-right--md u-mg-right--md" style="border-right: 1px solid #ddd;">Browse all 1000 Datasets</a>
										<div class="flex-1 d-flex flex-justify-between flex-align-center">
										<div class="input-group-slick flex-1">
											<span class="icon fas fa-search"></span>
											<input type="text" class="form-control" aria-label="Search Theme|Community datasets"
													id="geopportal_community_<?php echo $geopserve_generation_array[$i]['title'] ?>_search"
													query-prefix="/#/?communities=<?php echo $geopserve_shortcode_array['id'] . $geopserve_generation_array[$i]['query'] ?>"
													aria-label="<?php echo $geopserve_generation_array[$i]['search'] ?>"
													placeholder="<?php echo $geopserve_generation_array[$i]['search'] ?>">
											</div>
											<button class="u-mg-left--lg btn btn-secondary" grabs-from="geopportal_community_<?php echo $geopserve_generation_array[$i]['title'] ?>_search">SEARCH</a>
										</div>
									</div>
								</div>
							<?php } ?>



							</div> <!-- m-results -->
						</div> <!-- m-article__desc -->
					</div> <!-- m-article -->
				</div> <!-- carousel-item (active) -->

				<?php
			} // End of single asset type loop.
			?>








        <div class="carousel-item active">
          <div class="m-article">
            <div class="m-article__heading u-text--sm">Recent Datasets</div>
            <div class="m-article__desc">
              <div class="m-results">


                <div class="m-results-item">
                  <div class="m-results-item__body">
                    <div class="m-results-item__icon m-results-item__icon--sm">
                      <span class="icon-dataset is-themed u-text--huge"></span>
                    </div>
                    <div class="flex-1">
                      <div class="m-results-item__heading">
                        <a href="#" target="_blank">Dataset Title</a>
                      </div>
                      <div class="m-results-item__facets">
                        <span class="m-results-item__type">
                          <strong>Dataset</strong>
                          <span> by <a class="is-linkless" href="#">username</a></span>
                        </span> |
                        <span>created Jan 01, 2019</span> |
                        <span>last modified Jan 01, 2019</span>
                      </div>
                      <div class="m-results-item__description">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                       </div>
                     </div>
                     <!-- icon if necessary -->
                     <img class="m-results-item__icon t--large" alt="Thumbnail" src="/img/img-404.png">
                   </div>
                 </div>


                 <div class="m-results-item">
                   <div class="m-results-item__body flex-align-center">
                     <a href="#" class="u-pd-right--md u-mg-right--md" style="border-right: 1px solid #ddd;">Browse all 1000 Datasets</a>
                     <div class="flex-1 d-flex flex-justify-between flex-align-center">
                     <div class="input-group-slick flex-1">
                       <span class="icon fas fa-search"></span>
                       <input type="text" class="form-control" aria-label="Search Theme|Community datasets"
                             placeholder="Search Theme|Community datasets">
                       </div>
                       <a href="/search.html?type=dcat:Dataset&q=test"
                           class="u-mg-left--lg btn btn-secondary">SEARCH</a>
                     </div>
                   </div>
                 </div>



               </div>
             </div>
           </div>
         </div>




       </div>
       <div class="carousel-item"> <!-- Services   “icon-service” --> </div>
       <div class="carousel-item"> <!-- Layers   “icon-layer” --> </div>
       <div class="carousel-item"> <!-- Maps   “icon-map” --> </div>
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
