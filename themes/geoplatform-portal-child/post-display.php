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

// Checks if there's data in the excerpt and, if so, assigns it to be displayed.
// If not, grabs post content and clips it at 200 characters.
$geopportal_post = get_post($geopportal_mainpage_feat_page);
if (!empty($geopportal_post->post_excerpt))
  $geopccb_excerpt = $geopportal_post->post_excerpt;
else{
  $geopccb_excerpt = $geopportal_post->post_content;
  if (strlen($geopccb_excerpt) > 200)
    $geopccb_excerpt = substr($geopccb_excerpt, 0, 200) . '...';
}
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
            <?php echo $geopccb_excerpt; ?>
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
              <?php echo $geopccb_excerpt; ?>
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
