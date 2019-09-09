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

/**
 * Establish this as a child theme of GeoPlatform CCB.
 * Also loads jQuery and several assets.
 */
function geopportal_enqueue_scripts() {
	$parent_style = 'parent-style';
	wp_enqueue_style( 'fontawesome-css', 'https://use.fontawesome.com/releases/v5.7.2/css/all.css');
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'geop-style', get_template_directory_uri() . '/css/geop-style.css');
	wp_enqueue_style( 'geop-portal-style', get_template_directory_uri() . '/css/portal-style.css');
	wp_enqueue_style( 'fontello', get_stylesheet_directory_uri() . '/font/fontello.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
	wp_enqueue_style( 'flaticons-css', get_stylesheet_directory_uri() . '/font/flaticon.css');

	if (is_page_template('page-templates/page_style-guide.php'))
		wp_enqueue_style( 'styleguide-css', get_template_directory_uri() . '/css/styleguide.css');

	// wp_enqueue_script( 'popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' );
	wp_enqueue_script( 'geoplatform-ccb-js', get_template_directory_uri() . '/js/geoplatform.style.js', array('jquery'), null, true );
	wp_enqueue_script( 'geop-prism-js', get_template_directory_uri() . '/js/prism.js' );
	wp_enqueue_script( 'geop-styleguide-js', get_template_directory_uri() . '/js/styleguide.js' );
  wp_enqueue_script( 'auth', get_stylesheet_directory_uri() . '/scripts/authentication.js' );
  wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'geopportal_enqueue_scripts' );

// Loads bootstrap resources, but only for pages that aren't Angular with bundled bootstrap.
function geopportal_enqueue_bootstrap() {
	if ( !is_page( array('geoplatform-search', 'geoplatform-items', 'register', 'geoplatform-map-preview' ) ) ){
		wp_enqueue_script( 'geop_bootstrap_js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js' );
		wp_enqueue_style( 'geop_bootstrap_css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css');
	}
}
add_action( 'wp_enqueue_scripts', 'geopportal_enqueue_bootstrap' );

/**
 * Loads all the unique scripts and styles for this theme. Overrides CCB's function.
 */
function geop_ccb_scripts() {
	$geop_ccb_options = geop_ccb_get_theme_mods();
}
add_action( 'wp_enqueue_scripts', 'geop_ccb_scripts' );

// function geop_ccb_header_image_method() {}
// add_action( 'wp_enqueue_scripts', 'geop_ccb_header_image_method' );


// function geop_ccb_header_customize_css(){}
// add_action( 'wp_head', 'geop_ccb_header_customize_css');

// Disable admin bar (un-comment for prod sites)
if ( !current_user_can('administrator')){
	add_filter('show_admin_bar', '__return_false');
}

// If the homepage is navigated to via communities.geoplatform.gov, this function
// will ensure that the user is redirected to the communities resource page.
// Possesses additional check to ensure this only happens on proper portal pages.
function geop_portal_redirect(){
	$geopportal_domain = $_SERVER["SERVER_NAME"];
	$geopportal_home_url = isset($_ENV['wpp_url']) ? $_ENV['wpp_url'] : "https://www.geoplatform.gov";
	if ($geopportal_domain == "communities.geoplatform.gov" && home_url() == $geopportal_home_url){
		wp_redirect(home_url() . '/resources/communities/');
		exit;
	}
}
add_action( 'wp_loaded', 'geop_portal_redirect');

