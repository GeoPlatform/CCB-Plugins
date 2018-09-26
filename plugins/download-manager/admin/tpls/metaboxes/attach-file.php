<div id="ftabs" class="w3eden">
<input type="hidden" name="file[files][]" value="<?php $afiles = maybe_unserialize(get_post_meta(get_the_ID(), "__wpdm_files", true)); echo is_array($afiles)&&isset($afiles[0])?$afiles[0]:''; ?>" id="wpdmfile" />
<div style="padding: 10px;">
<div class="cfile" id="cfl" style="padding: 10px;border:2px solid #ddd;background: #ffffff">
    <?php
    $filesize = "<em style='color: darkred'>( ".__("attached file is missing/deleted",'download-manager')." )</em>";
    $afile = is_array($afiles)&&isset($afiles[0])?$afiles[0]:'';
    $afile = trim($afile);
    if($afile !=''){

        if(strpos($afile, "://")){
            $fparts = parse_url($afile);
            $filesize = "<span class='w3eden'><span class='text-primary'><i class='fa fa-link'></i> {$fparts['host']}</span></span>";
        }
        else {
            if (file_exists(UPLOAD_DIR . '/' . $afile))
                $filesize = number_format(filesize(UPLOAD_DIR . '/' . $afile) / 1025, 2) . " KB";
            else if (file_exists($afile))
                $filesize = number_format(filesize($afile) / 1025, 2) . " KB";
        }

        if(strpos($afile, "#")) {
            $afile = explode("#", $afile);
            $afile = $afile[1];
        }

        ?>

        <div style="position: relative;"><strong><?php echo  basename($afile); ?></strong><br/><?php echo $filesize; ?> <a href='#' id="dcf" title="Delete Current File" style="position: absolute;right:0;top:0;height:32px;"><img src="<?php echo plugins_url('/download-manager/assets/images/error.png'); ?>" /></a></div>
    <?php } else echo "<span style='font-weight:bold;color:#ddd'>". __('No file uploaded yet!', 'download-manager')."</span>"; ?>
    <div style="clear: both;"></div>
</div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#upload" data-toggle="tab"><?php _e('Upload', 'download-manager'); ?></a></li>
    <?php  if(current_user_can('access_server_browser')){ ?>
    <li><a href="#browse" data-toggle="tab"><?php _e('Browse', 'download-manager'); ?></a></li>
    <?php } ?>
    <li><a href="#remote" role="tab" data-toggle="tab"><?php echo __('URL','wpdmpro'); ?></a></li>
