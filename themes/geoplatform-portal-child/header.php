<?php
// Getting theme mods for search bar and mega-menu hiding checks.
$geopccb_theme_options = geop_ccb_get_theme_mods();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap core CSS
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

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

    <?php wp_head();?>
  </head>
<body>

  <!--  <% heading= '' %> -->

    <!-- code from partials/header.ejs -->
      <header class="t-transparent">
    <?php if ( is_page_template('page-templates/page_style-guide.php')) { ?>

      <?php } else{?>

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

                          <a href="<?php echo $geopportal_redirect ?>"><?php echo $geopportal_text ?></a>
                        </div>
                    </li>
                </ul>
                <h4 class="brand">
                  <a href="/" title="Go to the Geoplatform Home Page">
                      <span class="icon-gp"></span>
                      GeoPlatform <?php //if (is_user_logged_in()){ echo $current_user->display_name; }?>
                    </li>
                  </a>
                  <!-- This will be the "Site Title" in the Customizer Site Identity tab -->
<!--
                  <a href="/" title="Go to the <?php echo get_bloginfo( 'name' ); ?> Home Page">
                  <?php //echo get_bloginfo( 'name' ); ?>
-->
                </a>
                </h4>

            </div>
        </div>
  <?php } ?>

</header>
