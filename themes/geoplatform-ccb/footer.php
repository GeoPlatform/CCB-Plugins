<?php
/**
 * A GeoPlatform Footer Template
 *
 * @link https://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29
 *
 * @package GeoPlatform CCB
 *
 * @since 3.1.3
 */
$geopccb_theme_options = geop_ccb_get_theme_mods();

echo "<div>";
  echo "<footer class='o-footer'>";

      // Displays the below only if the megamenu is enabled in the footer.
      if (get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'both' || get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'foot'){

        echo "<a class='u-float--right' href='#'>";
          echo "<span class='fas fa-arrow-up'></span> to top";
        echo "</a>";

        ?>
        <!-- Left in HTML so that Javascript will work. -->
        <div>
            <a onClick="toggleClass('#footer-megamenu','is-collapsed')">
                <span class="fas fa-angle-down"></span>
                Menu
            </a>
            <hr>
        </div>
        <?php
      }
      // Footer Megamenu section.
      echo "<nav class='m-megamenu is-collapsed' id='footer-megamenu'>";
        echo "<div class='m-megamenu__content'>";
          echo "<div class='col'>";
            echo "<div class='d-lg-none d-xl-none'>";
              echo "<div class='m-megamenu__heading'>Navigation</div>";
                echo "<ul class='menu' role='menu'>";

                  // Output logic time.

                  if (get_theme_mod('searchbar_controls', $geopccb_theme_options['searchbar_controls']) == 'gp'){
                    echo "<li role='menuitem'>";
                    echo "<a role='menuitem' class='d-md-none' href='" . home_url('geoplatform-search') . "'>Search</a>";
                    echo "</li>";
                  }
                  wp_nav_menu( array( 'theme_location' => 'community-links' ) );

                echo "</ul>";
                echo "<br>";
              echo "</div>";

              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('footer-left')) ? esc_html(wp_get_nav_menu_name('footer-left')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'footer-left' ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('footer-center')) ? esc_html(wp_get_nav_menu_name('footer-center')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'footer-center' ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('footer-right-col1')) ? esc_html(wp_get_nav_menu_name('footer-right-col1')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'footer-right-col1' ) );
            echo "</div>";

            echo "<div class='col'>";
              echo "<div class='m-megamenu__heading'>" . (esc_html(wp_get_nav_menu_name('footer-right-col2')) ? esc_html(wp_get_nav_menu_name('footer-right-col2')) : 'Example Menu Title') . "</div>";
              wp_nav_menu( array( 'theme_location' => 'footer-right-col2' ) );
            echo "</div>";

          echo "</div>";
        echo "<hr>";
      echo "</nav>";

      // Footer Menu section (not Megamenu)
      echo "<div class='l-flex-container flex-justify-between flex-align-center'>";
        echo "<div class='a-brand u-mg-right--xlg'>";
          echo "<img alt='GP' src='" . get_stylesheet_directory_uri() . "/img/logo.svg' style='width:1em'>";
            echo "<a href='" . home_url() . "/'>GeoPlatform.gov</a>";
          echo "</div>";

          echo "<div>";

            $geopccb_head_menu_array = array(
              'theme_location' => 'footer-bar',
              'container' => false,
              'echo' => false,
              'items_wrap' => '%3$s',
              'depth' => 0,
              'fallback_cb' => false,
              'link_class' => 'is-hidden--xs is-hidden--sm menu-border-foot bordered-right u-pd-right--md u-mg-right--md',
              'link_role' => 'menuitem',
            );

            // http://biostall.com/add-character-between-menu-items-in-wordpress-using-wp_nav_menu-function/
            echo strip_tags( wp_nav_menu( $geopccb_head_menu_array ), '<a>' );

            // Moves the "To Top" operation to the right of the Footer Bar if footer megamenu is disabled.
            if (get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'head' || get_theme_mod('megamenu_controls', $geopccb_theme_options['megamenu_controls']) == 'none'){
              echo "<a class='u-float--right' href='#'>";
                echo "<span class='fas fa-arrow-up'></span> to top";
              echo "</a>";
            }

          echo "</div>";
        echo "</div>";

      echo "<br>";

      echo "<small>";
        echo "The GeoPlatform was developed by the member agencies of the Federal Geographic Data Committee (FGDC) through collaboration with partners and stakeholders. The target audience for the GeoPlatform includes Federal agencies, State, local, and Tribal governments, private sector, academia, and the general public.";
      echo "</small>";

      echo "</footer>";
    echo "</div>";

  wp_footer();
  echo "</body>";
echo "</html>";
