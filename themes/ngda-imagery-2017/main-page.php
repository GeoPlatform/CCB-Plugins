<div class="whatsNew section--linked">

<div class="container-fluid">


        <div class="col-lg-12">


            <h4 class="heading text-centered">
		<div class="line"></div>
                 <div class="line-arrow"></div>
              <div class="title darkened">
                Featured
              </div>
            </h4>
              <!-- <div class="row"> -->
                <!-- <div class="col-md-6"> -->


                  <!-- <?php
                  //set counter
                //  $postNum = 0;
                  //show the sites themes (categories)
                  //while ( have_posts() && ($postNum < 10)) : the_post();
                //      $postNum++;
                    //  get_template_part( 'post-card', get_post_format() );
                //  endwhile;?>
                  <!-- </div> -->





              <!-- </div> -->

              <div class="row">
                <div class="col-md-12">

                      <?php
                            //https://developer.wordpress.org/reference/functions/get_categories/
                            $categories = get_categories( array(
                                'orderby' => 'name',
                                'order'   => 'ASC',
                                'exclude' => '24'
                            ) );
                            //var_dump($categories);
                            foreach( $categories as $category ) {
                                $category_link = sprintf(
                                    '<a style="background-image:linear-gradient(
                    rgba(0, 0, 0, 0.3),
                    rgba(0, 0, 0, 0.3)
                  ),
               url(%4$s);" href="%1$s" alt="%2$s" class="media embed-responsive embed-responsive-16by9" id="module"><h3 id="mid">%3$s</h3></a>',
                                    esc_url( get_category_link( $category->term_id ) ),
                                    esc_attr( sprintf( __( 'View all posts in %s', 'ngda-imagery-2017' ), $category->name ) ),
                                    esc_html( $category->name ),
                                    esc_url(z_taxonomy_image_url($category->term_id))
                                );





              ?>
              <div class="col-sm-6 col-md-6 col-lg-4 col-xlg-4">
              <div class="gp-ui-card gp-ui-card--md gp-ui-card text-center">

                      <?php echo sprintf( esc_html__( '%s', 'ngda-imagery-2017' ), $category_link ); ?>

                   </a>

              <br style="clear: both;">


</div>
</div>
<?php } ?>
<!-- <?php
                  // echo '<p>' . sprintf( esc_html__( 'Category: %s', 'ngda-imagery-2017' ), $category_link ) . '</p> ';
                  // echo '<p>' . sprintf( esc_html__( 'Description: %s', 'ngda-imagery-2017' ), $category->description ) . '</p>';
                  // echo '<p>' . sprintf( esc_html__( 'Post Count: %s', 'ngda-imagery-2017' ), $category->count ) . '</p>';
              //} ?> -->

              </div>



          <!-- </div> -->

          <div class="card-footer card-footer-right"><!--<a class="btn btn-accent" title="A-16 NDGA Portfolio" href="http://www.fgdc.gov/initiatives/portfolio-management" target="_blank">
          Learn More</a>--></div>
          </div>
          </div>

        </div>
        <br>

        <div class="footing">
                <div class="line-cap"></div>
                <div class="line"></div>
            </div>

    </div>

</div>

</div>

</div>
