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

	// The only operation this page actually does is delete and recreate the search page.
	// Checks first for the specifically-designed GeoPlatform search page template,
	// then the full-page template with added hook text. If neither found, creates a
	// basic page with the hook text.
	jQuery('document').ready(function(){
		// jQuery('#geopsearch_reset').click(function(){
    //   var data = {
		// 		action: "geopsearch_refresh",
	  //   };
		// 	jQuery.post(ajaxurl, data, function(response){
		// 		if (response)
		// 			alert(response);
		// 		location.reload();
		// 	});
		// 	return false;
    // });

		jQuery('#geopsearch_reset').click(function(){
      var data = {
				action: "geopsearch_site_search",
				key_one: 'temp_val_#1',
				key_two: 'temp_val_#2',
				key_three: 'temp_val_#3',
				key_four: 'temp_val_#4',
				key_five: 'temp_val_#5',
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
