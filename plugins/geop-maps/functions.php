<?php
//Set the proper environment
$geop_env = 'dev';
$geop_dev = 'dev';
$geop_stg = 'stg';

//Adding Global Variables for URL endpoints in each environment
if($geop_dev == $geop_env) {
  //Begin development Block
  $geop_maps_url = "https://sit-maps.geoplatform.us";
  $geop_viewer_url = "https://sit-viewer.geoplatform.us";
  $geop_marketplace_url = "https://sit-marketplace.geoplatform.us";
  $geop_dashboard_url = "https://sit-dashboard.geoplatform.us/lma?surveyId=8&page=0&size=500&sortElement=title&sortOrder=asc&colorTheme=green";
  $geop_wpp_url = "https://sit.geoplatform.us";
  $geop_ual_url = "https://sit-ual.geoplatform.us";
  $geop_ckan_mp_url = "https://sit-ckan.geoplatform.us/?progress=planned&h=Marketplace";
  $geop_ckan_url = "https://sit-ckan.geoplatform.us/";
  $geop_cms_url = "https://sit-cms.geoplatform.us/resources";
  $geop_idp_url = "https://sitidp.geoplatform.us";
  $geop_sd_url = "servicedesk@geoplatform.us";
}
elseif($geop_stg == $geop_env) {
  $geop_maps_url = "https://stg-maps.geoplatform.gov";
  $geop_viewer_url = "https://stg-viewer.geoplatform.gov";
  $geop_marketplace_url = "https://stg-marketplace.geoplatform.gov";
  $geop_dashboard_url = "https://stg-dashboard.geoplatform.gov/lma?surveyId=8&page=0&size=500&sortElement=title&sortOrder=asc&colorTheme=green";
  $geop_wpp_url = "https://stg.geoplatform.gov";
  $geop_ual_url = "https://stg-ual.geoplatform.gov";
  $geop_ckan_mp_url = "https://stg-ckan.geoplatform.gov/?progress=planned&h=Marketplace";
  $geop_ckan_url = "https://stg-ckan.geoplatform.gov/";
  $geop_cms_url = "https://stg-cms.geoplatform.gov/resources";
  $geop_idp_url = "https://stg-idp.geoplatform.us";
  $geop_sd_url = "servicedesk@geoplatform.gov";
}
else {
  $geop_maps_url = "https://maps.geoplatform.gov";
  $geop_viewer_url = "https://viewer.geoplatform.gov";
  $geop_marketplace_url = "https://marketplace.geoplatform.gov";
  $geop_dashboard_url = "https://dashboard.geoplatform.gov/lma?surveyId=8&page=0&size=500&sortElement=title&sortOrder=asc&colorTheme=green";
  $geop_wpp_url = "https://geoplatform.gov";
  $geop_ual_url = "https://ual.geoplatform.gov";
  $geop_ckan_mp_url = "https://ckan.geoplatform.gov/?progress=planned&h=Marketplace";
  $geop_ckan_url = "https://ckan.geoplatform.gov/";
  $geop_cms_url = "https://cms.geoplatform.gov/resources";
  $geop_idp_url = "https://idp.geoplatform.gov";
  $geop_sd_url = "servicedesk@geoplatform.gov";
}
?>
