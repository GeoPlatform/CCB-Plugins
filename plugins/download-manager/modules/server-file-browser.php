<?php

function wpdm_dir_tree(){
    global $current_user;
    $root = '';
    if(!is_user_logged_in()) return;
    if (!isset($_GET['task']) || $_GET['task'] != 'wpdm_dir_tree') return;
    if(!current_user_can('access_server_browser') && !current_user_can('manage_options')) { echo "<ul><li>".__('Not Allowed!','download-manager')."</li></ul>"; die(); }
    $_POST['dir'] = urldecode($_POST['dir']);
    if (file_exists($_POST['dir'])) {
        $files = scandir($_POST['dir']);
        natcasesort($files);
        if (count($files) > 2) { /* The 2 accounts for . and .. */
            echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
            // All dirs
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && file_exists($root . $_POST['dir'] . $file) && is_dir($root . $_POST['dir'] . $file)) {
                    echo "<li class=\"directory collapsed\"><a id=\"" . uniqid() . "\" href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
                }
            }
            // All files
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && file_exists($root . $_POST['dir'] . $file) && !is_dir($root . $_POST['dir'] . $file)) {
                    $ext = preg_replace('/^.*\./', '', $file);
                    echo "<li class=\"file ext_$ext\"><a id=\"" . uniqid() . "\" href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
                }
            }
            echo "</ul>";
        }
    }
    die();

}

function wpdm_file_browser(){
    //if($_GET['task']!='wpdm_file_browser') return;
    ?>
    <script type="text/javascript" src="<?php echo plugins_url().'/download-manager/assets/js/jqueryFileTree.js';?>"></script>
    <link rel="stylesheet" href="<?php echo plugins_url().'/download-manager/assets/css/jqueryFileTree.css';?>" />
    <style type="text/css">.jqueryFileTree li{line-height: 20px;}</style>
    <!--<div class="wrap">
    <div class="icon32" id="icon-categories"><br></div>
    <h2>Browse Files</h2>-->
    <div id="tree" style="height: 200px;overflow:auto"></div>
    <script language="JavaScript">
    <!--
      jQuery( function() {
            jQuery('#tree').fileTree({
                root: '<?php echo get_option('_wpdm_file_browser_root',$_SERVER['DOCUMENT_ROOT']); ?>/',
                script: 'admin.php?task=wpdm_dir_tree',
                expandSpeed: 1000,
                collapseSpeed: 1000,
                multiFolder: false
            }, function(file, id) {
                var sfilename = file.split('/');
                var filename = sfilename[sfilename.length-1];
                if(confirm('Add this file?')){
                    var d = new Date();
                    var ID = d.getTime();

                    jQuery('#wpdmfile').val(file);
                    jQuery('#cfl').html('<div><strong>'+file+'</strong>').slideDown();


                }
                //jQuery('#serverfiles').append('<li><label><input checked=checked type="checkbox" value="'+file+'" name="imports[]" class="role"> &nbsp; '+filename+'</label></li>');                
            });

      });
    //-->
    </script>
    <!--</div> -->
    <?php
   // die();
}

function wpmp_file_browser_metabox(){
    ?>
    
    <div class="postbox " id="action">
<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span><?php echo __('Add file(s) from server','download-manager'); ?></span></h3>
<div class="inside" style="height: 200px;overflow: auto;">
      
<?php wpdm_file_browser(); ?>

<ul id="serverfiles">



 


</ul>   
 <div class="clear"></div>
</div>
</div>
    
    <?php
}

if(is_admin()){
     
    //add_action("init","wpdm_file_browser");
    add_action("init","wpdm_dir_tree");
    add_action("add_new_file_sidebar","wpmp_file_browser_metabox");
}


