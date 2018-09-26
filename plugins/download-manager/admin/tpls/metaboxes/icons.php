


<div id="package-icons" class="tab-pane">

    <div class="w3eden"><input style="background: url(<?php echo get_post_meta($post->ID,'__wpdm_icon', true); ?>) no-repeat;background-size: 32px;padding-left: 40px" id="wpdmiconurl" placeholder="<?php _e('Icon URL','download-manager'); ?>" value="<?php echo get_post_meta($post->ID,'__wpdm_icon', true); ?>" type="text"  name="file[icon]"  class="form-control" ></div>
    <br clear="all" />
    <?php
    $path = WPDM_BASE_DIR."assets/file-type-icons/";
    $scan = scandir( $path );
    $k = 0;
    $fileinfo = array();
    foreach( $scan as $v )
    {
        if( $v=='.' or $v=='..' or is_dir($path.$v) ) continue;

        $fileinfo[$k]['file'] = 'download-manager/assets/file-type-icons/'.$v;
        $fileinfo[$k]['name'] = $v;
        $k++;
    }



    ?>
    <div id="w-icons">
        <?php
        $img = array('jpg','gif','jpeg','png', 'svg');
        foreach($fileinfo as $index=>$value): $tmpvar = explode(".",$value['file']); $ext = strtolower(end($tmpvar)); if(in_array($ext,$img)): ?>
            <label>
                <img class="wdmiconfile" id="<?php echo md5(plugins_url().'/'.$value['file']) ?>" src="<?php  echo plugins_url().'/'.$value['file'] ?>" alt="<?php echo $value['name'] ?>" style="padding:5px; margin:1px; float:left; border:#fff 2px solid;height: 32px;width:auto; " />
                </label>
        <?php endif; endforeach; ?>
    </div>
    <script type="text/javascript">
        //border:#CCCCCC 2px solid

        <?php if(isset($_GET['action'])&&$_GET['action']=='edit'){ ?>
        jQuery('#<?php echo md5(get_post_meta($post->ID,'__wpdm_icon', true)) ?>').addClass("iactive");
        <?php } ?>    
        jQuery('body').on('click', 'img.wdmiconfile',function(){
            jQuery('#wpdmiconurl').val(jQuery(this).attr('src'));
            jQuery('#wpdmiconurl').css('background-image','url('+jQuery(this).attr('src')+')');
            jQuery('img.wdmiconfile').removeClass('iactive');
            jQuery(this).addClass('iactive');



        });
        jQuery('#wpdmiconurl').on('change', function(){
            jQuery('#wpdmiconurl').css('background-image','url('+jQuery(this).val()+')');
        });




    </script>
    <style>

        .iactive{
            -moz-box-shadow:    inset 0 0 10px #5FAC4F;
            -webkit-box-shadow: inset 0 0 10px #5FAC4F;
            box-shadow:         inset 0 0 10px #5FAC4F;
            background: #D9FCD1;
        }
    </style>

    <div class="clear"></div>
</div>
 
 