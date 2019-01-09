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

// Excerpt grabber and size-checker for header. Not complex, but makes me feel clever.
// If the exceprt is larger than 300 characters, breaks it in half while making
// sure that the first half is as close to 300 as possible without going over.
$geop_portal_excerpt_overflow = false;
$geop_portal_excerpt_one = wp_strip_all_tags(wp_kses_post(get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true)));
$geop_portal_excerpt_two = wp_kses_post(get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true));

if (strlen($geop_portal_excerpt_one) > 300){
  $geop_portal_excerpt_overflow = true;
  $geop_portal_exploded_array = explode('.', $geop_portal_excerpt_one);
  $geop_portal_excerpt_one = array_shift($geop_portal_exploded_array) . '.';
  while (count($geop_portal_exploded_array) > 0 && (strlen($geop_portal_excerpt_one) + strlen($geop_portal_exploded_array[0]) < 300))
    $geop_portal_excerpt_one = $geop_portal_excerpt_one . array_shift($geop_portal_exploded_array) . '.';
}
else {
  $geop_portal_excerpt_one = $geop_portal_excerpt_two;
}

$geop_portal_bread_title = get_the_title($post);
if(!empty($post->geopportal_breadcrumb_title)){
  $geop_portal_bread_title = $post->geopportal_breadcrumb_title;
}
?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>

    <?php
    // Adds breadcrumb elements from array to sub-header, starting from end to beginning of array.
    for ($i = count($geopportal_breadcrumb_array)-1; $i >= 0; $i--) {
      $geop_portal_bread_title = get_the_title($geopportal_breadcrumb_array[$i]);
      if(!empty($geopportal_breadcrumb_array[$i]->geopportal_breadcrumb_title)){
        $geop_portal_bread_title = $geopportal_breadcrumb_array[$i]->geopportal_breadcrumb_title;
      }
      ?>
      <li><a href="<?php echo get_the_permalink($geopportal_breadcrumb_array[$i]); ?>"><?php echo $geop_portal_bread_title; ?></a></li>
    <?php } ?>

</ul>

<?php if (wp_kses_post(get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true)) != '' ){
?>
<div class="m-page-overview">
  <?php echo $geop_portal_excerpt_one;
  if ($geop_portal_excerpt_overflow){ ?>
    <div class="m-page-overview__toggle" onclick="toggleClass('.m-page-overview__additional','is-expanded m-page-overview__additive'), toggleClass('.m-page-overview','is-collapsed')">
      <span class="fas fa-caret-down"></span>
    </div>
  </div>
  <div class="m-page-overview__additional">
    <?php echo $geop_portal_excerpt_two ?>
    <div class="m-page-overview__toggle" onclick="toggleClass('.m-page-overview__additional','is-expanded m-page-overview__additive'), toggleClass('.m-page-overview','is-collapsed')">
      <span class="fas fa-caret-down"></span>
    </div>
  <?php } ?>
</div>
<?php } ?>
