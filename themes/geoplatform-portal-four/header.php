<?php
/**
 * A GeoPlatform Header template
 *
 * @link https://codex.wordpress.org/Designing_Headers
 *
 * enhanced comment display
 * @link //per https://codex.wordpress.org/Migrating_Plugins_and_Themes_to_2.7/Enhanced_Comment_Display
 */

// Getting theme mods for search bar and mega-menu hiding checks.
$geopccb_theme_options = geop_ccb_get_theme_mods();
?>
<html lang="en-us">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

  <?php wp_head(); ?>

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
            <div class="a-nav__collapsible-menu">
                <div class="dropdown" role="menuitem">
                    <button class="btn btn-link dropdown-toggle geopportal_explore_button is-hidden--xs" href="<?php echo home_url('resources'); ?>" id="explore-resources-button" aria-haspopup="true" aria-expanded="false" role="menu">
                        Explore
                    </button>
                    <div id="geopportal_header_explore_dropdown_child" class="dropdown-menu dropdown-menu-right" aria-labelledby="explore-resources-button" style="z-index:9999;">
                        <a href="<?php echo home_url(get_theme_mod('headlink_data')); ?>">Datasets</a>
                        <a href="<?php echo home_url(get_theme_mod('headlink_services')); ?>">Services</a>
                        <a href="<?php echo home_url(get_theme_mod('headlink_layers')); ?>">Layers</a>
                        <a href="<?php echo home_url(get_theme_mod('headlink_maps')); ?>">Maps</a>
                        <a href="<?php echo home_url(get_theme_mod('headlink_galleries')); ?>">Galleries</a>
                        <a href="<?php echo home_url(get_theme_mod('headlink_communities')); ?>">Communities</a>
                    </div>
                </div>

                <a role="menuitem" class="is-hidden--xs is-hidden--sm" href="<?php echo home_url(get_theme_mod('headlink_ngda_themes')); ?>">NGDA Themes</a>
                <a role="menuitem" class="is-hidden--xs is-hidden--sm" href="<?php echo home_url(get_theme_mod('headlink_search')); ?>">Search</a>
            </div>
            <a role="menuitem" class="is-hidden--xs" href="<?php echo home_url(get_theme_mod('headlink_help')); ?>">Help</a>
            <button id="megamenu-button" role="menuitem" class="btn btn-link is-linkless" onclick="toggleClass('#header-megamenu','is-open')">
                <span class="is-hidden--xs">More</span>
                <span class="fas fa-bars is-hidden--sm is-hidden--md is-hidden--lg"></span>
            </button>
        </nav>

        <?php
        $geopportal_current_user = wp_get_current_user();

        // Sets the login url, for redirection back to previous page on login/logout.
        // Address bar from...
        //
        // https://stackoverflow.com/questions/6768793/get-the-full-url-in-php
        //
        $geopportal_login_url;
        if ( is_front_page() || is_404() )
          $geopportal_login_url = home_url();
        elseif ( is_category() )
          $geopportal_login_url = esc_url( get_category_link( $wp_query->get_queried_object_id() ) );
        elseif (isset($post) && ( $post->post_name == 'register' || $post->post_name == 'geoplatform-items' || $post->post_name == 'geoplatform-map-preview'  || $post->post_name == 'geoplatform-search'))
          $geopportal_login_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        else
          $geopportal_login_url = get_permalink();

        if($geopportal_current_user->ID != 0) {

          $geopportal_front_username_text = "";
          $geopportal_front_loginname_text = "";
          $geopportal_front_user_redirect = gpp_getEnv('accounts_url',"https://accounts.geoplatform.gov");

          if (isset($_COOKIE["gpoauth-a"])){
            $geopportal_cookie_object = json_decode(base64_decode(explode(".", base64_decode($_COOKIE["gpoauth-a"]))[1]));
            $geopportal_front_username_text = $geopportal_cookie_object->name;
          }
          elseif (!empty($geopportal_current_user->user_firstname) && !empty($geopportal_current_user->user_lastname))
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
              <button class="btn btn-link dropdown-toggle" type="button" id="userSignInButton" aria-haspopup="true" aria-expanded="false">
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

    <nav class="m-megamenu" id="header-megamenu" style="z-index:9998;">
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

              <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_feat')); ?>">Featured</a>
              <?php wp_nav_menu( array( 'theme_location' => 'headfoot-featured' ) ); ?>
              <br>
              <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_involved')); ?>">Get Involved</a>
              <?php wp_nav_menu( array( 'theme_location' => 'headfoot-getInvolved' ) ); ?>
            </div>

            <div class="col">
              <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_explore')); ?>">Explore Data</a>
              <?php wp_nav_menu( array( 'theme_location' => 'headfoot-exploreData' ) ); ?>
              <br>
              <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_apps')); ?>">Apps &amp; Services</a>
              <?php wp_nav_menu( array( 'theme_location' => 'headfoot-appsService' ) ); ?>
            </div>

            <div class="col">
                <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_about')); ?>">About</a>
                <?php wp_nav_menu( array( 'theme_location' => 'headfoot-about' ) ); ?>
            </div>

        </div>

        <button type="button" class="btn btn-link btn-block" aria-label="Close" onclick="toggleClass('#header-megamenu','is-open')">
            <span class="fas fa-caret-up"></span>
        </button>
    </nav>

<!-- Header drop-down handling -->
  <script type="text/javascript">
    jQuery(document).ready(function() {

      // Explore resources button
      jQuery("#explore-resources-button").click(function(event){
        var geopccb_resource_var = (jQuery("#explore-resources-button").attr("aria-expanded") == 'false') ? 'true' : 'false';
        jQuery("#explore-resources-button").attr("aria-expanded", geopccb_resource_var);
        jQuery("#geopportal_header_explore_dropdown_child").toggleClass("show");

        if (jQuery('#header-megamenu').hasClass("is-open")){
          jQuery('#header-megamenu').toggleClass("is-open");
        }
        if (jQuery('#geopportal_header_user_dropdown_child').hasClass("show")){
          var geopccb_user_var = (jQuery("#userSignInButton").attr("aria-expanded") == 'false') ? 'true' : 'false';
          jQuery("#userSignInButton").attr("aria-expanded", geopccb_user_var);
          jQuery('#geopportal_header_user_dropdown_child').toggleClass("show");
        }
      });

      // User info button
      jQuery("#userSignInButton").click(function(event){
        var geopccb_user_var = (jQuery("#userSignInButton").attr("aria-expanded") == 'false') ? 'true' : 'false';
        jQuery("#userSignInButton").attr("aria-expanded", geopccb_user_var);
        jQuery("#geopportal_header_user_dropdown_child").toggleClass("show");

        if (jQuery('#header-megamenu').hasClass("is-open")){
          jQuery('#header-megamenu').toggleClass("is-open");
        }
        if (jQuery('#geopportal_header_explore_dropdown_child').hasClass("show")){
          var geopccb_resource_var = (jQuery("#explore-resources-button").attr("aria-expanded") == 'false') ? 'true' : 'false';
          jQuery("#explore-resources-button").attr("aria-expanded", geopccb_resource_var);
          jQuery('#geopportal_header_explore_dropdown_child').toggleClass("show");
        }
      });

      // Megamenu button, here only hiding other drop-downs if visible.
      jQuery("#megamenu-button").click(function(event){
        if (jQuery('#geopportal_header_explore_dropdown_child').hasClass("show")){
          var geopccb_resource_var = (jQuery("#explore-resources-button").attr("aria-expanded") == 'false') ? 'true' : 'false';
          jQuery("#explore-resources-button").attr("aria-expanded", geopccb_resource_var);
          jQuery('#geopportal_header_explore_dropdown_child').toggleClass("show");
        }
        if (jQuery('#geopportal_header_user_dropdown_child').hasClass("show")){
          var geopccb_user_var = (jQuery("#userSignInButton").attr("aria-expanded") == 'false') ? 'true' : 'false';
          jQuery("#userSignInButton").attr("aria-expanded", geopccb_user_var);
          jQuery('#geopportal_header_user_dropdown_child').toggleClass("show");
        }
      });
    });
  </script>

</header>
