// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.

export const environment = {
  production: true,
  env: 'stg',
  ualUrl: 'https://stg-ual.geoplatform.gov',
  wpUrl: '',
  editUrl: 'https://stg-oe.geoplatform.gov/edit/{id}',
  svcHistoryUrl: 'https://stg-dashboard.geoplatform.gov/api/sd/service/{id}/history',
  helpUrl: 'https://stg.geoplatform.gov/help/apps/geoplatform-item-details/',
  root: 'wp-content/plugins/geoplatform-item-details/',
  assets: '/wp-content/plugins/geoplatform-item-details/assets'
};
