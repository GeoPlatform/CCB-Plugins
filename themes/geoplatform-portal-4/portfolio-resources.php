<?php
class Geopportal_Portfolio_Resources_Widget extends WP_Widget {

	// Constructor. Simple.
	function __construct() {
		parent::__construct(
			'geopportal_portfolio_resources_widget', // Base ID
			esc_html__( 'GeoPlatform Portfolio Resources', 'geoplatform-ccb' ), // Name
			array( 'description' => esc_html__( 'GeoPlatform portfolio resources widget for the front page. Requires the Content Blocks plugin.', 'geoplatform-ccb' ), 'customize_selective_refresh' => true) // Args
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
		// if (array_key_exists('geopportal_port_res_color', $instance) && isset($instance['geopportal_port_res_color']) && !empty($instance['geopportal_port_res_color']))
		// 	$geopportal_port_res_color = $instance[ 'geopportal_port_res_color' ] ? 'true' : 'false';
		// else
		// 	$geopportal_port_res_color = 'true';


    if (array_key_exists('geopportal_port_res_first_title', $instance) && isset($instance['geopportal_port_res_first_title']) && !empty($instance['geopportal_port_res_first_title']))
      $geopportal_port_res_first_title = apply_filters('widget_title', $instance['geopportal_port_res_first_title']);
		else
      $geopportal_port_res_first_title = "Open Maps";
		if (array_key_exists('geopportal_port_res_first_content', $instance) && isset($instance['geopportal_port_res_first_content']) && !empty($instance['geopportal_port_res_first_content']))
      $geopportal_port_res_first_content = apply_filters('widget_title', $instance['geopportal_port_res_first_content']);
		else
      $geopportal_port_res_first_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_first_button_one', $instance) && isset($instance['geopportal_port_res_first_button_one']) && !empty($instance['geopportal_port_res_first_button_one']))
      $geopportal_port_res_first_button_one = apply_filters('widget_title', $instance['geopportal_port_res_first_button_one']);
		else
      $geopportal_port_res_first_button_one = "Explore Open Maps";
		if (array_key_exists('geopportal_port_res_first_link_one', $instance) && isset($instance['geopportal_port_res_first_link_one']) && !empty($instance['geopportal_port_res_first_link_one']))
      $geopportal_port_res_first_link_one = apply_filters('widget_title', $instance['geopportal_port_res_first_link_one']);
		else
      $geopportal_port_res_first_link_one = "";
		if (array_key_exists('geopportal_port_res_first_button_two', $instance) && isset($instance['geopportal_port_res_first_button_two']) && !empty($instance['geopportal_port_res_first_button_two']))
      $geopportal_port_res_first_button_two = apply_filters('widget_title', $instance['geopportal_port_res_first_button_two']);
		else
      $geopportal_port_res_first_button_two = "Create an Open Map <span class='fas fa-external-link-alt'></span>";
		if (array_key_exists('geopportal_port_res_first_link_two', $instance) && isset($instance['geopportal_port_res_first_link_two']) && !empty($instance['geopportal_port_res_first_link_two']))
      $geopportal_port_res_first_link_two = apply_filters('widget_title', $instance['geopportal_port_res_first_link_two']);
		else
      $geopportal_port_res_first_link_two = "";

		if (array_key_exists('geopportal_port_res_second_title', $instance) && isset($instance['geopportal_port_res_second_title']) && !empty($instance['geopportal_port_res_second_title']))
      $geopportal_port_res_second_title = apply_filters('widget_title', $instance['geopportal_port_res_second_title']);
		else
      $geopportal_port_res_second_title = "Data";
		if (array_key_exists('geopportal_port_res_second_content', $instance) && isset($instance['geopportal_port_res_second_content']) && !empty($instance['geopportal_port_res_second_content']))
      $geopportal_port_res_second_content = apply_filters('widget_title', $instance['geopportal_port_res_second_content']);
		else
      $geopportal_port_res_second_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_second_button_one', $instance) && isset($instance['geopportal_port_res_second_button_one']) && !empty($instance['geopportal_port_res_second_button_one']))
      $geopportal_port_res_second_button_one = apply_filters('widget_title', $instance['geopportal_port_res_second_button_one']);
		else
      $geopportal_port_res_second_button_one = "Explore Datasets";
		if (array_key_exists('geopportal_port_res_second_link_one', $instance) && isset($instance['geopportal_port_res_second_link_one']) && !empty($instance['geopportal_port_res_second_link_one']))
      $geopportal_port_res_second_link_one = apply_filters('widget_title', $instance['geopportal_port_res_second_link_one']);
		else
      $geopportal_port_res_second_link_one = "";
		if (array_key_exists('geopportal_port_res_second_button_two', $instance) && isset($instance['geopportal_port_res_second_button_two']) && !empty($instance['geopportal_port_res_second_button_two']))
      $geopportal_port_res_second_button_two = apply_filters('widget_title', $instance['geopportal_port_res_second_button_two']);
		else
      $geopportal_port_res_second_button_two = "Register a Dataset";
		if (array_key_exists('geopportal_port_res_second_link_two', $instance) && isset($instance['geopportal_port_res_second_link_two']) && !empty($instance['geopportal_port_res_second_link_two']))
      $geopportal_port_res_second_link_two = apply_filters('widget_title', $instance['geopportal_port_res_second_link_two']);
		else
      $geopportal_port_res_second_link_two = "";

		if (array_key_exists('geopportal_port_res_third_title', $instance) && isset($instance['geopportal_port_res_third_title']) && !empty($instance['geopportal_port_res_third_title']))
      $geopportal_port_res_third_title = apply_filters('widget_title', $instance['geopportal_port_res_third_title']);
		else
      $geopportal_port_res_third_title = "Data Services";
		if (array_key_exists('geopportal_port_res_third_content', $instance) && isset($instance['geopportal_port_res_third_content']) && !empty($instance['geopportal_port_res_third_content']))
      $geopportal_port_res_third_content = apply_filters('widget_title', $instance['geopportal_port_res_third_content']);
		else
      $geopportal_port_res_third_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_third_button_one', $instance) && isset($instance['geopportal_port_res_third_button_one']) && !empty($instance['geopportal_port_res_third_button_one']))
      $geopportal_port_res_third_button_one = apply_filters('widget_title', $instance['geopportal_port_res_third_button_one']);
		else
      $geopportal_port_res_third_button_one = "Explore Data Services";
		if (array_key_exists('geopportal_port_res_third_link_one', $instance) && isset($instance['geopportal_port_res_third_link_one']) && !empty($instance['geopportal_port_res_third_link_one']))
      $geopportal_port_res_third_link_one = apply_filters('widget_title', $instance['geopportal_port_res_third_link_one']);
		else
      $geopportal_port_res_third_link_one = "";
		if (array_key_exists('geopportal_port_res_third_button_two', $instance) && isset($instance['geopportal_port_res_third_button_two']) && !empty($instance['geopportal_port_res_third_button_two']))
      $geopportal_port_res_third_button_two = apply_filters('widget_title', $instance['geopportal_port_res_third_button_two']);
		else
      $geopportal_port_res_third_button_two = "Register a Service";
		if (array_key_exists('geopportal_port_res_third_link_two', $instance) && isset($instance['geopportal_port_res_third_link_two']) && !empty($instance['geopportal_port_res_third_link_two']))
      $geopportal_port_res_third_link_two = apply_filters('widget_title', $instance['geopportal_port_res_third_link_two']);
		else
      $geopportal_port_res_third_link_two = "";

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
      $geopportal_port_res_fourth_button_one = "Explore Galleries";
		if (array_key_exists('geopportal_port_res_fourth_link_one', $instance) && isset($instance['geopportal_port_res_fourth_link_one']) && !empty($instance['geopportal_port_res_fourth_link_one']))
      $geopportal_port_res_fourth_link_one = apply_filters('widget_title', $instance['geopportal_port_res_fourth_link_one']);
		else
      $geopportal_port_res_fourth_link_one = "";
		if (array_key_exists('geopportal_port_res_fourth_button_two', $instance) && isset($instance['geopportal_port_res_fourth_button_two']) && !empty($instance['geopportal_port_res_fourth_button_two']))
      $geopportal_port_res_fourth_button_two = apply_filters('widget_title', $instance['geopportal_port_res_fourth_button_two']);
		else
      $geopportal_port_res_fourth_button_two = "Create a Gallery <span class='fas fa-external-link-alt'></span>";
		if (array_key_exists('geopportal_port_res_fourth_link_two', $instance) && isset($instance['geopportal_port_res_fourth_link_two']) && !empty($instance['geopportal_port_res_fourth_link_two']))
      $geopportal_port_res_fourth_link_two = apply_filters('widget_title', $instance['geopportal_port_res_fourth_link_two']);
		else
      $geopportal_port_res_fourth_link_two = "";

		if (array_key_exists('geopportal_port_res_fifth_title', $instance) && isset($instance['geopportal_port_res_fifth_title']) && !empty($instance['geopportal_port_res_fifth_title']))
      $geopportal_port_res_fifth_title = apply_filters('widget_title', $instance['geopportal_port_res_fifth_title']);
		else
      $geopportal_port_res_fifth_title = "Galleries";
		if (array_key_exists('geopportal_port_res_fifth_content', $instance) && isset($instance['geopportal_port_res_fifth_content']) && !empty($instance['geopportal_port_res_fifth_content']))
      $geopportal_port_res_fifth_content = apply_filters('widget_title', $instance['geopportal_port_res_fifth_content']);
		else
      $geopportal_port_res_fifth_content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ";
		if (array_key_exists('geopportal_port_res_fifth_button_one', $instance) && isset($instance['geopportal_port_res_fifth_button_one']) && !empty($instance['geopportal_port_res_fifth_button_one']))
      $geopportal_port_res_fifth_button_one = apply_filters('widget_title', $instance['geopportal_port_res_fifth_button_one']);
		else
      $geopportal_port_res_fifth_button_one = "Explore Communities";
		if (array_key_exists('geopportal_port_res_fifth_link_one', $instance) && isset($instance['geopportal_port_res_fifth_link_one']) && !empty($instance['geopportal_port_res_fifth_link_one']))
      $geopportal_port_res_fifth_link_one = apply_filters('widget_title', $instance['geopportal_port_res_fifth_link_one']);
		else
      $geopportal_port_res_fifth_link_one = "";
		if (array_key_exists('geopportal_port_res_fifth_button_two', $instance) && isset($instance['geopportal_port_res_fifth_button_two']) && !empty($instance['geopportal_port_res_fifth_button_two']))
      $geopportal_port_res_fifth_button_two = apply_filters('widget_title', $instance['geopportal_port_res_fifth_button_two']);
		else
      $geopportal_port_res_fifth_button_two = "";
		if (array_key_exists('geopportal_port_res_fifth_link_two', $instance) && isset($instance['geopportal_port_res_fifth_link_two']) && !empty($instance['geopportal_port_res_fifth_link_two']))
      $geopportal_port_res_fifth_link_two = apply_filters('widget_title', $instance['geopportal_port_res_fifth_link_two']);
		else
      $geopportal_port_res_fifth_link_two = "";

		?>


		<!--
		PORTFOLIO RESOURCES SECTION
		-->


			<div class="p-landing-page__portfolio-wrapper t-light" style="background-image:url('<?php echo get_stylesheet_directory_uri() . '/img/wave-blue.svg' ?>')">

		<!-- <div class="p-landing-page__portfolio-wrapper t-light"> -->

		    <div class="p-landing-page__portfolio carousel slide" data-ride="carousel" data-interval="false" id="portfolioCarousel">

		        <ol class="carousel-indicators">
		            <li data-target="#portfolioCarousel" data-slide-to="0" class="active" title="<?php echo sanitize_text_field($geopportal_port_res_first_title) ?>"></li>
		            <li data-target="#portfolioCarousel" data-slide-to="1" title="<?php echo sanitize_text_field($geopportal_port_res_second_title) ?>"></li>
		            <li data-target="#portfolioCarousel" data-slide-to="2" title="<?php echo sanitize_text_field($geopportal_port_res_third_title) ?>"></li>
		            <li data-target="#portfolioCarousel" data-slide-to="3" title="<?php echo sanitize_text_field($geopportal_port_res_fourth_title) ?>"></li>
		            <li data-target="#portfolioCarousel" data-slide-to="4" title="<?php echo sanitize_text_field($geopportal_port_res_fifth_title) ?>"></li>
		        </ol>

		        <div class="m-article__heading m-article__heading--front-page"><?php echo sanitize_text_field($geopportal_port_res_main_title) ?></div>

		        <div class="carousel-inner">

		            <div class="carousel-item active">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_first_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/map.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_first_title) ?></div>
		                        <div class="a-summary">
															<?php echo do_shortcode($geopportal_port_res_first_content) ?>
		                        </div>
		                        <br>
		                        <div class="l-flex-container flex-justify-between">
		                          <button type="button" class="btn btn--primary" href="<?php echo esc_url($geopportal_port_res_first_link_one) ?>"><?php echo sanitize_text_field($geopportal_port_res_first_button_one) ?></button>
															<?php
															if (!empty($geopportal_port_res_first_button_two)){
															?>
		                            <button type="button" class="btn btn--secondary" href="<?php echo esc_url($geopportal_port_res_first_link_two) ?>">
		                                <?php echo sanitize_text_field($geopportal_port_res_first_button_two) ?>
		                                <span class="fas fa-external-link-alt"></span>
		                            </button>
															<?php
															}
															?>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <div class="carousel-item">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_second_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/data.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_second_title) ?></div>
		                        <div class="a-summary">
		                          <?php echo do_shortcode($geopportal_port_res_second_content) ?>
		                        </div>
		                        <br>
		                        <div class="l-flex-container flex-justify-between">
		                           <button type="button" class="btn btn--primary" href="<?php echo esc_url($geopportal_port_res_second_link_one) ?>"><?php echo sanitize_text_field($geopportal_port_res_second_button_one) ?></button>
															<?php
															if (!empty($geopportal_port_res_second_button_two)){
															?>
		                            <a class="btn btn--secondary" href="<?php echo esc_url($geopportal_port_res_second_link_two) ?>"><?php echo sanitize_text_field($geopportal_port_res_second_button_two) ?></a>
															<?php
															}
															?>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <div class="carousel-item">
		                <div class="p-landing-page__portfolio__pane">
		                    <img class="thumbnail" alt="<?php echo sanitize_text_field($geopportal_port_res_third_title) ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/service.svg' ?>">
		                    <div>
		                        <div class="a-heading"><?php echo sanitize_text_field($geopportal_port_res_third_title) ?></div>
		                        <div class="a-summary">
															<?php echo do_shortcode($geopportal_port_res_third_content) ?>
		                        </div>
		                        <br>
		                        <div class="l-flex-container flex-justify-between">
	                            <button type="button" class="btn btn--primary" href="<?php echo esc_url($geopportal_port_res_third_link_one) ?>"><?php echo sanitize_text_field($geopportal_port_res_third_button_one) ?></button>
															<?php
															if (!empty($geopportal_port_res_third_button_two)){
															?>
		                            <a class="btn btn--secondary" href="<?php echo esc_url($geopportal_port_res_third_link_two) ?>"><?php echo sanitize_text_field($geopportal_port_res_third_button_two) ?></a>
															<?php
															}
															?>
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
		                        <div class="l-flex-container flex-justify-between">
	                            <button type="button" class="btn btn--primary" href="<?php echo esc_url($geopportal_port_res_fourth_link_one) ?>"><?php echo sanitize_text_field($geopportal_port_res_fourth_button_one) ?></button>
															<?php
															if (!empty($geopportal_port_res_fourth_button_two)){
															?>
		                            <a class="btn btn--secondary" href="<?php echo esc_url($geopportal_port_res_fourth_link_two) ?>"><?php echo sanitize_text_field($geopportal_port_res_fourth_button_two) ?></a>
															<?php
															}
															?>
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
		                        <div class="l-flex-container flex-justify-between">
	                            <button type="button" class="btn btn--primary" href="<?php echo esc_url($geopportal_port_res_fifth_link_one) ?>"><?php echo sanitize_text_field($geopportal_port_res_fifth_button_one) ?></button>
															<?php
															if (!empty($geopportal_port_res_fifth_button_two)){
															?>
		                            <a class="btn btn--secondary" href="<?php echo esc_url($geopportal_port_res_fifth_link_two) ?>"><?php echo sanitize_text_field($geopportal_port_res_fifth_button_two) ?></a>
															<?php
															}
															?>
		                        </div>
		                    </div>
		                </div>
		            </div>

