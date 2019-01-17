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
function geopserve_gen_carousel(geopserve_id_in, geopserve_cat_in, geopserve_count_in, geopserve_iter_in, geopserve_thumb_in, geopserve_uri_in){

	const Query = GeoPlatform.Query;
	const ItemTypes = GeoPlatform.ItemTypes;
	const QueryParameters = GeoPlatform.QueryParameters;
	var ItemService = GeoPlatform.ItemService;

	var query = new GeoPlatform.Query();

	if (geopserve_cat_in == "Data")
		query.setTypes(ItemTypes.DATASET);
	if (geopserve_cat_in == "Services")
		query.setTypes(ItemTypes.SERVICE);
	if (geopserve_cat_in == "Layers")
		query.setTypes(ItemTypes.LAYER);
	if (geopserve_cat_in == "Maps")
		query.setTypes(ItemTypes.MAP);
	if (geopserve_cat_in == "Galleries")
		query.setTypes(ItemTypes.GALLERY);

	query.setPageSize(geopserve_count_in);
	query.setSort('modified,desc');
	if (geopserve_id_in) {
		query.usedBy(geopserve_id_in);
	}
	query.setQ("");

	geopserve_retrieve_objects(query)
		.then(function (response) {
			var geopserve_max_panes = geopserve_count_in;
			if (response.totalResults < geopserve_count_in)
				geopserve_max_panes = response.totalResults;

			var geopserve_results = response.results;

			for (var i = 0; i < geopserve_max_panes; i++){
			}


		// Pane generation loop.
		for (var i = 0; i < geopserve_max_panes; i++){
			// Conditionals that attempt to grab author and date from JSON.
			// For date, sets a default unknown, then attempts to grab the modified and,
			// failing that, created values, translating them into date strings of the
			// desired format.

			var geopserve_result_time = "Unknown Time";
			if (typeof geopserve_results[i].modified != 'undefined'){
				var geopserve_temp_date = new Date(geopserve_results[i].modified);
				geopserve_result_time = geopserve_temp_date.toLocaleString('en-us', { month: 'short' }) + " " + geopserve_temp_date.getDate() + ", " + geopserve_temp_date.getFullYear();
			}
			else if (typeof geopserve_results[i].modified === 'undefined' && typeof geopserve_results[i].created != 'undefined'){
				var geopserve_temp_date = new Date(geopserve_results[i].created);
				geopserve_result_time = geopserve_temp_date.toLocaleString('en-us', { month: 'short' }) + " " + geopserve_temp_date.getDate() + ", " + geopserve_temp_date.getFullYear();
			}

			var geopserve_asset_link = "https://oe.geoplatform.gov/view/" + geopserve_results[i].id;

			var geopserve_thumb_src = geopserve_uri_in + geopserve_results[i].id + "/thumbnail";
			var geopserve_thumb_error = "this.src='" + geopserve_thumb_in + "'";
			var geopserve_label_text = geopserve_results[i].label;

			var geopserve_result_name = "Unknown User";
			if (typeof geopserve_results[i].createdBy != 'undefined')
				geopserve_result_name = geopserve_results[i].createdBy;

			console.log(geopserve_thumb_src);

			var geopserve_under_label_text = geopserve_result_time + " by " + geopserve_result_name;
			var geopserve_temp_div = 'geopserve_carousel_gen_div_' + geopserve_iter_in;

			geopserve_gen_element(geopserve_thumb_src, geopserve_asset_link, geopserve_under_label_text, geopserve_label_text, geopserve_temp_div, geopserve_thumb_error);
		}
	})
	.catch(function (error) {
		errorSelector.show();
		workingSelector.hide();
		pagingSelector.hide();
	});
}( jQuery );

function geopserve_gen_element(geopserve_thumb_src, geopserve_asset_link, geopserve_under_label_text, geopserve_label_text, geopserve_temp_div, geopserve_thumb_error){
	// Simpler than the above, setting a default and overriding if the there is
	// a creating user found. The two strings are then combined for output.
	var head_div = geopserve_createEl({type: 'div', class: 'm-tile m-tile--16x9'});
	var thumb_div = geopserve_createEl({type: 'div', class: 'm-tile__thumbnail'});
	var thumb_img = geopserve_createEl({type: 'img', alt: "This is alternative text for the thumbnail", src: geopserve_thumb_src, onerror: geopserve_thumb_error});
	var body_div = geopserve_createEl({type: 'div', class: 'm-tile__body'});
	var body_href = geopserve_createEl({type: 'a', class: 'm-tile__heading', href: geopserve_asset_link, target: '_blank', html: geopserve_label_text});
	var sub_div = geopserve_createEl({type: 'div', class: 'm-tile__timestamp', html:geopserve_under_label_text});

	thumb_div.appendChild(thumb_img);
	body_div.appendChild(body_href);
	body_div.appendChild(sub_div);
	head_div.appendChild(thumb_div);
	head_div.appendChild(body_div);

	document.getElementById(geopserve_temp_div).appendChild(head_div);
}



function geopserve_retrieve_objects(query) {
	var deferred = Q.defer();
	var service = new GeoPlatform.ItemService(GeoPlatform.ualUrl, new GeoPlatform.JQueryHttpClient());
	service.search(query)
		.then(function (response) { deferred.resolve(response); })
		.catch(function (e) { deferred.reject(e); });
	return deferred.promise;
}

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
	if(geopserve_el_atts.onerror)
		new_el.setAttribute('onerror', geopserve_el_atts.onerror);
	return new_el;
}
