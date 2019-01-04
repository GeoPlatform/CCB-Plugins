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


// Community carousel window creator. Called during each loop of the carousel,
// so will only have to deal with one data type at a time.
//
// *  #param geopserve_id_in: the community ID for the query.
// *  #param geopserve_cat_in: data type for the query.
// *  #param geopserve_count_in: number of panes to generate.
// *  #param geopserve_iter_in: iter of the loop in which this function is called, used for element attachement.
// *  #param geopserve_thumb_in: 404 image url, in case there is no image to use.
//
function geopserve_gen_carousel(geopserve_id_in, geopserve_cat_in, geopserve_count_in, geopserve_iter_in, geopserve_thumb_in){



	// Data category array format for each entry is....
	// Button text, search bar text, search query, base uri, and temporary box text.
	// var geoserve_generation_array = Array();
	// if (geopserve_cats_in.substring(0, 1) == 'T'){
	// 	geoserve_temp_array = {
	// 		'button': 'Data',
	// 		'title': 'Recent Datasets',
	// 		'search': 'Search for associated datasets',
	// 		'query': '&types=dcat:Dataset&q=',
	// 		'uri': 'https://ual.geoplatform.gov/api/datasets/',
	// 		'box': 'DATASET LABEL',
	// 	}
	// 	geoserve_generation_array.push(geoserve_temp_array);
	// }
	// if (geopserve_cats_in.substring(1, 2) == 'T'){
	// 	geoserve_temp_array = {
	// 		'button': 'Services',
	// 		'title': 'Recent Services',
	// 		'search': 'Search for associated services',
	// 		'query': '&types=regp:Service&q=',
	// 		'uri': 'https://ual.geoplatform.gov/api/services/',
	// 		'box': 'SERVICE LABEL',
	// 	}
	// 	geoserve_generation_array.push(geoserve_temp_array);
	// }
	// if (geopserve_cats_in.substring(2, 3) == 'T'){
	// 	geoserve_temp_array = {
	// 		'button': 'Layers',
	// 		'title': 'Recent Layers',
	// 		'search': 'Search for associated layers',
	// 		'query': '&types=Layer&q=',
	// 		'uri': 'https://ual.geoplatform.gov/api/layers/',
	// 		'box': 'LAYER LABEL',
	// 	}
	// 	geoserve_generation_array.push(geoserve_temp_array);
	// }
	// if (geopserve_cats_in.substring(3, 4) == 'T'){
	// 	geoserve_temp_array = {
	// 		'button': 'Maps',
	// 		'title': 'Recent Maps',
	// 		'search': 'Search for associated maps',
	// 		'query': '&types=Map&q=',
	// 		'uri': 'https://ual.geoplatform.gov/api/maps/',
	// 		'box': 'MAP LABEL',
	// 	}
	// 	geoserve_generation_array.push(geoserve_temp_array);
	// }
	// if (geopserve_cats_in.substring(4, 5) == 'T'){
	// 	geoserve_temp_array = {
	// 		'button': 'Galleries',
	// 		'title': 'Recent Galleries',
	// 		'search': 'Search for associated galleries',
	// 		'query': '&types=Gallery&q=',
	// 		'uri': 'https://ual.geoplatform.gov/api/galleries/',
	// 		'box': 'GALLERY LABEL',
	// 	}
	// 	geoserve_generation_array.push(geoserve_temp_array);
	// }

	for (var i = 0; i < geopserve_count_in; i++){

		var label_text = geopserve_cat_in + " LABEL";

		var head_div = geopserve_createEl({type: 'div', class: 'm-tile m-tile--16x9'});
		var thumb_div = geopserve_createEl({type: 'div', class: 'm-tile__thumbnail'});
		var thumb_img = geopserve_createEl({type: 'img', alt: "This is alternative text for the thumbnail", src: geopserve_thumb_in});
		var body_div = geopserve_createEl({type: 'div', class: 'm-tile__body'});
		var body_href = geopserve_createEl({type: 'a', class: 'm-tile__heading', href: '/secondary.html', html: label_text});
		var sub_div = geopserve_createEl({type: 'div', class: 'm-tile__timestamp', html:'Jan 1, 2018 by Joe User'});

		thumb_div.appendChild(thumb_img);
		body_div.appendChild(body_href);
		body_div.appendChild(sub_div);
		head_div.appendChild(thumb_div);
		head_div.appendChild(body_div);

		var geopserve_temp_div = 'geopserve_carousel_gen_div_' + geopserve_iter_in;
		document.getElementById(geopserve_temp_div).appendChild(head_div);
	}
}( jQuery );

// Creates an HTML element and, using the arrays of string pairs passed here
// from geop_layer_control_gen(), adds attributes to it that make it into a
// functional element before returning it.
function geopserve_createEl(geopserve_el_atts){
	geopserve_el_atts = geopserve_el_atts || {};
	var new_el = document.createElement(geopserve_el_atts.type);
	if(geopserve_el_atts.html)
		new_el.innerHTML = geopserve_el_atts.html;
	if(geopserve_el_atts.text)
		new_el.setAttribute('text', geopserve_el_atts.text);
	if(geopserve_el_atts.class)
		new_el.setAttribute('class', geopserve_el_atts.class);
	if(geopserve_el_atts.style)
		new_el.setAttribute('style', geopserve_el_atts.style);
	if(geopserve_el_atts.id)
		new_el.setAttribute('id', geopserve_el_atts.id);
	if(geopserve_el_atts.title)
		new_el.setAttribute('title', geopserve_el_atts.title);
	if(geopserve_el_atts.href)
		new_el.setAttribute('href', geopserve_el_atts.href);
	if(geopserve_el_atts.target)
		new_el.setAttribute('target', geopserve_el_atts.target);
	if(geopserve_el_atts.span)
		new_el.setAttribute('span', geopserve_el_atts.span);
	if(geopserve_el_atts.opac)
		new_el.setAttribute('opac', geopserve_el_atts.opac);
	if(geopserve_el_atts.alt)
		new_el.setAttribute('alt', geopserve_el_atts.alt);
	if(geopserve_el_atts.src)
		new_el.setAttribute('src', geopserve_el_atts.src);
	if(geopserve_el_atts.href)
		new_el.setAttribute('href', geopserve_el_atts.href);
	return new_el;
}
