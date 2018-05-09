<div class="banner banner--fixed-height">
<!--Used for the Main banner background to show up properly-->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-sm-10 col-xs-12">
                      <!--Insert any banner info or things you'd like here-->

		                    <?php if (is_single()){ ?>

		     <!--if it is a post, the Theme header(banner intro content)shows above the page title.-->
                      <h3 style="color:white"><?php the_title(); ?></h3>

                      <p>
                        <?php echo wp_kses_post(get_post_meta($post->ID, 'custom_wysiwyg', true)); ?>
                      </p>

                      <?php } elseif (is_page()) { ?>
			                   <!--Otherwise page title shows above banner intro content-->
                          <h3 style="color:white"><?php the_title(); ?></h3>

                          <p>
                            <?php echo wp_kses_post(get_post_meta($post->ID, 'custom_wysiwyg', true)); ?>
                          </p>
                        <?php } elseif (is_archive()) {
                                the_archive_title( '<h1 class="page-title">', '</h1>' );
                                the_archive_description( '<div class="archive-description">', '</div>' );

                              } elseif (is_404()) { ?>
                                <h2 class="page-title">
                                  <?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'geoplatform-ccb' ); ?>
                                </h2>

                              <?php } else {
                                //if nothing, then show nothing
                              }
                          ?>

                    </div><!--#col-md-10 col-sm-10 col-xs-12-->
                </div><!--#row-->
            </div><!--#container-->
        </div><!--#content-->
    </div> <!--#banner banner-fixed-height-->
