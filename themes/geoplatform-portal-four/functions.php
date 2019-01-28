<?php
/**
 * Get additional Docker container enviroment variables
 *
 * @param [string] $name
 * @param [string] $def (default)
 * @return ENV[$name] or $def if none found
 */
function gpp_getEnv($name, $def){
	return isset($_ENV[$name]) ? $_ENV[$name] : $def;
}
// set additional env variables
$geopccb_comm_url = gpp_getEnv('comm_url',"https://www.geoplatform.gov/communities/");
$geopccb_accounts_url = gpp_getEnv('accounts_url',"https://accounts.geoplatform.gov");

/**
 * Establish this as a child theme of GeoPlatform CCB.
 * Also loads jQuery and several assets.
 */
function geopportal_enqueue_scripts() {
	$parent_style = 'parent-style';
	wp_enqueue_style( 'bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css');
	wp_enqueue_style( 'fontawesome-css', 'https://use.fontawesome.com/releases/v5.2.0/css/all.css');
	wp_enqueue_style( 'geop-root-css', get_stylesheet_directory_uri() . '/css/root-css.css');
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));

	if (is_page_template('page-templates/page_style-guide.php'))
		wp_enqueue_style( 'styleguide-css', get_stylesheet_directory_uri() . '/css/styleguide.css');

	wp_enqueue_script( 'popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' );
	wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js' );
	wp_enqueue_script( 'geoplatform-ccb-js', get_template_directory_uri() . '/js/geoplatform.style.js', array('jquery'), null, true );
	wp_enqueue_script( 'geop-prism-js', get_stylesheet_directory_uri() . '/js/prism.js' );
	wp_enqueue_script( 'geop-styleguide-js', get_stylesheet_directory_uri() . '/js/styleguide.js' );
  wp_enqueue_script( 'auth', get_stylesheet_directory_uri() . '/scripts/authentication.js' );
  wp_enqueue_script( 'fixedScroll', get_stylesheet_directory_uri() . '/scripts/fixed_scroll.js');
  wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'geopportal_enqueue_scripts' );


/**
 * Loads all the unique scripts and styles for this theme. Overrides CCB's function.
 */
function geop_ccb_scripts() {
	$geop_ccb_options = geop_ccb_get_theme_mods();
}
add_action( 'wp_enqueue_scripts', 'geop_ccb_scripts' );

function geop_ccb_header_image_method() {}
add_action( 'wp_enqueue_scripts', 'geop_ccb_header_image_method' );


function geop_ccb_header_customize_css(){}
add_action( 'wp_head', 'geop_ccb_header_customize_css');


//Disable admin bar (un-comment for prod sites)
//add_filter('show_admin_bar', '__return_false');

//--------------------------
//Support adding Menus for header and footer
//https://premium.wpmudev.org/blog/add-menus-to-wordpress/?utm_expid=3606929-97.J2zL7V7mQbSNQDPrXwvBgQ.0&utm_referrer=https%3A%2F%2Fwww.google.com%2F
//--------------------------
function geop_ccb_register_menus() {
  register_nav_menus(
    array(
      'headfoot-featured' => __( 'HF - Featured' ),
      'headfoot-getInvolved' => __( 'HF - Get Involved' ),
			// 'headfoot-exploreData' => __(' HF - Explore Data'),
      // 'headfoot-appservices' => __( 'HF - Apps and Services' ),
      'headfoot-about' => __( 'HF - About' ),
      //'headfoot-aboutR' => __( 'HF - About Right' ),
      // 'headfoot-help' => __( 'HF - Help' ),
      // 'headfoot-themes' => __( 'HF - Themes')
    )
  );
}
add_action( 'init', 'geop_ccb_register_menus' );

/**
 * Supporting Theme Customizer editing
 *
 * Overriding the same function in geoplatform-ccb; most aspects of it have no
 * bearing here.
 */
function geop_ccb_customize_register( $wp_customize ) {
	//get defaults array
	$geopccb_theme_options = geop_ccb_get_theme_mods();

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


function geop_ccb_header_link_register( $wp_customize ){

	$wp_customize->add_section( 'headlink_format' , array(
		'title'    => __( 'Header Links', 'geoplatform-ccb' ),
		'description' => 'Insert the slugs of posts or pages that each element will navigate to when clicked.',
		'priority' => 25
	) );

	$wp_customize->add_setting('headlink_data',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_data',array(
		'type' => 'text',
		'label' => 'Datasets (Explore Resources Menu)',
		'section' => 'headlink_format',
		'priority' => 15,
	));

	$wp_customize->add_setting('headlink_services',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_services',array(
		'type' => 'text',
		'label' => 'Services (Explore Resources Menu)',
		'section' => 'headlink_format',
		'priority' => 20,
	));

	$wp_customize->add_setting('headlink_layers',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_layers',array(
		'type' => 'text',
		'label' => 'Layers (Explore Resources Menu)',
		'section' => 'headlink_format',
		'priority' => 25,
	));

	$wp_customize->add_setting('headlink_maps',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_maps',array(
		'type' => 'text',
		'label' => 'Maps (Explore Resources Menu)',
		'section' => 'headlink_format',
		'priority' => 30,
	));

	$wp_customize->add_setting('headlink_galleries',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_galleries',array(
		'type' => 'text',
		'label' => 'Galleries (Explore Resources Menu)',
		'section' => 'headlink_format',
		'priority' => 35,
	));

	$wp_customize->add_setting('headlink_communities',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_communities',array(
		'type' => 'text',
		'label' => 'Communities',
		'section' => 'headlink_format',
		'priority' => 40,
	));

	$wp_customize->add_setting('headlink_search',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_search',array(
		'type' => 'text',
		'label' => 'Search (GeoPlatform Search Plugin Page)',
		'section' => 'headlink_format',
		'priority' => 45,
		'default' => 'geoplatform-search'
	));

	$wp_customize->add_setting('headlink_register',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_register',array(
		'type' => 'text',
		'label' => 'Register (GeoPlatform Resource Registration Page)',
		'section' => 'headlink_format',
		'priority' => 46,
		'default' => 'geoplatform-register'
	));

	$wp_customize->add_setting('headlink_items',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_items',array(
		'type' => 'text',
		'label' => 'Items (GeoPlatform Item Details Page)',
		'section' => 'headlink_format',
		'priority' => 47,
		'default' => 'geoplatform-items'
	));





	$wp_customize->add_setting('headlink_help',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_help',array(
		'type' => 'text',
		'label' => 'Help',
		'section' => 'headlink_format',
		'priority' => 50,
	));

	$wp_customize->add_setting('headlink_apps',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_apps',array(
		'type' => 'text',
		'label' => "Apps & Services (Mega Menu)",
		'section' => 'headlink_format',
		'priority' => 55,
	));
}
add_action( 'customize_register', 'geop_ccb_header_link_register');



