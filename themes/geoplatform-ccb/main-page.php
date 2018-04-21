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
              $category_image_placeholder = get_template_directory_uri() . "/img/placeholder-category-photo.jpeg";
              $placeholder_text_template = 'The category photo above is a placeholder. If you would like to edit your category card photos,
              please <a href="https://wordpress.org/plugins/categories-images/">Download</a> or Activate the Categories Images plugin. Then
              navigate to Posts(or Pages)->Categories to edit and set your specfic category image';
              
              //https://developer.wordpress.org/reference/functions/get_categories/
              $categories = get_categories( array(
                  'orderby' => 'name',
                  'order'   => 'ASC',
                  'exclude' => '24'
              ) );
              //var_dump($categories);
              foreach( $categories as $category ) {
                  if (function_exists('z_taxonomy_image_url')) { //Category Images plugin enabled
                    if (z_taxonomy_image_url($category->term_id)) { //if there is a url to pull
                      $category_image = z_taxonomy_image_url($category->term_id);
                      $placeholder_text = '';
                    }
                    else { //No category image set
                      $category_image = $category_image_placeholder;
                      $placeholder_text = $placeholder_text_template;
                    }

                  }
                  else { //Category Images plugin not activated
                    $category_image = $category_image_placeholder;
                    $placeholder_text = $placeholder_text_template;
                  }
                  $category_link = sprintf(
                      '<a style="background-image:linear-gradient(
                        rgba(0, 0, 0, 0.3),
                        rgba(0, 0, 0, 0.3)
                        ),
                        url(%4$s);" href="%1$s" alt="%2$s" class="media embed-responsive embed-responsive-16by9" id="module"><h3 id="mid">%3$s</h3></a>',
                      esc_url( get_category_link( $category->term_id ) ),
                      esc_attr( sprintf( __( 'View all posts in %s', 'geoplatform-2017-theme' ), $category->name ) ),
                      esc_html( $category->name ),
                      esc_url($category_image)
                  );?>
                <div class="col-sm-6 col-md-6 col-lg-4 col-xlg-4">
                  <div class="gp-ui-card gp-ui-card--md gp-ui-card text-center">
                    <?php echo sprintf( esc_html__( '%s', 'geoplatform-2017-theme' ), $category_link ); ?>
                     <br style="clear: both;">
                  </div><!--#gp-ui-card gp-ui-card-md gp-ui-card text-center-->
                </div><!--#col-sm-6 col-md-6 col-lg-4 col-xlg-4-->
            <?php } //foreach?>

            </div><!--#col-md-12-->
            <p>
              <?php echo $placeholder_text; ?>
            </p>
          <div class="card-footer card-footer-right"></div><!--#card-footer card-footer-right-->
        </div><!--#row-->
      </div><!-- #col-lg-12 -->

    </div><!-- #container-fluid -->
    <br>
    <div class="footing">
      <div class="line-cap"></div>
      <div class="line"></div>
    </div><!-- #footing -->
</div><!--whatsNew section-linked-->
