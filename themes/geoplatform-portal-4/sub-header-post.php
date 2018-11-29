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

//$geop_portal_subhead_home_url = home_url() . "/" . get_theme_mod('headlink_default', '/');
//$geop_portal_subhead_data_url = home_url() . "/" . get_theme_mod('headlink_data', '/');
global $wp;

$geop_portal_excerpt_one = get_the_excerpt();
$geop_portal_excerpt_two = "";

if (strlen($geop_portal_excerpt_one) > 300){
  $geop_portal_exploded_array = explode('.', $geop_portal_excerpt_one);
  $geop_portal_excerpt_one = array_shift($geop_portal_exploded_array);
  if (count($geop_portal_exploded_array) > 0)
    $geop_portal_excerpt_two = implode('.', $geop_portal_exploded_array);
  // $geop_portal_temp_string = $geop_portal_exploded_array[0];
  // for ($i = 0; $i < 4; $i++){
  //   $geop_portal_temp_string =
  //   if ($geop_portal_excerpt_one)
  // }
}

?>

<ul class="m-page-breadcrumbs">
    <li><a href="<?php echo home_url() ?>/">Home</a></li>
    <li><a href="<?php echo home_url(get_theme_mod('headlink_archive')); ?>">Pages</a></li>
    <li><a href="<?php echo home_url($wp->request); ?>"><?php the_title(); ?></a></li>
</ul>

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


<!-- <ul class="p-landing-page__role-nav" role="menu">
    <li role="menuitem">What is your focus?</li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_home_active ?>"><a href="<?php echo home_url() . '/' . get_theme_mod('headlink_default'); ?>">None (default)</a></li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_data_active ?>"><a href="<?php echo home_url() . '/' . get_theme_mod('headlink_data'); ?>">Data</a></li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_new_active ?>"><a href="<?php echo home_url() . '/' . get_theme_mod('headlink_new'); ?>">I'm new</a></li>
</ul> -->
