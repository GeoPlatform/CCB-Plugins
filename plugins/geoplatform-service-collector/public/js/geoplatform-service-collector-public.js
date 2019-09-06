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
function geopserve_gen_list(geopserve_options){

	// Service collection setup.
	const Query = geoplatform.client.Query;
	// const Classifiers = GeoPlatformClient.KGClassifiers;
	let itemSvc = new geoplatform.client.ItemService(geopserve_options.ual_domain, new geoplatform.client.XHRHttpClient());
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
	if (geopserve_options.label_id || geopserve_options.query_var){
		var geopserve_label_array = (geopserve_options.label_id) ? geopserve_options.label_id.replace(/,/g, "-").split("-") : [];
		var geopserve_query_array = (geopserve_options.query_var) ? geopserve_options.query_var.replace(/,/g, "-").split("-") : [];
		var geopserve_q_array = geopserve_label_array.concat(geopserve_query_array);
		for (i = 0; i < geopserve_q_array.length; i++)
			geopserve_q_array[i] = '"' + geopserve_q_array[i] + '"';
		query.setQ(geopserve_q_array);
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
		query.classifier(geopserve_options.kg_id, geopserve_class_array);
	}

	// Adds thumbnails and clone-of to the query return.
	var fields = query.getFields();
	fields.push("thumbnail");
	fields.push("_cloneOf");
	query.setFields(fields);

	// Performs the query grab.
	itemSvc.search(query)
		.then(function (response) {

			// Determines the object ID to which the generated text will apply.
			var geopserve_browseall_div = 'geopserve_carousel_search_div_' + geopserve_options.current_tab;

			var geopserve_cat_single = geopserve_options.cat_name;
			geopserve_cat_single = geopserve_cat_single.replace("ies", "ys");
			geopserve_cat_single = geopserve_cat_single.substring(0, geopserve_cat_single.length-1);

			// "browse all number asset type" text attachement, only fires in geop
			// search mode.
			if (geopserve_options.search_state == 'geop'){

				// Determines singular, plural, or empty results text.
				var geopserve_search_text = 'Browse all ' + response.totalResults + " " + geopserve_options.cat_name;
				if (response.totalResults == 1){
					// var geopserve_cat_single = geopserve_options.cat_name;
					// geopserve_cat_single = geopserve_cat_single.replace("ies", "ys");
					// geopserve_cat_single = geopserve_cat_single.substring(0, geopserve_cat_single.length-1);
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
				var geopserve_asset_link = geopserve_options.redirect_url + geopserve_results[i].id;

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
				var geopserve_under_label_href = geopserve_options.search_url;
				if (typeof geopserve_results[i].createdBy != 'undefined')
					geopserve_under_label_href = geopserve_options.search_url + geopserve_under_label_name;

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
				var geopserve_master_div = 'geopserve_carousel_gen_div_' + geopserve_options.current_tab + geopserve_options.current_suffix;

				// Modifies the 404 for proper syntax.
				var geopserve_thumb_error = "this.src='" + geopserve_options.failsafe + "'";

				// Sets clone value, if present.
				var geopserve_clone_val = "none";
				if (geopserve_results[i].hasOwnProperty('_cloneOf'))
					geopserve_clone_val = geopserve_options.redirect_url + geopserve_results[i]._cloneOf;

				// Makes it an object.
				var geopserve_gen_element = {
					thumb_src: geopserve_thumb_src,
					asset_link: geopserve_asset_link,
					label_text: geopserve_label_text,
					master_div: geopserve_master_div,
					thumb_error: geopserve_thumb_error,
					label_icon: geopserve_under_label_icon,
					label_type: geopserve_under_label_type,
					label_name: geopserve_under_label_name,
					label_href: geopserve_under_label_href,
					label_created: geopserve_under_label_created,
					label_modified: geopserve_under_label_modified,
					label_description: geopserve_under_label_description,
					iter: geopserve_options.current_tab,
					clone_val: geopserve_clone_val,
				}

				// Feeds all this prep work into the generator.
				geopserve_gen_list_element(geopserve_gen_element);
			}

			// RPM Reporting
			try {
				if(typeof RPMService != 'undefined') {
					// Log Search
					RPM.logSearch(query.query)
				} else {
					console.log("Error: Unable to track Asset usage -- RPM library not loaded")
				}
			} catch(err) {
				console.log("Error: Error reporting Map usage to RPM: ", err)
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
// #param geopserve_gen_element: object containing data to process.
function geopserve_gen_list_element(geopserve_gen_element){

	// Constructs ID of master div.
	var master_div_id = 'geopserve-carousel-master-div-' + geopserve_gen_element.iter;

	// Creates each element as a variable.
	var master_div = geopserve_createEl({type: 'div', class: 'm-results-item', id: master_div_id});
	var main_div = geopserve_createEl({type: 'div', class: 'm-results-item__body'});
	var icon_div = geopserve_createEl({type: 'div', class: 'm-results-item__icon m-results-item__icon--sm'});
	var icon_span = geopserve_createEl({type: 'span', class: geopserve_gen_element.label_icon});
	var body_div = geopserve_createEl({type: 'div', class: 'flex-1'});
	var head_div = geopserve_createEl({type: 'div', class: 'm-results-item__heading'});
	var head_href = geopserve_createEl({type: 'a', href: geopserve_gen_element.asset_link, target: '_blank', html: geopserve_gen_element.label_text});
	var mid_div = geopserve_createEl({type: 'div', class: 'm-results-item__facets'});
	var top_span = geopserve_createEl({type: 'span', class: 'm-results-item__type', html: geopserve_gen_element.label_type});
	var top_sub_span = geopserve_createEl({type: 'span', html: " by "});
	var top_sub_href = geopserve_createEl({type: 'a', class: 'is-linkless', href: geopserve_gen_element.label_href, html: geopserve_gen_element.label_name, target: '_blank'});
	var first_gap = document.createTextNode(" | ");
	var mid_span = geopserve_createEl({type: 'span', html: geopserve_gen_element.label_created});
	var second_gap = document.createTextNode(" | ");
	var bottom_span = geopserve_createEl({type: 'span', html: geopserve_gen_element.label_modified});
	var sub_div = geopserve_createEl({type: 'div', class: 'm-results-item__description', html: geopserve_gen_element.label_description});

	// Creates cloned dif if necessary.
	if (geopserve_gen_element.clone_val != "none"){
		var clone_div = geopserve_createEl({type: 'div', class: 'm-results-item__facets'});
		var clone_icon = geopserve_createEl({type: 'span', class: 'fas fa-clone t-fg--gray-md'});
		var clone_text = document.createTextNode(" Cloned from ");
		var clone_href = geopserve_createEl({type: 'a', href: geopserve_gen_element.clone_val, html: 'another item', target: '_blank'});
	}

	// Creates thumbnail image only if asset possesses a thumbnail.
	if (geopserve_gen_element.thumb_src !== 'undefined')
		var thumb_img = geopserve_createEl({type: 'img', class: 'm-results-item__icon t--large', alt: geopserve_gen_element.label_text, src: geopserve_gen_element.thumb_src, onerror: geopserve_gen_element.thumb_error});

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

	if (geopserve_gen_element.clone_val != "none"){
		clone_div.appendChild(clone_icon);
		clone_div.appendChild(clone_text);
		clone_div.appendChild(clone_href);
	}

	body_div.appendChild(head_div);
	body_div.appendChild(mid_div);
	if (geopserve_gen_element.clone_val != "none"){
		body_div.appendChild(clone_div);
	}
	body_div.appendChild(sub_div);

	main_div.appendChild(icon_div);
	main_div.appendChild(body_div);

	// Attaches the thumbnail image only if it exists.
	if (geopserve_gen_element.thumb_src !== 'undefined')
		main_div.appendChild(thumb_img);

	master_div.appendChild(main_div);

	// Attaches elements to the master div.
	document.getElementById(geopserve_gen_element.master_div).appendChild(master_div);
}

// Grabs results fromm the Client-API.
function geopserve_list_retrieve_objects(query, geopserve_ual) {
	var deferred = Q.defer();
	var service = new GeoPlatform.ItemService(geopserve_ual, new GeoPlatform.XHRHttpClient());
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
	if(geopserve_el_atts.alt)
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

	const ItemTypes = geoplatform.client.ItemTypes;

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
