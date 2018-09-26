<?php

if (!defined('ABSPATH')) die('Error!');

if(wpdm_ip_blocked()) {
    $_ipblockedmsg =  __('Your IP address is blocked!', 'download-manager');
    $ipblockedmsg = get_option('__wpdm_blocked_ips_msg', '');
    $ipblockedmsg = $ipblockedmsg == ''?$_ipblockedmsg:$ipblockedmsg;
    WPDM_Messages::Error($ipblockedmsg, 1);
}

do_action("wpdm_onstart_download", $package);

global $current_user, $dfiles;

$speed = 1024; //in KB - default 1 MB
$speed = apply_filters('wpdm_download_speed', $speed);

//get_currentuserinfo();

if(in_array($package['post_status'], array('draft','inherit','trash','pending'))) wp_die(__('Package is not available!', 'wpdmpro'));

if (wpdm_is_download_limit_exceed($package['ID'])) wp_die(__('Download Limit Exceeded','download-manager'));
$files = \WPDM\Package::getFiles($package['ID']);
$fileCount = count($files);

$log = new \WPDM\libs\DownloadStats();
$oid = isset($_GET['oid']) ? esc_attr($_GET['oid']) : '';
$indsc = 1;
$indsc = isset($_GET['ind']) && get_option('__wpdm_ind_stats') == 0 ? 0 : 1;
if ($indsc && !isset($_GET['nostat']))
    $log->newStat($package['ID'], $current_user->ID, $oid);

if ($fileCount == 0 || trim($files[0]) == '') {
    if (isset($package['sourceurl']) && $package['sourceurl'] != '') {

        if (!isset($package['url_protect']) || $package['url_protect'] == 0 && strpos($package['sourceurl'], '://')) {
            header('location: ' . $package['sourceurl']);
            die();
        }

        $r_filename = basename($package['sourceurl']);
        $r_filename = explode("?", $r_filename);
        $r_filename = $r_filename[0];
        wpdm_download_file($package['sourceurl'], $r_filename, $speed, 1, $package);
        die();
    }

    wpdm_download_data('download-not-available.txt', __('Sorry! Download is not available yet.','download-manager'));
    die();

}

$idvdl = \WPDM\Package::isSingleFileDownloadAllowed($package['ID']) && isset($_GET['ind']); //isset($package['individual_file_download']) && isset($_GET['ind']) ? 1 : 0;

if ($fileCount > 1 && !$idvdl) {

    $zipped = get_post_meta($package['ID'], "__wpdm_zipped_file", true);
    $cache_zip = get_option('__wpdm_cache_zip', 0);
    $cache_zip_po = get_post_meta($package['ID'], "__wpdm_cache_zip", true);
    //dd($cache_zip_po);
    $cache_zip = $cache_zip_po == -1 || !$cache_zip_po ? $cache_zip : $cache_zip_po;
    if ($zipped == '' || !file_exists($zipped) || $cache_zip == 0) {
        if(class_exists('ZipArchive'))
        $zip = new ZipArchive();
        else wp_die(__('Please activate "zlib" in your server','download-manager'));
        $zipped = UPLOAD_DIR . sanitize_file_name($package['post_title']) . '-' . $package['ID'] . '.zip';

        if ($zip->open($zipped, ZIPARCHIVE::CREATE) !== TRUE) {
            wpdm_download_data('error.txt', 'Failed to create file. Please make "' . UPLOAD_DIR . '" dir writable..');
            die();
        }

        foreach ($files as $file) {
            $file = trim($file);
            if (file_exists(UPLOAD_DIR . $file)) {
                $fnm = preg_replace("/^[0-9]+?wpdm_/", "", $file);
                $zip->addFile(UPLOAD_DIR . $file, $fnm);
            } else if (file_exists($file))
                $zip->addFile($file, wpdm_basename($file));
            else if (file_exists(WP_CONTENT_DIR . end($tmp = explode("wp-content", $file)))) //path fix on site move
                $zip->addFile(WP_CONTENT_DIR . end($tmp = explode("wp-content", $file)), wpdm_basename($file));
        }
        if ($dfiles) {
            foreach ($dfiles as $file) {
                $zip->addFile($file, str_replace($dir, '', $file));
            }
        }

        $zip->close();
        update_post_meta($package['ID'], "__wpdm_zipped_file", $zipped);
    }

    wpdm_download_file($zipped, sanitize_file_name($package['post_title']) . '.zip', $speed, 1, $package);
    //@unlink($zipped);
} else {

    //Individual file or single file download section

    $indfile = '';

    if(count($files) == 0) wp_die(__('No file found!','download-manager'));
    $indfile = array_shift($files);
    $indfile = trim($indfile);
    //URL Download
    if ($indfile != '' && strpos($indfile, '://')) {

        if (!isset($package['url_protect']) || $package['url_protect'] == 0) {
            header('location: ' . $indfile);

        } else {
            $indfile = trim($indfile);
            $r_filename = wpdm_basename($indfile);
            $r_filename = explode("?", $r_filename);
            $r_filename = $r_filename[0];
            wpdm_download_file($indfile, $r_filename, $speed, 1, $package);

        }

        die();
    }

    if (file_exists(UPLOAD_DIR . $indfile))
        $filepath = UPLOAD_DIR . $indfile;
    else if (file_exists($indfile))
        $filepath = $indfile;
    else if (file_exists(WP_CONTENT_DIR . end($tmp = explode("wp-content", $indfile)))) //path fix on site move
        $filepath = WP_CONTENT_DIR . end($tmp = explode("wp-content", $indfile));
    else {
        do_action("wpdm_file_not_found", $package);
        wpdm_download_data('file-not-found.txt', 'File not found or deleted from server');
        die();
    }

    //$plock = get_wpdm_meta($file['id'],'password_lock',true);
    //$fileinfo = get_wpdm_meta($package['id'],'fileinfo');

    $filename = wpdm_basename($filepath);
    $filename = preg_replace("/([0-9]+)[wpdm]+_/", "", $filename);

    wpdm_download_file($filepath, $filename, $speed, 1, $package);
    //@unlink($filepath);

}
do_action("after_download", $package);

die();

