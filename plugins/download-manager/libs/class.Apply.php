<?php

namespace WPDM\libs;

class Apply {

    function __construct(){

        add_filter('wpdm_custom_data', array( $this, 'SR_CheckPackageAccess' ), 10, 2);

        add_action('publish_wpdmpro', array( $this, 'customPings' ));


        $this->AdminActions();
        $this->FrontendActions();

    }

    function FrontendActions(){
        if(is_admin()) return;
        add_action("init", array($this, 'playMedia'), 0);
        add_action("init", array($this, 'triggerDownload'), 1);
        add_filter('widget_text', 'do_shortcode');
        add_action('query_vars', array( $this, 'dashboardPageVars' ));
        add_action('init', array( $this, 'addWriteRules' ), 1, 0 );
        add_action('init', array($this, 'Login'));
        add_action('init', array($this, 'Register'));
        add_action('init', array($this, 'wpdmIframe'));
        add_action('wp', array($this, 'updateProfile'));
        add_action('wp', array($this, 'Logout'));
        add_action('request', array($this, 'rssFeed'));
        add_filter( 'ajax_query_attachments_args', array($this, 'usersMediaQuery') );
        add_action( 'init', array($this, 'sfbAccess'));
        remove_action('wp_head', 'wp_generator');
        add_action( 'wp_head', array($this, 'addGenerator'), 9);
        add_filter('pre_get_posts', array($this, 'queryTag'));
        add_filter('the_excerpt_embed', array($this, 'oEmbed'));



    }

    function AdminActions(){
        if(!is_admin()) return;
        add_action( 'admin_init', array($this, 'sfbAccess'));
        add_action('save_post', array( $this, 'DashboardPages' ));
        add_action( 'wp_ajax_wpdm_clear_stats', array($this, 'clearStats'));
        add_action( 'wp_ajax_clear_cache', array($this, 'clearCache'));

    }

    function SR_CheckPackageAccess($data, $id){
        global $current_user;
        $skiplocks = maybe_unserialize(get_option('__wpdm_skip_locks', array()));
        if( is_user_logged_in() ){
            foreach($skiplocks as $lock){
                unset($data[$lock."_lock"]); // = 0;
            }
        }

        return $data;
    }

    function AddWriteRules(){
        global $wp_rewrite;
        $udb_page_id = get_option('__wpdm_user_dashboard', 0);
        if($udb_page_id) {
            $page_name = get_post_field("post_name", $udb_page_id);
            add_rewrite_rule('^' . $page_name . '/(.+)/?', 'index.php?page_id=' . $udb_page_id . '&udb_page=$matches[1]', 'top');
        }
        $adb_page_id = get_option('__wpdm_author_dashboard', 0);

        if($adb_page_id) {
            $page_name = get_post_field("post_name", $adb_page_id);
            add_rewrite_rule('^' . $page_name . '/(.+)/?', 'index.php?page_id=' . $adb_page_id . '&adb_page=$matches[1]', 'top');
        }
        //$wp_rewrite->flush_rules();
        //dd($wp_rewrite);


    }

    function DashboardPages($post_id){
        if ( wp_is_post_revision( $post_id ) )  return;
        $page_id = get_option('__wpdm_user_dashboard', 0);
        $post = get_post($post_id);
        $flush = 0;
        if((int)$page_id > 0 && has_shortcode($post->post_content, "wpdm_user_dashboard")) {
            update_option('__wpdm_user_dashboard', $post_id);
            $flush = 1;
        }

        $page_id = get_option('__wpdm_author_dashboard', 0);
        $post = get_post($post_id);

        if((int)$page_id > 0 && has_shortcode($post->post_content, "wpdm_frontend")) {
            update_option('__wpdm_author_dashboard', $post_id);
            $flush = 1;
        }

        if($flush == 1) {
            $this->AddWriteRules();
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }

    }

    function dashboardPageVars( $vars ){
        array_push($vars, 'udb_page', 'adb_page');
        return $vars;
    }


