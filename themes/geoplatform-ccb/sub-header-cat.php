<?php
// Secondary header, used for the home page.
global $wp;

// gets current category.
$geopccb_breadcrumb_cat = get_category($wp_query->get_queried_object_id());
$geopccb_breadcrumb_array = array($geopccb_breadcrumb_cat);
while ($geopccb_breadcrumb_cat->parent){
  $geopccb_breadcrumb_cat = get_category($geopccb_breadcrumb_cat->parent);
  array_push($geopccb_breadcrumb_array, $geopccb_breadcrumb_cat);
}
?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>

    <?php
    // Adds breadcrumb elements from array to sub-header, starting from end to beginning of array.
    for ($i = count($geopccb_breadcrumb_array)-1; $i >= 0; $i--) { ?>
      <li><a href="<?php echo esc_url( get_category_link( $geopccb_breadcrumb_array[$i]->term_id ) ); ?>"><?php echo esc_attr($geopccb_breadcrumb_array[$i]->name) ?></a></li>
    <?php } ?>

</ul>

<!-- Second part of excerpt will only show if the second excerpt string is populated. -->
<?php
$geopccb_breadcrumb_cat = get_category($wp_query->get_queried_object_id());
if (esc_attr($geopccb_breadcrumb_cat->description) != '' ){
?>
  <div class="m-page-overview">
    <?php echo esc_attr($geopccb_breadcrumb_cat->description); ?>
  </div>
<?php } ?>
