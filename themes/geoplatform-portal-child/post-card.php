<?php
/**
 * The template for single post content, in card format
 *
 * @package GeoPlatform CCB
 *
 * @since 3.0.0
 */
// $geopportal_mainpage_feat_page = get_page_by_path($geopportal_mainpage_disp_array[$geopportal_counter]);
// echo $geopportal_mainpage_disp_array[$geopportal_counter];
// echo "  ";
// echo get_the_title($geopportal_mainpage_feat_page);
// echo "<br>"

$geopportal_mainpage_feat_page = get_page_by_path($geopportal_mainpage_disp_post_array[$geopportal_counter], OBJECT, 'post');
?>

<div class="col-sm-6 col-md-4">
<div class="gp-ui-card gp-ui-card--md gp-ui-card">
  <?php if ( has_post_thumbnail($geopportal_mainpage_feat_page) ) {?>
    <a class="media embed-responsive embed-responsive-16by9" href="<?php echo get_the_permalink($geopportal_mainpage_feat_page); ?>"
        title="<?php _e( 'Register for the Geospatial Platform Workshop', 'geoplatform-ccb') ?> ">

        <img class="embed-responsive-item" src="<?php echo get_the_post_thumbnail_url($geopportal_mainpage_feat_page); ?>" >

    </a>
    <div class="gp-ui-card__body">
        <div class="text--primary"><?php echo get_the_title($geopportal_mainpage_feat_page); ?></div>
        <div class="text--secondary"></div>
        <div class="text--supporting">
            <?php echo do_shortcode($geopportal_mainpage_disp_content_array[$geopportal_counter]); ?>
        </div>
    </div>

    <div class="gp-ui-card__footer">
        <div class="pull-right">
            <a href="<?php echo get_the_permalink($geopportal_mainpage_feat_page); ?>" class="btn btn-link pull-right"><?php _e( 'Learn More', 'geoplatform-ccb'); ?></a>
        </div>
    </div>

    <?php } else {?>
      <div class="gp-ui-card__body">
          <div class="text--primary"><?php echo get_the_title($geopportal_mainpage_feat_page); ?></div>
          <div class="text--secondary"></div>
          <div class="text--supporting">
              <?php echo do_shortcode($geopportal_mainpage_disp_content_array[$geopportal_counter]); ?>
          </div>
      </div>
      <div class="gp-ui-card__footer">
          <div class="pull-right">
              <a href="<?php echo get_the_permalink($geopportal_mainpage_feat_page); ?>" class="btn btn-link pull-right"><?php _e( 'Learn More', 'geoplatform-ccb'); ?></a>
          </div>
      </div>
  <?php } ?>
</div>
</div>
