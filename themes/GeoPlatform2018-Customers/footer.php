
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
        </div>

        <hr/>


        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!--Featured location-->
                <!-- <h4 style="color:white">Featured</h4> -->
                <h4 style="color:white"><?php echo gp_get_nav_menu_name('headfoot-featured'); ?></h4>
                    <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>


                <!-- <br class="hidden-xs">
                <h4 style="color:white">Get Involved</h4>
                <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?>
                <ul> -->

            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <h4 style="color:white"><?php echo gp_get_nav_menu_name('headfoot-center'); ?></h4>
                  <?php wp_nav_menu( array( 'theme_location' => 'headfoot-center' ) ); ?>

            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <h4 style="color:white">About</h4> -->
                <h4 style="color:white"><?php echo gp_get_nav_menu_name('headfoot-aboutL'); ?></h4>
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
