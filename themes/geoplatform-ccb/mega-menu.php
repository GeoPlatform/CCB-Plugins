<!--
 Mega Menu
 -->
<nav class="mega-menu mega-menu-right" id="megamenu" role="menu">

    <div class="mega-menu-content container">

        <div class="mega-menu__body">

            <div class="account-details visible-xs">
                    <div class="media">
                        <div class="media-left">
                            <div class="media-object">
                                <span class="glyphicon glyphicon-user glyphicon-xlg"></span>
                            </div><!--#media-object-->
                        </div><!--#media-left-->
                        <div class="media-body">
                            <p><em>Create a new account or log in using an existing account.</em></p>
                            <a class="" href="<?php echo esc_url($GLOBALS['idp_url'] . "/modifyuser.html"); ?>" target="_blank">Register</a> a new account<br/>
                            <a class="" onClick="login()">Sign In</a> to an existing account
                        </div><!--#media-body-->
                    </div><!--#media-->
            </div><!--#account-details visible-xs-->


            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                      <h5><?php echo esc_html(wp_get_nav_menu_name('header-left')) ? esc_html(wp_get_nav_menu_name('header-left')) : 'Example Menu Title';?></h5>
                        <?php wp_nav_menu( array( 'theme_location' => 'header-left' ) ); ?>

                    <br class="hidden-xs">
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <h5><?php echo esc_html(wp_get_nav_menu_name('header-center')) ? esc_html(wp_get_nav_menu_name('header-center')) : 'Example Menu Title';?></h5>
                  <?php wp_nav_menu( array( 'theme_location' => 'header-center' ) ); ?>

                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h5><?php echo esc_html(wp_get_nav_menu_name('header-right-col1')) ? esc_html(wp_get_nav_menu_name('header-right-col1')) : 'Example Menu Title';?></h5>

                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <?php wp_nav_menu( array( 'theme_location' => 'header-right-col1' ) ); ?>
                        </div><!-- #col-md-6 col-sm-12 col-xs-12-->

                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <?php wp_nav_menu( array( 'theme_location' => 'header-right-col2' ) ); ?>
                        </div><!-- #col-md-6 col-sm-12 col-xs-12-->
                    </div><!-- #row -->
                    <br class="hidden-xs">
                </div><!-- #col-md-4 col-sm-4 col-xs-12 -->
          </div><!-- #row -->
      </div>  <!-- #mega-menu__body -->
    </div><!--#mega-menu-content container-->
</nav><!--/nav-->