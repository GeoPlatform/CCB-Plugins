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
    jQuery("#geopmap_add_action").click(function(e){
			var data = {
			  action: "geopmap_add_action",
				map_id: jQuery("#map_id_in").val(),
	      map_height: jQuery("#map_height").val(),
	      map_width: jQuery("#map_width").val()
			};
			jQuery.post(ajaxurl, data, function(response){
			  if (response)
			 	  alert(response);
			 	location.reload();
			});
			return false;
    });

    jQuery(".geopmap_indiv_remove_action").click(function(e){
		  var data = {
				action: "geopmap_remove_action",
				map_id: jQuery(this).val()
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
