<?php
/**
 * Template Name: GeoPlatform Registration Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
 get_header();
 get_template_part( 'mega-menu', get_post_format() );
 get_template_part( 'single-banner', get_post_format() );
?>


<app-root></app-root>

<?php get_footer(); ?>
