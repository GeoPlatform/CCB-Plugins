<?php
// Secondary header, used for the home page.
global $wp;
$geopccb_theme_options = geop_ccb_get_theme_mods();

if (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) == 'below')
  geop_ccb_lower_community_links();

if (get_theme_mod('breadcrumb_controls', $geopccb_theme_options['breadcrumb_controls']) == 'on'){
  echo "<ul class='m-page-breadcrumbs'>";
    echo "<li><a href='" . home_url() . "/'>Home</a></li>";
    echo "<li><a href='#'/>";
      printf( esc_html__( 'Search Results for: %s', 'geoplatform-ccb' ), "<span>" . get_search_query() . '</span>' );
    echo "</a></li>";
  echo "</ul>";
}