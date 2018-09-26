<?php
namespace WPDM\admin;

class WordPressDownloadManagerAdmin
{

    function __construct()
    {
        new \WPDM\admin\menus\Welcome();
        new \WPDM\admin\menus\Packages();
        new \WPDM\admin\menus\Categories();
        new \WPDM\admin\menus\Addons();
        new \WPDM\admin\menus\Stats();
        new \WPDM\admin\menus\Settings();

        $this->Actions();
    }

    function Actions()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('admin_init', array($this, 'metaBoxes'), 0);
        add_action('init', array($this, 'registerScripts'), 0);
        add_action('wp_ajax_updatenow', array($this, 'updateNow'));
        add_action('admin_head', array($this, 'adminHead'));

        add_action('wp_ajax_updateaddon', array( $this, 'updateAddon' ));
        add_action('wp_ajax_installaddon', array( $this, 'installAddon' ));

        add_action('wp_dashboard_setup', array($this, 'addDashboardWidget'));

    }

    function registerScripts(){
        wp_register_script('wpdm-bootstrap', WPDM_BASE_URL . 'assets/bootstrap/js/bootstrap.min.js', array('jquery'));
        wp_register_style('wpdm-bootstrap', WPDM_BASE_URL . 'assets/bootstrap/css/bootstrap.min.css');
        wp_register_style('wpdm-font-awesome', WPDM_BASE_URL . 'assets/fontawesome/css/all.css');
        wp_register_style('wpdm-front', WPDM_BASE_URL . 'assets/css/front.css');
    }

    /**
     * Enqueue admin scripts & styles
     */
    function enqueueScripts($hook){


        if(get_post_type()=='wpdmpro' || wpdm_query_var('post_type') == 'wpdmpro' || $hook == 'index.php'){
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-form');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('jquery-ui-slider');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-timepicker', WPDM_BASE_URL.'assets/js/jquery-ui-timepicker-addon.js',array('jquery','jquery-ui-core','jquery-ui-datepicker','jquery-ui-slider') );

            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_media();

            wp_enqueue_script('jquery-choosen', plugins_url('/download-manager/assets/js/chosen.jquery.min.js'), array('jquery'));
            wp_enqueue_style('choosen-css', plugins_url('/download-manager/assets/css/chosen.css'));
            wp_enqueue_style('jqui-css', plugins_url('/download-manager/assets/jqui/theme/jquery-ui.css'));

            wp_enqueue_script('wpdm-admin', plugins_url('/download-manager/assets/js/wpdm-admin.js'), array('jquery'));
            wp_enqueue_style('wpdm-font-awesome' );
        }

        if(get_post_type()=='wpdmpro' || wpdm_query_var('post_type') == 'wpdmpro' || $hook == 'index.php'){
            wp_enqueue_script('wpdm-bootstrap' );
            wp_enqueue_style('wpdm-bootstrap' );
            //wp_enqueue_style('wpdm-bootstrap-theme', plugins_url('/download-manager/assets/bootstrap/css/bootstrap-theme.min.css'));
            wp_enqueue_style('wpdm-admin-styles', plugins_url('/download-manager/assets/css/admin-styles.css'));
        }

    }


    /**
     * @usage Single click add-on update
     */
    function updateAddon(){
        if(isset($_POST['updateurl']) && current_user_can(WPDM_ADMIN_CAP)){
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            $upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
            $downloadlink = $_POST['updateurl'].'&preact=login&user=' . get_option('__wpdm_suname') . '&pass=' . get_option('__wpdm_supass').'&__wpdmnocache='.uniqid();
            $update = new \stdClass();
            $plugininfo = wpdm_plugin_data($_POST['plugin']);
            deactivate_plugins($plugininfo['plugin_index_file'], true);
            delete_plugins(array($plugininfo['plugin_index_file']));
            $upgrader->install($downloadlink);
            if(file_exists(dirname(WPDM_BASE_DIR).'/'.$plugininfo['plugin_index_file']))
                activate_plugin($plugininfo['plugin_index_file']);
            die("Updated Successfully");
        } else {
            die("Only site admin is authorized to install add-on");
        }
    }

    /**
     * @usage Single click add-on install
     */
    function installAddon(){
        if(isset($_POST['updateurl']) && current_user_can(WPDM_ADMIN_CAP)){
            include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            $upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
            $downloadlink = $_POST['updateurl'].'&preact=login&user=' . get_option('__wpdm_suname') . '&pass=' . get_option('__wpdm_supass');
            $upgrader->install($downloadlink);
            $plugininfo = wpdm_plugin_data($_POST['plugin']);
            if(file_exists(dirname(WPDM_BASE_DIR).'/'.$plugininfo['plugin_index_file']))
                activate_plugin($plugininfo['plugin_index_file']);
            die("Installed Successfully");
        } else {
            die("Only site admin is authorized to install add-on");
        }
    }


    function adminHead(){
        remove_submenu_page( 'index.php', 'wpdm-welcome' );
        ?>
        <script type="text/javascript">

            var wpdmConfig = {
                siteURL: '<?php echo site_url(); ?>'
            };

            jQuery(function () {


                jQuery('#TB_closeWindowButton').click(function () {
                    tb_remove();
                });

            });
        </script>
        <?php
    }

    function widgetCallback(){
        include dirname(__FILE__).'/tpls/dashboard-widget.php';
    }

    function addDashboardWidget(){
        wp_add_dashboard_widget('wpdm_dashboard_widget', 'WordPress Download Manager', array($this, 'widgetCallback'));
        global $wp_meta_boxes;
        $side_dashboard = $wp_meta_boxes['dashboard']['side']['core'];
        $wpdm_widget = array('wpdm_dashboard_widget' => $wp_meta_boxes['dashboard']['normal']['core']['wpdm_dashboard_widget']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['wpdm_dashboard_widget']);
        $sorted_dashboard = array_merge($wpdm_widget, $side_dashboard);
        $wp_meta_boxes['dashboard']['side']['core'] = $sorted_dashboard;
    }

    function metaBoxes()
    {

        $meta_boxes = array(
            'wpdm-settings' => array('title' => __('Package Settings','download-manager'), 'callback' => array($this, 'packageSettings'), 'position' => 'normal', 'priority' => 'low'),
            'wpdm-upload-file' => array('title' => __('Attach File','download-manager'), 'callback' => array($this, 'uploadFiles'), 'position' => 'side', 'priority' => 'core'),
        );


        $meta_boxes = apply_filters("wpdm_meta_box", $meta_boxes);
        foreach ($meta_boxes as $id => $meta_box) {
            extract($meta_box);
            if(!isset($position)) $position = 'normal';
            if(!isset($priority)) $priority = 'core';
            add_meta_box($id, $title, $callback, 'wpdmpro', $position, $priority);
        }
    }

    function Files($post)
    {
        include(WPDM_BASE_DIR."admin/tpls/metaboxes/attached-files.php");
    }

    function packageSettings($post)
    {
        include(WPDM_BASE_DIR."admin/tpls/metaboxes/package-settings.php");
    }

    function uploadFiles($post)
    {
        include(WPDM_BASE_DIR."admin/tpls/metaboxes/attach-file.php");
    }


}