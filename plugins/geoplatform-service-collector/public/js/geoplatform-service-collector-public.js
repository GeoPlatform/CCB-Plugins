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


// Community asset count applicator. Called during each loop of the carousel, so
// will only have to deal with one data type at a time. Will discern how many
// copies exist of the given asset type and apply that number to the search area.
//
// #param geopserve_id_in: the community ID for the query.
// #param geopserve_cat_in: data type for the query.
// #param geopserve_iter_in: iter of the loop in which this function is called, used for element attachement.
// #param geopserve_ual_domain_in: UAL source to draw from.
function geopserve_gen_count(geopserve_id_in, geopserve_source, geopserve_cat_in, geopserve_iter_in, geopserve_ual_domain_in){

	// Service collection setup.
	const Query = GeoPlatform.Query;
	const ItemTypes = GeoPlatform.ItemTypes;
	const QueryParameters = GeoPlatform.QueryParameters;
	var ItemService = GeoPlatform.ItemService;

	var query = new GeoPlatform.Query();

	// Sets type of asset type to grab.
	if (geopserve_cat_in == "Datasets")
		query.setTypes(ItemTypes.DATASET);
	if (geopserve_cat_in == "Services")
		query.setTypes(ItemTypes.SERVICE);
	if (geopserve_cat_in == "Layers")
		query.setTypes(ItemTypes.LAYER);
	if (geopserve_cat_in == "Maps")
		query.setTypes(ItemTypes.MAP);
	if (geopserve_cat_in == "Galleries")
		query.setTypes(ItemTypes.GALLERY);

	// Restricts results to a single source, if provided.
	if (geopserve_id_in) {
		if (geopserve_source == 'theme')
			query.setThemes(geopserve_id_in);
		else
			query.usedBy(geopserve_id_in);
	}

	// Performs the query grab.
	geopserve_list_retrieve_objects(query, geopserve_ual_domain_in)
	.then(function (response) {

		// Variables for the text and page element it's to be attached to.
		var geopserve_master_div = 'geopserve_carousel_search_div_' + geopserve_iter_in;
		var geopserve_search_text = 'Browse all ' + response.totalResults + " " + geopserve_cat_in;
		if (response.totalResults <= 0)
			geopserve_search_text = 'No ' + geopserve_cat_in.toLowerCase() + ' to browse';

		// Creates the text and attaches it.
		geop_search_node = document.createTextNode(geopserve_search_text);
		document.getElementById(geopserve_master_div).appendChild(geop_search_node);
	})
	.catch(function (error) {
		errorSelector.show();
		workingSelector.hide();
		pagingSelector.hide();
	});
}( jQuery );


