(function( $ ) {
	'use strict';
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
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
			window.location.href="<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string;
		});

		// Search functionality trigger on pressing enter in search bar.
		jQuery( ".geopportal_port_community_search_form" ).submit(function(event){
			event.preventDefault();
			var geopportal_grabs_from = jQuery(this).attr("grabs-from");
			var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
			window.location.href="<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string;
	  });
	});




})( jQuery );

function geopserve_gen_carousel(geopserve_id_in, geopserve_cats_in, geopserve_count_in){

	// Data category array format for each entry is....
	// Button text, search bar text, search query, base uri, and temporary box text.
	var geoserve_generation_array = array();
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


	var QueryFactory = GeoPlatform.QueryFactory;
	var ItemTypes = GeoPlatform.ItemTypes;
	var QueryParameters = GeoPlatform.QueryParameters;

	let query = new Query();
	// var query = new GeoPlatform.Query();

	query.setQ('');
	query.setFacets(null);
	query.setFields(['resourceTypes', 'landingPage', 'modified']);




	return geopserve_id_in;
}( jQuery );
