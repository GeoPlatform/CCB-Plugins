<?php
/**
 * The template for Single post and page banners
 *
 * @link https://codex.wordpress.org/Sidebars
 * 
 * @package GeoPlatform CCB
 * 
 * @since 3.0.0
 */
?>

<div class="sidebar-module sidebar-module-inset">

  <?php if ( is_active_sidebar( 'geoplatform-widgetized-area' ) ) : ?>
      <div id="widgetized-area">
        <?php dynamic_sidebar( 'geoplatform-widgetized-area' ); ?>
      </div><!-- widgetized-area -->
  <?php endif; ?>
</div> <!-- sidebar-module sidebar-module-inset -->
