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
 * Also loads jQuery.
 */
function geopngda_enqueue_scripts() {
	$parent_style = 'parent-style';

	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), wp_get_theme()->get('Version'));
  wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'geopngda_enqueue_scripts' );


//-------------------------------
// Diabling auto formatting and adding <p> tags to copy/pasted HTML in pages
//-------------------------------
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

//---------------------------------------
//Supporting Theme Customizer editing
//https://codex.wordpress.org/Theme_Customization_API
//--------------------------------------
function geop_ngda_customize_register( $wp_customize )
{
					//Community Info section, settings, and controls
				$wp_customize->add_section( 'community_info_section' , array(
						'title'    => __( 'Community Info Sidebar', 'geoplatform-ccb' ),
						'priority' => 65
				) );
						$wp_customize->add_setting( 'Community_Name_box' , array(
								'default'   => 'Insert Community Name here',
								'transport' => 'refresh',
								'sanitize_callback' => 'sanitize_text_field'
							) );
						$wp_customize->add_control( 'Community_Name_box', array(
								'label' => 'Community Name',
								'section' => 'community_info_section',
								'type' => 'text',
								'priority' => 10
							) );
						$wp_customize->add_setting( 'Community_Type_box' , array(
								'default'   => 'Insert Community Type here',
								'transport' => 'refresh',
								'sanitize_callback' => 'sanitize_text_field'
							) );
						$wp_customize->add_control( 'Community_Type_box', array(
								'label' => 'Community Type',
								'section' => 'community_info_section',
								'type' => 'text',
								'priority' => 20
							) );
						$wp_customize->add_setting( 'Sponsor_box' , array(
								'default'   => 'Insert Sponsor here',
								'transport' => 'refresh',
								'sanitize_callback' => 'sanitize_text_field'
							) );
						$wp_customize->add_control( 'Sponsor_box', array(
								'label' => 'Sponsor',
								'section' => 'community_info_section',
								'type' => 'text',
								'priority' => 30
							) );
						$wp_customize->add_setting( 'Sponsor_Email_box' , array(
								'default'   => 'Insert Sponsor Email here',
								'transport' => 'refresh',
								'sanitize_callback' => 'sanitize_email'
							) );
						$wp_customize->add_control( 'Sponsor_Email_box', array(
								'label' => 'Sponsor Email',
								'section' => 'community_info_section',
								'type' => 'email',
								'priority' => 40
							) );
						$wp_customize->add_setting( 'Theme_Lead_Agency_box' , array(
								'default'   => 'Insert Theme Lead Agency here',
								'transport' => 'refresh',
								'sanitize_callback' => 'sanitize_text_field'
							) );
						$wp_customize->add_control( 'Theme_Lead_Agency_box', array(
								'label' => 'Theme Lead Agency',
								'section' => 'community_info_section',
								'type' => 'text',
								'priority' => 50
							) );
						$wp_customize->add_setting( 'Theme_Lead_box' , array(
								'default'   => 'Insert Theme Lead here',
								'transport' => 'refresh',
								'sanitize_callback' => 'sanitize_text_field'
							) );
						$wp_customize->add_control( 'Theme_Lead_box', array(
								'label' => 'Theme Lead',
								'section' => 'community_info_section',
								'type' => 'text',
								'priority' => 60
							) );



							//NGDA/NCC visual format toggle section, settings, and controls
							//http://themefoundation.com/wordpress-theme-customizer/ section 5.2 Radio Buttons
							$wp_customize->add_section( 'ngda_format' , array(
									'title'    => __( 'NGDA Format', 'geoplatform-ccb' ),
									'priority' => 66
							) );

							$wp_customize->add_setting('ngda_appearance',array(
					        'default' => 'ngda',
									'sanitize_callback' => 'geop_ngda_sanitize_format'
					  	));

							$wp_customize->add_control('ngda_appearance',array(
					        'type' => 'radio',
					        'label' => 'Choose the display format',
					        'section' => 'ngda_format',
									'description' => 'Toggle between the default NGDA and specialized NCC section output formats for the Featured section.<br>NGDA permits 6 featured pages and 8 featured posts.<br>NCC permits 6 cards, combining pages and posts.',
					        'choices' => array(
					            'ngda' => __('NGDA', 'geoplatform-ccb'),
					            'ncc' => __('NCC',  'geoplatform-ccb')
										),
							));


}
add_action( 'customize_register', 'geop_ngda_customize_register');



