<div class="whatsNew section--linked">

  <div class="container-fluid">
    <div class="col-lg-12">
      <h4 class="heading text-centered">
		      <div class="line"></div>
            <div class="line-arrow"></div>
              <div class="title darkened">
                Featured Pages
              </div>
      </h4>
      <div class="row">
        <div class="col-sm-12 col-md-9 col-lg-9">
          <?php
          //show pages with Front Page category_name
            if (is_user_logged_in()) {
              $SQLQuery = array(
              'post_type' => 'page',
              'category_name'=> 'Front Page',
              'orderby' => 'date',
              'post_status'=> array('publish','private'),
              'order' => 'ASC'
              );
            }
            else{
              $SQLQuery = array(
              'post_type' => 'page',
              'category_name'=> 'Front Page',
              'orderby' => 'date',
              'order' => 'ASC'
              );
            }
              $the_query = new WP_Query( $SQLQuery );

              // The Loop
              if ( $the_query->have_posts() ) {
              	while ( $the_query->have_posts() ) {
              		$the_query->the_post();?>
               		<!-- //get_template_part('feature-post', get_post_format()); -->
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                   <div class="gp-ui-card gp-ui-card--md gp-ui-card">

                   <a style="background-image:linear-gradient(
                   rgba(0, 0, 0, 0.3),
                   rgba(0, 0, 0, 0.3)
                  ),
                   url(<?php the_post_thumbnail_url();?>)" href="<?php the_permalink(); ?>" alt="<?php the_title(); ?>" class="media embed-responsive embed-responsive-16by9" id="module">
                   <h3 class="text-center" id="mid"><?php the_title(); ?></h3>
                 </a>

                   <br style="clear: both;">

                   </div>
                   </div>
                 <?php }
              	/* Restore original Post Data */
        	      wp_reset_postdata();
                } else {
                  // no Pages found
                ?>
                <div class="text-primary">
                There are no pages set to feature. If you would like to showcase some Pages in this area, set the category of your selected page to 'Front Page'.
                </div>
              <?php } ?>

              <!-- <div class="col-sm-6 col-md-6 col-lg-3 col-xlg-3">
              <div class="gp-ui-card gp-ui-card--md gp-ui-card text-center">

                      <?php //echo sprintf( esc_html__( '%s', 'ngda-imagery-2017' ), $category_link ); ?>

                   </a>

              <br style="clear: both;">


          </div>
          </div> -->
          <!-- <?php // } ?> -->
        </div> <!-- end col-sm-12 col-md-9 col-lg-9 -->

          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style='margin-top: -33px;'>
            <?php get_template_part( 'sidebar', get_post_format() ); ?>
          </div>

          <div class="card-footer card-footer-right"></div>


        </div> <!-- end row-->
      </div><!-- end col-lg-12-->
    </div> <!-- end container-fluid-->
    <div class="footing">
        <div class="line-cap"></div>
        <div class="line"></div>
    </div><!--#footing-->
</div> <!-- whatsNew section-linked-->
