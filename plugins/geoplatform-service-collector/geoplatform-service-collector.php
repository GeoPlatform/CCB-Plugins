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
 * @since             2.1.5
 * @package           Geoplatform_Service_Collector
 *
 * @wordpress-plugin
 * Plugin Name:       GeoPlatform Asset Carousel
 * Plugin URI:        https://www.geoplatform.gov
 * Description:       Display your data from the GeoPlatform portfolio in a carousel format.
 * Version:           2.1.5
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
define( 'GEOSERVE_PLUGIN', '2.1.5' );

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
 * @since    1.1.2
 */
function run_geoplatform_service_collector() {

	$plugin = new Geoplatform_Service_Collector();
	$plugin->run();

}
run_geoplatform_service_collector();


function geopserve_shortcode_generation($geopserve_atts){

	// Establishes a base array with default values required for shortcode creation
	// and overwrites them with values from $geopserve_atts.
	$geopserve_shortcode_array = shortcode_atts(array(
		'title' => '',
		'count' => '6',
		'cat' => 'TFFFFFF',
		'adds' => 'TTTFDM',
		'form' => 'standard',
		'search' => 'stand',
		'community' => '',
		'theme' => '',
		'label' => '',
		'keyword' => '',
		'topic' => '',
		'usedby' => '',
		'class' => ''
	), $geopserve_atts);
	ob_start();

	// Adding GeoPlatform styling. Placing it here ensures it doesn't get
	// overridden by the theme.
	wp_enqueue_style( 'geop-style', plugin_dir_url( __FILE__ ) . 'public/css/geop-style.css', array());
	wp_enqueue_style( 'geop_bootstrap_css', plugin_dir_url( __FILE__ ) . 'public/css/bootstrap.css', array(), '2.1.5', 'all' );
	wp_enqueue_style( 'geop_font_awesome', plugin_dir_url( __FILE__ ) . 'public/font/fontawesome-all.css', array(), '2.1.5', 'all' );

	// The original intention was to handle the shortcode output differently based
	// upon compact or standard form. Currently, compact form is not planned to be
	// pursued further, so the function executes the standard, causing no difference
	// in output. If compact form is returned to, so will the viability of this
	// process, as it will likely be more efficient to isolate the differences
	// between forms to output in the name of minimizing code footprint.
	if ($geopserve_shortcode_array['form'] == 'compact'){
		geopserve_shortcode_generation_compact($geopserve_shortcode_array);
	}
	else{
		geopserve_shortcode_generation_standard($geopserve_shortcode_array);
	}
	return ob_get_clean();
}

