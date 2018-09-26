<?php
if (!defined('ABSPATH')) die();

if (is_admin()) {


    add_action('wp_ajax_wpdm-activate-shop', 'wpdm_activate_shop');
    add_action('wp_ajax_wpdm-install-addon', 'wpdm_install_addon');

    add_action('wp_ajax_wpdm_generate_password', 'wpdm_generate_password');


    add_action('wp_ajax_wpdm_check_update', 'wpdm_check_update');
    add_action('admin_footer', 'wpdm_newversion_check');
    add_filter('admin_notices', 'wpdm_admin_notices');





} else {

    /** Short-Codes */

    add_shortcode("wpdm_file", "wpdm_package_link_legacy");
    add_shortcode("wpdm_category", "wpdm_category");


    /** Actions */

    add_action("init", 'wpdm_view_countplus');

    add_action("wp", "wpdm_ajax_call_exec");
    add_action('wpdm_user_logged_in', 'wpdm_user_logged_in');

    /** Filters */

    add_filter('the_content', 'wpdm_downloadable');

    add_filter("wpdm_render_custom_form_fields", 'wpdm_render_custom_data', 10, 2);


    add_action('init', 'wpdm_check_invpass');


    // Tags



}


add_filter('run_ngg_resource_manager', '__return_false');