</ul>
    <div class="tab-content">
    <div class="tab-pane active" id="upload">
        <div id="plupload-upload-ui" class="hide-if-no-js">
            <div id="drag-drop-area">
                <div class="drag-drop-inside" style="margin-top: 40px">
                    <p class="drag-drop-info" style="letter-spacing: 1px;font-size: 10pt"><?php _e('Drop files here'); ?><p>
                    <p>&mdash; <?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?> &mdash;</p>
                    <p class="drag-drop-buttons">
                        <button id="plupload-browse-button" type="button" class="btn btn-sm btn-default"><i class="fa fa-folder-open color-green"></i> <?php esc_attr_e('Select Files'); ?></button><br/>
                        <small style="margin-top: 15px;display: block">[ Max: <?php echo get_option('__wpdm_chunk_upload',0) == 1?'No Limit':(int)(wp_max_upload_size()/1048576).' MB'; ?> ]</small>
                    </p>
                </div>
            </div>
        </div>

        <?php

        $plupload_init = array(
            'runtimes'            => 'html5,silverlight,flash,html4',
            'browse_button'       => 'plupload-browse-button',
            'container'           => 'plupload-upload-ui',
            'drop_element'        => 'drag-drop-area',
            'file_data_name'      => 'package_file',
            'multiple_queues'     => true,
            'url'                 => admin_url('admin-ajax.php'),
            'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters'             => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
            'multipart'           => true,
            'urlstream_upload'    => true,
            // additional post data to send to our ajax hook
            'multipart_params'    => array(
                '_ajax_nonce' => wp_create_nonce('wpdm_admin_upload_file'),
                'action'      => 'wpdm_admin_upload_file',            // the ajax action name
            ),
        );

        if(get_option('__wpdm_chunk_upload',0) == 1){
            $plupload_init['chunk_size'] = get_option('__wpdm_chunk_size', 1024).'kb';
            $plupload_init['max_retries'] = 3;
        } else
            $plupload_init['max_file_size'] = wp_max_upload_size().'b';

        // we should probably not apply this filter, plugins may expect wp's media uploader...
        $plupload_init = apply_filters('plupload_init', $plupload_init); ?>

        <script type="text/javascript">

            jQuery(document).ready(function($){

                // create the uploader and pass the config from above
                var uploader = new plupload.Uploader(<?php echo json_encode($plupload_init); ?>);

                // checks if browser supports drag and drop upload, makes some css adjustments if necessary
                uploader.bind('Init', function(up){
                    var uploaddiv = jQuery('#plupload-upload-ui');

                    if(up.features.dragdrop){
                        uploaddiv.addClass('drag-drop');
                        jQuery('#drag-drop-area')
                            .bind('dragover.wp-uploader', function(){ uploaddiv.addClass('drag-over'); })
                            .bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv.removeClass('drag-over'); });

                    }else{
                        uploaddiv.removeClass('drag-drop');
                        jQuery('#drag-drop-area').unbind('.wp-uploader');
                    }
                });

                uploader.init();

                // a file was added in the queue
                uploader.bind('FilesAdded', function(up, files){
                    //var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);



                    plupload.each(files, function(file){
                        jQuery('#filelist').append(
                            '<div class="file" id="' + file.id + '"><b>' +

                            file.name.replace(/</ig, "&lt;") + '</b> (<span>' + plupload.formatSize(0) + '</span>/' + plupload.formatSize(file.size) + ') ' +
                            '<div class="progress progress-success progress-striped active"><div class="bar fileprogress"></div></div></div>');
                    });

                    up.refresh();
                    up.start();
                });

                uploader.bind('UploadProgress', function(up, file) {

                    jQuery('#' + file.id + " .fileprogress").width(file.percent + "%");
                    jQuery('#' + file.id + " span").html(plupload.formatSize(parseInt(file.size * file.percent / 100)));
                });


                // a file was uploaded
                uploader.bind('FileUploaded', function(up, file, response) {

                    jQuery('#' + file.id ).remove();
                    var d = new Date();
                    var ID = d.getTime();
                    response = response.response;
                    if(response == -3)
                        jQuery('#cfl').html('<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> &nbsp; <?php _e('Invalid File Type!','download-manager');?></span>');
                    else if(response == -2)
                        jQuery('#cfl').html('<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> &nbsp; <?php _e('Unauthorized Access!','download-manager');?></span>');
                    else {
                        var data = response.split("|||");
                        jQuery('#wpdmfile').val(data[1]);
                        jQuery('#cfl').html('<div><strong>' + data[1] + '</strong> <a href="#" id="dcf" title="<?php _e('Delete Current File', 'download-manager');?>" style="position: absolute;right:0;top:0;height:32px;"><img src="<?php echo plugins_url('/download-manager/assets/images/error.png'); ?>" /></a>').slideDown();
                    }
                });
            });

        </script>
        <div id="filelist"></div>

        <div class="clear"></div>
    </div>


<div id="browse" class="tab-pane">
    <?php  if(current_user_can('access_server_browser')) wpdm_file_browser(); ?>
</div>
        <div class="tab-pane" id="remote" class="w3eden">
            <div class="input-group"><input type="url" id="rurl" class="form-control" placeholder="Insert URL"><span class="input-group-btn"><button type="button" id="rmta" class="btn btn-default"><i class="fa fa-plus-circle"></i></button></span></div>
        </div>
</div>
</div>

<script>
jQuery(function(){
        //jQuery( "#ftabs" ).tabs();

        jQuery('#rmta').click(function(){
        var ID = 'file_' + parseInt(Math.random()*1000000);
        var file = jQuery('#rurl').val();
        var filename = file;
            jQuery('#rurl').val('');
        if(/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(file)==false){
                alert("Invalid url");
            return false;
            }

            jQuery('#wpdmfile').val(file);
            jQuery('#cfl').html('<div><strong>'+file+'</strong>').slideDown();


    });



    jQuery('body').on('click', '#dcf', function(){
        if(!confirm('<?php _e('Are you sure?','download-manager'); ?>')) return false;
        jQuery('#wpdmfile').val('');
        jQuery('#cfl').html('<?php _e('<div class="w3eden"><div class="text-danger"><i class="fa fa-check-circle"></i> Removed!</div></div>','download-manager'); ?>');
    });




});
 
</script>
<?php

do_action("wpdm_attach_file_metabox");