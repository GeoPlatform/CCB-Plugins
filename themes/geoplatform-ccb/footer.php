
<footer>
    <div class="container-fluid top-link-row">
        <div class="row">
            <div class="col-md-12">
                <a class="pull-right" href="#"> &#8593; top</a>
            </div><!--#col-md-12-->
        </div><!--#row-->
    </div><!--#container-fluid top-link-row-->
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>
                    <!--<img src="/img/GeoPlatform_logo_sm.png" alt="GeoPlatform Logo" style="height: 1.5rem; vertical-align: bottom;">-->
                    <a href="<?php echo esc_url($GLOBALS['wpp_url']); ?>" title="<?php _e( 'Go to the Geoplatform Home Page', 'geoplatform-ccb'); ?>">
                        <span class="icon-gp"></span>
                        <?php _e( 'GeoPlatform', 'geoplatform-ccb'); ?>
                    </a>
                    <?php $geop_ccb_home_name = esc_html(get_bloginfo( 'name' )); ?>
                    <a href="<?php echo esc_url(get_site_url());?>" title="<?php printf( __( 'Go to the %s Home Page', 'geoplatform-ccb'), $geop_ccb_home_name); ?>">
                    <?php echo esc_html(get_bloginfo( 'name' )); ?>
                  </a>
                </h3>
            </div><!--#col-md-6-->
        </div><!--#row-->
        <hr/>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!--Featured location-->
                <h4 style="color:white"><?php echo esc_html(wp_get_nav_menu_name('footer-left')) ? esc_html(wp_get_nav_menu_name('footer-left')) : _e( 'Example Menu Title', 'geoplatform-ccb'); ?></h4>
                    <?php wp_nav_menu( array( 'theme_location' => 'footer-left' ) ); ?>
            </div><!--#col-md-4 col-sm-4 col-xs-12-->
            <div class="col-md-4 col-sm-4 col-xs-12">
              <h4 style="color:white"><?php echo esc_html(wp_get_nav_menu_name('footer-center')) ? esc_html(wp_get_nav_menu_name('footer-center')) : _e( 'Example Menu Title', 'geoplatform-ccb'); ?></h4>
              <?php wp_nav_menu( array( 'theme_location' => 'footer-center' ) ); ?>
            </div><!--#col-md-4 col-sm-4 col-xs-12-->
            <div class="col-md-4 col-sm-4 col-xs-12">
                <h4 style="color:white"><?php echo esc_html(wp_get_nav_menu_name('footer-right-col1')) ? esc_html(wp_get_nav_menu_name('footer-right-col1')) : _e( 'Example Menu Title', 'geoplatform-ccb'); ?></h4>
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                      <?php wp_nav_menu( array( 'theme_location' => 'footer-right-col1' ) ); ?>
                    </div><!--#col-md-6 col-sm-12 col-xs-12-->
                    <div class="col-md-6 col-sm-12 col-xs-12">
                      <?php wp_nav_menu( array( 'theme_location' => 'footer-right-col2' ) ); ?>
                    </div><!--#col-md-6 col-sm-12 col-xs-12-->
                </div><!--#row-->
                <br class="hidden-xs">
            </div><!--#col-md-4 col-sm-4 col-xs-12-->
        </div><!--#row-->
        <hr>
        <div class="row">
            <div class="col-md-8 col-sm-7 col-xs-12">
                <p><?php _e( 'Implementation of the GeoPlatform embodies the principles and spirit of Open Government, emphasizing government-to-citizen communication, accountability, and transparency. The GeoPlatform supports open formats, data standards, and common core and extensible metadata. The portfolio of data, applications, and services provided here is stewarded through the use of open licenses and careful review and hosted on an infrastructure that maximizes interoperability. Increased sharing and reuse of resources facilitated by the GeoPlatform will reduce costs, result in savings and wise investments, and stimulate innovation and entrepreneurship. On balance, the integrated approach of the GeoPlatform means that the federal portfolio of geospatial data is better managed, serves a broader audience, and is easier to use.', 'geoplatform-ccb'); ?></p>
                <p>
                <?php _e( 'The GeoPlatform was developed by the member agencies of the Federal Geographic Data Committee (FGDC) through collaboration with partners and stakeholders. The target audience for the GeoPlatform includes Federal agencies, State, local, and Tribal governments, private sector, academia, and the general public.', 'geoplatform-ccb'); ?>
                </p>
            </div><!--#col-md-4 col-sm-5 col-xs-12-->
            <div class="col-md-4 col-sm-5 col-xs-12">
                <a href="<?php echo esc_url("https://www.fgdc.gov"); ?>" target="_blank">
                    <img src="<?php echo esc_url("" . get_template_directory_uri() . "/img/fgdc_logo_dkbg.png"); ?>" target="_blank" alt="FGDC Logo">
                </a>
            </div><!--#col-md-4 col-sm-5 col-xs-12-->
        </div><!--#row-->
    </div><!--#container-->
</footer>


<?php wp_footer();?>
</body>
</html>
