<?php
if (!defined('ABSPATH')) die();
    global $current_user, $wpdb;
    $user = get_userdata($current_user->ID);

     
?>
 

<div class="w3eden">
<div id="edit-profile-form" style="margin-top: 20px">

<?php if(isset($_SESSION['member_error'])){ ?>
<div class="alert alert-error"><b>Save Failed!</b><br/><?php echo implode('<br/>',$_SESSION['member_error']); unset($_SESSION['member_error']); ?></div>
<?php } ?>
<?php if(isset($_SESSION['member_success'])){ ?>
<div class="alert alert-success"><b>Done!</b><br/><?php echo $_SESSION['member_success']; unset($_SESSION['member_success']); ?></div>
<?php } ?>


<form method="post" id="edit_profile" name="contact_form" action="" class="form">
<div class="row">
                                                <div class="col-md-6"><label for="name">Display name: </label><input type="text" class="required form-control input-lg" value="<?php echo $user->display_name;?>" name="profile[display_name]" id="name"></div>
                                                <div class="col-md-6"><label for="payment_account">PayPal Email: </label><input type="text" value="<?php echo get_user_meta($user->ID,'payment_account',true); ?>" class="form-control input-lg" name="payment_account" id="payment_account"> </div>

                                                <div class="col-md-6"><label for="username">Username:</label><input type="text" class="required form-control input-lg" value="<?php echo $user->user_login;?>" id="username" readonly="readonly"></div>
                                                <div class="col-md-6"><label for="email">Email:</label><input type="text" class="required form-control input-lg" value="<?php echo $user->user_email;?>" id="email" readonly="readonly"></div>

                                                <div class="col-md-6"><label for="new_pass">New Password: </label><input placeholder="Use nothing if you don't want to change old password" type="password" class="form-control input-lg" value="" name="password" id="new_pass"> </div>
                                                <div class="col-md-6"><label for="re_new_pass">Re-type New Password: </label><input type="password" value="" class="form-control input-lg" name="cpassword" id="re_new_pass"> </div>

                                                
                                                <?php do_action('wpdm_update_profile_filed_html', $user); ?>

                                                
                                                <div class="col-md-12 clear"><label for="message">Description:</label><textarea class="text form-control input-lg" cols="40" rows="8" name="profile[description]" id="message"><?php echo htmlspecialchars(stripslashes($current_user->description));?></textarea></div>

<div class="col-md-12 clear"><Br/><button type="submit" class="btn btn-large btn-primary"><i class="icon-ok icon-white"></i> Save Changes</button></div>
</div>
</form>
</div>
</div>

<style>#edit-profile-form .col-md-6 {  margin-bottom: 20px;  }</style>