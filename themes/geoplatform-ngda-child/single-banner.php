<div class="banner banner--fixed-height">
<!--Used for the Main banner background to show up properly-->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-sm-10 col-xs-12">
                      <!--Insert any banner info or things you'd like here-->
	                    <?php if (is_single()){ ?>
                      <h3 style="color:white"><?php the_title(); ?></h3>
                      <p>
                        <?php echo get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true); ?>
                      </p>
                      <?php } elseif (is_page()) { ?>
			                   <!--Otherwise page title shows above banner intro content-->
                          <h3 style="color:white"><?php the_title(); ?></h3>

                          <p>
                            <?php echo get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true); ?>
                          </p>
                        <?php } elseif (is_archive()) {
                                the_archive_title( '<h1 class="page-title">', '</h1>' );
                                the_archive_description( '<div class="archive-description">', '</div>' );

                              } elseif (is_404()) { ?>
                                <h2 class="page-title">
                                  <?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'geoplatform-ngda' ); ?>
                                </h2>

                              <?php } else {
                                //if nothing, then show nothing
                              }
                          ?>

                    </div><!--#col-md-10 col-sm-10 col-xs-12-->
                </div><!--#row-->

            </div><!--#container-->

        </div><!--#content-->
        <div class="container-fluid" style="margin-top:-4em;">
        <div class="row">
          <div class="col-md-offset-3">
            <ul role="menu" class="header__menu" style="color:white; margin-right:1em;">
          <?php wp_nav_menu( array(
            'theme_location' => 'community-links',
            'menu_class' => 'text-white nav navbar-nav navbar-right navbar-fixed-bottom',
            'items_wrap' => '%3$s',
            'container' => 'li'
           ) ); ?>
         </ul>
         <br />
       </div><!--#col-md-offset-3-->
        </div><!--#row-->
      </div><!--#container-fluid-->
    </div> <!--#banner banner-fixed-height-->
