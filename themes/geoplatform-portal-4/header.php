<?php
// Getting theme mods for search bar and mega-menu hiding checks.
$geopccb_theme_options = geop_ccb_get_theme_mods();
?>
<html lang="en-us">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>GP Theme v2</title>
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

  <?php
  $font_choice = get_theme_mod( 'font_choice' );
  if( $font_choice != '' ) {
    switch ( $font_choice ) {
      case 'lato':
        echo '<style type="text/css">';
        echo "body { font-family: 'Lato', sans-serif !important;}";
        echo '</style>';
        break;
      case 'slabo':
        echo '<style type="text/css">';
        echo "body { font-family: 'Slabo 27px', serif !important; }";
        echo '</style>';
        break;
        //add more cases for more fonts later
    }
  }

  wp_head();?>
</head>
<header class="o-header" role="banner">

    <div class="o-header__primary">

        <!--
            REMOVE THIS COMMENT WHEN IMPLEMENTING...
            Use H1 on .a-brand b/c 508 requires an H1 to appear near the top of
            a page for screen readers to know where to start. Note that the
            .a-brand class overrides any styles set by H1-H6, so no worries as
            long as no blanket styles are defined for H1-H6.
        -->
        <h1 class="a-brand">
            <img alt="GP" src="<?php echo get_stylesheet_directory_uri() . '/img/logo.svg' ?>" style="width:1em">
            <span>GeoPlatform.gov</span>
        </h1>

        <nav class="a-nav" role="navigation" aria-label="High-level navigation links" role="menu">
            <a role="menuitem" class="is-hidden--xs" href="/">Home</a>
            <!-- <div class="a-nav__collapsible-menu"> -->
                <a role="menuitem" class="is-hidden--xs is-hidden--sm" href="/secondary.html">Pages</a>
                <a role="menuitem" class="is-hidden--xs is-hidden--sm" href="/items/dataset.html">Portfolio</a>
            <!-- </div> -->
            <a role="menuitem" class="is-hidden--xs" href="/help.html">Help</a>
            <a role="menuitem" class="is-linkless" onclick="toggleClass('#header-megamenu','is-open')">
                <span class="is-hidden--xs">More</span>
                <span class="fas fa-bars is-hidden--sm is-hidden--md is-hidden--lg"></span>
            </a>
            <a role="menuitem" href="#">
                <span class="fas fa-user"></span>
                <span class="is-hidden--xs">Sign In</span>
            </a>
        </nav>

    </div>

    <div class="o-header__secondary">

        <div class="a-page__title">Page Title</div>

        <div class="a-search">
            <div class="icon fas fa-search"></div>
            <div class="m-search-box">
                <div class="input-group-slick">
                    <span class="icon fas fa-search"></span>
                    <input type="text" class="form-control"
                        placeholder="SEARCH THE GEOPLATFORM"
                        aria-label="Search the GeoPlatform"
                        onKeyUp="if(arguments[0].keyCode===13)window.location.href='/search.html'">
                </div>
            </div>
        </div>

    </div>

    <nav class="m-megamenu" id="header-megamenu">

        <div class="m-megamenu__content">

            <div class="col">
                <div class="m-megamenu__heading">Featured</div>
                <ul class="menu" role="menu">
                    <li role="menuitem"><a href="https://sit.geoplatform.us/release-overview/">Release Overview</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/map-collaboration/">Map Collaboration</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/arcgis-online-support/">ArcGIS Online Support</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/cloud-hosting-services/">Cloud Hosting Services</a></li>
                    <li role="menuitem"><a target="_blank" href="https://www.youtube.com/watch?v=rgd8vCLnvfo&amp;list=PLMCNwMDgTQxB-yC5xy20dtEoXCRfG3M63">GeoPlatform on YouTube <sup><span class="glyphicon glyphicon-new-window"></span></sup></a></li>
                </ul>
                <br>
                <div class="m-megamenu__heading">Get Involved</div>
                <ul class="menu" role="menu">
                    <li role="menuitem"><a href="https://sit.geoplatform.us/share-geospatial-resources/">Share GeoSpatial Resources</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/data-acquisition-services/">Data Acquisition Services</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/communities/">Communities</a></li>
                </ul>
            </div>


            <div class="col">
                <div class="m-megamenu__heading">Explore Data</div>
                <ul class="menu" role="menu">
                    <li role="menuitem">
                        <a href="https://sit-oe.geoplatform.us" target="_blank">
                            Object Editor
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                  </li>
                  <li role="menuitem">
                      <a href="https://geoplatform.maps.arcgis.com/home/" target="_blank">
                          GeoPlatform ArcGIS Organization
                          <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="https://sit-data.geoplatform.us/" target="_blank">
                          Search Data.gov
                          <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="https://sit-marketplace.geoplatform.us/" target="_blank">
                          Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="https://sit.geoplatform.us/geoplatform-resources/" target="_blank">
                          Resources <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                </ul>
                <br>
                <a class="m-megamenu__heading" href="/apps.html">Apps &amp; Services</a>
                <ul class="menu" role="menu">
                    <li role="menuitem">
                        <a href="https://sit-viewer.geoplatform.us" target="_blank">
                            Map Viewer
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="https://sit-maps.geoplatform.us" target="_blank">
                            Map Manager
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="https://sit-marketplace.geoplatform.us" target="_blank">
                            Marketplace Preview
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="https://sit-dashboard.geoplatform.us/#/lma?surveyId=8&amp;page=0&amp;size=500&amp;sortElement=title&amp;sortOrder=asc&amp;colorTheme=green" target="_blank">
                            Performance Dashboard
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="http://statuschecker.fgdc.gov" target="_blank">
                            FGDC Service Status Checker
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                     </li>
                </ul>
            </div>


            <div class="col">
                <div class="m-megamenu__heading">About</div>
                <ul class="menu" role="menu">
                    <li role="menuitem"><a href="/help.html">Help</a></li>
                    <li role="menuitem"><a href="/faq.html">FAQ</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/glossary-of-terms/">Glossary of Terms</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/contact-us/">Contact Us</a></li>
                    <li role="menuitem"><a href="/styleguide">Style Guide</a></li>
                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-965">
                        <a href="https://sit-ccb.geoplatform.us/">Dynamic Communities</a>
                    </li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/data-policies/">Data Policy</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/privacy-policy/">Privacy Policy</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/accessibility/">Accessibility</a></li>
                    <li role="menuitem"><a href="https://sit.geoplatform.us/news/">News</a></li>
                    <li role="menuitem">
                        <a href="https://gira.geoplatform.gov/">
                            GIRA-
                            <span class="u-text--sm">Geospatial Interoperability Reference Architecture</span>
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <button type="button" class="btn btn-link btn-block" onclick="toggleClass('#header-megamenu','is-open')">
            <span class="fas fa-caret-up"></span>
        </button>
    </nav>

</header>