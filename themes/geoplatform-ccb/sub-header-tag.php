<?php
/**
 * Name: Sub-Header-Tag
 *
 * Secondary header for use with tag output pages.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
global $wp;
$geopccb_theme_options = geop_ccb_get_theme_mods();

if (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) == 'below'){
  echo "<div class='geopccb-header-bar'>";
    geop_ccb_lower_community_links();
  echo "</div>";
}

if (get_theme_mod('breadcrumb_controls', $geopccb_theme_options['breadcrumb_controls']) == 'on'){

  // gets current tag.
  $geopccb_breadcrumb_tag = get_tag($wp_query->get_queried_object_id());
  $geopccb_breadcrumb_array = array($geopccb_breadcrumb_tag);
  while ($geopccb_breadcrumb_tag->parent){
    $geopccb_breadcrumb_tag = get_tag($geopccb_breadcrumb_tag->parent);
    array_push($geopccb_breadcrumb_array, $geopccb_breadcrumb_tag);
  }

  echo "<ul class='m-page-breadcrumbs'>";
    echo "<li><a href='" . home_url() . "/'>Home</a></li>";

    // Adds breadcrumb elements from array to sub-header, starting from end to beginning of array.
    for ($i = count($geopccb_breadcrumb_array)-1; $i >= 0; $i--) {
      echo "<li><a href='" . esc_url( get_category_link( $geopccb_breadcrumb_array[$i]->term_id ) ) . "'>". esc_attr($geopccb_breadcrumb_array[$i]->name) . "</a></li>";
    }

  echo "</ul>";
}
