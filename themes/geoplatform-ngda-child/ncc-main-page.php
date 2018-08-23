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
      <div class="row">
        <div class="col-md-12">
        <?php

          //show pages with Front Page category_name
          $args = array(
            'post_type' => 'page',
            'category_name'=> 'Front Page',
            'orderby' => 'date',
            'order' => 'ASC'
          );
          $the_query = new WP_Query( $args );

          // The Loop
          if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
              $the_query->the_post();?>

              <div class="services">
                <div class="col-md-6 col-lg-4 col-sm-12">
                  <figure class="snip1174 green col-md-4">
                    <img src="<?php the_post_thumbnail_url();?>" class="centered" alt="<?php the_title(); ?>" />
                    <figcaption>
                      <h2><?php the_title(); ?></h2>
                      <h4><?php echo get_post_meta($post->ID, 'custom_wysiwyg', true); ?></h4>
                      <a href="<?php the_permalink(); ?>">Go to Page</a>
                    </figcaption>
                  </figure>
                </div>
              </div> <?php
            }
            /* Restore original Post Data */
            wp_reset_postdata();
            } ?>
          <br style="clear: both;">
        </div>
      </div>
      <div class="footing">
        <div class="line-cap"></div>
        <div class="line"></div>
      </div>
    </div>
  </div>
</div>