// The Asset Carousel generation for standard output.
function geopserve_shortcode_generation_standard($geopserve_shortcode_array){

	// Required inclusion for detecting if the Item Details and Search plugins
	// are active.
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Current pagination page and suffix, starting at one and A.
	$geopserve_current_page = 0;
	$geopserve_current_tab = 0;
	$geopserve_current_suffix = 'A';

	// Handles sortation logic.
	$geopserve_sort_string = '';
	switch (substr($geopserve_shortcode_array['adds'], 5, 1)) {
		case "N":
			$geopserve_sort_string .= "label,";
			break;
		case "R":
			$geopserve_sort_string .= "_score,";
			break;
		default:
			$geopserve_sort_string .= "modified,";
			break;
	}
	$geopserve_sort_string .= (substr($geopserve_shortcode_array['adds'], 4, 1) == 'A') ? "asc" : "desc";

	// Starts interpretation. First calls the tab interpretation function's return,
	// which is an array of arrays containing key-value pairs relevant for output
	// of the carousel tabs.
	$geopserve_tab_array = geopserve_tab_interpretation($geopserve_shortcode_array['cat']);

	// Interprets the togglable portions of the misc additional features.
	$geopserve_show_main_title = (substr($geopserve_shortcode_array['adds'], 0, 1) == 'T');
	$geopserve_show_tabs = (substr($geopserve_shortcode_array['adds'], 1, 1) == 'T');
	$geopserve_show_sub_titles = (substr($geopserve_shortcode_array['adds'], 2, 1) == 'T');
	$geopserve_show_pages = (substr($geopserve_shortcode_array['adds'], 3, 1) == 'T');

	// Grabs the search bar format. Checks for the state of the GeoPlatform search
	// format and lack of the associated plugin, defaulting to standard format if
	// true.
	$geopserve_search_state = $geopserve_shortcode_array['search'];
	if ( $geopserve_shortcode_array['search'] == 'geop' && !is_plugin_active('geoplatform-search/geoplatform-search.php') )
		$geopserve_search_state = "stand";

	// Default image and environment pull. Only really applicable in GeoPlatform
	// instances that may be in testing phases.
	$geopserve_ual_domain = isset($_ENV['ual_url']) ? $_ENV['ual_url'] : 'https://ual.geoplatform.gov';

	// Item Details plugin detection. If found, will pass off the relevant
	// redirected url to the function. If not, it will set it to the associated
	// environment's Item Detail page on portal.
	$geopserve_redirect_url = isset($_ENV['wpp_url']) ? $_ENV['wpp_url'] : "https://www.geoplatform.gov";
	if ( is_plugin_active('geoplatform-item-details/geoplatform-item-details.php') )
		$geopserve_redirect_url = home_url();
	$geopserve_redirect_url = $geopserve_redirect_url . "/resources/";

	// Basically the same as above but for Search plugin.
	$geopserve_search_url = isset($_ENV['wpp_url']) ? $_ENV['wpp_url'] : "https://www.geoplatform.gov";
	if ( is_plugin_active('geoplatform-search/geoplatform-search.php') )
		$geopserve_search_url = home_url();
	$geopserve_search_url = $geopserve_search_url . "/geoplatform-search/#/?createdBy=";

	// Name of current tab to send to generation method.
	$geopserve_current_tab_name = "Assets";
	if (!empty($geopserve_tab_array))
		$geopserve_current_tab_name = $geopserve_tab_array[$geopserve_current_page]['name'];

	// Javascript block for full-carousel controls.
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {

			// var geopserve_reset_count = sizeof($geopserve_tab_array);

			/*
			 * TODO: use Obj for variables instead of geopserve_id_array
			 */
			var geopserve_options = {
				community_id: "<?php echo $geopserve_shortcode_array['community'] ?>",
				theme_id: "<?php echo $geopserve_shortcode_array['theme'] ?>",
				label_id: "<?php echo $geopserve_shortcode_array['label'] ?>",
				keyword_id: "<?php echo $geopserve_shortcode_array['keyword'] ?>",
				topic_id: "<?php echo $geopserve_shortcode_array['topic'] ?>",
				usedby_id: "<?php echo $geopserve_shortcode_array['usedby'] ?>",
				class_id: "<?php echo $geopserve_shortcode_array['class'] ?>",
				current_tab: parseInt('<?php echo $geopserve_current_tab ?>', 10),
				current_page: parseInt('<?php echo $geopserve_current_page ?>', 10),
				current_suffix: "<?php echo $geopserve_current_suffix ?>",
				sort_style: "<?php echo $geopserve_sort_string ?>",
				cat_name: "<?php echo $geopserve_current_tab_name ?>",
				per_page: "<?php echo $geopserve_shortcode_array['count'] ?>",
				ual_domain: "<?php echo $geopserve_ual_domain ?>",
				redirect_url: "<?php echo $geopserve_redirect_url ?>",
				search_url: "<?php echo $geopserve_search_url ?>",
				home: "<?php echo home_url() ?>",
				failsafe: "<?php echo plugin_dir_url(__FILE__) . 'public/assets/default-featured.jpg' ?>",
				search_state: "<?php echo $geopserve_search_state ?>",
				query_var: "",
			}

			geopserve_gen_list(geopserve_options);

			// Button color controls, because the CSS doesn't work for plugins. On
			// click, active classes are removed from all buttons, then granted to the
			// button that was clicked.
			jQuery(".geopserve-carousel-button-base").click(function(event){
				jQuery(".geopserve-carousel-button-base").removeClass("geopserve-carousel-active active");
				jQuery(this).addClass("geopserve-carousel-active active");
				geopserve_options.cat_name = jQuery(this).attr("title");
				geopserve_options.current_tab = jQuery(this).attr("data-slide-to");

				if (jQuery(this).attr('gen-bool') == 'F'){
					jQuery(this).attr('gen-bool','T');
					jQuery('#geopserve_carousel_gen_div_' + geopserve_options.current_tab + geopserve_options.current_suffix).empty();
					geopserve_gen_list(geopserve_options);
				}
			});

			// Pagination control for previous page. Only triggers if the
			// current page is not the first.
			jQuery(".geopserve-pagination-prev-button").click(function(event){
				if (geopserve_options.current_page > 0){

					geopserve_options.current_page =  geopserve_options.current_page - 1;

					// Sets all tabs to false gen-bool, then reassigns the current to true.
					jQuery(".geopserve-carousel-button-base").attr('gen-bool','F');
					jQuery('.geopserve-carousel-button-base[data-slide-to="' + geopserve_options.current_tab + '"]').attr('gen-bool','T');
					jQuery('.geopserve_carousel_gen_class').empty();
					geopserve_gen_list(geopserve_options);

					var new_page = "Page " + (geopserve_options.current_page + 1);
					jQuery('.geopserve_pagination_tracker').text(new_page);
				}
			});

			// Pagination control for next page. Aside from the lack of
			// checking for a maximum page, and increase in current_page
			// value as opposed to decreasing, works identical to above.
			jQuery(".geopserve-pagination-next-button").click(function(event){

				geopserve_options.current_page =  geopserve_options.current_page + 1;

				// Sets all tabs to false gen-bool, then reassigns the current to true.
				jQuery(".geopserve-carousel-button-base").attr('gen-bool','F');
				jQuery('.geopserve-carousel-button-base[data-slide-to="' + geopserve_options.current_tab + '"]').attr('gen-bool','T');
				jQuery('.geopserve_carousel_gen_class').empty();
				geopserve_gen_list(geopserve_options);

				var new_page = "Page " + (geopserve_options.current_page + 1);
				jQuery('.geopserve_pagination_tracker').text(new_page);
			});

			jQuery(".geopserve_search_standard_button").click(function(event){

				// Grabs the input parameters from the search bar.
				var geopportal_grabs_from = jQuery("#geopserve_stand_search_button_" + geopserve_options.current_tab).attr("grabs-from");
				geopserve_options.query_var = jQuery("#" + geopportal_grabs_from).val();

				jQuery(".geopserve-carousel-button-base").attr('gen-bool','F');
				jQuery('.geopserve-carousel-button-base[data-slide-to="' + geopserve_options.current_tab + '"]').attr('gen-bool','T');
				jQuery('.geopserve_carousel_gen_class').empty();
				geopserve_gen_list(geopserve_options);
			});


			// Search functionality trigger on pressing enter in search bar.
			// Functions identically to the button press.
			jQuery(".geopserve_search_standard_form").submit(function(event){
				event.preventDefault();

				var geopportal_grabs_from = jQuery("#geopserve_stand_search_button_" + geopserve_options.current_tab).attr("grabs-from");
				geopserve_options.query_var = jQuery("#" + geopportal_grabs_from).val();

				jQuery(".geopserve-carousel-button-base").attr('gen-bool','F');
				jQuery('.geopserve-carousel-button-base[data-slide-to="' + geopserve_options.current_tab + '"]').attr('gen-bool','T');
				jQuery('.geopserve_carousel_gen_class').empty();
				geopserve_gen_list(geopserve_options);
			});

			// Search functionality trigger on button click.
			jQuery(".geopserve_search_geop_button").click(function(event){
				var geopportal_grabs_from = jQuery(this).attr("grabs-from");
				var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
				window.open(
					"<?php echo home_url('geoplatform-search')?>" + geopportal_query_string,
					'_blank'
				);
			});

			// Search functionality trigger on pressing enter in search bar.
			jQuery(".geopserve_search_geop_form").submit(function(event){
				event.preventDefault();
				var geopportal_grabs_from = jQuery(this).attr("grabs-from");
				var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
				window.open(
					"<?php echo home_url('geoplatform-search')?>" + geopportal_query_string,
					'_blank'
				);
			});
		});
	</script>
	<?php

	// CAROUSEL CONSTRUCTION BEGINS
	// Everywhere that 'hide' is checked is indicitive of an option that strips out
	// the titles of the carousel and each entry, as well as the search bar. This
	// Cuts it down to just the panel outputs and buttons.
	echo "<div class='m-article'>";

		// Checks if the title is empty or disabled. If neither are true, it is shown.
		if (!empty($geopserve_shortcode_array['title']) && $geopserve_show_main_title)
			echo "<div class='m-article__heading'>" . $geopserve_shortcode_array['title'] . "</div><br>";

		// Houses the main carousel body.
	   echo "<div class='carousel slide' data-ride='carousel' data-interval='false' id='geopserve_community_anchor_carousel'>";

			// Generates the top tab buttons, but only if there are at least two data
			// types to provide output for and tabs aren't disabled.
			if (sizeof($geopserve_tab_array) > 1 && $geopserve_show_tabs){
				echo "<div class='geopserve-tab-margins u-mg-bottom--xlg'>";

				// Checks if pagination is enabled and, if so, outputs the left control.
				if ($geopserve_show_pages)
					echo "<button class='icon fas fa-caret-left geopserve-pagination-button-base geopserve-pagination-prev-button' aria-label='Tab Left'></button>";

				// Loops through the tab info array, grabbing the desired info and to
				// generates the tabs. The first tab is designed to be the "active" one,
				// and pairs with the first active pane generated in the Javascript.
				echo "<ol class='carousel-indicators carousel-indicators-override' style='margin:0'>";
				for ($i = 0; $i < sizeof($geopserve_tab_array); $i++){
					if ($i == 0)
						echo "<li data-target='#geopserve_community_anchor_carousel' data-slide-to='" . $i . "' class='carousel-indicators geopserve-carousel-button-base geopserve-carousel-active active' title='" . $geopserve_tab_array[$i]['name'] . "' gen-bool='T'>";
					else
						echo "<li data-target='#geopserve_community_anchor_carousel' data-slide-to='" . $i . "' class='carousel-indicators geopserve-carousel-button-base' title='" . $geopserve_tab_array[$i]['name'] . "' gen-bool='F'>";

					echo "<span class='" . $geopserve_tab_array[$i]['icon'] . "'></span>" . $geopserve_tab_array[$i]['name'] . "</li>";
				}
			  echo "</ol>";

				// Checks if pagination is enabled and, if so, outputs the right control.
				if ($geopserve_show_pages)
					echo "<button class='icon fas fa-caret-right geopserve-pagination-button-base geopserve-pagination-next-button' aria-label='Tab Right'></button>";

				echo "</div>";
			}
			elseif((sizeof($geopserve_tab_array) <= 1 || !$geopserve_show_tabs) && $geopserve_show_pages){

				// If tabs are meant to be hidden but pagination is shown, simple controls
				// are generated here.
				echo "<div class='geopserve-tab-margins u-mg-bottom--xlg'>";
					echo "<button class='icon fas fa-caret-left geopserve-pagination-button-base geopserve-pagination-prev-button geopserve-tab-pagers'></button>";
					echo "<span class='geopserve-pagination-counter-base geopserve_pagination_tracker geopserve-tab-pagers'>Page 1</span>";
					echo "<button class='icon fas fa-caret-right geopserve-pagination-button-base geopserve-pagination-next-button geopserve-tab-pagers'></button>";
				echo "</div>";
			}

			// Inner carousel section, housing the assets and search area.
      echo "<div class='carousel-inner'>";

			// Carousel block creation. Sets the first created data type to the
			// active status, then produces the remaining elements.
			for ($i = 0; $i < sizeof($geopserve_tab_array); $i++){

				// Sets the first part of the carousel as active.
				if ($i == 0)
					echo "<div class='carousel-item active'>";
				else
					echo "<div class='carousel-item'>";

					// Containing article.
					echo "<div class='m-article'>";

				// Displays the current carousel item title, if not hidden.
				if ($geopserve_show_sub_titles)
					echo "<div class='m-article__heading u-text--sm' style='text-align:center;'>Recent " . strtolower($geopserve_tab_array[$i]['name']) . "</div>";

				// More container divs, including the carousel div that assets will be
				// applied to. This consists of two divs; A, which is the default active
				// div for holding output, and N, which is the alternate div for pagination.
				echo "<div class='m-article__desc'>";
					echo "<div class='m-results'>";
						echo "<div id='geopserve_carousel_gen_div_" . $i . "A' class='geopserve_carousel_gen_class'></div>";
						echo "<div id='geopserve_carousel_gen_div_" . $i . "N' class='geopserve_carousel_gen_class geopserve-hidden'></div>";

						// Placeholder text setup, establishing a default that's overwritten
						// if within a Portal 4 custom post type.
						$geopserve_search_placeholder = "Search " . strtolower($geopserve_tab_array[$i]['name']);
						if (get_post_type() == 'community-post')
							$geopserve_search_placeholder = "Search community " . strtolower($geopserve_tab_array[$i]['name']);
						elseif (get_post_type() == 'ngda-post')
							$geopserve_search_placeholder = "Search theme " . strtolower($geopserve_tab_array[$i]['name']);

							// Search bar output for geop setting.
							if ($geopserve_search_state == 'geop'){

								// Search query construction begins.

								// Sets query info for communities and usedby values, which are
								// basically the same.
								$geopserve_search_query_prefix = "/#/?" . $geopserve_tab_array[$i]['query'];
								if (!empty($geopserve_shortcode_array['community']) && !empty($geopserve_shortcode_array['usedby']))
									$geopserve_search_query_prefix .= "communities=" . $geopserve_shortcode_array['community'] . "," . $geopserve_shortcode_array['usedby'] . "&";
								elseif (!empty($geopserve_shortcode_array['community']) && empty($geopserve_shortcode_array['usedby']))
									$geopserve_search_query_prefix .= "communities=" . $geopserve_shortcode_array['community'] . "&";
								elseif (empty($geopserve_shortcode_array['community']) && !empty($geopserve_shortcode_array['usedby']))
									$geopserve_search_query_prefix .= "communities=" . $geopserve_shortcode_array['usedby'] . "&";

								// Sets query info for themes, keywords, and topics.
								if (!empty($geopserve_shortcode_array['theme']))
									$geopserve_search_query_prefix .= "themes=" . $geopserve_shortcode_array['theme'] . "&";
								if (!empty($geopserve_shortcode_array['keyword']))
									$geopserve_search_query_prefix .= "keywords=" . $geopserve_shortcode_array['keyword'] . "&";
								if (!empty($geopserve_shortcode_array['topic']))
									$geopserve_search_query_prefix .= "topics=" . $geopserve_shortcode_array['topic'] . "&";
								if (!empty($geopserve_shortcode_array['class']))
									$geopserve_search_query_prefix .= "concepts=" . $geopserve_shortcode_array['class'] . "&";

								// Adds the last part of the string, which is the "q" that the
								// search bar input will concat to. Adds 'label' to this if it
								// is a criteria.
								$geopserve_search_query_prefix .= "q=";
								if (!empty($geopserve_shortcode_array['label']))
									$geopserve_search_query_prefix .= $geopserve_shortcode_array['label'] . " ";

								// Search and paging control construction.
								echo "<div class='m-results-item'>";
									echo "<div class='geopserve-search-display'>";

										// Outputs the current page number. This is done only if pagination
										// is enabled, there is more than one asset type tab, and output
										// for them is enabled.
										if ($geopserve_show_pages && sizeof($geopserve_tab_array) > 1 && $geopserve_show_tabs)
											echo "<span class='geopserve-pagination-counter-base geopserve_pagination_tracker u-mg-left--md is-hidden--xs'>Page 1</span>";

										// Continued construction.
										echo "<a href='" . home_url() . "/geoplatform-search" . $geopserve_search_query_prefix . "' class='u-pd-right--md u-mg-left--md is-hidden--xs geopserve-carousel-browse' target='_blank' id='geopserve_carousel_search_div_" . $i . "' aria-label='Browse All'></a>";
										echo "<div class='flex-1 d-flex flex-justify-between flex-align-center u-mg-left--md'>";
											echo "<div class='input-group-slick flex-1'>";
												echo "<form class='input-group-slick flex-1 geopserve_search_geop_form' grabs-from='geopportal_community_" . $geopserve_tab_array[$i]['name'] . "_search'>";
												echo "<span class='icon fas fa-search'></span>";
													echo "<input type='text' class='form-control' aria-label='Search " . $geopserve_shortcode_array['title'] . " " . strtolower($geopserve_tab_array[$i]['name']) . "' " .
															"id='geopportal_community_" . $geopserve_tab_array[$i]['name'] . "_search' " .
															"query-prefix='" . $geopserve_search_query_prefix . "' " .
															"aria-label='Search " . $geopserve_tab_array[$i]['name'] . "' " .
															"placeholder='" . $geopserve_search_placeholder . "'>";
												echo "</form>";
											echo "</div>";
											echo "<button class='geopserve_search_geop_button u-mg-left--lg u-mg-right--md btn btn-secondary' grabs-from='geopportal_community_" . $geopserve_tab_array[$i]['name'] . "_search'>SEARCH</a>";
										echo "</div>";
									echo "</div>";
								echo "</div>";
							}
							elseif ($geopserve_search_state == 'hide'){
								// If the search bar is set to hide, no action.
							}
							else {

								// Default behavior is standard search.
								echo "<div class='m-results-item'>";
									echo "<div class='geopserve-search-display'>";

										// Outputs the current page number. This is done only if
										// pagination is enabled, there is more than one asset type
										// tab, and output for them is enabled.
										if ($geopserve_show_pages && sizeof($geopserve_tab_array) > 1 && $geopserve_show_tabs)
											echo "<span class='geopserve-pagination-counter-base geopserve_pagination_tracker u-mg-left--md is-hidden--xs'>Page 1</span>";

										// Continued construction.
										echo "<div class='flex-1 d-flex flex-justify-between flex-align-center u-mg-left--md'>";
											echo "<div class='input-group-slick flex-1'>";
												echo "<form class='input-group-slick flex-1 geopserve_search_standard_form' grabs-from='geopportal_community_" . $geopserve_tab_array[$i]['name'] . "_search'>";
												echo "<span class='icon fas fa-search'></span>";
													echo "<input type='text' class='form-control' aria-label='Search " . $geopserve_shortcode_array['title'] . " " . strtolower($geopserve_tab_array[$i]['name']) . "' " .
															"id='geopportal_community_" . $geopserve_tab_array[$i]['name'] . "_search' " .
															"aria-label='Search " . $geopserve_tab_array[$i]['name'] . "' " .
															"placeholder='" . $geopserve_search_placeholder . "'>";
												echo "</form>";
											echo "</div>";
											echo "<button class='geopserve_search_standard_button u-mg-left--lg u-mg-right--md btn btn-secondary' grabs-from='geopportal_community_" . $geopserve_tab_array[$i]['name'] . "_search' id='geopserve_stand_search_button_" . $i . "'>SEARCH</a>";
										echo "</div>";
									echo "</div>";
								echo "</div>";
							}

							// Divs that close out the interface.
							echo "</div> <!-- m-results -->";
						echo "</div> <!-- m-article__desc -->";
					echo "</div> <!-- m-article -->";
				echo "</div> <!-- carousel-item -->";

			} // End of single asset type loop.
      echo "</div> <!-- carousel-inner -->";
    echo "</div> <!-- carousel slide -->";
  echo "</div> <!-- m-article -->";
}

