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

// Bootstrap determination logic.
$geopccb_bootstrap_use = true;
if (get_theme_mod('bootstrap_controls', $geopccb_theme_options['bootstrap_controls']) == 'gone')
  $geopccb_bootstrap_use = false;
elseif (isset($post)){
  if ( $post->post_name == 'geoplatform-search' || $post->post_name == 'register' || $post->post_name == 'geoplatform-items' )
    $geopccb_bootstrap_use = false;
}
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
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

  <?php
  //enabling enhanced comment display
  if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

  wp_head();
  ?>

</head>
<header class="o-header o-header--sticky" role="banner">

    <div class="o-header__primary" data-page-title="Welcome to the GeoPlatform!">

        <!--
            REMOVE THIS COMMENT WHEN IMPLEMENTING...
            Use H1 on .a-brand b/c 508 requires an H1 to appear near the top of
            a page for screen readers to know where to start. Note that the
            .a-brand class overrides any styles set by H1-H6, so no worries as
            long as no blanket styles are defined for H1-H6.
        -->
        <h1 class="a-brand">
            <img alt="GP" src="<?php echo get_stylesheet_directory_uri() . '/img/logo.svg' ?>" style="width:1em">
            <a href="<?php echo home_url() ?>/" title="Home">GeoPlatform.gov</a>
        </h1>

        <nav class="a-nav" role="navigation" aria-label="High-level navigation links" role="menu">
          <?php
          // Search bar format determination.
          // $geopccb_search_format = get_theme_mod('searchbar_controls', $geopccb_theme_options['searchbar_controls']);
          // if ($geopccb_search_format == 'gp' && !in_array('geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array())))
          //   $geopccb_search_format = 'wp';
          //
          // // Checks the search bar settings and switches them out as needed.
          // if ($geopccb_search_format == 'wp'){
          //   echo "<li>";
          //   get_search_form();
          //   echo "</li>";
          // }
          // elseif ($geopccb_search_format == 'gp'){
          //   echo "<li>";
          //   get_template_part( 'gpsearch', get_post_format() );
          //   echo "</li>";
          // }


          ?>
          <div class="a-nav__collapsible-menu">
            <?php
            wp_nav_menu( array(
              'theme_location' => 'community-links',
              // 'container' => 'a',
              // 'container_class' => 'nav navbar-nav navbar-right navbar-fixed-bottom',
              'items_wrap' => '%3$s',
              // 'fallback_cb' => false
             ) );
             ?>
              <a role="menuitem" class="is-hidden--xs is-hidden--sm" href="<?php echo home_url(get_theme_mod('headlink_ngda_themes')); ?>">NGDA Themes</a>
            </div>
            <a role="menuitem" class="is-hidden--xs" href="<?php echo home_url(get_theme_mod('headlink_help')); ?>">Help</a>
            <a role="menuitem" class="is-linkless" onclick="toggleClass('#header-megamenu','is-open')">
                <span class="is-hidden--xs">More</span>
                <span class="fas fa-bars is-hidden--sm is-hidden--md is-hidden--lg"></span>
            </a>
        </nav>

        <?php
        $geopportal_current_user = wp_get_current_user();

        $geopportal_login_url;
        if ( is_front_page() || is_404() ){ $geopportal_login_url = home_url(); }
        elseif ( is_category() ){ $geopportal_login_url = esc_url( get_category_link( $wp_query->get_queried_object_id() ) ); }
        else { $geopportal_login_url = get_permalink(); }

        if($geopportal_current_user->ID != 0) {

          $geopportal_front_username_text = "";
          $geopportal_front_loginname_text = "";
          $geopportal_front_user_redirect = gpp_getEnv('accounts_url',"https://accounts.geoplatform.gov");

          if (!empty($geopportal_current_user->user_firstname) && !empty($geopportal_current_user->user_lastname))
            $geopportal_front_username_text = $geopportal_current_user->user_firstname . " " . $geopportal_current_user->user_lastname;
          elseif (!empty($geopportal_current_user->user_firstname) && empty($geopportal_current_user->user_lastname))
            $geopportal_front_username_text = $geopportal_current_user->user_firstname;
          elseif (empty($geopportal_current_user->user_firstname) && !empty($geopportal_current_user->user_lastname))
            $geopportal_front_username_text = $geopportal_current_user->user_lastname;
          else
            $geopportal_front_username_text = $geopportal_current_user->user_login;

          $geopportal_front_loginname_text = $geopportal_current_user->user_login;
          $geopportal_front_user_redirect = gpp_getEnv('accounts_url',"https://accounts.geoplatform.gov");
          ?>

          <div class="dropdown" id="geopportal_header_user_dropdown_parent">
              <button class="btn btn-link dropdown-toggle" type="button" id="userSignInButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="fas fa-user"></span>
                  <span class="is-hidden--xs"><?php echo $geopportal_front_username_text ?></span>
              </button>
              <div class="dropdown-menu dropdown-menu-right" id="geopportal_header_user_dropdown_child" aria-labelledby="userSignInButton">
                  <div class="d-flex">
                      <div class="col u-text--center">
                          <span class="fas fa-user fa-5x"></span>
                          <br>
                          <?php
                          if($geopportal_current_user->ID != 0) { ?>
                            <div><strong><?php echo $geopportal_front_username_text ?></strong></div>
                            <div class="u-text--sm"><em><?php echo $geopportal_front_loginname_text ?></em></div>
                          <?php } else { ?>
                            <div><strong><a href="<?php echo esc_url(wp_login_url( $geopportal_login_url ) ); ?>"><?php echo $geopportal_front_username_text ?></a></strong></div>
                          <?php } ?>
                      </div>
                      <div class="col">
                          <a class="dropdown-item" href="<?php echo $geopportal_front_user_redirect ?>/profile">Edit Profile</a>
                          <a class="dropdown-item" href="<?php echo $geopportal_front_user_redirect ?>/updatepw">Change Password</a>
                          <?php
                          if($geopportal_current_user->ID != 0) { ?>
                            <a class="dropdown-item" href="<?php echo esc_url(wp_logout_url( $geopportal_login_url ) ); ?>">Sign Out</a>
                          <?php } ?>
                          </div>
                      </div>
                  </div>
                  <!-- <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a> -->
              </div>
            <?php } else { ?>
              <div class="dropdown" id="geopportal_header_user_dropdown_parent">
                  <a class="btn btn-link" href="<?php echo esc_url(wp_login_url( $geopportal_login_url ) ); ?>">
                      <span class="fas fa-user"></span>
                      <span class="is-hidden--xs">Sign In</span>
                  </a>
              </div>

            <?php } ?>
        </div>
    </div>

    <div class="o-header__secondary">

        <?php
        if (is_front_page()){ ?>
          <div class="a-page__title">Welcome to the GeoPlatform!</div>
        <?php } elseif (is_404()) { ?>
          <div class="a-page__title">Page Not Found</div>
        <?php } elseif (is_category()) { ?>
          <div class="a-page__title"><?php esc_html(single_cat_title()); ?></div>
        <?php } else { ?>
          <div class="a-page__title"><?php the_title(); ?></div>
        <?php } ?>

    </div>
    <nav class="m-megamenu" id="header-megamenu">
        <div class="m-megamenu__content">

            <div class="col">

              <div class="d-lg-none d-xl-none">
                  <div class="m-megamenu__heading">Navigation</div>
                  <ul class="menu" role="menu">
                      <li role="menuitem" class="d-md-none">
                          <a href="<?php echo home_url('resources'); ?>">Explore</a>
                          <ul class="menu" role="menu">
                              <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_data')); ?>" class="u-pd-left--xlg">Datasets</a></li>
                              <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_services')); ?>" class="u-pd-left--xlg">Services</a></li>
                              <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_layers')); ?>" class="u-pd-left--xlg">Layers</a></li>
                              <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_maps')); ?>" class="u-pd-left--xlg">Maps</a></li>
                              <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_galleries')); ?>" class="u-pd-left--xlg">Galleries</a></li>
                              <li role="menuitem"><a href="<?php echo home_url(get_theme_mod('headlink_communities')); ?>" class="u-pd-left--xlg">Communities</a></li>
                          </ul>
                      </li>
                      <li role="menuitem">
                          <a role="menuitem" href="<?php echo home_url(get_theme_mod('headlink_ngda_themes')); ?>">NGDA Themes</a>
                      </li>
                      <li role="menuitem">
                          <a role="menuitem" href="<?php echo home_url(get_theme_mod('headlink_search')); ?>">Search</a>
                      </li>
                  </ul>
                  <br>
              </div>

              <div class="m-megamenu__heading">Featured</div>
              <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>
              <br>
              <div class="m-megamenu__heading">Get Involved</div>
              <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?>
            </div>


            <div class="col">
                <div class="m-megamenu__heading">Explore Data</div>
                <ul class="menu" role="menu">
                    <li role="menuitem">
                        <a href="<?php echo geop_ccb_getEnv('oe_url',"https://oe.geoplatform.gov"); ?>" target="_blank">
                            Object Editor
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                  </li>
                  <li role="menuitem">
                      <a href="<?php echo esc_url('https://geoplatform.maps.arcgis.com/home/'); ?>" target="_blank">
                          GeoPlatform ArcGIS Organization
                          <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="<?php echo geop_ccb_getEnv('ckan_url',"https://data.geoplatform.gov/"); ?>" target="_blank">
                          Search Data.gov
                          <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="<?php echo geop_ccb_getEnv('ckan_mp_url',"https://data.geoplatform.gov/#/?progress=planned&h=Marketplace"); ?>" target="_blank">
                          Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="<?php echo home_url('resources'); ?>" target="_blank">
                          Resources <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                </ul>
                <br>
                <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_apps')); ?>">Apps &amp; Services</a>
                <ul class="menu" role="menu">
                    <li role="menuitem">
                        <a href="<?php echo geop_ccb_getEnv('viewer_url', 'https://viewer.geoplatform.gov'); ?>" target="_blank">
                            Map Viewer
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo geop_ccb_getEnv('maps_url', 'https://maps.geoplatform.gov'); ?>" target="_blank">
                            Map Manager
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo geop_ccb_getEnv('marketplace_url',"https://marketplace.geoplatform.gov"); ?>" target="_blank">
                            Marketplace Preview
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo geop_ccb_getEnv('dashboard_url',"https://dashboard.geoplatform.gov/#/lma?surveyId=8&page=0&size=500&sortElement=title&sortOrder=asc&colorTheme=green"); ?>" target="_blank">
                            Performance Dashboard
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo esc_url('http://statuschecker.fgdc.gov'); ?>" target="_blank">
                            FGDC Service Status Checker
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                     </li>
                </ul>
            </div>


            <div class="col">
                <div class="m-megamenu__heading">About</div>
                <?php wp_nav_menu( array( 'theme_location' => 'headfoot-about' ) ); ?>
            </div>

        </div>

        <button type="button" class="btn btn-link btn-block" onclick="toggleClass('#header-megamenu','is-open')">
            <span class="fas fa-caret-up"></span>
        </button>
    </nav>

