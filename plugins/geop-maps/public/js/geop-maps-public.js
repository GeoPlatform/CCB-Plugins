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

	 // GeoPlatform object framework for use by Client-API and MapCore.
	 if (window.GeoPlatform === undefined) {
    window.GeoPlatform = {
		 		 //REQUIRED: environment the application is deployed within
		 		 // one of "development", "sit", "stg", "prd", or "production"
		 		 "env" : "development",

		 		 //REQUIRED: URL to GeoPlatform UAL for API usage
		 		 "ualUrl" : "https://ual.geoplatform.gov",

		 		 //Object Editor URL.
		 		 "oeUrl" : "https://oe.geoplatform.gov",

		 		 //timeout max for requests
		 		 "timeout" : "5000",

		 		 //identifier of GP Layer to use as default base layer
		 		 "defaultBaseLayerId" : "209573d18298e893f21e6064b23c8638",

		 		 //{env}-{id} of application deployed
		 		 "appId" : "development-mv"
     };
   }
})( jQuery );
