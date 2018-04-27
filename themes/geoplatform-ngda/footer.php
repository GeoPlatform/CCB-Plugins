
<footer>
    <div class="container-fluid top-link-row">
        <div class="row">
            <div class="col-md-12">
                <a class="pull-right" href="#"> &#8593; top</a>
            </div>
        </div>
    </div>
    <div class="container">

        <div class="row">
            <div class="col-md-6">
                <h3>
                    <!--<img src="/img/GeoPlatform_logo_sm.png" alt="GeoPlatform Logo" style="height: 1.5rem; vertical-align: bottom;">-->
                    <a href="<?php echo $GLOBALS['wpp_url']; ?>" title="Go to the Geoplatform Home Page">
                        <span class="icon-gp"></span>
                        GeoPlatform
                    </a>
                    <a href="<?php echo get_site_url();?>" title="Go to the <?php echo get_bloginfo( 'name' ); ?> Home Page">
                    <?php echo get_bloginfo( 'name' ); ?>
                  </a>
                </h3>

            </div>
            <div class="col-md-6">
                <div class="pull-right" style="margin-top: 2rem;">
                    <!--
                    <a title="Follow us on Twitter"><span class="fa fa-facebook text-white"></span></a> &nbsp;&nbsp;
                    <a alt="Like us on Facebook"><span class="fa fa-twitter text-white"></span></a> &nbsp;&nbsp;
                    <a alt="Follow us on Instagram"><span class="fa fa-instagram text-white"></span></a>
                    -->
                </div>
            </div>
        </div>

        <hr/>


        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!--Featured location-->
                <h4 style="color:white">Featured</h4>
                    <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>


                <!-- <br class="hidden-xs">
                <h4 style="color:white">Get Involved</h4>
                <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?>
                <ul> -->

            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <h4 style="color:white">Explore Data</h4>
                <ul>
                    <li>
                        <a href="<?php echo esc_url("https://geoplatform.maps.arcgis.com/home/"); ?>" target="_blank">
                            GeoPlatform ArcGIS Organization<sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $GLOBALS['ckan_url']; ?>" target="_blank">
                            Search Catalog
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $GLOBALS['ckan_mp_url']; ?>" target="_blank">Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup></a></li>
                    <li><a href="<?php echo $GLOBALS['cms_url']; ?>" target="_blank">Resources <sup><span class="glyphicon glyphicon-new-window"></span></sup></a></li>
                </ul>
                <br class="hidden-xs">
                <h4 style="color:white">Apps &amp; Services</h4>
                <ul>
                  <li><a href="<?php echo $GLOBALS['viewer_url']; ?>" target="_blank">Map Viewer
                    <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                    </a>
                  </li>
                  <li><a href="<?php echo $GLOBALS['maps_url']; ?>" target="_blank">Map Manager
                    <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                    </a>
                  </li>
                  <li><a href="<?php echo $GLOBALS['marketplace_url']; ?>" target="_blank">Marketplace Preview
                    <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                    </a>
                  </li>
                  <li><a href="<?php echo $GLOBALS['dashboard_url']; ?>" target="_blank">Performance Dashboard
                    <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                    </a>
                  </li>
                    <li>
                        <a href="<?php echo esc_url("http://statuschecker.fgdc.gov"); ?>" target="_blank">
                            FGDC Service Status Checker
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                     </li>
                </ul>

            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <h4 style="color:white">About</h4>
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                      <?php wp_nav_menu( array( 'theme_location' => 'headfoot-aboutL' ) ); ?>


                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                      <?php wp_nav_menu( array( 'theme_location' => 'headfoot-aboutR' ) ); ?>

                    </div>
                </div>
                <br class="hidden-xs">


            </div>

        </div>

        <hr>

        <div class="row">
            <div class="col-md-8 col-sm-7 col-xs-12">
                <p>Implementation of the GeoPlatform embodies the principles and spirit of Open Government, emphasizing government-to-citizen communication, accountability, and transparency. The GeoPlatform supports open formats, data standards, and common core and extensible metadata. The portfolio of data, applications, and services provided here is stewarded through the use of open licenses and careful review and hosted on an infrastructure that maximizes interoperability. Increased sharing and reuse of resources facilitated by the GeoPlatform will reduce costs, result in savings and wise investments, and stimulate innovation and entrepreneurship. On balance, the integrated approach of the GeoPlatform means that the federal portfolio of geospatial data is better managed, serves a broader audience, and is easier to use.</p>
                <p>
                The GeoPlatform was developed by the member agencies of the Federal Geographic Data Committee (FGDC) through collaboration with partners and stakeholders. The target audience for the GeoPlatform includes Federal agencies, State, local, and Tribal governments, private sector, academia, and the general public.
                </p>
            </div>
            <div class="col-md-4 col-sm-5 col-xs-12">
                <a href="<?php echo esc_url("http://www.fgdc.gov"); ?>" target="_blank">
                    <img src="<?php echo esc_url("/wp-content/uploads/2017/01/fgdc_logo_dkbg.png"); ?>" target="_blank" alt="FGDC Logo">
                </a>
            </div>
        </div>
    </div>
</footer>


<?php wp_footer();?>
</body>
</html>

<!-- code for authentication

<% if(NODE_ENV === "dev") { %>
<script src="//localhost:35729/livereload.js"></script>
<% } %>

<% if(error && error.message && error.message.indexOf("expire")>0) { %>
    <!--<script>alert("Your session has expired. Please sign in again.");</script>-->
