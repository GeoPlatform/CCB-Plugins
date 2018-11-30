<?php
// Secondary header, used for the home page.

$geop_portal_subhead_home_active = "";
$geop_portal_subhead_data_active = "";
$geop_portal_subhead_new_active = "";

if (is_page_template("page-templates/data_template.php"))
  $geop_portal_subhead_data_active = "active";
elseif (is_page_template("page-templates/new_template.php"))
  $geop_portal_subhead_new_active = "active";
else
  $geop_portal_subhead_home_active = "active";

global $wp;

// Excerpt grabber and size-checker for header. Not complex, but makes me feel clever.
// If the exceprt is larger than 300 characters, breaks it in half while making
// sure that the first half is as close to 300 as possible without going over.
$geop_portal_excerpt_one = get_the_excerpt();
$geop_portal_excerpt_two = "";

if (strlen($geop_portal_excerpt_one) > 300){
  $geop_portal_exploded_array = explode('.', $geop_portal_excerpt_one);
  $geop_portal_excerpt_one = array_shift($geop_portal_exploded_array) . '.';
  while (count($geop_portal_exploded_array) > 0 && (strlen($geop_portal_excerpt_one) + strlen($geop_portal_exploded_array[0]) < 300))
    $geop_portal_excerpt_one = $geop_portal_excerpt_one . array_shift($geop_portal_exploded_array) . '.';
  $geop_portal_excerpt_two = implode('.', $geop_portal_exploded_array);
}

?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>
    <li><a href="<?php echo home_url(get_theme_mod('headlink_archive')); ?>">Pages</a></li>
    <li><a href="<?php echo home_url($wp->request); ?>"><?php the_title(); ?></a></li>
</ul>

<!-- Second part of excerpt will only show if the second excerpt string is populated. -->
<div class="m-page-overview">
  <?php
  echo $geop_portal_excerpt_one;
  if (strlen($geop_portal_excerpt_two) > 0){
  ?>
    <div class="m-page-overview__additional">
      <?php echo $geop_portal_excerpt_two ?>
    </div>
    <div class="m-page-overview__toggle" onclick="toggleClass('.m-page-overview__additional','is-expanded')">
      <span class="fas fa-caret-down"></span>
    </div>
  <?php } ?>
</div>
