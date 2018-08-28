<div class="apps-and-services section--linked">

    <!-- top directional lines and section heading -->
    <h4 class="heading">
        <div class="line"></div>
        <div class="line-arrow"></div>
        <div class="title darkened">Featured Posts</div>
    </h4>

    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <br>
              <div class="row">
              <?php
                //Grabs the featured_appearance value and declares the trimmed post array.
                $geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');
                $geopngda_posts_final = array();

                // Populates the trimmed posts array the old-fashioned way by date with first 8 results.
                if ($geopccb_featured_sort_format == 'date'){
                  $geopngda_posts = get_posts( array(
                    'post_type' => 'post',
                    'orderby' => 'date',
                    'order' => 'DSC',
                    'numberposts' => -1
                  ) );

                  // This list is then filtered for all posts in the Front Page category,
                  // ending the loop after 8 results.
                  foreach($geopngda_posts as $geopngda_post){
                    if (in_category("Front Page", $geopngda_post))
                      array_push($geopngda_posts_final, $geopngda_post);
                    if (count($geopngda_posts_final) >= 8)
                      break;
                  }
                }
                else {

                  // Custom sorting method. Begins by getting all of the posts.
                  $geopngda_posts = get_posts( array(
                      'post_type'   => 'post',
                      'numberposts' => -1
                  ) );

                  // Assigns posts with valid priority values to the trimmed array.
                  $geopngda_posts_trimmed = array();
                  foreach($geopngda_posts as $geopngda_post){
                    if ($geopngda_post->geop_ngda_post_priority > 0)
                      array_push($geopngda_posts_trimmed, $geopngda_post);
                  }

                  // Bubble sorts the resulting posts.
                  $geopngda_posts_size = count($geopngda_posts_trimmed)-1;
                  for ($i = 0; $i < $geopngda_posts_size; $i++) {
                    for ($j = 0; $j < $geopngda_posts_size - $i; $j++) {
                      $k = $j + 1;
                      $geopngda_test_left = $geopngda_posts_trimmed[$j]->geop_ngda_post_priority;
                      $geopngda_test_right = $geopngda_posts_trimmed[$k]->geop_ngda_post_priority;
                      if ($geopngda_test_left > $geopngda_test_right) {
                        // Swap elements at indices: $j, $k
                        list($geopngda_posts_trimmed[$j], $geopngda_posts_trimmed[$k]) = array($geopngda_posts_trimmed[$k], $geopngda_posts_trimmed[$j]);
                      }
                    }
                  }

                  // Removes all posts after the first 8.
                  $geopngda_posts_final = array_slice($geopngda_posts_trimmed, 0, 8);

                }

                // Outputs posts.
                foreach ($geopngda_posts_final as $geopccb_post){
                  ?>
                  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                  <div class="gp-ui-card gp-ui-card--md gp-ui-card">
                    <?php if ( has_post_thumbnail($geopccb_post) ) {?>
                      <a class="media embed-responsive embed-responsive-16by9" href="<?php echo get_the_permalink($geopccb_post); ?>"
                          title="Register for the Geospatial Platform Workshop">
                          <img class="embed-responsive-item" src="<?php echo get_the_post_thumbnail_url($geopccb_post); ?>" >
                      </a>
                      <div class="gp-ui-card__body">
                          <div class="text--primary"><?php echo get_the_title($geopccb_post); ?></div>
                          <div class="text--secondary"><?php echo get_the_date("F j, Y", $geopccb_post->ID); ?></div>

                          <div class="text--supporting">
                              <?php echo substr($geopccb_post->post_content, 0, 55); ?>
                          </div>
                      </div><!--gp-ui-card__body w/image-->

                      <div class="gp-ui-card__footer">
                          <div class="pull-right">
                              <a href="<?php echo get_the_permalink($geopccb_post); ?>" class="btn btn-link pull-right">Learn More</a>
                          </div>
                      </div><!--gp-ui-card__footer w/image-->

                      <?php } else {?>
                        <div class="gp-ui-card__body">
                            <div class="text--primary"><?php echo get_the_title($geopccb_post); ?></div>
                            <div class="text--secondary"><?php echo get_the_date("F j, Y", $geopccb_post->ID); ?></div>
                            <div class="text--supporting">
                                <?php echo substr($geopccb_post->post_content, 0, 55); ?>
                            </div>
                        </div><!--gp-ui-card__body-->
                        <div class="gp-ui-card__footer">
                            <div class="pull-right">
                                <a href="<?php echo get_the_permalink($geopccb_post); ?>" class="btn btn-link pull-right">Learn More</a>
                            </div>
                        </div><!--gp-ui-card__footer-->
                    <?php } ?>
                  </div><!--#gp-ui-card gp-ui-card-md gp-ui-card-->
                  </div><!--#col-xs-12 col-sm-6 col-md-4 col-lg-3-->
                  <?php
                } // END OF LOOP
                ?>
              </div><!--/row -->
            </div><!--col-lg-12-->
          </div><!--#row-->
      <br>
    </div><!--#container-fluid-->

    <!-- bottom directional lines -->
    <div class="footing">
        <div class="line-cap"></div>
        <div class="line"></div>
    </div><!--#footing-->
</div><!--#apps-and-services section-linked-->