    function Login()
    {
        global $wp_query, $post, $wpdb;
        if (!isset($_POST['wpdm_login'])) return;
        if(!isset($_SESSION['login_try'])) $_SESSION['login_try'] = 0;

        $_SESSION['login_try'] = $_SESSION['login_try'] + 1;
        if($_SESSION['login_try'] > 10) wp_die("Slow Down!");

        unset($_SESSION['login_error']);
        $creds = array();
        $creds['user_login'] = $_POST['wpdm_login']['log'];
        $creds['user_password'] = $_POST['wpdm_login']['pwd'];
        $creds['remember'] = isset($_POST['rememberme']) ? $_POST['rememberme'] : false;
        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            $_SESSION['login_error'] = $user->get_error_message();

            if(wpdm_is_ajax()) die('failed');

            header("location: " . $_SERVER['HTTP_REFERER']);
            die();
        } else {
            do_action('wp_login', $creds['user_login'], $user);
            if(wpdm_is_ajax()) die('success');

            header("location: " . $_POST['redirect_to']);
            die();
        }
    }

    function Logout()
    {

        if (get_query_var('adb_page') == 'logout' || get_query_var('udb_page') == 'logout') {
            wp_logout();
            if(get_query_var('udb_page') == 'logout')
                header("location: " . get_permalink(get_option('__wpdm_user_dashboard')));
            else
                header("location: " . get_permalink(get_option('__wpdm_author_dashboard')));
            die();
        }
    }

    function Register()
    {
        global $wp_query, $wpdb;
        if (!isset($_POST['wpdm_reg'])) return;

        $shortcode_params = \WPDM\libs\Crypt::Decrypt($_REQUEST['phash']);

        if(isset($shortcode_params['captcha']) && $shortcode_params['captcha'] == 'true') {
            if(isset($_REQUEST['g-recaptcha-response'])) {
                $p = array('secret' => get_option('_wpdm_recaptcha_secret_key'), 'response' => $_REQUEST['g-recaptcha-response']);
                $recap = remote_post('https://www.google.com/recaptcha/api/siteverify', $p);
                $recap = json_decode($recap);
            } else {
                $recap = new \stdClass();
                $recap->success = false;
            }
            if ($recap->success == false) {
                $_SESSION['reg_error'] = __('Captcha Verification Failed!','download-manager');
                if (wpdm_is_ajax()) die('Error: ' . $_SESSION['reg_error']);
                header("location: " . $_POST['permalink']);
                die();
            }
        }

        if(!get_option('users_can_register') && isset($_POST['wpdm_reg'])){
            if(wpdm_is_ajax()) die(__('Error: User registration is disabled!','download-manager'));
            else $_SESSION['reg_error'] = __('Error: User registration is disabled!','download-manager');
            header("location: " . esc_attr($_POST['permalink']));
            die();
        }

        extract($_POST['wpdm_reg']);
        $_SESSION['tmp_reg_info'] = $_POST['wpdm_reg'];
        $user_id = username_exists($user_login);
        $loginurl = $_POST['permalink'];
        if ($user_login == '') {
            $_SESSION['reg_error'] = __('Username is Empty!','download-manager');

            if(wpdm_is_ajax()) die('Error: '.$_SESSION['reg_error']);


            header("location: " . $_POST['permalink']);
            die();
        }
        if (!isset($user_email) || !is_email($user_email)) {
            $_SESSION['reg_error'] = __('Invalid Email Address!','download-manager');

            if(wpdm_is_ajax()) die($_SESSION['reg_error']);

            header("location: " . $_POST['permalink']);
            die();
        }

        if (!$user_id) {
            $user_id = email_exists($user_email);
            if (!$user_id) {

                $auto_login = 0;
                if(isset($shortcode_params['autologin']) && $shortcode_params['autologin'] == 'true')
                    $auto_login = 1;

                $user_pass = (isset($shortcode_params['verifyemail']) && $shortcode_params['verifyemail'] == 'true')?wp_generate_password(12, false):$user_pass;

                $errors = new \WP_Error();

                do_action( 'register_post', $user_login, $user_email, $errors );

                $errors = apply_filters( 'registration_errors', $errors, $user_login, $user_email );

                if ( $errors->get_error_code() ) {
                    if(wpdm_is_ajax()) die('Error: ' . $errors->get_error_message() );
                    else $_SESSION['reg_error'] = $errors->get_error_message();
                    header("location: " . $_POST['permalink']);
                    die();
                }

                $user_id = wp_create_user($user_login, $user_pass, $user_email);
                $name = explode(" ", $display_name);
                if(!isset($name[1])) $name[1] = '';
                wp_update_user(array('ID' => $user_id, 'display_name' => $display_name, 'first_name' => $name[0], 'last_name' => $name[1]));
                $display_name = isset($display_name)?$display_name:$user_id;
                $headers = "From: " . get_option('sitename') . " <" . get_option('admin_email') . ">\r\nContent-type: text/html\r\n";
                $message = file_get_contents(wpdm_tpl_path('wpdm-new-user.html',WPDM_BASE_DIR.'tpls/email-templates/'));
                $loginurl = $_POST['permalink'];
                $message = str_replace(array("[#support_email#]", "[#homeurl#]", "[#sitename#]", "[#loginurl#]", "[#name#]", "[#username#]", "[#password#]", "[#date#]"), array(get_option('admin_email'), site_url('/'), get_option('blogname'), $loginurl, $display_name, $user_login, $user_pass, date("M d, Y")), $message);

                if ($user_id) {
                    wp_mail($user_email, "Welcome to " . get_option('sitename'), $message, $headers);

                }
                unset($_SESSION['guest_order']);
                unset($_SESSION['login_error']);
                unset($_SESSION['tmp_reg_info']);
                //if(!isset($_SESSION['reg_warning']))
                $creds['user_login'] = $user_login;
                $creds['user_password'] = $user_pass;
                $creds['remember'] = true;
                $_SESSION['sccs_msg'] = "Your account has been created successfully and login info sent to your mail address.";
                if($auto_login==1) {
                    $_SESSION['sccs_msg'] = "Your account has been created successfully";
                    wp_signon($creds);
                    wp_set_current_user($user_id);
                    wp_set_auth_cookie($user_id);

                }

                if(wpdm_is_ajax()) die('success');

                header("location: " . $loginurl);
                die();
            } else {
                $_SESSION['reg_error'] = __('Email already exists.');
                $plink = $_POST['permalink'] ? $_POST['permalink'] : $_SERVER['HTTP_REFERER'];

                if(wpdm_is_ajax()) die('Error: '.$_SESSION['reg_error']);

                header("location: " . $loginurl);
                die();
            }
        } else {
            $_SESSION['reg_error'] = __('User already exists.');
            $plink = $_POST['permalink'] ? $_POST['permalink'] : $_SERVER['HTTP_REFERER'];

            if(wpdm_is_ajax()) die('Error: '.$_SESSION['reg_error']);

            header("location: " . $loginurl);
            die();
        }
        die();
    }

    function updateProfile()
    {
        global $current_user;

        if (isset($_POST['wpdm_profile']) && is_user_logged_in()) {

            $error = 0;

            $pfile_data['display_name'] = $_POST['wpdm_profile']['display_name'];

            if ($_POST['password'] != $_POST['cpassword']) {
                $_SESSION['member_error'][] = 'Password not matched';
                $error = 1;
            }
            if (!$error) {
                $pfile_data['ID'] = $current_user->ID;
                if ($_POST['password'] != '')
                    $pfile_data['user_pass'] = $_POST['password'];
                wp_update_user($pfile_data);

                update_user_meta($current_user->ID, 'payment_account', $_POST['payment_account']);
                $_SESSION['member_success'] = 'Profile data updated successfully.';
            }

            do_action("wpdm_update_profile");

            if(wpdm_is_ajax()){
                if($error == 1){
                    $msg['type'] = 'danger';
                    $msg['msg'] = $_SESSION['member_error'];
                    unset($_SESSION['member_error']);
                    echo json_encode($msg);
                    die();
                } else {
                    $msg['type'] = 'success';
                    $msg['msg'] = $_SESSION['member_success'];
                    unset($_SESSION['member_success']);
                    echo json_encode($msg);
                    die();
                }
            }
            header("location: " . $_SERVER['HTTP_REFERER']);
            die();
        }
    }

    function playMedia(){

        if(strstr("!{$_SERVER['REQUEST_URI']}", "/wpdm-media/")){
            $media = explode("wpdm-media/", $_SERVER['REQUEST_URI']);
            $media = explode("/", $media[1]);
            list($ID, $file, $name) = $media;
            $key = wpdm_query_var('_wpdmkey');

            if ( isset($_SERVER['HTTP_RANGE']) ) {
                $partialContent = true;
                preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
                $offset = intval($matches[1]);
                $length = intval($matches[2]) - $offset;
            } else {
                $partialContent = false;
            }

            $keyValid = is_wpdmkey_valid($ID, $key, true);
            if(!$partialContent) {
                if ($key == '' || !$keyValid)
                    \WPDM_Messages::Error(stripslashes(get_option('wpdm_permission_msg')), 1);
            }
            $files = \WPDM\Package::getFiles($ID);
            $file = $files[$file];
            $file = \WPDM\libs\FileSystem::fullPath($file, $ID);
            $stream = new \WPDM\libs\StreamMedia($file);
            $stream->start();
            die();
        }
    }


    /**
     * @usage Process Download Request
     */
    function triggerDownload()
    {

        global $wpdb, $current_user, $wp_query;
        //get_currentuserinfo();
        if (!isset($wp_query->query_vars['wpdmdl']) && !isset($_GET['wpdmdl'])) return;
        $id = isset($_GET['wpdmdl']) ? (int)$_GET['wpdmdl'] : (int)$wp_query->query_vars['wpdmdl'];
        if ($id <= 0) return;
        $key = array_key_exists('_wpdmkey', $_GET) ? esc_attr($_GET['_wpdmkey']) : '';
        $key = $key == '' && array_key_exists('_wpdmkey', $wp_query->query_vars) ? $wp_query->query_vars['_wpdmkey'] : $key;
        $key = preg_replace("/[^_a-z|A-Z|0-9]/i", "", $key);
        $key = "__wpdmkey_".$key;
        $package = get_post($id, ARRAY_A);
        $package['ID'] = $package['ID'];
        $package = array_merge($package, wpdm_custom_data($package['ID']));
        if (isset($package['files']))
            $package['files'] = maybe_unserialize($package['files']);
        else
            $package['files'] = array();
        //$package = wpdm_setup_package_data($package);

        $package['access'] = wpdm_allowed_roles($id);

        if (is_array($package)) {
            $role = @array_shift(@array_keys($current_user->caps));
            $cpackage = apply_filters('before_download', $package);
            $lock = '';
            $package = $cpackage ? $cpackage : $package;

            if (isset($package['password_lock']) && $package['password_lock'] == 1) $lock = 'locked';
            if (isset($package['captcha_lock']) && $package['captcha_lock'] == 1) $lock = 'locked';

            if ($lock !== 'locked')
                $lock = apply_filters('wpdm_check_lock', $lock, $id);

            if (isset($_GET['masterkey']) && esc_attr($_GET['masterkey']) == $package['masterkey']) {
                $lock = 0;
            }


            $limit = $key ? (int)trim(get_post_meta($package['ID'], $key, true)) : 0;


            if ($limit <= 0 && $key != '') delete_post_meta($package['ID'], $key);
            else if ($key != '')
                update_post_meta($package['ID'], $key, $limit - 1);

            $matched = (is_array(@maybe_unserialize($package['access'])) && is_user_logged_in())?array_intersect($current_user->roles, @maybe_unserialize($package['access'])):array();

            if (($id != '' && is_user_logged_in() && count($matched) < 1 && !@in_array('guest', $package['access'])) || (!is_user_logged_in() && !@in_array('guest', $package['access']) && $id != '')) {
                do_action("wpdm_download_permission_denied", $id);
                wpdm_download_data("permission-denied.txt", __("You don't have permission to download this file",'download-manager'));
                die();
            } else {

                if ($lock === 'locked' && $limit <= 0) {
                    do_action("wpdm_invalid_download_link", $id, $key);
                    if ($key != '')
                        wpdm_download_data("link-expired.txt", __("Download link is expired. Please get new download link.",'download-manager'));
                    else
                        wpdm_download_data("invalid-link.txt", __("Download link is expired or not valid. Please get new download link.",'download-manager'));
                    die();
                } else
                    if ($package['ID'] > 0) {
                        if((int)$package['quota'] == 0 || $package['quota'] > $package['download_count'])
                            include(WPDM_BASE_DIR . "wpdm-start-download.php");
                        else
                            wpdm_download_data("stock-limit-reached.txt", __("Stock Limit Reached", 'wpdmpro'));

                    }

            }
        } else
            wpdm_notice(__("Invalid download link.",'download-manager'));
    }


    /**
     * @usage Add with main RSS feed
     * @param $query
     * @return mixed
     */
    function rssFeed($query) {
        if ( isset($query['feed'])  && !isset($query['post_type']) &&  get_option('__wpdm_rss_feed_main', 0) == 1 ){
            $query['post_type'] = array('post','wpdmpro');
        }
        return $query;
    }

    /**
     * @usage Schedule custom ping
     * @param $post_id
     */
    function customPings($post_id){
        wp_schedule_single_event(time(), 'do_pings', array($post_id));
    }

    /**
     * @usage Allow access to server file browser for selected user roles
     */
    function sfbAccess(){

        global $wp_roles;

        $roleids = is_array($wp_roles->roles)?array_keys($wp_roles->roles):array();
        $roles = get_option('_wpdm_file_browser_access',array('administrator'));
        $naroles = array_diff($roleids, $roles);

        foreach($roles as $role) {
            $role = get_role($role);
            if(is_object($role))
                $role->add_cap('access_server_browser');
        }

        if(is_array($naroles)) {
            foreach ($naroles as $role) {
                $role = get_role($role);
                $role->remove_cap('access_server_browser');

            }
        }

    }

    /**
     * @usage Allow front-end users to access their own files only
     * @param $query_params
     * @return string
     */
    function usersMediaQuery( $query_params ){
        global $current_user;

        if(current_user_can('edit_posts')) return $query_params;

        if( is_user_logged_in() ){
            $query_params['author'] = $current_user->ID;
        }
        return $query_params;
    }

    /**
     * @usage Add packages wth tag query
     * @param $query
     * @return mixed
     */
    function queryTag($query)
    {
        if (is_tag() && $query->is_main_query()) {
            $post_type = get_query_var('post_type');
            if (!is_array($post_type))
                $post_type = array('post', 'wpdmpro');
            else
                $post_type = array_merge($post_type, array('post', 'wpdmpro'));
            $query->set('post_type', $post_type);
        }
        return $query;
    }

    /**
     * @usage Add generator tag
     */
    function addGenerator(){
        echo '<meta name="generator" content="WordPress Download Manager '.WPDM_Version.'" />'."\r\n";
    }

    function oEmbed($content){
        if(function_exists('wpdmpp_effective_price') && wpdmpp_effective_price(get_the_ID()) > 0)
        $template = '[excerpt_200]<br/><span class="oefooter">'.__('Price','download-manager').': '.wpdmpp_currency_sign().wpdmpp_effective_price(get_the_ID()).'</span><span class="oefooter">[download_count] Downloads</span><span class="oefooter"><a href="[page_url]" target="_parent">&#x1f4b3; Buy Now</a></span><style>.oefooter{ border: 1px solid #dddddd;padding: 5px 15px;font-size: 8pt;display: inline-block;margin-top: 3px;background: #f5f5f5; margin-right: 5px; } .oefooter a{ color: #3B88C3; font-weight: bold; } </style>';
        else
        $template = '[excerpt_200]<br/><span class="oefooter">[create_date]</span><span class="oefooter">[download_count] Downloads</span><span class="oefooter">&#x2b07; [download_link]</span><style>.oefooter{ border: 1px solid #dddddd;padding: 5px 15px;font-size: 8pt;display: inline-block;margin-top: 3px;background: #f5f5f5; margin-right: 5px; } .oefooter a{ color: #3B88C3; font-weight: bold; } </style>';
        return \WPDM\Package::fetchTemplate($template, get_the_ID());
    }

    function clearStats(){
        if(!current_user_can('manage_options')) die('error');
        global $wpdb;
        $wpdb->query('truncate table '.$wpdb->prefix.'ahm_download_stats');
        die('ok');
    }

    function wpdmIframe(){
        if(isset($_REQUEST['__wpdmlo'])){
            include wpdm_tpl_path("lock-options-iframe.php");
            die();
        }
    }

    /**
     * Empty cache dir
     */
    function clearCache(){
        if(!current_user_can('manage_options')) return;
        \WPDM\libs\FileSystem::deleteFiles(WPDM_CACHE_DIR, false);
        die('ok');
    }





}
