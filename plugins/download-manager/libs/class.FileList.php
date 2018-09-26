<?php


namespace WPDM\libs;


class FileList
{
    /**
     * @usage Callback function for [file_list] tag
     * @param $file
     * @param bool|false $play_only
     * @return string
     */
    public static function Table($file, $play_only = false)
    {
        global $current_user;

//    $files = \WPDM\Package::getFiles($file['ID']);
//
//    $template = new \WPDM\libs\Template();
//    return $template->Assign('files', $files)
//        ->Assign('package_id', $file['ID'])
//        ->Assign('fileinfo', $file['fileinfo'])
//        ->Assign('package_dir', $file['package_dir'])
//        ->Assign('password_lock', $file['password_lock'])
//        ->Fetch('file-list.php');

        $file['files'] = maybe_unserialize($file['files']);
        $fhtml = '';
        $idvdl = \WPDM\Package::isSingleFileDownloadAllowed($file['ID']);  //isset($file['individual_file_download']) ? $file['individual_file_download'] : 0;
        $pd = isset($file['publish_date'])&&$file['publish_date']!=""?strtotime($file['publish_date']):0;
        $xd = isset($file['expire_date'])&&$file['expire_date']!=""?strtotime($file['expire_date']):0;

        $nodl = $play_only?'style="display: none"':"";

        $cur = is_user_logged_in()?$current_user->roles:array('guest');

        if(($xd>0 && $xd<time()) || ($pd>0 && $pd>time()))  $idvdl = 0;

        $dir = isset($file['package_dir'])?$file['package_dir']:'';
        $dfiles = array();
        if($dir!=''){
            $dfiles = wpdm_get_files($dir);

        }

        $file['access'] = wpdm_allowed_roles($file['ID']);

        if ((is_array($file['files']) && count($file['files']) > 0) || (is_array($dfiles) && count($dfiles) > 0)) {
            $fileinfo = isset($file['fileinfo']) ? $file['fileinfo'] : array();
            $pwdlock = isset($file['password_lock']) ? $file['password_lock'] : 0;

            //Check if any other lock option applied for this package
            $olock = wpdm_is_locked($file['ID']) ? 1 : 0;
            $swl = 0;
            if(!isset($file['quota'])||$file['quota']<=0) $file['quota'] = 9999999999999;
            if(is_user_logged_in()) $cur[] = 'guest';
            if(!isset($file['access']) || count($file['access'])==0 || !wpdm_user_has_access($file['ID']) || wpdm_is_download_limit_exceed($file['ID']) || $file['quota'] <= $file['download_count']) $olock = 1;
            $pwdcol = $dlcol = '';

            if ($pwdlock && $idvdl) $pwdcol = "<th>".__("Password",'download-manager')."</th>";
            if ($idvdl && ($pwdlock || !$olock)) { $dlcol = "<th>".__("Action",'download-manager')."</th>"; $swl = 1; }
            $allfiles = is_array($file['files'])?$file['files']:array();

            //$allfiles = array_merge($allfiles, $dfiles);
            $fhtml = "<audio controls preload='auto' style='width: 100%;display: none' id='audio-player-{$file['ID']}'></audio><table id='wpdm-filelist-{$file['ID']}' class='wpdm-filelist table table-hover'><thead><tr><th>".__("File",'download-manager')."</th>{$pwdcol}{$dlcol}</tr></thead><tbody>";
            if (is_array($allfiles)) {
                $pc = 0;
                foreach ($allfiles as $fileID => $sfile) {

                    $individual_file_actions = '';
                    $individual_file_actions = apply_filters("individual_file_action", $individual_file_actions, $file['ID'], $sfile, $fileID);

                    $ind = \WPDM\libs\Crypt::Encrypt($sfile);
                    $pc++;

                    if (!isset($fileinfo[$sfile]) || !@is_array($fileinfo[$sfile])) $fileinfo[$sfile] = array();
                    if (!isset($fileinfo[$fileID]) || !@is_array($fileinfo[$fileID])) $fileinfo[$fileID] = array();

                    $filePass = isset($fileinfo[$sfile]['password'])?$fileinfo[$sfile]['password']:(isset($fileinfo[$fileID]['password'])?$fileinfo[$fileID]['password']:'');
                    $fileTitle = isset($fileinfo[$sfile]['title']) && $fileinfo[$sfile]['title'] != '' ? $fileinfo[$sfile]['title']:(isset($fileinfo[$fileID]['title']) && $fileinfo[$fileID]['title'] != '' ? $fileinfo[$fileID]['title']:preg_replace("/([0-9]+)_/", "",wpdm_basename($sfile)));

                    if ($swl) {
                        $mp3 = explode(".", $sfile);
                        $mp3 = end($mp3);
                        $mp3 = strtolower($mp3);
                        $play = $mp3 == 'mp3'?"<a rel='nofollow' class='btn btn-success btn-xs wpdm-btn-play song-{$file['ID']}-{$pc}' data-song-index='song-{$file['ID']}-{$pc}' id='song-{$file['ID']}-{$pc}' data-state='stop' href='#' data-player='audio-player-{$file['ID']}' data-song='" . wpdm_download_url($file) . "&ind=" . $ind . "'><i style='margin-top:0px' class='fa fa-play'></i></a>":"";

                        if ($filePass == '' && $pwdlock) $filePass = $file['password'];

                        $fhtml .= "<tr><td>{$fileTitle}</td>";
                        if ($pwdlock)
                            $fhtml .= "<td width='110' class='text-right'><input  onkeypress='jQuery(this).removeClass(\"input-error\");' size=10 type='password' value='' id='pass_{$file['ID']}_{$ind}' placeholder='".__('Password','download-manager')."' name='pass' class='form-control input-sm inddlps' /></td>";
                        if ($filePass != '' && $pwdlock)
                            $fhtml .= "<td width=150><button class='inddl btn btn-primary btn-sm' data-pid='{$file['ID']}' data-file='{$sfile}' rel='" . wpdm_download_url($file) . "&ind=" . $ind . "' data-pass='#pass_{$file['ID']}_{$ind}'><i class='fa fa-download'></i>&nbsp;".__("Download",'download-manager')."</button>&nbsp;{$individual_file_actions}</td></tr>";
                        else
                            $fhtml .= "<td style='width: 150px;white-space: nowrap;'  class='text-right'><a rel='nofollow' class='btn btn-primary btn-xs' {$nodl} href='" . wpdm_download_url($file) . "&ind=" . $ind . "'><i class='fa fa-download'></i> &nbsp;".__("Download",'download-manager')."</a>&nbsp;{$play}&nbsp;{$individual_file_actions}</td></tr>";
                    } else {
                        $fhtml .= "<tr><td>{$fileTitle}</td></tr>";
                    }
                }

            }

            if (is_array($dfiles)) {

                foreach ($dfiles as $ind => $sfile) {

                    $individual_file_actions = '';
                    $individual_file_actions = apply_filters("individual_file_action", $individual_file_actions, $file['ID'], $sfile, $ind);

                    $ind = str_replace($dir, "", $sfile);

                    $ind = \WPDM\libs\Crypt::Encrypt($sfile);


                    if (!isset($fileinfo[$sfile]) || !@is_array($fileinfo[$sfile])) $fileinfo[$sfile] = array();
                    if(!isset($fileinfo[$sfile]['password'])) $fileinfo[$sfile]['password'] = "";
                    if ($idvdl == 1 && ($pwdlock || !$olock)) {

                        $mp3 = explode(".", $sfile);
                        $mp3 = end($mp3);
                        $mp3 = strtolower($mp3);
                        $play = $mp3 == 'mp3'?"<a rel='nofollow' class='btn btn-success btn-xs wpdm-btn-play' href='#' data-player='audio-player-{$file['ID']}' data-song='" . wpdm_download_url($file) . "&ind=" . $ind . "'><i style='margin-top:0px' class='fa fa-play'></i></a>":"";

                        if ($fileinfo[$sfile]['password'] == '' && $pwdlock) $fileinfo[$sfile]['password'] = $file['password'];
                        $ttl = isset($fileinfo[$sfile]['title']) && $fileinfo[$sfile]['title']!="" ? $fileinfo[$sfile]['title'] :  str_replace('/', " <i class='fa fa-angle-right text-primary'></i> ",str_replace($dir, "", $sfile));
                        $fhtml .= "<tr><td>{$ttl}</td>";
                        $fileinfo[$sfile]['password'] = $fileinfo[$sfile]['password'] == '' ? $file['password'] : $fileinfo[$sfile]['password'];
                        if ($fileinfo[$sfile]['password'] != '' && $pwdlock)
                            $fhtml .= "<td width='110'  class='text-right'><input  onkeypress='jQuery(this).removeClass(\"input-error\");' size=10 type='password' value='' id='pass_{$file['ID']}_{$ind}' placeholder='".__('Password','download-manager')."' name='pass' class='form-control input-sm inddlps' /></td>";
                        if ($fileinfo[$sfile]['password'] != '' && $pwdlock)
                            $fhtml .= "<td width=150><button class='inddl btn btn-primary btn-sm' data-pid='{$file['ID']}' data-file='{$sfile}' rel='" . wpdm_download_url($file) . "&ind=" . $ind . "' data-pass='#pass_{$file['ID']}_{$ind}'><i class='fa fa-download'></i> &nbsp;".__('Download','download-manager')."</button>&nbsp;{$individual_file_actions}</td></tr>";
                        else
                            $fhtml .= "<td style='width: 150px;white-space: nowrap;'  class='text-right'><a rel='nofollow' class='btn btn-primary btn-xs' href='" . wpdm_download_url($file) . "&ind=" . $ind . "'><i class='fa fa-download'></i> &nbsp;".__('Download','download-manager')."</a>{$play}&nbsp;{$individual_file_actions}</td></tr>";
                    } else {
                        $ttl = isset($fileinfo[$sfile]['title']) && $fileinfo[$sfile]['title']!="" ? $fileinfo[$sfile]['title'] :  str_replace('/', " <i class='fa fa-angle-right text-primary'></i> ",str_replace($dir, "", $sfile));
                        $fhtml .= "<tr><td>{$ttl}</td></tr>";
                    }
                }

            }
            $fhtml .= "</tbody></table>";
            $siteurl = home_url('/');




        }


        return $fhtml;

    }


