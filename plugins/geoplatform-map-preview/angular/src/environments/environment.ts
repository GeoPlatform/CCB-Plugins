// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
    production: false,
    env: 'development',
    ualUrl: 'https://sit-ual.geoplatform.us',
    //ualUrl: 'http://localhost:4040',         //TESTING SAVES LOCALLY
    wpUrl: '',
    root: '/',
    assets: '/assets',
    rpmUrl: 'https://sit-rpm.geoplatform.us',
    rpmToken: 'f822e078fcb182110b5de9deeba85e7e',
    logLevel: 'debug'
};