/**
 * Deviated widget form for NGDA. Removes card format from each widget area, compressing them.
 *
 * @link https://codex.wordpress.org/Function_Reference/dynamic_sidebar
 * @link https://www.elegantthemes.com/blog/tips-tricks/how-to-manage-the-wordpress-sidebar
 * @return void
 */
function geop_ccb_sidebar() {
	register_sidebar(
	array(
		'id' => 'geoplatform-widgetized-area',
		'name' => __( 'Sidebar Widgets', 'geoplatform-ccb' ),
		'description' => __( 'Widgets that go in the sidebar can be added here', 'geoplatform-ccb' ),
		'class' => 'widget-class',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>'
	)
	);
}
add_action( 'widgets_init', 'geop_ccb_sidebar' );


//-------------------------------
//Add extra boxes to Category editor
//-------------------------------
//https://en.bainternet.info/wordpress-category-extra-fields/


//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'geop_ngda_extra_category_fields');

//add extra fields to category edit form callback function
function geop_ngda_extra_category_fields( $tag ) {    //check for existing featured ID
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

//Global Content Width
//per https://codex.wordpress.org/Content_Width#Adding_Theme_Support
//-------------------------------
if ( ! isset( $content_width ) ) {
	$content_width = 900;
}

/**
 * Sanitization callback functions for theme check
 *
 * @link https://themeshaper.com/2013/04/29/validation-sanitization-in-customizer/
 * @param [type] $geop_ccb_value
 * @return void
 */
function geop_ngda_sanitize_format( $geop_ngda_value ) {
	if ( ! in_array( $geop_ngda_value, array( 'ngda', 'ncc' ) ) )
  	$geop_ngda_value = 'ngda';
	return $geop_ngda_value;
}

// Bumping out functions not needed from parent theme.
function geopccb_category_column_action_two( $geopccb_columns, $geopccb_column, $geopccb_id ) {};
function geopccb_category_column_filter_two( $geopccb_columns ) {};
function geop_ccb_category_mod_update() {};
function geop_ccb_category_mod_interface( $tag ){};

	//---------------------------------------
	//Supporting Sort Toggle between old date system and new custom system. Overwrites the CCB version.
	//https://codex.wordpress.org/Theme_Customization_API
	//--------------------------------------
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
    'description' => 'Date sorting displays the six most recent posts and pages within the Front Page category.<br><br>Custom sorting makes use of values you assign your content in the Page and Post admin pages. Each page or post can be assigned a numeric value. Lower values will appear first, zero and negative values will not appear at all.',
		'choices' => array(
		  'custom' => __('Custom', 'geoplatform-ccb'),
			'date' => __('Date',  'geoplatform-ccb')
		),
	));
}
add_action( 'customize_register', 'geop_ccb_sorting_register');






/**
 * Post priority incorporation
 */

// register the meta box
add_action( 'add_meta_boxes', 'geop_ngda_custom_field_post_checkboxes' );
function geop_ngda_custom_field_post_checkboxes() {
    add_meta_box(
        'geop_ngda_sorting_post_id',          // this is HTML id of the box on edit screen
        'Featured Display Priority',    // title of the box
        'geop_ngda_custom_field_post_box_content',   // function to be called to display the checkboxes, see the function below
        'post',        // on which edit screen the box should appear
        'normal',      // part of page where the box should appear
        'default'      // priority of the box
    );
}

// display the metabox
function geop_ngda_custom_field_post_box_content($post) {
		echo "<input type='number' name='geop_ngda_post_priority' id='geop_ngda_post_priority' value='" . $post->geop_ngda_post_priority . "' style='width:30%;'>";
 		echo "<p class='description'>Featured content is displayed from lowest value to highest. Set to a negative number or zero to make it not appear.</p>";

}