// The Asset Carousel generation for compact output.
// Currently the compact form is not in use and no plans exist it implement it
// in the near future.
function geopserve_shortcode_generation_compact($geopserve_atts){
	geopserve_shortcode_generation_compact($geopserve_shortcode_array);
}

// Interprets the T/F string for category tabs to be shown.
// Examines the input string, which should just be a series of T and F characters,
// one by one, pushing approiate values to an array on finding T's, which is then
// returned.
function geopserve_tab_interpretation($geopserve_string_in){

	// Checks the "cat" shortcode value char by char, populating the generation
	// with sub-arrays of constants for each asset type.
	$geopserve_generation_array = array();
	if (substr($geopserve_string_in, 0, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Datasets',
				'query' => 'types=dcat:Dataset&',
				'icon' => 'icon-dataset',
			)
		);
	}
	if (substr($geopserve_string_in, 1, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Services',
				'query' => 'types=regp:Service&',
				'icon' => 'icon-service',
			)
		);
	}
	if (substr($geopserve_string_in, 2, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Layers',
				'query' => 'types=Layer&',
				'icon' => 'icon-layer',
			)
		);
	}
	if (substr($geopserve_string_in, 3, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Maps',
				'query' => 'types=Map&',
				'icon' => 'icon-map',
			)
		);
	}
	if (substr($geopserve_string_in, 4, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Galleries',
				'query' => 'types=Gallery&',
				'icon' => 'icon-gallery',
			)
		);
	}
	if (substr($geopserve_string_in, 5, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Communities',
				'query' => 'types=Community&',
				'icon' => 'icon-community',
			)
		);
	}
	if (substr($geopserve_string_in, 6, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Applications',
				'query' => 'types=Application&',
				'icon' => 'icon-application',
			)
		);
	}
	if (substr($geopserve_string_in, 7, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Topics',
				'query' => 'types=Topic&',
				'icon' => 'icon-topic',
			)
		);
	}
	if (substr($geopserve_string_in, 8, 1) == 'T'){
		array_push( $geopserve_generation_array, array(
				'name' => 'Websites',
				'query' => 'types=WebSite&',
				'icon' => 'icon-website',
			)
		);
	}
	if ( empty($geopserve_generation_array) ){
		array_push( $geopserve_generation_array, array(
				'name' => 'Assets',
				'query' => '',
				'icon' => 'icon-website',
			)
		);
	}

	return $geopserve_generation_array;
}

// Adds the shortcode hook to init.
function geopserve_shortcodes_init()
{
    add_shortcode('geopserve', 'geopserve_shortcode_generation');
		// get_template_part( 'public/css/geop-style.css', get_post_format() );
}
add_action('init', 'geopserve_shortcodes_init');

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