// Community list window creator. Called during each loop of the carousel, so
// will only have to deal with one data type at a time. Generate the panes of
// the carousel.
//
// #param geopserve_id_in: the community ID for the query.
// #param geopserve_cat_in: data type for the query.
// #param geopserve_count_in: number of panes to generate.
// #param geopserve_iter_in: iter of the loop in which this function is called, used for element attachement.
// #param geopserve_icon_in: asset's icon class
// #param geopserve_ual_domain_in: UAL source to draw from.
// #param geopserve_redirect_in: Panel base URL for this particular asset type.
// #param geopserve_new_tab: Determines if a pane opens in a new window or not.
// #param geopserve_home: Home url of hosting site.
// #param geopserve_404_in: 404 image path.
//
function geopserve_gen_list(geopserve_id_in, geopserve_source, geopserve_cat_in, geopserve_count_in, geopserve_iter_in, geopserve_icon_in, geopserve_ual_domain_in, geopserve_redirect_in, geopserve_new_tab, geopserve_home, geopserve_404_in){

	// Service collection setup.
	const Query = GeoPlatform.Query;
	const ItemTypes = GeoPlatform.ItemTypes;
	const QueryParameters = GeoPlatform.QueryParameters;
	var ItemService = GeoPlatform.ItemService;

	var query = new GeoPlatform.Query();

	// Sets type of asset type to grab.
	if (geopserve_cat_in == "Datasets")
		query.setTypes(ItemTypes.DATASET);
	if (geopserve_cat_in == "Services")
		query.setTypes(ItemTypes.SERVICE);
	if (geopserve_cat_in == "Layers")
		query.setTypes(ItemTypes.LAYER);
	if (geopserve_cat_in == "Maps")
		query.setTypes(ItemTypes.MAP);
	if (geopserve_cat_in == "Galleries")
		query.setTypes(ItemTypes.GALLERY);

	// Sets return count and sortation style.
	query.setPageSize(geopserve_count_in);
	query.setSort('modified,desc');

	// Restricts results to a single source, if provided.
	if (geopserve_id_in) {
		if (geopserve_source == 'theme')
			query.setThemes(geopserve_id_in);
		else
			query.usedBy(geopserve_id_in);
	}

	// Adds thumbnails to the query return.
	var fields = query.getFields();
	fields.push("thumbnail");
	query.setFields(fields);

	// Performs the query grab.
	geopserve_list_retrieve_objects(query, geopserve_ual_domain_in)
		.then(function (response) {

			// ItemService.search(thumb_query).then( thumb_response => thumb_response.results.forEach( item => item.thumbnail ? console.log(buildThumb(item.thumbnail)) : doNothing(); ) );

			// Gets the results.
			var geopserve_results = response.results;

			// Sets result output minimum.
			var geopserve_max_panes = geopserve_count_in;
			if (response.totalResults < geopserve_count_in)
				geopserve_max_panes = response.totalResults;

			// Pane generation loop.
			for (var i = 0; i < geopserve_max_panes; i++){

				// Grabs the id and uses it to construct an item details href.
				var geopserve_asset_link = geopserve_redirect_in + geopserve_results[i].id;

				// Sets the title of the asset.
				var geopserve_label_text = geopserve_results[i].label;

				// Sets thumbnail to undefined, then replaces with the thumbnail namespace
				// from UAL if the asset has a valid one.
				var geopserve_thumb_src = 'undefined';
				if (geopserve_results[i].hasOwnProperty('thumbnail')){
					if (geopserve_results[i].thumbnail.hasOwnProperty('url') || geopserve_results[i].thumbnail.hasOwnProperty('contentData')){
						geopserve_thumb_src = geopserve_ual_domain_in + "/api/items/" + geopserve_results[i].id + "/thumbnail";
					}
				}

				// Determines singular version of the asset type and icon.
				var geopserve_under_label_type = "";
				var geopserve_under_label_icon = "";
				switch(geopserve_cat_in){
					case "Datasets":
						geopserve_under_label_type = "<strong>Dataset</strong>";
						geopserve_under_label_icon = "icon-dataset is-themed u-text--huge"
						break;
					case "Services":
						geopserve_under_label_type = "<strong>Service</strong>";
						geopserve_under_label_icon = "icon-service is-themed u-text--huge"
						break;
					case "Layers":
						geopserve_under_label_type = "<strong>Layer</strong>";
						geopserve_under_label_icon = "icon-layer is-themed u-text--huge"
						break;
					case "Maps":
						geopserve_under_label_type = "<strong>Map</strong>";
						geopserve_under_label_icon = "icon-map is-themed u-text--huge"
						break;
					case "Galleries":
						geopserve_under_label_type = "<strong>Gallery</strong>";
						geopserve_under_label_icon = "icon-gallery is-themed u-text--huge"
						break;
					default:
						geopserve_under_label_type = "<strong>Unknown</strong>";
						geopserve_under_label_icon = "icon-dataset is-themed u-text--huge"
						break;
				}

				// Determines the author's name.
				var geopserve_under_label_name = "Unknown User";
				if (typeof geopserve_results[i].createdBy != 'undefined')
					geopserve_under_label_name = geopserve_results[i].createdBy;

				// Sets up a GeoPlatform Search endpoint.
				var geopserve_under_label_href = geopserve_home + "/geoplatform-search/";
				if (typeof geopserve_results[i].createdBy != 'undefined')
					geopserve_under_label_href = geopserve_home + "/geoplatform-search/#/?createdBy=" + geopserve_under_label_name;

				// Finds the creation date.
				var geopserve_under_label_created = "Unkown creation time";
				if (typeof geopserve_results[i].created != 'undefined'){
					var geopserve_temp_date = new Date(geopserve_results[i].created);
					geopserve_under_label_created = "created " + geopserve_temp_date.toLocaleString('en-us', { month: 'short' }) + " " + geopserve_temp_date.getDate() + ", " + geopserve_temp_date.getFullYear();
				}

				// Finds the last modified date, or subs in creation date if not found.
				var geopserve_under_label_modified = "Unknown modification time";
				if (typeof geopserve_results[i].modified != 'undefined'){
					var geopserve_temp_date = new Date(geopserve_results[i].modified);
					geopserve_under_label_modified = "last modified " + geopserve_temp_date.toLocaleString('en-us', { month: 'short' }) + " " + geopserve_temp_date.getDate() + ", " + geopserve_temp_date.getFullYear();
				}
				else if (typeof geopserve_results[i].modified === 'undefined' && typeof geopserve_results[i].created != 'undefined'){
					var geopserve_temp_date = new Date(geopserve_results[i].created);
					geopserve_under_label_modified = "last modified " + geopserve_temp_date.toLocaleString('en-us', { month: 'short' }) + " " + geopserve_temp_date.getDate() + ", " + geopserve_temp_date.getFullYear();
				}

				// Finds the description.
				var geopserve_under_label_description = "No description for this asset has been provided.";
				if (typeof geopserve_results[i].description != 'undefined')
					geopserve_under_label_description = geopserve_results[i].description;

				// Packages the under label data in an array for feeding to the generator.
				var geopserve_under_label_array = [geopserve_under_label_icon, geopserve_under_label_type, geopserve_under_label_name, geopserve_under_label_href, geopserve_under_label_created, geopserve_under_label_modified, geopserve_under_label_description];

				// String for the ID of the div containing the assets.
				var geopserve_master_div = 'geopserve_carousel_gen_div_' + geopserve_iter_in;

				// Modifies the 404 for proper syntax.
				var geopserve_thumb_error = "this.src='" + geopserve_404_in + "'";

				// Feeds all this prep work into the generator.
				geopserve_gen_list_element(geopserve_thumb_src, geopserve_asset_link, geopserve_label_text, geopserve_master_div, geopserve_thumb_error, geopserve_new_tab, geopserve_under_label_array);
			}
		})
		.catch(function (error) {
			errorSelector.show();
			workingSelector.hide();
			pagingSelector.hide();
		});
}( jQuery );

