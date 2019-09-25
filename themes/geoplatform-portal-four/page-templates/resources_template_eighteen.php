<?php
/**
 * Template Name: Resources Pages Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
 get_header();
 get_template_part( 'sub-header-post', get_post_format() );
 ?>

 <div class="l-body l-body--two-column p-ngda">
   <div class="l-body__main-column">
           <?php dynamic_sidebar( 'geoplatform-resources-template-widgets-eighteen' ); ?>
   </div>
   <?php get_template_part( 'sidebar', get_post_format() ); ?>
 </div>
 <?php get_footer(); ?>
