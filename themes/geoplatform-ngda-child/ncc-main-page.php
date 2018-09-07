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
              if (count($geopngda_pages_final) >= 6)
                break;
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
            $geopngda_pages_final = array_slice($geopngda_pages_trimmed, 0, 6);
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
              <div class="services">
                <div class="col-md-6 col-lg-4 col-sm-12">
                  <figure class="snip1174 green col-md-4">
                    <?php if (has_post_thumbnail($geopccb_page->ID)){?>
                      <img src="<?php echo get_the_post_thumbnail_url($geopccb_page);?>" class="centered" alt="<?php echo get_the_title($geopccb_page); ?>" />
                    <?php } else { ?>
                      <img src="<?php echo get_stylesheet_directory_uri() . '/pixel.png' ?>" class="centered" alt="<?php echo get_the_title($geopccb_page); ?>" />
                    <?php }?>
                    <figcaption>
                      <h2><?php echo get_the_title($geopccb_page); ?></h2>
                      <h4><?php echo get_post_meta($geopccb_page->ID, 'custom_wysiwyg', true); ?></h4>
                      <a href="<?php echo get_the_permalink($geopccb_page); ?>">Go to Page</a>
                    </figcaption>
                  </figure>
                </div>
              </div>
              <?php
            } // End of card loop.
          } // End of output content check.
          ?>
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
