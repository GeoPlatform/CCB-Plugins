<div id="lock-options"  class="tab-pane">
    <?php echo __('You can use one or more of following methods to lock your package download:','download-manager'); ?>
    <br/>
    <br/>
    <div class="wpdm-accordion w3eden">
        <!-- Terms Lock -->
        <div class="panel panel-default">
            <h3 class="panel-heading"><label><input type="checkbox" class="wpdmlock" rel='terms_lock' name="file[terms_lock]" <?php if(get_post_meta($post->ID,'__wpdm_terms_lock', true)=='1') echo "checked=checked"; ?> value="1"><?php echo __('Must Agree with Terms','download-manager'); ?></label></h3>
            <div  id="terms_lock" class="fwpdmlock panel-body" <?php if(get_post_meta($post->ID,'__wpdm_terms_lock', true)!='1') echo "style='display:none'"; ?> >

                <label for="ttl_z"><?php echo __('Terms Title:','download-manager'); ?></label>
                <input type="text" class="form-control" name="file[terms_title]" value="<?php echo esc_html(get_post_meta($post->ID,'__wpdm_terms_title', true)); ?>" />
                <label for="tos_z"><?php echo __('Terms and Conditions:','download-manager'); ?></label>
                <textarea class="form-control" type="text" name="file[terms_conditions]" id="tc_z"><?php echo esc_html(get_post_meta($post->ID,'__wpdm_terms_conditions', true)); ?></textarea>
                <label for="tcl_z"><?php echo __('Terms Checkbox Label:','download-manager'); ?></label>
                <input type="text" class="form-control" name="file[terms_check_label]" value="<?php echo esc_html(get_post_meta($post->ID,'__wpdm_terms_check_label', true)); ?>" />


            </div>
        </div>
        <!-- Password Lock -->
        <div class="panel panel-default">
        <h3 class="panel-heading"><label><input type="checkbox" class="wpdmlock" rel='password' name="file[password_lock]" <?php if(get_post_meta($post->ID,'__wpdm_password_lock', true)=='1') echo "checked=checked"; ?> value="1"><?php echo __('Enable Password Lock','download-manager'); ?></label></h3>
        <div  id="password" class="fwpdmlock panel-body" <?php if(get_post_meta($post->ID,'__wpdm_password_lock', true)!='1') echo "style='display:none'"; ?> >

            <label for="pps_z"><?php echo __('Password:','download-manager'); ?></label>

                        <input class="form-control" type="text" name="file[password]" id="pps_z" value="<?php echo get_post_meta($post->ID,'__wpdm_password', true); ?>" />
            <em class="note"><small><?php _e('If you want to use multiple passwords, keep each one inside [], like [123][456][789]', 'download-manager'); ?></small></em>


        </div>
        </div>


        <!-- Captcha Lock -->
        <div class="panel panel-default">
            <h3 class="panel-heading"><label><input type="checkbox" rel="captcha" class="wpdmlock" name="file[captcha_lock]" <?php if(get_post_meta($post->ID,'__wpdm_captcha_lock', true)=='1') echo "checked=checked"; ?> value="1"><?php echo __('Enable Captcha Lock','download-manager'); ?></label></h3>
            <div id="captcha" class="frm fwpdmlock panel-body"  <?php if(get_post_meta($post->ID,'__wpdm_captcha_lock', true)!='1') echo "style='display:none'"; ?> >

                <a href="edit.php?post_type=wpdmpro&page=settings"><?php if(!get_option('_wpdm_recaptcha_site_key') || !get_option('_wpdm_recaptcha_secret_key')) _e('Please configure reCAPTCHA','download-manager'); ?></a>
                <?php _e('Users will be asked for reCAPTCHA verification before download.','download-manager'); ?>

            </div>
        </div>



        <?php do_action('wpdm_download_lock_option',$post); ?>
    </div>
    <div class="clear"></div>
</div>