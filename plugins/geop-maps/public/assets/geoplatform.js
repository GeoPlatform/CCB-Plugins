(function () {
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
})();
