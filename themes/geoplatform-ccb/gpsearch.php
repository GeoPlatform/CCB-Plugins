
<!-- Search bar section. -->

<form id="geoplatformsearchform">
  <div class="input-group-slick">
    <span class="glyphicon glyphicon-search"></span>
    <input type="text" class="form-control" id="geoplatformsearchfield" placeholder="<?php echo esc_attr( 'Search GeoPlatform...', 'geoplatform-ccb' ); ?>"/>
    <button type="submit" class="btn btn-default" id="geoplatformsearchbutton"><?php _e( 'Search', 'geoplatform-ccb'); ?></button>
  </div>
</form>

<script>

// Code section. First jQuery triggers off of form submission (enter button) and
// navigates to the geoplatform-search page with the search field params.
  jQuery( "#geoplatformsearchform" ).submit(function( event ) {
    event.preventDefault();
    window.location.href='<?php echo home_url('geoplatform-search') ?>/#/?q='+jQuery('#geoplatformsearchfield').val();
  });

// Functionally identical to above, triggered by submit button press.
  jQuery( "#geoplatformsearchbutton" ).click(function( event ) {
    window.location.href='<?php echo home_url('geoplatform-search') ?>/#/?q='+jQuery('#geoplatformsearchfield').val();
  });
</script>
