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

	// The only operation this page actually does is delete and recreate the item page.
 	// Checks first for the specifically-designed GeoPlatform Items page template,
 	// then the full-page template with added hook text. If neither found, creates a
 	// basic page with the hook text.
 	jQuery('document').ready(function(){
 		jQuery('#geopitems_reset').click(function(){
      var data = {
 				action: "geopitems_refresh",
 	    };
 			jQuery.post(ajaxurl, data, function(response){
 				if (response)
 					alert(response);
 				location.reload();
 			});
 			return false;
    });

		// Flushes the rewrite rules so that new rules can be written. Also done on
		// plugin activation and deactivation, but handy to have here. Should not
		// need to be used but rarely.
		jQuery('#geopitems_flush').click(function(){
      var data = {
 				action: "geopitems_flush",
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
