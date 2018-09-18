<?php
/**
 * Get Docker container enviroment variables
 *
 * @param [string] $name
 * @param [string] $def (default)
 * @return ENV[$name] or $def if none found
 */
if ( ! function_exists( 'geop_ccb_getEnv' ) ){
  function geop_ccb_getEnv($name, $def){
    return isset($_ENV[$name]) ? $_ENV[$name] : $def;
	}
}
//set env variables
$geopccb_maps_url = geop_ccb_getEnv('maps_url', 'https://maps.geoplatform.gov');
$geopccb_viewer_url = geop_ccb_getEnv('viewer_url', 'https://viewer.geoplatform.gov');
$geopccb_marketplace_url = geop_ccb_getEnv('marketplace_url',"https://marketplace.geoplatform.gov");
$geopccb_dashboard_url = geop_ccb_getEnv('dashboard_url',"https://dashboard.geoplatform.gov/#/lma?surveyId=8&page=0&size=500&sortElement=title&sortOrder=asc&colorTheme=green");
$geopccb_wpp_url = geop_ccb_getEnv('wpp_url',"https://geoplatform.gov");
$geopccb_ual_url = geop_ccb_getEnv('ual_url',"https://ual.geoplatform.gov");
$geopccb_ckan_mp_url = geop_ccb_getEnv('ckan_mp_url',"https://data.geoplatform.gov/#/?progress=planned&h=Marketplace");
$geopccb_ckan_url = geop_ccb_getEnv('ckan_url',"https://data.geoplatform.gov/");
$geopccb_cms_url = geop_ccb_getEnv('cms_url',"https://marketplace.geoplatform.gov/");
$geopccb_idp_url = geop_ccb_getEnv('idp_url',"https://idp.geoplatform.gov");
$geopccb_oe_url = geop_ccb_getEnv('oe_url',"https://oe.geoplatform.gov");
$geopccb_sd_url = geop_ccb_getEnv('sd_url',"servicedesk@geoplatform.gov");
$geopccb_ga_code = geop_ccb_getEnv('ga_code','UA-42040723-1');

