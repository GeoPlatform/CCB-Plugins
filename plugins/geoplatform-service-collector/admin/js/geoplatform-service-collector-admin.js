(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	 /* This is the document ready jQuery block, which contains the button press
    * detectors for the add and remove map buttons. In both cases, when a button
		* is pressed, the necessary data is collected and sent to the admin-ajax.php
		* function which passes that info off to the associated function in geoplatform-maps.php,
		* which IN TURN pass them off to the associated files in admin/partials. Once
		* the operation is performed, any issues encountered will be shown in an
		* alert window before the page reloads.
   */
  jQuery(document).ready(function() {
    jQuery("#geopserve_add_action").click(function(e){
			var data = {
			  action: "geopserve_add_action",
				serve_name: jQuery("#serve_name_in").val(),
	      serve_id: jQuery("#serve_id_in").val(),
	      serve_count: jQuery("#serve_count").val(),
				serve_cat_dat: jQuery("#serve_cat_data").is(":checked"),
				serve_cat_ser: jQuery("#serve_cat_serve").is(":checked"),
				serve_cat_lay: jQuery("#serve_cat_layer").is(":checked"),
				serve_cat_map: jQuery("#serve_cat_map").is(":checked"),
				serve_cat_gal: jQuery("#serve_cat_gallery").is(":checked"),
				serve_cat_org: jQuery("#serve_cat_organ").is(":checked"),
				serve_cat_con: jQuery("#serve_cat_contact").is(":checked")
			};
			jQuery.post(ajaxurl, data, function(response){
			  if (response)
			 	  alert(response);
			 	location.reload();
			});
			return false;
    });

    jQuery(".geopserve_indiv_car_remove_action").click(function(e){
		  var data = {
				action: "geopserve_remove_action",
				serve_rand: jQuery(this).val()
			};
			jQuery.post(ajaxurl, data, function(response){
				if (response)
					alert(response);
				location.reload();
			});
			return false;
    });
  });
})( jQuery );