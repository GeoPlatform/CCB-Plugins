<div class="sidebar-module sidebar-module-inset">

  <!--Community specific information-->
    <?php if ( !is_active_sidebar( 'geoplatform-widgetized-area' ) ) {
      get_template_part( 'community-info', get_post_format() );
    }
    if ( is_active_sidebar( 'geoplatform-widgetized-area' ) ) : ?>
    	     <div id="widgetized-area">
    		      <?php dynamic_sidebar( 'geoplatform-widgetized-area' ); ?>
    	     </div><!-- widgetized-area -->
    <?php endif; ?>
</div> <!-- sidebar-module sidebar-module-inset -->
