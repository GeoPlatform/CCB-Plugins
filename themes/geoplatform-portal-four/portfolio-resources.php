<?php
class Geopportal_Portfolio_Resources_Widget extends WP_Widget {

	// Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_portfolio_resources_widget', // Base ID
			esc_html__( 'GeoPlatform Portfolio', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform Portfolio widget for the front page. Provides a carousel detailing services and resources offered by GeoPlatform. Each section accepts text, url, and content block input to be fleshed out. Requires the Content Blocks plugin.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
		);
	}

	// Handles the widget output.
	public function widget( $args, $instance ) {

		// Checks to see if the widget admin boxes are empty. If so, uses default
    // values. If not, pulls the values from the boxes.
		if (array_key_exists('geopportal_port_res_main_title', $instance) && isset($instance['geopportal_port_res_main_title']) && !empty($instance['geopportal_port_res_main_title']))
      $geopportal_port_res_main_title = apply_filters('widget_title', $instance['geopportal_port_res_main_title']);
		else
      $geopportal_port_res_main_title = "Explore Portfolio Resources";

    if (array_key_exists('geopportal_port_res_first_title', $instance) && isset($instance['geopportal_port_res_first_title']) && !empty($instance['geopportal_port_res_first_title']))
      $geopportal_port_res_first_title = apply_filters('widget_title', $instance['geopportal_port_res_first_title']);
		else
      $geopportal_port_res_first_title = "Data";
		if (array_key_exists('geopportal_port_res_first_content', $instance) && isset($instance['geopportal_port_res_first_content']) && !empty($instance['geopportal_port_res_first_content']))
      $geopportal_port_res_first_content = apply_filters('widget_title', $instance['geopportal_port_res_first_content']);
		else
      $geopportal_port_res_first_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_first_button_one', $instance) && isset($instance['geopportal_port_res_first_button_one']) && !empty($instance['geopportal_port_res_first_button_one']))
      $geopportal_port_res_first_button_one = apply_filters('widget_title', $instance['geopportal_port_res_first_button_one']);
		else
      $geopportal_port_res_first_button_one = "Register a Dataset";
		if (array_key_exists('geopportal_port_res_first_link_one', $instance) && isset($instance['geopportal_port_res_first_link_one']) && !empty($instance['geopportal_port_res_first_link_one']))
      $geopportal_port_res_first_link_one = apply_filters('widget_title', $instance['geopportal_port_res_first_link_one']);
		else
      $geopportal_port_res_first_link_one = "";

		if (array_key_exists('geopportal_port_res_second_title', $instance) && isset($instance['geopportal_port_res_second_title']) && !empty($instance['geopportal_port_res_second_title']))
      $geopportal_port_res_second_title = apply_filters('widget_title', $instance['geopportal_port_res_second_title']);
		else
      $geopportal_port_res_second_title = "Data Services";
		if (array_key_exists('geopportal_port_res_second_content', $instance) && isset($instance['geopportal_port_res_second_content']) && !empty($instance['geopportal_port_res_second_content']))
      $geopportal_port_res_second_content = apply_filters('widget_title', $instance['geopportal_port_res_second_content']);
		else
      $geopportal_port_res_second_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_second_button_one', $instance) && isset($instance['geopportal_port_res_second_button_one']) && !empty($instance['geopportal_port_res_second_button_one']))
      $geopportal_port_res_second_button_one = apply_filters('widget_title', $instance['geopportal_port_res_second_button_one']);
		else
      $geopportal_port_res_second_button_one = "Register a Service";
		if (array_key_exists('geopportal_port_res_second_link_one', $instance) && isset($instance['geopportal_port_res_second_link_one']) && !empty($instance['geopportal_port_res_second_link_one']))
      $geopportal_port_res_second_link_one = apply_filters('widget_title', $instance['geopportal_port_res_second_link_one']);
		else
      $geopportal_port_res_second_link_one = "";

		if (array_key_exists('geopportal_port_res_third_title', $instance) && isset($instance['geopportal_port_res_third_title']) && !empty($instance['geopportal_port_res_third_title']))
      $geopportal_port_res_third_title = apply_filters('widget_title', $instance['geopportal_port_res_third_title']);
		else
      $geopportal_port_res_third_title = "Open Maps";
		if (array_key_exists('geopportal_port_res_third_content', $instance) && isset($instance['geopportal_port_res_third_content']) && !empty($instance['geopportal_port_res_third_content']))
      $geopportal_port_res_third_content = apply_filters('widget_title', $instance['geopportal_port_res_third_content']);
		else
      $geopportal_port_res_third_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_third_button_one', $instance) && isset($instance['geopportal_port_res_third_button_one']) && !empty($instance['geopportal_port_res_third_button_one']))
      $geopportal_port_res_third_button_one = apply_filters('widget_title', $instance['geopportal_port_res_third_button_one']);
		else
      $geopportal_port_res_third_button_one = "Create an Open Map";
		if (array_key_exists('geopportal_port_res_third_link_one', $instance) && isset($instance['geopportal_port_res_third_link_one']) && !empty($instance['geopportal_port_res_third_link_one']))
      $geopportal_port_res_third_link_one = apply_filters('widget_title', $instance['geopportal_port_res_third_link_one']);
		else
      $geopportal_port_res_third_link_one = "";

		if (array_key_exists('geopportal_port_res_fourth_title', $instance) && isset($instance['geopportal_port_res_fourth_title']) && !empty($instance['geopportal_port_res_fourth_title']))
      $geopportal_port_res_fourth_title = apply_filters('widget_title', $instance['geopportal_port_res_fourth_title']);
		else
      $geopportal_port_res_fourth_title = "Galleries";
		if (array_key_exists('geopportal_port_res_fourth_content', $instance) && isset($instance['geopportal_port_res_fourth_content']) && !empty($instance['geopportal_port_res_fourth_content']))
      $geopportal_port_res_fourth_content = apply_filters('widget_title', $instance['geopportal_port_res_fourth_content']);
		else
      $geopportal_port_res_fourth_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_fourth_button_one', $instance) && isset($instance['geopportal_port_res_fourth_button_one']) && !empty($instance['geopportal_port_res_fourth_button_one']))
      $geopportal_port_res_fourth_button_one = apply_filters('widget_title', $instance['geopportal_port_res_fourth_button_one']);
		else
      $geopportal_port_res_fourth_button_one = "Create a Gallery";
		if (array_key_exists('geopportal_port_res_fourth_link_one', $instance) && isset($instance['geopportal_port_res_fourth_link_one']) && !empty($instance['geopportal_port_res_fourth_link_one']))
      $geopportal_port_res_fourth_link_one = apply_filters('widget_title', $instance['geopportal_port_res_fourth_link_one']);
		else
      $geopportal_port_res_fourth_link_one = "";

		if (array_key_exists('geopportal_port_res_fifth_title', $instance) && isset($instance['geopportal_port_res_fifth_title']) && !empty($instance['geopportal_port_res_fifth_title']))
      $geopportal_port_res_fifth_title = apply_filters('widget_title', $instance['geopportal_port_res_fifth_title']);
		else
      $geopportal_port_res_fifth_title = "Communities";
		if (array_key_exists('geopportal_port_res_fifth_content', $instance) && isset($instance['geopportal_port_res_fifth_content']) && !empty($instance['geopportal_port_res_fifth_content']))
      $geopportal_port_res_fifth_content = apply_filters('widget_title', $instance['geopportal_port_res_fifth_content']);
		else
      $geopportal_port_res_fifth_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_fifth_button_one', $instance) && isset($instance['geopportal_port_res_fifth_button_one']) && !empty($instance['geopportal_port_res_fifth_button_one']))
      $geopportal_port_res_fifth_button_one = apply_filters('widget_title', $instance['geopportal_port_res_fifth_button_one']);
		else
      $geopportal_port_res_fifth_button_one = "View Communities";
		if (array_key_exists('geopportal_port_res_fifth_link_one', $instance) && isset($instance['geopportal_port_res_fifth_link_one']) && !empty($instance['geopportal_port_res_fifth_link_one']))
      $geopportal_port_res_fifth_link_one = apply_filters('widget_title', $instance['geopportal_port_res_fifth_link_one']);
		else
      $geopportal_port_res_fifth_link_one = "";
		?>


		<!--
		PORTFOLIO RESOURCES SECTION
		-->
		<div class="p-landing-page__portfolio-wrapper t-light" id="resources">


		    <div class="p-landing-page__portfolio carousel slide" data-ride="carousel" data-interval="false"id="geopportal_anchor_carousel" style="background-image:url('<?php echo get_stylesheet_directory_uri() . '/img/wave-blue.svg' ?>')">

		        <ol class="carousel-indicators">
		            <li data-target="#geopportal_anchor_carousel" data-slide-to="0" class="active" title="<?php echo sanitize_text_field($geopportal_port_res_first_title) ?>"></li>
		            <li data-target="#geopportal_anchor_carousel" data-slide-to="1" title="<?php echo sanitize_text_field($geopportal_port_res_second_title) ?>"></li>
		            <li data-target="#geopportal_anchor_carousel" data-slide-to="2" title="<?php echo sanitize_text_field($geopportal_port_res_third_title) ?>"></li>
		            <li data-target="#geopportal_anchor_carousel" data-slide-to="3" title="<?php echo sanitize_text_field($geopportal_port_res_fourth_title) ?>"></li>
		            <li data-target="#geopportal_anchor_carousel" data-slide-to="4" title="<?php echo sanitize_text_field($geopportal_port_res_fifth_title) ?>"></li>
		        </ol>

		        <div class="m-article__heading m-article__heading--front-page">
		            <?php echo sanitize_text_field($geopportal_port_res_main_title) ?>
		        </div>
		        <div class="p-landing-page__portfolio__tabs">
		            <a class="is-linkless" onclick="cycleCarouselTo('#geopportal_anchor_carousel',0)">Data</a>
		            <a class="is-linkless" onclick="cycleCarouselTo('#geopportal_anchor_carousel',1)">Services</a>
		            <a class="is-linkless" onclick="cycleCarouselTo('#geopportal_anchor_carousel',2)">Maps</a>
		            <a class="is-linkless" onclick="cycleCarouselTo('#geopportal_anchor_carousel',3)">Galleries</a>
		            <a class="is-linkless" onclick="cycleCarouselTo('#geopportal_anchor_carousel',4)">Communities</a>
		        </div>


		        <div class="carousel-inner">

		            <div class="carousel-item active">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_first_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/data.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_first_title) ?></div>
		                        <div class="a-summary">
															<?php echo do_shortcode($geopportal_port_res_first_content) ?>
		                        </div>
		                        <br>

		                        <div class="flex-1 d-flex flex-justify-between flex-align-center">
		                            <form class="input-group-slick flex-1 geopportal_port_res_search_form" grabs-from="geop_carousel_lite_dataset_search">
		                                <span class="icon fas fa-search"></span>
		                                <input type="text" class="form-control" id="geop_carousel_lite_dataset_search" query-prefix="/#/?types=dcat:Dataset&q=" aria-label="Search GeoPlatform Datasets" placeholder="Search GeoPlatform Datasets">
		                            </form>
		                            <button type="button" class="btn btn-primary geopportal_port_res_search_button" grabs-from="geop_carousel_lite_dataset_search">SEARCH</button>
		                        </div>
		                        <br>
		                        <div>
		                            <a href="<?php echo esc_url($geopportal_port_res_first_link_one) ?>" class="btn btn-outline-secondary"><?php echo sanitize_text_field($geopportal_port_res_first_button_one) ?></a>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <div class="carousel-item">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_second_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/service.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_second_title) ?></div>
		                        <div class="a-summary">
															<?php echo do_shortcode($geopportal_port_res_second_content) ?>
		                        </div>
		                        <br>
		                        <div class="flex-1 d-flex flex-justify-between flex-align-center">
		                            <form class="input-group-slick flex-1 geopportal_port_res_search_form" grabs-from="geop_carousel_lite_service_search">
		                                <span class="icon fas fa-search"></span>
		                                <input type="text" class="form-control" id="geop_carousel_lite_service_search" query-prefix="/#/?types=regp:Service&q=" aria-label="Search GeoPlatform Web Services" placeholder="Search GeoPlatform Web Services">
		                            </form>
		                            <button type="button" class="btn btn-primary geopportal_port_res_search_button" grabs-from="geop_carousel_lite_service_search">SEARCH</button>
		                        </div>
		                        <br>
		                        <div>
		                            <a href="<?php echo esc_url($geopportal_port_res_second_link_one) ?>" class="btn btn-outline-secondary"><?php echo sanitize_text_field($geopportal_port_res_second_button_one) ?></a>
		                        </div>
		                    </div>
		                </div>
		            </div>


		            <div class="carousel-item">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_third_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/map.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_third_title) ?></div>
		                        <div class="a-summary">
															<?php echo do_shortcode($geopportal_port_res_third_content) ?>
		                        </div>
		                        <br>
		                        <div class="flex-1 d-flex flex-justify-between flex-align-center">
		                            <form class="input-group-slick flex-1 geopportal_port_res_search_form" grabs-from="geop_carousel_lite_map_search">
		                                <span class="icon fas fa-search"></span>
		                                <input type="text" class="form-control" id="geop_carousel_lite_map_search" query-prefix="/#/?types=Map&q=" aria-label="Search GeoPlatform Maps" placeholder="Search GeoPlatform Maps">
		                            </form>
		                            <button type="button" class="btn btn-primary geopportal_port_res_search_button" grabs-from="geop_carousel_lite_map_search">SEARCH</button>
		                        </div>
		                        <br>
		                        <div>
															<a href="<?php echo esc_url($geopportal_port_res_third_link_one) ?>" class="btn btn-outline-secondary"><?php echo sanitize_text_field($geopportal_port_res_third_button_one) ?><span class="fas fa-external-link-alt"></span></a>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <div class="carousel-item">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_fourth_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/collect.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_fourth_title) ?></div>
		                        <div class="a-summary">
															<?php echo do_shortcode($geopportal_port_res_fourth_content) ?>
		                        </div>
		                        <br>
		                        <div class="flex-1 d-flex flex-justify-between flex-align-center">
		                            <form class="input-group-slick flex-1 geopportal_port_res_search_form" grabs-from="geop_carousel_lite_galleries_search">
		                                <span class="icon fas fa-search"></span>
		                                <input type="text" class="form-control" id="geop_carousel_lite_galleries_search" query-prefix="/#/?types=Gallery&q=" aria-label="Search GeoPlatform Galleries" placeholder="Search GeoPlatform Galleries">
		                            </form>
		                            <button type="button" class="btn btn-primary geopportal_port_res_search_button" grabs-from="geop_carousel_lite_galleries_search">SEARCH</button>
		                        </div>
		                        <br>
		                        <div>
															<a href="<?php echo esc_url($geopportal_port_res_fourth_link_one) ?>" class="btn btn-outline-secondary" target="_blank"><?php echo sanitize_text_field($geopportal_port_res_fourth_button_one) ?><span class="fas fa-external-link-alt"></span></a>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <div class="carousel-item">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_fifth_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/community2.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_fifth_title) ?></div>
		                        <div class="a-summary">
															<?php echo do_shortcode($geopportal_port_res_fifth_content) ?>
		                        </div>
		                        <br>
		                        <div class="flex-1 d-flex flex-justify-between flex-align-center">
		                            <form class="input-group-slick flex-1 geopportal_port_res_search_form" grabs-from="geop_carousel_lite_communities_search">
		                                <span class="icon fas fa-search"></span>
		                                <input type="text" class="form-control" id="geop_carousel_lite_communities_search" query-prefix="/#/?types=Community&q=" aria-label="Search GeoPlatform Communities" placeholder="Search GeoPlatform Communities">
		                            </form>
		                            <button type="button" class="btn btn-primary geopportal_port_res_search_button" grabs-from="geop_carousel_lite_communities_search">SEARCH</button>
		                        </div>
		                        <br>
		                        <div>
															<a href="<?php echo esc_url($geopportal_port_res_fifth_link_one) ?>" class="btn btn-outline-secondary u-mg-right--md"><?php echo sanitize_text_field($geopportal_port_res_fifth_button_one) ?></a>
		                        </div>
		                    </div>
		                </div>
		            </div>

		        </div>

		        <a class="carousel-control-prev" href="#geopportal_anchor_carousel" role="button" data-slide="prev">
		            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		            <span class="sr-only">Previous</span>
		        </a>

		        <a class="carousel-control-next" href="#geopportal_anchor_carousel" role="button" data-slide="next">
		            <span class="carousel-control-next-icon" aria-hidden="true"></span>
		            <span class="sr-only">Next</span>
		        </a>

		    </div>
		</div>

				<script type="text/javascript">
					jQuery(document).ready(function() {

						jQuery(".geopportal_port_res_search_button").click(function(event){
							var temp = jQuery(this).attr("grabs-from");
							var geopportal_grabs_from = jQuery(this).attr("grabs-from");
							var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
							window.open(
								"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
								'_blank'
							);
						});

						jQuery( ".geopportal_port_res_search_form" ).submit(function(event){
							event.preventDefault();
							var geopportal_grabs_from = jQuery(this).attr("grabs-from");
							var geopportal_query_string = jQuery("#" + geopportal_grabs_from).attr("query-prefix") + jQuery("#" + geopportal_grabs_from).val();
							window.open(
								"<?php echo home_url(get_theme_mod('headlink_search'))?>" + geopportal_query_string,
								'_blank'
							);
						});
			    });
				</script>

		<?php
	}

	// The admin side of the widget
	public function form( $instance ) {

		// Checks if the Content Boxes plugin is installed.
		$geopportal_port_res_cb_bool = false;
		$geopportal_port_res_cb_message = "Content Blocks plugin not found.";
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) )){
			$geopportal_port_res_cb_bool = true;
			$geopportal_port_res_cb_message = "Click here to edit this content block";
		}

		// Main title and color settings
		$geopportal_port_res_main_title = ! empty( $instance['geopportal_port_res_main_title'] ) ? $instance['geopportal_port_res_main_title'] : 'Explore Portfolio Resources';

		// First input boxes.
		$geopportal_port_res_first_title = ! empty( $instance['geopportal_port_res_first_title'] ) ? $instance['geopportal_port_res_first_title'] : 'Data';
		$geopportal_port_res_first_content = ! empty( $instance['geopportal_port_res_first_content'] ) ? $instance['geopportal_port_res_first_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_first_button_one = ! empty( $instance['geopportal_port_res_first_button_one'] ) ? $instance['geopportal_port_res_first_button_one'] : 'Register a Dataset';
		$geopportal_port_res_first_link_one = ! empty( $instance['geopportal_port_res_first_link_one'] ) ? $instance['geopportal_port_res_first_link_one'] : '';

		// Sets up the first content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_port_res_first_content', $instance) && isset($instance['geopportal_port_res_first_content']) && !empty($instance['geopportal_port_res_first_content']) && $geopportal_port_res_cb_bool){
    	$geopportal_port_res_first_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_first_content' ]);
    	if (is_numeric($geopportal_port_res_first_temp_url))
      	$geopportal_port_res_first_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_first_temp_url . "&action=edit";
    	else
      	$geopportal_port_res_first_url = home_url();
		}
		else
			$geopportal_port_res_first_url = home_url();


		// Second input boxes.
		$geopportal_port_res_second_title = ! empty( $instance['geopportal_port_res_second_title'] ) ? $instance['geopportal_port_res_second_title'] : 'Data Servuces';
		$geopportal_port_res_second_content = ! empty( $instance['geopportal_port_res_second_content'] ) ? $instance['geopportal_port_res_second_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_second_button_one = ! empty( $instance['geopportal_port_res_second_button_one'] ) ? $instance['geopportal_port_res_second_button_one'] : 'Register a Service';
		$geopportal_port_res_second_link_one = ! empty( $instance['geopportal_port_res_second_link_one'] ) ? $instance['geopportal_port_res_second_link_one'] : '';

		// Sets up the second content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_port_res_second_content', $instance) && isset($instance['geopportal_port_res_second_content']) && !empty($instance['geopportal_port_res_second_content']) && $geopportal_port_res_cb_bool){
    	$geopportal_port_res_second_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_second_content' ]);
    	if (is_numeric($geopportal_port_res_second_temp_url))
      	$geopportal_port_res_second_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_second_temp_url . "&action=edit";
    	else
      	$geopportal_port_res_second_url = home_url();
		}
		else
			$geopportal_port_res_second_url = home_url();


		// Third input boxes.
		$geopportal_port_res_third_title = ! empty( $instance['geopportal_port_res_third_title'] ) ? $instance['geopportal_port_res_third_title'] : 'Open Maps';
		$geopportal_port_res_third_content = ! empty( $instance['geopportal_port_res_third_content'] ) ? $instance['geopportal_port_res_third_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_third_button_one = ! empty( $instance['geopportal_port_res_third_button_one'] ) ? $instance['geopportal_port_res_third_button_one'] : 'Create an Open Map';
		$geopportal_port_res_third_link_one = ! empty( $instance['geopportal_port_res_third_link_one'] ) ? $instance['geopportal_port_res_third_link_one'] : '';

		// Sets up the third content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_port_res_third_content', $instance) && isset($instance['geopportal_port_res_third_content']) && !empty($instance['geopportal_port_res_third_content']) && $geopportal_port_res_cb_bool){
    	$geopportal_port_res_third_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_third_content' ]);
    	if (is_numeric($geopportal_port_res_third_temp_url))
      	$geopportal_port_res_third_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_third_temp_url . "&action=edit";
    	else
      	$geopportal_port_res_third_url = home_url();
		}
		else
			$geopportal_port_res_third_url = home_url();


		// Fourth input boxes.
		$geopportal_port_res_fourth_title = ! empty( $instance['geopportal_port_res_fourth_title'] ) ? $instance['geopportal_port_res_fourth_title'] : 'Galleries';
		$geopportal_port_res_fourth_content = ! empty( $instance['geopportal_port_res_fourth_content'] ) ? $instance['geopportal_port_res_fourth_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_fourth_button_one = ! empty( $instance['geopportal_port_res_fourth_button_one'] ) ? $instance['geopportal_port_res_fourth_button_one'] : 'Create a Gallery';
		$geopportal_port_res_fourth_link_one = ! empty( $instance['geopportal_port_res_fourth_link_one'] ) ? $instance['geopportal_port_res_fourth_link_one'] : '';

		// Sets up the fourth content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_port_res_fourth_content', $instance) && isset($instance['geopportal_port_res_fourth_content']) && !empty($instance['geopportal_port_res_fourth_content']) && $geopportal_port_res_cb_bool){
    	$geopportal_port_res_fourth_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_fourth_content' ]);
    	if (is_numeric($geopportal_port_res_fourth_temp_url))
      	$geopportal_port_res_fourth_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_fourth_temp_url . "&action=edit";
    	else
      	$geopportal_port_res_fourth_url = home_url();
		}
		else
			$geopportal_port_res_fourth_url = home_url();

		// Fifth input boxes.
		$geopportal_port_res_fifth_title = ! empty( $instance['geopportal_port_res_fifth_title'] ) ? $instance['geopportal_port_res_fifth_title'] : 'Communities';
		$geopportal_port_res_fifth_content = ! empty( $instance['geopportal_port_res_fifth_content'] ) ? $instance['geopportal_port_res_fifth_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_fifth_button_one = ! empty( $instance['geopportal_port_res_fifth_button_one'] ) ? $instance['geopportal_port_res_fifth_button_one'] : 'View New Communities';
		$geopportal_port_res_fifth_link_one = ! empty( $instance['geopportal_port_res_fifth_link_one'] ) ? $instance['geopportal_port_res_fifth_link_one'] : '';

		// Sets up the fifth content box link, or just a home link if invalid.
		if (array_key_exists('geopportal_port_res_fifth_content', $instance) && isset($instance['geopportal_port_res_fifth_content']) && !empty($instance['geopportal_port_res_fifth_content']) && $geopportal_port_res_cb_bool){
    	$geopportal_port_res_fifth_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_fifth_content' ]);
    	if (is_numeric($geopportal_port_res_fifth_temp_url))
      	$geopportal_port_res_fifth_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_fifth_temp_url . "&action=edit";
    	else
      	$geopportal_port_res_fifth_url = home_url();
		}
		else
			$geopportal_port_res_fifth_url = home_url();


 ?>


<!-- HTML for the widget control box. -->
    <p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_main_title' ); ?>">Main Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_main_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_main_title' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_main_title ); ?>" />
    </p>
		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_title' ); ?>">First Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_first_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_title' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_first_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_content' ); ?>">First Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_port_res_first_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_content' ); ?>" value="<?php echo esc_attr($geopportal_port_res_first_content); ?>" />
			<a href="<?php echo esc_url($geopportal_port_res_first_url); ?>" target="_blank"><?php _e($geopportal_port_res_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_button_one' ); ?>">First Button Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_first_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_first_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_link_one' ); ?>">First Button URL:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_first_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_first_link_one ); ?>" />
    </p>

		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_title' ); ?>">Second Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_second_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_title' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_second_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_content' ); ?>">Second Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_port_res_second_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_content' ); ?>" value="<?php echo esc_attr($geopportal_port_res_second_content); ?>" />
			<a href="<?php echo esc_url($geopportal_port_res_second_url); ?>" target="_blank"><?php _e($geopportal_port_res_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_button_one' ); ?>">Second Button Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_second_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_second_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_link_one' ); ?>">Second Button URL:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_second_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_second_link_one ); ?>" />
    </p>

		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_title' ); ?>">Third Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_third_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_title' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_third_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_content' ); ?>">Third Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_port_res_third_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_content' ); ?>" value="<?php echo esc_attr($geopportal_port_res_third_content); ?>" />
			<a href="<?php echo esc_url($geopportal_port_res_third_url); ?>" target="_blank"><?php _e($geopportal_port_res_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_button_one' ); ?>">Third Button Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_third_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_third_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_link_one' ); ?>">Third Button URL:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_third_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_third_link_one ); ?>" />
    </p>

		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_title' ); ?>">Fourth Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_title' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fourth_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_content' ); ?>">Fourth Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_content' ); ?>" value="<?php echo esc_attr($geopportal_port_res_fourth_content); ?>" />
			<a href="<?php echo esc_url($geopportal_port_res_fourth_url); ?>" target="_blank"><?php _e($geopportal_port_res_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_button_one' ); ?>">Fourth Button Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fourth_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_link_one' ); ?>">Fourth Button URL:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_fourth_link_one ); ?>" />
    </p>

		<hr>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_title' ); ?>">Fifth Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_title' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_title' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fifth_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_content' ); ?>">Fifth Content Block Shortcode:</label><br>
			<input type="text"  id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_content' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_content' ); ?>" value="<?php echo esc_attr($geopportal_port_res_fifth_content); ?>" />
			<a href="<?php echo esc_url($geopportal_port_res_fifth_url); ?>" target="_blank"><?php _e($geopportal_port_res_cb_message, 'geoplatform-ccb') ?></a><br>
		</p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_button_one' ); ?>">Fifth Button #1 Text:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fifth_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_link_one' ); ?>">Fifth Button URL:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_fifth_link_one ); ?>" />
    </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Checks if the Content Boxes plugin is installed.
		$geopportal_port_res_cb_bool = false;
		if (in_array( 'custom-post-widget/custom-post-widget.php', (array) get_option( 'active_plugins', array() ) ))
			$geopportal_port_res_cb_bool = true;

		$instance[ 'geopportal_port_res_main_title' ] = strip_tags( $new_instance[ 'geopportal_port_res_main_title' ] );
		// $instance[ 'geopportal_port_res_color' ] = $new_instance[ 'geopportal_port_res_color' ];

		$instance[ 'geopportal_port_res_first_title' ] = strip_tags( $new_instance[ 'geopportal_port_res_first_title' ] );
	  $instance[ 'geopportal_port_res_first_content' ] = strip_tags( $new_instance[ 'geopportal_port_res_first_content' ] );
		$instance[ 'geopportal_port_res_first_button_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_first_button_one' ] );
		$instance[ 'geopportal_port_res_first_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_first_link_one' ] );
		$instance[ 'geopportal_port_res_first_url' ] = strip_tags( $new_instance[ 'geopportal_port_res_first_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_port_res_first_content', $instance) && isset($instance['geopportal_port_res_first_content']) && !empty($instance['geopportal_port_res_first_content']) && $geopportal_port_res_cb_bool){
	  	$geopportal_port_res_first_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_first_content' ]);
	  	if (is_numeric($geopportal_port_res_first_temp_url))
	    	$geopportal_port_res_first_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_first_temp_url . "&action=edit";
	  	else
	    	$geopportal_port_res_first_url = home_url();
		}
		else
			$geopportal_port_res_first_url = home_url();


		$instance[ 'geopportal_port_res_second_title' ] = strip_tags( $new_instance[ 'geopportal_port_res_second_title' ] );
	  $instance[ 'geopportal_port_res_second_content' ] = strip_tags( $new_instance[ 'geopportal_port_res_second_content' ] );
		$instance[ 'geopportal_port_res_second_button_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_second_button_one' ] );
		$instance[ 'geopportal_port_res_second_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_second_link_one' ] );
		$instance[ 'geopportal_port_res_second_url' ] = strip_tags( $new_instance[ 'geopportal_port_res_second_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_port_res_second_content', $instance) && isset($instance['geopportal_port_res_second_content']) && !empty($instance['geopportal_port_res_second_content']) && $geopportal_port_res_cb_bool){
	  	$geopportal_port_res_second_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_second_content' ]);
	  	if (is_numeric($geopportal_port_res_second_temp_url))
	    	$geopportal_port_res_second_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_second_temp_url . "&action=edit";
	  	else
	    	$geopportal_port_res_second_url = home_url();
		}
		else
			$geopportal_port_res_second_url = home_url();


		$instance[ 'geopportal_port_res_third_title' ] = strip_tags( $new_instance[ 'geopportal_port_res_third_title' ] );
	  $instance[ 'geopportal_port_res_third_content' ] = strip_tags( $new_instance[ 'geopportal_port_res_third_content' ] );
		$instance[ 'geopportal_port_res_third_button_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_third_button_one' ] );
		$instance[ 'geopportal_port_res_third_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_third_link_one' ] );
		$instance[ 'geopportal_port_res_third_url' ] = strip_tags( $new_instance[ 'geopportal_port_res_third_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_port_res_third_content', $instance) && isset($instance['geopportal_port_res_third_content']) && !empty($instance['geopportal_port_res_third_content']) && $geopportal_port_res_cb_bool){
	  	$geopportal_port_res_third_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_third_content' ]);
	  	if (is_numeric($geopportal_port_res_third_temp_url))
	    	$geopportal_port_res_third_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_third_temp_url . "&action=edit";
	  	else
	    	$geopportal_port_res_third_url = home_url();
		}
		else
			$geopportal_port_res_third_url = home_url();

		$instance[ 'geopportal_port_res_fourth_title' ] = strip_tags( $new_instance[ 'geopportal_port_res_fourth_title' ] );
	  $instance[ 'geopportal_port_res_fourth_content' ] = strip_tags( $new_instance[ 'geopportal_port_res_fourth_content' ] );
		$instance[ 'geopportal_port_res_fourth_button_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_fourth_button_one' ] );
		$instance[ 'geopportal_port_res_fourth_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_fourth_link_one' ] );
		$instance[ 'geopportal_port_res_fourth_url' ] = strip_tags( $new_instance[ 'geopportal_port_res_fourth_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_port_res_fourth_content', $instance) && isset($instance['geopportal_port_res_fourth_content']) && !empty($instance['geopportal_port_res_fourth_content']) && $geopportal_port_res_cb_bool){
	  	$geopportal_port_res_fourth_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_fourth_content' ]);
	  	if (is_numeric($geopportal_port_res_fourth_temp_url))
	    	$geopportal_port_res_fourth_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_fourth_temp_url . "&action=edit";
	  	else
	    	$geopportal_port_res_fourth_url = home_url();
		}
		else
			$geopportal_port_res_fourth_url = home_url();

		$instance[ 'geopportal_port_res_fifth_title' ] = strip_tags( $new_instance[ 'geopportal_port_res_fifth_title' ] );
	  $instance[ 'geopportal_port_res_fifth_content' ] = strip_tags( $new_instance[ 'geopportal_port_res_fifth_content' ] );
		$instance[ 'geopportal_port_res_fifth_button_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_fifth_button_one' ] );
		$instance[ 'geopportal_port_res_fifth_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_fifth_link_one' ] );
		$instance[ 'geopportal_port_res_fifth_url' ] = strip_tags( $new_instance[ 'geopportal_port_res_fifth_url' ] );

	  // Validity check for the content box URL.
		if (array_key_exists('geopportal_port_res_fifth_content', $instance) && isset($instance['geopportal_port_res_fifth_content']) && !empty($instance['geopportal_port_res_fifth_content']) && $geopportal_port_res_cb_bool){
	  	$geopportal_port_res_fifth_temp_url = preg_replace('/\D/', '', $instance[ 'geopportal_port_res_fifth_content' ]);
	  	if (is_numeric($geopportal_port_res_fifth_temp_url))
	    	$geopportal_port_res_fifth_url = home_url() . "/wp-admin/post.php?post=" . $geopportal_port_res_fifth_temp_url . "&action=edit";
	  	else
	    	$geopportal_port_res_fifth_url = home_url();
		}
		else
			$geopportal_port_res_fifth_url = home_url();

	  return $instance;
	}
}

// Registers and enqueues the widget.
function geopportal_register_portal_portfolio_resources_widget() {
		register_widget( 'Geopportal_Portfolio_Resources_Widget' );
}
add_action( 'widgets_init', 'geopportal_register_portal_portfolio_resources_widget' );
