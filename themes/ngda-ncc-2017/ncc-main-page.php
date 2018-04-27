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
                  //Uncomment below to showcase categories on the main page
          //               //https://developer.wordpress.org/reference/functions/get_categories/
          //               $categories = get_categories( array(
          //                   'orderby' => 'name',
          //                   'order'   => 'ASC',
          //                   'exclude' => '24'
          //               ) );
          //               //var_dump($categories);
          //               foreach( $categories as $category ) {
          //                   $category_link = sprintf(
          //                       '<a style="background-image:linear-gradient(
          //       rgba(0, 0, 0, 0.3),
          //       rgba(0, 0, 0, 0.3)
          //     ),
          //  url(%4$s);" href="%1$s" alt="%2$s" class="media embed-responsive embed-responsive-16by9" id="module"><h3 id="mid">%3$s</h3></a>',
          //                       esc_url( get_category_link( $category->term_id ) ),
          //                       esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
          //                       esc_html( $category->name ),
          //                       esc_url(z_taxonomy_image_url($category->term_id))
          //                   );


          //https://codex.wordpress.org/Class_Reference/WP_Query
          //Uncomment below to showcase specific posts on the main page
          // The Query
          // $the_query = new WP_Query( array( 'post_type' => 'page', 'post__in' => array( 429, 433, 415), 'orderby' => 'post__in' ) );

          //show pages with Front Page category_name
          $args = array(
          'post_type' => 'page',
          'category_name'=> 'Front Page',
          'orderby' => 'date',
          'order' => 'ASC'
          //'date_query'    => array(
          //    'column'  => 'post_date',
          //    'after'   => '- 30 days'
          //    )
          );
          $the_query = new WP_Query( $args );

          // The Loop
          if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
              $the_query->the_post();?>
              <!-- //get_template_part('feature-post', get_post_format()); -->
            <!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
               <div class="gp-ui-card gp-ui-card--md gp-ui-card">

               <a style="background-image:linear-gradient(
               rgba(0, 0, 0, 0.3),
               rgba(0, 0, 0, 0.3)
              ),
               url(<?php the_post_thumbnail_url();?>)" href="<?php the_permalink(); ?>" alt="<?php the_title(); ?>" class="media embed-responsive embed-responsive-16by9" id="module">
               <h3 class="text-center" id="mid"><?php the_title(); ?></h3> -->

                  <!-- <?php echo get_post_meta($post->ID, 'custom_wysiwyg', true); ?> -->

             <!-- </a>

               <br style="clear: both;">

               </div>
               </div> -->

               <div class="services">
<div class="col-md-6 col-lg-4 col-sm-12">
    <figure class="snip1174 green col-md-4">

        <img src="<?php the_post_thumbnail_url();?>" class="centered" alt="<?php the_title(); ?>" />

      <figcaption>
        <h2><?php the_title(); ?></h2>
        <h4>
        <?php echo get_post_meta($post->ID, 'custom_wysiwyg', true); ?></h4>
        <a href="<?php the_permalink(); ?>">Go to Page</a>
      </figcaption>
    </figure>
</div>
</div>
             <?php }
            /* Restore original Post Data */
            wp_reset_postdata();
          } else {
            // no posts found
          } ?>

          <!-- <div class="col-sm-6 col-md-6 col-lg-3 col-xlg-3">
          <div class="gp-ui-card gp-ui-card--md gp-ui-card text-center">

                  <?php echo sprintf( esc_html__( '%s', 'textdomain' ), $category_link ); ?>

               </a>

          <br style="clear: both;">


</div>
</div> -->
<!-- <?php// } ?> -->

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
