<div class="search section--linked">

  <h4 class="heading">
    <div class="line"></div>
    <div class="line-arrow"></div>
  </h4>

  <br>

  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <div class="input-group-slick input-group-slick--lg">
        <span class="glyphicon glyphicon-search"></span>
        <input id="geoplatformsearchfield" type="text" placeholder="Search the GeoPlatform" class="form-control input-lg"> 
        <button type="button" class="btn btn-primary"
          onclick="window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatformsearchfield').val()">
          Search
        </button>
        <!--
        ^ THIS BUTTON SHOULD SEND USER TO THE SEARCH PLUGIN PAGE WHEN CLICKED AND SHOULD SEND
        THE CONTENTS OF THE INPUT ELEMENT ABOVE IT AS THE "q=" QUERY STRING PARAMETER.
        -->
        </div>
      </div>
    </div>
  </div>

  <br>
  <br>

  <div class="footing">
    <div class="line-cap"></div>
    <div class="line"></div>
  </div>

</div>