// save data from checkboxes
add_action( 'save_post', 'geop_ngda_custom_field_post_data' );
function geop_ngda_custom_field_post_data($post_id) {
    if ( !isset( $_POST['geop_ngda_post_priority'] ) || is_null( $_POST['geop_ngda_post_priority'] || empty( $_POST['geop_ngda_post_priority'] )))
      update_post_meta( $post_id, 'geop_ngda_post_priority', 0 );
    else
  		update_post_meta( $post_id, 'geop_ngda_post_priority', $_POST['geop_ngda_post_priority'] );
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
function geopngda_post_column_filter( $geopccb_columns ) {
  $geopccb_new_columns = array();
  $geopccb_new_columns['priority'] = __('Priority', 'geoplatform-ccb');
  $geopccb_new_columns['comments'] = $geopccb_columns['comments'];
	$geopccb_new_columns['date'] = $geopccb_columns['date'];
	unset( $geopccb_columns['date'] );
  unset( $geopccb_columns['comments'] );

  return array_merge( $geopccb_columns, $geopccb_new_columns );
}

/**
 * Data added to category admin column, or N/A if not applicable.
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
function geopngda_post_column_action( $geopccb_column, $geopccb_id ) {
    if ( $geopccb_column == 'priority' ){
			$geopccb_pri = get_post($geopccb_id)->geop_ngda_post_priority;
      if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
        $geopccb_pri = "N/A";
      echo '<p>' . $geopccb_pri . '</p>';
    }
}


add_filter('manage_post_posts_columns', 'geopngda_post_column_filter');
add_action('manage_post_posts_custom_column', 'geopngda_post_column_action', 10, 2);





/**
 * Page priority incorporation
 */

// register the meta box
add_action( 'add_meta_boxes', 'geop_ngda_custom_field_page_checkboxes' );
function geop_ngda_custom_field_page_checkboxes() {
    add_meta_box(
        'geop_ngda_sorting_page_id',          // this is HTML id of the box on edit screen
        'Featured Display Priority',    // title of the box
        'geop_ngda_custom_field_post_box_content',   // function to be called to display the checkboxes, see the function below
        'page',        // on which edit screen the box should appear
        'normal',      // part of page where the box should appear
        'default'      // priority of the box
    );
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
function geopngda_page_column_filter( $geopccb_columns ) {
  $geopccb_new_columns = array();
  $geopccb_new_columns['priority'] = __('Priority', 'geoplatform-ccb');
  $geopccb_new_columns['comments'] = $geopccb_columns['comments'];
	$geopccb_new_columns['date'] = $geopccb_columns['date'];
	unset( $geopccb_columns['date'] );
  unset( $geopccb_columns['comments'] );

  return array_merge( $geopccb_columns, $geopccb_new_columns );
}

/**
 * Data added to category admin column, or N/A if not applicable.
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
function geopngda_page_column_action( $geopccb_column, $geopccb_id ) {
    if ( $geopccb_column == 'priority' ){
			$geopccb_pri = get_post($geopccb_id)->geop_ngda_post_priority;
//			var_dump(get_post($geopccb_id));
      if (!$geopccb_pri || !isset($geopccb_pri) || !is_numeric($geopccb_pri) || $geopccb_pri <= 0)
        $geopccb_pri = "N/A";
      echo '<p>' . $geopccb_pri . '</p>';
    }
}


add_filter('manage_pages_columns', 'geopngda_page_column_filter');
add_action('manage_pages_custom_column', 'geopngda_page_column_action', 10, 2);















/**
 * CDN Distribution handler
 *
 * @link https://github.com/YahnisElsts/plugin-update-checker
 */
function geop_ngda_distro_manager() {
  require dirname(__FILE__) . '/plugin-update-checker-4.4/plugin-update-checker.php';
  $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
  	'https://raw.githubusercontent.com/GeoPlatform/CCB-Plugins/develop/config/gp-ngda-update-details.json',
  	__FILE__,
  	'geoplatform-ngda-child'
  );
}
geop_ngda_distro_manager();