function geop_ccb_footer_link_register( $wp_customize ){

	$wp_customize->add_section( 'footlink_format' , array(
		'title'    => __( 'Footer Links', 'geoplatform-ccb' ),
		'description' => 'Insert the text for footer elements as well as the full URL that they will navigate to when clicked.',
		'priority' => 30,
	) );

	$wp_customize->add_setting('footlink_one_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_one_text',array(
		'type' => 'text',
		'label' => "Footer Link #1 text",
		'section' => 'footlink_format',
		'priority' => 10,
	));

	$wp_customize->add_setting('footlink_one_url',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_one_url',array(
		'type' => 'text',
		'label' => "Footer Link #1 url",
		'section' => 'footlink_format',
		'priority' => 15,
	));

	$wp_customize->add_setting('footlink_two_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_two_text',array(
		'type' => 'text',
		'label' => "Footer Link #2 text",
		'section' => 'footlink_format',
		'priority' => 20,
	));

	$wp_customize->add_setting('footlink_two_url',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_two_url',array(
		'type' => 'text',
		'label' => "Footer Link #2 url",
		'section' => 'footlink_format',
		'priority' => 25,
	));

	$wp_customize->add_setting('footlink_three_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_three_text',array(
		'type' => 'text',
		'label' => "Footer Link #3 text",
		'section' => 'footlink_format',
		'priority' => 30,
	));

	$wp_customize->add_setting('footlink_three_url',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_three_url',array(
		'type' => 'text',
		'label' => "Footer Link #3 url",
		'section' => 'footlink_format',
		'priority' => 35,
	));

	$wp_customize->add_setting('footlink_four_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_four_text',array(
		'type' => 'text',
		'label' => "Footer Link #4 text",
		'section' => 'footlink_format',
		'priority' => 40,
	));

	$wp_customize->add_setting('footlink_four_url',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_four_url',array(
		'type' => 'text',
		'label' => "Footer Link #4 url",
		'section' => 'footlink_format',
		'priority' => 45,
	));

	$wp_customize->add_setting('footlink_five_text',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_five_text',array(
		'type' => 'text',
		'label' => "Footer Link #5 text",
		'section' => 'footlink_format',
		'priority' => 50,
	));

	$wp_customize->add_setting('footlink_five_url',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('footlink_five_url',array(
		'type' => 'text',
		'label' => "Footer Link #5 url",
		'section' => 'footlink_format',
		'priority' => 55,
	));
}
add_action( 'customize_register', 'geop_ccb_footer_link_register');




function geop_ccb_featured_register( $wp_customize ){

	$wp_customize->add_section( 'featured_posts' , array(
		'title'    => __( 'Featured Pages', 'geoplatform-ccb' ),
		'description' => 'Settings here determine the behavior of any GeoPlatform Featured widgets. The boxes below accept the slugs of the linked posts. <br>Please ensure that any input slugs are valid.<br>Ensure you enter a valid map ID, not shortcode. The GeoPlatform Maps plugin will construct the necessary parameters itself.',
		'priority' => 35,
	) );

	$wp_customize->add_setting('featured_primary_post',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_primary_post',array(
		'type' => 'text',
		'label' => "Primary Post Slug",
		'section' => 'featured_posts',
		'priority' => 10,
	));

	$wp_customize->add_setting('featured_secondary_one',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_secondary_one',array(
		'type' => 'text',
		'label' => "Sub-Feature Post #1 Slug",
		'section' => 'featured_posts',
		'priority' => 15,
	));

	$wp_customize->add_setting('featured_secondary_two',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_secondary_two',array(
		'type' => 'text',
		'label' => "Sub-Feature Post #2 Slug",
		'section' => 'featured_posts',
		'priority' => 20,
	));

	$wp_customize->add_setting('featured_secondary_three',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_secondary_three',array(
		'type' => 'text',
		'label' => "Sub-Feature Post #3 Slug",
		'section' => 'featured_posts',
		'priority' => 25,
	));

	$wp_customize->add_setting('featured_secondary_four',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_secondary_four',array(
		'type' => 'text',
		'label' => "Sub-Feature Post #4 Slug",
		'section' => 'featured_posts',
		'priority' => 30,
	));

	$wp_customize->add_setting('featured_map_title',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_map_title',array(
		'type' => 'text',
		'label' => "Map Title",
		'section' => 'featured_posts',
		'priority' => 35,
	));

	$wp_customize->add_setting('featured_map_id',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_map_id',array(
		'type' => 'text',
		'label' => "Map ID",
		'section' => 'featured_posts',
		'priority' => 40,
	));

	$wp_customize->add_setting('featured_more_count',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_more_count',array(
		'type' => 'number',
		'label' => "More Content Count",
		'section' => 'featured_posts',
		'priority' => 45,
	));

	$wp_customize->add_setting('featured_browse_slug',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('featured_browse_slug',array(
		'type' => 'text',
		'label' => "Browse All Page Slug",
		'section' => 'featured_posts',
		'priority' => 50,
	));




}
add_action( 'customize_register', 'geop_ccb_featured_register');



