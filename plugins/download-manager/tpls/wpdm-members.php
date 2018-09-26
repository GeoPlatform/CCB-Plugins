<?php
if (!defined('ABSPATH')) die();
//Template Name: Members

    get_header();
    have_posts();
    the_post();
    global $wp_query, $current_user;
    
    $section = $wp_query->query_vars['minimaxtask'];  
    
?>
   
    <?php  
           
       if(!is_user_logged_in()&&$section!='new-password'){
          /* if($wp_query->query_vars['minimaxtask']=='register')
           include("reg-form.php");
           else if($wp_query->query_vars['minimaxtask']=='forgotpass')
           include("remind-password.php");
           else */
           include("be-member.php");
       }
       else {
   
    $section = str_replace("-","",$wp_query->query_vars['minimaxtask']);
    $section = $section?$section:'dashboard';
    ${"active_$section"} = "selected";
   
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="wpmp-members">

<?php
if(get_user_meta($current_user->ID, 'wpmpsstatus')!='active'&&$wp_query->query_vars['minimaxtask']=='my-themes') $section = 'dashboard';
$section = $section?$section:'dashboard';
include(dirname(__FILE__)."/members/$section.php") ;
?>
</div>  

</div>
</div>

<?php           
           
           
       }
        
    
    ?>    
     <div style="clear: both;"></div>

  <?php get_footer(); ?>