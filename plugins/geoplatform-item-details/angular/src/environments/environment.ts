// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
  production: false,
  env: 'dev',
  ualUrl: 'https://sit-ual.geoplatform.us',
  wpUrl: '',
  editUrl: 'https://sit-oe.geoplatform.us/edit/{id}',
  svcHistoryUrl: 'https://sit-dashboard.geoplatform.us/api/sd/service/{id}/history',
  helpUrl: 'https://sit.geoplatform.us/help/apps/geoplatform-item-details/',
  root: 'wp-content/plugins/geoplatform-item-details/',
  assets: 'assets/'
};
