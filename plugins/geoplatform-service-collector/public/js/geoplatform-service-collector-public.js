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

// Community list window creator. Called during each loop of the carousel, so
// will only have to deal with one data type at a time. Generate the panes of
// the carousel.
//
// #param geopserve_options: object containing all values to be processed.
// #param geopserve_query: query if doing a live label filter.
function geopserve_gen_list(geopserve_options, geopserve_query){

	// Service collection setup.
	const Query = GeoPlatformClient.Query;
	let itemSvc = new GeoPlatformClient.ItemService(geopserve_options.ual_domain, new GeoPlatformClient.JQueryHttpClient());
	var query = new Query();
	var countQuery = new Query();

	// Sets the type of asset to grab.
	query.setTypes(geopserve_typeGrab(geopserve_options.cat_name));

	// Sets return count, current page, and sortation style.
	query.setPageSize(geopserve_options.per_page);
	query.setPage(geopserve_options.current_page);
	query.setSort(geopserve_options.sort_style);

	// Cleans, explodes, combines, and applies community and usedby criteria.
	var geopserve_com_use_array = '';
	if (geopserve_options.community_id){
		var geopserve_community_temp = geopserve_options.community_id.replace(/ /g, "-").replace(/,/g, "-").split("-");
		geopserve_com_use_array = geopserve_com_use_array.concat(geopserve_community_temp);
	}
	if (geopserve_options.usedby_id){
		var geopserve_usedby_temp = geopserve_options.usedby_id.replace(/ /g, "-").replace(/,/g, "-").split("-");
		geopserve_com_use_array = geopserve_com_use_array.concat(geopserve_usedby_temp);
	}
	if (geopserve_com_use_array != undefined && geopserve_com_use_array.length > 0)
		query.usedBy(geopserve_com_use_array);

	// Cleans, explodes, and applies theme criteria.
	if (geopserve_options.theme_id){
		var geopserve_theme_array = geopserve_options.theme_id.replace(/ /g, "-").replace(/,/g, "-").split("-");
		query.setThemes(geopserve_theme_array);
	}

	// Cleans, explodes, combines, and applies title/label and query criteria.
	if (geopserve_options.label_id || geopserve_query){
		var geopserve_label_array = (geopserve_options.label_id) ? geopserve_options.label_id.replace(/,/g, "-").split("-") : [];
		var geopserve_query_array = (geopserve_query) ? geopserve_query.replace(/,/g, "-").split("-") : [];
		var geopserve_q_array = geopserve_label_array.concat(geopserve_query_array);
		for (i = 0; i < geopserve_q_array.length; i++)
			geopserve_q_array[i] = '"' + geopserve_q_array[i] + '"';
		query.setQ(geopserve_q_array);
		console.log(geopserve_q_array);
	}

	// Cleans, explodes, and applies keyword criteria.
	if (geopserve_options.keyword_id){
		var geopserve_keyword_array = geopserve_options.keyword_id.replace(/ /g, "-").replace(/,/g, "-").split("-");
		query.setKeywords(geopserve_keyword_array);
	}

	// Cleans, explodes, and applies topic criteria.
	if (geopserve_options.topic_id){
		var geopserve_topic_array = geopserve_options.topic_id.replace(/ /g, "-").replace(/,/g, "-").split("-");
		query.setTopics(geopserve_topic_array);
	}

	// Cleans, explodes, and applies classifier criteria.
	if (geopserve_options.class_id){
		var geopserve_class_array = geopserve_options.class_id.replace(/ /g, "-").replace(/,/g, "-").split("-");
		query.setClassifier(geopserve_options.kg_id, geopserve_class_array);
	}

	// Adds thumbnails and clone-of to the query return.
	var fields = query.getFields();
	fields.push("thumbnail");
	fields.push("_cloneOf");
	query.setFields(fields);

	// Performs the query grab.
	itemSvc.search(query)
		.then(function (response) {
			console.log(response);

			// Determines the object ID to which the generated text will apply.
			var geopserve_browseall_div = 'geopserve_carousel_search_div_' + geopserve_options.iter;

			// "browse all number asset type" text attachement, only fires in geop
			// search mode.
			if (geopserve_options.search_state == 'geop'){

				// Determines singular, plural, or empty results text.
				var geopserve_search_text = 'Browse all ' + response.totalResults + " " + geopserve_options.cat_name;
				if (response.totalResults == 1){
					var geopserve_cat_single = geopserve_options.cat_name;
					geopserve_cat_single = geopserve_cat_single.replace("ies", "ys");
					geopserve_cat_single = geopserve_cat_single.substring(0, geopserve_cat_single.length-1);
					geopserve_search_text = 'Browse ' + response.totalResults + " " + geopserve_cat_single;
				}
				if (response.totalResults <= 0)
					geopserve_search_text = 'No ' + geopserve_options.cat_name.toLowerCase() + ' to browse';

				// Attaches the text.
				document.getElementById(geopserve_browseall_div).innerHTML = geopserve_search_text;
			}

			// Gets the results.
			var geopserve_results = response.results;

			// Sets result output minimum.
			var geopserve_max_panes = geopserve_options.per_page;
			if (response.totalResults < geopserve_options.per_page)
				geopserve_max_panes = response.totalResults;

			// Pane generation loop.
			for (var i = 0; i < geopserve_max_panes; i++){

				// Grabs the id and uses it to construct an item details href.
				var geopserve_asset_link = geopserve_options.redirect + geopserve_results[i].id;

				// Sets the title of the asset.
				var geopserve_label_text = geopserve_results[i].label;

				// Sets thumbnail to undefined, then replaces with the thumbnail namespace
				// from UAL if the asset has a valid one.
				var geopserve_thumb_src = 'undefined';
				if (geopserve_results[i].hasOwnProperty('thumbnail')){
					if (geopserve_results[i].thumbnail.hasOwnProperty('url') || geopserve_results[i].thumbnail.hasOwnProperty('contentData')){
						geopserve_thumb_src = geopserve_options.ual_domain + "/api/items/" + geopserve_results[i].id + "/thumbnail";
					}
				}

				// Determines singular version of the asset type and icon.
				var geopserve_typeGen_results = geopserve_typeGen(geopserve_options.cat_name);
				var geopserve_under_label_type = geopserve_typeGen_results.type;
				var geopserve_under_label_icon = geopserve_typeGen_results.icon;

				// Determines the author's name.
				var geopserve_under_label_name = "Unknown User";
				if (typeof geopserve_results[i].createdBy != 'undefined')
					geopserve_under_label_name = geopserve_results[i].createdBy;

				// Sets up a GeoPlatform Search endpoint.
				var geopserve_under_label_href = geopserve_options.home + "/geoplatform-search/";
				if (typeof geopserve_results[i].createdBy != 'undefined')
					geopserve_under_label_href = geopserve_options.home + "/geoplatform-search/#/?createdBy=" + geopserve_under_label_name;

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
				var geopserve_master_div = 'geopserve_carousel_gen_div_' + geopserve_options.iter + geopserve_options.current_suffix;

				// Modifies the 404 for proper syntax.
				var geopserve_thumb_error = "this.src='" + geopserve_options.failsafe + "'";

				// Sets clone value, if present.
				var geopserve_clone_val = "none";
				if (geopserve_results[i].hasOwnProperty('_cloneOf'))
					geopserve_clone_val = geopserve_options.redirect + geopserve_results[i]._cloneOf;

				// Feeds all this prep work into the generator.
				geopserve_gen_list_element(geopserve_thumb_src, geopserve_asset_link, geopserve_label_text, geopserve_master_div, geopserve_thumb_error, geopserve_under_label_array, geopserve_options.iter, geopserve_clone_val);
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
// #param geopserve_under_label_array: Array of elements for the text under the title.
// #param geopserve_iter_in: current interval of the creation process.
// #param geopserve_clone_val: "none" if not cloned, URL of the original element's Item Details page if so.
function geopserve_gen_list_element(geopserve_thumb_src, geopserve_asset_link, geopserve_label_text, geopserve_master_div, geopserve_thumb_error, geopserve_under_label_array, geopserve_iter_in, geopserve_clone_val){

	// Constructs ID of master div.
	var master_div_id = 'geopserve-carousel-master-div-' + geopserve_iter_in;

	// Creates each element as a variable.
	var master_div = geopserve_createEl({type: 'div', class: 'm-results-item', id: master_div_id});
	var main_div = geopserve_createEl({type: 'div', class: 'm-results-item__body'});
	var icon_div = geopserve_createEl({type: 'div', class: 'm-results-item__icon m-results-item__icon--sm'});
	var icon_span = geopserve_createEl({type: 'span', class: geopserve_under_label_array[0]});
	var body_div = geopserve_createEl({type: 'div', class: 'flex-1'});
	var head_div = geopserve_createEl({type: 'div', class: 'm-results-item__heading'});
	var head_href = geopserve_createEl({type: 'a', href: geopserve_asset_link, target: '_blank', html: geopserve_label_text});
	var mid_div = geopserve_createEl({type: 'div', class: 'm-results-item__facets'});
	var top_span = geopserve_createEl({type: 'span', class: 'm-results-item__type', html: geopserve_under_label_array[1]});
	var top_sub_span = geopserve_createEl({type: 'span', html: " by "});
	var top_sub_href = geopserve_createEl({type: 'a', class: 'is-linkless', href: geopserve_under_label_array[3], html: geopserve_under_label_array[2], target: '_blank'});
	var first_gap = document.createTextNode(" | ");
	var mid_span = geopserve_createEl({type: 'span', html: geopserve_under_label_array[4]});
	var second_gap = document.createTextNode(" | ");
	var bottom_span = geopserve_createEl({type: 'span', html: geopserve_under_label_array[5]});
	var sub_div = geopserve_createEl({type: 'div', class: 'm-results-item__description', html: geopserve_under_label_array[6]});

	// Creates cloned dif if necessary.
	if (geopserve_clone_val != "none"){
		var clone_div = geopserve_createEl({type: 'div', class: 'm-results-item__facets'});
		var clone_icon = geopserve_createEl({type: 'span', class: 'fas fa-clone t-fg--gray-md'});
		var clone_text = document.createTextNode(" Cloned from ");
		var clone_href = geopserve_createEl({type: 'a', href: geopserve_clone_val, html: 'another item', target: '_blank'});
	}

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

	if (geopserve_clone_val != "none"){
		clone_div.appendChild(clone_icon);
		clone_div.appendChild(clone_text);
		clone_div.appendChild(clone_href);
	}

	body_div.appendChild(head_div);
	body_div.appendChild(mid_div);
	if (geopserve_clone_val != "none"){
		body_div.appendChild(clone_div);
	}
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
	if(geopserve_el_atts.id)
		new_el.setAttribute('id', geopserve_el_atts.id);
	return new_el;
}

// Takes the asset type name and uses it to determine the item type to filter
// query results by.
function geopserve_typeGrab(geopserve_cat_in){

	const ItemTypes = GeoPlatformClient.ItemTypes;

	var geopserve_typeMap = {
		Datasets: ItemTypes.DATASET,
		Services: ItemTypes.SERVICE,
		Layers: ItemTypes.LAYER,
		Maps: ItemTypes.MAP,
		Galleries: ItemTypes.GALLERY,
		Communities: ItemTypes.COMMUNITY,
		Applications: ItemTypes.APPLICATION,
		Topics: ItemTypes.TOPIC,
		Websites: ItemTypes.WEBSITE
	}

	return geopserve_typeMap[geopserve_cat_in];
}

// Performs similar to above, but with HTML values for output.
function geopserve_typeGen(geopserve_cat_in){

	var geopserve_typeMap = {
		Datasets: {
			type: "<strong>Dataset</strong>",
			icon: "icon-dataset is-themed u-text--huge",
		},
		Services: {
			type: "<strong>Service</strong>",
			icon: "icon-service is-themed u-text--huge",
		},
		Layers: {
			type: "<strong>Layer</strong>",
			icon: "icon-layer is-themed u-text--huge",
		},
		Maps: {
			type: "<strong>Map</strong>",
			icon: "icon-map is-themed u-text--huge",
		},
		Galleries: {
			type: "<strong>Gallery</strong>",
			icon: "icon-gallery is-themed u-text--huge",
		},
		Communities: {
			type: "<strong>Communities</strong>",
			icon: "icon-community is-themed u-text--huge",
		},
		Applications: {
			type: "<strong>Applications</strong>",
			icon: "icon-application is-themed u-text--huge",
		},
		Topics: {
			type: "<strong>Topics</strong>",
			icon: "icon-topic is-themed u-text--huge",
		},
		Websites: {
			type: "<strong>Websites</strong>",
			icon: "icon-website is-themed u-text--huge",
		},
		Default: {
			type: "<strong>Unknown</strong>",
			icon: "icon-dataset is-themed u-text--huge",
		}
	}

	return geopserve_typeMap[geopserve_cat_in];
}





// Community asset count applicator. Called during each loop of the carousel, so
// will only have to deal with one data type at a time. Will discern how many
// copies exist of the given asset type and apply that number to the search area.
//
// THIS FUNCTION IS DEPRECATED AND ONLY REMAINS FOR REFERENCE.
// IT WILL BE DELETED AFTER CODE CLEANUP.
//
// #param geopserve_id_array: array of strings for each asset type to query.
// #param geopserve_cat_in: the data type for the query.
// #param geopserve_iter_in: iter of the loop in which this function is called, used for element attachement.
// #param geopserve_ual_domain_in: UAL source to draw from.
// function geopserve_gen_count(geopserve_id_array, geopserve_cat_in, geopserve_iter_in, geopserve_ual_domain_in){
//
// 	// Translates id array into individual query strings for each asset type.
// 	var geopserve_community_id = geopserve_id_array[0];
// 	var geopserve_theme_id = geopserve_id_array[1];
// 	var geopserve_label_id = geopserve_id_array[2];
// 	var geopserve_keyword_id = geopserve_id_array[3];
// 	var geopserve_topic_id = geopserve_id_array[4];
// 	var geopserve_usedby_id = geopserve_id_array[5];
// 	var geopserve_class_id = geopserve_id_array[6];
// 	var geopserve_kg_id = geopserve_id_array[7];
//
// 	// Service collection setup.
// 	const Query = GeoPlatformClient.Query;
// 	const ItemTypes = GeoPlatformClient.ItemTypes;
// 	let itemSvc = new GeoPlatformClient.ItemService(geopserve_ual_domain_in, new GeoPlatformClient.JQueryHttpClient());
//
// 	var query = new Query();
//
// 	// Sets type of asset type to grab.
// 	if (geopserve_cat_in == "Datasets")
// 		query.setTypes(ItemTypes.DATASET);
// 	if (geopserve_cat_in == "Services")
// 		query.setTypes(ItemTypes.SERVICE);
// 	if (geopserve_cat_in == "Layers")
// 		query.setTypes(ItemTypes.LAYER);
// 	if (geopserve_cat_in == "Maps")
// 		query.setTypes(ItemTypes.MAP);
// 	if (geopserve_cat_in == "Galleries")
// 		query.setTypes(ItemTypes.GALLERY);
// 	if (geopserve_cat_in == "Communities")
// 		query.setTypes(ItemTypes.COMMUNITY);
// 	if (geopserve_cat_in == "Applications")
// 		query.setTypes(ItemTypes.APPLICATION);
// 	if (geopserve_cat_in == "Topics")
// 		query.setTypes(ItemTypes.TOPIC);
// 	if (geopserve_cat_in == "Websites")
// 		query.setTypes(ItemTypes.WEBSITE);
//
// 	// Cleans, explodes, combines, and applies community and usedby criteria.
// 	var geopserve_com_use_array = '';
// 	if (geopserve_community_id){
// 		var geopserve_community_temp = geopserve_community_id.replace(/ /g, "-");
// 		geopserve_community_temp = geopserve_community_temp.replace(/,/g, "-");
// 		geopserve_community_array = geopserve_community_temp.split("-");
// 		geopserve_com_use_array = geopserve_com_use_array.concat(geopserve_community_array);
// 	}
// 	if (geopserve_usedby_id){
// 		var geopserve_usedby_temp = geopserve_usedby_id.replace(/ /g, "-");
// 		geopserve_usedby_temp = geopserve_usedby_temp.replace(/,/g, "-");
// 		geopserve_usedby_array = geopserve_usedby_temp.split("-");
// 		geopserve_com_use_array = geopserve_com_use_array.concat(geopserve_usedby_array);
// 	}
// 	if (geopserve_com_use_array != undefined && geopserve_com_use_array.length > 0)
// 		query.usedBy(geopserve_com_use_array);
//
// 	// Cleans, explodes, and applies theme criteria.
// 	if (geopserve_theme_id){
// 		var geopserve_theme_temp = geopserve_theme_id.replace(/ /g, "-");
// 		geopserve_theme_temp = geopserve_theme_temp.replace(/,/g, "-");
// 		geopserve_theme_array = geopserve_theme_temp.split("-");
// 		query.setThemes(geopserve_theme_array);
// 	}
//
// 	// Cleans, explodes, combines, and applies title/label criteria.
// 	var geopserve_label_array = '';
// 	if (geopserve_label_id){
// 		var geopserve_label_temp = geopserve_label_id.replace(/,/g, "-");
// 		geopserve_label_array = geopserve_label_temp.split("-");
// 		for (i = 0; i < geopserve_label_array.length; i++)
// 			geopserve_label_array[i] = '"' + geopserve_label_array[i] + '"';
// 		query.setQ(geopserve_label_array);
// 	}
//
// 	// Cleans, explodes, and applies keyword criteria.
// 	if (geopserve_keyword_id){
// 		var geopserve_keyword_temp = geopserve_keyword_id.replace(/ /g, "-");
// 		geopserve_keyword_temp = geopserve_keyword_temp.replace(/,/g, "-");
// 		geopserve_keyword_array = geopserve_keyword_temp.split("-");
// 		query.setKeywords(geopserve_keyword_array);
// 	}
//
// 	// Cleans, explodes, and applies topic criteria.
// 	if (geopserve_topic_id){
// 		var geopserve_topic_temp = geopserve_topic_id.replace(/ /g, "-");
// 		geopserve_topic_temp = geopserve_topic_temp.replace(/,/g, "-");
// 		geopserve_topic_array = geopserve_topic_temp.split("-");
// 		query.setTopics(geopserve_topic_array);
// 	}
//
// 	// Cleans, explodes, and applies classifier criteria.
// 	if (geopserve_class_id){
// 		var geopserve_class_temp = geopserve_class_id.replace(/ /g, "-");
// 		geopserve_class_temp = geopserve_class_temp.replace(/,/g, "-");
// 		geopserve_class_array = geopserve_class_temp.split("-");
// 		query.setClassifier(geopserve_kg_id, geopserve_class_array);
// 	}
//
// 	// Performs the query grab.
// 	// geopserve_list_retrieve_objects(query, geopserve_ual_domain_in)
// 	itemSvc.search(query)
// 	.then(function (response) {
//
// 		// console.log(response.totalResults);
//
// 		// Variables for the text and page element it's to be attached to.
// 		var geopserve_master_div = 'geopserve_carousel_search_div_' + geopserve_iter_in;
//
// 		// Determines singular, plural, or empty results text.
// 		var geopserve_search_text = 'Browse all ' + response.totalResults + " " + geopserve_cat_in;
// 		if (response.totalResults == 1){
// 			var geopserve_cat_single = geopserve_cat_in;
// 			geopserve_cat_single = geopserve_cat_single.replace("ies", "ys");
// 			geopserve_cat_single = geopserve_cat_single.substring(0, geopserve_cat_single.length-1);
// 			geopserve_search_text = 'Browse ' + response.totalResults + " " + geopserve_cat_single;
// 		}
// 		if (response.totalResults <= 0)
// 			geopserve_search_text = 'No ' + geopserve_cat_in.toLowerCase() + ' to browse';
//
// 		// Creates the text and attaches it.
// 		geop_search_node = document.createTextNode(geopserve_search_text);
// 		document.getElementById(geopserve_master_div).appendChild(geop_search_node);
// 	})
// 	.catch(function (error) {
// 		errorSelector.show();
// 		workingSelector.hide();
// 		pagingSelector.hide();
// 	});
// }( jQuery );
