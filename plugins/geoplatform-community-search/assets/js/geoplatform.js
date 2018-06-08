(function () {
  if (window.GeoPlatform === undefined) {
    window.GeoPlatform = {
      //REQUIRED: environment the application is deployed within
      // one of "development", "sit", "stg", "prd", or "production"
      "env": "development",

      "wmvUrl": "https://viewer.geoplatform.gov",
      "oeUrl": "https://oe.geoplatform.gov",

      //REQUIRED: URL to GeoPlatform UAL for API usage
      "ualUrl": "https://ual.geoplatform.gov",

      //timeout max for requests
      "timeout": "5000",

      //{env}-{id} of application deployed
      "appId": "geopcomsearch-plugin"
    };
  }
})();