		        </div>

		        <a class="carousel-control-prev" href="#portfolioCarousel" role="button" data-slide="prev">
		            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		            <span class="sr-only">Previous</span>
		        </a>

		        <a class="carousel-control-next" href="#portfolioCarousel" role="button" data-slide="next">
		            <span class="carousel-control-next-icon" aria-hidden="true"></span>
		            <span class="sr-only">Next</span>
		        </a>

		    </div>
		</div>

<!--
				<script type="text/javascript">
					jQuery(document).ready(function() {
				    jQuery("#datasets-tab").click(function(e){
							jQuery("#pr-ov-maps").removeClass("show active");
							jQuery("#maps-tab").removeClass("active");

							jQuery("#pr-ov-datasets").addClass("show active");
							jQuery("#datasets-tab").addClass("active");
							jQuery("#pr-ov-datasets").fadeIn();

							jQuery("#pr-ov-services").removeClass("show active");
							jQuery("#services-tab").removeClass("active");

							jQuery("#pr-ov-galleries").removeClass("show active");
							jQuery("#galleries-tab").removeClass("active");
						});
						jQuery("#maps-tab").click(function(e){
							jQuery("#pr-ov-maps").addClass("show active");
							jQuery("#maps-tab").addClass("active");
							jQuery("#pr-ov-maps").fadeIn();

							jQuery("#pr-ov-datasets").removeClass("show active");
							jQuery("#datasets-tab").removeClass("active");

							jQuery("#pr-ov-services").removeClass("show active");
							jQuery("#services-tab").removeClass("active");

							jQuery("#pr-ov-galleries").removeClass("show active");
							jQuery("#galleries-tab").removeClass("active");
						});
						jQuery("#services-tab").click(function(e){
							jQuery("#pr-ov-maps").removeClass("show active");
							jQuery("#maps-tab").removeClass("active");

							jQuery("#pr-ov-datasets").removeClass("show active");
							jQuery("#datasets-tab").removeClass("active");

							jQuery("#pr-ov-services").addClass("show active");
							jQuery("#services-tab").addClass("active");

							jQuery("#pr-ov-galleries").removeClass("show active");
							jQuery("#galleries-tab").removeClass("active");
						});
						jQuery("#galleries-tab").click(function(e){
							jQuery("#pr-ov-maps").removeClass("show active");
							jQuery("#maps-tab").removeClass("active");

							jQuery("#pr-ov-datasets").removeClass("show active");
							jQuery("#datasets-tab").removeClass("active");

							jQuery("#pr-ov-services").removeClass("show active");
							jQuery("#services-tab").removeClass("active");

							jQuery("#pr-ov-galleries").addClass("show active");
							jQuery("#galleries-tab").addClass("active");
						});
			    });
				</script> -->



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
		// $geopportal_port_res_color = ! empty( $instance['geopportal_port_res_color'] ) ? $instance['geopportal_port_res_color'] : 'true';

