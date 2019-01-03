<?php
// Secondary header, used for the home page.
global $wp;

// Breadcrumb post array determination.
$geopportal_breadcrumb_parent = get_page_by_path($post->geopportal_compost_parent_slug, OBJECT, array('post', 'page', 'geopccb_catlink'));

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
?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>
    <li><a href="<?php echo get_the_permalink($geopportal_breadcrumb_parent); ?>"><?php echo get_the_title($geopportal_breadcrumb_parent); ?></a></li>
    <li><a href="<?php echo get_the_permalink($post); ?>"><?php echo get_the_title($post); ?></a></li>
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
