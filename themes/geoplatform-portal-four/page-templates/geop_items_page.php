<?php
/**
 * Template Name: GeoPlatform Items Template
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-templates/
 *
 * @package Geoplatform CCB
 *
 * @since 2.0.0
 */
get_header();
// get_template_part( 'sub-header-post', get_post_format() );

global $wp_query;
global $wp;
?>

<script> 
    window.GeoPlatformSearchPluginEnv = { 
        wpUrl: "<?php bloginfo('wpurl') ?>" 
    }; 
</script>
<app-root></app-root>

<?php get_footer(); ?>
