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
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
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
















// Hook backbone for shortcode interpretation.
function geopserve_com_shortcodes_creation($geopserve_atts){

	// Establishes a base array with default values required for shortcode creation
	// and overwrites them with values from $geoserve_atts.
  $geoserve_shortcode_array = shortcode_atts(array(
		'title' => '',
    'id' => '84924e415a256ba1941d161e16f5188c',
    'cat' => 'TFFFFFF',
		'count' => '6',
  ), $geopserve_atts);
  ob_start();

	// Constructs the full community URI for ual, to pull info from.
	$geoserve_id_san = sanitize_key($geoserve_shortcode_array['id']);
	$geoserve_ual_url = 'https://ual.geoplatform.gov/api/communities/';
	$geoserve_ual_url .= $geoserve_id_san;


	// Data category array format for each entry is....
	// Button text, search bar text, search query, base uri, and temporary box text.
	$geoserve_generation_array = array();
	if (substr(($geoserve_shortcode_array['cat']), 0, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Data',
				'search' => 'Search for associated datasets',
				'query' => '&types=dcat:Dataset&q=',
				'uri' => 'https://ual.geoplatform.gov/api/datasets/',
				'box' => 'DATASET LABEL',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 1, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Services',
				'search' => 'Search for associated services',
				'query' => '&types=regp:Service&q=',
				'uri' => 'https://ual.geoplatform.gov/api/services/',
				'box' => 'SERVICE LABEL',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 2, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Layers',
				'search' => 'Search for associated layers',
				'query' => '&types=Layer&q=',
				'uri' => 'https://ual.geoplatform.gov/api/layers/',
				'box' => 'LAYER LABEL',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 3, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Maps',
				'search' => 'Search for associated maps',
				'query' => '&types=Map&q=',
				'uri' => 'https://ual.geoplatform.gov/api/datasets/',
				'box' => 'MAP LABEL',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 4, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Galleries',
				'search' => 'Search for associated galleries',
				'query' => '&types=Gallery&q=',
				'uri' => 'https://ual.geoplatform.gov/api/datasets/',
				'box' => 'GALLERY LABEL',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 5, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Organizations',
				'search' => 'Search for associated organizations',
				'query' => '&types=org:Organization&q=',
				'uri' => 'https://ual.geoplatform.gov/api/datasets/',
				'box' => 'ORGANIZATION LABEL',
			)
		);
	}
	if (substr(($geoserve_shortcode_array['cat']), 6, 1) == 'T'){
		array_push( $geoserve_generation_array, array(
				'button' => 'Contacts',
				'search' => 'Search for associated contacts',
				'query' => '&types=vcard:VCard&q=',
				'uri' => 'https://ual.geoplatform.gov/api/datasets/',
				'box' => 'CONTACT LABEL',
			)
		);
	}

	// Default image.
	$geopserve_disp_thumb = plugin_dir_url(__FILE__) . 'includes/img-404.png';
	?>


<!-- Carousel construction -->
	<div class="m-article">
		<?php

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
				if (sizeof($geoserve_generation_array) > 1){
					?>
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
							if ($i == 0){ ?>
								<div class="carousel-item active">
							<?php } else { ?>
								<div class="carousel-item">
							<?php } ?>
	                <div class="m-article">
	                    <div class="m-article__heading" style="text-align:center;">Recent <?php echo $geoserve_generation_array[$i]['button'] ?></div>
	                    <div class="m-article__desc">
	                        <div class="d-grid d-grid--3-col--lg" id="geopserve_carousel_gen_div_<?php echo $i ?>">

														<script>
															geopserve_gen_carousel("<?php echo $geoserve_shortcode_array['id'] ?>", "<?php echo $geoserve_generation_array[$i]['button'] ?>", <?php echo $geoserve_shortcode_array['count'] ?>, <?php echo $i ?>, "<?php echo $geopserve_disp_thumb ?>");
														</script>

	                        </div>
	                        <div class="u-mg-top--xlg d-flex flex-justify-between flex-align-center">
	                            <form class="input-group-slick flex-1 geopportal_port_community_search_form" grabs-from="geopportal_community_<?php echo $geoserve_generation_array[$i]['button'] ?>_search">
	                                <span class="icon fas fa-search"></span>
	                                <input type="text" class="form-control" id="geopportal_community_<?php echo $geoserve_generation_array[$i]['button'] ?>_search"
	                                    query-prefix="/#/?communities=<?php echo $geoserve_shortcode_array['id'] . $geoserve_generation_array[$i]['query'] ?>"
	                                    aria-label="<?php echo $geoserve_generation_array[$i]['search'] ?>" placeholder="<?php echo $geoserve_generation_array[$i]['search'] ?>">
	                            </form>
	                            <button class="u-mg-left--lg btn btn-secondary geopportal_port_community_search_button" grabs-from="geopportal_community_<?php echo $geoserve_generation_array[$i]['button'] ?>_search">SEARCH <?php echo strtoupper($geoserve_generation_array[$i]['button']) ?></button>
	                        </div>
	                    </div>
	                </div>
	            </div>

						<?php } ?>

	        </div>
	    </div>
	</div>

	<script type="text/javascript">
	  jQuery(document).ready(function() {
	  });
	</script>

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
