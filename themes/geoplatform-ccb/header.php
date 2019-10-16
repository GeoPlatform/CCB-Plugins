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

<!-- HTML head stuff -->
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php
echo "<head>";
  echo "<meta charset='utf-8'>";
  echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'>";
  echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
  // <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  echo "<meta name='description' content=''>";
  echo "<meta name='author' content=''>";
  echo "<link rel='shortcut icon' href='" . get_template_directory_uri() . "/favicon.ico' />";

  //enabling enhanced comment display
  if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

  wp_head();

echo "</head>";

// Proper header stuff.
echo "<header class='o-header o-header--sticky' role='banner'>";

  // Minute checks for banner consideration. Current page is checked for being
  // a plugin page, then for being a specific duty page. Used further down too.
  $geopccb_plugin_page = false;
  if (isset($post)){
    if ( $post->post_name == 'geoplatform-search' || $post->post_name == 'register' || $post->post_name == 'geoplatform-items' )
      $geopccb_plugin_page = true;
    elseif (is_404() || is_search() || is_tag() || is_archive()) {
      $geopccb_plugin_page = true;
    }
  }

  // Checks three criteria that may cause the header bar to touch a grey menu bar.
  // 1) The community-links menu is above the title bar.
  // 2) The blue title is removed due to post banner being enabled, the current
  //    location is a singular, and criteria two-sub is true.
  // 2-sub) The breadcrumbs are enabled OR the category-links menu isn't set to
  //        "integrated", so at least one grey bar is below the header bar.
  $geopccb_criteria_one = has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) == 'above';
  $geopccb_criteria_two_sub = get_theme_mod('breadcrumb_controls', $geopccb_theme_options['breadcrumb_controls']) != 'off' || (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) != 'integrated');
  $geopccb_criteria_two = get_theme_mod('postbanner_controls', $geopccb_theme_options['postbanner_controls']) != 'off' && is_singular() && $geopccb_criteria_two_sub;

  // The plugin_page criteria is included in case it is a fully-exempt page.
  if ( ($geopccb_criteria_one || $geopccb_criteria_two) && !$geopccb_plugin_page)
    echo "<div class='header-grey-border o-header__primary' data-page-title='Welcome to the GeoPlatform!'>";
  else
    echo "<div class='o-header__primary' data-page-title='Welcome to the GeoPlatform!'>";

        // <!--
        //     REMOVE THIS COMMENT WHEN IMPLEMENTING...
        //     Use H1 on .a-brand b/c 508 requires an H1 to appear near the top of
        //     a page for screen readers to know where to start. Note that the
        //     .a-brand class overrides any styles set by H1-H6, so no worries as
        //     long as no blanket styles are defined for H1-H6.
        // -->
    echo "<h1 class='a-brand'>";
      echo "<img alt='GP' src='" . get_template_directory_uri() . "/img/logo.svg' style='width:1em'>";
      echo "<a href='https://www.geoplatform.gov' title='Home'>GeoPlatform.gov</a>";
    echo "</h1>";

    //Banner stuff
    echo "<nav class='a-nav' role='navigation' aria-label='High-level navigation links' role='menu'>";

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
      if (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) == 'integrated'){
        echo "<div class='a-nav__collapsible-menu'>";

          $geopccb_head_menu_array = array(
            'theme_location' => 'community-links',
            'container' => false,
            'echo' => false,
            'items_wrap' => '%3$s',
            'depth' => 0,
            'fallback_cb' => false,
            'link_class' => 'is-hidden--xs is-hidden--sm',
            'link_role' => 'menuitem',
          );

          echo strip_tags( wp_nav_menu( $geopccb_head_menu_array ), '<a>' );
        echo "</div>";
      }

      // Displays the megamenu button, but only if enabled.
      if (get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'both' || get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'head'){
        ?>
        <!-- Megamenu opener/closer, can't be PHP echoed. -->
        <a role="menuitem" class="is-linkless" onclick="toggleClass('#header-megamenu','is-open')">
          <span class="is-hidden--xs">More</span>
          <span class="fas fa-bars is-hidden--sm is-hidden--md is-hidden--lg"></span>
        </a>
        <?php
      }

    echo "</nav>";

    // This ENTIRE section handles the user info logic.
    $geopccb_current_user = wp_get_current_user();

    // Sets the login url, for redirection back to previous page on login/logout.
    // Address bar from...
    //
    // https://stackoverflow.com/questions/6768793/get-the-full-url-in-php
    //
    $geopccb_login_url;
    if ( is_front_page() || is_404() )
      $geopccb_login_url = home_url();
    elseif ( is_category() )
      $geopccb_login_url = esc_url( get_category_link( $wp_query->get_queried_object_id() ) );
    elseif (isset($post) && ( $post->post_name == 'register' || $post->post_name == 'geoplatform-items' || $post->post_name == 'geoplatform-map-preview' ))
      $geopccb_login_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    else
      $geopccb_login_url = get_permalink();

    // Trigger block for if the user is valid (not ID 0).
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

        // <!-- User section continued, HTML area -->
      echo "<div class='dropdown' id='geopccb_header_user_dropdown_parent'>";
        echo "<button class='btn btn-link dropdown-toggle' type='button' id='userSignInButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
          echo "<span class='fas fa-user'></span>";
          echo "<span class='is-hidden--xs'>&nbsp". $geopccb_front_username_text . "</span>";
        echo "</button>";

        echo "<div class='dropdown-menu dropdown-menu-right' id='geopccb_header_user_dropdown_child' aria-labelledby='userSignInButton'>";
          echo "<div class='d-flex'>";
            echo "<div class='col u-text--center'>";
              echo "<span class='fas fa-user fa-5x'></span>";
              echo "<br>";

              if($geopccb_current_user->ID != 0) {
                echo "<div><strong>" . $geopccb_front_username_text . "</strong></div>";
                echo "<div class='u-text--sm'><em>" . $geopccb_front_loginname_text . "</em></div>";
              } else {
                echo "<div><strong><a href='" . esc_url(wp_login_url( $geopccb_login_url ) ) . "'>" . $geopccb_front_username_text . "</a></strong></div>";
              }

            echo "</div>";
            echo "<div class='col'>";
              echo "<a class='dropdown-item' href='" . $geopccb_front_user_redirect . "/profile'>Edit Profile</a>";
              echo "<a class='dropdown-item' href='" . $geopccb_front_user_redirect . "/updatepw'>Change Password</a>";

              if($geopccb_current_user->ID != 0) {
                echo "<a class='dropdown-item' href='" . esc_url(wp_logout_url( $geopccb_login_url ) ) . "'>Sign Out</a>";
              }

            echo "</div>";
          echo "</div>";
        echo "</div>";
      echo "</div>";

    ?>
    <script type="text/javascript">

      // Toggles user info visibility.
			jQuery(document).ready(function() {

				jQuery("#geopccb_header_user_dropdown_parent").click(function(event){
          var geopccb_user_var = (jQuery("#userSignInButton").attr("aria-expanded") == 'false') ? 'true' : 'false';
          jQuery("#userSignInButton").attr("aria-expanded", geopccb_user_var);
					jQuery("#geopccb_header_user_dropdown_child").toggleClass("show");
          jQuery("#geopccb_header_user_dropdown_parent").toggleClass("show");
				});

		  });
		</script>

    <?php
    } else {

      // What's output if the user is not logged in.
      echo "<div class='dropdown' id='geopccb_header_user_dropdown_parent'>";
        echo "<a class='btn btn-link' href='" . esc_url(wp_login_url( $geopccb_login_url ) ) . "'>";
          echo "<span class='fas fa-user'></span>";
          echo "<span class='is-hidden--xs'>&nbspSign In</span>";
        echo "</a>";
      echo "</div>";
    }
    echo "</div>";
  echo "</div>";

  // Title bar portion.
  echo "<div class='community-link-menu-control'>";
    if (has_nav_menu('community-links') && get_theme_mod('linkmenu_controls', $geopccb_theme_options['linkmenu_controls']) == 'above'){
      echo "<div class='geopccb-header-bar'>";
        geop_ccb_lower_community_links();
      echo "</div>";
    }

    // Current page is checked for banner being off, status as not a post, or
    // whether it's one of the filtered posts above. If it passes any, the blue
    // title card is shown.
    if ((get_theme_mod('postbanner_controls', $geopccb_theme_options['postbanner_controls']) == 'off') || !is_singular() || $geopccb_plugin_page){

        echo "<div class='o-header__secondary' style='margin-top:0px;'>";

        // Various checks for the current page, changes title out as necessary.
        if (is_front_page()){
          echo "<div class='a-page__title'>" . esc_html(get_bloginfo( 'name' )) . "</div>";
        } elseif (is_404()) {
          echo "<div class='a-page__title'>Page Not Found</div>";
        } elseif (is_search()) {
          echo "<div class='a-page__title'>Search Results</div>";
        } elseif (is_category()) {
          echo "<div class='a-page__title'>" . esc_html(single_cat_title('', false)) . "</div>";
        } elseif (is_tag()) {
          echo "<div class='a-page__title'>" . esc_html(ucwords(single_tag_title('', false))) . "</div>";
        } else {
          echo "<div class='a-page__title'>" . get_the_title() . "</div>";
        }

        echo "</div>";
      }
    echo "</div>";

    // New Megamenu area. Hides if the the More button is disabled.
    if (get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'both' || get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'head'){
      echo "<nav class='m-megamenu' id='header-megamenu'>";
        echo "<div class='m-megamenu__content'>";
          echo "<div class='col'>";
            echo "<div class='d-lg-none d-xl-none'>";
              echo "<div class='m-megamenu__heading'>Navigation</div>";
              echo "<ul class='menu' role='menu'>";

                if ($geopccb_search_format == 'gp'){
                    echo "<li role='menuitem'>";
                    echo "<a role='menuitem' class='d-md-none' href='" . home_url('geoplatform-search') . "'>Search</a>";
                    echo "</li>";
                }
                wp_nav_menu( array( 'theme_location' => 'community-links', 'fallback_cb' => false ) );

              echo "</ul>";
              echo "<br>";
            echo "</div>";

            echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-left')) ? esc_html(wp_get_nav_menu_name('header-left')) : '') . "</div>";
                wp_nav_menu( array( 'theme_location' => 'header-left', 'fallback_cb' => false ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-center')) ? esc_html(wp_get_nav_menu_name('header-center')) : '') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'header-center', 'fallback_cb' => false ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-right-col1')) ? esc_html(wp_get_nav_menu_name('header-right-col1')) : '') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'header-right-col1', 'fallback_cb' => false ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('header-right-col2')) ? esc_html(wp_get_nav_menu_name('header-right-col2')) : '') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'header-right-col2', 'fallback_cb' => false ) );
            echo "</div>";

          echo "</div>";

          ?>
          <!-- Another section that cannot be echoed. -->
          <button type="button" class="btn btn-link btn-block" aria-label="Close" onclick="toggleClass('#header-megamenu','is-open')">
            <span class="fas fa-caret-up"></span>
          </button>
          <?php

      echo "</nav>";
    }
echo "</header>";
