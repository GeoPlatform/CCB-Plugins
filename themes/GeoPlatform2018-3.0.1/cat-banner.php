<?php if (function_exists('z_taxonomy_image_url') && z_taxonomy_image_url()) {
  $gp_banner_image = z_taxonomy_image_url();
} else {
  $gp_banner_image = get_template_directory_uri() . '/img/placeholder-category-photo.jpeg';
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