// Element creation and attachment. Takes a bunch of arguments and uses them to
// construct each asset in the carousel.
//
// #param geopserve_thumb_src: source href for the thumbnail.
// #param geopserve_asset_link: URL to this element's Item Details page.
// #param geopserve_label_text: Title of the asset.
// #param geopserve_master_div: String for the ID of the div containing the assets.
// #param geopserve_thumb_error: string for the 404 error image if no thumb exists.
// #param geopserve_new_tab: Determines if a pane opens in a new window or not.
// #param geopserve_under_label_array: Array of elements for the text under the title.
//
function geopserve_gen_list_element(geopserve_thumb_src, geopserve_asset_link, geopserve_label_text, geopserve_master_div, geopserve_thumb_error, geopserve_new_tab, geopserve_under_label_array){

	// Creates each element as variables.
	var master_div = geopserve_createEl({type: 'div', class: 'm-results-item'});
	var main_div = geopserve_createEl({type: 'div', class: 'm-results-item__body'});
	var icon_div = geopserve_createEl({type: 'div', class: 'm-results-item__icon m-results-item__icon--sm'});
	var icon_span = geopserve_createEl({type: 'span', class: geopserve_under_label_array[0]});
	var body_div = geopserve_createEl({type: 'div', class: 'flex-1'});
	var head_div = geopserve_createEl({type: 'div', class: 'm-results-item__heading'});
	var head_href = geopserve_createEl({type: 'a', href: geopserve_asset_link , target: '_blank', html: geopserve_label_text});
	var mid_div = geopserve_createEl({type: 'div', class: 'm-results-item__facets'});
	var top_span = geopserve_createEl({type: 'span', class: 'm-results-item__type', html: geopserve_under_label_array[1]});
	var top_sub_span = geopserve_createEl({type: 'span', html: " by "});
	var top_sub_href = geopserve_createEl({type: 'a', class: 'is-linkless', href: geopserve_under_label_array[3], html: geopserve_under_label_array[2], target: '_blank'});
	var first_gap = document.createTextNode(" | ");
	var mid_span = geopserve_createEl({type: 'span', html: geopserve_under_label_array[4]});
	var second_gap = document.createTextNode(" | ");
	var bottom_span = geopserve_createEl({type: 'span', html: geopserve_under_label_array[5]});
	var sub_div = geopserve_createEl({type: 'div', class: 'm-results-item__description', html: geopserve_under_label_array[6]});

	// Creates thumbnail image only if asset possesses a thumbnail.
	if (geopserve_thumb_src !== 'undefined')
		var thumb_img = geopserve_createEl({type: 'img', class: 'm-results-item__icon t--large', alt: 'Thumbnail', src: geopserve_thumb_src, onerror: geopserve_thumb_error});

	// Appends them to each-other in the desired order.
	icon_div.appendChild(icon_span);

	head_div.appendChild(head_href);

	top_sub_span.appendChild(top_sub_href);
	top_span.appendChild(top_sub_span);

	mid_div.appendChild(top_span);
	mid_div.appendChild(first_gap);
	mid_div.appendChild(mid_span);
	mid_div.appendChild(second_gap);
	mid_div.appendChild(bottom_span);

	body_div.appendChild(head_div);
	body_div.appendChild(mid_div);
	body_div.appendChild(sub_div);

	main_div.appendChild(icon_div);
	main_div.appendChild(body_div);

	// Attaches the thumbnail image only if it exists.
	if (geopserve_thumb_src !== 'undefined')
		main_div.appendChild(thumb_img);

	master_div.appendChild(main_div);

	// Attaches elements to the master div.
	document.getElementById(geopserve_master_div).appendChild(master_div);
}

// Grabs results fromm the Client-API.
function geopserve_list_retrieve_objects(query, geopserve_ual) {
	var deferred = Q.defer();
	var service = new GeoPlatform.ItemService(geopserve_ual, new GeoPlatform.JQueryHttpClient());
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
	if(geopserve_el_atts.class)
		new_el.setAttribute('class', geopserve_el_atts.class);
	if(geopserve_el_atts.style)
		new_el.setAttribute('style', geopserve_el_atts.style);
	if(geopserve_el_atts.href)
		new_el.setAttribute('href', geopserve_el_atts.href);
	if(geopserve_el_atts.target)
		new_el.setAttribute('target', geopserve_el_atts.target);
	if(geopserve_el_atts.span)
		new_el.setAttribute('alt', geopserve_el_atts.alt);
	if(geopserve_el_atts.src)
		new_el.setAttribute('src', geopserve_el_atts.src);
	if(geopserve_el_atts.onerror)
		new_el.setAttribute('onerror', geopserve_el_atts.onerror);
	return new_el;
}
