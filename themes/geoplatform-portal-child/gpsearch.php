<div class="search section--linked" style="background:#fff">

  <h4 class="heading">
    <div class="line"></div>
    <div class="line-arrow"></div>
  </h4>

  <br>

<!-- Search bar section. -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <form id="geoplatformsearchform">
          <div class="input-group-slick input-group-slick--lg">
            <span class="glyphicon glyphicon-search"></span>
            <input id="geoplatformsearchfield" type="text" placeholder="Search the GeoPlatform" class="form-control input-lg">
            <button id="geoplatformsearchbutton" type="button" class="btn btn-primary">Search</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>

// Code section. First jQuery triggers off of form submission (enter button) and
// navigates to the geoplatform-search page with the search field params.
  jQuery( "#geoplatformsearchform" ).submit(function( event ) {
    event.preventDefault();
    window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatformsearchfield').val();
  });

// Functionally identical to above, triggered by submit button press.
  jQuery( "#geoplatformsearchbutton" ).click(function( event ) {
    window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatformsearchfield').val();
  });
  </script>

  <br>
  <br>

  <div class="footing">
    <div class="line-cap"></div>
    <div class="line"></div>
  </div>

</div>
