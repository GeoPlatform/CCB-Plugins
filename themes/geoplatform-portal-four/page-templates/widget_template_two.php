<?php
/**
 * Template Name: Widget Template Two
 *
 *https://developer.wordpress.org/themes/template-files-section/page-templates/
 */
//include header area
get_header(); ?>

<!--Sectioning of pages, the Loop setup, pagination, and general flow of a lot of this theme came from
https://www.taniarascia.com/developing-a-wordpress-theme-from-scratch/ -->
<?php //get_template_part( 'mega-menu', get_post_format() ); ?>

<div class="l-body p-landing-page" role="main">

  <!--GeoPlatform header with intro -->
<?php get_template_part( 'sub-header-post', get_post_format() ); ?>

<?php if ( is_active_sidebar( 'geoplatform-widgetized-page-two' ) ) : ?>
    <div id="widgetized-page">
      <?php dynamic_sidebar( 'geoplatform-widgetized-page-two' ); ?>
    </div><!-- widgetized-area -->
<?php endif; ?>

</div>
<!--Footer section-->
<?php get_footer(); ?>
