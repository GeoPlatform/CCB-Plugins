<?php

function gpp_getEnv($name, $def){
	return isset($_ENV[$name]) ? $_ENV[$name] : $def;
}
$geopccb_comm_url = gpp_getEnv('comm_url',"https://www.geoplatform.gov/communities/");
$geopccb_accounts_url = gpp_getEnv('accounts_url',"https://accounts.geoplatform.gov");

function geopportal_enqueue_scripts() {
    wp_enqueue_script( 'auth', get_stylesheet_directory_uri() . '/scripts/authentication.js' );
    wp_enqueue_script( 'fixedScroll', get_stylesheet_directory_uri() . '/scripts/fixed_scroll.js');
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'geopportal_enqueue_scripts' );

function geopportal_enqueue_styles() {
  // $parent_style = 'custom-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
  //
  // wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
  // wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );


}
add_action( 'wp_enqueue_styles', 'geopportal_enqueue_styles' );

//Disable admin bar (un-comment for prod sites)
// add_filter('show_admin_bar', '__return_false');

//--------------------------
//Support adding Menus for header and footer
//https://premium.wpmudev.org/blog/add-menus-to-wordpress/?utm_expid=3606929-97.J2zL7V7mQbSNQDPrXwvBgQ.0&utm_referrer=https%3A%2F%2Fwww.google.com%2F
//--------------------------
function geop_ccb_register_menus() {
  register_nav_menus(
    array(
      'headfoot-featured' => __( 'HF - Featured' ),
      'headfoot-getInvolved' => __( 'HF - Get Involved' ),
      'headfoot-appservices' => __( 'HF - Apps and Services' ),
      'headfoot-aboutL' => __( 'HF - About Left' ),
      'headfoot-aboutR' => __( 'HF - About Right' ),
      'headfoot-help' => __( 'HF - Help' ),
      'headfoot-themes' => __( 'HF - Themes')
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
 * Adds sidebar accounts widget.
 */
class Geopportal_Account_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'geopportal_account_widget', // Base ID
			esc_html__( 'GeoPlatform Account Widget', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Account Widget', 'geoplatform-ccb' ), ) // Args
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
		$title = "Account Management";
		?>
		<p>
		  The GeoPlatform theme Account Management widget.
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
 * Adds sidebar featured services widget.
 */
class Geopportal_Featured_Sidebar_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'geopportal_featured_sidebar_widget', // Base ID
			esc_html__( 'GeoPlatform Featured Sidebar Widget', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Featured Sidebar Widget', 'geoplatform-ccb' ), ) // Args
		);
	}

	/**
	* Front-end display of widget. Just gets featured-services template.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		get_template_part( 'featured-services', get_post_format() );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = "Sidebar Featured Services";
		?>
		<p>
		  The GeoPlatform theme Featured Services widget.
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}
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
			esc_html__( 'GeoPlatform Contact Widget', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Contact Widget', 'geoplatform-ccb' ), ) // Args
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
		$title = "Contact Information";
		?>
		<p>
		  The GeoPlatform theme contact information widget.
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


function geopportal_register_widgets() {
    register_widget( 'Geopportal_Account_Widget' );
		register_widget( 'Geopportal_Featured_Sidebar_Widget' );
		register_widget( 'Geopportal_Contact_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_widgets' );


//-------------------------------
// Diabling auto formatting and adding <p> tags to copy/pasted HTML in pages
//-------------------------------
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );


//---------------------------------------
//Supporting Theme Customizer editing
//https://codex.wordpress.org/Theme_Customization_API
//--------------------------------------
function geop_ccb_customize_register( $wp_customize )
{
		//color section, settings, and controls
    $wp_customize->add_section( 'header_color_section' , array(
        'title'    => __( 'Header Color Section', 'geoplatform-2017-theme' ),
        'priority' => 30
    ) );

		//h1 color setting and control
		$wp_customize->add_setting( 'header_color_setting' , array(
				'default'   => '#000000',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_link_color', array(
				'label'    => __( 'Header1 Color', 'geoplatform-2017-theme' ),
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
				'label'    => __( 'Header 2 Color', 'geoplatform-2017-theme' ),
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
				'label'    => __( 'Header 3 Color', 'geoplatform-2017-theme' ),
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
				'label'    => __( 'Header 4 Color', 'geoplatform-2017-theme' ),
				'section'  => 'header_color_section',
				'settings' => 'header4_color_setting',
		) ) );

    //link (<a>) color and control
		$wp_customize->add_setting( 'link_color_setting' , array(
				'default'   => '#000000',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'a_link_color', array(
				'label'    => __( 'Link Color', 'geoplatform-2017-theme' ),
				'section'  => 'header_color_section',
				'settings' => 'link_color_setting',
		) ) );

		//.brand color and control
		$wp_customize->add_setting( 'brand_color_setting' , array(
				'default'   => '#000000',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'brand_color', array(
				'label'    => __( 'Brand Color', 'geoplatform-2017-theme' ),
				'section'  => 'header_color_section',
				'settings' => 'brand_color_setting',
		) ) );



		//Fonts section, settings, and controls
		//http://themefoundation.com/wordpress-theme-customizer/ section 5.2 Radio Buttons
		$wp_customize->add_section( 'font_section' , array(
				'title'    => __( 'Font Section', 'geoplatform-2017-theme' ),
				'priority' => 50
			) );

		$wp_customize->add_setting('font_choice',array(
        'default' => 'lato',
				'sanitize_callback' => 'geop_sanitize_fonts',
    	));

		$wp_customize->add_control('font_choice',array(
        'type' => 'radio',
        'label' => 'Fonts',
        'section' => 'font_section',
        'choices' => array(
            'lato' => __('Lato', 'geoplatform-2017-theme'),
            'slabo' => __('Slabo',  'geoplatform-2017-theme')
						),
				));

		//Banner Intro Text editor section, settings, and controls
		// pulled from https://wpshout.com/making-themes-more-wysiwyg-with-the-wordpress-customizer/
		//fixed some issues with linking up through https://github.com/paulund/wordpress-theme-customizer-custom-controls/issues/4
		$wp_customize->add_section( 'banner_text_section' , array(
				'title'    => __( 'Banner Area', 'geoplatform-2017-theme' ),
				'priority' => 50
			));



         // Add a text editor control
         require_once dirname(__FILE__) . '/text/text-editor-custom-control.php';
         $wp_customize->add_setting( 'text_editor_setting', array(
             'default'        => '',
						 'transport' => 'refresh',
						 'sanitize_callback' => 'wp_kses_post'
         ) );
         $wp_customize->add_control( new Text_Editor_Custom_Control( $wp_customize, 'text_editor_setting', array(
             'label'   => 'Banner Text Editor',
             'section' => 'banner_text_section',
             'settings'   => 'text_editor_setting',
             'priority' => 20
         ) ) );


				//color section, settings, and controls
		$wp_customize->add_section( 'custom_links_section' , array(
				'title'    => __( 'Custom Links Section', 'starter' ),
				'priority' => 60
		) );
			$wp_customize->add_setting( 'Map_Gallery_link_box' , array(
					'default'   => 'Insert Map Gallery Link here',
					'transport' => 'refresh',
				) );
		  $wp_customize->add_control( 'Map_Gallery_link_box', array(
					'label' => 'Map Gallery link',
					'section' => 'custom_links_section',
					'type' => 'url',
					'priority' => 10
				) );

				//remove default colors section as Header Color Section does this job better
				 $wp_customize->remove_section( 'colors' );

				 //Remove default Menus and Static Front page sections as this theme doesn't utilize them at this time
				 // $wp_customize->remove_panel( 'nav_menus');
				 $wp_customize->remove_section( 'static_front_page' );

				 //remove site tagline and checkbox for showing site title and tagline from Site Identity section
				 //; Not needed for the theme
				 $wp_customize->remove_control('blogdescription');
				 $wp_customize->remove_control('display_header_text');

}
add_action( 'customize_register', 'geop_ccb_customize_register');


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

?>