<?php
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

  <?php wp_head();?>

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
                    <a class="btn btn-link dropdown-toggle is-hidden--xs" href="/data.html" id="explore-resources-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu">
                        Explore
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userSignInButton">
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
          $geopportal_front_user_redirect = esc_url($GLOBALS['geopccb_accounts_url']);

          if (!empty($geopportal_current_user->user_firstname) && !empty($geopportal_current_user->user_lastname))
            $geopportal_front_username_text = $geopportal_current_user->user_firstname . " " . $geopportal_current_user->user_lastname;
          elseif (!empty($geopportal_current_user->user_firstname) && empty($geopportal_current_user->user_lastname))
            $geopportal_front_username_text = $geopportal_current_user->user_firstname;
          elseif (empty($geopportal_current_user->user_firstname) && !empty($geopportal_current_user->user_lastname))
            $geopportal_front_username_text = $geopportal_current_user->user_lastname;
          else
            $geopportal_front_username_text = $geopportal_current_user->user_login;

          $geopportal_front_loginname_text = $geopportal_current_user->user_login;
          $geopportal_front_user_redirect = esc_url($GLOBALS['geopccb_accounts_url']);
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

    <script>
      // jQuery(document).ready(function() {
      //   jQuery("#userSignInButton").click(function(e){
      //     if (jQuery("#geopportal_header_user_dropdown_parent").hasClass("show")){
      //       jQuery("#geopportal_header_user_dropdown_parent").removeClass("show");
      //       jQuery("#geopportal_header_user_dropdown_child").removeClass("show");
      //     }
      //     else {
      //       jQuery("#geopportal_header_user_dropdown_parent").addClass("show");
      //       jQuery("#geopportal_header_user_dropdown_child").addClass("show");
      //     }
      //   });
      // });
    </script>

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
        <?php }

        // Search works but currently breaks CSS if the form is used. Uncomment when CSS for it is ready.

        // Ensures that the search bar will only appear if the associated plugin is active.
    		if (in_array( 'geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array() ) )){
    		?>

        <!-- <form id="geoplatform_header_searchfield"> -->
          <!-- <div class="a-search">
            <div class="icon fas fa-search"></div>
            <form class="m-search-box" id="geoplatform_header_searchfield">
                <div class="input-group-slick">
                    <span class="icon fas fa-search"></span>
                    <input type="text" class="form-control" id="geoplatform_header_searchform" placeholder="SEARCH THE GEOPLATFORM">
                </div>
            </form>
          </div> -->
        <!-- </form> -->
        <?php
        }
        ?>
    </div>

    <!-- <script>
		// Triggers off of form submission and navigates to the geoplatform-search page with the search field params.
		  jQuery( "#geoplatform_header_searchfield" ).submit(function( event ) {
		    event.preventDefault();
		    window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatform_header_searchform').val();
		  });
		</script> -->


    <nav class="m-megamenu" id="header-megamenu">

        <div class="m-megamenu__content">

            <div class="col">
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
                        <a href="<?php echo $GLOBALS['geopccb_oe_url']; ?>" target="_blank">
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
                      <a href="<?php echo $GLOBALS['geopccb_ckan_url']; ?>" target="_blank">
                          Search Data.gov
                          <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="<?php echo $GLOBALS['geopccb_ckan_mp_url']; ?>" target="_blank">
                          Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                  <li role="menuitem">
                      <a href="<?php echo $GLOBALS['geopccb_cms_url']; ?>" target="_blank">
                          Resources <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                      </a>
                  </li>
                </ul>
                <br>
                <a class="m-megamenu__heading" href="<?php echo home_url(get_theme_mod('headlink_apps')); ?>">Apps &amp; Services</a>
                <ul class="menu" role="menu">
                    <li role="menuitem">
                        <a href="<?php echo $GLOBALS['geopccb_viewer_url']; ?>" target="_blank">
                            Map Viewer
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo $GLOBALS['geopccb_maps_url']; ?>" target="_blank">
                            Map Manager
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo $GLOBALS['geopccb_marketplace_url']; ?>" target="_blank">
                            Marketplace Preview
                            <sup><span class="glyphicon glyphicon-new-window"></span></sup>
                        </a>
                    </li>
                    <li role="menuitem">
                        <a href="<?php echo $GLOBALS['geopccb_dashboard_url'] . '#/lma?surveyId=8&amp;page=0&amp;size=500&amp;sortElement=title&amp;sortOrder=asc&amp;colorTheme=green'; ?>" target="_blank">
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
