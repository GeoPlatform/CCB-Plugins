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
                    <!-- <h5>Featured</h5> -->
                    <h5><?php echo gp_get_nav_menu_name('headfoot-featured'); ?></h5>
                        <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>



                    <br class="hidden-xs">
                    <!-- <h5>Get Involved</h5>
                    <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?> -->

                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <h5><?php echo gp_get_nav_menu_name('headfoot-center'); ?></h5>
                      <?php wp_nav_menu( array( 'theme_location' => 'headfoot-center' ) ); ?>

                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- <h5>About</h5> -->
                    <h5><?php echo gp_get_nav_menu_name('headfoot-aboutL'); ?></h5>
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
