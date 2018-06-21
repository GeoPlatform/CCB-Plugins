<?php
/**
 * The template the category banner
 *
 * @package Geoplatform_CCB
 */
$category = get_category( get_query_var( 'cat' ) );//get category data
$cat_id = $category->cat_ID; //get category ID
$class_category_image = get_term_meta($cat_id, 'category-image-id', true);//Get the image ID
if ( $class_category_image ) {
  $gp_banner_image = wp_get_attachment_image_src($class_category_image, 'full')[0]; //get and set the URL
} else {
  $gp_banner_image = get_template_directory_uri() . '/img/default-category-photo.jpeg';
}
?>
<div class="banner banner--fixed-height" style="background-position:center; background-image:url(
  <?php echo esc_url($gp_banner_image);?>)">
  <!--Used for the Main banner background to show up properly-->
  <div class="content">
      <div class="container-fluid">
          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <!--Insert any banner info or things you'd like here-->
                  <div>
                    <h3 style="color:white"><?php single_cat_title(); ?></h3>
                    <?php echo category_description(); ?>
                  </div>
              </div><!--#col-md-12 col-sm-12 col-xs-12-->
          </div><!--#row-->
      </div><!--#container-->
  </div><!--#content-->
</div><!--#banner banner-fixed-height-->
