<?php
// Secondary header, used for the home page.
global $wp;

// gets current category.
$geopportal_breadcrumb_cat = get_category($wp_query->get_queried_object_id());
$geopportal_breadcrumb_array = array($geopportal_breadcrumb_cat);
while ($geopportal_breadcrumb_cat->parent){
  $geopportal_breadcrumb_cat = get_category($geopportal_breadcrumb_cat->parent);
  array_push($geopportal_breadcrumb_array, $geopportal_breadcrumb_cat);
}
?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>

    <?php
    // Adds breadcrumb elements from array to sub-header, starting from end to beginning of array.
    for ($i = count($geopportal_breadcrumb_array)-1; $i >= 0; $i--) { ?>
      <li><a href="<?php echo esc_url( get_category_link( $geopportal_breadcrumb_array[$i]->term_id ) ); ?>"><?php echo esc_attr($geopportal_breadcrumb_array[$i]->name) ?></a></li>
    <?php } ?>

</ul>

<!-- Second part of excerpt will only show if the second excerpt string is populated. -->
<?php
$geopportal_breadcrumb_cat = get_category($wp_query->get_queried_object_id());
if (esc_attr($geopportal_breadcrumb_cat->description) != '' ){
?>
  <div class="m-page-overview">
    <?php echo esc_attr($geopportal_breadcrumb_cat->description); ?>
  </div>
<?php } ?>
