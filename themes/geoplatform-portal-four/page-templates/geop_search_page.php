<?php
/**
 * Name: GeoPlatform Search Template
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

<div class="l-body l-body--one-column">
  <div class="l-body__main-column">
    <script> window.GeoPlatformPluginEnv = { wpUrl: "<?php bloginfo('wpurl') ?>" }; </script>
    <app-root></app-root>
  </div>
</div>
<?php get_footer(); ?>
