<?php
/**
 * Get Docker container enviroment variables
 *
 * @param [string] $name
 * @param [string] $def (default)
 * @return ENV[$name] or $def if none found
 */
function geop_ccb_getEnv($name, $def){
    return isset($_ENV[$name]) ? $_ENV[$name] : $def;
}

$maps_url = geop_ccb_getEnv('maps_url', 'https://maps.geoplatform.gov');
$viewer_url = geop_ccb_getEnv('viewer_url', 'https://viewer.geoplatform.gov');
$marketplace_url = geop_ccb_getEnv('marketplace_url',"https://marketplace.geoplatform.gov");
$dashboard_url = geop_ccb_getEnv('dashboard_url',"https://dashboard.geoplatform.gov/#/lma?surveyId=8&page=0&size=500&sortElement=title&sortOrder=asc&colorTheme=green");
$wpp_url = geop_ccb_getEnv('wpp_url',"https://geoplatform.gov");
$ual_url = geop_ccb_getEnv('ual_url',"https://ual.geoplatform.gov");
$ckan_mp_url = geop_ccb_getEnv('ckan_mp_url',"https://ckan.geoplatform.gov/#/?progress=planned&h=Marketplace");
$ckan_url = geop_ccb_getEnv('ckan_url',"https://ckan.geoplatform.gov/");
$cms_url = geop_ccb_getEnv('cms_url',"https://cms.geoplatform.gov/resources");
$idp_url = geop_ccb_getEnv('idp_url',"https://idp.geoplatform.gov");
$oe_url = geop_ccb_getEnv('oe_url',"https://oe.geoplatform.gov");
$sd_url = geop_ccb_getEnv('sd_url',"servicedesk@geoplatform.gov");
$ga_code = geop_ccb_getEnv('ga_code','UA-00000000-0');


