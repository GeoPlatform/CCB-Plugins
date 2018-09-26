<?php
/**
 * Created by PhpStorm.
 * User: shahnuralam
 * Date: 11/9/15
 * Time: 7:44 PM
 */

namespace WPDM\admin\menus;


use \WPDM\libs\FileSystem;

class Stats
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'Menu'));
        add_action('admin_init', array($this, 'Export'));
    }

    function Menu()
    {
        $menu_access_cap = apply_filters('wpdm_admin_menu_stats', WPDM_MENU_ACCESS_CAP);
        add_submenu_page('edit.php?post_type=wpdmpro', __('History &lsaquo; Download Manager','download-manager'), __('History','download-manager'), $menu_access_cap, 'wpdm-stats', array($this, 'UI'));
    }

    function Export(){
        if(wpdm_query_var('page') == 'wpdm-stats' && wpdm_query_var('task') == 'export'){
            global $wpdb;
            $data = $wpdb->get_results("select s.*, p.post_title as file from {$wpdb->prefix}ahm_download_stats s, {$wpdb->prefix}posts p where p.ID = s.pid order by id DESC");
            FileSystem::downloadHeaders("download-stats.csv");
            echo "File,User ID,Order ID,Date,Timestamp,IP\r\n";
            foreach ($data as $d){
                echo "{$d->file},{$d->uid},{$d->oid},{$d->year}-{$d->month}-{$d->day},{$d->timestamp},{$d->ip}\r\n";
            }
            die();
        }
    }

    function UI()
    {
        include(WPDM_BASE_DIR."admin/tpls/stats.php");
    }


}