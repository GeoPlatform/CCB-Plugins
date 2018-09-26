<?php
if (!defined('ABSPATH')) die();
/**
 * Warning!!!
 * Don't change any function from here
 *
 */

global $stabs, $package, $wpdm_package;



/**
 * @param $tablink
 * @param $newtab
 * @param $func
 * @deprecated Deprecated from v4.2, use filter hook 'add_wpdm_settings_tab'
 * @usage Deprecated: From v4.2, use filter hook 'add_wpdm_settings_tab'
 */
function add_wdm_settings_tab($tablink, $newtab, $func)
{
    global $stabs;
    $stabs["{$tablink}"] = array('id' => $tablink,'icon'=>'fa fa-cog', 'link' => 'edit.php?post_type=wpdmpro&page=settings&tab=' . $tablink, 'title' => $newtab, 'callback' => $func);
}

function wpdm_create_settings_tab($tabid, $tabtitle, $callback, $icon = 'fa fa-cog')
{
    return \WPDM\admin\menus\Settings::createMenu($tabid, $tabtitle, $callback, $icon);
}


/**
 * @usage Check user's download limit
 * @param $id
 * @return bool
 */
function wpdm_is_download_limit_exceed($id)
{
    return \WPDM\Package::userDownloadLimitExceeded($id);
}


/**
 * @param (int|array) $package Package ID (INT) or Complete Package Data (Array)
 * @param string $ext
 * @return string|void
 */
function wpdm_download_url($package, $ext = '')
{
    if(!is_array($package)) $package = intval($package);
    $id = is_int($package)?$package:$package['ID'];
    return \WPDM\Package::getDownloadURL($id, $ext);
}


/**
 * @usage Check if a download manager category has child
 * @param $parent
 * @return bool
 */

function wpdm_cat_has_child($parent)
{
    $termchildren = get_term_children( $parent, 'wpdmcategory' );
    if(count($termchildren)>0) return true;
    return false;
}

/**
 * @usage Get category checkbox list
 * @param int $parent
 * @param int $level
 * @param array $sel
 */
function wpdm_cblist_categories($parent = 0, $level = 0, $sel = array())
{
    $cats = get_terms('wpdmcategory', array('hide_empty' => false, 'parent' => $parent));
    if (!$cats) $cats = array();
    if ($parent != '') echo "<ul>";
    foreach ($cats as $cat) {
        $id = $cat->slug;
        $pres = $level * 5;

            if (in_array($id, $sel))
                $checked = 'checked=checked';
            else
                $checked = '';
            echo "<li style='margin-left:{$pres}px;padding-left:0'><label><input id='c$id' type='checkbox' name='file[category][]' value='$id' $checked /> ".$cat->name."</label></li>\n";
            wpdm_cblist_categories($cat->term_id, $level + 1, $sel);

    }
    if ($parent != '') echo "</ul>";
}

/**
 * @usage Get category dropdown list
 * @param string $name
 * @param string $selected
 * @param string $id
 * @param int $echo
 * @return string
 */
function wpdm_dropdown_categories($name = '', $selected = '', $id = '', $echo = 1)
{
    return wp_dropdown_categories(array('show_option_none'=>__('Select category','download-manager'),'show_count'=>0,'orderby'=>'name','echo'=>$echo, 'class' => 'form-control selectpicker', 'taxonomy' => 'wpdmcategory','hide_empty' => 0, 'name' => $name, 'id' => $id ,'selected' => $selected));

}


/**
 * @usage Post with cURL
 * @param $url
 * @param $data
 * @return bool|mixed|string
 */