//-------------------------------
// Add scripts and stylesheets
//-------------------------------
//https://www.taniarascia.com/wordpress-from-scratch-part-two/
function geop_ccb_scripts() {
  	wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrap-css',get_template_directory_uri() . '/css/bootstrap.css');
	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/css/Geomain_style.css' );
	wp_enqueue_script( 'bootstrap-min', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '3.3.7', true);
	//wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array(), '3.3.7', true);
	wp_enqueue_script( 'geoplatform-ccb-js', get_template_directory_uri() . '/js/geoplatform.style.js', array('jquery'), null, true );
  	wp_enqueue_script( 'geoplatform-ccb-min', get_template_directory_uri() . '/js/geoplatform.style.min.js', array('jquery'), null, true );
	wp_enqueue_script( 'auth', get_template_directory_uri() . '/scripts/authentication.js', array(), null, true);
	wp_enqueue_script( 'fixedScroll', get_template_directory_uri() . '/scripts/fixed_scroll.js', array(), null, true);
	wp_enqueue_script( 'ajax-pagination',  get_template_directory_uri() . '/js/ajax-pagination.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'geop_ccb_scripts' );

//-------------------------------
// Add Google Analytics
//http://www.wpbeginner.com/beginners-guide/how-to-install-google-analytics-in-wordpress/
//-------------------------------
function geop_ccb_add_googleanalytics(){ ?>
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	var php_ga_code = "<?php echo $GLOBALS['ga_code']; ?>"

	ga('create', php_ga_code , 'auto');
	ga('send', 'pageview');
	</script>
<?php
}
add_action('wp_head','geop_ccb_add_googleanalytics');

//-------------------------------
// Add Google Lato Fonts
//-------------------------------
function geop_ccb_google_fonts() {
				wp_register_style('Lato/Slabo', 'https://fonts.googleapis.com/css?family=Lato:400,700|Slabo+27px');
				wp_enqueue_style( 'Lato/Slabo');
		}
add_action('wp_enqueue_scripts', 'geop_ccb_google_fonts');

function geop_ccb_setup(){
  /*
  * Make theme available for translation.
  * If you're building a theme based on Geoplatform CCB, use a find and replace
  * to change 'geoplatform-ccb' to the name of your theme in all the template files.
  */
  load_theme_textdomain( 'geoplatform-ccb' );

  /*
  * WordPress Titles
  */
  add_theme_support( 'title-tag' );

  /*
  * Support for a custom header Images
  */
  $header_args = array(
  	'default-image' => get_template_directory_uri() . '/img/default-banner.png',
    'uploads'       => true,
    );
  add_theme_support( 'custom-header', $header_args);

  /*
  * Support Featured Images
  */
  add_theme_support( 'post-thumbnails' );

  /*
  * Theme Support for Automatic Feed links per theme check
  * https://codex.wordpress.org/Automatic_Feed_Links
  */
  add_theme_support( 'automatic-feed-links' );

  //-------------------------------
  //Theme Support for html5, and the html5 search form
  //https://developer.wordpress.org/reference/functions/get_search_form/
  //http://buildwpyourself.com/wordpress-search-form-template/
  //-------------------------------
  add_theme_support( 'html5', array( 'search-form' ) );

  add_theme_support('starter-content', array(
    // Starter menus (see gp_create_services_menu)
	'nav_menus' => array(
			'header-left' => array(
				'name' => __('My Test Navigation', 'geoplatform-ccb'),
				'items' =>  array(
					'link_map_view'   =>  array(
						'title' =>  _x( 'Map Viewer', 'Theme starter Content', 'geoplatform-ccb' ),
						'url'   =>  $GLOBALS['viewer_url'],
					),
					'link_map_man'    =>  array(
						'title' =>  _x( 'Map Manager', 'Theme starter Content', 'geoplatform-ccb' ),
						'url'   =>  $GLOBALS['maps_url'],
					),
					'link_marketplace'    =>  array(
						'title' =>  _x( 'Marketplace Preview', 'Theme starter Content', 'geoplatform-ccb' ),
						'url'   =>  $GLOBALS['marketplace_url'],
					),
					'link_perf_dash'    =>  array(
						'title' =>  _x( 'Performance Dashboard', 'Theme starter Content', 'geoplatform-ccb' ),
						'url'   =>  $GLOBALS['dashboard_url'],
					),
					'link_ckan'    =>  array(
						'title' =>  _x( 'Search Catalog', 'Theme starter Content', 'geoplatform-ccb' ),
						'url'   =>  $GLOBALS['ckan_url'],
					),
					'link_ckan_mp'    =>  array(
						'title' =>  _x( 'Search Marketplace', 'Theme starter Content', 'geoplatform-ccb' ),
						'url'   =>  $GLOBALS['ckan_mp_url'],
					),
				),
			),
		),
	'attachments' => array(
			'image-banner' => array(
				'post_title' => _x( 'Banner', 'Theme starter content', 'geoplatform-ccb' ),
				'file' => '/img/placeholder-banner.png',
			),
			'image-category' => array(
				'post_title' => _x( 'Category', 'Theme starter content', 'geoplatform-ccb' ),
				'file' => '/img/default-category-photo.jpeg',
			),
		),
	'posts' => array(
		'home',
		'about',
		'blog',
	),
	'options' => array(
		'show_on_front' => 'posts',
		'page_on_front' => '{{home}}',
		'page_for_posts' => '{{blog}}',
		),
	'widgets' => array(
		'geoplatform-widgetized-area' => array(
				'text_about',
			),
		),
	)
  );
}
add_action('after_setup_theme', 'geop_ccb_setup');

//Use for testing purposes to enable Fresh site and load starter content after switching the theme in and out
function geop_ccb_fresh_site_enable(){
  update_option('fresh_site', 1);
}
add_action('switch_theme','geop_ccb_fresh_site_enable');


//------------------------------------
//Support for a custom logo image
// https://developer.wordpress.org/themes/functionality/custom-logo/
//------------------------------------
// add_theme_support( 'custom-logo' );
function geop_ccb_custom_logo_setup() {
    $geop_ccb_logo_defaults = array(
        'height'      => 40,
        'width'       => 40,
        'flex-height' => false,
        'flex-width'  => false
    );
    add_theme_support( 'custom-logo', $geop_ccb_logo_defaults );
}
add_action( 'after_setup_theme', 'geop_ccb_custom_logo_setup' );


//--------------------------
//Support adding Menus for header and footer
//https://premium.wpmudev.org/blog/add-menus-to-wordpress/?utm_expid=3606929-97.J2zL7V7mQbSNQDPrXwvBgQ.0&utm_referrer=https%3A%2F%2Fwww.google.com%2F
//--------------------------
function geop_ccb_register_menus() {
  register_nav_menus(
    array(
			'community-links' => 'Community Links',
			'header-left' => 'Header Menu - Left Column',
      'header-center' => 'Header Menu - Center Column',
      'header-right-col1' => 'Header Menu - Right Column 1',
			'header-right-col2' => 'Header Menu - Right Column 2',
			'footer-left' => 'Footer Menu - Left Column',
      'footer-center' => 'Footer Menu - Center Column',
      'footer-right-col1' => 'Footer Menu - Right Column 1',
			'footer-right-col2' => 'Footer Menu - Right Column 2'
    )
  );
}
add_action( 'init', 'geop_ccb_register_menus' );


function geop_ccb_create_services_menu(){
//pre-filling menu items
//https://codex.wordpress.org/Function_Reference/wp_create_nav_menu
// Check if the menu exists
$menu_name = 'Apps & Services';
$menu_exists = wp_get_nav_menu_object( $menu_name );

// If it doesn't exist, let's create it.
if( !$menu_exists){
		$menu_id = wp_create_nav_menu($menu_name);

	// Set up default menu items
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Map Viewer <sup><span class="glyphicon glyphicon-new-window"></span></sup>', 'geoplatform-ccb'),
			'menu-item-url' => $GLOBALS['viewer_url'],
			'menu-item-status' => 'publish',
			'menu-item-type' => 'custom',
			'menu-item-target'=> '_blank',
			));
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Map Manager <sup><span class="glyphicon glyphicon-new-window"></span></sup>', 'geoplatform-ccb'),
			'menu-item-url' => $GLOBALS['maps_url'],
			'menu-item-status' => 'publish',
			'menu-item-type' => 'custom',
			'menu-item-target'=> '_blank',
			));
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Marketplace Preview <sup><span class="glyphicon glyphicon-new-window"></span></sup>', 'geoplatform-ccb'),
			'menu-item-url' => $GLOBALS['marketplace_url'],
			'menu-item-status' => 'publish',
			'menu-item-type' => 'custom',
			'menu-item-target'=> '_blank',
			));
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Performance Dashboard <sup><span class="glyphicon glyphicon-new-window"></span></sup>', 'geoplatform-ccb'),
			'menu-item-url' => $GLOBALS['dashboard_url'],
			'menu-item-status' => 'publish',
			'menu-item-type' => 'custom',
			'menu-item-target'=> '_blank',
			));
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Search Catalog <sup><span class="glyphicon glyphicon-new-window"></span></sup>', 'geoplatform-ccb'),
			'menu-item-url' => $GLOBALS['ckan_url'],
			'menu-item-status' => 'publish',
			'menu-item-type' => 'custom',
			'menu-item-target'=> '_blank',
			));
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Search Marketplace <sup><span class="glyphicon glyphicon-new-window"></span></sup>', 'geoplatform-ccb'),
			'menu-item-url' => $GLOBALS['ckan_mp_url'],
			'menu-item-status' => 'publish',
			'menu-item-type' => 'custom',
			'menu-item-target'=> '_blank',
			));

		//Get theme locations and set this menu to those locations
		//https://rochcass.wordpress.com/2016/01/14/wordpress-create-menu-locations-and-assigning-menus-programmatically/
		$locations = get_theme_mod('nav_menu_locations');
		$locations['footer-center'] = $menu_id;
		$locations['header-center'] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations);
	}
}
add_action('init', 'geop_ccb_create_services_menu');

