<?php
/**
 * Template Name: Full Width Page
 *
 *https://developer.wordpress.org/themes/template-files-section/page-templates/
 */

// Checks for geoplatform theme and includes custom mimic header if found. If
// not, includes the current theme's default header.
if (strpos(strtolower(wp_get_theme()->get('Name')), 'geoplatform') !== false){
  include 'geoplatform-search-page-header.php';
}
else {
  get_header();
}

get_template_part( 'mega-menu', get_post_format() ); ?>
<!--Used for the Main banner background to show up -->

<?php get_template_part( 'single-banner', get_post_format() ); ?>

<div class="container" style="max-width:2000px; width:100%">

  <div class="row">
    <div class="loop" style="width:100%">
        <script> window.GeoPlatformSearchPluginEnv = { wpUrl: "<?php bloginfo('wpurl') ?>" }; </script>
      <app-root></app-root>
    </div><!--#loop-->
  </div><!--#row-->
</div><!--#container-->

<?php get_footer(); ?>
