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
?>
<div>
  <footer class="o-footer">

      <a class="u-float--right" href="#">
          <span class="fas fa-arrow-up"></span>
          to top
      </a>

      <div>
          <a onClick="toggleClass('#footer-megamenu','is-collapsed')">
              <span class="fas fa-angle-down"></span>
              Menu
          </a>
          <hr>
      </div>

      <nav class="m-megamenu is-collapsed" id="footer-megamenu">

          <div class="m-megamenu__content">

            <div class="col">

              <div class="d-lg-none d-xl-none">
                  <div class="m-megamenu__heading">Navigation</div>
                  <ul class="menu" role="menu">
                    <?php
                    if (get_theme_mod('searchbar_controls', $geopccb_theme_options['searchbar_controls']) == 'gp'){
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
            ?>
          </div>

          <hr>

      </nav>

      <div class="l-flex-container flex-justify-between flex-align-center">
          <div class="a-brand u-mg-right--xlg">
              <img alt="GP" src="<?php echo get_stylesheet_directory_uri() . '/img/logo.svg' ?>" style="width:1em">
              <a href="<?php echo home_url() ?>/">GeoPlatform.gov</a>
          </div>

          <div>
            <?php
            $geopccb_head_menu_array = array(
              'menu' => 'community-links',
              'container' => false,
              'echo' => false,
              'items_wrap' => '%3$s',
              'depth' => 0,
              'fallback_cb' => false,
              'link_class' => 'is-hidden--xs is-hidden--sm',
              'link_role' => 'menuitem',
            );

            // http://biostall.com/add-character-between-menu-items-in-wordpress-using-wp_nav_menu-function/
            echo str_replace('</a>' , '</a>&nbsp&nbsp;|&nbsp' , strip_tags( wp_nav_menu( $geopccb_head_menu_array ), '<a>' ));
            ?>
          </div>
      </div>

      <br>

      <small>
          The GeoPlatform was developed by the member agencies of the
          Federal Geographic Data Committee (FGDC) through collaboration
          with partners and stakeholders. The target audience for the
          GeoPlatform includes Federal agencies, State, local, and
          Tribal governments, private sector, academia, and the general
          public.
      </small>

  </footer>

</div>


<?php wp_footer();?>
</body>
</html>
