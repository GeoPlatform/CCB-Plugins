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

  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
  wp_enqueue_script( 'auth', get_stylesheet_directory_uri() . '/scripts/authentication.js' );
  wp_enqueue_script( 'fixedScroll', get_stylesheet_directory_uri() . '/scripts/fixed_scroll.js');
  wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'geopportal_enqueue_scripts' );

//Disable admin bar (un-comment for prod sites)
add_filter('show_admin_bar', '__return_false');

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
      'headfoot-aboutL' => __( 'HF - About Left' ),
      'headfoot-aboutR' => __( 'HF - About Right' ),
      // 'headfoot-help' => __( 'HF - Help' ),
      // 'headfoot-themes' => __( 'HF - Themes')
    )
  );
}
add_action( 'init', 'geop_ccb_register_menus' );


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
			'name' => __( 'Frontpage Widgets', 'geoplatform-portal-child' ),
			'description' => __( 'Widgets that go on the portal front page can be added here.', 'geoplatform-ccb' ),
			'class' => 'widget-class'
		)
		);
	}
	add_action( 'widgets_init', 'geop_ccb_frontpage' );
}


/**
 * Adds sidebar accounts widget.
 */
class Geopportal_Account_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'geopportal_account_widget', // Base ID
			esc_html__( 'GeoPlatform Sidebar Account', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform account management widget for the sidebar.', 'geoplatform-ccb' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget. Just gets account template.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		get_template_part( 'account', get_post_format() );
	}

	/**
	 * Back-end widget form. Just gives text.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		?>
		<p>
		  <?php _e("This is the GeoPlatform theme account management widget for the sidebar. There are no options to customize here.", "geoplatform-ccb"); ?>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
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
class Geopportal_GPSearch_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'geopportal_gpsearch_widget', // Base ID
			esc_html__( 'GeoPlatform Search', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform search bar widget for the front page. Requires the GeoPlatform Search plugin.', 'geoplatform-ccb' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		if (in_array( 'geoplatform-search/geoplatform-search.php', (array) get_option( 'active_plugins', array() ) ))
	    get_template_part( 'gpsearch', get_post_format() );
	}

	public function form( $instance ) {
		$title = "GeoPlatform Search";
		?>
		<p>
		  <?php _e("This is the GeoPlatform theme Search bar widget for the front page. It will only display if the plugin is active. There are no settings involved with this widget.", "geoplatform-ccb"); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {}
}




/**
 * Adds map gallery front-page widget.
 */
class Geopportal_Gallery_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'geopportal_gallery_widget', // Base ID
			esc_html__( 'GeoPlatform Map Gallery', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform map gallery widget for the front page. See content for instructions.', 'geoplatform-ccb' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
    get_template_part( 'map-gallery', get_post_format() );
	}

	public function form( $instance ) {
		$title = "GeoPlatform Map Gallery";
		?>
		<p>
		  <?php _e("This is the GeoPlatform theme map gallery widget for the front page. Controls for its content can be found under Customization -> Map Gallery.", "geoplatform-ccb"); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {}
}

// Includes complex widgets which regester themselves.
get_template_part( 'apps-and-services', get_post_format() );
get_template_part( 'featured-services', get_post_format() );
get_template_part( 'main-page', get_post_format() );
get_template_part( 'main-page-two', get_post_format() );
get_template_part( 'cornerstones', get_post_format() );
get_template_part( 'featured', get_post_format() );
get_template_part( 'first-time', get_post_format() );

/**
 * Registers simpler widgets.
 */
function geopportal_register_portal_widgets() {
	register_widget( 'Geopportal_Account_Widget' );
	register_widget( 'Geopportal_Contact_Widget' );
	register_widget( 'Geopportal_GPSearch_Widget' );
	register_widget( 'Geopportal_Gallery_Widget' );
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
function geop_ccb_page_column_action( $geopccb_column, $geopccb_id ){};
function geop_ccb_custom_field_page_checkboxes(){};
function geop_ccb_page_column_filter( $geopccb_columns ){};
function geop_ccb_page_column_sorter($geopccb_columns){};
function geop_ccb_feature_card_register($wp_customize){};

// Killing search register functions from CCB that have no use in NGDA.
function geop_ccb_search_register(){};

// Killing all CCB menu creation due to this theme's use of its own system.
function geop_ccb_register_header_menus(){};
function geop_ccb_register_comlink_menus(){};
function geop_ccb_register_footer_menus(){};

// Killing Category Links custom post type and supporting functionality from CCB theme
function geop_ccb_create_category_post() {};
function geop_ccb_custom_field_catlink_checkboxes() {};
function geop_ccb_catlink_column_filter( $geopccb_columns ) {};
function geop_ccb_catlink_column_action( $geopccb_column, $geopccb_id ) {};
function geop_ccb_custom_field_external_url_content($post) {};
function geop_ccb_custom_field_catlink_data($post_id) {};
function geop_ccb_catlink_column_sorter($geopccb_columns) {};



/**
 * CDN Distribution handler
 *
 * @link https://github.com/YahnisElsts/plugin-update-checker
 */
function geop_portal_distro_manager() {
  require dirname(__FILE__) . '/plugin-update-checker-4.4/plugin-update-checker.php';
  $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  	'https://raw.githubusercontent.com/GeoPlatform/CCB-Plugins/develop/config/gp-portal-update-details.json',
  	__FILE__,
  	'geoplatform-portal-child'
  );
}
geop_portal_distro_manager();


add_filter('widget_text', 'do_shortcode');

?>
