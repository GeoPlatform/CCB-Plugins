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

})( jQuery );


jQuery(document).ready( function($){

	jQuery(document).on('click', '#add_map_button', function (e) {
			e.preventDefault();				// prevent page reload and form submission.
       var member = jQuery('#add_map_text').val();
       $.ajax({
           type: "GET",
           url: "http://localhost/wp-content/plugins/geop-maps/admin/partials/geop-maps-admin-add-map.php",
           data: {
               param: member,
           },
           dataType: "json",
					 asynch: false,
           success: function(r){
              location.reload();
           },
       });

   });
 });
