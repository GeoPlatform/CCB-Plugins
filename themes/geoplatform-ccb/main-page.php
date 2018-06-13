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
              $category_image_placeholder = get_template_directory_uri() . "/img/default-category-photo.jpeg";
              $placeholder_text_template = 'The category photo(s) above have a placeholder image in them. If you would like to edit your category card photos,
              please navigate to Posts(or Pages)->Categories to edit and set your specfic category image';
              $image_set = false;
              //pagination
              //https://stackoverflow.com/questions/36976897/paginate-category-list-wordpress
              if ( get_query_var('paged') ) {
                       $paged = get_query_var('paged');
                   } else if ( get_query_var('page') ) {
                       $paged = get_query_var('page');
                   } else {$paged = 1;}
              $per_page = 12;
              $paged_offset = ($paged - 1) * $per_page;

              //https://developer.wordpress.org/reference/functions/get_categories/
              $categories = get_categories( array(
                  'orderby'   => 'name',
                  'order'     => 'ASC',
                  'hide_empty'=> 0,
                  'number'    => $per_page,
                  'paged'     => $paged,
                  'offset'    => $paged_offset
              ) );
              //https://developer.wordpress.org/reference/functions/get_categories/
              //List categories and descriptions
              foreach( $categories as $category ) {
                    if (get_term_meta($category->cat_ID, 'category-image-id', true)) { //if there is an image ID to pull
                      $class_category_image = get_term_meta($category->cat_ID, 'category-image-id', true); //Get that ID
                      $category_image = wp_get_attachment_image_src($class_category_image, 'full')[0]; //get and set the URL
                      $image_set = true; //at least one category has been changed
                    }
                    else { //No category image set
                      $category_image = $category_image_placeholder;
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
                        esc_url( get_category_link( $category->term_id ) ),
                        esc_attr( sprintf( __( 'View all posts in %s', 'geoplatform-ccb' ), $category->name ) ),
                        esc_attr( sprintf( __( ' %s', 'geoplatform-ccb' ), $category->name ) ),
                        esc_url($category_image)
                    );
                    ?>
                  </div><!--#gp-ui-card gp-ui-card-md gp-ui-card text-center-->
                </div><!--#col-sm-6 col-md-6 col-lg-4 col-xlg-4-->
            <?php } //foreach ?>
            <div class="col-md-12">
              <p>
                <?php
                if ($image_set == true) {
                  $placeholder_text = '';
                }
                else{
                  $placeholder_text = $placeholder_text_template;
                }
                echo wp_kses_post($placeholder_text); ?>
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
