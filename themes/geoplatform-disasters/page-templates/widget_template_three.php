<?php
/**
 * Template Name: Widget Page Three Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
get_header();
get_template_part( 'sub-header-post', get_post_format() );

echo "<div class='l-body l-body--one-column'>";
  echo "<div class='l-body__main-column'>";
    dynamic_sidebar( 'geop-disasters-widget-page-three' );
  echo "</div>";
echo "</div>";
get_footer();