/********************************************************/
// Adding Dashicons in WordPress Front-end
/********************************************************/
add_action( 'wp_enqueue_scripts', 'geop_ccb_load_dashicons_front_end' );
function geop_ccb_load_dashicons_front_end() {
  wp_enqueue_style( 'dashicons' );
}

//---------------------------------------
//Supporting Theme Customizer editing
//https://codex.wordpress.org/Theme_Customization_API
//--------------------------------------
function geop_ccb_customize_register( $wp_customize )
{
		//color section, settings, and controls
    $wp_customize->add_section( 'header_color_section' , array(
        'title'    => __( 'Header Color Section', 'geoplatform-ccb' ),
        'priority' => 30
    ) );

		//h1 color setting and control
		$wp_customize->add_setting( 'header_color_setting' , array(
				'default'   => '#000000',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_link_color', array(
				'label'    => __( 'Header1 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header_color_setting',
		) ) );

		//h2 color setting and control
		$wp_customize->add_setting( 'header2_color_setting' , array(
				'default'   => '#000000',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h2_link_color', array(
				'label'    => __( 'Header 2 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header2_color_setting',
		) ) );

		//h3 color setting and control
		$wp_customize->add_setting( 'header3_color_setting' , array(
				'default'   => '#000000',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h3_link_color', array(
				'label'    => __( 'Header 3 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header3_color_setting',
		) ) );

		//h4 color setting and control
		$wp_customize->add_setting( 'header4_color_setting' , array(
				'default'   => '#000000',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h4_link_color', array(
				'label'    => __( 'Header 4 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header4_color_setting',
		) ) );

    //link (<a>) color and control
		$wp_customize->add_setting( 'link_color_setting' , array(
				'default'   => '#428bca',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'a_link_color', array(
				'label'    => __( 'Link Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'link_color_setting',
		) ) );

		//.brand color and control
		$wp_customize->add_setting( 'brand_color_setting' , array(
				'default'   => '#fff',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'brand_color', array(
				'label'    => __( 'Brand Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'brand_color_setting',
		) ) );



		//Fonts section, settings, and controls
		//http://themefoundation.com/wordpress-theme-customizer/ section 5.2 Radio Buttons
		$wp_customize->add_section( 'font_section' , array(
				'title'    => __( 'Font Section', 'geoplatform-ccb' ),
				'priority' => 50
			) );

		$wp_customize->add_setting('font_choice',array(
        'default' => 'lato',
				'sanitize_callback' => 'geop_ccb_sanitize_fonts',
    	));

		$wp_customize->add_control('font_choice',array(
        'type' => 'radio',
        'label' => 'Fonts',
        'section' => 'font_section',
        'choices' => array(
            'lato' => __('Lato', 'geoplatform-ccb'),
            'slabo' => __('Slabo',  'geoplatform-ccb')
						),
				));

		//Banner Intro Text editor section, settings, and controls
		// pulled from https://wpshout.com/making-themes-more-wysiwyg-with-the-wordpress-customizer/
		//fixed some issues with linking up through https://github.com/paulund/wordpress-theme-customizer-custom-controls/issues/4
		$wp_customize->add_section( 'banner_text_section' , array(
				'title'    => __( 'Banner Area', 'geoplatform-ccb' ),
				'priority' => 50
			) );

    $geop_ccb_default_title = "<h1 style='text-align: center; color:white;'>" . __( 'Your Community Title', 'geoplatform-ccb') . "</h1><p style='text-align: center'>" . __( 'Create and manage your own Dynamic Digital Community on the GeoPlatform!', 'geoplatform-ccb') . "</p>";
         // Add a text editor control
         require_once dirname(__FILE__) . '/text/text-editor-custom-control.php';
         $wp_customize->add_setting( 'text_editor_setting', array(
             'default'   => $geop_ccb_default_title,
						 'transport' => 'refresh',
						 'sanitize_callback' => 'wp_kses_post'
         ) );
         $wp_customize->add_control( new Text_Editor_Custom_Control( $wp_customize, 'text_editor_setting', array(
             'label'   => __( 'Banner Text Editor', 'geoplatform-ccb' ),
             'section' => 'banner_text_section',
             'settings'   => 'text_editor_setting',
             'priority' => 10
         ) ) );

				 //Call to action button (formerly "Learn More" button)
				 $wp_customize->add_setting('call2action_button', array(
					 'default' => true,
					 'transport' => 'refresh',
           'sanitize_callback' => 'geop_ccb_sanitize_checkbox'
				 ) );

				 $wp_customize->add_control('call2action_button', array(
					 'section' => 'banner_text_section',
					 'label' =>__( 'Show Call to Action button?', 'geoplatform-ccb' ),
					 'type' => 'checkbox',
					 'priority' => 20,
				 ) );

				 $wp_customize->add_setting('call2action_text', array(
					 'default' => 'Learn More',
					 'transport' => 'refresh',
					 'sanitize_callback' => 'sanitize_text_field',
				 ));
				 $wp_customize->add_control('call2action_text', array(
					 'section' => 'banner_text_section',
					 'label' =>__( 'Button Text', 'geoplatform-ccb' ),
					 'type' => 'text',
					 'priority' => 30,
					 'input_attrs' => array(
						'placeholder' 		=> __( 'Place your text for the button here...', 'geoplatform-ccb' ),
					),
				 ) );

				 $wp_customize->add_setting('call2action_url', array(
					'default' => 'https://geoplatform.gov/about',
					'transport' => 'refresh',
					'sanitize_callback' => 'esc_url_raw',
				));
				$wp_customize->add_control('call2action_url', array(
					'section' => 'banner_text_section',
					'label' =>__( 'Button URL', 'geoplatform-ccb' ),
					'type' => 'URL',
					'priority' => 40,
					'input_attrs' => array(
					 'placeholder' 		=> __( 'Place your url for the button here...', 'geoplatform-ccb' ),
				 ),
				) );

				//Map Gallery Custom link section, settings, and controls
			$wp_customize->add_section( 'map_gallery_section' , array(
				'title'    => __( 'Map Gallery', 'geoplatform-ccb' ),
				'priority' => 60
			) );
			$wp_customize->add_setting( 'Map_Gallery_link_box' , array(
					'default'   => 'https://ual.geoplatform.gov/api/galleries/6c47d5d45264bedce3ac13ca14d0a0f7',
					'transport' => 'refresh',
					'sanitize_callback' => 'sanitize_text_field'
				) );
			$wp_customize->add_control( 'Map_Gallery_link_box', array(
					'label' => 'Map Gallery link',
					'section' => 'map_gallery_section',
					'description' => 'Make sure you use a full UAL link. Example: https://ual.geoplatform.gov/api/galleries/{your map gallery ID}',
					'type' => 'url',
					'priority' => 10
				) );

				//Add radio button to choose link style between envs (sit, stg, or prod)
				$wp_customize->add_setting( 'Map_Gallery_env_choice' , array(
						'default'   => 'prod',
						'transport' => 'refresh',
						'sanitize_callback' => 'geop_ccb_sanitize_mapchoice'
					) );
				$wp_customize->add_control( 'Map_Gallery_env_choice', array(
						'label' => 'Map Gallery Environment',
						'description' => __( 'If your gallery link above does not match the enviroment (sit, stg, or prod) the site is currently in, please change this setting to match.', 'geoplatform-ccb'),
						'section' => 'map_gallery_section',
						'type' => 'radio',
						'priority' => 20,
						'choices' => array(
								'match'=>__( 'My gallery link matches my site enviroment', 'geoplatform-ccb'),
								'sit' => 'sit (sit-ual.geoplatform.us)',
								'stg' => 'stg (stg-ual.geoplatform.gov)',
								'prod' => 'prod (ual.geoplatform.gov)'
								)
					) );

				//remove default colors section as Header Color Section does this job better
				 $wp_customize->remove_section( 'colors' );

				 //Remove default Menus and Static Front page sections as this theme doesn't utilize them at this time
				 //$wp_customize->remove_panel( 'nav_menus');
				 $wp_customize->remove_section( 'static_front_page' );

				 //remove site tagline and checkbox for showing site title and tagline from Site Identity section
				 //; Not needed for the theme
				 $wp_customize->remove_control('blogdescription');
				 $wp_customize->remove_control('display_header_text');

}
add_action( 'customize_register', 'geop_ccb_customize_register');

//-------------------------------
//Sanitization callbak functions for customizer
//https://themeshaper.com/2013/04/29/validation-sanitization-in-customizer/
//-------------------------------
function geop_ccb_sanitize_fonts( $value ) {
    if ( ! in_array( $value, array( 'lato', 'slabo' ) ) )
        $value = 'lato';
    return $value;
}

function geop_ccb_sanitize_mapchoice( $value ) {
    if ( ! in_array( $value, array( 'match', 'sit', 'stg', 'prod' ) ) )
        $value = 'match';
    return $value;
}

function geop_ccb_sanitize_checkbox( $checked ){
    //returns true if checkbox is checked
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

//-------------------------------
//getting Enqueue script for custom customize control.
//-------------------------------
 //https://codex.wordpress.org/Plugin_API/Action_Reference/customize_controls_enqueue_scripts
function geop_ccb_custom_customize_enqueue() {
	wp_enqueue_script( 'custom-customize', get_template_directory_uri() . '/customizer/customizer.js', array( 'jquery', 'customize-controls' ), false, true );
}
add_action( 'customize_controls_enqueue_scripts', 'geop_ccb_custom_customize_enqueue' );

//-------------------------------
//Dynamically show the colors changing
//-------------------------------
//needs to have 'transport' => 'refresh' in add_setting() above in order to work
//https://codex.wordpress.org/Theme_Customization_API#Part_2:_Generating_Live_CSS
function geop_ccb_header_customize_css()
{
    ?>
         <style type="text/css">
             h1 { color: <?php echo get_theme_mod('header_color_setting', '#000000'); ?>; }
						 h2 { color: <?php echo get_theme_mod('header2_color_setting', '#000000'); ?>!important; }
						 h3 { color: <?php echo get_theme_mod('header3_color_setting', '#000000'); ?>; }
						 h4, .section--linked .heading .title { color: <?php echo get_theme_mod('header4_color_setting', '#000000'); ?>; }
						 .text-selected, .text-active, a, a:visited { color: <?php echo get_theme_mod('link_color_setting', '#428bca'); ?>; }
						 header.t-transparent .brand>a { color: <?php echo get_theme_mod('brand_color_setting', '#fff'); ?>; }

         </style>
    <?php
}
add_action( 'wp_head', 'geop_ccb_header_customize_css');

//-------------------------------
//Override banner background-image as the custom header
//-------------------------------
//https://codex.wordpress.org/Function_Reference/wp_add_inline_style
function geop_ccb_header_image_method() {
	wp_enqueue_style(
		'custom-style',
		get_template_directory_uri() . '/css/Geomain_style.css'
	);
			$headerImage = get_header_image();
        $custom_css = "
                .banner{
                        background-image: url({$headerImage});
                }";
        wp_add_inline_style( 'custom-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'geop_ccb_header_image_method' );

//-------------------------------
//Give page and post banners a WYSIWYG editor
//-------------------------------
//http://help4cms.com/add-wysiwyg-editor-in-wordpress-meta-box/
define('WYSIWYG_META_BOX_ID', 'my-editor');
add_action('admin_init', 'wysiwyg_register_custom_meta_box');
function wysiwyg_register_custom_meta_box()
 {
 add_meta_box(WYSIWYG_META_BOX_ID, __('Banner Area Custom Content', 'geoplatform-ccb') , 'geop_ccb_custom_wysiwyg', 'post');
 add_meta_box(WYSIWYG_META_BOX_ID, __('Banner Area Custom Content', 'geoplatform-ccb') , 'geop_ccb_custom_wysiwyg', 'page');
 }

function geop_ccb_custom_wysiwyg($post)
 {
 echo "<h3>" . __( 'Anything you add below will show up in the Banner:', 'geoplatform-ccb') . "</h3>";
 $content = get_post_meta($post->ID, 'geop_ccb_custom_wysiwyg', true);
 wp_editor(htmlspecialchars_decode($content) , 'geop_ccb_custom_wysiwyg', array(
 "media_buttons" => true
 ));
 }

function geop_ccb_custom_wysiwyg_save_postdata($post_id)
 {
 if (!empty($_POST['geop_ccb_custom_wysiwyg']))
 {
 $data = htmlspecialchars_decode($_POST['geop_ccb_custom_wysiwyg']);
 update_post_meta($post_id, 'geop_ccb_custom_wysiwyg', $data);
 }
 }
add_action('save_post', 'geop_ccb_custom_wysiwyg_save_postdata');


//Making Category description pages WYSIWYG
//https://paulund.co.uk/add-tinymce-editor-category-description

remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

add_filter('edit_category_form_fields', 'geop_ccb_cat_description');
function geop_ccb_cat_description($tag)
{
	?>
			<!-- <table class="form-table"> -->
					<tr class="form-field">
							<th scope="row" valign="top"><label for="description">Description</label></th>
							<td>
							<?php
									$settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' );
									wp_editor(wp_kses_post($tag->description , ENT_QUOTES, 'UTF-8'), 'geop_ccb_cat_description', $settings);
							?>
							<br />
							<span class="description">The description is not prominent by default; however, some themes may show it.</span>
							</td>
					</tr>
			<!-- </table> -->
	<?php
}

add_action('admin_head', 'geop_ccb_remove_default_category_description');
function geop_ccb_remove_default_category_description()
{
    global $current_screen;
    if ( $current_screen->id == 'edit-category' )
    {
    ?>
        <script type="text/javascript">
        jQuery(function($) {
            $('textarea#description').closest('tr.form-field').remove();
        });
        </script>
    <?php
    }
}


//-------------------------------
//Add extra boxes to Category editor
//-------------------------------
//https://en.bainternet.info/wordpress-category-extra-fields/

//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'geop_ccb_extra_category_fields_forms');

//add extra fields to category edit form callback function
function geop_ccb_extra_category_fields_forms( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>
  <!-- Topic 1 Name and Url -->
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-name1"><?php _e( 'Topic 1 Name', 'geoplatform-ccb'); ?></label></th>
  		<td style="padding: 5px 5px;">
  			<input type="text" name="Cat_meta[topic-name1]" id="Cat_meta[topic-name1]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name1'] ? $cat_meta['topic-name1'] : ''; ?>">
  		</td>
  </tr>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-url1"><?php _e( 'Topic 1 URL', 'geoplatform-ccb'); ?></label></th>
  <td style="padding: 5px 5px;">
  <input type="text" name="Cat_meta[topic-url1]" id="Cat_meta[topic-url1]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url1'] ? $cat_meta['topic-url1'] : ''; ?>"><br />
      </td>
  </tr>

  <!-- Topic 2 Name and Url -->
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-name2"><?php _e( 'Topic 2 Name', 'geoplatform-ccb'); ?></label></th>
  		<td style="padding: 5px 5px;">
  			<input type="text" name="Cat_meta[topic-name2]" id="Cat_meta[topic-name2]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name2'] ? $cat_meta['topic-name2'] : ''; ?>">
  		</td>
  </tr>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-url2"><?php _e( 'Topic 2 URL', 'geoplatform-ccb'); ?></label></th>
  <td style="padding: 5px 5px;">
  <input type="text" name="Cat_meta[topic-url2]" id="Cat_meta[topic-url2]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url2'] ? $cat_meta['topic-url2'] : ''; ?>">
      </td>
  </tr>

  <!-- Topic 3 Name and Url -->
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-name3"><?php _e( 'Topic 3 Name', 'geoplatform-ccb'); ?></label></th>
  		<td style="padding: 5px 5px;">
  			<input type="text" name="Cat_meta[topic-name3]" id="Cat_meta[topic-name3]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name3'] ? $cat_meta['topic-name3'] : ''; ?>">
  		</td>
  </tr>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-url3"><?php _e( 'Topic 3 URL', 'geoplatform-ccb'); ?></label></th>
  <td style="padding: 5px 5px;">
  <input type="text" name="Cat_meta[topic-url3]" id="Cat_meta[topic-url3]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url3'] ? $cat_meta['topic-url3'] : ''; ?>"><br />
        </td>
  </tr>

  <!-- Topic 4 Name and Url -->
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-name4"><?php _e( 'Topic 4 Name', 'geoplatform-ccb'); ?></label></th>
  		<td style="padding: 5px 5px;">
  			<input type="text" name="Cat_meta[topic-name4]" id="Cat_meta[topic-name4]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name4'] ? $cat_meta['topic-name4'] : ''; ?>">
  		</td>
  </tr>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-url4"><?php _e( 'Topic 4 URL', 'geoplatform-ccb'); ?></label></th>
  <td style="padding: 5px 5px;">
  <input type="text" name="Cat_meta[topic-url4]" id="Cat_meta[topic-url4]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url4'] ? $cat_meta['topic-url4'] : ''; ?>"><br />
      </td>
  </tr>

  <!-- Topic 5 Name and Url -->
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-name5"><?php _e( 'Topic 5 Name', 'geoplatform-ccb'); ?></label></th>
  		<td style="padding: 5px 5px;">
  			<input type="text" name="Cat_meta[topic-name5]" id="Cat_meta[topic-name5]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name5'] ? $cat_meta['topic-name5'] : ''; ?>">
  		</td>
  </tr>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="topic-url5"><?php _e( 'Topic 5 URL', 'geoplatform-ccb'); ?></label></th>
  <td style="padding: 5px 5px;">
  <input type="text" name="Cat_meta[topic-url5]" id="Cat_meta[topic-url5]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url5'] ? $cat_meta['topic-url5'] : ''; ?>"><br />
      </td>
  </tr>
<?php
}

// save extra category fields hook
add_action ( 'edited_category', 'geop_ccb_save_extra_category_fields');
// save extra category fields callback function
function geop_ccb_save_extra_category_fields( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}

//Adding Categories and Tag functionality to pages (for frontpage setting)
//https://stackoverflow.com/questions/14323582/wordpress-how-to-add-categories-and-tags-on-pages
function geop_ccb_page_cat_tag_settings() {
// Add tag metabox to page
register_taxonomy_for_object_type('post_tag', 'page');
// Add category metabox to page
register_taxonomy_for_object_type('category', 'page');
}
// Add to the admin_init hook of your theme functions.php file
add_action( 'init', 'geop_ccb_page_cat_tag_settings' );

// ensure all tags and categories are included in queries
function geop_ccb_tags_categories_support_query($wp_query) {
  if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
  if ($wp_query->get('category_name')) $wp_query->set('post_type', 'any');
}
add_action('pre_get_posts', 'geop_ccb_tags_categories_support_query');


//-------------------------------
// Widgetizing the theme
// https://codex.wordpress.org/Function_Reference/dynamic_sidebar
// https://www.elegantthemes.com/blog/tips-tricks/how-to-manage-the-wordpress-sidebar
//------------------------------------
add_action( 'widgets_init', 'geop_ccb_sidebar' );
function geop_ccb_sidebar() {
    register_sidebar(
        array(
            'id' => 'geoplatform-widgetized-area',
            'name' => __( 'Sidebar Widgets', 'geoplatform-ccb' ),
            'description' => __( 'Widgets that go in the sidebar can be added here', 'geoplatform-ccb' ),
            'class' => 'widget-class',
            'before_widget' => '<div id="%1$s" class="card widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
						'after_title'   => '</h4>'
        )
    );
}

//-------------------------------
//Global Content Width
//per https://codex.wordpress.org/Content_Width#Adding_Theme_Support
//-------------------------------
if ( ! isset( $content_width ) ) {
	$content_width = 600;
}

//-------------------------------
// Theme specific enabled capabilities
//https://codex.wordpress.org/Function_Reference/add_cap
//https://codex.wordpress.org/Roles_and_Capabilities
//-------------------------------
function geop_ccb_add_theme_caps(){
	// gets the roles
	$subRole = get_role('subscriber');
	$contribRole = get_role('contributor');
	$authRole = get_role( 'author' );
	$editorRole = get_role('editor');

		//Allows these roles to read private pages
		$contribRole->add_cap('read_private_pages');
		$authRole->add_cap('read_private_pages');

		//Allows these roles to read private posts
		$contribRole->add_cap('read_private_posts');
		$authRole->add_cap('read_private_posts');

		//Allows these roles to edit pages
		$authRole->add_cap('edit_pages');

		//Allows these roles to edit published pages
		$authRole->add_cap('edit_published_pages');

		//Allows these roles to publish pages
		$authRole->add_cap('publish_pages');

		//Allows these roles to delete pages
		$authRole->add_cap('delete_pages');

		//Allows these roles to delete published pages
		$authRole->add_cap('delete_published_pages');

 		//Allows these roles to use Customizer
		$editorRole->add_cap('customize');

		//Allow access to “Widgets”, “Menus”, “Customize”, “Background” and “Header” under “Appearance”
		$editorRole->add_cap('edit_theme_options');

		//Allows these roles to edit WP Dashboard layout
		$editorRole->add_cap('edit_dashboard');

		//Allows these roles to see list of users on site
		$editorRole->add_cap('list_users');

		//Allows these roles to edit themes on the site
		$editorRole->add_cap('edit_theme');

    //Allows these roles to upload files on the site
		$contribRole->add_cap('upload_files');
}
add_action( 'admin_init', 'geop_ccb_add_theme_caps' );

//------------------
// Capabilities removed on Deactivation
//https://codex.wordpress.org/Plugin_API/Action_Reference/switch_theme
//--------------------
function geop_ccb_remove_theme_caps() {
	// Theme is deactivated
	// Need to remove these capabilities from the database
	// gets the roles
	$subRole = get_role('subscriber');
	$contribRole = get_role('contributor');
	$authRole = get_role( 'author' );
	$editorRole = get_role('editor');

	// Remove the capability when theme is deactivated
	$contribRole->remove_cap('read_private_pages');
	$authRole->remove_cap('read_private_pages');
	$subRole->remove_cap('read_private_pages');

	//Disallows these roles to read private posts
	$contribRole->remove_cap('read_private_posts');
	$authRole->remove_cap('read_private_posts');
	$subRole->remove_cap('read_private_posts');

	//Disallows these roles to edit pages
	$authRole->remove_cap('edit_pages');

	//Disallows these roles to edit published pages
	$authRole->remove_cap('edit_published_pages');

	//Disallows these roles to publish pages
	$authRole->remove_cap('publish_pages');

	//Disallows these roles to delete pages
	$authRole->remove_cap('delete_pages');

	//Disallows these roles to delete published pages
	$authRole->remove_cap('delete_published_pages');

	//Disallows these roles to use Customizer
	$editorRole->remove_cap('customize');

	//Disallow access to “Widgets”, “Menus”, “Customize”, “Background” and “Header” under “Appearance”
	$editorRole->remove_cap('edit_theme_options');

	//Disallows these roles to edit WP Dashboard layout
	$editorRole->remove_cap('edit_dashboard');

	//Disallows these roles to see list of users on site
	$editorRole->remove_cap('list_users');

	//Disallows these roles to edit themes on the site
	$editorRole->remove_cap('edit_theme');

  //Allows these roles to upload files on the site
  $contribRole->remove_cap('upload_files');
}
add_action('switch_theme', 'geop_ccb_remove_theme_caps');


//private pages and posts show up in search for correct roles
//https://wordpress.stackexchange.com/questions/110569/private-posts-pages-search
function geop_ccb_filter_search($query){
    if( is_admin() || ! $query->is_main_query() ) return;
    if ($query->is_search) {
        if( current_user_can('read_private_posts') && current_user_can('read_private_pages') ) {
            $query->set('post_status',array('private','publish'));
						$query->set('post_type',array('post','page'));
        }
    }
}
add_action('pre_get_posts','geop_ccb_filter_search');

/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function geop_ccb_excerpt_more( $more ) {
    if (is_category()) {
      return;
    }
    else {
      return sprintf( '<a class="read-more" href="%1$s">%2$s</a>',
          get_permalink( get_the_ID() ),
          __( ' Read More...', 'geoplatform-ccb' )
      );
    }
}
add_filter( 'excerpt_more', 'geop_ccb_excerpt_more' );

/**
 * Registers an editor stylesheet for the theme.
 * https://developer.wordpress.org/reference/functions/add_editor_style/
 */
function geop_ccb_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'geop_ccb_theme_add_editor_styles' );

/**
 * Category Image class
 * From https://catapultthemes.com/adding-an-image-upload-field-to-categories/
 **/
if ( ! class_exists( 'GP_TAX_META' ) ) {

class GP_TAX_META {

  public function __construct() {
    //
  }

 /*
  * Initialize the class and start calling our hooks and filters
  * @since 1.0.0
 */
 public function init() {
   add_action( 'category_add_form_fields', array ( $this, 'add_category_image' ), 10, 2 );
   add_action( 'created_category', array ( $this, 'save_category_image' ), 10, 2 );
   add_action( 'category_edit_form_fields', array ( $this, 'update_category_image' ), 10, 2 );
   add_action( 'edited_category', array ( $this, 'updated_category_image' ), 10, 2 );
   add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
   add_action( 'admin_footer', array ( $this, 'add_script' ) );
   add_filter( 'manage_edit-category_columns', 'geopccb_category_column_filter' );
   add_filter( 'manage_category_custom_column', 'geopccb_category_column_action', 10, 3 );
 }

public function load_media() {
 wp_enqueue_media();
}

 /*
  * Add a form field in the new category page
  * @since 1.0.0
 */
 public function add_category_image ( $taxonomy ) { ?>
   <div class="form-field term-group">
     <label for="category-image-id"><?php _e('Image', 'geoplatform-ccb'); ?></label>
     <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
     <div id="category-image-wrapper"></div>
     <p>
       <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'geoplatform-ccb' ); ?>" />
       <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'geoplatform-ccb' ); ?>" />
    </p>
   </div>
 <?php
 }

 /*
  * Save the form field
  * @since 1.0.0
 */
 public function save_category_image ( $term_id, $tt_id ) {
   if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
     $image = $_POST['category-image-id'];
     add_term_meta( $term_id, 'category-image-id', $image, true );
   }
 }

 /*
  * Edit the form field
  * @since 1.0.0
 */
 public function update_category_image ( $term, $taxonomy ) { ?>
   <tr class="form-field term-group-wrap">
     <th scope="row">
       <label for="category-image-id"><?php _e( 'Image', 'geoplatform-ccb' ); ?></label>
     </th>
     <td>
       <?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
       <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
       <div id="category-image-wrapper">
         <?php if ( $image_id ) { ?>
           <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
         <?php } ?>
       </div>
       <p>
         <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Image', 'geoplatform-ccb' ); ?>" />
         <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Image', 'geoplatform-ccb' ); ?>" />
       </p>
     </td>
   </tr>
 <?php
 }

/*
 * Update the form field value
 * @since 1.0.0
 */
 public function updated_category_image ( $term_id, $tt_id ) {
   if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
     $image = $_POST['category-image-id'];
     update_term_meta ( $term_id, 'category-image-id', $image );
   } else {
     update_term_meta ( $term_id, 'category-image-id', '' );
   }
 }

/*
 * Add script
 * @since 1.0.0
 */
 public function add_script() { ?>
   <script>
     jQuery(document).ready( function($) {
       function ct_media_upload(button_class) {
         var _custom_media = true,
         _orig_send_attachment = wp.media.editor.send.attachment;
         $('body').on('click', button_class, function(e) {
           var button_id = '#'+$(this).attr('id');
           var send_attachment_bkp = wp.media.editor.send.attachment;
           var button = $(button_id);
           _custom_media = true;
           wp.media.editor.send.attachment = function(props, attachment){
             if ( _custom_media ) {
               $('#category-image-id').val(attachment.id);
               $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
               $('#category-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
             } else {
               return _orig_send_attachment.apply( button_id, [props, attachment] );
             }
            }
         wp.media.editor.open(button);
         return false;
       });
     }
     ct_media_upload('.ct_tax_media_button.button');
     $('body').on('click','.ct_tax_media_remove',function(){
       $('#category-image-id').val('');
       $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
     });
     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
     $(document).ajaxComplete(function(event, xhr, settings) {
       var queryStringArr = settings.data.split('&');
       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
         var xml = xhr.responseXML;
         $response = $(xml).find('term_id').text();
         if($response!=""){
           // Clear the thumb image
           $('#category-image-wrapper').html('');
         }
       }
     });
   });
 </script>
 <?php }

  }

$GP_TAX_META = new GP_TAX_META();
$GP_TAX_META -> init();

}


/**
 * Thumbnail column added to category admin.
 *
 * Functionality inspired by categories-images plugin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function geopccb_category_column_filter( $columns ) {
  $new_columns = array();
  $new_columns['cb'] = $columns['cb'];
  $new_columns['thumb'] = __('Image', 'categories-images');

  unset( $columns['cb'] );

  return array_merge( $new_columns, $columns );
}

/**
 * Thumbnail added to category admin column, or default if not applicable.
 *
 * Functionality inspired by categories-images plugin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function geopccb_category_column_action( $columns, $column, $id ) {
  $class_category_image = get_term_meta($id, 'category-image-id', true);//Get the image ID
    if ( $column == 'thumb' ){
      $temp_img = wp_get_attachment_image_src($class_category_image, 'full')[0];
      if (!$temp_img)
        $temp_img = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
      $columns = '<img src="' . $temp_img . '" style="max-height: 12em; max-width: 100%;" />';
    }
    return $columns;
}
