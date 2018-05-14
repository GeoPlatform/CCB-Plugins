<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <!--http://themefoundation.com/wordpress-theme-customizer/ section 5.2 Radio Buttons-->
    <?php
    $font_choice = get_theme_mod( 'font_choice' );
    if( $font_choice != '' ) {
        switch ( $font_choice ) {
            case 'lato':
                echo '<style type="text/css">';
                echo "body { font-family: 'Lato', sans-serif !important;}";
                echo '</style>';
                break;
            case 'slabo':
                echo '<style type="text/css">';
                echo "body { font-family: 'Slabo 27px', serif !important; }";
                echo '</style>';
                break;
            //add more cases for more fonts later
        }
    }
?>
<?php
//enabling enhanced comment display
//per https://codex.wordpress.org/Migrating_Plugins_and_Themes_to_2.7/Enhanced_Comment_Display
if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head();?>

  </head>
<body <?php body_class(); ?>>
  <header class="t-transparent">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul role="menu" class="header__menu">
                    <li><?php get_search_form(); ?></li>
                    <li>
                        <!-- mega menu toggle -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-link header__btn dropdown-toggle"
                                    data-toggle="dropdown" data-target="#megamenu" aria-expanded="false">
                                <span class="icon-hamburger-menu t-light"></span>
                                <span class="hidden-xs">Menu <span class="caret"></span></span>
                            </button>
                        </div>
                    </li>

                    <!-- login button toggle -->
                    <!-- Disable for now, re-enable for authentication -->
                    <li class="hidden-xs">


                        <div class="btn-account btn-group">

                            <!--if(!authenticated) -->
                            <?php if (!is_user_logged_in()){?>
                              <a href="<?php echo esc_url(wp_login_url( get_option('siteurl') ) ); ?>">
                                  <button style="color:white;" type="button" class="btn btn-link" onclick="login">Sign In</button>
                                </a>
                          <?php  } else {?>
                            <a href="<?php echo esc_url(wp_logout_url( home_url() ) ); ?>">
                                <button style="color:white;" type="button" class="btn btn-link">Sign out</button>
                              </a>
                            <?php } ?>

                        </div>

                    </li>

                </ul>
                <h4 class="brand">
                  <a href="<?php echo esc_url($GLOBALS['wpp_url']); ?>" title="Go to the Geoplatform Home Page">
                      <span class="icon-gp"></span>
                      GeoPlatform
                  </a>
                  <!-- This will be the "Site Title" in the Customizer Site Identity tab -->
                  <a href="<?php echo esc_url(get_site_url());?>" title="Go to the <?php echo esc_html(get_bloginfo( 'name' )); ?> Home Page">
                  <?php echo esc_html(get_bloginfo( 'name' )); ?>
                </a>
                </h4>
            </div><!--#col-md-12-->
        </div><!--#row-->
</header>
