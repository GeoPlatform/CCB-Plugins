(function () {
  if (window.GeoPlatform === undefined) {
    window.GeoPlatform = {
      //REQUIRED: environment the application is deployed within
      // one of "development", "sit", "stg", "prd", or "production"
      "env": "development",

      "wmvUrl": "https://sit-viewer.geoplatform.us",
      "oeUrl": "https://sit-oe.geoplatform.us", 

      //REQUIRED: URL to GeoPlatform UAL for API usage
      "ualUrl": "https://sit-ual.geoplatform.us",

      //timeout max for requests
      "timeout": "5000",

      //{env}-{id} of application deployed
      "appId": "gpsearch-plugin"
    };
  }
})();