		// First input boxes.
		$geopportal_port_res_first_title = ! empty( $instance['geopportal_port_res_first_title'] ) ? $instance['geopportal_port_res_first_title'] : 'Open Maps';
		$geopportal_port_res_first_content = ! empty( $instance['geopportal_port_res_first_content'] ) ? $instance['geopportal_port_res_first_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_first_button_one = ! empty( $instance['geopportal_port_res_first_button_one'] ) ? $instance['geopportal_port_res_first_button_one'] : 'Explore Open Maps';
		$geopportal_port_res_first_button_two = ! empty( $instance['geopportal_port_res_first_button_two'] ) ? $instance['geopportal_port_res_first_button_two'] : "Create an Open Map <span class='fas fa-external-link-alt'></span>";
		$geopportal_port_res_first_link_one = ! empty( $instance['geopportal_port_res_first_link_one'] ) ? $instance['geopportal_port_res_first_link_one'] : '';
		$geopportal_port_res_first_link_two = ! empty( $instance['geopportal_port_res_first_link_two'] ) ? $instance['geopportal_port_res_first_link_two'] : '';

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
		$geopportal_port_res_second_title = ! empty( $instance['geopportal_port_res_second_title'] ) ? $instance['geopportal_port_res_second_title'] : 'Data';
		$geopportal_port_res_second_content = ! empty( $instance['geopportal_port_res_second_content'] ) ? $instance['geopportal_port_res_second_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_second_button_one = ! empty( $instance['geopportal_port_res_second_button_one'] ) ? $instance['geopportal_port_res_second_button_one'] : 'Explore Datasets';
		$geopportal_port_res_second_button_two = ! empty( $instance['geopportal_port_res_second_button_two'] ) ? $instance['geopportal_port_res_second_button_two'] : "Register a Dataset";
		$geopportal_port_res_second_link_one = ! empty( $instance['geopportal_port_res_second_link_one'] ) ? $instance['geopportal_port_res_second_link_one'] : '';
		$geopportal_port_res_second_link_two = ! empty( $instance['geopportal_port_res_second_link_two'] ) ? $instance['geopportal_port_res_second_link_two'] : '';

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
		$geopportal_port_res_third_title = ! empty( $instance['geopportal_port_res_third_title'] ) ? $instance['geopportal_port_res_third_title'] : 'Data Services';
		$geopportal_port_res_third_content = ! empty( $instance['geopportal_port_res_third_content'] ) ? $instance['geopportal_port_res_third_content'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
		$geopportal_port_res_third_button_one = ! empty( $instance['geopportal_port_res_third_button_one'] ) ? $instance['geopportal_port_res_third_button_one'] : 'Explore Data Services';
		$geopportal_port_res_third_button_two = ! empty( $instance['geopportal_port_res_third_button_two'] ) ? $instance['geopportal_port_res_third_button_two'] : "Register a Service";
		$geopportal_port_res_third_link_one = ! empty( $instance['geopportal_port_res_third_link_one'] ) ? $instance['geopportal_port_res_third_link_one'] : '';
		$geopportal_port_res_third_link_two = ! empty( $instance['geopportal_port_res_third_link_two'] ) ? $instance['geopportal_port_res_third_link_two'] : '';

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
		$geopportal_port_res_fourth_button_one = ! empty( $instance['geopportal_port_res_fourth_button_one'] ) ? $instance['geopportal_port_res_fourth_button_one'] : 'Explore Galleries';
		$geopportal_port_res_fourth_button_two = ! empty( $instance['geopportal_port_res_fourth_button_two'] ) ? $instance['geopportal_port_res_fourth_button_two'] : "Create a Gallery <span class='fas fa-external-link-alt'></span>";
		$geopportal_port_res_fourth_link_one = ! empty( $instance['geopportal_port_res_fourth_link_one'] ) ? $instance['geopportal_port_res_fourth_link_one'] : '';
		$geopportal_port_res_fourth_link_two = ! empty( $instance['geopportal_port_res_fourth_link_two'] ) ? $instance['geopportal_port_res_fourth_link_two'] : '';

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
		$geopportal_port_res_fifth_button_one = ! empty( $instance['geopportal_port_res_fifth_button_one'] ) ? $instance['geopportal_port_res_fifth_button_one'] : 'Explore Communities';
		$geopportal_port_res_fifth_button_two = ! empty( $instance['geopportal_port_res_fifth_button_two'] ) ? $instance['geopportal_port_res_fifth_button_two'] : '';
		$geopportal_port_res_fifth_link_one = ! empty( $instance['geopportal_port_res_fifth_link_one'] ) ? $instance['geopportal_port_res_fifth_link_one'] : '';
		$geopportal_port_res_fifth_link_two = ! empty( $instance['geopportal_port_res_fifth_link_two'] ) ? $instance['geopportal_port_res_fifth_link_two'] : '';

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
		<!-- <p>
      <label for="<?php //echo $this->get_field_id( 'geopportal_port_res_color' ); ?>">Widget Color:</label>
			<input class="checkbox" type="checkbox" <?php //checked( $instance[ 'geopportal_port_res_color' ], 'on' ); ?> id="<?php //echo $this->get_field_id( 'geopportal_port_res_color' ); ?>" name="<?php //echo $this->get_field_name( 'geopportal_port_res_color' ); ?>" />
		</p> -->
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
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_button_one' ); ?>">First Button #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_first_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_first_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_link_one' ); ?>">First Hotlink #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_first_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_first_link_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_button_two' ); ?>">First Button #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_first_button_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_button_two' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_first_button_two ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_first_link_two' ); ?>">First Hotlink #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_first_link_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_first_link_two' ); ?>" value="<?php echo esc_url( $geopportal_port_res_first_link_two ); ?>" />
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
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_button_one' ); ?>">Second Button #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_second_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_second_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_link_one' ); ?>">Second Hotlink #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_second_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_second_link_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_button_two' ); ?>">Second Button #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_second_button_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_button_two' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_second_button_two ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_second_link_two' ); ?>">Second Hotlink #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_second_link_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_second_link_two' ); ?>" value="<?php echo esc_url( $geopportal_port_res_second_link_two ); ?>" />
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
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_button_one' ); ?>">Third Button #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_third_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_third_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_link_one' ); ?>">Third Hotlink #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_third_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_third_link_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_button_two' ); ?>">Third Button #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_third_button_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_button_two' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_third_button_two ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_third_link_two' ); ?>">Third Hotlink #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_third_link_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_third_link_two' ); ?>" value="<?php echo esc_url( $geopportal_port_res_third_link_two ); ?>" />
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
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_button_one' ); ?>">Fourth Button #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fourth_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_link_one' ); ?>">Fourth Hotlink #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_fourth_link_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_button_two' ); ?>">Fourth Button #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_button_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_button_two' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fourth_button_two ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_link_two' ); ?>">Fourth Hotlink #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fourth_link_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fourth_link_two' ); ?>" value="<?php echo esc_url( $geopportal_port_res_fourth_link_two ); ?>" />
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
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_button_one' ); ?>">Fifth Button #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_button_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_button_one' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fifth_button_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_link_one' ); ?>">Fifth Hotlink #1:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_link_one' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_link_one' ); ?>" value="<?php echo esc_url( $geopportal_port_res_fifth_link_one ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_button_two' ); ?>">Fifth Button #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_button_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_button_two' ); ?>" value="<?php echo esc_attr( $geopportal_port_res_fifth_button_two ); ?>" />
    </p>
		<p>
      <label for="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_link_two' ); ?>">Fifth Hotlink #2:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'geopportal_port_res_fifth_link_two' ); ?>" name="<?php echo $this->get_field_name( 'geopportal_port_res_fifth_link_two' ); ?>" value="<?php echo esc_url( $geopportal_port_res_fifth_link_two ); ?>" />
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
		$instance[ 'geopportal_port_res_first_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_first_link_one' ] );
		$instance[ 'geopportal_port_res_first_link_two' ] = strip_tags( $new_instance[ 'geopportal_port_res_first_link_two' ] );
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
		$instance[ 'geopportal_port_res_second_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_second_link_one' ] );
		$instance[ 'geopportal_port_res_second_link_two' ] = strip_tags( $new_instance[ 'geopportal_port_res_second_link_two' ] );
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
		$instance[ 'geopportal_port_res_third_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_third_link_one' ] );
		$instance[ 'geopportal_port_res_third_link_two' ] = strip_tags( $new_instance[ 'geopportal_port_res_third_link_two' ] );
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
		$instance[ 'geopportal_port_res_fourth_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_fourth_link_one' ] );
		$instance[ 'geopportal_port_res_fourth_link_two' ] = strip_tags( $new_instance[ 'geopportal_port_res_fourth_link_two' ] );
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
		$instance[ 'geopportal_port_res_fifth_link_one' ] = strip_tags( $new_instance[ 'geopportal_port_res_fifth_link_one' ] );
		$instance[ 'geopportal_port_res_fifth_link_two' ] = strip_tags( $new_instance[ 'geopportal_port_res_fifth_link_two' ] );
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