    /**
     * @usage Callback function for [file_list_extended] tag
     * @param $file
     * @return string
     * @usage Generate file list with preview
     */
    public static function Box($file)
    {
        global $current_user;
        $file['files'] = maybe_unserialize($file['files']);
        $fhtml = '';
        $idvdl = \WPDM\Package::isSingleFileDownloadAllowed($file['ID']); //isset($file['individual_file_download']) ? $file['individual_file_download'] : 0;
        $pd = isset($file['publish_date'])&&$file['publish_date']!=""?strtotime($file['publish_date']):0;
        $xd = isset($file['expire_date'])&&$file['expire_date']!=""?strtotime($file['expire_date']):0;

        $cur = is_user_logged_in()?$current_user->roles:array('guest');

        $_SESSION['wpdmfilelistcd_'.$file['ID']] = 1;

        if(($xd>0 && $xd<time()) || ($pd>0 && $pd>time()))  $idvdl = 0;

        $dir = isset($file['package_dir'])?$file['package_dir']:'';
        $dfiles = array();
        if($dir!=''){
            $dfiles = wpdm_get_files($dir, false);

        }

        $file['access'] = wpdm_allowed_roles($file['ID']);

        if (count($file['files']) > 0 || count($dfiles) > 0) {

            $fileinfo = isset($file['fileinfo']) ? $file['fileinfo'] : array();
            $pwdlock = isset($file['password_lock']) ? $file['password_lock'] : 0;

            //Check if any other lock option apllied for this package
            $olock = wpdm_is_locked($file['ID']) ? 1 : 0;
            $swl = 0;
            if(!isset($file['quota'])||$file['quota']<=0) $file['quota'] = 9999999999999;
            if(is_user_logged_in()) $cur[] = 'guest';
            if(!isset($file['access']) || count($file['access'])==0 || !wpdm_user_has_access($file['ID']) || wpdm_is_download_limit_exceed($file['ID']) || $file['quota'] <= $file['download_count']) $olock = 1;
            $pwdcol = $dlcol = '';
            if ($pwdlock && $idvdl) $pwdcol = "<th>".__("Password",'download-manager')."</th>";
            if ($idvdl && ($pwdlock || !$olock)) { $dlcol = "<th align=center>".__("Download",'download-manager')."</th>"; $swl = 1; }
            $allfiles = is_array($file['files'])?$file['files']:array();


            //$allfiles = array_merge($allfiles, $dfiles);
            $fhtml = "<div class='row' id='xfilelist'>";
            if (is_array($allfiles)) {

                foreach ($allfiles as $fileID => $sfile) {
                    $fhtml .= "<div class='col-md-4 col-sm-6 col-xs-6'><div class='panel panel-default'>";
                    $ind = \WPDM\libs\Crypt::Encrypt($sfile);

                    if (!@is_array($fileinfo[$sfile])) $fileinfo[$sfile] = array();
                    if (!@is_array($fileinfo[$fileID])) $fileinfo[$fileID] = array();

                    $filePass = isset($fileinfo[$sfile]['password'])?$fileinfo[$sfile]['password']:(isset($fileinfo[$fileID]['password'])?$fileinfo[$fileID]['password']:'');
                    $fileTitle = isset($fileinfo[$sfile]['title']) && $fileinfo[$sfile]['title'] != '' ? $fileinfo[$sfile]['title']:(isset($fileinfo[$fileID]['title']) && $fileinfo[$fileID]['title'] != '' ? $fileinfo[$fileID]['title']:preg_replace("/([0-9]+)_/", "",wpdm_basename($sfile)));


                    if ($filePass == '' && $pwdlock) $filePass = $file['password'];

                    $fhtml .= "<div class='panel-heading ttip' title='{$fileTitle}'>{$fileTitle}</div>";

                    $imgext = array('png','jpg','jpeg', 'gif');
                    $ext = explode(".", $sfile);
                    $ext = end($ext);
                    $ext = strtolower($ext);
                    $filepath = file_exists($sfile)?$sfile:UPLOAD_DIR.$sfile;
                    $thumb = "";

                    if($ext == '' || !file_exists(WPDM_BASE_DIR.'assets/file-type-icons/'.$ext.'.png')) $ext = '_blank';

                    if(in_array($ext, $imgext))
                        $thumb = wpdm_dynamic_thumb($filepath, array(88, 88));
                    if($thumb)
                        $fhtml .= "<div class='panel-body text-center'><img class='file-thumb' src='{$thumb}' alt='{$fileTitle}' /></div><div class='panel-footer footer-info'>".wpdm_file_size($sfile)."</div><div class='panel-footer'>";
                    else
                        $fhtml .= "<div class='panel-body text-center'><img class='file-ico' src='".WPDM_BASE_URL.'assets/file-type-icons/'.$ext.'.png'."' alt='{$fileTitle}' /></div><div class='panel-footer footer-info'>".wpdm_file_size($sfile)."</div><div class='panel-footer'>";


                    if ($swl) {

                        if ($filePass != '' && $pwdlock)
                            $fhtml .= "<div class='input-group'><input  onkeypress='jQuery(this).removeClass(\"input-error\");' size=10 type='password' value='' id='pass_{$file['ID']}_{$ind}' placeholder='Password' name='pass' class='form-control input-sm inddlps' />";
                        if ($filePass != '' && $pwdlock)
                            $fhtml .= "<span class='input-group-btn'><button class='inddl btn btn-primary btn-sm' file='{$sfile}' rel='" . wpdm_download_url($file) . "&ind=" . $ind . "' pass='#pass_{$file['ID']}_{$ind}'><i class='fa fa-download'></i></button></span></div>";
                        else
                            $fhtml .= "<a class='btn btn-primary btn-sm btn-block' href='" . wpdm_download_url($file) . "&ind=" . $ind . "'><span class='pull-left'><i class='fa fa-download'></i></span>&nbsp;".__("Download",'download-manager')."</a>";
                    }


                    $fhtml .= "</div></div></div>";
                }

            }

            if (is_array($dfiles)) {

                foreach ($dfiles as $ind => $sfile) {

                    $ind = \WPDM\libs\Crypt::Encrypt($sfile);

                    $fhtml .= "<div class='col-md-4 col-sm-6 col-xs-6'><div class='panel panel-default'>";
                    if (!isset($fileinfo[$sfile]) || !@is_array($fileinfo[$sfile])) $fileinfo[$sfile] = array();
                    if(!isset($fileinfo[$sfile]['password'])) $fileinfo[$sfile]['password'] = "";

                    if ($fileinfo[$sfile]['password'] == '' && $pwdlock) $fileinfo[$sfile]['password'] = $file['password'];
                    $ttl = isset($fileinfo[$sfile]['title']) && $fileinfo[$sfile]['title']!="" ? $fileinfo[$sfile]['title'] : preg_replace("/([0-9]+)_/", "", wpdm_basename($sfile));

                    $cttl = (is_dir($sfile))?"<a href='#' class='wpdm-indir' data-dir='{$ttl}' data-pid='{$file['ID']}'>{$ttl}/</a>": $ttl;

                    $fhtml .= "<div class='panel-heading ttip' title='{$ttl}'>{$cttl}</div>";

                    $imgext = array('png','jpg','jpeg', 'gif');
                    $ext = explode(".", $sfile);
                    $ext = end($ext);
                    $ext = strtolower($ext);
                    if(is_dir($sfile)) { $ext = 'folder'; }
                    $filepath = file_exists($sfile)?$sfile:UPLOAD_DIR.$sfile;
                    $thumb = "";
                    $showt = 1;
                    if(in_array($ext, $imgext) && apply_filters('file_list_extended_show_thumbs', $showt))
                        $thumb = wpdm_dynamic_thumb($filepath, array(88, 88));

                    $fticon = WPDM_BASE_URL.'assets/file-type-icons/'.$ext.'.png';

                    if(!file_exists(WPDM_BASE_DIR.'assets/file-type-icons/'.$ext.'.png'))
                        $fticon = WPDM_BASE_URL.'assets/file-type-icons/ini.png';

                    if($thumb)
                        $fhtml .= "<div class='panel-body text-center'><img class='file-thumb' src='{$thumb}' alt='{$ttl}' /></div><div class='panel-footer footer-info'>".wpdm_file_size($sfile)."</div><div class='panel-footer'>";
                    else
                        $fhtml .= "<div class='panel-body text-center'><img class='file-ico' src='".$fticon."' alt='{$ttl}' /></div><div class='panel-footer footer-info'>".wpdm_file_size($sfile)."</div><div class='panel-footer'>";


                    if ($swl) {
                        $fileinfo[$sfile]['password'] = $fileinfo[$sfile]['password'] == '' ? $file['password'] : $fileinfo[$sfile]['password'];
                        if ($fileinfo[$sfile]['password'] != '' && $pwdlock  && !is_dir($sfile))
                            $fhtml .= "<div class='input-group'><input  onkeypress='jQuery(this).removeClass(\"input-error\");' size=10 type='password' value='' id='pass_{$file['ID']}_{$ind}' placeholder='Password' name='pass' class='form-control input-sm inddlps' />";
                        if ($fileinfo[$sfile]['password'] != '' && $pwdlock  && !is_dir($sfile))
                            $fhtml .= "<span class='input-group-btn'><button class='inddl btn btn-primary btn-sm' file='{$sfile}' rel='" . wpdm_download_url($file) . "&ind=" . $ind . "' pass='#pass_{$file['ID']}_{$ind}'><i class='fa fa-download'></i></button></span></div>";
                        else  if(!is_dir($sfile))
                            $fhtml .= "<a class='btn btn-primary btn-sm btn-block' href='" . wpdm_download_url($file) . "&ind=" . $ind . "'><span class='pull-left'><i class='fa fa-download'></i></span>&nbsp;".__("Download",'download-manager')."</a>";
                        else
                            $fhtml .= "<a class='btn btn-primary btn-sm btn-block wpdm-indir' href='#'  data-dir='{$ttl}' data-pid='{$file['ID']}'><span class='pull-left'><i class='fa fa-folder'></i></span> &nbsp;".__("Browse",'download-manager')."</a>";

                    }


                    $fhtml .= "</div></div></div>";
                }

            }
            $fhtml .= "</div>";
            $siteurl = home_url('/');
            $fhtml .= "<script type='text/javascript' language='JavaScript'> jQuery('.inddl').click(function(){ var tis = this; jQuery.post('{$siteurl}',{wpdmfileid:'{$file['ID']}',wpdmfile:jQuery(this).attr('file'),actioninddlpvr:jQuery(jQuery(this).attr('pass')).val()},function(res){ res = res.split('|'); var ret = res[1]; if(ret=='error') jQuery(jQuery(tis).attr('pass')).addClass('input-error'); if(ret=='ok') location.href=jQuery(tis).attr('rel')+'&_wpdmkey='+res[2];});}); </script> ";


        }


        return $fhtml;

    }

}