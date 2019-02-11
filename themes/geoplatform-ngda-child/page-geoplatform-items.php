<?php
/**
 * Template Name: GeoPlatform Items Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 * @subpackage geoplatform-portal-four
 * @since 2.0.0
 */
get_header();
get_template_part( 'single-banner', get_post_format() );

global $wp_query;
global $wp;
?>

<app-root></app-root>

<?php get_footer(); ?>
