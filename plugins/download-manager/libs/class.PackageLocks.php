<?php

namespace WPDM\libs;

global $gp1c, $tbc;


class PackageLocks
{

    public function __construct(){
        global $post;
        //if(has_shortcode($post->post_content, "[wpdm_package]"))
        add_action('wp_enqueue_scripts', array($this, 'Enqueue'));
    }

    function Enqueue(){
       // wp_enqueue_script('wpdm-fb', 'http://connect.facebook.net/en_US/all.js?ver=3.1.3#xfbml=1');
    }


    public static function askPassword($package){
        ob_start();
        $unqid = uniqid();
        ?>

        <div class="panel panel-default" style="margin: 0">
            <div class="panel-heading">
                <?php _e('Enter Correct Password to Download','download-manager'); ?>
            </div>
            <div class="panel-body" id="wpdmdlp_<?php echo  $unqid . '_' . $package['ID']; ?>">
                <div id="msg_<?php echo $package['ID']; ?>" style="display:none;"><button type="button" class="btn btn-lg btn-info btn-block" disabled="disabled"><?php _e('Processing...','download-manager'); ?></button></div>
                <form id="wpdmdlf_<?php echo $unqid . '_' . $package['ID']; ?>" method=post action="<?php echo home_url('/'); ?>" style="margin-bottom:0px;">
                    <input type=hidden name="__wpdm_ID" value="<?php echo $package['ID']; ?>" />
                    <input type=hidden name="dataType" value="json" />
                    <input type=hidden name="execute" value="wpdm_getlink" />
                    <input type=hidden name="action" value="wpdm_ajax_call" />
                    <div class="input-group input-group-lg">
                        <input type="password"  class="form-control" placeholder="<?php _e('Enter Password','download-manager'); ?>" size="10" id="password_<?php echo $unqid . '_' . $package['ID']; ?>" name="password" />
                        <span class="input-group-btn"><input style="border-top-right-radius: 3px;border-bottom-right-radius: 3px" id="wpdm_submit_<?php echo $unqid . '_' . $package['ID']; ?>" class="wpdm_submit btn btn-info" type="submit" value="<?php _e('Submit','download-manager'); ?>" /></span>
                    </div>

                </form>

                <script type="text/javascript">
                    jQuery("#wpdmdlf_<?php echo $unqid . '_' . $package['ID']; ?>").submit(function(){
                        var ctz = new Date().getMilliseconds();
                        jQuery("#msg_<?php echo  $package['ID']; ?>").html('<button type="button" class="btn btn-lg btn-info btn-block" disabled="disabled"><?php _e('Processing...','download-manager'); ?></div>').show();
                        jQuery("#wpdmdlf_<?php echo  $unqid . '_' . $package['ID']; ?>").hide();
                        jQuery(this).removeClass("wpdm_submit").addClass("wpdm_submit_wait");
                        jQuery(this).ajaxSubmit({
                            url: "<?php echo home_url('/?nocache='); ?>" + ctz,
                            success: function(res){

                                jQuery("#wpdmdlf_<?php echo  $unqid . '_' . $package['ID']; ?>").hide();
                                jQuery("#msg_<?php echo  $package['ID']; ?>").html("verifying...").css("cursor","pointer").show().click(function(){ jQuery(this).hide();jQuery("#wpdmdlf_<?php echo  $unqid . '_' . $package['ID']; ?>").show(); });
                                if(res.downloadurl!=""&&res.downloadurl!=undefined) {
                                    window.open(res.downloadurl);
                                    jQuery("#wpdmdlf_<?php echo  $unqid . '_' . $package['ID']; ?>").html("<a style='color:#ffffff !important' class='wpdm-download-button btn btn-success btn-lg btn-block' href='"+res.downloadurl+"'><?php _e('Download','download-manager'); ?></a>");
                                    jQuery("#msg_<?php echo  $package['ID']; ?>").hide();
                                    jQuery("#wpdmdlf_<?php echo  $unqid . '_' . $package['ID']; ?>").show();
                                } else {
                                    jQuery("#msg_<?php echo $package['ID']; ?>").html("<div class='btn btn-lg btn-light btn-block'>"+res.error+"</div>");
                                }
                            }
                        });
                        return false;
                    });
                </script>
            </div>
        </div>

        <?php
        $data = ob_get_clean();
        return $data;
    }


    public static function reCaptchaLock($package, $buttononly = false){
        ob_start();
        //wp_enqueue_script('wpdm-recaptcha', 'https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit');
        $force = str_replace("=", "", base64_encode("unlocked|" . date("Ymdh")));
        ?>
        <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
        <div class="__wpdm_loadrecap" id="reCaptchaLock_<?php echo $package['ID']; ?>"></div>
        <div id="msg_<?php echo $package['ID']; ?>"></div>
        <script type="text/javascript">
            var ctz = new Date().getMilliseconds();
            var siteurl = "<?php echo home_url('/?nocache='); ?>"+ctz,force="<?php echo $force; ?>";
            var verifyCallback_<?php echo $package['ID']; ?> = function(response) {
                jQuery.post(siteurl,{__wpdm_ID:<?php echo $package['ID'];?>,dataType:'json',execute:'wpdm_getlink',force:force,social:'c',reCaptchaVerify:response,action:'wpdm_ajax_call'},function(res){
                    if(res.downloadurl!='' && res.downloadurl !== undefined && res !== undefined ) {
                    location.href=res.downloadurl;
                    jQuery('#reCaptchaLock_<?php echo $package['ID']; ?>').html('<a href="'+res.downloadurl+'" class="wpdm-download-button btn btn-success btn-lg btn-block"><?php _e('Download','download-manager'); ?></a>');
                    } else {
                        //alert(res.error);
                        jQuery('#reCaptchaLock_<?php echo $package['ID']; ?>').html('<div class="color-red">'+res.error+"</div>");
                    }
                });
            };
            if(onloadCallback == undefined) {
                var onloadCallback = function () {
                    jQuery('.__wpdm_loadrecap').each(function () {
                        grecaptcha.render('reCaptchaLock_<?php echo $package['ID']; ?>', {
                            'sitekey': '<?php echo get_option('_wpdm_recaptcha_site_key'); ?>',
                            'callback': verifyCallback_<?php echo $package['ID']; ?>,
                            'theme': 'light'
                        });
                    });
                };
            }



        </script>

        <?php
        return ob_get_clean();
    }



}
