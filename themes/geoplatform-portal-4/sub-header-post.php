<?php
// Secondary header, used for the home page.
global $wp;

// Breadcrumb post array creation, starting with current post and added each parent in turn.
$geopportal_breadcrumb_post = $post;
$geopportal_breadcrumb_array = array($post);
while ($geopportal_breadcrumb_post->post_parent){
  $geopportal_breadcrumb_post = get_post($geopportal_breadcrumb_post->post_parent);
  array_push($geopportal_breadcrumb_array, $geopportal_breadcrumb_post);
}
?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>

    <?php
    // Adds breadcrumb elements from array to sub-header, starting from end to beginning of array.
    for ($i = count($geopportal_breadcrumb_array)-1; $i >= 0; $i--) { ?>
      <li><a href="<?php echo get_the_permalink($geopportal_breadcrumb_array[$i]); ?>"><?php echo get_the_title($geopportal_breadcrumb_array[$i]); ?></a></li>
    <?php } ?>

</ul>

<!-- Second part of excerpt will only show if the second excerpt string is populated. -->
<div class="m-page-overview">
  <?php echo wp_kses_post(get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true)); ?>
</div>
