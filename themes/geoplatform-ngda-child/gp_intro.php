<?php
/**
 * A template for the front page banner
 *
 * WYSIWYG banner area
 * @link https://wpshout.com/making-themes-more-wysiwyg-with-the-wordpress-customizer/
 * @link https://github.com/paulund/wordpress-theme-customizer-custom-controls/
 *
 * Sane defaults for theme mods
 * @link https://make.wordpress.org/themes/2014/07/09/using-sane-defaults-in-themes/
 *
 * @package GeoPlatform CCB
 *
 * @since 1.0.0
 */

//get theme mod defaults
$geop_ccb_theme_mods = geop_ccb_get_theme_mods();
?>
<div class="banner section--linked">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                  <!-- WYSIWYG Text area. See functions.php -> text_editor_setting() for details
                  Current area modeled off of Geoplatform styling -->
                <?php echo wp_kses_post(get_theme_mod( 'text_editor_setting', $geop_ccb_theme_mods['text_editor_setting'] ));?>
                  <div class="row">
                    <br />
                    <?php $geopccb_c2a_button = wp_kses_post(get_theme_mod( 'call2action_button_setting', $geop_ccb_theme_mods['call2action_button_setting'] ));
                          $geopccb_c2a_url = wp_kses_post(get_theme_mod( 'call2action_url_setting', $geop_ccb_theme_mods['call2action_url_setting'] ));
                          $geopccb_c2a_text = wp_kses_post(get_theme_mod( 'call2action_text_setting', $geop_ccb_theme_mods['call2action_text_setting'] ));
                    ?>
                    <?php if ($geopccb_c2a_button == true) { ?>
                      <div class="text-centered">
                            <a href="<?php echo esc_url($geopccb_c2a_url);?>" class="btn btn-lg btn-white-outline">
                              <?php echo esc_html($geopccb_c2a_text);?></a>
                      </div><!--#text-centered-->
                    <?php } ?>
                  </div><!--#row-->
                </div><!--#col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12-->
            </div><!--#row-->
        </div><!--#container-->
        <div style="margin-right:30px;">
          <ul role="menu" class="header__menu">


        <?php wp_nav_menu( array(
          'theme_location' => 'community-links',
          'menu_class' => 'text-white nav navbar-nav navbar-right',
          'menu_class'=> 'header__menu',
          'items_wrap' => '%3$s',
          'container' => 'li'
         ) ); ?>
          </ul>
          <br/>
        </div><!--#margin-right:30px-->


        <div class="footing">
            <div class="line-cap lightened"></div>
            <div class="line lightened"></div>
        </div><!--#footer-->
    </div><!--#content-->
</div><!--#banner section-linked-->
