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
// get_template_part( 'sub-header-post', get_post_format() );

global $wp_query;
global $wp;

$geopitems_idp = isset($_ENV['idp_url']) ? $_ENV['idp_url'] : 'https://idp.geoplatform.gov';
?>

<script>
  window.GeoPlatformPluginEnv = {
      wpUrl: "<?php bloginfo('wpurl') ?>"
  };
  window.GeoPlatform = {
    IDP_BASE_URL: "<?php echo $geopitems_idp ?>", // Where IDP is
    APP_BASE_URL: "<?php echo home_url() ?>", // root dir for site (ex: 'https://geoplatform.gov' or 'https://communities.geoplatform.gov/ngda-wildbeasts'
    LOGIN_URL: "<?php echo wp_login_url() ?>",
    LOGOUT_URL: "<?php echo wp_logout_url() ?>",
  };
</script>

<app-root></app-root>

<?php get_footer(); ?>
