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
        <div class="footing">
          <div class="line-cap lightened"></div>
          <div class="line lightened"></div>
        </div><!--#footer-->
    </div><!--#content-->

    <?php
    // Only displays the nav menu if it has content. This prevents the footing elements from goofing up.
    if (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geop_ccb_theme_mods['linkmenu_controls']) == 'tran'){ ?>

    <div class="container-fluid" style="margin-top:-4em;">
      <div class="row text-white">
        <div class="col-md-offset-3 text-white" style="color:white;">
          <ul role="menu" class="header__menu text-white" style="color:white!important; margin-right:1em;">
            <?php
            wp_nav_menu( array(
              'theme_location' => 'community-links',
              'container' => 'li',
              'container_class' => 'text-white nav navbar-nav navbar-right navbar-fixed-bottom',
              'items_wrap' => '%3$s',
              'fallback_cb' => false
             ) );
             ?>
          </ul>
        <br />
        </div><!--#col-md-offset-3-->
      </div><!--#row-->
    </div><!--#container-fluid-->
  <?php } ?>
</div><!--#banner section-linked-->
