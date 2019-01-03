(function () {
  if (window.GeoPlatform === undefined) {
    window.GeoPlatform = {
      //REQUIRED: environment the application is deployed within
      // one of "development", "sit", "stg", "prd", or "production"
      "env": "development",

      // Viewer URL.
      "wmvUrl": "https://viewer.geoplatform.gov",

      // Test URL.
      "testUrl": "https://ual.geoplatform.gov",

      // Object Editor URL.
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
