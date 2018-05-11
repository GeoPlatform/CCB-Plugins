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
    * detectors for the add and remove map buttons. With add, it collects the
    * necessary information from the input boxes and calls the addition AJAX
    * method below.
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

    /* The remove button handler, which functions on class due to the procedural
     * nature of the remove buttons being evoked. Grabs the value of the pressed
     * button, which is the map ID, and passes it to the remove AJAX method.
    */
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

   /* This is the actual AJAX call. It gathers the data for passing to the function,
    * then, within a jQuery.ajax() call, passes the necessary parameters along with
    * console error reporting actions and a force page reload. It also checks for
    * any data echoed back from the add file, indicating an error, and sends it
    * out as an alert to the user.
   */

 });
})( jQuery );
