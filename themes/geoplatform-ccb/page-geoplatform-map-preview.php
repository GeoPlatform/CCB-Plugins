<?php
/**
 * Name: GeoPlatform Map Preview Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 * @subpackage geoplatform-portal-four
 * @since 2.0.0
 */
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
  the_content();
endwhile; endif;

get_footer(); ?>
