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

        <!-- Banner stuff -->
        <nav class="a-nav" role="navigation" aria-label="High-level navigation links" role="menu">
          <?php
          // Search bar format determination.
          $geopccb_search_format = get_theme_mod('searchbar_controls', $geopccb_theme_options['searchbar_controls']);
          if ($geopccb_search_format == 'gp' && !in_array('geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array())))
            $geopccb_search_format = 'wp';

          // Checks the search bar settings and switches them out as needed.
          if ($geopccb_search_format == 'wp'){
            echo "<a role='menuitem' class='is-hidden--xs is-hidden--sm is-hidden--md'>";
            get_search_form();
            echo "</a>";
          }
          elseif ($geopccb_search_format == 'gp'){
            echo "<a role='menuitem' class='is-hidden--xs is-hidden--sm is-hidden--md'>";
            get_template_part( 'gpsearch', get_post_format() );
            echo "</a>";
            echo "<a role='menuitem' class='d-xl-none is-hidden--xs' href='" . home_url('geoplatform-search') . "'>Search</a>";
          }

          // Menu area for the Header Links.
          echo "<div class='a-nav__collapsible-menu'>";

            $geopccb_head_menu_array = array(
              'menu' => 'community-links',
              'container' => false,
              'echo' => false,
              // 'container_class' => 'a-nav__collapsible-menu',
              'items_wrap' => '%3$s',
              'depth' => 0,
              'fallback_cb' => false,
              'link_class' => 'is-hidden--xs is-hidden--sm',
              'link_role' => 'menuitem',
            );

            echo strip_tags( wp_nav_menu( $geopccb_head_menu_array ), '<a>' );
            ?>
          <!-- Megamenu opener/closer -->
          </div>
            <a role="menuitem" class="is-linkless" onclick="toggleClass('#header-megamenu','is-open')">
                <span class="is-hidden--xs">More</span>
                <span class="fas fa-bars is-hidden--sm is-hidden--md is-hidden--lg"></span>
            </a>
        </nav>

        <?php

        // This ENTIRE section handles the user info section.
        $geopccb_current_user = wp_get_current_user();

        $geopccb_login_url;
        if ( is_front_page() || is_404() ){ $geopccb_login_url = home_url(); }
        elseif ( is_category() ){ $geopccb_login_url = esc_url( get_category_link( $wp_query->get_queried_object_id() ) ); }
        else { $geopccb_login_url = get_permalink(); }

        if($geopccb_current_user->ID != 0) {

          $geopccb_front_username_text = "";
          $geopccb_front_loginname_text = "";
          $geopccb_front_user_redirect = geop_ccb_getEnv('accounts_url',"https://accounts.geoplatform.gov");

          if (!empty($geopccb_current_user->user_firstname) && !empty($geopccb_current_user->user_lastname))
            $geopccb_front_username_text = $geopccb_current_user->user_firstname . " " . $geopccb_current_user->user_lastname;
          elseif (!empty($geopccb_current_user->user_firstname) && empty($geopccb_current_user->user_lastname))
            $geopccb_front_username_text = $geopccb_current_user->user_firstname;
          elseif (empty($geopccb_current_user->user_firstname) && !empty($geopccb_current_user->user_lastname))
            $geopccb_front_username_text = $geopccb_current_user->user_lastname;
          else
            $geopccb_front_username_text = $geopccb_current_user->user_login;

          $geopccb_front_loginname_text = $geopccb_current_user->user_login;
          $geopccb_front_user_redirect = geop_ccb_getEnv('accounts_url',"https://accounts.geoplatform.gov");
          ?>

          <!-- User section continued, HTML area -->
          <div class="dropdown" id="geopccb_header_user_dropdown_parent">
              <button class="btn btn-link dropdown-toggle" type="button" id="userSignInButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="fas fa-user"></span>
                  <span class="is-hidden--xs"><?php echo $geopccb_front_username_text ?></span>
              </button>
              <div class="dropdown-menu dropdown-menu-right" id="geopccb_header_user_dropdown_child" aria-labelledby="userSignInButton">
                  <div class="d-flex">
                      <div class="col u-text--center">
                          <span class="fas fa-user fa-5x"></span>
                          <br>
                          <?php
                          if($geopccb_current_user->ID != 0) { ?>
                            <div><strong><?php echo $geopccb_front_username_text ?></strong></div>
                            <div class="u-text--sm"><em><?php echo $geopccb_front_loginname_text ?></em></div>
                          <?php } else { ?>
                            <div><strong><a href="<?php echo esc_url(wp_login_url( $geopccb_login_url ) ); ?>"><?php echo $geopccb_front_username_text ?></a></strong></div>
                          <?php } ?>
                      </div>
                      <div class="col">
                          <a class="dropdown-item" href="<?php echo $geopccb_front_user_redirect ?>/profile">Edit Profile</a>
                          <a class="dropdown-item" href="<?php echo $geopccb_front_user_redirect ?>/updatepw">Change Password</a>
                          <?php
                          if($geopccb_current_user->ID != 0) { ?>
                            <a class="dropdown-item" href="<?php echo esc_url(wp_logout_url( $geopccb_login_url ) ); ?>">Sign Out</a>
                          <?php } ?>
                          </div>
                      </div>
                  </div>
                  <!-- <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a> -->
              </div>
            <?php } else { ?>
              <div class="dropdown" id="geopccb_header_user_dropdown_parent">
                  <a class="btn btn-link" href="<?php echo esc_url(wp_login_url( $geopccb_login_url ) ); ?>">
                      <span class="fas fa-user"></span>
                      <span class="is-hidden--xs">Sign In</span>
                  </a>
              </div>

            <?php } ?>
        </div>
    </div>

    <div class="o-header__secondary">

        <?php
        // Various checks for the current page, changes title out as necessary.
        if (is_front_page()){
          echo "<div class='a-page__title'>" . esc_html(get_bloginfo( 'name' )) . "</div>";
        } elseif (is_404()) {
          echo "<div class='a-page__title'>Page Not Found</div>";
        } elseif (is_category()) {
          echo "<div class='a-page__title'>" . esc_html(single_cat_title()) . "</div>";
        } else {
          echo "<div class='a-page__title'>" . the_title() . "</div>";
        } ?>

    </div>
    <nav class="m-megamenu" id="header-megamenu">
        <div class="m-megamenu__content">

            <div class="col">

              <div class="d-lg-none d-xl-none">
                  <div class="m-megamenu__heading">Navigation</div>
                  <ul class="menu" role="menu">
                    <?php
                    if ($geopccb_search_format == 'gp'){
                      echo "<li role='menuitem'>";
                      echo "<a role='menuitem' class='d-md-none' href='" . home_url('geoplatform-search') . "'>Search</a>";
                      echo "</li>";
                    }

                    wp_nav_menu( array( 'theme_location' => 'community-links' ) );
                    ?>

                  </ul>
                  <br>
              </div>

              <?php
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-left')) ? esc_html(wp_get_nav_menu_name('header-left')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'header-left' ) );
              ?>
            </div>

            <?php
            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-center')) ? esc_html(wp_get_nav_menu_name('header-center')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'header-center' ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-right-col1')) ? esc_html(wp_get_nav_menu_name('header-right-col1')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'header-right-col1' ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-right-col2')) ? esc_html(wp_get_nav_menu_name('header-right-col2')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'header-right-col2' ) );
            echo "</div>";
            ?>
        </div>

        <button type="button" class="btn btn-link btn-block" onclick="toggleClass('#header-megamenu','is-open')">
            <span class="fas fa-caret-up"></span>
        </button>
    </nav>

</header>
