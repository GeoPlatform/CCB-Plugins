<?php
/**
 * The template the category page banner
 *
 * @package Geoplatform_CCB
 *
 * @link https://codex.wordpress.org/Function_Reference/get_category
 * @link https://developer.wordpress.org/reference/functions/get_term_meta/
 *
 * @since 3.1.3
 *
 */
$geopccb_category = get_category( get_query_var( 'cat' ) );//get category data
$geopccb_cat_id = $geopccb_category->cat_ID; //get category ID
$geopccb_class_category_image = get_term_meta($geopccb_cat_id, 'category-image-id', true);//Get the image ID
if ( $geopccb_class_category_image ) {
  $geopccb_banner_image = wp_get_attachment_image_src($geopccb_class_category_image, 'full')[0]; //get and set the URL
} else {
  $geopccb_banner_image = get_template_directory_uri() . '/img/default-category-photo.jpeg';
}
?>
<div class="banner banner--fixed-height" style="background-position:center; background-image:url(<?php echo esc_url($geopccb_banner_image);?>)">
  <!--Used for the Main banner background to show up properly-->
  <div class="content">
      <div class="container-fluid">
          <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <!--Insert any banner info or things you'd like here-->
                  <div>
                    <h3 style="color:white"><?php esc_html(single_cat_title()); ?></h3>
                    <p><?php echo category_description(); ?></p>
                  </div>
              </div><!--#col-md-12 col-sm-12 col-xs-12-->
          </div><!--#row-->
      </div><!--#container-->
  </div><!--#content-->
</div><!--#banner banner-fixed-height-->
