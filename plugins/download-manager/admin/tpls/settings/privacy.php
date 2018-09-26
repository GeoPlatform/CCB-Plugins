<?php
/**
 * Date: 9/28/16
 * Time: 9:26 PM
 */
if(!defined('ABSPATH')) die('!');
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo __( "Privacy Settings" , "download-manager" ); ?></div>
    <div class="panel-body">
        <div class="form-group">
            <input type="hidden" value="0" name="__wpdm_noip" />
            <label><input style="margin: 0 10px 0 0" type="checkbox" <?php checked(get_option('__wpdm_noip'),1); ?> value="1" name="__wpdm_noip"><?php _e('Do not store visitor\'s IP','wpdmpro'); ?></label><br/>
            <em><?php _e('Check this option if you do not want to store visitors IPs','wpdmpro'); ?></em>
        </div>

        <div class="form-group">
            <input type="hidden" value="0" name="__wpdm_delstats_on_udel" />
            <label><input style="margin: 0 10px 0 0" type="checkbox" <?php checked(get_option('__wpdm_delstats_on_udel'),1); ?> value="1" name="__wpdm_delstats_on_udel"><?php _e('Delete download history when users close accounts','wpdmpro'); ?></label><br/>
            <em><?php _e('If any user is deleted or close his/her own account, delete their download history too','wpdmpro'); ?></em>
        </div>

        <?php
        do_action("wpdm_privacy_settings_option");
        ?>

    </div>
</div>

<?php
do_action("wpdm_privacy_settings_panel");
?>

<div class="panel panel-default">
    <div class="panel-heading"><?php _e('Cache & Stats','wpdmpro'); ?></div>
    <div class="panel-body">



            <button type="button" id="clearCache" style="width: 250px" class="btn btn-success btn-lg"><?php _e('Empty Cache Dir','wpdmpro'); ?></button>
            <button type="button" id="clearStats" style="width: 250px" class="btn btn-danger btn-lg"><?php _e('Delete All Stats Data','wpdmpro'); ?></button>





    </div>
</div>
<script>
    jQuery(function($) {
        $('#clearCache').on('click', function () {
            $(this).html('<i class="fa fa-sync fa-spin"></i>');
            $.get(ajaxurl + '?action=clear_cache', function (res) {
                $('#clearCache').html('<i class="fa fa-check-circle"></i>')
            });
            return false;
        });
        $('#clearStats').on('click', function () {
            if (!confirm('Are you sure?')) return false;
            $(this).html('<i class="fa fa-sync fa-spin"></i>');
            $.get(ajaxurl + '?action=clear_stats', function (res) {
                $('#clearStats').html('<i class="fa fa-check-circle"></i>')
            });
            return false;
        });

    });
</script>