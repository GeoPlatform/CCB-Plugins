<?php
if (!defined('ABSPATH')) die();
global $current_user, $wpdb;
$user = get_userdata($current_user->ID);


?>

    <div id="edit-profile-form" class="w3eden">

        <?php if(isset($_SESSION['member_error'])){ ?>
            <div class="alert alert-danger" data-title="Save Failed!"><?php echo implode('<br/>',$_SESSION['member_error']); unset($_SESSION['member_error']); ?></div>
        <?php } ?>
        <?php if(isset($_SESSION['member_success'])){ ?>
            <div class="alert alert-success" data-title="Done!"><?php echo $_SESSION['member_success']; unset($_SESSION['member_success']); ?></div>
        <?php } ?>


        <form method="post" id="edit_profile" name="contact_form" action="" class="form">
            <div class="panel panel-default dashboard-panel">
            <div class="panel-heading"><?php _e('Basic Profile','download-manager'); ?></div>
                <div class="panel-body">
            <div class="row">
                <div class="col-md-6"><label for="name">Display name: </label><input type="text" class="required form-control" value="<?php echo $user->display_name;?>" name="wpdm_profile[display_name]" id="name"></div>
                <div class="col-md-6"><label for="payment_account">PayPal Email: </label><input type="text" value="<?php echo get_user_meta($user->ID,'payment_account',true); ?>" class="form-control" name="payment_account" id="payment_account"> </div>

                <div class="col-md-6"><label for="username">Username:</label><input type="text" class="required form-control" value="<?php echo $user->user_login;?>" id="username" readonly="readonly"></div>
                <div class="col-md-6"><label for="email">Email:</label><input type="text" class="required form-control" value="<?php echo $user->user_email;?>" id="email" readonly="readonly"></div>

                <div class="col-md-6"><label for="new_pass">New Password: </label><input placeholder="Use nothing if you don't want to change old password" type="password" class="form-control" value="" name="password" id="new_pass"> </div>
                <div class="col-md-6"><label for="re_new_pass">Re-type New Password: </label><input type="password" value="" class="form-control" name="cpassword" id="re_new_pass"> </div>


                <?php do_action('wpdm_update_profile_filed_html', $user); ?>


                <div class="col-md-12 clear"><label for="message">Description:</label><textarea class="text form-control" cols="40" rows="8" name="wpdm_profile[description]" id="message"><?php echo htmlspecialchars(stripslashes($current_user->description));?></textarea></div>


            </div>
            </div>
            </div>

            <?php do_action("wpdm_edit_profile_form"); ?>

            <div class="row">
                <div class="col-md-12 clear"><button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-floppy-o"></i> &nbsp; Save Changes</button></div>
            </div>


        </form>
    </div>
