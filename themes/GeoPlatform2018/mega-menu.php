<!--
 Mega Menu
 -->
<nav class="mega-menu mega-menu-right" id="megamenu" role="menu">

    <div class="mega-menu-content container">

        <div class="mega-menu__body">

            <div class="account-details visible-xs">
                <!-- <% if(!authenticated) { %> -->
                    <div class="media">
                        <div class="media-left">
                            <div class="media-object">
                                <span class="glyphicon glyphicon-user glyphicon-xlg"></span>
                            </div>
                        </div>
                        <div class="media-body">
                            <p><em>Create a new account or log in using an existing account.</em></p>
                            <a class="" href="<?php echo esc_url($GLOBALS['idp_url'] . "/modifyuser.html"); ?>" target="_blank">Register</a> a new account<br/>
                            <a class="" onClick="login()">Sign In</a> to an existing account
                        </div>
                    </div>

            </div>


            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5>Featured</h5>
                        <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>



                    <br class="hidden-xs">
                    <!-- <h5>Get Involved</h5>
                    <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?> -->

                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5>Apps &amp; Services</h5>
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
                            <a href="<?php echo $GLOBALS['ckan_url']; ?>" target="_blank">
                                Search Catalog
                                <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $GLOBALS['ckan_mp_url']; ?>" target="_blank">Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup></a></li>
                    </ul>

                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5>About</h5>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <?php wp_nav_menu( array( 'theme_location' => 'headfoot-aboutL' ) ); ?>



                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <?php wp_nav_menu( array( 'theme_location' => 'headfoot-aboutR' ) ); ?>

                        </div>
                    </div>
                    <br class="hidden-xs">
                    <!-- <h5>Themes</h5>
			<?php wp_nav_menu( array( 'theme_location' => 'headfoot-themes','fallback_cb' => '') ); ?> -->

                </div>

            </div>

      </div>  <!-- #mega-menu__body -->

    </div><!--#mega-menu-content container-->
</nav><!--/nav-->
