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
              //https://codex.wordpress.org/Class_Reference/WP_Query
              //Uncomment below to showcase specific posts on the main page
              // The Query
              // $the_query = new WP_Query( array( 'post_type' => 'page', 'post__in' => array( 429, 433, 415), 'orderby' => 'post__in' ) );

              // show pages with Front Page category_name
              // $args = array(
              // 'post_type' => 'page',
              // 'category_name'=> 'Front Page',
              // 'orderby' => 'date',
              // 'order' => 'ASC'
              //'date_query'    => array(
              //    'column'  => 'post_date',
              //    'after'   => '- 30 days'
              //    )
              // );


              if (current_user_can('read_private_pages')) {
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
                      </div><!--#gp-ui-card gp-ui-card-md gp-ui-card-->
                   </div><!--#col-xs-12 col-sm-6 col-md-6 col-lg-4-->
                 <?php }//while
              	/* Restore original Post Data */
        	      wp_reset_postdata();
                } else {
                // no Pages found
                ?>
                <div class="text-center">
                There are no pages set to feature. If you would like to showcase some Pages in this area, set the category of your selected page to 'Front Page'.
                </div>
              <?php } //else ?>
        </div> <!-- end col-sm-12 col-md-9 col-lg-9 -->
          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" style='margin-top: -33px;'>
            <?php get_template_part( 'sidebar', get_post_format() ); ?>
          </div><!-- #col-xs-12 col-sm-12 col-md-3 col-lg-3 -->
          <div class="card-footer card-footer-right"></div>
        </div> <!-- end row-->
      </div><!-- end col-lg-12-->
    </div> <!-- end container-fluid-->
    <div class="footing">
        <div class="line-cap"></div>
        <div class="line"></div>
    </div><!--#footing-->
</div> <!-- #whatsNew section-linked-->