function geop_ccb_sanitize_fonts( $geop_portal_value ) {
  if ( $geop_portal_value == '' )
    $geop_portal_value = home_url();
  return $geop_portal_value;
}

// Bootstrap controls are removed due to irrelevance.
function geop_ccb_bootstrap_register($wp_customize){}


//-------------------------------
// Widgetizing the theme
// https://codex.wordpress.org/Function_Reference/dynamic_sidebar
// https://www.elegantthemes.com/blog/tips-tricks/how-to-manage-the-wordpress-sidebar
//------------------------------------

/**
 * Sidebar setup
 */
function wpsites_before_post_widget( $content ) {
	if ( is_singular( array( 'post', 'page' ) ) && is_active_sidebar( 'before-post' ) && is_main_query() ) {
		dynamic_sidebar('before-post');
	}
	return $content;
}
add_filter( 'the_content', 'wpsites_before_post_widget' );

/**
 * Widgetizing the front page
 */
if ( ! function_exists ( 'geop_ccb_frontpage' ) ) {
	function geop_ccb_frontpage() {
		register_sidebar(
		array(
			'id' => 'geoplatform-widgetized-page',
			'name' => __( 'Frontpage Widgets', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go on the portal front page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_frontpage' );
}

/**
 * Widgetizing the data page
 */
if ( ! function_exists ( 'geop_ccb_datapage' ) ) {
	function geop_ccb_datapage() {
		register_sidebar(
		array(
			'id' => 'geoplatform-widgetized-page-two',
			'name' => __( 'Widgetized Template Two', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go on the portal data page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_datapage' );
}

/**
 * Widgetizing the i'm new page
 */
if ( ! function_exists ( 'geop_ccb_newpage' ) ) {
	function geop_ccb_newpage() {
		register_sidebar(
		array(
			'id' => 'geoplatform-widgetized-page-one',
			'name' => __( 'Widgetized Template One', 'geoplatform-portal-four' ),
			'description' => __( "Widgets that go on the portal I'm New page can be added here.", 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_newpage' );
}

/**
 * Widgetizing the sidebar
 */
if ( ! function_exists ( 'geop_ccb_sidebar' ) ) {
	function geop_ccb_sidebar() {
		register_sidebar(
		array(
			'id' => 'geoplatform-widgetized-page-sidebar',
			'name' => __( 'Sidebar Widgets', 'geoplatform-portal-four' ),
			'description' => __( "Widgets that go in the sidebar can be added here.", 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_sidebar' );
}

/**
 * Widgetizing Explore Resources page one
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_one' ) ) {
	function geop_ccb_explore_resources_widgets_one() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-one',
			'name' => __( 'Resources Template One', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page one can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_one' );
}

/**
 * Widgetizing Explore Resources page two
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_two' ) ) {
	function geop_ccb_explore_resources_widgets_two() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-two',
			'name' => __( 'Resources Template Two', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page two can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_two' );
}

/**
 * Widgetizing Explore Resources page three
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_three' ) ) {
	function geop_ccb_explore_resources_widgets_three() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-three',
			'name' => __( 'Resources Template Three', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page three can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_three' );
}

/**
 * Widgetizing Explore Resources page four
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_four' ) ) {
	function geop_ccb_explore_resources_widgets_four() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-four',
			'name' => __( 'Resources Template Four', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page four can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_four' );
}

/**
 * Widgetizing Explore Resources page five
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_five' ) ) {
	function geop_ccb_explore_resources_widgets_five() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-five',
			'name' => __( 'Resources Template Five', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page five can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_five' );
}

/**
 * Widgetizing Explore Resources page six
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_six' ) ) {
	function geop_ccb_explore_resources_widgets_six() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-six',
			'name' => __( 'Resources Template Six', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page six can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_six' );
}

/**
 * Widgetizing Explore Resources page seven
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_seven' ) ) {
	function geop_ccb_explore_resources_widgets_seven() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-seven',
			'name' => __( 'Resources Template Seven', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page seven can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_seven' );
}

/**
 * Widgetizing Explore Resources page eight
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_eight' ) ) {
	function geop_ccb_explore_resources_widgets_eight() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-eight',
			'name' => __( 'Resources Template Eight', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page eight can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_eight' );
}

/**
 * Widgetizing Explore Resources page nine
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_nine' ) ) {
	function geop_ccb_explore_resources_widgets_nine() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-nine',
			'name' => __( 'Resources Template Nine', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page nine can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_nine' );
}

/**
 * Widgetizing Explore Resources page ten
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_ten' ) ) {
	function geop_ccb_explore_resources_widgets_ten() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-ten',
			'name' => __( 'Resources Template Ten', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Resources page ten can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_ten' );
}

/**
 * Adds sidebar contact form widget.
 */
class Geopportal_Contact_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'geopportal_contact_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Contact', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform contact information widget for the sidebar.', 'geoplatform-ccb' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget. Just gets contact template.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		get_template_part( 'contact', get_post_format() );
	}

	/**
	 * Back-end widget form. Just text.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		?>
		<p>
		  <?php _e("This is the GeoPlatform theme contact information widget for the sidebar. There are no options to customize here.", "geoplatform-ccb"); ?>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved. N/A
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {}
}

/**
 * Adds gpsearch front-page widget.
 */
class Geopportal_Graph_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'geopportal_graph_widget', // Base ID
			esc_html__( 'GeoPlatform Graph', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform asset graph widget for the front page. Requires the GeoPlatform Search plugin.', 'geoplatform-ccb' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		get_template_part( 'graph', get_post_format() );
	}

	public function form( $instance ) {
		$title = "GeoPlatform Graph";
		?>
		<p>
		  <?php _e("This is the GeoPlatform Graph and Search Bar widget for the front page. It will only display the search bar if the plugin is active. There are no settings involved with this widget.", "geoplatform-ccb"); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {}
}


// Includes complex widgets which regester themselves.
get_template_part( 'main-page', get_post_format() );
get_template_part( 'onboarding', get_post_format() );
get_template_part( 'portfolio-resources', get_post_format() );
get_template_part( 'apps-and-services', get_post_format() );
// get_template_part( 'portfolio-resources-dark', get_post_format() );
// get_template_part( 'portfolio-resources-old', get_post_format() );
// get_template_part( 'communities', get_post_format() );
get_template_part( 'partners', get_post_format() );
get_template_part( 'themes', get_post_format() );
get_template_part( 'side-content-text', get_post_format() );
get_template_part( 'side-content-links', get_post_format() );
get_template_part( 'side-content-preview', get_post_format() );
get_template_part( 'widget-resources-elements', get_post_format() );
get_template_part( 'widget-resources-search', get_post_format() );
get_template_part( 'widget-resources-creation', get_post_format() );
get_template_part( 'widget-resources-community', get_post_format() );


/**
 * Registers simpler widgets.
 */
function geopportal_register_portal_widgets() {
	register_widget( 'Geopportal_Contact_Widget' );
	register_widget( 'Geopportal_Graph_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_widgets' );



//-------------------------------
// Diabling auto formatting and adding <p> tags to copy/pasted HTML in pages
//-------------------------------
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );


//---------------------------------------
//Supporting Theme Customizer editing
//https://codex.wordpress.org/Theme_Customization_API
//--------------------------------------
function geop_portal_customize_register( $wp_customize )
{

		// 		//color section, settings, and controls
		// $wp_customize->add_section( 'custom_links_section' , array(
		// 		'title'    => __( 'Custom Links Section', 'starter' ),
		// 		'priority' => 60
		// ) );
		// 	$wp_customize->add_setting( 'Map_Gallery_link_box' , array(
		// 			'default'   => 'Insert Map Gallery Link here',
		// 			'transport' => 'refresh',
		// 		) );
		//   $wp_customize->add_control( 'Map_Gallery_link_box', array(
		// 			'label' => 'Map Gallery link',
		// 			'section' => 'custom_links_section',
		// 			'type' => 'url',
		// 			'priority' => 10
		// 		) );

}
add_action( 'customize_register', 'geop_portal_customize_register');


function custom_wysiwyg($post) {
  echo "<h3>Anything you add below will show up in the Banner:</h3>";
  $content = get_post_meta($post->ID, 'custom_wysiwyg', true);
  wp_editor(htmlspecialchars_decode($content) , 'custom_wysiwyg', array("media_buttons" => true));
}

function geopportal_custom_wysiwyg_save_postdata($post_id) {
  if (!empty($_POST['custom_wysiwyg'])) {
    $data = htmlspecialchars_decode($_POST['custom_wysiwyg']);
    update_post_meta($post_id, 'custom_wysiwyg', $data);
  }
}
add_action('save_post', 'geopportal_custom_wysiwyg_save_postdata');




/**********************************************************************************************************************************************
 * Creates the community post custom post type.
 */
function geop_ccb_create_community_post() {
  register_post_type( 'community-post',
    array(
      'labels' => array(
        'name' => 'Community Posts',
        'singular_name' => 'Community Post',
      ),
			'capability_type' => 'page',
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'category', 'templates'),
      'taxonomies' => array('category'),
      'publicly_queryable'  => true,
			'menu_icon' => 'dashicons-images-alt2',
    )
  );
}
add_action( 'init', 'geop_ccb_create_community_post' );




function geopccb_wysiwyg_register_custom_meta_box_compost(){
	add_meta_box(WYSIWYG_META_BOX_ID, __('Banner Area Custom Content', 'geoplatform-ccb') , 'geop_ccb_custom_wysiwyg', 'community-post');
}
add_action('admin_init', 'geopccb_wysiwyg_register_custom_meta_box_compost');


/**
 * Community Post input incorporation
 */
// register the meta box for priority AND URL.
function geop_ccb_custom_field_compost_metaboxes() {
  add_meta_box(
      'geop_ccb_compost_main_data',          // this is HTML id of the box on edit screen
      'Primary Community Data',    // title of the box
      'geop_ccb_main_data_content',   // function to be called to display the checkboxes, see the function below
      'community-post',        // on which edit screen the box should appear
      'normal',      // part of page where the box should appear
      'default'      // priority of the box
  );
  add_meta_box(
      'geop_ccb_compost_add_data',          // this is HTML id of the box on edit screen
      'Supplemental Community Data',    // title of the box
      'geop_ccb_add_data_content',   // function to be called to display the checkboxes, see the function below
      'community-post',        // on which edit screen the box should appear
      'normal',      // part of page where the box should appear
      'default'      // priority of the box
  );
	add_meta_box(
			'geop_ccb_sorting_compost_id',          // this is HTML id of the box on edit screen
			'Featured Display Priority',    // title of the box
			'geop_ccb_priority_sort_content',   // function to be called to display the checkboxes, see the function below
			'community-post',        // on which edit screen the box should appear
			'normal',      // part of page where the box should appear
			'default'      // priority of the box
	);
}
add_action( 'add_meta_boxes', 'geop_ccb_custom_field_compost_metaboxes' );

// display the metabox for com_post URL and checkbox
function geop_ccb_main_data_content($post) {
	echo "<p>Community ID:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type='text' name='geopportal_compost_community_id' id='geopportal_compost_community_id' value='" . $post->geopportal_compost_community_id . "' style='width:30%'></p>";
	echo "<p>Community URL:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type='text' name='geopportal_compost_community_url' id='geopportal_compost_community_url' value='" . $post->geopportal_compost_community_url . "' style='width:30%'></p>";
	echo "<p>Map Shortcode:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type='text' name='geopportal_compost_map_shortcode' id='geopportal_compost_map_shortcode' value='" . $post->geopportal_compost_map_shortcode . "' style='width:30%'></p>";
	echo "<p>Carousel Shortcode: <input type='text' name='geopportal_compost_carousel_shortcode' id='geopportal_compost_carousel_shortcode' value='" . $post->geopportal_compost_carousel_shortcode . "' style='width:30%'></p>";
	echo "<p>Parent Slug:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type='text' name='geopportal_compost_parent_slug' id='geopportal_compost_parent_slug' value='" . $post->geopportal_compost_parent_slug . "' style='width:30%'></p>";
	echo "<p>Content Title:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type='text' name='geopportal_compost_content_title' id='geopportal_compost_content_title' value='" . $post->geopportal_compost_content_title . "' style='width:30%'></p>";
}

// display the metabox for com_post URL and checkbox
function geop_ccb_add_data_content($post) {
	echo "<p>Sponsor Name: <input type='text' name='geopportal_compost_sponsor_name' id='geopportal_compost_sponsor_name' value='" . $post->geopportal_compost_sponsor_name . "' style='width:30%'></p>";
	echo "<p>Sponsor Email:&nbsp <input type='text' name='geopportal_compost_sponsor_email' id='geopportal_compost_sponsor_email' value='" . $post->geopportal_compost_sponsor_email . "' style='width:30%'></p>";
	echo "<p>Lead Agency:&nbsp&nbsp&nbsp&nbsp <input type='text' name='geopportal_compost_agency_name' id='geopportal_compost_agency_name' value='" . $post->geopportal_compost_agency_name . "' style='width:30%'></p>";
	echo "<p>Lead Name:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type='text' name='geopportal_compost_lead_name' id='geopportal_compost_lead_name' value='" . $post->geopportal_compost_lead_name . "' style='width:30%'></p>";
}

function geop_ccb_priority_sort_content($post) {
	echo "<input type='number' name='geop_ccb_post_priority' id='geop_ccb_post_priority' value='" . $post->geop_ccb_post_priority . "' style='width:30%;'>";
	echo "<p class='description'>Featured content is output in order from lowest value to highest.<br>It can also be set to any negative number or zero to make it not appear.<br>These settings will not take effect unless Featured Sorting is set to Custom.</p>";
}

// save data from the cat_link URL box and checkbox
function geop_ccb_custom_field_compost_data($post_id) {
  if ( !isset( $_POST['geopportal_compost_community_id'] ) || is_null( $_POST['geopportal_compost_community_id']) || empty( $_POST['geopportal_compost_community_id'] ))
    update_post_meta( $post_id, 'geopportal_compost_community_id', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_community_id', $_POST['geopportal_compost_community_id'] );
	if ( !isset( $_POST['geopportal_compost_community_url'] ) || is_null( $_POST['geopportal_compost_community_url']) || empty( $_POST['geopportal_compost_community_url'] ))
    update_post_meta( $post_id, 'geopportal_compost_community_url', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_community_url', $_POST['geopportal_compost_community_url'] );
	if ( !isset( $_POST['geopportal_compost_parent_slug'] ) || is_null( $_POST['geopportal_compost_parent_slug']) || empty( $_POST['geopportal_compost_parent_slug'] ))
    update_post_meta( $post_id, 'geopportal_compost_parent_slug', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_parent_slug', $_POST['geopportal_compost_parent_slug'] );
	if ( !isset( $_POST['geopportal_compost_content_title'] ) || is_null( $_POST['geopportal_compost_content_title']) || empty( $_POST['geopportal_compost_content_title'] ))
    update_post_meta( $post_id, 'geopportal_compost_content_title', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_content_title', $_POST['geopportal_compost_content_title'] );

	if ( !isset( $_POST['geopportal_compost_map_shortcode'] ) || is_null( $_POST['geopportal_compost_map_shortcode']) || empty( $_POST['geopportal_compost_map_shortcode'] ))
    update_post_meta( $post_id, 'geopportal_compost_map_shortcode', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_map_shortcode', $_POST['geopportal_compost_map_shortcode'] );
	if ( !isset( $_POST['geopportal_compost_carousel_shortcode'] ) || is_null( $_POST['geopportal_compost_carousel_shortcode']) || empty( $_POST['geopportal_compost_carousel_shortcode'] ))
    update_post_meta( $post_id, 'geopportal_compost_carousel_shortcode', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_carousel_shortcode', $_POST['geopportal_compost_carousel_shortcode'] );

	if ( !isset( $_POST['geopportal_compost_sponsor_name'] ) || is_null( $_POST['geopportal_compost_sponsor_name']) || empty( $_POST['geopportal_compost_sponsor_name'] ))
    update_post_meta( $post_id, 'geopportal_compost_sponsor_name', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_sponsor_name', $_POST['geopportal_compost_sponsor_name'] );

	if ( !isset( $_POST['geopportal_compost_sponsor_email'] ) || is_null( $_POST['geopportal_compost_sponsor_email']) || empty( $_POST['geopportal_compost_sponsor_email'] ))
    update_post_meta( $post_id, 'geopportal_compost_sponsor_email', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_sponsor_email', $_POST['geopportal_compost_sponsor_email'] );

	if ( !isset( $_POST['geopportal_compost_agency_name'] ) || is_null( $_POST['geopportal_compost_agency_name']) || empty( $_POST['geopportal_compost_agency_name'] ))
    update_post_meta( $post_id, 'geopportal_compost_agency_name', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_agency_name', $_POST['geopportal_compost_agency_name'] );

	if ( !isset( $_POST['geopportal_compost_lead_name'] ) || is_null( $_POST['geopportal_compost_lead_name']) || empty( $_POST['geopportal_compost_lead_name'] ))
    update_post_meta( $post_id, 'geopportal_compost_lead_name', '' );
  else
		update_post_meta( $post_id, 'geopportal_compost_lead_name', $_POST['geopportal_compost_lead_name'] );

	if ( !isset( $_POST['geop_ccb_post_priority'] ) || is_null( $_POST['geop_ccb_post_priority']) || empty( $_POST['geop_ccb_post_priority'] ))
    update_post_meta( $post_id, 'geop_ccb_post_priority', '0' );
  else
		update_post_meta( $post_id, 'geop_ccb_post_priority', $_POST['geop_ccb_post_priority'] );
}
add_action( 'save_post', 'geop_ccb_custom_field_compost_data' );

function geop_ccb_compost_column_filter( $geopccb_columns ) {
	$geopccb_columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Title',
		'categories' => 'Categories',
		'priority' => 'Priority',
		'Date' => 'Date',
	);
	return $geopccb_columns;
}
add_filter( "manage_community-post_posts_columns", "geop_ccb_compost_column_filter" );

function geop_ccb_compost_column_action( $geopccb_column, $geopccb_id ) {
	if ( $geopccb_column == 'priority' ){
		$geopccb_pri = get_post($geopccb_id)->geop_ccb_post_priority;
		if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
			$geopccb_pri = "N/A";
		echo '<p>' . $geopccb_pri . '</p>';
	}
}
add_action('manage_community-post_posts_custom_column', 'geop_ccb_compost_column_action', 10, 2);

// Adding sortation, handled functionally by the posts function.
function geop_ccb_compost_column_sorter($geopccb_columns) {
	$geopccb_columns['priority'] = 'geop_ccb_post_priority';
	return $geopccb_columns;
}
add_filter('manage_edit-community-post_sortable_columns', 'geop_ccb_compost_column_sorter');












/**
 * Breadcrumb Customization for posts, pages, and community posts.
 */

// register the meta box
function geopportal_add_breadcrumb_title() {
    add_meta_box(
        'geopportal_breadcrumb_title_id',          // this is HTML id of the box on edit screen
        'Breadcrumb and Tile Title',    // title of the box
        'geopportal_breadcrumb_box_content',   // function to be called to display the checkboxes, see the function below
				array(
					'post',
					'page',
					'community-post',
				),
        // 'post',        // on which edit screen the box should appear
        'normal',      // part of page where the box should appear
        'default'      // priority of the box
  );
}
add_action( 'add_meta_boxes', 'geopportal_add_breadcrumb_title' );

// display the metabox
function geopportal_breadcrumb_box_content($post) {
	echo "<input type='text' name='geopportal_breadcrumb_title' id='geopportal_breadcrumb_title' value='" . $post->geopportal_breadcrumb_title . "' style='width:30%;'>";
	echo "<p class='description'>Assign an optional title for the post to be displayed in the header breadcrumbs and in Resource Elements panes.<br>If left blank, the breadcrumbs and panes will display the post's proper title.</p>";
}

// save data from checkboxes
function geopportal_breadcrumb_post_data($post_id) {
  if ( !isset( $_POST['geopportal_breadcrumb_title'] ) || is_null( $_POST['geopportal_breadcrumb_title']) || empty( $_POST['geopportal_breadcrumb_title'] ))
    update_post_meta( $post_id, 'geopportal_breadcrumb_title', '0' );
  else
		update_post_meta( $post_id, 'geopportal_breadcrumb_title', $_POST['geopportal_breadcrumb_title'] );
}
add_action( 'save_post', 'geopportal_breadcrumb_post_data' );




/**
 * Breadcrumb customization for categories.
 *
 * @link https://wordpress.stackexchange.com/questions/8736/add-custom-field-to-category
*/
function geop_ccb_category_bread_interface( $tag ){
  $cat_bread = get_term_meta( $tag->term_id, 'cat_bread', true ); ?>
  <tr class='form-field'>
    <th scope='row'><label for='cat_page_visible'><?php _e('Breadcrumb Title', 'geoplatform-ccb'); ?></label></th>
    <td>
      <input type='text' name='cat_bread' id='cat_bread' value='<?php echo $cat_bread ?>'>
      <p class='description'><?php _e("Assign an optional title for the post to be displayed in the header breadcrumbs.<br>If left blank, the breadcrumbs will display the post's proper title.", 'geoplatform-ccb'); ?></p>
    </td>
  </tr> <?php
}
//add_action ( 'edit_category_form_fields', 'geop_ccb_category_bread_interface');

function geop_ccb_category_bread_update() {
  if ( isset( $_POST['cat_bread'] ) )
    update_term_meta( $_POST['tag_ID'], 'cat_bread', $_POST['cat_bread'] );
}
//add_action ( 'edited_category', 'geop_ccb_category_bread_update');








//-------------------------------
//Add extra boxes to Category editor
//-------------------------------
//https://en.bainternet.info/wordpress-category-extra-fields/


//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'extra_category_fields');

//add extra fields to category edit form callback function
function extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>
<!-- Topic 1 Name and Url -->
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-name1">Topic 1 Name</label></th>
		<td style="padding: 5px 5px;">
			<input type="text" name="Cat_meta[topic-name1]" id="Cat_meta[topic-name1]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name1'] ? $cat_meta['topic-name1'] : ''; ?>">
			<label for="url_type1" style="margin-left:1em;"><b>URL Type: </b></label>
			<select name="Cat_meta[url_type1]" id="Cat_meta[url_type1]" class="postform">
				<option value="value_1" selected="selected">NewsMap</option>
				<option value="value_2" <?php echo ($cat_meta['url_type1'] == "value_2") ? 'selected="selected"': ''; ?>>Regular</option>
			</select>
				<span class="description">  Choose "NewsMap" for link below to show your EMM NewsBrief URL, or "Regular" to use any normal full URL.</span>
		</td>
</tr>
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-url1">Topic 1 URL</label></th>
<td style="padding: 5px 5px;">
<input type="text" name="Cat_meta[topic-url1]" id="Cat_meta[topic-url1]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url1'] ? $cat_meta['topic-url1'] : ''; ?>"><br />


    </td>
</tr>


<!-- Topic 2 Name and Url -->
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-name2">Topic 2 Name</label></th>
		<td style="padding: 5px 5px;">
			<input type="text" name="Cat_meta[topic-name2]" id="Cat_meta[topic-name2]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name2'] ? $cat_meta['topic-name2'] : ''; ?>">
			<label for="url_type2" style="margin-left:1em;"><b>URL Type: </b></label>
			<select name="Cat_meta[url_type2]" id="Cat_meta[url_type2]" class="postform">
				<option value="value_1" selected="selected">NewsMap</option>
				<option value="value_2" <?php echo ($cat_meta['url_type2'] == "value_2") ? 'selected="selected"': ''; ?>>Regular</option>
			</select>
				<span class="description">  Choose "NewsMap" for link below to show your EMM NewsBrief URL, or "Regular" to use any normal full URL.</span>
		</td>
</tr>

<tr class="form-field">
<th scope="row" valign="top"><label for="topic-url2">Topic 2 URL</label></th>
<td style="padding: 5px 5px;">
<input type="text" name="Cat_meta[topic-url2]" id="Cat_meta[topic-url2]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url2'] ? $cat_meta['topic-url2'] : ''; ?>">

    </td>
</tr>

<!-- Topic 3 Name and Url -->
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-name3">Topic 3 Name</label></th>
		<td style="padding: 5px 5px;">
			<input type="text" name="Cat_meta[topic-name3]" id="Cat_meta[topic-name3]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name3'] ? $cat_meta['topic-name3'] : ''; ?>">
			<label for="url_type3" style="margin-left:1em;"><b>URL Type: </b></label>
			<select name="Cat_meta[url_type3]" id="Cat_meta[url_type3]" class="postform">
				<option value="value_1" selected="selected">NewsMap</option>
				<option value="value_2" <?php echo ($cat_meta['url_type3'] == "value_2") ? 'selected="selected"': ''; ?>>Regular</option>
			</select>
				<span class="description">  Choose "NewsMap" for link below to show your EMM NewsBrief URL, or "Regular" to use any normal full URL.</span>
		</td>
</tr>
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-url3">Topic 3 URL</label></th>
<td style="padding: 5px 5px;">
<input type="text" name="Cat_meta[topic-url3]" id="Cat_meta[topic-url3]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url3'] ? $cat_meta['topic-url3'] : ''; ?>"><br />
      </td>
</tr>

<!-- Topic 4 Name and Url -->
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-name4">Topic 4 Name</label></th>
		<td style="padding: 5px 5px;">
			<input type="text" name="Cat_meta[topic-name4]" id="Cat_meta[topic-name4]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name4'] ? $cat_meta['topic-name4'] : ''; ?>">
			<label for="url_type4" style="margin-left:1em;"><b>URL Type: </b></label>
			<select name="Cat_meta[url_type4]" id="Cat_meta[url_type4]" class="postform">
				<option value="value_1" selected="selected">NewsMap</option>
				<option value="value_2" <?php echo ($cat_meta['url_type4'] == "value_2") ? 'selected="selected"': ''; ?>>Regular</option>
			</select>
				<span class="description">  Choose "NewsMap" for link below to show your EMM NewsBrief URL, or "Regular" to use any normal full URL.</span>
		</td>
</tr>
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-url4">Topic 4 URL</label></th>
<td style="padding: 5px 5px;">
<input type="text" name="Cat_meta[topic-url4]" id="Cat_meta[topic-url4]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url4'] ? $cat_meta['topic-url4'] : ''; ?>"><br />

    </td>
</tr>

<!-- Topic 5 Name and Url -->
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-name5">Topic 5 Name</label></th>
		<td style="padding: 5px 5px;">
			<input type="text" name="Cat_meta[topic-name5]" id="Cat_meta[topic-name5]" size="20" style="width:20%;" value="<?php echo $cat_meta['topic-name5'] ? $cat_meta['topic-name5'] : ''; ?>">
			<label for="url_type5" style="margin-left:1em;"><b>URL Type: </b></label>
			<select name="Cat_meta[url_type5]" id="Cat_meta[url_type5]" class="postform">
				<option value="value_1" selected="selected">NewsMap</option>
				<option value="value_2" <?php echo ($cat_meta['url_type5'] == "value_2") ? 'selected="selected"': ''; ?>>Regular</option>
			</select>
				<span class="description">  Choose "NewsMap" for link below to show your EMM NewsBrief URL, or "Regular" to use any normal full URL.</span>
		</td>
</tr>
<tr class="form-field">
<th scope="row" valign="top"><label for="topic-url5">Topic 5 URL</label></th>
<td style="padding: 5px 5px;">
<input type="text" name="Cat_meta[topic-url5]" id="Cat_meta[topic-url5]" size="20" style="width:80%;" value="<?php echo $cat_meta['topic-url5'] ? $cat_meta['topic-url5'] : ''; ?>"><br />

    </td>
</tr>
<?php
}
// save extra category extra fields hook
add_action ( 'edited_category', 'save_extra_category_fileds');


// save extra category extra fields callback function
function save_extra_category_fileds( $term_id ) {
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

//-------------------------------
//Global Content Width
//per https://codex.wordpress.org/Content_Width#Adding_Theme_Support
//-------------------------------

if ( ! isset( $content_width ) ) {
	$content_width = 900;
}

// Bumping out category functions not needed from parent theme.
// function geop_ccb_sorting_register( $wp_customize ){};
// function geopccb_category_column_action_two( $geopccb_columns, $geopccb_column, $geopccb_id ) {};
// function geopccb_category_column_filter_two( $geopccb_columns ) {};
// function geop_ccb_category_mod_update() {};
// function geop_ccb_category_mod_interface( $tag ){};

// Bumping out page functions not needed from parent theme.
// function geop_ccb_page_column_action( $geopccb_column, $geopccb_id ){};
// function geop_ccb_custom_field_page_checkboxes(){};
// function geop_ccb_page_column_filter( $geopccb_columns ){};
// function geop_ccb_page_column_sorter($geopccb_columns){};
// function geop_ccb_feature_card_register($wp_customize){};

// Killing search register functions from CCB that have no use in Portal.
function geop_ccb_search_register(){};
function geop_ccb_linkmenu_register(){};

// Killing all CCB menu creation due to this theme's use of its own system.
function geop_ccb_register_header_menus(){};
function geop_ccb_register_comlink_menus(){};
function geop_ccb_register_footer_menus(){};

// Killing Category Links custom post type and supporting functionality from CCB theme
// function geop_ccb_create_category_post() {};
// function geop_ccb_custom_field_catlink_checkboxes() {};
// function geop_ccb_catlink_column_filter( $geopccb_columns ) {};
// function geop_ccb_catlink_column_action( $geopccb_column, $geopccb_id ) {};
// function geop_ccb_custom_field_external_url_content($post) {};
// function geop_ccb_custom_field_catlink_data($post_id) {};
// function geop_ccb_catlink_column_sorter($geopccb_columns) {};



/**
 * CDN Distribution handler
 *
 * @link https://github.com/YahnisElsts/plugin-update-checker
 */
// function geop_portal_distro_manager() {
//   require dirname(__FILE__) . '/plugin-update-checker-4.4/plugin-update-checker.php';
//   $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//   	'https://raw.githubusercontent.com/GeoPlatform/CCB-Plugins/develop/config/gp-portal-update-details.json',
//   	__FILE__,
//   	'geoplatform-portal-four'
//   );
// }
// geop_portal_distro_manager();


add_filter('widget_text', 'do_shortcode');

?>
