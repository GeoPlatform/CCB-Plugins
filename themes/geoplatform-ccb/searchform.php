<?php
/**
 * default search form
 */
?>
<!-- HTML5 basic search form. Used for reference
http://buildwpyourself.com/wordpress-search-form-template/ -->
<!-- GP styled search form -->
<form role="search" method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<div class="input-group-slick">
      <span class="glyphicon glyphicon-search"></span>
      <input type="search" class="form-control" placeholder="<?php echo esc_attr( 'Search...', 'geoplatform-ccb' ); ?>"
      name="s" id="search-input" value="<?php echo esc_attr( get_search_query() ); ?>"/>
      <button type="submit" class="btn btn-default" id="search-submit"><?php _e( 'Search', 'geoplatform-ccb'); ?></button>
  </div>
</form>