/**
 * Add scripts to header
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_scripts' ) ) {
  function geop_ccb_scripts() {
  	wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style.css' );
  	wp_enqueue_style( 'bootstrap-css',get_template_directory_uri() . '/css/bootstrap.css');
  	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/css/Geomain_style.css' );
    wp_enqueue_script( 'geoplatform-ccb-js', get_template_directory_uri() . '/js/geoplatform.style.js', array('jquery'), null, true );

    $geop_ccb_options = geop_ccb_get_theme_mods();
    if (get_theme_mod('bootstrap_controls', $geop_ccb_options['bootstrap_controls']) == 'on'){
  	   wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array(), '3.3.7', true);
    }
  }
  add_action( 'wp_enqueue_scripts', 'geop_ccb_scripts' );
}

//-------------------------------
// Add Google Analytics
//http://www.wpbeginner.com/beginners-guide/how-to-install-google-analytics-in-wordpress/
//-------------------------------
if ( ! function_exists ( 'geopccb_add_googleanalytics' ) ) {
  function geopccb_add_googleanalytics(){ ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122866646-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-42040723-1');
    </script>
  <?php
  }
  add_action('wp_head','geopccb_add_googleanalytics');
}

/**
 * Add Google Lato Fonts
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_google_fonts' ) ) {
	function geop_ccb_google_fonts() {
					wp_register_style('Lato/Slabo', 'https://fonts.googleapis.com/css?family=Lato:400,700|Slabo+27px');
					wp_enqueue_style( 'Lato/Slabo');
			}
	add_action('wp_enqueue_scripts', 'geop_ccb_google_fonts');
}
/**
 * Setup Theme and add supports
 *
 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
 * @link /https://developer.wordpress.org/reference/functions/get_search_form/
 * @link http://buildwpyourself.com/wordpress-search-form-template/
 * @link https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_setup' ) ) {
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
	$geopccb_header_args = array(
		'default-image' => get_template_directory_uri() . '/img/default-banner.png',
		'uploads'       => true,
		);
	add_theme_support( 'custom-header', $geopccb_header_args);

	/*
	* Support Featured Images
	*/
	add_theme_support( 'post-thumbnails' );

	/*
	* Theme Support for Automatic Feed links
	*/
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Theme Support for html5, and the html5 search form
	 */
	add_theme_support( 'html5', array( 'search-form' ) );

	/**
	 * Starter Content
	 */
	add_theme_support('starter-content', array(
		// Starter menus (see gp_create_services_menu)
		'nav_menus' => array(
				'header-left' => array(
					'name' => __('My Test Navigation', 'geoplatform-ccb'),
					'items' =>  array(
						'link_map_view'   =>  array(
							'title' =>  _x( 'Map Viewer', 'Theme starter Content', 'geoplatform-ccb' ),
							'url'   =>  $GLOBALS['geopccb_viewer_url'],
						),
						'link_map_man'    =>  array(
							'title' =>  _x( 'Map Manager', 'Theme starter Content', 'geoplatform-ccb' ),
							'url'   =>  $GLOBALS['geopccb_maps_url'],
						),
						'link_marketplace'    =>  array(
							'title' =>  _x( 'Marketplace Preview', 'Theme starter Content', 'geoplatform-ccb' ),
							'url'   =>  $GLOBALS['geopccb_marketplace_url'],
						),
						'link_perf_dash'    =>  array(
							'title' =>  _x( 'Performance Dashboard', 'Theme starter Content', 'geoplatform-ccb' ),
							'url'   =>  $GLOBALS['geopccb_dashboard_url'],
						),
						'link_ckan'    =>  array(
							'title' =>  _x( 'Search Catalog', 'Theme starter Content', 'geoplatform-ccb' ),
							'url'   =>  $GLOBALS['geopccb_ckan_url'],
						),
						'link_ckan_mp'    =>  array(
							'title' =>  _x( 'Search Marketplace', 'Theme starter Content', 'geoplatform-ccb' ),
							'url'   =>  $GLOBALS['geopccb_ckan_mp_url'],
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
}

/**
 * Support for a custom logo image
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_custom_logo_setup' ) ) {
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
}

/**
 * Support adding Menus for the three menu types, segregated by location.
 *
 * @link https://premium.wpmudev.org/blog/add-menus-to-wordpress/?utm_expid=3606929-97.J2zL7V7mQbSNQDPrXwvBgQ.0&utm_referrer=https%3A%2F%2Fwww.google.com%2F
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_register_comlink_menus' ) ) {
 	function geop_ccb_register_comlink_menus() {
 	  register_nav_menus(
   		array('community-links' => 'Community Links')
 	  );
	}
  add_action( 'init', 'geop_ccb_register_comlink_menus' );
}

if ( ! function_exists ( 'geop_ccb_register_header_menus' ) ) {
 	function geop_ccb_register_header_menus() {
 	  register_nav_menus(
   		array(
   			'header-left' => 'Header Menu - Left Column',
   			'header-center' => 'Header Menu - Center Column',
   			'header-right-col1' => 'Header Menu - Right Column 1',
   			'header-right-col2' => 'Header Menu - Right Column 2',
   		)
 	  );
	}
  add_action( 'init', 'geop_ccb_register_header_menus' );
}

if ( ! function_exists ( 'geop_ccb_register_footer_menus' ) ) {
	function geop_ccb_register_footer_menus() {
  	register_nav_menus(
  		array(
  			'footer-left' => 'Footer Menu - Left Column',
  			'footer-center' => 'Footer Menu - Center Column',
  			'footer-right-col1' => 'Footer Menu - Right Column 1',
  			'footer-right-col2' => 'Footer Menu - Right Column 2'
  		)
  	);
	}
	add_action( 'init', 'geop_ccb_register_footer_menus' );
}

/**
 * Adding Dashicons in WordPress Front-end
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_load_dashicons_front_end' ) ) {
	function geop_ccb_load_dashicons_front_end() {
	  wp_enqueue_style( 'dashicons' );
	}
	add_action( 'wp_enqueue_scripts', 'geop_ccb_load_dashicons_front_end' );
}


/**
 * Supporting Theme Customizer editing
 *
 * @link https://codex.wordpress.org/Theme_Customization_API
 *
 * Banner intro text editor links
 * @link https://wpshout.com/making-themes-more-wysiwyg-with-the-wordpress-customizer/
 * fixed some issues with linking up
 * @link https://github.com/paulund/wordpress-theme-customizer-custom-controls/issues/4
 *
 * @param [type] $wp_customize
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_customize_register' ) ) {
function geop_ccb_customize_register( $wp_customize ) {
	//get defaults array
	$geopccb_theme_options = geop_ccb_get_theme_mods();

	//color section, settings, and controls
    $wp_customize->add_section( 'header_color_section' , array(
        'title'    => __( 'Header Color Section', 'geoplatform-ccb' ),
        'priority' => 30
    ) );

		//h1 color setting and control
		$wp_customize->add_setting( 'header_color_setting' , array(
				'default'   => $geopccb_theme_options['header_color_setting'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_link_color_control', array(
				'label'    => __( 'Header1 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header_color_setting',
		) ) );

		//h2 color setting and control
		$wp_customize->add_setting( 'header2_color_setting' , array(
				'default'   => $geopccb_theme_options['header2_color_setting'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h2_link_color_control', array(
				'label'    => __( 'Header 2 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header2_color_setting',
		) ) );

		//h3 color setting and control
		$wp_customize->add_setting( 'header3_color_setting' , array(
				'default'   => $geopccb_theme_options['header3_color_setting'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h3_link_color_control', array(
				'label'    => __( 'Header 3 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header3_color_setting',
		) ) );

		//h4 color setting and control
		$wp_customize->add_setting( 'header4_color_setting' , array(
				'default'   => $geopccb_theme_options['header4_color_setting'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'h4_link_color_control', array(
				'label'    => __( 'Header 4 Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'header4_color_setting',
		) ) );

    //link (<a>) color and control
		$wp_customize->add_setting( 'link_color_setting' , array(
				'default'   => $geopccb_theme_options['link_color_setting'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'a_link_color_control', array(
				'label'    => __( 'Link Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'link_color_setting',
		) ) );

		//.brand color and control
		$wp_customize->add_setting( 'brand_color_setting' , array(
				'default'   => $geopccb_theme_options['brand_color_setting'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'brand_color_control', array(
				'label'    => __( 'Brand Color', 'geoplatform-ccb' ),
				'section'  => 'header_color_section',
				'settings' => 'brand_color_setting',
		) ) );

		//Fonts section, settings, and controls
		//http://themefoundation.com/wordpress-theme-customizer/ section 5.2 Radio Buttons
		$wp_customize->add_section( 'font_section' , array(
				'title'    => __( 'GeoPlatform Controls', 'geoplatform-ccb' ),
				'priority' => 50
		) );

		$wp_customize->add_setting('font_choice',array(
        'default' => 'lato',
				'sanitize_callback' => 'geop_ccb_sanitize_fonts',
  	));

		$wp_customize->add_control('font_choice',array(
        'type' => 'select',
        'label' => 'Fonts',
        'description' => "Select the font for this community.",
        'section' => 'font_section',
        'choices' => array(
            'lato' => __('Lato', 'geoplatform-ccb'),
            'slabo' => __('Slabo',  'geoplatform-ccb')
					),
		));

		//Banner Intro Text editor section, settings, and controls
		$wp_customize->add_section( 'banner_text_section' , array(
				'title'    => __( 'Banner Area', 'geoplatform-ccb' ),
				'priority' => 50
			) );

         // Add a text editor control
         require_once dirname(__FILE__) . '/text/text-editor-custom-control.php';
         $wp_customize->add_setting( 'text_editor_setting', array(
            'default'   => $geopccb_theme_options['text_editor_setting'],
			'transport' => 'refresh',
			'type' 		=> 'theme_mod',
			'sanitize_callback' => 'wp_kses_post'
         ) );
         $wp_customize->add_control( new Text_Editor_Custom_Control( $wp_customize, 'text_editor_setting', array(
             'label'   => __( 'Banner Text Editor', 'geoplatform-ccb' ),
             'section' => 'banner_text_section',
             'settings'   => 'text_editor_setting',
             'priority' => 10
         ) ) );

				 //Call to action button (formerly "Learn More" button)
				 $wp_customize->add_setting('call2action_button_setting', array(
					 'default' => $geopccb_theme_options['call2action_button_setting'],
					 'transport' => 'refresh',
           			'sanitize_callback' => 'geop_ccb_sanitize_checkbox'
				 ) );

				 $wp_customize->add_control('call2action_button_control', array(
					 'section' => 'banner_text_section',
					 'label' =>__( 'Show Call to Action button?', 'geoplatform-ccb' ),
					 'type' => 'checkbox',
					 'settings' => 'call2action_button_setting',
					 'priority' => 20,
				 ) );

				 $wp_customize->add_setting('call2action_text_setting', array(
					 'default' => $geopccb_theme_options['call2action_text_setting'],
					 'transport' => 'refresh',
					 'sanitize_callback' => 'sanitize_text_field',
				 ));
				 $wp_customize->add_control('call2action_text_control', array(
					 'section' => 'banner_text_section',
					 'label' =>__( 'Button Text', 'geoplatform-ccb' ),
					 'type' => 'text',
					 'settings' => 'call2action_text_setting',
					 'priority' => 30,
					 'input_attrs' => array(
						'placeholder' 		=> __( 'Place your text for the button here...', 'geoplatform-ccb' ),
					),
				 ) );

				 $wp_customize->add_setting('call2action_url_setting', array(
					'default' => $geopccb_theme_options['call2action_url_setting'],
					'transport' => 'refresh',
					'sanitize_callback' => 'esc_url_raw',
				));
				$wp_customize->add_control('call2action_url_control', array(
					'section' => 'banner_text_section',
					'label' =>__( 'Button URL', 'geoplatform-ccb' ),
					'type' => 'URL',
					'settings' => 'call2action_url_setting',
					'priority' => 40,
					'input_attrs' => array(
					 'placeholder' 		=> __( 'Place your url for the button here...', 'geoplatform-ccb' ),
				 ),
				) );

				//Map Gallery Custom link section, settings, and controls
			$wp_customize->add_section( 'map_gallery_section' , array(
				'title'    => __( 'Map Gallery', 'geoplatform-ccb' ),
				'priority' => 70
			) );
			$wp_customize->add_setting( 'map_gallery_link_box_setting' , array(
					'default'   => $geopccb_theme_options['map_gallery_link_box_setting'],
					'transport' => 'refresh',
					'sanitize_callback' => 'sanitize_text_field'
				) );
			$wp_customize->add_control( 'map_gallery_link_box_control', array(
					'section' => 'font_section',
					'label' => 'Map Gallery link',
					'settings' => 'map_gallery_link_box_setting',
					'description' => 'Make sure you use a full UAL link. Example: https://ual.geoplatform.gov/api/galleries/{your map gallery ID}',
					'type' => 'url',
					'priority' => 60
				) );






				//remove default colors section as Header Color Section does this job better
				 $wp_customize->remove_section( 'colors' );

				 //Remove default Menus and Static Front page sections as this theme doesn't utilize them at this time
				 $wp_customize->remove_section( 'static_front_page' );

				 //remove site tagline and checkbox for showing site title and tagline from Site Identity section
				 //; Not needed for the theme
				 $wp_customize->remove_control('blogdescription');
				 $wp_customize->remove_control('display_header_text');

  }
  add_action( 'customize_register', 'geop_ccb_customize_register');
}

/**
 * Sanitization callback functions for customizer fonts
 *
 * @link https://themeshaper.com/2013/04/29/validation-sanitization-in-customizer/
 * @param [type] $geop_ccb_value
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_sanitize_fonts' ) ) {
	function geop_ccb_sanitize_fonts( $geop_ccb_value ) {
    	if ( ! in_array( $geop_ccb_value, array( 'lato', 'slabo' ) ) )
        	$geop_ccb_value = 'lato';
    	return $geop_ccb_value;
	}
}

/**
 * Sanitization callback functions for customizer bootstrap
 *
 * @link https://themeshaper.com/2013/04/29/validation-sanitization-in-customizer/
 * @param [type] $geop_ccb_value
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_sanitize_bootstrap' ) ) {
	function geop_ccb_sanitize_bootstrap( $geop_ccb_value ) {
		if ( ! in_array( $geop_ccb_value, array( 'on', 'off', 'gone' ) ) )
			$geop_ccb_value = 'on';
		return $geop_ccb_value;
	}
}

/**
 * Sanitization callback functions for customizer searchbar
 *
 * @link https://themeshaper.com/2013/04/29/validation-sanitization-in-customizer/
 * @param [type] $geop_ccb_value
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_sanitize_searchbar' ) ) {
	function geop_ccb_sanitize_searchbar( $geop_ccb_value ) {
		if ( ! in_array( $geop_ccb_value, array( 'wp', 'gp', 'none' ) ) )
			$geop_ccb_value = 'wp';
    if (!in_array( 'geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array())) && $geop_ccb_value == 'gp')
      $geop_ccb_value = 'wp';
		return $geop_ccb_value;
	}
}

/**
 * Sanitization callback functions for customizer checkbox
 *
 * @link https://themeshaper.com/2013/04/29/validation-sanitization-in-customizer/
 * @param [type] $geop_ccb_value
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_sanitize_checkbox' ) ) {
	function geop_ccb_sanitize_checkbox( $checked ){
		//returns true if checkbox is checked
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}
}

/**
 * getting Enqueue script for custom customize control.
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/customize_controls_enqueue_scripts
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_custom_customize_enqueue' ) ) {
function geop_ccb_custom_customize_enqueue() {
	wp_enqueue_script( 'custom-customize', get_template_directory_uri() . '/customizer/customizer.js', array( 'jquery', 'customize-controls' ), false, true );
	}
	add_action( 'customize_controls_enqueue_scripts', 'geop_ccb_custom_customize_enqueue' );
}


/**
 * Dynamically show the colors and fonts changing
 *
 * @link https://codex.wordpress.org/Theme_Customization_API#Part_2:_Generating_Live_CSS
 *
 * needs to have 'transport' => 'refresh' in add_setting() above in order to work
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_header_customize_css' ) ) {
	function geop_ccb_header_customize_css()
	{	//Get defaults
		$geop_ccb_options = geop_ccb_get_theme_mods();
		?>
			<style type="text/css">
				h1 { color: <?php echo get_theme_mod('header_color_setting', $geop_ccb_options['header_color_setting']); ?>; }
				h2 { color: <?php echo get_theme_mod('header2_color_setting', $geop_ccb_options['header2_color_setting']); ?>!important; }
				h3 { color: <?php echo get_theme_mod('header3_color_setting', $geop_ccb_options['header3_color_setting']); ?>; }
				h4, .section--linked .heading .title { color: <?php echo get_theme_mod('header4_color_setting', $geop_ccb_options['header4_color_setting']); ?>; }
				.text-selected, .text-active, a, a:visited { color: <?php echo get_theme_mod('link_color_setting', $geop_ccb_options['link_color_setting']); ?>; }
				header.t-transparent .brand>a { color: <?php echo get_theme_mod('brand_color_setting', $geop_ccb_options['brand_color_setting']); ?>; }
        h1, h2, h3, h4, h5, h6, div, p { font-family:  <?php echo get_theme_mod('font_choice', $geop_ccb_options['font_choice']); ?>;}
			</style>
		<?php
	}
	add_action( 'wp_head', 'geop_ccb_header_customize_css');
}


/**
 * Register a default header, so that users may change back to it.
 *
 * https://wordpress.stackexchange.com/questions/195641/default-header-image-does-not-display
 *
 */
register_default_headers( array(
  'default-image' => array(
    'url'           => get_template_directory_uri() . '/img/default-banner.png',
    'thumbnail_url' => get_template_directory_uri() . '/img/default-banner.png',
    'description'   => __( 'Default Header Image', 'geoplatform-ccb' )
  ),
));

/**
 * Override banner background-image as the custom header
 *
 * @link https://codex.wordpress.org/Function_Reference/wp_add_inline_style
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_header_image_method' ) ) {
	function geop_ccb_header_image_method() {
		wp_enqueue_style('custom-style', get_template_directory_uri() . '/css/Geomain_style.css');
			$geopccb_headerImage = get_header_image();
      if (! $geopccb_headerImage)
        $geopccb_headerImage = get_template_directory_uri() . "/img/default-banner.png";

			$geopccb_custom_css = "
					.banner{
							background-image: url({$geopccb_headerImage});
					}";
			wp_add_inline_style( 'custom-style', $geopccb_custom_css );
		}
		add_action( 'wp_enqueue_scripts', 'geop_ccb_header_image_method' );
}

/**
 * Give page and post banners a WYSIWYG editor
 *
 * @link http://help4cms.com/add-wysiwyg-editor-in-wordpress-meta-box
 *
 * @return void
 */
define('WYSIWYG_META_BOX_ID', 'my-editor');
if ( ! function_exists ( 'geopccb_wysiwyg_register_custom_meta_box' ) ) {
	function geopccb_wysiwyg_register_custom_meta_box(){
		add_meta_box(WYSIWYG_META_BOX_ID, __('Banner Area Custom Content', 'geoplatform-ccb') , 'geop_ccb_custom_wysiwyg', 'post');
		add_meta_box(WYSIWYG_META_BOX_ID, __('Banner Area Custom Content', 'geoplatform-ccb') , 'geop_ccb_custom_wysiwyg', 'page');
	}
	add_action('admin_init', 'geopccb_wysiwyg_register_custom_meta_box');
}

/**
 * Setup input area for posts/pages
 *
 * @link http://help4cms.com/add-wysiwyg-editor-in-wordpress-meta-box
 * @param object $geopccb_post
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_custom_wysiwyg' ) ) {
	function geop_ccb_custom_wysiwyg($geopccb_post){
		echo "<h3>Anything you add below will show up in the Banner:</h3>";
		$geopccb_content = get_post_meta($geopccb_post->ID, 'geop_ccb_custom_wysiwyg', true);
		wp_editor(htmlspecialchars_decode($geopccb_content) , 'geop_ccb_custom_wysiwyg', array(
			"media_buttons" => true
		));
	}
}

 /**
  * Save Post data

  * @link http://help4cms.com/add-wysiwyg-editor-in-wordpress-meta-box

  * @param int $geopccb_post_id
  * @return void
  */
if ( ! function_exists ( 'geop_ccb_custom_wysiwyg_save_postdata' ) ) {
	function geop_ccb_custom_wysiwyg_save_postdata($geopccb_post_id){
		if (!empty($_POST['geop_ccb_custom_wysiwyg'])){
			$geopccb_data = htmlspecialchars_decode($_POST['geop_ccb_custom_wysiwyg']);
			update_post_meta($geopccb_post_id, 'geop_ccb_custom_wysiwyg', $geopccb_data);
	  }
	}
  add_action('save_post', 'geop_ccb_custom_wysiwyg_save_postdata');
}

/**
 * Making Category description pages WYSIWYG
 *
 * @link https://paulund.co.uk/add-tinymce-editor-category-description
 *
 * Author: Paulund
 * Author URI: http://www.paulund.co.uk
 * Version: 1.0 License: GPL2
 *
 * Copyright (C) Year Author Email This program is free software;
 * you can redistribute it and/or modify it under the terms of the
 * GNU General Public License, version 2, as published by the Free
 * Software Foundation. This program is distributed in the hope
 * that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for
 * more details. You should have received a copy of the GNU
 * General Public License along with this program; if not,
 * write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

if ( ! function_exists ( 'geop_ccb_cat_description' ) ) {
  add_filter('edit_category_form_fields', 'geop_ccb_cat_description');
  function geop_ccb_cat_description($geopccb_tag) {
  	?>
  	<!-- <table class="form-table"> -->
  		<tr class="form-field">
  			<th scope="row" valign="top"><label for="description"><?php _e('Description', 'geoplatform-ccb') ?></label></th>
  			<td>
  			  <?php
  				  $geopccb_settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' );
  				  wp_editor(wp_kses_post($geopccb_tag->description , ENT_QUOTES, 'UTF-8'), 'geop_ccb_cat_description', $geopccb_settings);
  		    ?>
  			  <br />
  			  <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.', 'geoplatform-ccb') ?></span>
  		  </td>
  		</tr>
  		<!-- </table> -->
  	<?php
  }
}

if ( ! function_exists ( 'geop_ccb_remove_default_category_description' ) ) {
  add_action('admin_head', 'geop_ccb_remove_default_category_description');
  function geop_ccb_remove_default_category_description() {
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
}


/**
 * Adding Categories and Tag functionality to pages (for frontpage setting)
 *
 * @link https://stackoverflow.com/questions/14323582/wordpress-how-to-add-categories-and-tags-on-pages
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_page_cat_tag_settings' ) ) {
	function geop_ccb_page_cat_tag_settings() {
		// Add tag metabox to page
		register_taxonomy_for_object_type('post_tag', 'page');
		// Add category metabox to page
		register_taxonomy_for_object_type('category', 'page');
	}
	add_action( 'init', 'geop_ccb_page_cat_tag_settings' );
}

/**
 * Ensure all tags and categories are included in query
 *
 * @param array $wp_query
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_tags_categories_support_query' ) ) {
	function geop_ccb_tags_categories_support_query($wp_query) {
	if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
	if ($wp_query->get('category_name')) $wp_query->set('post_type', 'any');
	}
	add_action('pre_get_posts', 'geop_ccb_tags_categories_support_query');
}

/**
 * Widgetizing the theme
 *
 * @link https://codex.wordpress.org/Function_Reference/dynamic_sidebar
 * @link https://www.elegantthemes.com/blog/tips-tricks/how-to-manage-the-wordpress-sidebar
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_sidebar' ) ) {
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
	add_action( 'widgets_init', 'geop_ccb_sidebar' );
}


/**
 * Global Content Width
 *
 * @link https://codex.wordpress.org/Content_Width#Adding_Theme_Support
 */
if ( ! isset( $content_width ) ) {
	$content_width = 600;
}

/**
 * Theme specific enabled capabilities
 *
 * @link https://codex.wordpress.org/Function_Reference/add_cap
 * @link https://codex.wordpress.org/Roles_and_Capabilities
 *
 * @uses WP_Role::add_cap()
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_add_theme_caps' ) ) {
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

		//Allows these roles to edit private_posts
		$authRole->add_cap('edit_private_posts');

		//Allows these roles to edit private_pages
		$authRole->add_cap('edit_private_pages');

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
}

/**
 *  Capabilities removed on Deactivation
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/switch_theme
 *
 * @uses WP_Role::remove_cap()
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_remove_theme_caps' ) ) {
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

		//Disallows these roles to read private posts
		$contribRole->remove_cap('read_private_posts');
		$authRole->remove_cap('read_private_posts');

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

		//Disallows these roles to edit private_posts
		$authRole->remove_cap('edit_private_posts');

		//Disallows these roles to edit private_pages
		$authRole->remove_cap('edit_private_pages');

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
}
/**
 * Private pages and posts show up in search for correct roles
 *
 * @link https://wordpress.stackexchange.com/questions/110569/private-posts-pages-search
 *
 * @param array $query
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_filter_search' ) ) {
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
}

/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
if ( ! function_exists ( 'geop_ccb_excerpt_more' ) ) {
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
}

/**
 * Registers an editor stylesheet for the theme.
 * @link https://developer.wordpress.org/reference/functions/add_editor_style/
 */
if ( ! function_exists ( 'geop_ccb_theme_add_editor_styles' ) ) {
  function geop_ccb_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
  }
  add_action( 'admin_init', 'geop_ccb_theme_add_editor_styles' );
}

/**
 * Category Image class
 * @link https://catapultthemes.com/adding-an-image-upload-field-to-categories/
 **/
if ( ! class_exists( 'GP_TAX_META' ) ) {

class GP_TAX_META {

  public function __construct() {
    //
  }

 /*
  * Initialize the class and start calling our hooks and filters
  * @since 3.1.3
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
  //  if ( ! function_exists ( 'geopccb_category_column_action_two' ) )
  //   add_filter( 'manage_category_custom_column', 'geopccb_category_column_action_two', 10, 3 );
  // if ( ! function_exists ( 'geopccb_category_column_action_two' ) )
  //   add_filter( 'manage_edit-category_columns', 'geopccb_category_column_filter_two' );
 }

public function load_media() {
 wp_enqueue_media();
}

 /*
  * Add a form field in the new category page
  * @since 3.1.3
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
  * @since 3.1.3
 */
 public function save_category_image ( $geopccb_term_id, $tt_id ) {
   if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
     $geopccb_image = $_POST['category-image-id'];
     add_term_meta( $geopccb_term_id, 'category-image-id', $geopccb_image, true );
   }
 }

 /*
  * Edit the form field
  * @since 3.1.3
 */
 public function update_category_image ( $geopccb_term, $taxonomy ) { ?>
   <tr class="form-field term-group-wrap">
     <th scope="row">
       <label for="category-image-id"><?php _e( 'Image', 'geoplatform-ccb' ); ?></label>
     </th>
     <td>
       <?php $geopccb_image_id = get_term_meta ( $geopccb_term -> term_id, 'category-image-id', true ); ?>
       <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $geopccb_image_id; ?>">
       <div id="category-image-wrapper">
         <?php if ( $geopccb_image_id ) { ?>
           <?php echo wp_get_attachment_image ( $geopccb_image_id, 'thumbnail' ); ?>
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
 * @since 3.1.3
 */
 public function updated_category_image ( $geopccb_term_id, $tt_id ) {
   if( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
     $geopccb_image = $_POST['category-image-id'];
     update_term_meta ( $geopccb_term_id, 'category-image-id', $geopccb_image );
   } else {
     update_term_meta ( $geopccb_term_id, 'category-image-id', '' );
   }
 }

/*
 * Add script
 * @since 3.1.3
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
 * @link https://wordpress.org/plugins/categories-images/
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
if ( ! function_exists ( 'geopccb_category_column_filter' ) ) {
  function geopccb_category_column_filter( $geopccb_columns ) {
    $geopccb_new_columns = array();
    $geopccb_new_columns['cb'] = $geopccb_columns['cb'];
    $geopccb_new_columns['thumb'] = __('Image', 'geoplatform-ccb');

    unset( $geopccb_columns['cb'] );

    return array_merge( $geopccb_new_columns, $geopccb_columns );
  }
}

/**
 * Thumbnail added to category admin column, or default if not applicable.
 *
 * Functionality inspired by categories-images plugin.
 * @link https://wordpress.org/plugins/categories-images/
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
if ( ! function_exists ( 'geopccb_category_column_action' ) ) {
  function geopccb_category_column_action( $geopccb_columns, $geopccb_column, $geopccb_id ) {
    $geopccb_class_category_image = get_term_meta($geopccb_id, 'category-image-id', true);//Get the image ID
      if ( $geopccb_column == 'thumb' ){
        $geopccb_temp_img = wp_get_attachment_image_src($geopccb_class_category_image, 'full')[0];
        if (!$geopccb_temp_img)
          $geopccb_temp_img = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
        $geopccb_columns = '<img src="' . $geopccb_temp_img . '" style="max-height: 12em; max-width: 100%;" />';
      }
    return $geopccb_columns;
  }
}

/**
 * Using Sane Defaults
 * @link https://make.wordpress.org/themes/2014/07/09/using-sane-defaults-in-themes/
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_get_option_defaults' ) ) {
	function geop_ccb_get_option_defaults() {
		$defaults = array(
			'header_color_setting' => '#000000',
			'header2_color_setting' => '#000000',
			'header3_color_setting' => '#000000',
			'header4_color_setting' => '#000000',
			'link_color_setting' => '#428bca',
			'brand_color_setting' => '#fff',
			'text_editor_setting' => "<h1 style='text-align: center; color:white;'>Your Community Title</h1>
									<p style='text-align: center'>Create and manage your own
									Dynamic Digital Community on the GeoPlatform!</p>",
			'call2action_button_setting' => true,
			'call2action_url_setting' => 'https://geoplatform.gov/about',
			'call2action_text_setting' => 'Learn More',
			'map_gallery_link_box_setting' => 'https://ual.geoplatform.gov/api/galleries/6c47d5d45264bedce3ac13ca14d0a0f7',
      'font_choice' => 'lato',
      'bootstrap_controls' => 'on',
      'searchbar_controls' => 'wp',
		);
		return apply_filters( 'geop_ccb_option_defaults', $defaults );
	}
}

/**
 * Getting the appropriate defaults
 * @link https://make.wordpress.org/themes/2014/07/09/using-sane-defaults-in-themes/
 *
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_get_theme_mods' ) ) {
	function geop_ccb_get_theme_mods() {
		// Theme Mods API:
		return wp_parse_args(
			get_theme_mods(),
			geop_ccb_get_option_defaults()
		);
	}
}









//---------------------------------------
//Supporting Sort Toggle between old date system and new custom system.
//https://codex.wordpress.org/Theme_Customization_API
//--------------------------------------
if ( ! function_exists ( 'geop_ccb_sorting_register' ) ) {
  function geop_ccb_sorting_register( $wp_customize ){

		//http://themefoundation.com/wordpress-theme-customizer/ section 5.2 Radio Buttons
		$wp_customize->add_section( 'featured_format' , array(
			'title'    => __( 'Featured Sorting', 'geoplatform-ccb' ),
			'priority' => 40
		) );

	  $wp_customize->add_setting('featured_appearance',array(
		  'default' => 'date',
		  'sanitize_callback' => 'geop_ccb_sanitize_feature_sort_format'
		));

		$wp_customize->add_control('featured_appearance',array(
		  'type' => 'radio',
		  'label' => 'Choose the sorting method',
		  'section' => 'featured_format',
      'description' => 'You can make use of custom sorting from your Categories admin page. Each category can be assigned a numeric value. Lower values will appear first, zero and negative values will not appear at all.',
			'choices' => array(
			  'custom' => __('Custom', 'geoplatform-ccb'),
				'date' => __('Date',  'geoplatform-ccb')
			),
		));
  }
}
add_action( 'customize_register', 'geop_ccb_sorting_register');

/**
 * Sanitization callback functions for sort check
 *
 * @link https://themeshaper.com/2013/04/29/validation-sanitization-in-customizer/
 * @param [type] $geop_ccb_value
 * @return void
 */
function geop_ccb_sanitize_feature_sort_format( $geop_ccb_value ) {
	if ( ! in_array( $geop_ccb_value, array( 'custom', 'date' ) ) )
  	$geop_ccb_value = 'date';
	return $geop_ccb_value;
}






/**
 * Adds Category priority and display toggle aspects to category.
 *
 * @link https://wordpress.stackexchange.com/questions/8736/add-custom-field-to-category
*/
if ( ! function_exists ( 'geop_ccb_category_mod_interface' ) ){
  function geop_ccb_category_mod_interface( $tag ){
    $cat_pri = get_term_meta( $tag->term_id, 'cat_priority', true ); ?>
    <tr class='form-field'>
      <th scope='row'><label for='cat_page_visible'><?php _e('Category Display Order', 'geoplatform-ccb'); ?></label></th>
      <td>
        <input type='number' name='cat_pri' id='cat_pri' value='<?php echo $cat_pri ?>' style='width:30%;'>
        <p class='description'><?php _e('Categories are displayed from lowest value to highest.<br>Set to a negative number or zero to make it not appear.', 'geoplatform-ccb'); ?></p>
      </td>
    </tr> <?php
  }
  add_action ( 'edit_category_form_fields', 'geop_ccb_category_mod_interface');
}

if ( ! function_exists ( 'geop_ccb_category_mod_update' ) ){
  function geop_ccb_category_mod_update() {
    if ( isset( $_POST['cat_pri'] ) )
      update_term_meta( $_POST['tag_ID'], 'cat_priority', $_POST['cat_pri'] );
  }
  add_action ( 'edited_category', 'geop_ccb_category_mod_update');
}

/**
 * Priority column added to category admin.
 *
 * Functionality inspired by categories-images plugin.
 *
 * @link https://wordpress.org/plugins/categories-images/
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
if ( ! function_exists ( 'geopccb_category_column_filter_two' ) ) {
  function geopccb_category_column_filter_two( $geopccb_columns ) {
    $geopccb_new_columns = array();
    $geopccb_new_columns['priority'] = __('Priority', 'geoplatform-ccb');
    $geopccb_new_columns['posts'] = $geopccb_columns['posts'];
    unset( $geopccb_columns['posts'] );

    return array_merge( $geopccb_columns, $geopccb_new_columns );
  }
  add_filter('manage_edit-category_columns', 'geopccb_category_column_filter_two');
}

/**
 * Data added to category admin column, or N/A if not applicable.
 *
 * Functionality inspired by categories-images plugin.
 * @link https://wordpress.org/plugins/categories-images/
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
if ( ! function_exists ( 'geopccb_category_column_action_two' ) ) {
  function geopccb_category_column_action_two( $geopccb_columns, $geopccb_column, $geopccb_id ) {
    $geopccb_class_category_priority = get_term_meta($geopccb_id, 'cat_priority', true);
      if ( $geopccb_column == 'priority' ){
        $geopccb_pri = $geopccb_class_category_priority;
        if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
          $geopccb_pri = "N/A";
        $geopccb_columns = '<p>' . $geopccb_pri . '</p>';
      }
    return $geopccb_columns;
  }
  add_filter( 'manage_category_custom_column', 'geopccb_category_column_action_two', 10, 3 );
}





/**
 * Post priority incorporation
 */

// register the meta box
if ( ! function_exists ( 'geop_ccb_custom_field_post_checkboxes' ) ) {
  function geop_ccb_custom_field_post_checkboxes() {
      add_meta_box(
          'geop_ccb_sorting_post_id',          // this is HTML id of the box on edit screen
          'Featured Display Priority',    // title of the box
          'geop_ccb_custom_field_post_box_content',   // function to be called to display the checkboxes, see the function below
          'post',        // on which edit screen the box should appear
          'normal',      // part of page where the box should appear
          'default'      // priority of the box
    );
  }
  add_action( 'add_meta_boxes', 'geop_ccb_custom_field_post_checkboxes' );
}

// display the metabox
if ( ! function_exists ( 'geop_ccb_custom_field_post_box_content' ) ) {
  function geop_ccb_custom_field_post_box_content($post) {
		echo "<input type='number' name='geop_ccb_post_priority' id='geop_ccb_post_priority' value='" . $post->geop_ccb_post_priority . "' style='width:30%;'>";
 		echo "<p class='description'>Featured content is displayed from lowest value to highest. Set to a negative number or zero to make it not appear.</p>";
  }
}

// save data from checkboxes
if ( ! function_exists ( 'geop_ccb_custom_field_post_data' ) ) {
  function geop_ccb_custom_field_post_data($post_id) {
    if ( !isset( $_POST['geop_ccb_post_priority'] ) || is_null( $_POST['geop_ccb_post_priority']) || empty( $_POST['geop_ccb_post_priority'] ))
      update_post_meta( $post_id, 'geop_ccb_post_priority', '0' );
    else
  		update_post_meta( $post_id, 'geop_ccb_post_priority', $_POST['geop_ccb_post_priority'] );
  }
  add_action( 'save_post', 'geop_ccb_custom_field_post_data' );
}

/**
 * Priority column added to posts admin.
 *
 * Functionality inspired by categories-images plugin.
 *
 * @link https://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_post_column_filter' ) ) {
  function geop_ccb_post_column_filter( $geopccb_columns ) {
    $geopccb_new_columns = array();
    $geopccb_new_columns['priority'] = __('Priority', 'geoplatform-ccb');
    $geopccb_new_columns['comments'] = $geopccb_columns['comments'];
  	$geopccb_new_columns['date'] = $geopccb_columns['date'];
  	unset( $geopccb_columns['date'] );
    unset( $geopccb_columns['comments'] );

    return array_merge( $geopccb_columns, $geopccb_new_columns );
  }
  add_filter('manage_post_posts_columns', 'geop_ccb_post_column_filter');
}

/**
 * Makes priority column sortable for posts.
 *
 * Functionality inspired by categories-images plugin.
 *
 * @link https://wpdreamer.com/2014/04/how-to-make-your-wordpress-admin-columns-sortable/#register-sortable-columns
 */
if ( ! function_exists ( 'geop_ccb_post_column_sorter' ) ) {
  function geop_ccb_post_column_sorter($geopccb_columns) {
    $geopccb_columns['priority'] = 'geop_ccb_post_priority';
    return $geopccb_columns;
  }
  add_filter('manage_edit-post_sortable_columns', 'geop_ccb_post_column_sorter');
}

// Powers the priority column sorts.
if ( ! function_exists ( 'geop_ccb_post_column_thinker' ) ) {
  function geop_ccb_post_column_thinker( $query ) {
    if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {
      if( $orderby == 'geop_ccb_post_priority') {
        $query->set( 'meta_key', 'geop_ccb_post_priority' );
			  $query->set( 'orderby', 'meta_value_num' );
      }
    }
  }
  add_action( 'pre_get_posts', 'geop_ccb_post_column_thinker', 1 );
}

/**
 * Metadata null-buster. Only really needs to be run once to de-null posts and pages
 * without priority values for the sake of sortation. Should be removed in a future
 * release.
 *
 * https://wordpress.stackexchange.com/questions/270472/assign-update-the-custom-field-value-for-all-posts
**/
if ( ! function_exists ( 'geop_ccb_post_nullbreaker' ) ) {
  function geop_ccb_post_nullbreaker(){
    $args = array(
      'post_type' => array('post', 'page'), // Affects posts and pages; category_links came after this metadata was incorporated.
      'post_status' => array('publish', 'private', 'future', 'draft', 'pending', 'trash', 'auto-draft'),
      'posts_per_page'   => -1 // Get every post
    );
    $posts = get_posts($args);
    foreach ( $posts as $post ) {
      if ( !isset( $post->geop_ccb_post_priority ) || is_null($post->geop_ccb_post_priority) || empty($post->geop_ccb_post_priority ))
        update_post_meta( $post->ID, 'geop_ccb_post_priority', '0' );
    }
  }
  add_action('init','geop_ccb_post_nullbreaker');
}

/**
 * Data added to posts admin column, or N/A if not applicable.
 *
 * Functionality inspired by categories-images plugin.
 * @link https://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_post_column_action' ) ) {
  function geop_ccb_post_column_action( $geopccb_column, $geopccb_id ) {
    if ( $geopccb_column == 'priority' ){
			$geopccb_pri = get_post($geopccb_id)->geop_ccb_post_priority;
      if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
        $geopccb_pri = "N/A";
      echo '<p>' . $geopccb_pri . '</p>';
    }
  }
  add_action('manage_post_posts_custom_column', 'geop_ccb_post_column_action', 10, 2);
}







/**
 * Page priority incorporation
 */

// register the meta box
if ( ! function_exists ( 'geop_ccb_custom_field_page_checkboxes' ) ) {
  function geop_ccb_custom_field_page_checkboxes() {
    add_meta_box(
        'geop_ccb_sorting_page_id',          // this is HTML id of the box on edit screen
        'Featured Display Priority',    // title of the box
        'geop_ccb_custom_field_post_box_content',   // function to be called to display the checkboxes, see the function below
        'page',        // on which edit screen the box should appear
        'normal',      // part of page where the box should appear
        'default'      // priority of the box
    );
  }
  add_action( 'add_meta_boxes', 'geop_ccb_custom_field_page_checkboxes' );
}

/**
 * Priority column added to pages admin.
 *
 * Functionality inspired by categories-images plugin.
 *
 * @link https://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_page_column_filter' ) ) {
  function geop_ccb_page_column_filter( $geopccb_columns ) {
    $geopccb_new_columns = array();
    $geopccb_new_columns['priority'] = __('Priority', 'geoplatform-ccb');
    $geopccb_new_columns['comments'] = $geopccb_columns['comments'];
  	$geopccb_new_columns['date'] = $geopccb_columns['date'];
  	unset( $geopccb_columns['date'] );
    unset( $geopccb_columns['comments'] );

    return array_merge( $geopccb_columns, $geopccb_new_columns );
  }
  add_filter('manage_pages_columns', 'geop_ccb_page_column_filter');
}

// Adding sortation, handled functionally by the posts function.
if ( ! function_exists ( 'geop_ccb_page_column_sorter' ) && get_theme_mod('featured_appearance', 'date') == 'custom') {
  function geop_ccb_page_column_sorter($geopccb_columns) {
    $geopccb_columns['priority'] = 'geop_ccb_post_priority';
    return $geopccb_columns;
  }
  add_filter('manage_edit-page_sortable_columns', 'geop_ccb_page_column_sorter');
}

/**
 * Data added to pages admin column, or N/A if not applicable.
 *
 * Functionality inspired by categories-images plugin.
 * @link https://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_page_column_action' ) ) {
  function geop_ccb_page_column_action( $geopccb_column, $geopccb_id ) {
    if ( $geopccb_column == 'priority' ){
			$geopccb_pri = get_post($geopccb_id)->geop_ccb_post_priority;
      if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
        $geopccb_pri = "N/A";
      echo '<p>' . $geopccb_pri . '</p>';
    }
  }
  add_action('manage_pages_custom_column', 'geop_ccb_page_column_action', 10, 2);
}




/**
 * Creates the category post custom post type.
 */
if ( ! function_exists ( 'geop_ccb_create_category_post' ) ) {
  function geop_ccb_create_category_post() {
    register_post_type( 'geopccb_catlink',
      array(
        'labels' => array(
          'name' => 'Category Links',
          'singular_name' => 'Category Link'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'author', 'thumbnail', 'excerpt', 'category'),
        'taxonomies' => array('category'),
        'publicly_queryable'  => false,
      )
    );
  }
  add_action( 'init', 'geop_ccb_create_category_post' );
}

/**
 * Category Link priority incorporation
 */
// register the meta box for priority AND URL.
if ( ! function_exists ( 'geop_ccb_custom_field_catlink_checkboxes' ) ) {
  function geop_ccb_custom_field_catlink_checkboxes() {
    add_meta_box(
        'geop_ccb_sorting_catlink_id',          // this is HTML id of the box on edit screen
        'Featured Display Priority',    // title of the box
        'geop_ccb_custom_field_post_box_content',   // function to be called to display the checkboxes, see the function below
        'geopccb_catlink',        // on which edit screen the box should appear
        'normal',      // part of page where the box should appear
        'default'      // priority of the box
    );
    add_meta_box(
        'geop_ccb_sorting_catlink_url',          // this is HTML id of the box on edit screen
        'Redirect URL',    // title of the box
        'geop_ccb_custom_field_external_url_content',   // function to be called to display the checkboxes, see the function below
        'geopccb_catlink',        // on which edit screen the box should appear
        'normal',      // part of page where the box should appear
        'default'      // priority of the box
    );
  }
  add_action( 'add_meta_boxes', 'geop_ccb_custom_field_catlink_checkboxes' );
}

/**
 * Priority column added to category link admin.
 *
 * Functionality inspired by categories-images plugin.
 *
 * @link https://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_catlink_column_filter' ) ) {
  function geop_ccb_catlink_column_filter( $geopccb_columns ) {
    $geopccb_columns = array(
      'cb' => '<input type="checkbox" />',
      'title' => 'Title',
      'author' => 'Author',
      'categories' => 'Categories',
      'priority' => 'Priority',
      'Date' => 'Date',
    );
    return $geopccb_columns;
  }
  add_filter( "manage_geopccb_catlink_posts_columns", "geop_ccb_catlink_column_filter" );
}

/**
 * Data added to category link admin column, or N/A if not applicable.
 *
 * Functionality inspired by categories-images plugin.
 * @link https://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
if ( ! function_exists ( 'geop_ccb_catlink_column_action' ) ) {
  function geop_ccb_catlink_column_action( $geopccb_column, $geopccb_id ) {
    if ( $geopccb_column == 'priority' ){
			$geopccb_pri = get_post($geopccb_id)->geop_ccb_post_priority;
      if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
        $geopccb_pri = "N/A";
      echo '<p>' . $geopccb_pri . '</p>';
    }
  }
  add_action('manage_geopccb_catlink_posts_custom_column', 'geop_ccb_catlink_column_action', 10, 2);
}

// Adding sortation, handled functionally by the posts function.
if ( ! function_exists ( 'geop_ccb_catlink_column_sorter' ) && get_theme_mod('featured_appearance', 'date') == 'custom') {
  function geop_ccb_catlink_column_sorter($geopccb_columns) {
    $geopccb_columns['priority'] = 'geop_ccb_post_priority';
    return $geopccb_columns;
  }
  add_filter('manage_edit-geopccb_catlink_sortable_columns', 'geop_ccb_catlink_column_sorter');
}

// display the metabox for cat_link URL
if ( ! function_exists ( 'geop_ccb_custom_field_external_url_content' ) ) {
  function geop_ccb_custom_field_external_url_content($post) {
		echo "<input type='text' name='geop_ccb_cat_link_url' id='geop_ccb_cat_link_url' value='" . $post->geop_ccb_cat_link_url . "' style='width:30%;'>";
 		echo "<p class='description'>Featured content is displayed from lowest value to highest. Set to a negative number or zero to make it not appear.</p>";
  }
}

// save data from the cat_link URL box
if ( ! function_exists ( 'geop_ccb_custom_field_catlink_data' ) ) {
  function geop_ccb_custom_field_catlink_data($post_id) {
    if ( !isset( $_POST['geop_ccb_cat_link_url'] ) || is_null( $_POST['geop_ccb_cat_link_url']) || empty( $_POST['geop_ccb_cat_link_url'] ))
      update_post_meta( $post_id, 'geop_ccb_cat_link_url', '' );
    else
  		update_post_meta( $post_id, 'geop_ccb_cat_link_url', $_POST['geop_ccb_cat_link_url'] );
  }
  add_action( 'save_post', 'geop_ccb_custom_field_catlink_data' );
}










if ( ! function_exists ( 'geop_ccb_bootstrap_register' ) ) {
  function geop_ccb_bootstrap_register($wp_customize){

    $wp_customize->add_setting('bootstrap_controls',array(
        'default' => 'on',
        'sanitize_callback' => 'geop_ccb_sanitize_bootstrap',
    ));

    $wp_customize->add_control('bootstrap_controls',array(
        'type' => 'radio',
        'label' => 'Bootstrap Controls',
        'section' => 'font_section',
        'description' => "The GeoPlatform themes utilize Bootstrap for their dropdown menus, but some plugins use Bootstrap as well. When both are active at the same time it can cause errors or loss of function. In such cases, it is advised to disable Bootstrap in the plugin settings or here. The menu can also be disabled here if problems persist.",
        'choices' => array(
            'on' => __('Enabled', 'geoplatform-ccb'),
            'off' => __('Disabled',  'geoplatform-ccb'),
            'gone' => __('No Menu', 'geoplatform-ccb')
          ),
    ));
  }
  add_action( 'customize_register', 'geop_ccb_bootstrap_register');
}



if ( ! function_exists ( 'geop_ccb_search_register' ) ) {
  function geop_ccb_search_register($wp_customize){

    $wp_customize->add_setting('searchbar_controls',array(
        'default' => 'wp',
        'sanitize_callback' => 'geop_ccb_sanitize_searchbar',
    ));

    $geop_ccb_gpsearch_array = array(
        'wp' => __('Enabled', 'geoplatform-ccb'),
        'none' => __('Disabled',  'geoplatform-ccb'),
    );
    $geop_ccb_gpsearch_description = "The search bar in the header is used to search assets on this community site. It can be toggled on or off here.";
    if (in_array( 'geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array() ) )){
      $geop_ccb_gpsearch_array = array(
          'wp' => __('Site Search', 'geoplatform-ccb'),
          'gp' => __('GeoPlatform Search',  'geoplatform-ccb'),
          'none' => __('Disabled',  'geoplatform-ccb'),
      );
      $geop_ccb_gpsearch_description = "The search bar in the header can be used to search assets on this community site, or leverage the GeoPlatform Search plugin here. It can also be turned off.";
    }

    $wp_customize->add_control('searchbar_controls',array(
        'type' => 'radio',
        'label' => 'Search Bar Controls',
        'section' => 'font_section',
        'description' => $geop_ccb_gpsearch_description,
        'choices' => $geop_ccb_gpsearch_array,
    ));
  }
  add_action( 'customize_register', 'geop_ccb_search_register');
}




if ( ! function_exists ( 'geop_ccb_custom_field_post_data' ) ) {
  function geop_ccb_custom_field_post_data($post_id) {
    if ( !isset( $_POST['geop_ccb_post_priority'] ) || is_null( $_POST['geop_ccb_post_priority']) || empty( $_POST['geop_ccb_post_priority'] ))
      update_post_meta( $post_id, 'geop_ccb_post_priority', '0' );
    else
  		update_post_meta( $post_id, 'geop_ccb_post_priority', $_POST['geop_ccb_post_priority'] );
  }
  add_action( 'save_post', 'geop_ccb_custom_field_post_data' );
}






/**
 * CDN Distribution handler
 *
 * @link https://github.com/YahnisElsts/plugin-update-checker
 */
if ( ! function_exists ( 'geop_ccb_distro_manager' ) ) {
  function geop_ccb_distro_manager() {
    require dirname(__FILE__) . '/plugin-update-checker-4.4/plugin-update-checker.php';
    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    	'https://raw.githubusercontent.com/GeoPlatform/CCB-Plugins/develop/config/gp-ccb-update-details.json',
    	__FILE__,
    	'geoplatform-ccb'
    );
  }
  geop_ccb_distro_manager();
}

/**
 * Second image handler for individual banners.
 *
 * @link https://github.com/voceconnect/multi-post-thumbnails/wiki
 */
 // if (class_exists('geop_ccb_MultiPostThumbnails')) {
 //     new geop_ccb_MultiPostThumbnails(
 //         array(
 //             // Replace [YOUR THEME TEXT DOMAIN] below with the text domain of your theme (found in the theme's `style.css`).
 //             'label' => __( 'Banner Image', 'geoplatform-ccb'),
 //             'id' => 'geop-ccb-banner-image',
 //             'post_type' => 'post'
 //         )
 //     );
 // }
