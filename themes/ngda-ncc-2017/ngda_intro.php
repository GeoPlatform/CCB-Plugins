<div class="banner section--linked">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                  <!-- WYSIWYG Text area. See functions.php -> text_editor_setting() for details
                  Current area modeled off of Geoplatform styling -->
                          <?php echo get_theme_mod( 'text_editor_setting' ); ?>
                  <div class="row">
                    <br />
                    <?php if (true === get_theme_mod('call2action_button')) { ?>
                      <div class="text-centered">
                            <a href="<?php echo get_theme_mod('call2action_url')?>" class="btn btn-lg btn-white-outline"><?php echo get_theme_mod('call2action_text')?></a>
                      </div>
                    <?php } ?>

                  </div><!--#col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12-->
                </div><!--#content-->
            </div><!--#row-->
        </div><!--#container-->
        <div style="margin-right:30px;">
          <ul role="menu" class="header__menu" style="color:white;">


        <?php wp_nav_menu( array(
          'theme_location' => 'community-links',
          'menu_class' => 'text-white nav navbar-nav navbar-right',
          'menu_class'=> 'header__menu',
          'items_wrap' => '%3$s',
          'container' => 'li'
         ) ); ?>
       </ul>
       <br />
     </div><!--#margin-right:30px-->
        <div class="footing">
            <div class="line-cap lightened"></div>
            <div class="line lightened"></div>
        </div><!--#footing-->
    </div><!--#content-->
</div><!--#banner section-linked-->
