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

          // Sets up ability to read published and private posts.
          $geop_ngda_private_perm = array('publish');
          if (current_user_can('read_private_pages'))
            $geop_ngda_private_perm = array('publish', 'private');

          //Grabs the featured_appearance value and declares the trimmed post array.
          $geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');
          $geopngda_pages_final = array();

          // Mimics the old way of populating, but functional. Grabs all pages.
          if ($geopccb_featured_sort_format == 'date'){
            $geopngda_pages = get_posts(array(
              'post_type' => 'page',
              'orderby' => 'date',
              'order' => 'DSC',
              'numberposts' => -1,
              'post_status' => $geop_ngda_private_perm
            ) );

            // This list is then filtered for all pages in the Front Page category,
            // ending the loop after 6 results.
            foreach($geopngda_pages as $geopngda_page){
              if (in_category("Front Page", $geopngda_page))
                array_push($geopngda_pages_final, $geopngda_page);
              // if (count($geopngda_pages_final) >= 6)
              //   break;
            }
          }
          else {

            // Custom sorting method. Begins by getting all of the pages.
            $geopngda_pages = get_posts( array(
                'post_type'   => 'page',
                'numberposts' => -1,
                'post_status' => $geop_ngda_private_perm
            ) );

            // Assigns pages with valid priority values to the trimmed array.
            $geopngda_pages_trimmed = array();
            foreach($geopngda_pages as $geopngda_page){
              if ($geopngda_page->geop_ccb_post_priority > 0)
                array_push($geopngda_pages_trimmed, $geopngda_page);
            }

            // Bubble sorts the resulting pages.
            $geopngda_pages_size = count($geopngda_pages_trimmed)-1;
            for ($i = 0; $i < $geopngda_pages_size; $i++) {
              for ($j = 0; $j < $geopngda_pages_size - $i; $j++) {
                $k = $j + 1;
                $geopngda_test_left = $geopngda_pages_trimmed[$j]->geop_ccb_post_priority;
                $geopngda_test_right = $geopngda_pages_trimmed[$k]->geop_ccb_post_priority;
                if ($geopngda_test_left > $geopngda_test_right) {
                  // Swap elements at indices: $j, $k
                  list($geopngda_pages_trimmed[$j], $geopngda_pages_trimmed[$k]) = array($geopngda_pages_trimmed[$k], $geopngda_pages_trimmed[$j]);
                }
              }
            }
            // Removes all pages after the first 6.
            // $geopngda_pages_final = array_slice($geopngda_pages_trimmed, 0, 6);
            $geopngda_pages_final = $geopngda_pages_trimmed;
          }

          // Outputs the pages.
          if (count($geopngda_pages_final) <= 0){

            // Empty array outputs, in case of no valid pages for either output format.
            if ($geopccb_featured_sort_format == 'date'){
              ?>
              <div class="text-center">
                There are no pages set to feature. If you would like to showcase some Pages in this area, set the category of your selected page to 'Front Page'.
              </div>
              <?php
            } else {
              ?>
              <div class="text-center">
                There are no pages set to feature. If you would like to showcase some Pages in this area, provide Priority values to some pages.
              </div>
              <?php
            }
          } else {
            foreach ($geopngda_pages_final as $geopccb_page){
              ?>
              <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                <div class="gp-ui-card gp-ui-card--md gp-ui-card">
                  <a style="background-image:linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
                    url(<?php echo get_the_post_thumbnail_url($geopccb_page); ?>)" href="<?php echo get_the_permalink($geopccb_page); ?>" alt="<?php echo get_the_title($geopccb_page); ?>" class="media embed-responsive embed-responsive-16by9" id="module">
                      <h3 class="text-center" id="mid" style="font-size:1.5rem"><?php echo get_the_title($geopccb_page); ?></h3>
                  </a>
                  <br style="clear: both;">
                </div><!--#gp-ui-card gp-ui-card-md gp-ui-card-->
              </div><!--#col-xs-12 col-sm-6 col-md-6 col-lg-4-->
              <?php
            } // End of card loop.
          } // End of output content check.
          ?>
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