</header>

















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
              if ($geopccb_bootstrap_use)
                echo "<ul role='menu' class='header__menu'>";
              else
                echo "<ul role='menu' class='header__menu' style='margin-top:0.5em;'>";

              // Search bar format determination.
              $geopccb_search_format = get_theme_mod('searchbar_controls', $geopccb_theme_options['searchbar_controls']);
              if ($geopccb_search_format == 'gp' && !in_array('geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array())))
                $geopccb_search_format = 'wp';

              // Checks the search bar settings and switches them out as needed.
              if ($geopccb_search_format == 'wp'){
                echo "<li>";
                get_search_form();
                echo "</li>";
              }
              elseif ($geopccb_search_format == 'gp'){
                echo "<li>";
                get_template_part( 'gpsearch', get_post_format() );
                echo "</li>";
              }

              // mega menu toggle
              if ($geopccb_bootstrap_use){
                echo "<li>";
                  echo "<div class='btn-group'>";
                    echo "<button type='button' class='btn btn-link header__btn dropdown-toggle' data-toggle='dropdown' data-target='#megamenu' aria-expanded='false'>";
                      echo "<span class='icon-hamburger-menu t-light'></span>";
                      echo "<span class='hidden-xs'>" . __( 'Menu', 'geoplatform-ccb') . "<span class='caret'></span></span>";
                    echo "</button>";
                  echo "</div>";
                echo "</li>";
              } ?>
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
