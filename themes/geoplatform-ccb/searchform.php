<?php
/**
 * The template for default search form
 *
 * HTML5 basic search form. Used for reference
 * @link http://buildwpyourself.com/wordpress-search-form-template/
 *
 * @package GeoPlatform CCB
 *
 * @since 3.1.0
 */

// GP styled search form
echo "<form role='search' method='get' id='search-form' action='" . esc_url( home_url( '/' ) ) . "'>";
  echo "<div class='input-group-slick geop-header-search-min-site'>";
    echo "<span class='icon fas fa-search'></span>";
    echo "<input type='search' class='form-control' style='padding-right:5em' placeholder='" . esc_attr( 'Search Site...', 'geoplatform-ccb' ) . "' name='s' id='search-input' aria-label='Search the Site' value='" . esc_attr( get_search_query() ) . "'/>";
    echo "<button type='submit' class='btn btn-default' id='search-submit'>" . __( 'Search', 'geoplatform-ccb') . "</button>";
  echo "</div>";
echo "</form>";
