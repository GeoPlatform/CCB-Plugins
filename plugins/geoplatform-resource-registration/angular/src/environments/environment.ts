// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
    production: false,
    env: 'development',

    //url to UAL for this plugin's environment
    ualUrl: 'https://sit-ual.geoplatform.us',

    //url to Portal (which will be diff if this plugin is deployed in a CCB)
    portalUrl: 'https://sit.geoplatform.us',

    //url to the WP instance hosting this plugin
    wpUrl: '',

    root: '/',
    assets: 'assets/'
};