function remote_post($url, $data)
{
    $fields_string = "";
    foreach ($data as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');
    //open connection
    if(!function_exists('curl_init')) return WPDM_Messages::Error('<b>cURL</b> is not active or installed or not functioning properly in your server',-1);
    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    return $result;
}

/**
 * @usage Get with cURL
 * @param $url
 * @return bool|mixed|string
 */
function remote_get($url)
{
    $res = wp_remote_get($url);
    $content  = '';
    if( is_array($res) )
        $content = $res['body'];
    return $content;
}



function wpdm_ajax_call_exec()
{
    if (isset($_POST['action']) && $_POST['action'] == 'wpdm_ajax_call') {
        if ($_POST['execute']=='wpdm_getlink')
            wpdm_getlink();
        die();
    }
}


function wpdm_plugin_data($dir){
    $plugins = get_plugins();
    foreach($plugins as $plugin => $data){
        $data['plugin_index_file'] = $plugin;
        $plugin = explode("/", $plugin);
        if($plugin[0]==$dir) return $data;
    }
    return false;
}

/**
 * @usage Update Notification for Custom Addons
 * @param $plugin_name
 * @param $version
 * @param $update_url
 */
function wpdm_plugin_update_email($plugin_name, $version, $update_url)
{

    $admin_email = get_option('admin_email');
    $hash = "__wpdm_".md5($plugin_name.$version);
    $sent = get_option($hash, false);
    if(!$sent) {
        $email = array(
            'label' => __('New Package Notification', 'wpdmpro'),
            'for' => 'admin',
            'default' => array('subject' => __($plugin_name . ': Update Available', 'wpdmpro'),
                'from_name' => "WordPress Download Manager",
                'from_email' => "support@wpdownloadmanager.com",
                'to_email' => $admin_email,
                'message' => ''
            ));
        $main_content = 'New version available. Please update your copy.<br/><br/><table class="table" style="width: 100%" cellpadding="10px"><tr><td width="120px">Plugin Name:</td><td>' . $plugin_name . '</td></tr><tr><td width="120px">Version:</td><td>' . $version . '</td></tr><tr><td width="120px"></td><td><div style="padding-top: 10px;"><a style="border:1px solid #5f9f60 !important;color: #ffffff;background: #5f9f60;padding:10px 25px;text-decoration:none;font-weight:bold !important;text-transform:uppercase" class="btn" href="' . $update_url . '">Update Now</a></div></td></tr></table>';
        $template = $email['default'];
        $template_file = "default.html";
        $template_data = file_get_contents(wpdm_tpl_path($template_file, WPDM_BASE_DIR . 'email-templates/'));
        $message = str_replace(array("[#sitename#]", "[#site_url#]", "[#site_tagline#]","[#message#]", "[#facebook#]"), array("WordPress Download Manager", "https://www.wpdownloadmanager.com/", "Plugin Update Available", $main_content, "https://www.facebook.com/wpdownloadmanager/"), $template_data);

        $headers = "From: WordPress Download Manager <support@wpdownloadmanager.com>\r\nContent-type: text/html\r\n";

        wp_mail($admin_email, $plugin_name . ': Update Available', $message, $headers);
        update_option($hash, 1);

    }
}

function wpdm_check_update()
{

    if(!current_user_can(WPDM_ADMIN_CAP)) return;

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    $latest = '';//get_option('wpdm_latest');
    $latest_check = get_option('wpdm_latest_check');
    $time = time() - intval($latest_check);
    $plugins = get_plugins();

    $latest_v_url = 'https://www.wpdownloadmanager.com/versions.php';

    if ($latest == '' || $time > 86400) {
        $latest = remote_get($latest_v_url);
        update_option('wpdm_latest', $latest);
        update_option('wpdm_latest_check', time());

    }
    $latest = maybe_unserialize($latest);


    $page = isset($_REQUEST['page'])?esc_attr($_REQUEST['page']):'';
    $plugin_info_url = isset($_REQUEST['plugin_url'])?$_REQUEST['plugin_url']:'https://www.wpdownloadmanager.com/purchases/';
    if(is_array($latest)){
        foreach($latest as $plugin_dir => $latestv){
            $plugin_data = wpdm_plugin_data($plugin_dir);

            if (version_compare($plugin_data['Version'], $latestv, '<') == true  && $plugin_dir != 'download-manager') {
                $plugin_name = $plugin_data['Name'];
                $plugin_info_url = $plugin_data['PluginURI'];
                $active = is_plugin_active($plugin_data['plugin_index_file'])?'active':'';
                $trid = sanitize_title($plugin_name);
                $plugin_update_url =  admin_url('/edit.php?post_type=wpdmpro&page=settings&tab=plugin-update&plugin='.$plugin_dir); //'https://www.wpdownloadmanager.com/purchases/?'; //
                if($trid!=''){
                    wpdm_plugin_update_email($plugin_name, $latestv, $plugin_update_url);
                    if ($page == 'plugins') {
                        echo <<<NOTICE
     <script type="text/javascript">
      jQuery(function(){
        jQuery('tr:data[data-slug={$trid}]').addClass('update').after('<tr class="plugin-update-tr {$active} update"><td colspan=3 class="plugin-update colspanchange"><div class="update-message notice inline notice-warning notice-alt"><p>There is a new version of <strong>{$plugin_name}</strong> available. <a href="{$plugin_update_url}&v={$latestv}" style="margin-left:10px" target=_blank>Update now ( v{$latestv} )</a></p></div></td></tr>');
      });
      </script>
NOTICE;
                    } else {
                        echo <<<NOTICE
     <script type="text/javascript">
      jQuery(function(){
        jQuery('.wrap > h2').after('<div class="updated error" style="margin:10px 0px;padding:10px;border-left:2px solid #dd3d36;background: #ffffff"><div style="float:left;"><b style="color:#dd3d36;">Important!</b><br/>There is a new version of <u>{$plugin_name}</u> available.</div> <a style="border-radius:0; float:right;;color:#ffffff; background: #D54E21;padding:10px 15px;text-decoration: none;font-weight: bold;font-size: 9pt;letter-spacing:1px" href="{$plugin_update_url}&v={$latestv}"  target=_blank><i class="fas fa-sync"></i> update v{$latestv}</a><div style="clear:both"></div></div>');
         });
         </script>
NOTICE;
                    }}
            }
        }}
    if(wpdm_is_ajax()) die();
}

function wpdm_newversion_check(){

    if(!current_user_can(WPDM_ADMIN_CAP)) return;

    $tmpvar = explode("?", basename($_SERVER['REQUEST_URI']));
    $page = array_shift($tmpvar);
    $page = explode(".", $page);
    $page = array_shift($page);

    if (get_option('wpdm_update_notice') == 'disabled' || !($page == 'plugins' || get_post_type()=='wpdmpro') ) return;

    $page = $page == 'plugins'?$page:get_post_type();

    ?>
    <script type="text/javascript">
        jQuery(function(){

            jQuery.post(ajaxurl, {
                action:         'wpdm_check_update',
                page:           '<?php echo $page; ?>'
            }, function(res){
                jQuery('#wpfooter').after(res);
            });


        });
    </script>
    <?php
}

function wpdm_access_token(){
    $at = get_option("__wpdm_access_token", false);
    if($at)
        return $at;
    if(get_option('__wpdm_suname') != '') {
        $access_token = remote_get('https://www.wpdownloadmanager.com/?wpdm_api_req=getAccessToken&user=' . urlencode(get_option('__wpdm_suname')) . '&pass=' . urlencode(get_option('__wpdm_supass')));
        $access_token = json_decode($access_token);
        if (isset($access_token->access_token)) {
            update_option("__wpdm_access_token", $access_token->access_token);
            return $access_token->access_token;
        }
    }
    return '';
}



/**
 * Fontend style at tinymce
 */
if (!function_exists('wpdm_frontend_css')) {
    function wpdm_frontend_css($wp)
    {
        $wp .= ',' . get_bloginfo('stylesheet_url');
        return $wp;
    }
}


if (!isset($_REQUEST['P3_NOCACHE'])) {

include(dirname(__FILE__) . "/wpdm-hooks.php");

$files = scandir(dirname(__FILE__) . '/modules/');
    foreach ($files as $file) {
        $tmpdata = explode(".", $file);
        if ($file != '.' && $file != '..' && !@is_dir($file) && end($tmpdata) == 'php')
            include(dirname(__FILE__) . '/modules/' . $file);
    }
}


