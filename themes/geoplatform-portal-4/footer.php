<div>
  <footer class="o-footer">

      <a class="u-float--right" href="#">
          <span class="fas fa-arrow-up"></span>
          to top
      </a>

      <div>
          <a onClick="toggleClass('#footer-megamenu','is-collapsed')">
              <span class="fas fa-angle-down"></span>
              Menu
          </a>
          <hr>
      </div>

      <nav class="m-megamenu is-collapsed" id="footer-megamenu">

          <div class="m-megamenu__content">

              <div class="col">
                  <div class="m-megamenu__heading">Featured</div>
                  <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>
                  <!-- <ul class="menu" role="menu">
                      <li role="menuitem"><a href="https://sit.geoplatform.us/release-overview/">Release Overview</a></li>
                      <li role="menuitem"><a href="https://sit.geoplatform.us/map-collaboration/">Map Collaboration</a></li>
                      <li role="menuitem"><a href="https://sit.geoplatform.us/arcgis-online-support/">ArcGIS Online Support</a></li>
                      <li role="menuitem"><a href="https://sit.geoplatform.us/cloud-hosting-services/">Cloud Hosting Services</a></li>
                      <li role="menuitem"><a target="_blank" href="https://www.youtube.com/watch?v=rgd8vCLnvfo&amp;list=PLMCNwMDgTQxB-yC5xy20dtEoXCRfG3M63">GeoPlatform on YouTube <sup><span class="glyphicon glyphicon-new-window"></span></sup></a></li>
                  </ul> -->
                  <br>
                  <div class="m-megamenu__heading">Get Involved</div>
                  <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?>
                  <!-- <ul class="menu" role="menu">
                      <li role="menuitem"><a href="https://sit.geoplatform.us/share-geospatial-resources/">Share GeoSpatial Resources</a></li>
                      <li role="menuitem"><a href="https://sit.geoplatform.us/data-acquisition-services/">Data Acquisition Services</a></li>
                      <li role="menuitem"><a href="https://sit.geoplatform.us/communities/">Communities</a></li>
                  </ul> -->
              </div>


              <div class="col">
                  <div class="m-megamenu__heading">Explore Data</div>
                  <ul class="menu" role="menu">
                      <li role="menuitem">
                          <a href="<?php echo $GLOBALS['geopccb_oe_url']; ?>" target="_blank">
                              Object Editor
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo esc_url('https://geoplatform.maps.arcgis.com/home/'); ?>" target="_blank">
                            GeoPlatform ArcGIS Organization
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo $GLOBALS['geopccb_ckan_url']; ?>" target="_blank">
                            Search Data.gov
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo $GLOBALS['geopccb_ckan_mp_url']; ?>" target="_blank">
                            Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo $GLOBALS['geopccb_cms_url']; ?>" target="_blank">
                            Resources <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                  </ul>
                  <br>
                  <a class="m-megamenu__heading" href="<?php echo home_url() . '/' . get_theme_mod('headlink_apps'); ?>">Apps &amp; Services</a>
                  <ul class="menu" role="menu">
                      <li role="menuitem">
                          <a href="<?php echo $GLOBALS['geopccb_viewer_url']; ?>" target="_blank">
                              Map Viewer
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                      </li>
                      <li role="menuitem">
                          <a href="<?php echo $GLOBALS['geopccb_maps_url']; ?>" target="_blank">
                              Map Manager
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                      </li>
                      <li role="menuitem">
                          <a href="<?php echo $GLOBALS['geopccb_marketplace_url']; ?>" target="_blank">
                              Marketplace Preview
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                      </li>
                      <li role="menuitem">
                          <a href="<?php echo $GLOBALS['geopccb_dashboard_url'] . '#/lma?surveyId=8&amp;page=0&amp;size=500&amp;sortElement=title&amp;sortOrder=asc&amp;colorTheme=green' ?>" target="_blank">
                              Performance Dashboard
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                      </li>
                      <li role="menuitem">
                          <a href="<?php echo esc_url('http://statuschecker.fgdc.gov'); ?>" target="_blank">
                              FGDC Service Status Checker
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                       </li>
                  </ul>
              </div>



              <div class="col">
                  <div class="m-megamenu__heading">About</div>
                  <?php wp_nav_menu( array( 'theme_location' => 'headfoot-about' ) ); ?>
                  <!-- <ul class="menu" role="menu">
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
                              <span class="u-text-/-sm">Geospatial Interoperability Reference Architecture</span>
                          </a>
                      </li>
                  </ul> -->
              </div>

          </div>

          <hr>

      </nav>

      <div class="l-flex-container flex-justify-between flex-align-center">
          <div class="a-brand u-mg-right--xlg">
              <img alt="GP" src="<?php echo get_stylesheet_directory_uri() . '/img/logo.svg' ?>" style="width:1em">
              <a href="<?php echo home_url() ?>/">GeoPlatform.gov</a>
          </div>

          <div>
              <a href="<?php echo home_url() . '/' . get_theme_mod('footlink_terms'); ?>" target="_blank">Terms of Use</a>
              &nbsp;|&nbsp;
              <a href="<?php echo home_url() . '/' . get_theme_mod('footlink_two'); ?>" target="_blank">Link</a>
              &nbsp;|&nbsp;
              <a href="<?php echo home_url() . '/' . get_theme_mod('footlink_three'); ?>" target="_blank">Link</a>
          </div>
      </div>

      <br>

      <small>
          The GeoPlatform was developed by the member agencies of the
          Federal Geographic Data Committee (FGDC) through collaboration
          with partners and stakeholders. The target audience for the
          GeoPlatform includes Federal agencies, State, local, and
          Tribal governments, private sector, academia, and the general
          public.
      </small>

  </footer>

</div>


<?php wp_footer();?>
</body>
</html>
