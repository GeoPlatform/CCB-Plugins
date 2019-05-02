<?php
get_header();
$geopccb_theme_options = geop_ccb_get_theme_mods();

// Sectioning of pages, the Loop setup, pagination, and general flow of a lot of this theme came from
// https://www.taniarascia.com/developing-a-wordpress-theme-from-scratch/

echo "<div class='l-body p-landing-page' role='main'>";

if (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) == 'below')
  geop_ccb_lower_community_links();

if ( is_active_sidebar( 'geoplatform-widgetized-page' ) ) {
  echo "<div id='widgetized-page'>";
    dynamic_sidebar( 'geoplatform-widgetized-page' );
  echo "</div>";
}

echo "</div>";

get_footer();
?>
