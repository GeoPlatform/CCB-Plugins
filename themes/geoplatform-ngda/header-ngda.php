<!--Uncomment below to add file to theme. Make sure to comment back out once it's created-->
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

    <!--http://themefoundation.com/wordpress-theme-customizer/ section 5.2 Radio Buttons-->
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
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

    <!-- code from partials/header.ejs -->
    <header class="t-transparent">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul role="menu" class="header__menu">
                    <!--<li><?php get_search_form(); ?></li>-->
                    <!-- <li> -->
                        <!-- mega menu toggle -->
                        <!-- <div class="btn-group">
                            <button type="button" class="btn btn-link header__btn dropdown-toggle"
                                    data-toggle="dropdown" data-target="#megamenu" aria-expanded="false">
                                <span class="icon-hamburger-menu t-light"></span>
                                <span class="hidden-xs">Menu <span class="caret"></span></span>
                            </button>
                        </div>
                    </li> -->

                    <!-- login button toggle -->
                    <!-- Disable for now, re-enable for authentication -->
                    <li class="hidden-xs">
                        <div class="btn-group">
                          <h4 class="brand">
                            <!-- <% if(!authenticated) { %> -->
                            <?php if (!is_user_logged_in()){?>
                              <a href="<?php echo wp_login_url( get_option('siteurl') ); ?>">
                                  <button style="color:white;" type="button" class="btn btn-link" onclick="login">Sign In</button>
                                </a>
                          <?php  } else {?>
                            <a href="<?php echo wp_logout_url( home_url() ); ?>">
                                <button style="color:white;" type="button" class="btn btn-link">Sign out</button>
                              </a>
                            <?php } ?>
                            </h4>
                        </div><!--btn-account btn-group-->

                    </li><!--hidden-xs-->

                </ul><!--role="menu" class="header__menu"-->
                <h4 class="brand">
                  <a href="<?php echo $GLOBALS['wpp_url']; ?>" title="Go to the Geoplatform Home Page">
                      <span class="icon-gp"></span>
                      GeoPlatform
                  </a>
                  <!-- This will be the "Site Title" in the Customizer Site Identity tab -->
                  <a href="<?php echo get_site_url();?>" title="Go to the <?php echo get_bloginfo( 'name' ); ?> Home Page">
                  <?php echo get_bloginfo( 'name' ); ?>
                </a>
                </h4>

            </div>
        </div>


</header>
