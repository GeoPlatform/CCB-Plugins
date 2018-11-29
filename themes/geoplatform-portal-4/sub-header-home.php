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

?>

<ul class="p-landing-page__role-nav" role="menu">
    <li role="menuitem">What is your focus?</li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_home_active ?>"><a href="<?php echo home_url() . '/' . get_theme_mod('headlink_default'); ?>">None (default)</a></li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_data_active ?>"><a href="<?php echo home_url() . '/' . get_theme_mod('headlink_data'); ?>">Data</a></li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_new_active ?>"><a href="<?php echo home_url() . '/' . get_theme_mod('headlink_new'); ?>">I'm new</a></li>
</ul>
