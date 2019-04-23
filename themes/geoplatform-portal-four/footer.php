<?php
/**
 * A GeoPlatform Footer Template
 *
 * @link https://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29
 */
?>
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

                <div class="d-lg-none d-xl-none">
                    <div class="m-megamenu__heading">Navigation</div>
                    <ul class="menu" role="menu">
                        <li role="menuitem" class="d-md-none">
                            <a href="<?php echo home_url('resources'); ?>">Explore</a>
                            <ul class="menu" role="menu">
                                <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_data')); ?>" class="u-pd-left--xlg">Datasets</a></li>
                                <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_services')); ?>" class="u-pd-left--xlg">Services</a></li>
                                <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_layers')); ?>" class="u-pd-left--xlg">Layers</a></li>
                                <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_maps')); ?>" class="u-pd-left--xlg">Maps</a></li>
                                <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_galleries')); ?>" class="u-pd-left--xlg">Galleries</a></li>
                                <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_communities')); ?>" class="u-pd-left--xlg">Communities</a></li>
                            </ul>
                        </li>
                        <li role="menuitem">
                            <a role="menuitem" href="<?php echo home_url(get_theme_mod('headlink_ngda_themes')); ?>">NGDA Themes</a>
                        </li>
                        <li role="menuitem">
                            <a role="menuitem" href="<?php echo home_url(get_theme_mod('headlink_search')); ?>">Search</a>
                        </li>
                    </ul>
                    <br>
                </div>

                <div class="m-megamenu__heading">Featured</div>
                <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>
                <br>
                <div class="m-megamenu__heading">Get Involved</div>
                <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?>
              </div>


              <div class="col">
                  <div class="m-megamenu__heading">Explore Data</div>
                  <ul class="menu" role="menu">
                      <li role="menuitem">
                          <a href="<?php echo geop_ccb_getEnv('oe_url',"https://oe.geoplatform.gov"); ?>" target="_blank">
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
                        <a href="<?php echo geop_ccb_getEnv('ckan_url',"https://data.geoplatform.gov/"); ?>" target="_blank">
                            Search Data.gov
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo geop_ccb_getEnv('ckan_mp_url',"https://data.geoplatform.gov/#/?progress=planned&h=Marketplace"); ?>" target="_blank">
                            Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo home_url('resources'); ?>" target="_blank">
                            Resources <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                  </ul>
                  <br>
                  <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_apps')); ?>">Apps &amp; Services</a>
                  <ul class="menu" role="menu">
                      <li role="menuitem">
                          <a href="<?php echo geop_ccb_getEnv('viewer_url', 'https://viewer.geoplatform.gov'); ?>" target="_blank">
                              Map Viewer
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                      </li>
                      <li role="menuitem">
                          <a href="<?php echo geop_ccb_getEnv('maps_url', 'https://maps.geoplatform.gov'); ?>" target="_blank">
                              Map Manager
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                      </li>
                      <li role="menuitem">
                          <a href="<?php echo geop_ccb_getEnv('marketplace_url',"https://marketplace.geoplatform.gov"); ?>" target="_blank">
                              Marketplace Preview
                              <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                          </a>
                      </li>
                      <li role="menuitem">
                          <a href="<?php echo geop_ccb_getEnv('dashboard_url',"https://dashboard.geoplatform.gov/#/lma?surveyId=8&page=0&size=500&sortElement=title&sortOrder=asc&colorTheme=green"); ?>" target="_blank">
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
            <?php
            if (!empty(get_theme_mod('footlink_one_text')) && !empty(get_theme_mod('footlink_one_url')))
              echo "<a href=" . esc_url(get_theme_mod('footlink_one_url')) . " target='_blank'>" . get_theme_mod('footlink_one_text') . "</a>";
            if (!empty(get_theme_mod('footlink_two_text')) && !empty(get_theme_mod('footlink_two_url')))
              echo "&nbsp;|&nbsp<a href=" . esc_url(get_theme_mod('footlink_two_url')) . " target='_blank'>" . get_theme_mod('footlink_two_text') . "</a>";
            if (!empty(get_theme_mod('footlink_three_text')) && !empty(get_theme_mod('footlink_three_url')))
              echo "&nbsp;|&nbsp<a href=" . esc_url(get_theme_mod('footlink_three_url')) . " target='_blank'>" . get_theme_mod('footlink_three_text') . "</a>";
            if (!empty(get_theme_mod('footlink_four_text')) && !empty(get_theme_mod('footlink_four_url')))
              echo "&nbsp;|&nbsp<a href=" . esc_url(get_theme_mod('footlink_four_url')) . " target='_blank'>" . get_theme_mod('footlink_four_text') . "</a>";
            if (!empty(get_theme_mod('footlink_five_text')) && !empty(get_theme_mod('footlink_five_url')))
              echo "&nbsp;|&nbsp<a href=" . esc_url(get_theme_mod('footlink_five_url')) . " target='_blank'>" . get_theme_mod('footlink_five_text') . "</a>";
            ?>
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