//--------------------------
//Support adding Menus for header and footer
//https://premium.wpmudev.org/blog/add-menus-to-wordpress/?utm_expid=3606929-97.J2zL7V7mQbSNQDPrXwvBgQ.0&utm_referrer=https%3A%2F%2Fwww.google.com%2F
//--------------------------
function geop_ccb_register_menus() {
  register_nav_menus(
    array(
      'headfoot-featured' => __( 'HF - Featured' ),
      'headfoot-getInvolved' => __( 'HF - Get Involved' ),
			'headfoot-exploreData' => __( 'HF - Explore Data' ),
			'headfoot-appsService' => __( 'HF - Apps & Services' ),
      'headfoot-about' => __( 'HF - About' ),
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

	$wp_customize->add_setting('headlink_ngda_themes',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_ngda_themes',array(
		'type' => 'text',
		'label' => 'NGDA Themes',
		'section' => 'headlink_format',
		'priority' => 45,
	));

	$wp_customize->add_setting('headlink_search',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_search',array(
		'type' => 'text',
		'label' => 'Search (GeoPlatform Search Plugin Page)',
		'section' => 'headlink_format',
		'priority' => 50,
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
		'priority' => 55,
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
		'priority' => 60,
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
		'priority' => 65,
	));

	$wp_customize->add_setting('headlink_apps',array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field'
	));

	$wp_customize->add_control('headlink_apps',array(
		'type' => 'text',
		'label' => "Apps & Services (Mega Menu)",
		'section' => 'headlink_format',
		'priority' => 70,
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

	// $wp_customize->add_setting('featured_more_count',array(
	// 	'default' => '',
	// 	'sanitize_callback' => 'sanitize_text_field'
	// ));
	//
	// $wp_customize->add_control('featured_more_count',array(
	// 	'type' => 'number',
	// 	'label' => "More Content Count",
	// 	'section' => 'featured_posts',
	// 	'priority' => 45,
	// ));

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
 * Widgetizing Explore Resources page one
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_one' ) ) {
	function geop_ccb_explore_resources_widgets_one() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-one',
			'name' => __( 'Resources Datasets Widgets', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Dataset Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources Services Widgets', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Service Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources Layers Widgets', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Layer Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources Maps Widgets', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Map Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources Galleries Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Gallery Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources Communities Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Communities Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources NGDA Themes Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore NGDA Themes page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources Organizations Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Organizations Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources Contacts Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Contact Resources page can be added here.', 'geoplatform-ccb' ),
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
			'name' => __( 'Resources People Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore People Resources page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_ten' );
}

/**
 * Widgetizing Explore Resources page elevin
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_elevin' ) ) {
	function geop_ccb_explore_resources_widgets_elevin() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-elevin',
			'name' => __( 'Resources Applications Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Application Resources page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_elevin' );
}

/**
 * Widgetizing Explore Resources page twelve
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_twelve' ) ) {
	function geop_ccb_explore_resources_widgets_twelve() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-twelve',
			'name' => __( 'Resources Topics Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Topic Resources page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_twelve' );
}

/**
 * Widgetizing Explore Resources page thirteen
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_thirteen' ) ) {
	function geop_ccb_explore_resources_widgets_thirteen() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-thirteen',
			'name' => __( 'Resources Websites Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Website Resources page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_thirteen' );
}

/**
 * Widgetizing Explore Resources page fourteen
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_fourteen' ) ) {
	function geop_ccb_explore_resources_widgets_fourteen() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-fourteen',
			'name' => __( 'Resources Concepts Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Concept Resources page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_fourteen' );
}

/**
 * Widgetizing Explore Resources page fifteen
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_fifteen' ) ) {
	function geop_ccb_explore_resources_widgets_fifteen() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-fifteen',
			'name' => __( 'Resources Concept Schemes Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Concept Scheme Resources page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_fifteen' );
}

/**
 * Widgetizing Explore Resources page sixteen
 */
if ( ! function_exists ( 'geop_ccb_explore_resources_widgets_sixteen' ) ) {
	function geop_ccb_explore_resources_widgets_sixteen() {
		register_sidebar(
		array(
			'id' => 'geoplatform-resources-template-widgets-sixteen',
			'name' => __( 'Resources Right Statements Template', 'geoplatform-portal-four' ),
			'description' => __( 'Widgets that go in the Explore Right Statement Resources page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_explore_resources_widgets_sixteen' );
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
get_template_part( 'partners', get_post_format() );
// get_template_part( 'portfolio-resources-dark', get_post_format() );
// get_template_part( 'portfolio-resources-old', get_post_format() );
// get_template_part( 'communities', get_post_format() );
// get_template_part( 'themes', get_post_format() );
// get_template_part( 'side-content-text', get_post_format() );
// get_template_part( 'side-content-links', get_post_format() );
// get_template_part( 'side-content-preview', get_post_format() );
// get_template_part( 'side-content-featured', get_post_format() );
get_template_part( 'widget-resources-elements', get_post_format() );
get_template_part( 'widget-resources-search', get_post_format() );
get_template_part( 'widget-resources-creation', get_post_format() );
get_template_part( 'widget-resources-community', get_post_format() );
get_template_part( 'widget-resources-ngda', get_post_format() );
get_template_part( 'widget-resources-comment', get_post_format() );
get_template_part( 'widget-sidebar-featured', get_post_format() );




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
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'category', 'templates', 'revisions'),
      'taxonomies' => array('category'),
      'publicly_queryable'  => true,
			'menu_icon' => 'dashicons-images-alt2',
			'rewrite' => array( 'slug' => 'community' ),
    )
  );
}
add_action( 'init', 'geop_ccb_create_community_post' );

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



/**********************************************************************************************************************************************
 * Creates the ngda post custom post type.
 */
function geop_ccb_create_ngda_post() {
  register_post_type( 'ngda-post',
    array(
      'labels' => array(
        'name' => 'NGDA Posts',
        'singular_name' => 'NGDA Post',
      ),
			'capability_type' => 'page',
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'category', 'templates', 'revisions'),
      'taxonomies' => array('category'),
      'publicly_queryable'  => true,
			'menu_icon' => 'dashicons-images-alt2',
			'has_archive' => false,
			'rewrite' => array( 'slug' => 'ngda' ),
    )
  );
}
add_action( 'init', 'geop_ccb_create_ngda_post' );

