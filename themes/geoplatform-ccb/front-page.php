<?php
get_header();

// Sectioning of pages, the Loop setup, pagination, and general flow of a lot of this theme came from
// https://www.taniarascia.com/developing-a-wordpress-theme-from-scratch/

echo "<div class='l-body p-landing-page' role='main'>";

if ( is_active_sidebar( 'geoplatform-widgetized-page' ) ) {
  echo "<div id='widgetized-page'>";
    dynamic_sidebar( 'geoplatform-widgetized-page' );
  echo "</div>";
}

echo "</div>";

get_footer();
?>
