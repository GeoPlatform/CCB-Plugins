<div class="sidebar-module sidebar-module-inset">

  <!--include featured-services-->
  <?php get_template_part( 'featured-services', get_post_format() ); ?>


    <?php if ( is_active_sidebar( 'geoplatform-widgetized-area' ) ) : ?>
    	     <div id="widgetized-area">
    		      <?php dynamic_sidebar( 'geoplatform-widgetized-area' ); ?>
    	     </div><!-- widgetized-area -->
    <?php endif; ?>
</div> <!-- sidebar-module sidebar-module-inset -->
