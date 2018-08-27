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
                $geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');
                if ($geopccb_featured_sort_format == 'date'){
                  if ( have_posts() ){
                    while ( have_posts() ) : the_post();
                      get_template_part( 'post-card', get_post_format() );
                    endwhile;
                  }
                }
                else {
                  //getting the posts
                  $geopngda_posts = get_posts( array(
                      'post_type'   => 'post',
                      'numberposts' => -1
                  ) );
                  $geopngda_posts_trimmed = array();
                  // Removes categories to be excluded from the featured output array.
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

                  // 
                  foreach ($geopngda_posts_trimmed as $geopccb_post){
                    echo $geopccb_post->geop_ngda_post_priority . "<br>";
                  }




                }





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
