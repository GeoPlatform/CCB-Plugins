<?php
/**
 * A GeoPlatform Header template
 *
 * @link https://codex.wordpress.org/Designing_Headers
 *
 * enhanced comment display
 * @link //per https://codex.wordpress.org/Migrating_Plugins_and_Themes_to_2.7/Enhanced_Comment_Display
 *
 * @package GeoPlatform CCB
 *
 * @since 1.0.0
 */

// Getting theme mods for search bar and mega-menu hiding checks.
$geopccb_theme_options = geop_ccb_get_theme_mods();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
<?php
//enabling enhanced comment display
if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head();?>

  </head>
<body <?php body_class(); ?>>

<?php if (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) != 'tran'){ ?>

  <div class="container-fluid navbar-default">
    <div class="row">
      <div class="col-md-offset-3" style="margin-left:0%">
        <ul role="menu" class="header__menu header__menu_alt" style="color:black!important; margin-left:1em; float:left;">
          <?php
          wp_nav_menu( array(
            'theme_location' => 'community-links',
            'container' => 'li',
            'container_class' => 'nav navbar-nav navbar-right navbar-fixed-bottom',
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

  <header class="t-transparent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

          <!-- Checks for a lack of mega-menu button and adjusts height to keep it consistant. -->
              <?php
              if (get_theme_mod('bootstrap_controls', $geopccb_theme_options['bootstrap_controls']) != 'gone' && $post->post_name != 'geoplatform-search' && $post->post_name != 'register' && $post->post_name != 'geoplatform-items'){
                ?>
                <ul role="menu" class="header__menu">
                <?php
              } else {
                ?>
                <ul role="menu" class="header__menu" style="margin-top:0.5em;">
                <?php
              }

              $geopccb_search_format = get_theme_mod('searchbar_controls', $geopccb_theme_options['searchbar_controls']);
              if ($geopccb_search_format == 'gp' && !in_array('geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array())))
                $geopccb_search_format = 'wp';

              // Checks the search bar settings and switches them out as needed.
              if ($geopccb_search_format == 'wp'){
                ?>
                <li><?php get_search_form(); ?></li>
                <?php
              } elseif ($geopccb_search_format == 'gp'){
                ?>
                <li><?php get_template_part( 'gpsearch', get_post_format() ); ?></li>
                <?php
              }

                    if (get_theme_mod('bootstrap_controls', $geopccb_theme_options['bootstrap_controls']) != 'gone' && $post->post_name != 'geoplatform-search' && $post->post_name != 'register' && $post->post_name != 'geoplatform-items'){
                      ?>
                      <li>
                      <!-- mega menu toggle -->
                        <div class="btn-group">
                          <button type="button" class="btn btn-link header__btn dropdown-toggle"
                                data-toggle="dropdown" data-target="#megamenu" aria-expanded="false">
                            <span class="icon-hamburger-menu t-light"></span>
                            <span class="hidden-xs"><?php _e( 'Menu', 'geoplatform-ccb'); ?><span class="caret"></span></span>
                          </button>
                        </div>
                      </li>
                    <?php } ?>
                    <!-- login button toggle -->
                    <!-- Disable for now, re-enable for authentication -->
                    <li class="hidden-xs">
                        <div class="btn-account btn-group">

                            <!--if(!authenticated) -->
                            <?php if (!is_user_logged_in()){?>
                              <a href="<?php echo esc_url(wp_login_url( home_url() ) ); ?>">
                                  <button style="color:white;" type="button" class="btn btn-link" onclick="login"><?php _e( 'Sign In', 'geoplatform-ccb'); ?></button>
                                </a>
                          <?php  } else {?>
                            <a href="<?php echo esc_url(wp_logout_url( home_url() ) ); ?>">
                                <button style="color:white;" type="button" class="btn btn-link"><?php _e( 'Sign Out', 'geoplatform-ccb'); ?></button>
                              </a>
                            <?php } ?>
                        </div>
                    </li>
                </ul>
                <?php
                  if (function_exists('the_custom_logo') && has_custom_logo()){
                    echo '<h4 id="custom_header_logo">';
                    the_custom_logo();
                  }
                  else{
                    echo '<h4 class="brand"><a href="';
                    echo esc_url($GLOBALS['geopccb_wpp_url']);
                    echo '" title="' . __( 'Go to the Geoplatform Home Page', 'geoplatform-ccb') . '><span class="icon-gp"></span>GeoPlatform:</a>';
                  }?>
                  <!-- This will be the "Site Title" in the Customizer Site Identity tab -->
                  <a href="<?php echo esc_url(get_site_url());?>" title="Go to the <?php echo esc_html(get_bloginfo( 'name' )); ?> Home Page">
                  <?php echo esc_html(get_bloginfo( 'name' )); ?>
                </a>
                </h4>
            </div><!--#col-md-12-->
        </div><!--#row-->
</header>
