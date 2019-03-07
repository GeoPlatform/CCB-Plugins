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

		// Turns the slugs into pages.
		$geopportal_mainpage_disp_first_page = get_page_by_path(get_theme_mod('featured_primary_post'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_second_page = get_page_by_path(get_theme_mod('featured_secondary_one'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_third_page = get_page_by_path(get_theme_mod('featured_secondary_two'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_fourth_page = get_page_by_path(get_theme_mod('featured_secondary_three'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		$geopportal_mainpage_disp_fifth_page = get_page_by_path(get_theme_mod('featured_secondary_four'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));

		// Sets up default thumbnails and overwrites if post has one.
		$geopportal_mainpage_disp_first_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_second_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_third_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_fourth_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';
		$geopportal_mainpage_disp_fifth_thumb = get_theme_root_uri() . '/geoplatform-ccb/img/img-404.png';

		// Gets thumbnails
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

		// Sets dates.
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

		// Sets up category tags from posts.
		$geopportal_mainpage_disp_first_tags = get_the_category($geopportal_mainpage_disp_first_page->ID);
		$geopportal_mainpage_disp_second_tags = get_the_category($geopportal_mainpage_disp_second_page->ID);
		$geopportal_mainpage_disp_third_tags = get_the_category($geopportal_mainpage_disp_third_page->ID);
		$geopportal_mainpage_disp_fourth_tags = get_the_category($geopportal_mainpage_disp_fourth_page->ID);
		$geopportal_mainpage_disp_fifth_tags = get_the_category($geopportal_mainpage_disp_fifth_page->ID);

		// Makes sure browse all count is a number.
		$geopportal_mainpage_disp_more_count = get_theme_mod('featured_more_count');
		if (!is_numeric($geopportal_mainpage_disp_more_count) || $geopportal_mainpage_disp_more_count <= 0)
			$geopportal_mainpage_disp_more_count = 6;

		// Makes array of slug names for more content filtering.
		$geopportal_mainpage_disp_slug_array = array($geopportal_mainpage_disp_first_page->post_name, $geopportal_mainpage_disp_second_page->post_name, $geopportal_mainpage_disp_third_page->post_name, $geopportal_mainpage_disp_fourth_page->post_name, $geopportal_mainpage_disp_fifth_page->post_name);

		// Final sorted more content array.
		$geopportal_pages_sort = array();

		// gets posts.
		$geopportal_pages = get_posts(array(
			'orderby' => 'date',
			'order' => 'DSC',
			'numberposts' => -1,
			'post_status' => 'publish',
			'post_type' => 'post',
		) );

		// Filters posts already featured out of the featured content array.
		if (count($geopportal_pages) > 0){
			foreach($geopportal_pages as $geopportal_page){
				$geopportal_mainpage_temp_bool = true;
				for ($i = 0; $i < count($geopportal_mainpage_disp_slug_array) && $geopportal_mainpage_temp_bool; $i++){
					if ($geopportal_page->post_name == $geopportal_mainpage_disp_slug_array[$i]){
						$geopportal_mainpage_temp_bool = false;
					}
				}
				if ($geopportal_mainpage_temp_bool)
					array_push($geopportal_pages_sort, $geopportal_page);
			}
			// Removes all posts after the count set by more_count.
			$geopportal_pages_sort = array_slice($geopportal_pages_sort, 0, $geopportal_mainpage_disp_more_count);
		}

		// Quality control of browse all category, sets link to home page if fails.
		$geopportal_mainpage_browse_cat = get_page_by_path(get_theme_mod('featured_browse_slug'), OBJECT, array('post', 'page', 'geopccb_catlink', 'community-post', 'ngda-post'));
		if ($geopportal_mainpage_browse_cat)
			$geopportal_mainpage_browse_url = (get_post_type($geopportal_mainpage_browse_cat) == 'geopccb_catlink' ? esc_url($geopportal_mainpage_browse_cat->geop_ccb_cat_link_url) : get_the_permalink($geopportal_mainpage_browse_cat));
		else
			$geopportal_mainpage_browse_url = home_url();
		?>

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
													foreach ($geopportal_mainpage_disp_first_tags as $geopportal_mainpage_disp_first_tag){

														// Generates our color from the name of the category.
														$geopportal_mainpage_first_hue = floor(357 * (100000 / substr(base_convert(bin2hex($geopportal_mainpage_disp_first_tag->name . '000'), 16, 10), 0, 6)));
														?>
														<a href="<?php echo esc_url( get_category_link( $geopportal_mainpage_disp_first_tag->term_id ) ); ?>" class="a-badge" style="background-color:hsl(<?php echo $geopportal_mainpage_first_hue ?>, 60%, 35%);"><?php echo esc_attr($geopportal_mainpage_disp_first_tag->name) ?></a>
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
							$geopportal_mainpage_map_id = get_theme_mod('featured_map_id');
							$geopportal_mainpage_map_name = esc_attr(get_theme_mod('featured_map_title'));
							if (empty($geopportal_mainpage_map_id))
								$geopportal_mainpage_map_id = "1";
							$geopportal_mainpage_disp_map_short_final = "[geopmap id=" . $geopportal_mainpage_map_id . " name='" . $geopportal_mainpage_map_name . "' title=main]";
						?>
		        <div class="o-featured__map">
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
													foreach ($geopportal_mainpage_disp_second_tags as $geopportal_mainpage_disp_second_tag){
														$geopportal_mainpage_second_hue = floor(357 * (100000 / substr(base_convert(bin2hex($geopportal_mainpage_disp_second_tag->name . '000'), 16, 10), 0, 6)));
														?>
														<a href="<?php echo esc_url( get_category_link( $geopportal_mainpage_disp_second_tag->term_id ) ); ?>" class="a-badge" style="background-color:hsl(<?php echo $geopportal_mainpage_second_hue ?>, 60%, 35%);"><?php echo esc_attr($geopportal_mainpage_disp_second_tag->name) ?></a>
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
													foreach ($geopportal_mainpage_disp_third_tags as $geopportal_mainpage_disp_third_tag){
														$geopportal_mainpage_third_hue = floor(357 * (100000 / substr(base_convert(bin2hex($geopportal_mainpage_disp_third_tag->name . '000'), 16, 10), 0, 6)));
														?>
														<a href="<?php echo esc_url( get_category_link( $geopportal_mainpage_disp_third_tag->term_id ) ); ?>" class="a-badge" style="background-color:hsl(<?php echo $geopportal_mainpage_third_hue ?>, 60%, 35%);"><?php echo esc_attr($geopportal_mainpage_disp_third_tag->name); ?></a>
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
													foreach ($geopportal_mainpage_disp_fourth_tags as $geopportal_mainpage_disp_fourth_tag){
														$geopportal_mainpage_fourth_hue = floor(357 * (100000 / substr(base_convert(bin2hex($geopportal_mainpage_disp_fourth_tag->name . '000'), 16, 10), 0, 6)));
														?>
														<a href="<?php echo esc_url( get_category_link( $geopportal_mainpage_disp_fourth_tag->term_id ) ); ?>" class="a-badge" style="background-color:hsl(<?php echo $geopportal_mainpage_fourth_hue ?>, 60%, 35%);"><?php echo esc_attr($geopportal_mainpage_disp_fourth_tag->name); ?></a>
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
													foreach ($geopportal_mainpage_disp_fifth_tags as $geopportal_mainpage_disp_fifth_tag){
														$geopportal_mainpage_fifth_hue = floor(357 * (100000 / substr(base_convert(bin2hex($geopportal_mainpage_disp_fifth_tag->name . '000'), 16, 10), 0, 6)));
														?>
														<a href="<?php echo esc_url( get_category_link( $geopportal_mainpage_disp_fifth_tag->term_id ) ); ?>" class="a-badge" style="background-color:hsl(<?php echo $geopportal_mainpage_fifth_hue ?>, 60%, 35%);"><?php echo esc_attr($geopportal_mainpage_disp_fifth_tag->name); ?></a>
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
						if (count($geopportal_pages_sort) > 0){
							foreach ($geopportal_pages_sort as $geopccb_post){?>
								<div class="m-tile">
			            	<a class="is-linkless m-tile__heading" href="<?php echo get_the_permalink($geopccb_post); ?>"><?php echo get_the_title($geopccb_post); ?></a>
			            	<span class="m-tile__timestamp"><?php echo get_the_date("F j, Y", $geopccb_post->ID); ?></span>
			        	</div>
								<?php
							}
						}
						?>
					</div>

	      <a class="btn btn-light is-linkless" href="<?php echo esc_url($geopportal_mainpage_browse_url); ?>">Browse All</a>
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
		$geopportal_mainpage_more_cat = ! empty( $instance['geopportal_mainpage_more_cat'] ) ? $instance['geopportal_mainpage_more_cat'] : '0';
		$geopportal_mainpage_browse_link = ! empty( $instance['geopportal_mainpage_browse_link'] ) ? $instance['geopportal_mainpage_browse_link'] : '';
		?>

<!-- HTML for the widget control box. -->
		<p>
			<?php _e('No controls are provided for this widget here. To provide data for the Featured Posts widget, please input it under the Customize->Featured Pages section.' , 'geoplatform-ccb'); ?>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_mainpage_widget() {
		register_widget( 'Geopportal_MainPage_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_mainpage_widget' );
