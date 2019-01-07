<?php
class Geopportal_MainPage_Widget extends WP_Widget {

  // Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_mainpage_widget', // Base ID
			esc_html__( 'GeoPlatform Featured', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform featured articles widget for the front page.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

  // Handles the widget output.
	public function widget( $args, $instance ) {

    // Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
    // if (array_key_exists('geopportal_mainpage_title', $instance) && isset($instance['geopportal_mainpage_title']) && !empty($instance['geopportal_mainpage_title']))
    //   $geopportal_mainpage_disp_title = apply_filters('widget_title', $instance['geopportal_mainpage_title']);
		// else
    //   $geopportal_mainpage_disp_title = "Features &amp; Announcements";

		if (array_key_exists('geopportal_mainpage_first_link', $instance) && isset($instance['geopportal_mainpage_first_link']) && !empty($instance['geopportal_mainpage_first_link']))
      $geopportal_mainpage_disp_first_link = apply_filters('widget_title', $instance['geopportal_mainpage_first_link']);
		else
      $geopportal_mainpage_disp_first_link = "";
		if (array_key_exists('geopportal_mainpage_second_link', $instance) && isset($instance['geopportal_mainpage_second_link']) && !empty($instance['geopportal_mainpage_second_link']))
      $geopportal_mainpage_disp_second_link = apply_filters('widget_title', $instance['geopportal_mainpage_second_link']);
		else
    	$geopportal_mainpage_disp_second_link = "";
		if (array_key_exists('geopportal_mainpage_third_link', $instance) && isset($instance['geopportal_mainpage_third_link']) && !empty($instance['geopportal_mainpage_third_link']))
      $geopportal_mainpage_disp_third_link = apply_filters('widget_title', $instance['geopportal_mainpage_third_link']);
		else
      $geopportal_mainpage_disp_third_link = "";
		if (array_key_exists('geopportal_mainpage_fourth_link', $instance) && isset($instance['geopportal_mainpage_fourth_link']) && !empty($instance['geopportal_mainpage_fourth_link']))
      $geopportal_mainpage_disp_fourth_link = apply_filters('widget_title', $instance['geopportal_mainpage_fourth_link']);
		else
    	$geopportal_mainpage_disp_fourth_link = "";
		if (array_key_exists('geopportal_mainpage_fifth_link', $instance) && isset($instance['geopportal_mainpage_fifth_link']) && !empty($instance['geopportal_mainpage_fifth_link']))
      $geopportal_mainpage_disp_fifth_link = apply_filters('widget_title', $instance['geopportal_mainpage_fifth_link']);
		else
      $geopportal_mainpage_disp_fifth_link = "";

		if (array_key_exists('geopportal_mainpage_map_title', $instance) && isset($instance['geopportal_mainpage_map_title']) && !empty($instance['geopportal_mainpage_map_title']))
      $geopportal_mainpage_disp_map_title = apply_filters('widget_title', $instance['geopportal_mainpage_map_title']);
		else
      $geopportal_mainpage_disp_map_title = "";
		if (array_key_exists('geopportal_mainpage_map_shortcode', $instance) && isset($instance['geopportal_mainpage_map_shortcode']) && !empty($instance['geopportal_mainpage_map_shortcode']))
      $geopportal_mainpage_disp_map_shortcode = apply_filters('widget_title', $instance['geopportal_mainpage_map_shortcode']);
		else
      $geopportal_mainpage_disp_map_shortcode = "";
		if (array_key_exists('geopportal_mainpage_more_count', $instance) && isset($instance['geopportal_mainpage_more_count']) && !empty($instance['geopportal_mainpage_more_count']))
			$geopportal_mainpage_disp_more_count = apply_filters('widget_title', $instance['geopportal_mainpage_more_count']);
		else
			$geopportal_mainpage_disp_more_count = "6";

		if (array_key_exists('geopportal_mainpage_browse_link', $instance) && isset($instance['geopportal_mainpage_browse_link']) && !empty($instance['geopportal_mainpage_browse_link']))
			$geopportal_mainpage_disp_browse_link = apply_filters('widget_title', $instance['geopportal_mainpage_browse_link']);
		else
			$geopportal_mainpage_disp_browse_link = "";

		// Turns the slugs into pages.
		$geopportal_mainpage_disp_first_page = get_page_by_path($geopportal_mainpage_disp_first_link, OBJECT, array('post', 'page', 'geopccb_catlink'));
		$geopportal_mainpage_disp_second_page = get_page_by_path($geopportal_mainpage_disp_second_link, OBJECT, array('post', 'page', 'geopccb_catlink'));
		$geopportal_mainpage_disp_third_page = get_page_by_path($geopportal_mainpage_disp_third_link, OBJECT, array('post', 'page', 'geopccb_catlink'));
		$geopportal_mainpage_disp_fourth_page = get_page_by_path($geopportal_mainpage_disp_fourth_link, OBJECT, array('post', 'page', 'geopccb_catlink'));
		$geopportal_mainpage_disp_fifth_page = get_page_by_path($geopportal_mainpage_disp_fifth_link, OBJECT, array('post', 'page', 'geopccb_catlink'));

		// Sets up default thumbnails and overwrites if post has one.
		$geopportal_mainpage_disp_first_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_second_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_third_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_fourth_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_fifth_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';

		if ( has_post_thumbnail($geopportal_mainpage_disp_first_page) )
			$geopportal_mainpage_disp_first_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_first_page);
		if ( has_post_thumbnail($geopportal_mainpage_disp_second_page) )
			$geopportal_mainpage_disp_second_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_second_page);
		if ( has_post_thumbnail($geopportal_mainpage_disp_third_page) )
			$geopportal_mainpage_disp_third_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_third_page);
		if ( has_post_thumbnail($geopportal_mainpage_disp_fourth_page) )
			$geopportal_mainpage_disp_fourth_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_fourth_page);
		if ( has_post_thumbnail($geopportal_mainpage_disp_fifth_page) )
			$geopportal_mainpage_disp_fifth_thumb = get_the_post_thumbnail_url($geopportal_mainpage_disp_fifth_page);

		// Checks if a post is a catlink or not, grabs appropriate URL.
		$geopportal_mainpage_disp_first_url = get_post_type($geopportal_mainpage_disp_first_page) == 'geopccb_catlink' ? esc_url($geopportal_mainpage_disp_first_page->geop_ccb_cat_link_url) : get_the_permalink($geopportal_mainpage_disp_first_page);
		$geopportal_mainpage_disp_second_url = get_post_type($geopportal_mainpage_disp_second_page) == 'geopccb_catlink' ? esc_url($geopportal_mainpage_disp_second_page->geop_ccb_cat_link_url) : get_the_permalink($geopportal_mainpage_disp_second_page);
		$geopportal_mainpage_disp_third_url = get_post_type($geopportal_mainpage_disp_third_page) == 'geopccb_catlink' ? esc_url($geopportal_mainpage_disp_third_page->geop_ccb_cat_link_url) : get_the_permalink($geopportal_mainpage_disp_third_page);
		$geopportal_mainpage_disp_fourth_url = get_post_type($geopportal_mainpage_disp_fourth_page) == 'geopccb_catlink' ? esc_url($geopportal_mainpage_disp_fourth_page->geop_ccb_cat_link_url) : get_the_permalink($geopportal_mainpage_disp_fourth_page);
		$geopportal_mainpage_disp_fifth_url = get_post_type($geopportal_mainpage_disp_fifth_page) == 'geopccb_catlink' ? esc_url($geopportal_mainpage_disp_fifth_page->geop_ccb_cat_link_url) : get_the_permalink($geopportal_mainpage_disp_fifth_page);

		// Sets up invalid post notices for dates and overwrites if the associated post is valid with an actual date.
		$geopportal_mainpage_disp_first_date = "";
		$geopportal_mainpage_disp_second_date = "";
		$geopportal_mainpage_disp_third_date = "";
		$geopportal_mainpage_disp_fourth_date = "";
		$geopportal_mainpage_disp_fifth_date = "";

		if ( isset($geopportal_mainpage_disp_first_page->ID) )
			$geopportal_mainpage_disp_first_date = get_the_date("F j, Y", $geopportal_mainpage_disp_first_page->ID);
		if ( isset($geopportal_mainpage_disp_second_page->ID) )
			$geopportal_mainpage_disp_second_date = get_the_date("F j, Y", $geopportal_mainpage_disp_second_page->ID);
		if ( isset($geopportal_mainpage_disp_third_page->ID) )
			$geopportal_mainpage_disp_third_date = get_the_date("F j, Y", $geopportal_mainpage_disp_third_page->ID);
		if ( isset($geopportal_mainpage_disp_fourth_page->ID) )
			$geopportal_mainpage_disp_fourth_date = get_the_date("F j, Y", $geopportal_mainpage_disp_fourth_page->ID);
		if ( isset($geopportal_mainpage_disp_fifth_page->ID) )
			$geopportal_mainpage_disp_fifth_date = get_the_date("F j, Y", $geopportal_mainpage_disp_fifth_page->ID);

		// Sets up tags from posts, as well as the tag style array.
		$geopportal_mainpage_disp_first_tags = get_the_tags($geopportal_mainpage_disp_first_page->ID);
		$geopportal_mainpage_disp_second_tags = get_the_tags($geopportal_mainpage_disp_second_page->ID);
		$geopportal_mainpage_disp_third_tags = get_the_tags($geopportal_mainpage_disp_third_page->ID);
		$geopportal_mainpage_disp_fourth_tags = get_the_tags($geopportal_mainpage_disp_fourth_page->ID);
		$geopportal_mainpage_disp_fifth_tags = get_the_tags($geopportal_mainpage_disp_fifth_page->ID);
		$geopportal_mainpage_disp_tag_styles = array('a-badge a-badge--info', 'a-badge a-badge--warning', 'a-badge a-badge--wild');

		// Makes sure browse all count is a number.
		if (!is_numeric($geopportal_mainpage_disp_more_count) || $geopportal_mainpage_disp_more_count <= 0)
			$geopportal_mainpage_disp_more_count = 6;

		// Browse all array for the more featured content.
		$geopportal_browse_all_array = array();

		// Custom sorting method. Begins by getting all of the posts.
		$geopportal_posts = get_posts( array(
				'post_type'   => 'post',
				'numberposts' => -1
		) );

		// Assigns posts with valid priority values to the trimmed array.
		$geopportal_posts_trimmed = array();
		foreach($geopportal_posts as $geopportal_post){
			if ($geopportal_post->geop_ccb_post_priority > 0)
				array_push($geopportal_posts_trimmed, $geopportal_post);
		}

		// Bubble sorts the resulting posts.
		$geopportal_posts_size = count($geopportal_posts_trimmed)-1;
		for ($i = 0; $i < $geopportal_posts_size; $i++) {
			for ($j = 0; $j < $geopportal_posts_size - $i; $j++) {
				$k = $j + 1;
				$geopportal_test_left = $geopportal_posts_trimmed[$j]->geop_ccb_post_priority;
				$geopportal_test_right = $geopportal_posts_trimmed[$k]->geop_ccb_post_priority;
				if ($geopportal_test_left > $geopportal_test_right) {
					// Swap elements at indices: $j, $k
					list($geopportal_posts_trimmed[$j], $geopportal_posts_trimmed[$k]) = array($geopportal_posts_trimmed[$k], $geopportal_posts_trimmed[$j]);
				}
			}
		}

		// Removes all posts after the count set by more_count.
		$geopportal_browse_all_array = array_slice($geopportal_posts_trimmed, 0, $geopportal_mainpage_disp_more_count);
		?>

		<!-- For tag reference, need extra hyphen after each 'badge' -->
		<!-- <a href="search.html" class="a-badge a-badge-info">tag</a>
		<a href="search.html" class="a-badge a-badge-warning">keyword</a>
		<a href="search.html" class="a-badge a-badge-wild">category</a> -->


		<!--
		FEATURED ITEMS SECTION
		-->
		<div class="o-featured" id="featured">
		    <div class="o-featured__main">
		        <div class="o-featured__primary">
		            <div class="m-tile m-tile--16x9">
		                <div class="m-tile__thumbnail">
		                    <img alt="<?php echo get_the_title($geopportal_mainpage_disp_first_page); ?>" src="<?php echo $geopportal_mainpage_disp_first_thumb; ?>">
		                </div>
		                <div class="m-tile__body">
		                    <a href="<?php echo $geopportal_mainpage_disp_first_url; ?>" class="m-tile__heading"><?php echo get_the_title($geopportal_mainpage_disp_first_page); ?></a>
		                    <div class="m-tile__timestamp"><?php echo $geopportal_mainpage_disp_first_date; ?></div>
		                    <div class="m-tile__tags"><?php
												if ($geopportal_mainpage_disp_first_tags){
													$i = 0;
													foreach ($geopportal_mainpage_disp_first_tags as $geopportal_mainpage_disp_first_tag){?>
														<a href="<?php echo home_url(get_theme_mod('headlink_search')) . '/#/?q=' . $geopportal_mainpage_disp_first_tag->name ?>" class="a-badge <?php echo $geopportal_mainpage_disp_tag_styles[$i]?>"><?php echo $geopportal_mainpage_disp_first_tag->name ?></a>
														<?php
														$i >= 2 ? $i = 0 : $i++;
													}
												}
												?>
		                    </div>
		                </div>
		            </div>
		        </div>
						<?php
							$geopportal_mainpage_disp_map_short_final = "[geopmap id=" . $geopportal_mainpage_disp_map_shortcode . " title=main]";
						?>
		        <div class="o-featured__map">
		            <div class="o-featured__map__heading"><?php echo esc_attr($geopportal_mainpage_disp_map_title) ?></div>
		            <div class="m-map" id="featuredMap">
									<?php echo do_shortcode($geopportal_mainpage_disp_map_short_final) ?>
								</div>
		        </div>


		        <div class="o-featured__secondary">
		            <div class="m-tile m-tile--16x9">
		                <div class="m-tile__thumbnail">
		                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_mainpage_disp_second_thumb; ?>">
		                </div>
		                <div class="m-tile__body">
		                    <a href="<?php echo $geopportal_mainpage_disp_second_url; ?>" class="m-tile__heading"><?php echo get_the_title($geopportal_mainpage_disp_second_page); ?></a>
		                    <div class="m-tile__timestamp"><?php echo $geopportal_mainpage_disp_second_date; ?></div>
		                    <div class="m-tile__tags"><?php
												if ($geopportal_mainpage_disp_second_tags){
													$i = 0;
													foreach ($geopportal_mainpage_disp_second_tags as $geopportal_mainpage_disp_second_tag){?>
														<a href="<?php echo home_url(get_theme_mod('headlink_search')) . '/#/?q=' . $geopportal_mainpage_disp_second_tag->name ?>" class="a-badge <?php echo $geopportal_mainpage_disp_tag_styles[$i]?>"><?php echo $geopportal_mainpage_disp_second_tag->name ?></a>
														<?php
														$i >= 2 ? $i = 0 : $i++;
													}
												}
												?>
		                    </div>
		                </div>
		            </div>
		            <div class="m-tile m-tile--16x9">
		                <div class="m-tile__thumbnail">
		                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_mainpage_disp_third_thumb; ?>">
		                </div>
		                <div class="m-tile__body">
		                    <a href="<?php echo $geopportal_mainpage_disp_third_url; ?>" class="m-tile__heading"><?php echo get_the_title($geopportal_mainpage_disp_third_page); ?></a>
		                    <div class="m-tile__timestamp"><?php echo $geopportal_mainpage_disp_third_date; ?></div>
		                    <div class="m-tile__tags"><?php
												if ($geopportal_mainpage_disp_third_tags){
													$i = 0;
													foreach ($geopportal_mainpage_disp_third_tags as $geopportal_mainpage_disp_third_tag){?>
														<a href="<?php echo home_url(get_theme_mod('headlink_search')) . '/#/?q=' . $geopportal_mainpage_disp_third_tag->name ?>" class="a-badge <?php echo $geopportal_mainpage_disp_tag_styles[$i]?>"><?php echo $geopportal_mainpage_disp_third_tag->name ?></a>
														<?php
														$i >= 2 ? $i = 0 : $i++;
													}
												}
												?>
		                    </div>
		                </div>
		            </div>
		            <div class="m-tile m-tile--16x9">
		                <div class="m-tile__thumbnail">
		                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_mainpage_disp_fourth_thumb; ?>">
		                </div>
		                <div class="m-tile__body">
		                    <a href="<?php echo $geopportal_mainpage_disp_fourth_url; ?>" class="m-tile__heading"><?php echo get_the_title($geopportal_mainpage_disp_fourth_page); ?></a>
		                    <div class="m-tile__timestamp"><?php echo $geopportal_mainpage_disp_fourth_date; ?></div>
		                    <div class="m-tile__tags"><?php
												if ($geopportal_mainpage_disp_fourth_tags){
													$i = 0;
													foreach ($geopportal_mainpage_disp_fourth_tags as $geopportal_mainpage_disp_fourth_tag){?>
														<a href="<?php echo home_url(get_theme_mod('headlink_search')) . '/#/?q=' . $geopportal_mainpage_disp_fourth_tag->name ?>" class="a-badge <?php echo $geopportal_mainpage_disp_tag_styles[$i]?>"><?php echo $geopportal_mainpage_disp_fourth_tag->name ?></a>
														<?php
														$i >= 2 ? $i = 0 : $i++;
													}
												}
												?>
		                    </div>
		                </div>
		            </div>
		            <div class="m-tile m-tile--16x9">
		                <div class="m-tile__thumbnail">
		                    <img alt="This is alternative text for the thumbnail" src="<?php echo $geopportal_mainpage_disp_fifth_thumb; ?>">
		                </div>
		                <div class="m-tile__body">
		                    <a href="<?php echo $geopportal_mainpage_disp_fifth_url; ?>" class="m-tile__heading"><?php echo get_the_title($geopportal_mainpage_disp_fifth_page); ?></a>
		                    <div class="m-tile__timestamp"><?php echo $geopportal_mainpage_disp_fifth_date; ?></div>
		                    <div class="m-tile__tags"><?php
												if ($geopportal_mainpage_disp_fifth_tags){
													$i = 0;
													foreach ($geopportal_mainpage_disp_fifth_tags as $geopportal_mainpage_disp_fifth_tag){?>
														<a href="<?php echo home_url(get_theme_mod('headlink_search')) . '/#/?q=' . $geopportal_mainpage_disp_fifth_tag->name ?>" class="a-badge <?php echo $geopportal_mainpage_disp_tag_styles[$i]?>"><?php echo $geopportal_mainpage_disp_fifth_tag->name ?></a>
														<?php
														$i >= 2 ? $i = 0 : $i++;
													}
												}
												?>
		                    </div>
		                </div>
		            </div>
		        </div>

		    </div>

		    <div class="o-featured__side">
					<div class="a-heading">More Featured Content</div>
					<div class="o-featured__tertiary">

						<?php
						// Outputs posts.
						$geopportal_first_bool = true;
						foreach ($geopportal_browse_all_array as $geopccb_post){?>
							<div class="m-tile">
		            <a class="is-linkless m-tile__heading" href="<?php echo get_the_permalink($geopccb_post); ?>"><?php echo get_the_title($geopccb_post); ?></a><br>
		            <span class="m-tile__timestamp"><?php echo get_the_date("F j, Y", $geopccb_post->ID); ?></span>
		        	</div>
							<?php
						}
						?>
					</div>

	      <a class="btn btn-light is-linkless" href="<?php echo esc_url(home_url($geopportal_mainpage_disp_browse_link)) . '/'; ?>">Browse All</a>
			</div>
		</div>
    <?php
	}

  // The admin side of the widget.
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpage_cb_bool = false;
		$geopportal_mainpage_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_mainpage_cb_bool = true;
			$geopportal_mainpage_cb_message = "Click here to edit this content block";
		}

    // Checks for entries in the widget admin boxes and provides defaults if empty.
    // $geopportal_mainpage_title = ! empty( $instance['geopportal_mainpage_title'] ) ? $instance['geopportal_mainpage_title'] : 'Features &amp; Announcements';
		$geopportal_mainpage_first_link = ! empty( $instance['geopportal_mainpage_first_link'] ) ? $instance['geopportal_mainpage_first_link'] : '';
		$geopportal_mainpage_second_link = ! empty( $instance['geopportal_mainpage_second_link'] ) ? $instance['geopportal_mainpage_second_link'] : '';
		$geopportal_mainpage_third_link = ! empty( $instance['geopportal_mainpage_third_link'] ) ? $instance['geopportal_mainpage_third_link'] : '';
		$geopportal_mainpage_fourth_link = ! empty( $instance['geopportal_mainpage_fourth_link'] ) ? $instance['geopportal_mainpage_fourth_link'] : '';
		$geopportal_mainpage_fifth_link = ! empty( $instance['geopportal_mainpage_fifth_link'] ) ? $instance['geopportal_mainpage_fifth_link'] : '';
		$geopportal_mainpage_map_title = ! empty( $instance['geopportal_mainpage_map_title'] ) ? $instance['geopportal_mainpage_map_title'] : '';
		$geopportal_mainpage_map_shortcode = ! empty( $instance['geopportal_mainpage_map_shortcode'] ) ? $instance['geopportal_mainpage_map_shortcode'] : '';
		$geopportal_mainpage_more_count = ! empty( $instance['geopportal_mainpage_more_count'] ) ? $instance['geopportal_mainpage_more_count'] : '0';
		$geopportal_mainpage_browse_link = ! empty( $instance['geopportal_mainpage_browse_link'] ) ? $instance['geopportal_mainpage_browse_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('The boxes below accept the slugs of the linked post. Please ensure that any input slugs are valid.<br>Ensure you enter a valid map ID, not shortcode. The GeoPlatform Maps plugin will construct the necessary parameters itself.', 'geoplatform-ccb'); ?>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_first_link' ); ?>">Primary Post Slug:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_first_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_first_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_first_link ); ?>" />
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_second_link' ); ?>">First Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_second_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_second_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_second_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_third_link' ); ?>">Second Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_third_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_third_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_third_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_fourth_link' ); ?>">Third Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_fourth_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_fourth_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_fourth_link ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_fifth_link' ); ?>">Fourth Sub-Feature Post Slug:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_fifth_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_fifth_link' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_fifth_link ); ?>" />
		</p>
		<hr>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_map_title' ); ?>">Map Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_map_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_map_title' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_map_title ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_mainpage_map_shortcode' ); ?>">Map ID:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_mainpage_map_shortcode' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_map_shortcode' ); ?>" value="<?php echo esc_attr( $geopportal_mainpage_map_shortcode ); ?>" />
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_more_count' ); ?>">Featured Content Count:</label><br>
			<input type="number"  id="<?php echo $this->get_field_id( 'geopportal_mainpage_more_count' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_more_count' ); ?>" value="<?php echo esc_attr($geopportal_mainpage_more_count); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_mainpage_browse_link' ); ?>">Browse All Slug:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_mainpage_browse_link' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_mainpage_browse_link' ); ?>" value="<?php echo esc_attr($geopportal_mainpage_browse_link); ?>" />
		</p>
		<p>
			<?php _e('Tertiary content is controlled by the post priority settings. Navigate to the admin post panel to set these.', 'geoplatform-ccb'); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_mainpage_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_mainpage_cb_bool = true;

    // $instance[ 'geopportal_mainpage_title' ] = strip_tags( $new_instance[ 'geopportal_mainpage_title' ] );
		$instance[ 'geopportal_mainpage_first_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_first_link' ] );
		$instance[ 'geopportal_mainpage_second_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_second_link' ] );
		$instance[ 'geopportal_mainpage_third_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_third_link' ] );
		$instance[ 'geopportal_mainpage_fourth_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_fourth_link' ] );
		$instance[ 'geopportal_mainpage_fifth_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_fifth_link' ] );
		$instance[ 'geopportal_mainpage_map_title' ] = strip_tags( $new_instance[ 'geopportal_mainpage_map_title' ] );
		$instance[ 'geopportal_mainpage_map_shortcode' ] = strip_tags( $new_instance[ 'geopportal_mainpage_map_shortcode' ] );
		$instance[ 'geopportal_mainpage_more_count' ] = strip_tags( $new_instance[ 'geopportal_mainpage_more_count' ] );
		$instance[ 'geopportal_mainpage_browse_link' ] = strip_tags( $new_instance[ 'geopportal_mainpage_browse_link' ] );

		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_mainpage_widget() {
		register_widget( 'Geopportal_MainPage_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_mainpage_widget' );
