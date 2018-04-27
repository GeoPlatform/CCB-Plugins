<div class="sidebar-module sidebar-module-inset">

  <!--Community specific information-->
    <?php get_template_part( 'community-info', get_post_format() ); ?>


    <?php if ( is_active_sidebar( 'geoplatform-widgetized-area' ) ) : ?>
      <div class="card">
    	     <div id="widgetized-area">
    		      <?php dynamic_sidebar( 'geoplatform-widgetized-area' ); ?>
    	     </div><!-- widgetized-area -->
      </div><!-- card -->
    <?php endif; ?>
</div> <!-- sidebar-module sidebar-module-inset -->
