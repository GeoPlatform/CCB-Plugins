
<!--include header area-->
<?php get_header(); ?>

<!--Sectioning of pages, the Loop setup, pagination, and general flow of a lot of this theme came from
https://www.taniarascia.com/developing-a-wordpress-theme-from-scratch/ -->
<?php get_template_part( 'mega-menu', get_post_format() ); ?>
  <!--GeoPlatform header with intro -->
<?php get_template_part( 'gp_intro', get_post_format() ); ?>

<!--WP Features and Announcements-->
<?php get_template_part( 'mainpage', get_post_format() ); ?>

<!-- Search bar section from GeoPlatform only if plugin is active -->
<?php
  if (in_array( 'geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array() ) ))
    get_template_part( 'gpsearch', get_post_format() );
?>

<!-- Cornerstone Actions section from GeoPlatform-->
<?php get_template_part( 'cornerstones', get_post_format() ); ?>

<!-- Applications & Services section from GeoPlatform-->
<?php get_template_part( 'apps-and-services', get_post_format() ); ?>

<!--Featured Services section-->
<?php get_template_part( 'featured', get_post_format() ); ?>

<!-- First Time section for unregistered users -->
<!-- My Account for authenticated users-->
<?php get_template_part( 'first-time', get_post_format() ); ?>

<!--Footer section-->
<?php get_footer(); ?>
