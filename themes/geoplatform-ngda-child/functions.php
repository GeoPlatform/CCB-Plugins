<?php

function gpp_getEnv($name, $def){
	return isset($_ENV[$name]) ? $_ENV[$name] : $def;
}
$geopccb_comm_url = gpp_getEnv('comm_url',"https://www.geoplatform.gov/communities/");
$geopccb_accounts_url = gpp_getEnv('accounts_url',"https://accounts.geoplatform.gov");

function geopngda_enqueue_scripts() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'geopngda_enqueue_scripts' );

function geopngda_enqueue_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_styles', 'geopngda_enqueue_styles' );

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
						'title'    => __( 'Community Info Sidebar', 'geoplatform-ngda' ),
						'priority' => 70
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

}
add_action( 'customize_register', 'geop_ngda_customize_register');



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
