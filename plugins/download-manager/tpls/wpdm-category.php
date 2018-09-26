<?php
if (!defined('ABSPATH')) die();
get_header(); the_post(); ?>
      <div class="container">   
        <br>  
        <div class="row">
         
         <div class="span9">  
           
         <div class="thumbnail single-post" style="padding: 5px;">
         <?php the_wpdm_thumbnail(array(688)); ?>          
         </div> 
          
         
         <h1 class="header-1 entry-title"><?php the_wpdm_title(); ?></h1>
         <div class="stripe"></div>         
         <div class="thumbnail">
         <div class="single-post">

        <?php the_wpdm_content(); ?>
        
         </div> 
         </div> 
         <?php //ab_show_author_box(); ?>
         
                  
         </div> 
         <div class="span3">
         <div class="box widget">        
         <h3>Package Info</h3>
         <div class="stripe"></div><div class="thumbnail widget_content"><div class="single-post">        
         <table width="100%" class="table table-striped nomargin">
         <tr><td>Version</td><td><?php echo wpdm_package_info('version'); ?></td></tr>
         <tr><td>Created On</td><td><?php echo wpdm_package_info('create_date'); ?></td></tr>
         <tr><td>Last Updated</td><td><?php echo wpdm_package_info('update_date'); ?></td></tr>
         <tr><td>Downloaded</td><td><?php echo wpdm_package_info('download_count'); ?> times</td></tr>
         <tr><td>Price</td><td><?php echo wpdm_package_info('price'); ?></td></tr>
         </table><br>
         
         <center>
         <?php echo (int)get_wpdm_meta(get_wpdm_ID(),'price')>=0?str_replace("wpdm-gh-button wpdm-gh-icon tag","btn btn-info",FetchTemplate("[addtocart_form]", $package)):str_replace("wpdm-gh-button wpdm-gh-icon arrowdown wpdm-gh-big","btn btn-success btn-large",$package['download_link']); ?>
         </center>
        </div></div></div>
        
         <?php dynamic_sidebar('Single Post'); ?>
         
         </div>
        
        </div>
         
      </div>    
 <?php get_footer(); ?>