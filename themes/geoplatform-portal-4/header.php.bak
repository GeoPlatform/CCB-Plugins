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
  <title>GP Theme v2</title>
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

  <?php wp_head();?>

</head>
<header class="o-header" role="banner">

    <div class="o-header__primary">

        <!--
            REMOVE THIS COMMENT WHEN IMPLEMENTING...
            Use H1 on .a-brand b/c 508 requires an H1 to appear near the top of
            a page for screen readers to know where to start. Note that the
            .a-brand class overrides any styles set by H1-H6, so no worries as
            long as no blanket styles are defined for H1-H6.
        -->
        <h1 class="a-brand">
            <img alt="GP" src="<?php echo get_stylesheet_directory_uri() . '/img/logo.svg' ?>" style="width:1em">
            <span>GeoPlatform.gov</span>
        </h1>

        <nav class="a-nav" role="navigation" aria-label="High-level navigation links" role="menu">
            <a role="menuitem" class="is-hidden--xs" href="<?php echo home_url() ?>/">Home</a>
            <div class="a-nav__collapsible-menu">
                <a role="menuitem" class="is-hidden--xs is-hidden--sm" href="<?php echo home_url(get_theme_mod('headlink_pages')); ?>">Pages</a>
                <a role="menuitem" class="is-hidden--xs is-hidden--sm" href="<?php echo home_url(get_theme_mod('headlink_portfolio')); ?>">Portfolio</a>
            </div>
            <a role="menuitem" class="is-hidden--xs" href="<?php echo home_url(get_theme_mod('headlink_help')); ?>">Help</a>
            <a role="menuitem" class="is-linkless" onclick="toggleClass('#header-megamenu','is-open')">
                <span class="is-hidden--xs">More</span>
                <span class="fas fa-bars is-hidden--sm is-hidden--md is-hidden--lg"></span>
            </a>

            <?php
            $geopportal_current_user = wp_get_current_user();
            $geopportal_text = "Sign In";
            $geopportal_redirect = esc_url($GLOBALS['geopccb_accounts_url']);
            if($geopportal_current_user->ID != 0) {
              if (!empty($geopportal_current_user->user_firstname) && !empty($geopportal_current_user->user_lastname))
                $geopportal_text = $geopportal_current_user->user_firstname . " " . $geopportal_current_user->user_lastname;
              elseif (!empty($geopportal_current_user->user_firstname) && empty($geopportal_current_user->user_lastname))
                $geopportal_text = $geopportal_current_user->user_firstname;
              elseif (empty($geopportal_current_user->user_firstname) && !empty($geopportal_current_user->user_lastname))
                $geopportal_text = $geopportal_current_user->user_lastname;
              else
                $geopportal_text = $geopportal_current_user->user_login;

              $geopportal_redirect = esc_url($GLOBALS['geopccb_accounts_url']);
            }
            ?>

            <a role="menuitem" href="<?php echo $geopportal_redirect ?>">
                <span class="fas fa-user"></span>
                <span class="is-hidden--xs"><?php echo $geopportal_text ?></span>
            </a>
        </nav>

    </div>

    <div class="o-header__secondary">

        <?php
        if (is_front_page()){ ?>
          <div class="a-page__title">Welcome to the GeoPlatform!</div>
        <?php } elseif (is_404()) { ?>
          <div class="a-page__title">Page Not Found</div>
        <?php } else { ?>
          <div class="a-page__title"><?php the_title(); ?></div>
        <?php }

        // Search works but currently breaks CSS if the form is used. Uncomment when CSS for it is ready.

        // Ensures that the search bar will only appear if the associated plugin is active.
    		if (in_array( 'geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array() ) )){
    		?>

        <!-- <form id="geoplatform_header_searchfield"> -->
          <div class="a-search">
            <div class="icon fas fa-search"></div>
            <form class="m-search-box" id="geoplatform_header_searchfield">
                <div class="input-group-slick">
                    <span class="icon fas fa-search"></span>
                    <input type="text" class="form-control" id="geoplatform_header_searchform" placeholder="SEARCH THE GEOPLATFORM">
                </div>
            </form>
          </div>
        <!-- </form> -->
        <?php
        }
        ?>
    </div>

    <script>
		// Triggers off of form submission and navigates to the geoplatform-search page with the search field params.
		  jQuery( "#geoplatform_header_searchfield" ).submit(function( event ) {
		    event.preventDefault();
		    window.location.href='geoplatform-search/#/?q='+jQuery('#geoplatform_header_searchform').val();
		  });
		</script>


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
