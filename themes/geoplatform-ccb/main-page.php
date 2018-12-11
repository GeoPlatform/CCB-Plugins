<?php
/**
 * The main page template to showcase Featured Categories
 *
 * pagination
 * @link https://stackoverflow.com/questions/36976897/paginate-category-list-wordpress
 *
 * getting the categories
 * @link  https://developer.wordpress.org/reference/functions/get_categories/
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */
?>

<div class="whatsNew section--linked">
  <div class="container-fluid">
    <div class="col-lg-12">
        <h4 class="heading text-centered">
		        <div class="line"></div>
                <div class="line-arrow"></div>
              <div class="title darkened">
                <?php _e( 'Featured', 'geoplatform-ccb'); ?>
              </div>
        </h4>
        <div class="row">
          <div class="col-md-12">
            <?php
              $geopccb_category_image_default = get_template_directory_uri() . "/img/default-category-photo.jpeg";
              $geopccb_default_text_template = __( "The category photo(s) above have a default image in them. If you would like to edit your category card photos, please navigate to Posts(or Pages)->Categories to edit and set your specfic category image", 'geoplatform-ccb');
              $geopccb_image_set = false;

              //pagination
              if ( get_query_var('paged') ) {
                       $geopccb_paged = get_query_var('paged');
                   } else if ( get_query_var('page') ) {
                       $geopccb_paged = get_query_var('page');
                   } else {$geopccb_paged = 1;}
              $geopccb_per_page = 12;
              $geopccb_paged_offset = ($geopccb_paged - 1) * $geopccb_per_page;

              //getting the categories
              $geopccb_categories = get_categories( array(
                  'orderby'   => 'name',
                  'order'     => 'ASC',
                  'hide_empty'=> 0,
                  'number'    => $geopccb_per_page,
                  'paged'     => $geopccb_paged,
                  'offset'    => $geopccb_paged_offset
              ) );

              // Checks the theme sorting setting and switches be default date or the custom method.
              $geopccb_categories_trimmed = array();
              $geopccb_featured_sort_format = get_theme_mod('featured_appearance', 'date');
              if ($geopccb_featured_sort_format == 'date'){
                $geopccb_categories_trimmed = $geopccb_categories;
              }
              else {
                // Removes categories to be excluded from the featured output array.
                // These include categories with negative/zero/no priority value and child categories.
                foreach($geopccb_categories as $geopccb_category){
                  if (get_term_meta($geopccb_category->cat_ID, 'cat_priority', true) > 0 &&  ! $geopccb_category->category_parent){
                    array_push($geopccb_categories_trimmed, $geopccb_category);
                  }
                }

                // Bubble sorts the remaining array by cat_priority value.
                $geopccb_categories_size = count($geopccb_categories_trimmed)-1;
                for ($i = 0; $i < $geopccb_categories_size; $i++) {
                  for ($j = 0; $j < $geopccb_categories_size - $i; $j++) {
                    $k = $j + 1;
                    $geopccb_test_left = get_term_meta($geopccb_categories_trimmed[$j]->cat_ID, 'cat_priority', true);
                    $geopccb_test_right = get_term_meta($geopccb_categories_trimmed[$k]->cat_ID, 'cat_priority', true);
                    if ($geopccb_test_left > $geopccb_test_right) {
                      // Swap elements at indices: $j, $k
                      list($geopccb_categories_trimmed[$j], $geopccb_categories_trimmed[$k]) = array($geopccb_categories_trimmed[$k], $geopccb_categories_trimmed[$j]);
                    }
                  }
                }
              }

              //List categories and descriptions
              foreach( $geopccb_categories_trimmed as $geopccb_category ) {
                    if (get_term_meta($geopccb_category->cat_ID, 'category-image-id', true)) { //if there is an image ID to pull
                      $geopccb_class_category_image = get_term_meta($geopccb_category->cat_ID, 'category-image-id', true); //Get that ID
                      $geopccb_category_image = wp_get_attachment_image_src($geopccb_class_category_image, 'full')[0]; //get and set the URL
                      $geopccb_image_set = true; //at least one category has been changed
                    }
                    else { //No category image set
                      $geopccb_category_image = $geopccb_category_image_default;
                    }
                  ?>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xlg-4">
                  <div class="gp-ui-card gp-ui-card--md gp-ui-card text-center">
                    <?php
                    echo sprintf(
                        '<a style="background-image:linear-gradient(
                          rgba(0, 0, 0, 0.3),
                          rgba(0, 0, 0, 0.3)
                          ),
                          url(%4$s);" href="%1$s" alt="%2$s" class="media embed-responsive embed-responsive-16by9" id="module"><h3 id="mid">%3$s</h3></a>',
                        esc_url( get_category_link( $geopccb_category->term_id ) ),
                        esc_attr( sprintf( __( 'View all posts in %s', 'geoplatform-ccb' ), $geopccb_category->name ) ),
                        esc_attr( sprintf( __( ' %s', 'geoplatform-ccb' ), $geopccb_category->name ) ),
                        esc_url($geopccb_category_image)
                    );
                    ?>
                  </div><!--#gp-ui-card gp-ui-card-md gp-ui-card text-center-->
                </div><!--#col-sm-6 col-md-6 col-lg-4 col-xlg-4-->
            <?php } //foreach ?>
            <div class="col-md-12">
              <p>
                <?php
                if ($geopccb_image_set == true) {
                  $geopccb_default_text = '';
                }
                else{
                  $geopccb_default_text = $geopccb_default_text_template;
                }
                echo wp_kses_post($geopccb_default_text); ?>
              </p>
            </div>
            <div class="alignleft" style="margin-top: 1em;"><?php previous_posts_link('&laquo; Previous Categories') ?></div>
            <div class="alignright" style="margin-top: 1em;"><?php next_posts_link('More Categories &raquo;') ?></div>
            </div><!--#col-md-12-->
        </div><!--#row-->
      </div><!-- #col-lg-12 -->
    </div><!-- #container-fluid -->
    <br>
    <div class="footing">
      <div class="line-cap"></div>
      <div class="line"></div>
    </div><!-- #footing -->
</div><!--whatsNew section-linked-->
