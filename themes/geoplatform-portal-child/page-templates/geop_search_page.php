<?php
/**
 * Template Name: GeoPlatform Search Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
get_header();
//get_template_part( 'mega-menu', get_post_format() );
get_template_part( 'single-banner', get_post_format() );
?>
<div class="container" style="max-width:2000px; width:100%">

    <div class="row">
      <div class="loop" style="width:100%">
        <script> window.GeoPlatformSearchPluginEnv = { wpUrl: "<?php bloginfo('wpurl') ?>" }; </script>
        <app-root></app-root>
      </div><!--#loop-->
    </div><!--#row-->
  </div><!--#container-->

  <?php get_footer(); ?>
