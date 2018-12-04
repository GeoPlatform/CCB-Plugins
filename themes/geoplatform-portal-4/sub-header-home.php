<?php
// Secondary header, used for the home page.

$geop_portal_subhead_home_active = "";
$geop_portal_subhead_data_active = "";
$geop_portal_subhead_new_active = "";
//$geop_portal_tempstring = "";

if (is_page_template("page-templates/data_template.php"))
  $geop_portal_subhead_data_active = "active";
elseif (is_page_template("page-templates/new_template.php"))
  $geop_portal_subhead_new_active = "active";
else
  $geop_portal_subhead_home_active = "active";
?>

<script>

var geopportal_hotnav_obj_array = [];
var geopportal_hotnav_name_array = [];
var geopportal_hotnav_link_array = [];

jQuery(document).ready(function() {
  geopportal_hotnav_obj_array = jQuery("div").filter("[id^=geopportal_anchor_]");
//  console.log(geopportal_hotnav_obj_array);

  for (var i = 0; i < geopportal_hotnav_obj_array.length; i++){
    geopportal_hotnav_link_array.push('#' + jQuery(geopportal_hotnav_obj_array[i]).attr('id'));
    geopportal_hotnav_name_array.push(jQuery(geopportal_hotnav_obj_array[i]).attr('title'));
    console.log(jQuery(geopportal_hotnav_obj_array[i]).attr('id'));
  }
  if (geopportal_hotnav_link_array.length > 1){
    var geopportal_temp_li = jQuery("<li>", {role: "menuitem"});
    var geopportal_temp_html = jQuery("<a>", {html: geopportal_hotnav_name_array[1], href: geopportal_hotnav_link_array[1]});
    jQuery('#geopportal_submenu_list').append("<li role='menuitem' class='t-fg--gray-md'>Jump to: </li>");
    jQuery(geopportal_temp_li).append(geopportal_temp_html);
    jQuery('#geopportal_submenu_list').append(geopportal_temp_li);

    for (var i = 2; i < geopportal_hotnav_link_array.length; i++){
      var geopportal_temp_li = jQuery("<li>", {role: "menuitem"});
      var geopportal_temp_html = jQuery("<a>", {html: geopportal_hotnav_name_array[i], href: geopportal_hotnav_link_array[i]});
      jQuery('#geopportal_submenu_list').append("<li>•</li>");
      jQuery(geopportal_temp_li).append(geopportal_temp_html);
      jQuery('#geopportal_submenu_list').append(geopportal_temp_li);
    }
  }


  console.log(geopportal_hotnav_name_array);
  console.log(geopportal_hotnav_link_array);
  // jQuery("[id^='geopportal_anchor_']").each(function(i, el) {
  //   geopportal_hotnav_name_array.push(jQuery(this).attr('title'));
  //   geopportal_hotnav_link_array.push(jQuery(this).attr('id'));
  //   console.log(jQuery(this));
  //
  // });
});




</script>

<ul class="p-landing-page__quick-nav" role="menu" id="geopportal_submenu_list">
    <!-- <li role="menuitem" class="t-fg-/-gray-md">Jump to: </li>
    <li role="menuitem">
        <a href="#get-started">Get Started</a>
    </li>
    <li>•</li>
    <li role="menuitem">
        <a href="#explore">Explore Resources</a>
    </li>
    <li>•</li>
    <li role="menuitem">
        <a href="#communities">Explore Communities</a>
    </li> -->
</ul>

<!-- <ul class="p-landing-page__role-nav" role="menu">
    <li role="menuitem">What is your focus?</li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_home_active ?>"><a href="<?php echo home_url(get_theme_mod('headlink_default')); ?>">None (default)</a></li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_data_active ?>"><a href="<?php echo home_url(get_theme_mod('headlink_data')); ?>">Data</a></li>
    <li role="menuitem" class="<?php echo $geop_portal_subhead_new_active ?>"><a href="<?php echo home_url(get_theme_mod('headlink_new')); ?>">I'm new</a></li>
</ul> -->
