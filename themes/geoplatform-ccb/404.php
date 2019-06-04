<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */
$geopccb_theme_options = geop_ccb_get_theme_mods();
get_header();

if (get_theme_mod('breadcrumb_controls', $geopccb_theme_options['breadcrumb_controls']) == 'on'){
  echo "<ul class='m-page-breadcrumbs'>";
    echo "<li><a href='" . home_url() . "/'>Home</a></li>";
    echo "<li><a href='" . home_url($wp->request) . "'>404</a></li>";
  echo "</ul>";
}

echo "<div class='l-body l-body--one-column'>";
  echo "<div class='l-body__main-column'>";
		echo "<article class='m-article'>";
      echo "<br><br>";
      echo "<span class='far fa-frown u-text--gargantuan'></span>";
      echo "<div class='m-article__heading'>Page Not Found</div>";
      echo "<div class='m-article__desc'>";
				echo "<p>" . esc_html( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'geoplatform-ccb' ) . "</p>";
				get_search_form();
      echo "</div>";
    	echo "<br><br>";
    echo "</article>";
	echo "</div>";
echo "</div>";


get_footer(); ?>
