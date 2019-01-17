<?php
// Secondary header, used for the home page.
?>

<script>

var geopportal_hotnav_obj_array = [];
var geopportal_hotnav_name_array = [];
var geopportal_hotnav_link_array = [];

jQuery(document).ready(function() {
  geopportal_hotnav_obj_array = jQuery("div").filter("[id^=geopportal_anchor_]");

  for (var i = 0; i < geopportal_hotnav_obj_array.length; i++){
    geopportal_hotnav_link_array.push('#' + jQuery(geopportal_hotnav_obj_array[i]).attr('id'));
    geopportal_hotnav_name_array.push(jQuery(geopportal_hotnav_obj_array[i]).attr('title'));
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