/**
 * NGDA Post input incorporation
 */
// register the meta box for priority AND URL.
function geop_ccb_custom_field_ngdapost_metaboxes() {
  add_meta_box(
      'geop_ccb_compost_main_data',          // this is HTML id of the box on edit screen
      'Primary Community Data',    // title of the box
      'geop_ccb_main_data_content',   // function to be called to display the checkboxes, see the function below
      'ngda-post',        // on which edit screen the box should appear
      'normal',      // part of page where the box should appear
      'default'      // priority of the box
  );
  add_meta_box(
      'geop_ccb_compost_add_data',          // this is HTML id of the box on edit screen
      'Supplemental Community Data',    // title of the box
      'geop_ccb_add_data_content',   // function to be called to display the checkboxes, see the function below
      'ngda-post',        // on which edit screen the box should appear
      'normal',      // part of page where the box should appear
      'default'      // priority of the box
  );
	add_meta_box(
			'geop_ccb_sorting_compost_id',          // this is HTML id of the box on edit screen
			'Featured Display Priority',    // title of the box
			'geop_ccb_priority_sort_content',   // function to be called to display the checkboxes, see the function below
			'ngda-post',        // on which edit screen the box should appear
			'normal',      // part of page where the box should appear
			'default'      // priority of the box
	);
}
add_action( 'add_meta_boxes', 'geop_ccb_custom_field_ngdapost_metaboxes' );



function geopccb_wysiwyg_register_custom_meta_box_compost(){
	add_meta_box(WYSIWYG_META_BOX_ID, __('Banner Area Custom Content', 'geoplatform-ccb') , 'geop_ccb_custom_wysiwyg', 'community-post');
	add_meta_box(WYSIWYG_META_BOX_ID, __('Banner Area Custom Content', 'geoplatform-ccb') , 'geop_ccb_custom_wysiwyg', 'ngda-post');
}
add_action('admin_init', 'geopccb_wysiwyg_register_custom_meta_box_compost');




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
add_filter( "manage_ngda-post_posts_columns", "geop_ccb_compost_column_filter" );

function geop_ccb_compost_column_action( $geopccb_column, $geopccb_id ) {
	if ( $geopccb_column == 'priority' ){
		$geopccb_pri = get_post($geopccb_id)->geop_ccb_post_priority;
		if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
			$geopccb_pri = "N/A";
		echo '<p>' . $geopccb_pri . '</p>';
	}
}
add_action('manage_community-post_posts_custom_column', 'geop_ccb_compost_column_action', 10, 2);
add_action('manage_ngda-post_posts_custom_column', 'geop_ccb_compost_column_action', 10, 2);

// Adding sortation, handled functionally by the posts function.
function geop_ccb_compost_column_sorter($geopccb_columns) {
	$geopccb_columns['priority'] = 'geop_ccb_post_priority';
	return $geopccb_columns;
}
add_filter('manage_edit-community-post_sortable_columns', 'geop_ccb_compost_column_sorter');
add_filter('manage_edit-ngda-post_sortable_columns', 'geop_ccb_compost_column_sorter');


function geop_ccb_blogcount_register($wp_customize){

  $wp_customize->add_setting('blogcount_controls',array(
      'default' => 7,
      'sanitize_callback' => 'geop_ccb_sanitize_blogcount',
  ));

  $wp_customize->add_control('blogcount_controls',array(
      'type' => 'number',
      'label' => 'Blog Count Controls',
      'section' => 'featured_format',
      'description' => "Choose the number of entries on each page of the blog listing post.",
  ));
}
add_action( 'customize_register', 'geop_ccb_blogcount_register');










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
					'ngda-post',
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
    update_post_meta( $post_id, 'geopportal_breadcrumb_title', '' );
  else
		update_post_meta( $post_id, 'geopportal_breadcrumb_title', $_POST['geopportal_breadcrumb_title'] );
}
add_action( 'save_post', 'geopportal_breadcrumb_post_data' );

// Overriding identical functions in CCB theme to preserve existing metadata.
function geopccb_add_breadcrumb_title(){};
function geopccb_breadcrumb_box_content($post){};
function geopccb_breadcrumb_post_data($post_id){};

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
function geop_ccb_bootstrap_register(){};
function geop_ccb_feature_card_register(){};
function geop_ccb_breadcrumb_register(){};
function geop_ccb_postbanner_register(){};

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
